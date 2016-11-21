<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><img src="/Resources/Img/tableIcon.png" style="width: 80%;"/></span>
      <div class="info-box-content">
        <span class="info-box-text quios-table-text">Abertas</span>
        <span class="info-box-number quios-table-text" id="openNumber"><?php echo $qtdOpen; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><img src="/Resources/Img/tableIcon.png" style="width: 80%;"/></span>
      <div class="info-box-content">
        <span class="info-box-text quios-table-text">Fechadas</span>
        <span class="info-box-number quios-table-text" id="closeNumber"><?php echo $qtdClose; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><img src="/Resources/Img/tableIcon.png" style="width: 80%;"/></span>
      <div class="info-box-content">
        <span class="info-box-text quios-table-text">Pendentes</span>
        <span class="info-box-number quios-table-text" id="alertNumber"><?php echo $qtdAlert; ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><img src="/Resources/Img/tableIcon.png" style="width: 80%;"/></span>
      <div class="info-box-content">
        <span class="info-box-text quios-table-text">Total</span>
        <span class="info-box-number quios-table-text" id="totalNumber"><?php echo ($qtdOpen + $qtdClose + $qtdAlert); ?></span>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Mesas</h3>
    </div>
    <div class="box-body">
      <ul class="quios-tables" id="ulTables">
        <?php
        foreach($tablesDashboard as $table) {
          $statusTable = "closed";

          if(is_null($table->open_time) || (strtotime(date("Y-m-d", strtotime($table->open_time))) != strtotime(date("Y-m-d")))) {
            $statusTable = "closed";
          } else {
            if(is_null($table->close_time)) {
              $statusTable = "open";
            } else {
              if(is_null($table->paid_time)) {
                $statusTable = "alert";
              } else {
                $statusTable = "closed";
              }
            }
          }
          ?>
          <li class="quios-table-<?php echo $statusTable; ?>"<?php if($statusTable == "open" || $statusTable == "alert") { ?> onclick="showModal(<?php echo $table->number . ", '" . $statusTable . "'"; ?>);"<?php } ?>>
            <h1 style="font-size: 15px;"><b><?php echo $table->number; ?></b></h1>
          </li>
          <?php
        }
        ?>
      </ul>
    </div>
  </div>
</div>

<?php
	echo '<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $_SERVER['HTTP_HOST'] . ':23&choe=UTF-8" title="Cadastro"/>'
?>

<div class="modal fade" id="tablesModal" tabindex="-1" role="dialog" aria-labelledby="tablesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCloseModal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="tablesModalLabel">Detalhes de Mesa</h4>
      </div>
      <div class="modal-body" style="background-color: #FDF9F1;">
        <div class="quios-clear"></div>
        <div class="col-md-12">
          <a class="btn btn-app quios-modal-button" id="closeBtn" onclick="" data-dismiss="modal">
            <i class="fa fa-close"></i> Fechar
          </a>
          <a class="btn btn-app quios-modal-button" id="printBtn" onclick="">
            <i class="fa fa-print"></i> Imprimir
          </a>
          <a class="btn btn-app quios-modal-button" id="getawayBtn" onclick="" data-dismiss="modal">
            <i class="fa fa-car"></i> Fuga
          </a>
        </div>
        <div class="quios-clear"></div>
        <br/>
        <div class="col-md-6">
          <div class="box box-default">
            <div class="box-header with-border"><h3 class="box-title">Conta</h3></div>
            <div class="box-body" id="productsBox"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box box-default">
            <div class="box-header with-border"><h3 class="box-title">Pedidos</h3></div>
            <div class="box-body" id="requestsBox"></div>
          </div>
        </div>
        <div class="quios-clear"></div>
      </div>
    </div>
  </div>
</div>
<div class="quios-clear"></div>
<script type="text/javascript">

var modalValues = {

  <?php

  $content = "";

  foreach($tablesDashboard as $table) {
    $content .= "'bill" . $table->number . "': '" . $table->bill . "', ";

    if(!is_null($table->open_time) && (strtotime(date("Y-m-d", strtotime($table->open_time))) == strtotime(date("Y-m-d"))) && is_null($table->paid_time)) {

      $content .= "'requestsTable" . $table->number . "': '";

      $requests = $daoRequest->listAllByBill($table->bill);

      foreach($requests as $request) {
        $products = $daoProductRequest->listAllByRequest($request->request);
        $content .= "<div class=\"quios-bill-item\"><span class=\"quios-left\">" . $request->functionary . "</span><span class=\"quios-right\">" . date("d/m/Y - H\hi", strtotime($request->time)) . "</span><div class=\"quios-clear\"></div><span class=\"quios-left\" style=\"font-size: 12px; margin-left: 10px; color: #909090;\">" . $request->products . "</span><div class=\"quios-clear\"></div></div>";
        $content .= "', ";
      }

      $content .= "'productsTable" . $table->number . "': '";

      $allBillProducts = $daoProductRequest->listAllByBill($table->bill);

      foreach($allBillProducts as $product) {
        if($product->valid == 1) {
          $content .= "<div class=\"quios-bill-item\"><button type=\"button\" class=\"close quios-close-btn\" data-dismiss=\"alert\" aria-hidden=\"true\" onclick=\"invalidateItem(" . $product->product_request . ")\">×</button>";
        } else {
          $content .= "<div class=\"quios-bill-item quios-bill-item-invalid\"><button type=\"button\" class=\"close quios-valid-btn\" data-dismiss=\"alert\" aria-hidden=\"true\" onclick=\"validateItem(" . $product->product_request . ")\">✓</button>";
        }

        $content .= "<div class=\"quios-clear\"></div><div class=\"col-md-4\"><span class=\"quios-left\">" . $product->quantity . "X " . $product->product . "</span></div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " . number_format($product->price, 2, ',', '.') . "</span></div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " . number_format(($product->quantity * $product->price), 2, ',', '.') . "</span></div>";
        $content .= ($product->complement != null && $product->complement != "") ? "<div class=\"quios-clear\"></div><span style=\"font-size: 12px; margin-left: 30px; color: #909090;\">" . $product->complement . "</span>" : "";
        $content .= "<div class=\"quios-clear\"></div></div>";
      }

      $content .= "', ";

      $total = $daoProductRequest->listTotalByBill($table->bill);

      if(isset($total[0])) {
        $content .= "'total" . $total[0]->table_number . "': '";
        $content .= "<div class=\"total-value\"><br/><div class=\"col-md-8\">Subtotal:</div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " . number_format($total[0]->total, 2, ',', '.') . "</span></div><div class=\"quios-clear\"></div><div class=\"col-md-8\">Servi&ccedil;o:</div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " . number_format(($total[0]->total * 0.1), 2, ',', '.') . "</span></div><div class=\"quios-clear\"></div><div class=\"col-md-8\">Total:</div><div class=\"col-md-4\"><span class=\"quios-right\">R$ " . number_format(($total[0]->total + ($total[0]->total * 0.1)), 2, ',', '.') . "</span></div><div class=\"quios-clear\"></div></div>";
        $content .= "', ";
      }
    }
  }

  $content = rtrim($content, ' ');
  $content = rtrim($content, ',');

  echo $content;

  ?>

};

</script>
<script src="/Resources/dist/js/quiosgrama.dashboard.js" type="text/javascript"></script>
