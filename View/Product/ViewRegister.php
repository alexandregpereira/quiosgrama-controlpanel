<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <form id="form" method="post" action="/produto">
          <?php
          if(isset($product)) {
            ?>
            <input type="hidden" name="id" value="<?php echo $product[0]->id; ?>"/>
            <?php
          }
          ?>
          <input type="hidden" name="action" value="<?php echo isset($product) ? "upd" : "add"; ?>"/>
          <div class="form-group">
            <label>C&oacute;digo</label>
            <input type="text" class="form-control" name="code" id="code" value="<?php echo isset($product) ? $product[0]->code : ""; ?>">
          </div>
          <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($product) ? $product[0]->name : ""; ?>">
          </div>
          <div class="form-group">
            <label>Pre&ccedil;o</label>
            <input type="text" class="form-control" name="price" id="price" value="<?php echo isset($product) ? $product[0]->price : ""; ?>">
          </div>
          <div class="form-group">
            <label>Descri&ccedil;&atilde;o</label>
            <input type="text" class="form-control" name="description" id="description" value="<?php echo isset($product) ? $product[0]->description : ""; ?>">
          </div>
          <div class="form-group">
            <label>Popularidade</label>
            <input type="text" class="form-control" name="popularity" id="popularity" value="<?php echo isset($product) ? $product[0]->popularity : ""; ?>">
          </div>
          <div class="form-group">
            <label>Tipo de Produto</label>
            <select class="form-control" name="productType" id="productType">
              <option value="">Selecione abaixo</option>
              <?php
              foreach($types as $type) {
                ?>
                <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                <?php
              }
              ?>
            </select>
            <script>
            <?php
            if(isset($product)) {
              ?>
              document.getElementById('productType').value = '<?php echo $product[0]->product_type; ?>';
              <?php
            }
            ?>
            </script>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary" onclick="submitForm();">Enviar</button>
            <?php
            if(isset($product)) {
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
if (isset($product)) {
  ?>
  function delRecord() {
    vex.dialog.confirm({
      message: 'Confirmar exclus&atilde;o?',
      callback: function(value) {
        return console.log(value ? location.href = '/delecao-de-produto/<?php echo $product[0]->id; ?>' : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
      }
    });
  }
  <?php
}
?>

function submitForm() {
  var code = document.getElementById('code');
  var name = document.getElementById('name');
  var price = document.getElementById('price');
  var description = document.getElementById('description');
  var productType = document.getElementById('productType');

  var form = document.getElementById('form');

  if(code.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    name.focus();
  } else if(name.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    name.focus();
  } else if (price.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    price.focus();
  } else if (productType.value == '') {
    vex.dialog.alert('Preencha todos os campos!');
    productType.focus();
  } else {
    form.submit();
  }
}
</script>
