<?php use App\Constants\Constants; ?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/usuario">
          <input type="hidden" name="password" id="password" value=""/>
          <?php
          if(isset($user[0])) {
            ?>
            <input type="hidden" name="id" value="<?php echo $user[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($user) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($user) ? $user[0]->name : ""; ?>">
          </div>
          <div class="form-group">
            <label>E-mail</label>
            <input type="text" class="form-control" name="email" id="email" value="<?php echo isset($user) ? $user[0]->email : ""; ?>">
          </div>
          <div class="form-group">
            <label>Usu&aacute;rio</label>
            <input type="text" class="form-control" name="user" id="user" value="<?php echo isset($user) ? $user[0]->user : ""; ?>">
          </div>
          <div class="form-group">
            <label>Senha</label>
            <input type="password" class="form-control" name="passwordTemp" id="passwordTemp">
          </div>
          <?php
          if(isset($user)) {
            ?>
            <p class="margin">Caso a senha n&atilde;o for preenchida ela continuar&aacute; sendo a mesma.</p>
            <?php
          } else {
            ?>
            <p class="margin">Caso a senha n&atilde;o for preenchida ela ser&aacute; criada com o valor padr&atilde;o.</p>
            <?php
          }
          ?>
          <?php
          if($_CURRENT_USER->getAdministrator() == 1) {
            ?>
            <div class="form-group">
              <label for="administrator">Usu&aacute;rio Administrador&nbsp;&nbsp;</label>
              <input type="checkbox" name="administrator" id="administrator">
              <script>
              <?php
              if(isset($user) && $user[0]->administrator == 1) {
                ?>
                document.getElementById("administrator").checked = true;
                <?php
              }
              ?>
              </script>
            </div>
            <?php
          }
          ?>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if(isset($user)) {
              ?>
              <button type="button" class="btn btn-default" onclick="delRecord();">Excluir</button>
              <?php
            } else {
              ?>
              <button type="reset" class="btn btn-default">Limpar Campos</button>
              <?php
            }
            ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
<?php
if (isset($user)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-usuario/<?php echo $user[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var user = document.getElementById('user');
  var passwordTemp = document.getElementById('passwordTemp');
  var name = document.getElementById('name');
  var email = document.getElementById('email');

  <?php
  if (isset($user)) {
    ?>
    if (user.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      user.focus();
    } else if (name.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      name.focus();
    } else if (email.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      email.focus();
    } else if (passwordTemp.value != '' && passwordTemp.value.length < 6) {
      vex.dialog.alert('A senha deve ter pelo menos 6 caracteres!');
      passwordTemp.focus();
    } else {
      if(passwordTemp.value != '') {
        document.getElementById('password').value = passwordTemp.value;
      }
      form.submit();
    }
    <?php
  } else {
    ?>
    if (user.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      user.focus();
    } else if (name.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      name.focus();
    } else if (email.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      email.focus();
    } else if (passwordTemp.value != '' && passwordTemp.value.length < 6) {
      vex.dialog.alert('A senha deve ter pelo menos 6 caracteres!');
      passwordTemp.focus();
    } else {
      if(passwordTemp.value != '') {
        document.getElementById('password').value = passwordTemp.value;
      } else {
        document.getElementById('password').value = '<?php echo Constants::DEFAULT_PASSWORD; ?>';
      }
      form.submit();
    }
    <?php
  }
  ?>
}
</script>
