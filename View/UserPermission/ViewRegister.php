<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/permissao">
          <?php
          if(isset($permissionUser)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $permissionUser[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($permissionUser) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Usu&aacute;rio</label>
            <select class="form-control" name="user" id="user">
              <option value="">Selecione abaixo</option>
              <?php
              foreach($users as $user) {
                ?>
                <option value="<?php echo $user->id; ?>"><?php echo $user->user; ?></option>
                <?php
              }
              ?>
            </select>
            <?php
            if(isset($permissionUser)) {
              ?>
              <script>document.getElementById('user').value = '<?php echo $permissionUser[0]->user; ?>'</script>
              <?php
            }
            ?>
          </div>
          <div class="form-group">
            <label>Tipo de Permiss&atilde;o</label>
            <select class="form-control" name="permissionType" id="permissionType" onchange="selecionarTipo();">
              <option value="">Selecione abaixo</option>
              <option value="module">M&oacute;dulo</option>
              <option value="screen">Tela</option>
            </select>
            <?php
            if (isset($permissionUser)) {
              ?>
              <script>document.getElementById('permissionType').value = '<?php
              if ($permissionUser[0]->module != "") {
                echo 'module';
              } elseif ($permissionUser[0]->screen != "") {
                echo 'screen';
              }
              ?>';</script>
              <?php
            }
            ?>
          </div>
          <div class="form-group" id="formGroupModule"<?php echo (isset($permissionUser) && $permissionUser[0]->module != "") ? " style=\"\"" : " style=\"display: none;\""; ?>>
            <label>M&oacute;dulo</label>
            <select class="form-control" name="module" id="module">
              <option value="">Selecione abaixo</option>
              <?php
              foreach($modules as $module) {
                ?>
                <option value="<?php echo $module->id; ?>"><?php echo $module->description; ?></option>
                <?php
              }
              ?>
            </select>
            <?php
            if (isset($permissionUser) && $permissionUser[0]->module != "") {
              ?>
              <script>
              document.getElementById('module').value = '<?php echo $permissionUser[0]->module; ?>';
              </script>
              <?php
            }
            ?>
          </div>
          <div class="form-group" id="formGroupScreen"<?php echo (isset($permissionUser) && $permissionUser[0]->screen != "") ? " style=\"\"" : " style=\"display: none;\""; ?>>
            <label>Tela</label>
            <select class="form-control" name="screen" id="screen">
              <option value="">Selecione abaixo</option>
              <?php
              foreach($screens as $screen) {
                ?>
                <option value="<?php echo $screen->id; ?>"><?php echo $screen->description; ?></option>
                <?php
              }
              ?>
            </select>
            <?php
            if (isset($permissionUser) && $permissionUser[0]->screen != "") {
              ?>
              <script>
              document.getElementById('screen').value = '<?php echo $permissionUser[0]->screen; ?>';
              </script>
              <?php
            }
            ?>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if(isset($permissionUser)) {
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
if (isset($permissionUser)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-permissao/<?php echo $permissionUser[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var permissionType = document.getElementById('permissionType');
  var user = document.getElementById('user');
  var module = document.getElementById('module');
  var screen = document.getElementById('screen');

  if(permissionType.value == 'module'){
    if (user.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      user.focus();
    }else if (module.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      module.focus();
    } else {
      form.submit();
    }
  }else{
    if (user.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      user.focus();
    }else if (screen.value == '') {
      vex.dialog.alert('Preencha todos os campos!');
      screen.focus();
    } else {
      form.submit();
    }
  }
}

function selecionarTipo() {
  var permissionType = document.getElementById('permissionType').value;

  if (permissionType == "module") {
    document.getElementById('formGroupScreen').style.display = 'none';
    document.getElementById('formGroupModule').style.display = '';
  } else if (permissionType == "screen") {
    document.getElementById('formGroupScreen').style.display = '';
    document.getElementById('formGroupModule').style.display = 'none';
  }
}
</script>
