<?php use App\Constants\Constants; ?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/poi">
          <?php
          if(isset($poi)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $poi[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($poi) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($poi) ? $poi[0]->name : ""; ?>">
          </div>
          <div class="form-group">
            <label>Imagem</label>
            <select class="imagepicker" name="image" id="image">
              <?php
              $dir = dir($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_POI);

              while($file = $dir->read()) {
                if($file != ".." && $file != ".") {
                  $fileWithoutExtension = preg_replace('/\.[^.]*$/', '', $file);
                  ?>
                  <option data-img-src="<?php echo Constants::DIR_IMAGE_POI . "/" . $file; ?>" value="<?php echo $fileWithoutExtension; ?>"><?php echo $fileWithoutExtension; ?></option>
                  <?php
                }
              }
              $dir->close();
              ?>
            </select>
            <script>document.getElementById("image").value = "<?php echo isset($poi) ? $poi[0]->image : ""; ?>";</script>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if (isset($poi)) {
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
if (isset($poi)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-poi/<?php echo $poi[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var name = document.getElementById('name');
  var image = document.getElementById('image');

  var form = document.getElementById('form');

  if (name.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    name.focus();
  } else if (image.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    image.focus();
  } else {
    form.submit();
  }
}
</script>
