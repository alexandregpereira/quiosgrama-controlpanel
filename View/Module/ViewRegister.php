<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/modulo">
          <?php
          if(isset($module)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $module[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($module) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Descri&ccedil;&atilde;o</label>
            <input type="text" class="form-control" name="description" id="description" value="<?php echo isset($module) ? $module[0]->description : ""; ?>">
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if (isset($module)) {
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
if (isset($module)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-modulo/<?php echo $module[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var description = document.getElementById('description');

  var form = document.getElementById('form');

  if (description.value == '') {
    vex.dialog.alert('Preencha a descri&ccedil;&atilde;o!');
    description.focus();
  } else {
    form.submit();
  }
}
</script>
