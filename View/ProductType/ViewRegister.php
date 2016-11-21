<?php use App\Constants\Constants; ?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/tipo-de-produto">
          <?php
          if (isset($productType)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $productType[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($productType) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($productType) ? $productType[0]->name : ""; ?>">
          </div>
          <div class="form-group">
            <label>Prioridade</label>
            <input type="number" class="form-control" name="priority" id="priority" value="<?php echo isset($productType) ? $productType[0]->priority : ""; ?>">
          </div>
          <div class="form-group">
            <label>Imagem do &Iacute;cone</label>
            <select class="imagepicker" name="buttonImage" id="buttonImage">
              <?php
              $dir = dir($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON);

              while($file = $dir->read()) {
                if($file != ".." && $file != ".") {
                  $fileWithoutExtension = preg_replace('/\.[^.]*$/', '', $file);
                  ?>
                  <option data-img-src="<?php echo Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON . "/" . $file; ?>" value="<?php echo $fileWithoutExtension; ?>"><?php echo $fileWithoutExtension; ?></option>
                  <?php
                }
              }
              $dir->close();
              ?>
            </select>
            <script>document.getElementById("buttonImage").value = "<?php echo isset($productType) ? $productType[0]->button_image : ""; ?>";</script>
          </div>
          <div class="form-group">
            <label>Cor de Fundo</label>
            <input name="color" id="color" type="text" class="form-control colorpicker" value="<?php echo isset($productType) ? $productType[0]->color : ""; ?>"/>
          </div>
          <div id="divDestination" class="form-group">
            <label>Destino</label>
            </br>
            <a id="newDestination" href="#" style="color: #00cc00" onclick="insertOrUpdateDestination()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo</a>
            <select style="margin-top: 5px;" class="form-control" name="destination" id="destination">
              <option value="-1">Selecione abaixo</option>
              <?php
              foreach($destinations as $destination) {
                ?>
                <option value="<?php echo $destination->id; ?>"><?php echo $destination->name; ?></option>
                <?php
              }
              ?>
            </select>
            <?php
            if(isset($productType)) {
              ?>
              <script>document.getElementById('destination').value = "<?php echo $productType[0]->destination; ?>";</script>
              <?php
            }
            ?>
          </div>
          <div id="divIconName" style="display: none" class="form-group">
            <label>Imagem do Destino</label>
            <select class="imagepicker" name="iconName" id="iconName">
              <?php
              $dir = dir($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON);

              while($file = $dir->read()) {
                if($file != ".." && $file != ".") {
                  $fileWithoutExtension = preg_replace('/\.[^.]*$/', '', $file);
                  ?>
                  <option data-img-src="<?php echo Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON . "/" . $file; ?>" value="<?php echo $fileWithoutExtension; ?>"><?php echo $fileWithoutExtension; ?></option>
                  <?php
                }
              }
              $dir->close();
              ?>
            </select>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if (isset($productType)) {
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
if (isset($productType)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-tipo-de-produto/<?php echo $productType[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var name = document.getElementById('name');
  var priority = document.getElementById('priority');
  var tabImage = document.getElementById('tabImage');
  var buttonImage = document.getElementById('buttonImage');
  var color = document.getElementById('color');
  var iconImage = document.getElementById('iconImage');
  var destination = document.getElementById('destination');
  var destinationName = document.getElementById('destinationName');

  var form = document.getElementById('form');

  if(name.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    name.focus();
  } else if(priority.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    priority.focus();
  } else if(buttonImage.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    buttonImage.focus();
  } else if(color.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    color.focus();
  } else if(destination != null && destination.value <= 0) {
    vex.dialog.alert('Preencha todos os campos!');
    destination.focus();
  } else if(destinationName != null && destinationName.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    destination.focus();
  } else if(destinationName != null && iconName.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    destination.focus();
  } else {
    form.submit();
  }
}

function insertOrUpdateDestination(){
  var divDestination = document.getElementById('divDestination');
  document.getElementById("destination").remove();
  document.getElementById("newDestination").remove();
  var input = document.createElement('input');
  input.id = "destinationName";
  input.className = "form-control";
  input.name = "destinationName";
  divDestination.appendChild(input);

  var divIconName = document.getElementById('divIconName');
  divIconName.style.display = 'block';
}
</script>
