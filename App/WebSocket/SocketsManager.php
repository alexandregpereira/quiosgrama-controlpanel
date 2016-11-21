<?php

/**
 * @author Jean Souza
 */

namespace App\WebSocket;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SocketsManager implements MessageComponentInterface {

  protected $clients;
  private $quiosgramaSockets = array();

  public function __construct() {
    $this->clients = new \SplObjectStorage;

    $sockets = json_decode(file_get_contents("Sockets.json", true), true);

    foreach($sockets["Sockets"] as $key => $value)
      array_push($this->quiosgramaSockets, new $value['Name']());
  }

  public function onOpen(ConnectionInterface $conn) {
    $this->clients->attach($conn);

    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $json = json_decode($msg, true);

    foreach($this->quiosgramaSockets as $socket) {
      $classnameArray = explode('\\', get_class($socket));
      if(end($classnameArray) === $json['key']) {
        $method = $json['method'];
        $value = $json['value'];
        $return = $socket->$method($value);

        if(!is_null($return) && !empty($return)) {
          $this->sendMessageToSocket(
            $from,
            array(
              "methods" => array($json['method'] . "Response"),
              "values" => array($return),
              "key" => $json['key']
            )
          );
        }

        break;
      }
    }
  }

  public function onClose(ConnectionInterface $conn) {
    $this->clients->detach($conn);

    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
    echo "An error has occurred: {$e->getMessage()}\n";

    $conn->close();
  }

  private function sendMessage($message) {
    foreach ($this->clients as $client)
      $client->send(json_encode($message));
  }

  private function sendMessageToSocket($socket, $message) {
    $socket->send(json_encode($message));
  }

  public function load() {
    foreach($this->quiosgramaSockets as $socket) {
      $loadReturn = $socket->load();
      if(!empty($loadReturn)) $this->sendMessage($loadReturn);
    }
  }
}
