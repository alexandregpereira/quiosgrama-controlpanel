<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-tela')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-tela'">+ Nova Tela</button>
      <br><br>
      <?php
    }
    ?>
    <table id="screens" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Descri&ccedil;&atilde;o</th>
          <th>Caminho</th>
          <th>M&oacute;dulo</th>
          <th>Mostrar no Menu</th>
          <th>Exige Direitos Administrativos</th>
          <?php
          if(count($screens) > 0 && $daoPermissao->accessPermitted('/edicao-de-tela')) {
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
        if (count($screens) > 0) {
          foreach ($screens as $screen) {
            $module = $daoModule->listOne($screen->module);
            ?>
            <tr>
              <td><?php echo $screen->description; ?></td>
              <td><?php echo $screen->url; ?></td>
              <td><?php echo $module[0]->description; ?></td>
              <td><?php
              switch($screen->list_on_the_screen) {
                case 0:
                echo "N&atilde;o";
                break;
                case 1:
                echo "Sim";
                break;
                default:
                echo "N&atilde;o";
                break;
              }
              ?></td>
              <td><?php
              switch($screen->need_administrator_permission) {
                case 0:
                echo "N&atilde;o";
                break;
                case 1:
                echo "Sim";
                break;
                default:
                echo "N&atilde;o";
                break;
              }
              ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-tela')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-tela/<?php echo $screen->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $screen->id; ?>');">Excluir</button>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
        } else {
          ?>
          <tr><td colspan="7" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Descri&ccedil;&atilde;o</th>
          <th>Caminho</th>
          <th>M&oacute;dulo</th>
          <th>Mostrar no Menu</th>
          <th>Exige Direitos Administrativos</th>
          <?php
          if(count($screens) > 0 && $daoPermissao->accessPermitted('/edicao-de-tela')) {
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
      return console.log(value ? location.href = '/delecao-de-tela/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if(count($screens) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#screens').dataTable();
  });
  </script>
  <?php
}
?>
