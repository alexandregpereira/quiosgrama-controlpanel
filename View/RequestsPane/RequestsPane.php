<?php
  use App\Db\Dao\Impl\RequestDaoImpl;
?>
<div class="modal fade" id="requestDetailModal" tabindex="-1" role="dialog" aria-labelledby="requestDetailModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCloseModal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span id="requestDetailModalLabel"></span>
        </h4>
      </div>
      <div class="modal-body" style="background-color: #FDF9F1;">
        <div class="quios-clear"></div>
        <div class="col-md-12" id="modalContent">
          <br/>
          <div class="col-md-6">
            <div class="box box-default">
              <div class="box-header with-border"><h3 class="box-title">Hor&aacute;rio</h3></div>
              <div class="box-body" id="requestDetailModalTimeBox"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="box box-default">
              <div class="box-header with-border"><h3 class="box-title">Funcionário</h3></div>
              <div class="box-body" id="requestDetailModalFunctionaryBox"></div>
            </div>
          </div>
          <div class="quios-clear"></div>
          <div class="col-md-12" id="requestDetailModalProductsDiv" style="display: none;">
            <div class="box box-default">
              <div class="box-header with-border"><h3 class="box-title">Produtos</h3></div>
              <div class="box-body" id="requestDetailModalProductsBox"></div>
            </div>
          </div>
          <div class="quios-clear"></div>
        </div>
        <div class="quios-clear"></div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="allRequestModal" tabindex="-1" role="dialog" aria-labelledby="allRequestModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnCloseModal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span>Novos Pedidos</span>
        </h4>
      </div>
      <div class="modal-body" style="background-color: #FDF9F1;">
        <table class="table table-bordered table-striped" style="background: white;">
          <thead>
            <tr>
              <th>Mesa</th>
              <th>N&uacute;mero de Itens</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="allRequestModalTableTbody">
            <?php
            $daoRequests = new RequestDaoImpl();
            $requests = $daoRequests->listNewRequests();

            if (count($requests) > 0) {
              foreach ($requests as $request) {
                ?>
                <tr>
                  <td><?php echo $request->table_number; ?></td>
                  <td><?php echo $request->products; ?></td>
                  <td><button type="button" class="btn btn-primary requestItem" requestId="<?php echo $request->request; ?>">Detalhes</button></td>
                </tr>
                <?php
              }
            } else {
              ?>
              <tr>
                <td colspan="3" class="nenhum">Nenhum item encontrado.</td>
              </tr>
              <?php
            }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th>Mesa</th>
              <th>N&uacute;mero de Itens</th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
