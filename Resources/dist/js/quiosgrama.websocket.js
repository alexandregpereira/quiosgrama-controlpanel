var socket;
var host = "ws://" + socketAddress + ":" + socketPort;

function init() {
  try {
    socket = new WebSocket(host);

    socket.onopen = function(msg) {};

    socket.onmessage = function(msg) {
      var json = JSON.parse(msg.data);

      if(json.key === 'Dashboard') {
        if(typeof processDashboardSocketData !== 'undefined') processDashboardSocketData(json);
      } else {
        if(typeof processRequestsPaneSocketData !== 'undefined') processRequestsPaneSocketData(json);
      }
    };

    socket.onclose = function(msg) {};
  } catch(ex) {
    alert("falhou");
  }
}

init();

function quit() {
  if(socket != null) {
    console.log("Goodbye!");
    socket.close();
    socket = null;
  }
}

function reconnect() {
  quit();
  init();
}

function sendMessageToSocket(message) {
  try {
    socket.send(message);
  } catch(ex) {}
}
