<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-permissao')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-permissao'">+ Nova Permiss&atilde;o</button>
      <br><br>
      <?php
    }
    ?>
    <table id="userPermissions" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Usu&aacute;rio</th>
          <th>M&oacute;dulo</th>
          <th>Tela</th>
          <?php
          if(count($userPermissions) > 0 && $daoPermissao->accessPermitted('/edicao-de-permissao')) {
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
        if (count($userPermissions) > 0) {
          foreach ($userPermissions as $permissionUser) {
            $user = $daoUser->listOne($permissionUser->user);
            if($permissionUser->module != null && $permissionUser->module != ""){
              $module = $daoModule->listOne($permissionUser->module);
            } if($permissionUser->screen != null && $permissionUser->screen != ""){
              $screen = $daoScreen->listOne($permissionUser->screen);
            }
            ?>
            <tr>
              <td><?php echo $user[0]->user; ?></td>
              <td><?php echo isset($module[0]) ? $module[0]->description : "-"; ?></td>
              <td><?php echo isset($screen[0]) ? $screen[0]->description : "-"; ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-permissao')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-permissao/<?php echo $permissionUser->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $permissionUser->id; ?>');">Excluir</button>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
            unset($module);
            unset($screen);
          }
        } else {
          ?>
          <tr><td colspan="5" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Usu&aacute;rio</th>
          <th>M&oacute;dulo</th>
          <th>Tela</th>
          <?php
          if (count($userPermissions) > 0 && $daoPermissao->accessPermitted('/edicao-de-permissao')) {
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
      return console.log(value ? location.href = '/delecao-de-permissao/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if (count($userPermissions) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#userPermissions').dataTable();
  });
  </script>
  <?php
}
?>
