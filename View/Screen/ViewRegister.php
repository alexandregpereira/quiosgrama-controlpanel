<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/tela">
          <?php
          if(isset($screen)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $screen[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($screen) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Descri&ccedil;&atilde;o</label>
            <input type="text" class="form-control" name="description" id="description" value="<?php echo isset($screen) ? $screen[0]->description : ""; ?>">
          </div>
          <div class="form-group">
            <label>Caminho</label>
            <input type="text" class="form-control" name="url" id="url" value="<?php echo isset($screen) ? $screen[0]->url : ""; ?>">
          </div>
          <div class="input-group">
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
            if(isset($screen)) {
              ?>
              <script>document.getElementById('module').value = '<?php echo $screen[0]->module; ?>';</script>
              <?php
            }
            ?>
          </div>
          <br/><br/>
          <div class="form-group">
            <label for="listOnTheScreen">Mostrar no Menu&nbsp;&nbsp;</label>
            <input type="checkbox" name="listOnTheScreen" id="listOnTheScreen">
            <script>
            <?php
            if(isset($screen) && $screen[0]->list_on_the_screen == 1) {
              ?>
              document.getElementById("listOnTheScreen").checked = true;
              <?php
            }
            ?>
            </script>
          </div>
          <div class="form-group">
            <label for="needAdministratorPermission">Exige Direitos Administrativos&nbsp;&nbsp;</label>
            <input type="checkbox" name="needAdministratorPermission" id="needAdministratorPermission">
            <script>
            <?php
            if(isset($screen) && $screen[0]->need_administrator_permission == 1) {
              ?>
              document.getElementById("needAdministratorPermission").checked = true;
              <?php
            }
            ?>
            </script>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if(isset($screen)) {
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
if (isset($screen)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-tela/<?php echo $screen[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var description = document.getElementById('description');
  var url = document.getElementById('url');
  var module = document.getElementById('module');

  if (description.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    description.focus();
  } else if (url.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    url.focus();
  } else if (module.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    module.focus();
  } else {
    form.submit();
  }
}
</script>
