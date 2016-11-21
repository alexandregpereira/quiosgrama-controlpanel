<?php use App\Constants\Constants; ?>
<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-poi')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-poi'">+ Novo Poi</button>
      <br><br>
      <?php
    }
    ?>
    <table id="pois" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Imagem</th>
          <?php
          if (count($pois) > 0 && $daoPermissao->accessPermitted('/edicao-de-poi')) {
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
        if (count($pois) > 0) {
          foreach ($pois as $poi) {
            ?>
            <tr>
              <td><?php echo $poi->name; ?></td>
              <td>
                <button class="btn btn-primary" onclick="exibeImagem('<?php
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_POI . "/" . $poi->image . ".png")) {
                  echo Constants::DIR_IMAGE_POI . "/" . $poi->image . ".png";
                } elseif(file_exists($_SERVER['DOCUMENT_ROOT'] . Constants::DIR_IMAGE_POI . "/" . $poi->image . ".jpg")) {
                  echo Constants::DIR_IMAGE_POI . "/" . $poi->image . ".jpg";
                } else {
                  echo 0;
                }
                ?>');">Exibir</button>
              </td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-poi')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-poi/<?php echo $poi->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $poi->id; ?>');">Excluir</button>
                </td>
                <?php
              }
              ?>
            </tr>
            <?php
          }
        } else {
          ?>
          <tr><td colspan="5" class="nenhum">Nenhum item encontrado.</td></tr>
          <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Nome</th>
          <th>Imagem</th>
          <?php
          if(count($pois) > 0 && $daoPermissao->accessPermitted('/edicao-de-poi')) {
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
      return console.log(value ? location.href = '/delecao-de-poi/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if (count($pois) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#pois').dataTable();
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
