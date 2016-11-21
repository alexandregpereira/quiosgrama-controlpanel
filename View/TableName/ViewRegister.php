<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/mesa">
          <?php
          if(isset($table)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $table[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($table) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>N&uacute;mero</label>
            <input type="text" class="form-control" name="number" id="number" value="<?php echo isset($table) ? $table[0]->number : ""; ?>">
          </div>
          <div class="form-group">
            <label>Cliente</label>
            <select class="form-control" name="client" id="client">
              <option>Selecione abaixo</option>
              <?php
              foreach($clients as $client) {
                ?>
                <option value="<?php echo $client->id; ?>"><?php echo $client->name; ?></option>
                <?php
              }
              ?>
            </select>
            <?php
            if(isset($table)) {
              ?>
              <script>document.getElementById('client').value = "<?php echo $table[0]->client; ?>";</script>
              <?php
            }
            ?>
          </div>
          <div class="form-group">
            <label>Exibir</label>
            <select class="form-control" name="show" id="show">
              <option value="1">Sim</option>
              <option value="0">N&atilde;o</option>
            </select>
            <?php
            if(isset($table)) {
              ?>
              <script>document.getElementById('show').value = "<?php echo $table[0]->show; ?>";</script>
              <?php
            }
            ?>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if (isset($table)) {
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
if (isset($table)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-mesa/<?php echo $table[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var number = document.getElementById('number');
  var show = document.getElementById('show');

  var form = document.getElementById('form');

  if (number.value == '') {
    vex.dialog.alert('Preencha o n&uacute;mero!');
    number.focus();
  } else if (show.value == '') {
    vex.dialog.alert('Selecione a exibi&ccedil;&atilde;o!');
    show.focus();
  } else {
    form.submit();
  }
}
</script>
