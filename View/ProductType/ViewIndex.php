<?php use App\Constants\Constants; ?>
<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-tipo-de-produto')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-tipo-de-produto'">+ Novo Tipo de Produto</button>
      <br><br>
      <?php
    }
    ?>
    <table id="productTypes" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Prioridade</th>
          <th>Imagem do &Iacute;cone</th>
          <th>Cor de Fundo</th>
          <th>Destino</th>
          <?php
          if (count($productTypes) > 0 && $daoPermissao->accessPermitted('/edicao-de-tipo-de-produto')) {
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
        if (count($productTypes) > 0) {
          foreach ($productTypes as $productType) {
            ?>
            <tr>
              <td><?php echo $productType->name; ?></td>
              <td><?php echo $productType->priority; ?></td>
              <td>
                <button class="btn btn-primary" onclick="exibeImagem('<?php
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON . "/" . $productType->button_image . ".png")) {
                  echo Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON . "/" . $productType->button_image . ".png";
                } elseif(file_exists($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON . "/" . $productType->button_image . ".jpg")) {
                  echo Constants::DIR_IMAGE_PRODUCT_TYPE_BUTTON . "/" . $productType->button_image . ".jpg";
                } else {
                  echo 0;
                }
                ?>');">Exibir</button>
              </td>
              <td style="background-color: <?php echo $productType->color; ?>"></td>
              <td><?php echo $productType->destination_name; ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-tipo-de-produto')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-tipo-de-produto/<?php echo $productType->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $productType->id; ?>');">Excluir</button>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
        } else {
          ?>
          <tr><td colspan="8" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Nome</th>
          <th>Prioridade</th>
          <th>Imagem do &Iacute;cone</th>
          <th>Cor de Fundo</th>
          <th>Destino</th>
          <?php
          if(count($productTypes) > 0 && $daoPermissao->accessPermitted('/edicao-de-tipo-de-produto')) {
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
      return console.log(value ? location.href = '/delecao-de-tipo-de-produto/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if (count($productTypes) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#productTypes').dataTable();
  });

  function exibeImagem(img) {
    if(img == '0') {
      vex.dialog.alert('Imagem n&atilde;o encontrada!');
    } else {
      vex.dialog.alert('<img style="margin-top: 20px; padding: 4px; line-height: 1.42857143; background-color: #fff; border: 1px solid #ddd; border-radius: 4px; -webkit-transition: border .2s ease-in-out; -o-transition: border .2s ease-in-out; transition: border .2s ease-in-out; padding: 6px; -moz-user-select: none; -ms-user-select: none; " src="' + img + '">');
    }
  }
  </script>
  <?php
}
?>
