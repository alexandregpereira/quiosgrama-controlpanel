<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/funcionario">
          <?php
          if(isset($functionary)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $functionary[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($functionary) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($functionary) ? $functionary[0]->name : ""; ?>">
          </div>
          <div class="form-group">
            <label>Dispositivo</label>
            <select class="form-control" name="device" id="device">
              <option value="">Selecione abaixo</option>
              <?php
              foreach($devices as $device) {
                ?>
                <option value="<?php echo $device->id; ?>"><?php echo $device->imei; ?></option>
                <?php
              }
              ?>
            </select>
            <?php
            if(isset($functionary)) {
              ?>
              <script>
              document.getElementById('device').value = "<?php echo $functionary[0]->device; ?>";
              </script>
              <?php
            }
            ?>
          </div>
          <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" name="functionaryType" id="functionaryType">
              <option>Selecione abaixo</option>
              <option value="0">Gar&ccedil;om</option>
              <option value="1">Administrador</option>
              <option value="3">Cliente</option>
            </select>
            <?php
            if(isset($functionary)) {
              ?>
              <script>document.getElementById('functionaryType').value = "<?php echo $functionary[0]->admin_flag; ?>";</script>
              <?php
            }
            ?>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if(isset($functionary)) {
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
if (isset($functionary)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-funcionario/<?php echo $functionary[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var name = document.getElementById('name');
  var device = document.getElementById('device');
  var functionaryType = document.getElementById('functionaryType');

  var form = document.getElementById('form');

  if (name.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    name.focus();
  } else if (device.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    device.focus();
  } else if (functionaryType.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    device.focus();
  } else {
    form.submit();
  }
}
</script>
