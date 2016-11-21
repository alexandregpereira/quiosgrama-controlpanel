<div class="box">
  <div class="box-body table-responsive">
    <?php
    if($daoPermissao->accessPermitted('/cadastro-de-cliente')) {
      ?>
      <button class="btn btn-info btn-sm" onclick="window.location='/cadastro-de-cliente'">+ Novo Cliente</button>
      <br><br>
      <?php
    }
    ?>
    <table id="clients" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>CPF</th>
          <th>Telefone</th>
          <th>Cliente Tempor&aacute;rio</th>
          <th>Cliente Presente</th>
          <th>Mesa</th>
          <?php
          if (count($clients) > 0 && $daoPermissao->accessPermitted('/edicao-de-cliente')) {
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
        if (count($clients) > 0) {
          foreach ($clients as $client) {
            $table = $daoTable->listOne($client->table);
            ?>
            <tr>
              <td><?php echo $client->name; ?></td>
              <td><?php echo $utilities->formatCPF($client->cpf); ?></td>
              <td><?php echo $utilities->formatDDDPhone($client->phone); ?></td>
              <td><?php
              switch($client->temp_flag) {
                case 0:
                echo "N&atilde;o";
                break;
                case 1:
                echo "Sim";
                break;
                default:
                echo "N&atilde;o";
                break;
              }
              ?></td>
              <td><?php
              switch($client->present_flag) {
                case 0:
                echo "N&atilde;o";
                break;
                case 1:
                echo "Sim";
                break;
                default:
                echo "N&atilde;o";
                break;
              }
              ?></td>
              <td><?php echo isset($table[0]->number) ? $table[0]->number : "-"; ?></td>
              <?php
              if($daoPermissao->accessPermitted('/edicao-de-cliente')) {
                ?>
                <td>
                  <button type="button" class="btn btn-primary" onclick="window.location = '/edicao-de-cliente/<?php echo $client->id; ?>'">Editar</button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger" onclick="delRecord('<?php echo $client->id; ?>');">Excluir</button>
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
          <th>CPF</th>
          <th>Telefone</th>
          <th>Cliente Tempor&aacute;rio</th>
          <th>Cliente Presente</th>
          <th>Mesa</th>
          <?php
          if (count($clients) > 0 && $daoPermissao->accessPermitted('/edicao-de-cliente')) {
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
      return console.log(value ? location.href = '/delecao-de-cliente/' + id : vex.dialog.alert('Exclus&atilde;o n&atilde;o realizada'));
    }
  });
}
</script>
<?php
if (count($clients) > 0) {
  ?>
  <script type="text/javascript">
  $(function() {
    $('#clients').dataTable();
  });
  </script>
  <?php
}
?>
