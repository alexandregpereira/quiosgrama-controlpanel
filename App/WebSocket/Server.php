<?php

/**
 * @author Jean Souza
 */

namespace App\WebSocket;
$systemPath = getenv('QUIOSGRAMA_SYSTEM_PATH');
require_once $systemPath . 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use App\WebSocket\SocketsManager;

$socketsManager = new SocketsManager();

$webServer = IoServer::factory(
  new HttpServer(
    new WsServer($socketsManager)
  ),
  getenv('QUIOSGRAMA_SOCKET_PORT')
);

$loop = $webServer->loop;

$loop->addPeriodicTimer(0.50, function() use($socketsManager) {
  $socketsManager->load();
});

$webServer->run();
