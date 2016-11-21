<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-produto')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-produto'">+ Novo Produto</button>
      <br><br>
      <?php
    }
    ?>
    <table id="products" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>C&oacute;digo</th>
          <th>Nome</th>
          <th>Pre&ccedil;o</th>
          <th>Descri&ccedil;&atilde;o</th>
          <th>Tipo de Produto</th>
          <th>Popularidade</th>
          <?php
          if(count($products) > 0 && $daoPermissao->accessPermitted('/edicao-de-produto')) {
            ?>
            <th></th>
            <th></th>
            <?php
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        if (count($products) > 0) {
          foreach ($products as $product) {
            $productType = $daoType->listOne($product->product_type);
            ?>
            <tr>
              <td><?php echo $product->code; ?></td>
              <td><?php echo $product->name; ?></td>
              <td>R$ <?php echo $product->price; ?></td>
              <td><?php echo $product->description; ?></td>
              <td><?php echo $productType[0]->name; ?></td>
              <td><?php echo $product->popularity; ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-produto')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-produto/<?php echo $product->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $product->id; ?>');">Excluir</button>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
        } else {
          ?>
          <tr><td colspan="7" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>C&oacute;digo</th>
          <th>Nome</th>
          <th>Pre&ccedil;o</th>
          <th>Descri&ccedil;&atilde;o</th>
          <th>Tipo de Produto</th>
          <?php
          if(count($products) > 0 && $daoPermissao->accessPermitted('/edicao-de-produto')) {
            ?>
            <th></th>
            <th></th>
            <?php
          }
          ?>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script>
function delRecord(id) {
  vex.dialog.confirm({
    message: 'Confirmar exclus&atilde;o?',
    callback: function(value) {
      return console.log(value ? location.href = '/delecao-de-produto/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if(count($products) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#products').dataTable();
  });
  </script>
  <?php
}
?>
