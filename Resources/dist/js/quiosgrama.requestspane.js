var processRequestsPaneSocketData = function(json) {
  for(var i = 0; i < json.methods.length; i++) {
    eval(json.methods[i] + "('" + JSON.stringify(json.values[i]) + "')");
  }
};

function updateRequestsPane(values) {
  var json = JSON.parse(values);

  var html = "";

  for(var i = 0; i < json[0].length; i++) {
    var color = "red";

    if(json[0][i].diff_minutes < 30) color = "green";
    else if(json[0][i].diff_minutes >= 30 && json[0][i].diff_minutes < 60) color = "yellow";

    html += "<li><a href=\"#\" class=\"requestItem\" requestId=\"" + json[0][i].request + "\"><i class=\"fa fa-cutlery text-" + color + "\"></i> Mesa " + json[0][i].table_number + " - ";
    html += json[0][i].products + " " + ((json[0][i].products) > 1 ? "itens" : "item") + "</a></li>";
  }

  var htmlAllRequests = "";

  if(json[1].length > 0) {
    for(var i = 0; i < json[1].length; i++) {
      htmlAllRequests += "<tr>";
      htmlAllRequests += "<td>" + json[1][i].table_number + "</td>";
      htmlAllRequests += "<td>" + json[1][i].products + "</td>";
      htmlAllRequests += "<td><button type=\"button\" class=\"btn btn-primary requestItem\" requestId=\"" + json[1][i].request + "\">Detalhes</button></td>";
      htmlAllRequests += "</tr>";
    }
  } else {
    htmlAllRequests += "<tr>";
    htmlAllRequests += "<td colspan=\"3\" class=\"nenhum\">Nenhum item encontrado.</td>";
    htmlAllRequests += "</tr>";
  }

  var innerQtdRequestsPane = '';

  if(json[2][0].count === '0') innerQtdRequestsPane = "Não existem novos pedidos";
  else if(json[2][0].count === '1') innerQtdRequestsPane = "Você tem 1 novo pedido";
  else innerQtdRequestsPane = "Você tem " + json[2][0].count + " novos pedidos";

  $('#innerQtdRequestsPane').text(innerQtdRequestsPane);
  $('#qtdRequestsPane').text(json[2][0].count);
  $('#newRequestsPane').empty().append(html);
  $('#allRequestModalTableTbody').empty().append(htmlAllRequests);
}

function getRequest(id) {
  var json = JSON.stringify(
    {
      "key": "RequestsPane",
      "method": "getRequest",
      "value": id
    }
  );

  sendMessageToSocket(json);
}

function getRequestResponse(values) {
  var json = JSON.parse(values);

  if(json.request.request !== undefined) {
    var time = json.request.time;

    if(json.request.days > 0 || json.request.hours > 0 || json.request.minutes > 0) {
      time += ' -';
      if(json.request.days === 1) time += ' 1 dia,';
      else if(json.request.days > 0) time += ' ' + json.request.days + ' dias,';

      if(json.request.hours === 1) time += ' 1 hora,';
      else if(json.request.hours > 0) time += ' ' + json.request.hours + ' horas,';

      if(json.request.minutes === 1) time += ' 1 minuto';
      else if(json.request.minutes > 0) time += ' ' + json.request.minutes + ' minutos';

      time = time.replace(/,+$/,'');
    }

    $('#modalContent').append("<button class=\"btn btn-default\" id=\"btnViewedRequest\" data-dismiss=\"modal\" requestId=\"" + json.request.request + "\">Pedido Visualizado</button>");
    $('#requestDetailModalLabel').text("Novo pedido - Mesa " + json.request.table_number);
    $('#requestDetailModalTimeBox').text(time);
    $('#requestDetailModalFunctionaryBox').text(json.request.functionary);

    if(json.products.length > 0) {
      var htmlElements = '';

      $.each(json.products, function(index, product) {
        htmlElements += "<span>" + product.quantity + "X " + product.product + "<br/>";
      });

      $('#requestDetailModalProductsBox').html(htmlElements);
      $('#requestDetailModalProductsDiv').css("display", "");
    }

    $('#requestDetailModal').modal('show');
  }
}

var markRequestAsViewed = function(id) {
  var json = JSON.stringify(
    {
      "key": "RequestsPane",
      "method": "markRequestAsViewed",
      "value": id
    }
  );

  sendMessageToSocket(json);
};

$('body').on('click', '.requestItem', function() {
  $('#allRequestModal').modal('hide');
  getRequest($(this).attr('requestId'));
});

$('#modalContent').on('click', '#btnViewedRequest', function() {
  markRequestAsViewed($(this).attr('requestId'));
});

$('#seeAllRequests').on('click', function() {
  $('#allRequestModal').modal('show');
});

$('#requestDetailModal').on('hidden.bs.modal', function() {
  $('#btnViewedRequest').remove();
  $('#requestDetailModalLabel').text('');
  $('#requestDetailModalTimeBox').text('');
  $('#requestDetailModalFunctionaryBox').text('');
  $('#requestDetailModalProductsDiv').css("display", "none");
  $('#requestDetailModalProductsBox').html('');
});
