<?php use App\Constants\Constants; ?>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/complemento">
          <?php
          if(isset($complement)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $complement[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($complement) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>Descri&ccedil;&atilde;o</label>
            <input type="text" class="form-control" name="description" id="description" value="<?php echo isset($complement) ? $complement[0]->description : ""; ?>">
          </div>
          <div class="form-group">
            <label>Pre&ccedil;o</label>
            <input type="number" class="form-control" name="price" id="price" value="<?php echo isset($complement) ? $complement[0]->price : ""; ?>">
          </div>
          <div class="form-group">
            <label>Complemento v&aacute;lido para</label>
            <select multiple class="form-control" name="productTypes[]" id="productTypes">
              <?php
              foreach($productTypes as $productType) {
                ?>
                <option value="<?php echo $productType->id; ?>"><?php echo $productType->name; ?></option>
                <?php
              }
              ?>
            </select>
            <?php
            if(isset($complement)) {
              ?>
              <script>
              var values = [<?php
                $stringComplementTypes = "";
                foreach($complementTypes as $complementType) {
                  $stringComplementTypes .= "'" . $complementType->product_type . "', ";
                }
                $stringComplementTypes = rtrim($stringComplementTypes, " ");
                $stringComplementTypes = rtrim($stringComplementTypes, ",");
                echo $stringComplementTypes;
                ?>];
                var selectObj = document.getElementById('productTypes');

                for(var j = 0; j < values.length; j++) {
                  for(var i = 0; i < selectObj.length; i++) {
                    if(selectObj.options[i].value == values[j]) {
                      selectObj.options[i].selected = true;
                    }
                  }
                }
                </script>
                <?php
              }
              ?>
            </div>
            <div class="form-group">
              <label>Imagem</label>
              <select class="imagepicker" name="drawable" id="drawable">
                <?php
                $dir = dir($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_COMPLEMENT);

                while($file = $dir->read()) {
                  if($file != ".." && $file != ".") {
                    $fileWithoutExtension = preg_replace('/\.[^.]*$/', '', $file);
                    ?>
                    <option data-img-src="<?php echo Constants::DIR_IMAGE_COMPLEMENT . "/" . $file; ?>" value="<?php echo $fileWithoutExtension; ?>"><?php echo $fileWithoutExtension; ?></option>
                    <?php
                  }
                }
                $dir->close();
                ?>
              </select>
              <script>document.getElementById("drawable").value = "<?php echo isset($complement) ? $complement[0]->drawable : ""; ?>";</script>
            </div>
            <div class="box-footer">
              <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
              <?php
              if(isset($complement)) {
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
  if (isset($complement)) {
    ?>
    function delRecord() {
      vex.dialog.confirm({
        message: 'Confirmar exclus&atilde;o?',
        callback: function(value) {
          return console.log(value ? location.href = '/delecao-de-complemento/<?php echo $complement[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
        }
      });
    }
    <?php
  }
  ?>

  function submitForm() {
    var description = document.getElementById('description');
    var drawable = document.getElementById('drawable');

    var form = document.getElementById('form');

    if(description.value == '') {
      vex.dialog.alert('Preencha a descri&ccedil;&atilde;o!');
      description.focus();
    } else {
      form.submit();
    }
  }
  </script>
