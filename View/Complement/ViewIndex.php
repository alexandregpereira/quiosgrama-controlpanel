<?php use App\Constants\Constants; ?>
<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-complemento')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-complemento'">+ Novo Complemento</button>
      <br><br>
      <?php
    }
    ?>
    <table id="complements" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Descri&ccedil;&atilde;o</th>
          <th>Pre&ccedil;o</th>
          <th>Imagem</th>
          <th>V&aacute;lido para</th>
          <?php
          if (count($complements) > 0 && $daoPermissao->accessPermitted('/edicao-de-complemento')) {
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
        if (count($complements) > 0) {
          foreach ($complements as $complement) {
            $complementTypes = $daoComplementType->listAllByComplement($complement->id);
            ?>
            <tr>
              <td><?php echo $complement->description; ?></td>
              <td>R$ <?php echo $complement->price; ?></td>
              <td>
                <button class="btn btn-primary" onclick="exibeImagem('<?php
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_COMPLEMENT . "/" . $complement->drawable . ".png")) {
                  echo Constants::DIR_IMAGE_COMPLEMENT . "/" . $complement->drawable . ".png";
                } elseif(file_exists($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_COMPLEMENT . "/" . $complement->drawable . ".jpg")) {
                  echo Constants::DIR_IMAGE_COMPLEMENT . "/" . $complement->drawable . ".jpg";
                } else {
                  echo 0;
                }
                ?>');">Exibir</button>
              </td>
              <td>
                <button class="btn btn-primary" onclick="vex.dialog.alert('<?php
                $stringProductTypes = "";
                foreach($complementTypes as $complementType) {
                  $productType = $daoProductType->listOne($complementType->product_type);
                  $stringProductTypes .= $productType[0]->name . "<br/>";
                }
                echo (trim($stringProductTypes) != "") ? $stringProductTypes : "Nenhum tipo de produto relacionado a esse complemento";
                ?>');">Exibir</button>
              </td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-complemento')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-complemento/<?php echo $complement->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $complement->id; ?>');">Excluir</button>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
        } else {
          ?>
          <tr><td colspan="6" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Descri&ccedil;&atilde;o</th>
          <th>Pre&ccedil;o</th>
          <th>Imagem</th>
          <th>V&aacute;lido para</th>
          <?php
          if (count($complements) > 0 && $daoPermissao->accessPermitted('/edicao-de-complemento')) {
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
      return console.log(value ? location.href = '/delecao-de-complemento/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if (count($complements) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#complements').dataTable();
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
