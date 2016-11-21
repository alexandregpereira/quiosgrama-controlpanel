<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-usuario')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-usuario'">+ Novo Usu&aacute;rio</button>
      <br><br>
      <?php
    }
    ?>
    <table id="users" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Usu&aacute;rio</th>
          <th>Data de Cadastro</th>
          <th>Administrador</th>
          <?php
          if ($_CURRENT_USER->getAdministrator() == 1) {
            ?>
            <th></th>
            <?php
          } if ((count($users) > 1 || (count($users) == 1 && $users[0]->id != $_CURRENT_USER->getId())) && $daoPermissao->accessPermitted('/edicao-de-usuario')) {
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
        if (count($users) > 1 || (count($users) == 1 && $users[0]->id != $_CURRENT_USER->getId())) {
          foreach ($users as $user) {
            if ($user->id != $_CURRENT_USER->getId()) {
              ?>
              <tr>
                <td><?php echo $user->name; ?></td>
                <td><?php echo $user->email; ?></td>
                <td><?php echo $user->user; ?></td>
                <td><?php echo $utilities->convertDateUS2BR($user->register_date); ?></td>
                <td><?php
                switch($user->administrator) {
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
                if ($_CURRENT_USER->getAdministrator() == 1) {
                  ?>
                  <td>
                    <button type="button" class="btn btn-danger" onclick="resetPassword(<?php echo $user->id; ?>);">Zerar Senha</button>
                  </td>
                  <?php
                } if($daoPermissao->accessPermitted('/edicao-de-usuario')) {
                  ?>
                  <td>
                    <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-usuario/<?php echo $user->id; ?>'">Editar</button>
                  </td>
                  <td>
                    <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $user->id; ?>');">Excluir</button>
                  </td>
                  <?php
                }
                ?>
              </tr>
              <?php
            }
          }
        } else {
          ?>
          <tr><td colspan="8" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Usu&aacute;rio</th>
          <th>Data de Cadastro</th>
          <th>Administrador</th>
          <?php
          if ($_CURRENT_USER->getAdministrator() == 1) {
            ?>
            <th></th>
            <?php
          } if ((count($users) > 1 || (count($users) == 1 && $users[0]->id != $_CURRENT_USER->getId())) && $daoPermissao->accessPermitted('/edicao-de-usuario')) {
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
      return console.log(value ? location.href = '/delecao-de-usuario/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if (count($users) > 1 || (count($users) == 1 && $users[0]->id != $_CURRENT_USER->getId())) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#users').dataTable();
  });
  </script>
  <?php
} if ($_CURRENT_USER->getAdministrator() == 1) {
  ?>
  <script>
  function resetPassword(id) {
    vex.dialog.confirm({
      message: 'Voc&ecirc; realmente deseja zerar a senha do usu&aacute;rio?',
      callback: function(value) {
        return console.log(value ? location.href = '/zerar-senha/' + id : vex.dialog.alert('Senha original mantida.'));
      }
    });
  }
  </script>
  <?php
}
?>
