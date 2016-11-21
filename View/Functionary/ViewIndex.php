<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-funcionario')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-funcionario'">+ Novo Funcion&aacute;rio</button>
      <br><br>
      <?php
    }
    ?>
    <table id="functionarys" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Dispositivo</th>
          <th>Tipo</th>
          <?php
          if(count($functionarys) > 0 && $daoPermissao->accessPermitted('/edicao-de-funcionario')) {
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
        if(count($functionarys) > 0) {
          foreach($functionarys as $functionary) {
            $device = $daoDevice->listOne($functionary->device);
            ?>
            <tr>
              <td><?php echo $functionary->name; ?></td>
              <td><?php
                if(count($device) > 0){
                  echo $device[0]->imei;
                }
                ?>
              </td>
              <td><?php
                $functionaryObj->setAdminFlag($functionary->admin_flag);
                echo $functionaryObj->getTypeName(); ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-funcionario')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-funcionario/<?php echo $functionary->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $functionary->id; ?>');">Excluir</button>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
        } else {
          ?>
          <tr><td colspan="4" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Nome</th>
          <th>Dispositivo</th>
          <th>Tipo</th>
          <?php
          if(count($functionarys) > 0 && $daoPermissao->accessPermitted('/edicao-de-funcionario')) {
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
      return console.log(value ? location.href = '/delecao-de-funcionario/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if(count($functionarys) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#functionarys').dataTable();
  });
  </script>
  <?php
}
?>
