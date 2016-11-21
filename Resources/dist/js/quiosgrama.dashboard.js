
/************************************
** BEGIN - Modal control attributes
*************************************/

var tableOpened = null;
var statusTableOpened = null;

/************************************
** END - Modal control attributes
*************************************/

var processDashboardSocketData = function(json) {
  for(var i = 0; i < json.methods.length; i++) {
    eval(json.methods[i] + "('" + JSON.stringify(json.values[i]) + "')");
  }

  updateModal();
};


/************************************
** BEGIN - Modal methods
*************************************/

function showModal(table, status) {

  tableOpened = table;
  statusTableOpened = status;

  var flagClosed = false;

  if(status == "alert") flagClosed = true;

  document.getElementById('requestsBox').innerHTML = modalValues['requestsTable' + table];
  document.getElementById('productsBox').innerHTML = modalValues['productsTable' + table] + modalValues['total' + table];

  document.getElementById("closeBtn").setAttribute("onclick", "closeTable(" + table + ", " + flagClosed + ")");
  document.getElementById("getawayBtn").setAttribute("onclick", "setGetaway(" + table + ", " + flagClosed + ")");

  $('#tablesModal').modal('show');
}

function updateModal() {

  var flagClosed = false;

  if(statusTableOpened == "alert") flagClosed = true;

  document.getElementById('requestsBox').innerHTML = modalValues['requestsTable' + tableOpened];
  document.getElementById('productsBox').innerHTML = modalValues['productsTable' + tableOpened] + modalValues['total' + tableOpened];

  document.getElementById("closeBtn").setAttribute("onclick", "closeTable(" + tableOpened + ", " + flagClosed + ")");
  document.getElementById("getawayBtn").setAttribute("onclick", "setGetaway(" + tableOpened + ", " + flagClosed + ")");
}

/************************************
** END - Modal methods
*************************************/

/************************************
** BEGIN - Passive socket methods
*************************************/

function closeTable(number, closed) {
  var json = JSON.stringify(
    {
      "key": "Dashboard",
      "method": "closeTable",
      "value": {
        "bill": modalValues["bill" + number],
        "closed": closed
      }
    }
  );

  sendMessageToSocket(json);
}

function setGetaway(number, closed) {
  var json = JSON.stringify(
    {
      "key": "Dashboard",
      "method": "setGetaway",
      "value": {
        "bill": modalValues["bill" + number],
        "closed": closed
      }
    }
  );

  sendMessageToSocket(json);
}

function invalidateItem(productRequest) {
  var json = JSON.stringify(
    {
      "key": "Dashboard",
      "method": "invalidateItem",
      "value": productRequest
    }
  );

  sendMessageToSocket(json);
}

function validateItem(productRequest) {
  var json = JSON.stringify(
    {
      "key": "Dashboard",
      "method": "validateItem",
      "value": productRequest
    }
  );

  sendMessageToSocket(json);
}

/************************************
** END - Passive socket methods
*************************************/

/************************************
** BEGIN - Active socket methods
*************************************/

function updateTables(values) {

  var json = JSON.parse(values);

  var tablesContainer = document.getElementById('ulTables');
  var openNumber = document.getElementById('openNumber');
  var closeNumber = document.getElementById('closeNumber');
  var alertNumber = document.getElementById('alertNumber');
  var totalNumber = document.getElementById('totalNumber');

  var html = "";

  var qtdOpen = 0;
  var qtdClose = 0;
  var qtdAlert = 0;

  for(var i = 0; i < json.length; i++) {

    modalValues["bill" + json[i].number] = json[i].bill;

    var statusTable = "closed";

    var openTime = new Date(json[i].open_time);
    var openTimeString = openTime.getFullYear() + "-" + ("0" + (openTime.getMonth() + 1)).substr(-2) + "-" + ("0" + openTime.getDate()).substr(-2);

    var currentTime = new Date();
    var currentTimeString = currentTime.getFullYear() + "-" + ("0" + (currentTime.getMonth() + 1)).substr(-2) + "-" + ("0" + currentTime.getDate()).substr(-2);

    if(json[i].open_time == null || openTimeString != currentTimeString) {
      statusTable = "closed";
      qtdClose++;
    } else {
      if(json[i].close_time == null) {
        statusTable = "open";
        qtdOpen++;
      } else {
        if(json[i].paid_time == null) {
          statusTable = "alert";
          qtdAlert++;
        } else {
          statusTable = "closed";
          qtdClose++;
        }
      }
    }

    html += "<li class=\"quios-table-" + statusTable + "\"";

    if(statusTable == "open" || statusTable == "alert") {
      html += " onclick=\"showModal(" + json[i].number + ", '" + statusTable + "');\"";
    }

    html += ">";
    html += "<h1 style=\"font-size: 15px;\"><b>" + json[i].number + "</b></h1>";
    html += "</li>";
  }

  tablesContainer.innerHTML = html;

  openNumber.innerHTML = qtdOpen;
  closeNumber.innerHTML = qtdClose;
  alertNumber.innerHTML = qtdAlert;
  totalNumber.innerHTML = qtdOpen + qtdClose + qtdAlert;
}

