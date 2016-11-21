<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/dispositivo">
          <?php
          if(isset($device)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $device[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($device) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>IMEI</label>
            <input type="text" class="form-control" name="imei" id="imei" value="<?php echo isset($device) ? $device[0]->imei : ""; ?>">
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if (isset($device)) {
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
if (isset($device)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-dispositivo/<?php echo $device[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var imei = document.getElementById('imei');
  var form = document.getElementById('form');

  if (imei.value == '') {
    vex.dialog.alert('Preencha o imei!');
    imei.focus();
  } else {
    form.submit();
  }
}
</script>
