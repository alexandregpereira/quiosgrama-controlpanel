<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/alterar-senha">
          <div class="form-group">
            <label for="currentPassword">Senha atual:&nbsp;</label>
            <input type="password" class="form-control" name="currentPassword" id="currentPassword"/>
          </div>
          <div class="form-group">
            <label for="newPassword">Nova senha:&nbsp;</label>
            <input type="password" class="form-control" name="newPassword" id="newPassword"/>
          </div>
          <div class="form-group">
            <label for="newPasswordConfirmation">Confirma&ccedil;&atilde;o da nova senha:&nbsp;</label>
            <input type="password" class="form-control" name="newPasswordConfirmation" id="newPasswordConfirmation"/>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <button type="reset" class="btn btn-default">Limpar Campos</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function submitForm() {
  var currentPassword = document.getElementById('currentPassword');
  var newPassword = document.getElementById('newPassword');
  var newPasswordConfirmation = document.getElementById('newPasswordConfirmation');

  var form = document.getElementById('form');

  if (currentPassword.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    currentPassword.focus();
  } else if (newPassword.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    newPassword.focus();
  } else if (newPasswordConfirmation.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    newPasswordConfirmation.focus();
  } else {
    form.submit();
  }
}
</script>
