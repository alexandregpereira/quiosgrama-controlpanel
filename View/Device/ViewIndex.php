<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-dispositivo')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-dispositivo'">+ Novo Dispositivo</button>
      <br><br>
      <?php
    }
    ?>
    <table id="devices" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>IMEI</th>
          <?php
          if(count($devices) > 0 && $daoPermissao->accessPermitted('/edicao-de-dispositivo')) {
            ?>
            <th></th>
            <th></th>
            <?php
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        if (count($devices) > 0) {
          foreach ($devices as $device) {
            ?>
            <tr>
              <td><?php echo $device->imei; ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-dispositivo')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-dispositivo/<?php echo $device->id; ?>'">Editar</button>
                </td>
                <td>
                  <?php
                  if(is_null($device->exclusion_time)){
                  ?>
                    <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $device->id; ?>');">Excluir</button>
                  <?php
                  }
                  else{
                  ?>
                    <button type="button" class="btn btn-primary" onclick="delRecord('<?php echo $device->id; ?>');">Ativar</button>
                  <?php
                  }
                  ?>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
        } else {
          ?>
          <tr><td colspan="3" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>IMEI</th>
          <?php
          if(count($devices) > 0 && $daoPermissao->accessPermitted('/edicao-de-dispositivo')) {
            ?>
            <th></th>
            <th></th>
            <?php
          }
          ?>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script>
function delRecord(id) {
  vex.dialog.confirm({
    message: 'Confirmar exclus&atilde;o?',
    callback: function(value) {
      return console.log(value ? location.href = '/delecao-de-dispositivo/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if(count($devices) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#devices').dataTable();
  });
  </script>
  <?php
}
?>
