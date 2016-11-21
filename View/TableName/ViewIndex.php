<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-mesa')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-mesa'">+ Nova Mesa</button>
      <br><br>
      <?php
    }
    ?>
    <table id="tables" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>N&uacute;mero</th>
          <th>Cliente</th>
          <?php
          if(count($tables) > 0 && $daoPermissao->accessPermitted('/edicao-de-mesa')) {
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
        if(count($tables) > 0) {
          foreach($tables as $table) {
            $client = $daoClient->listOne($table->client);
            ?>
            <tr>
              <td><?php echo $table->number; ?></td>
              <td><?php echo isset($client[0]->name) ? $client[0]->name : "-"; ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-mesa')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-mesa/<?php echo $table->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $table->id; ?>');">Excluir</button>
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
          <th>N&uacute;mero</th>
          <th>Cliente</th>
          <?php
          if(count($tables) > 0 && $daoPermissao->accessPermitted('/edicao-de-mesa')) {
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
      return console.log(value ? location.href = '/delecao-de-mesa/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if(count($tables) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#tables').dataTable();
  });
  </script>
  <?php
}
?>
