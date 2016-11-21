<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/cliente">
          <?php
          if(isset($client)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $client[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($client) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($client) ? $client[0]->name : ""; ?>">
          </div>
          <div class="form-group">
            <label>CPF</label>
            <input type="text" class="form-control cpf" name="cpf" id="cpf" value="<?php echo isset($client) ? $client[0]->cpf : ""; ?>">
          </div>
          <div class="form-group">
            <label>Telefone</label>
            <input type="text" class="form-control phone" name="phone" id="phone" value="<?php echo isset($client) ? $client[0]->phone : ""; ?>">
          </div>
          <div class="form-group">
            <label>Cliente Tempor&aacute;rio</label>
            <select class="form-control" name="tempFlag" id="tempFlag">
              <option value="1">Sim</option>
              <option value="0">N&atilde;o</option>
            </select>
            <?php
            if(isset($client)) {
              ?>
              <script>document.getElementById('tempFlag').value = "<?php echo $client[0]->temp_flag; ?>";</script>
              <?php
            }
            ?>
          </div>
          <div class="form-group">
            <label>Cliente Presente</label>
            <select class="form-control" name="presentFlag" id="presentFlag">
              <option value="1">Sim</option>
              <option value="0">N&atilde;o</option>
            </select>
            <?php
            if(isset($client)) {
              ?>
              <script>document.getElementById('presentFlag').value = "<?php echo $client[0]->present_flag; ?>";</script>
              <?php
            }
            ?>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if(isset($client)) {
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
if (isset($client)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-cliente/<?php echo $client[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var name = document.getElementById('name');
  var form = document.getElementById('form');

  if (name.value == '') {
    vex.dialog.alert('Preencha a descri&ccedil;&atilde;o!');
    name.focus();
  } else {
    form.submit();
  }
}
</script>
