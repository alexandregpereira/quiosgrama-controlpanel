<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-modulo')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-modulo'">+ Novo M&oacute;dulo</button>
      <br><br>
      <?php
    }
    ?>
    <table id="modules" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Descri&ccedil;&atilde;o</th>
          <?php
          if(count($modules) > 0 && $daoPermissao->accessPermitted('/edicao-de-modulo')) {
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
        if (count($modules) > 0) {
          foreach ($modules as $module) {
            ?>
            <tr>
              <td><?php echo $module->description; ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-modulo')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-modulo/<?php echo $module->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $module->id; ?>');">Excluir</button>
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
          <th>Descri&ccedil;&atilde;o</th>
          <?php
          if(count($modules) > 0 && $daoPermissao->accessPermitted('/edicao-de-modulo')) {
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
      return console.log(value ? location.href = '/delecao-de-modulo/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if(count($modules) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#modules').dataTable();
  });
  </script>
  <?php
}
?>