function updateRequests(values) {

  var json = JSON.parse(values);

  var html = "";
  var table = null;
  var tableFlag = false;

  for(var i = 0; i < json.length; i++) {
    for(var j = 0; j < json[i].length; j++) {

      var time = new Date(json[i][j].time);
      var timeString = ("0" + time.getDate()).substr(-2) + "/" + ("0" + (time.getMonth() + 1)).substr(-2) + "/" + time.getFullYear() + " - " + time.getHours() + "h" + time.getMinutes();

      html += "<div class=\"quios-bill-item\">";
      html += "<span class=\"quios-left\">" + json[i][j].functionary + "</span>";
      html += "<span class=\"quios-right\">" + timeString + "</span>";
      html += "<div class=\"quios-clear\"></div>";
      html += "<span class=\"quios-left\" style=\"font-size: 12px; margin-left: 10px; color: #909090;\">" + json[i][j].products + "</span>";
      html += "<div class=\"quios-clear\"></div>";
      html += "</div>";

      table = json[i][j].table_number;
      tableFlag = true;
    }

    if(tableFlag) {
      modalValues['requestsTable' + table] = html;
      tableFlag = false;
    }

    html = "";
  }
}

function updateProducts(values) {

  var json = JSON.parse(values);

  var html = "";
  var table = null;
  var tableFlag = false;

  for(var i = 0; i < json.length; i++) {
    for(var j = 0; j < json[i].length; j++) {
      if(json[i][j].valid == 1) {
        html += "<div class=\"quios-bill-item\"><button type=\"button\" class=\"close quios-close-btn\" data-dismiss=\"alert\" aria-hidden=\"true\" onclick=\"invalidateItem(" + json[i][j].product_request + ")\">×</button>";
      } else {
        html += "<div class=\"quios-bill-item quios-bill-item-invalid\"><button type=\"button\" class=\"close quios-valid-btn\" data-dismiss=\"alert\" aria-hidden=\"true\" onclick=\"validateItem(" + json[i][j].product_request + ")\">✓</button>";
      }

      html += "<div class=\"quios-clear\"></div>";
      html += "<div class=\"col-md-4\">";
      html += "<span class=\"quios-left\">" + json[i][j].quantity + "X " + json[i][j].product + "</span>";
      html += "</div>";
      html += "<div class=\"col-md-4\">";
      html += "<span class=\"quios-right\">R$ " + formatReal((1 * json[i][j].price)) + "</span>";
      html += "</div>";
      html += "<div class=\"col-md-4\">";
      html += "<span class=\"quios-right\">R$ " + formatReal((json[i][j].quantity * json[i][j].price)) + "</span>";
      html += "</div>";
      html += (json[i][j].complement !== null && json[i][j].complement !== "") ? "<div class=\"quios-clear\"></div><span style=\"font-size: 12px; margin-left: 30px; color: #909090;\">" + json[i][j].complement + "</span>" : "";
      html += "<div class=\"quios-clear\"></div>";
      html += "</div>";

      table = json[i][j].table_number;
      tableFlag = true;
    }

    if(tableFlag) {
      modalValues['productsTable' + table] = html;
      tableFlag = false;
    }

    html = "";
  }
}

function updateTotals(values) {
  var json = JSON.parse(values);

  for(var i = 0; i < json.length; i++) {
    for(var j = 0; j < json[i].length; j++) {
      modalValues['total' + json[i][j].table_number] = "<div class=\"total-value\"><br/><div class=\"col-md-8\">Subtotal:</div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " + formatReal(json[i][j].total) + "</span></div><div class=\"quios-clear\"></div><div class=\"col-md-8\">Servi&ccedil;o:</div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " + formatReal(json[i][j].total * 0.1) + "</span></div><div class=\"quios-clear\"></div><div class=\"col-md-8\">Total:</div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " + formatReal((json[i][j].total * 1) + (json[i][j].total * 0.1)) + "</span></div><div class=\"quios-clear\"></div></div>";
    }
  }
}

/************************************
** END - Active socket methods
*************************************/
