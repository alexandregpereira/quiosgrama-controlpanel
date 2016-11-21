<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

  <title>Login | Quiosgrama</title>

  <link href="/Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/icheck/square/blue.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/vex.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/vex-theme-os.css" rel="stylesheet" type="text/css" />

  <!-- Atencao! Os JS abaixo devem ficar no inicio da pagina para o funcionamento dos alerts personalizados. -->
  <script src="/Resources/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <script src="/Resources/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/icheck/icheck.min.js" type="text/javascript"></script>


  <script src="/Resources/dist/js/jquery-1.9.1.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/vex.combined.min.js" type="text/javascript"></script>
  <script type="text/javascript">
  vex.defaultOptions.className = 'vex-theme-os';
  </script>
</head>
<body class="login-page">
  <div class="login-box">
    <div class="login-logo">
      <img src="/Resources/Img/logo.png" style="max-width: 340px;"/>
    </div>
    <div class="login-box-body">
      <form action="/fazer-login" method="post">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="user" placeholder="Usu&aacute;rio"/>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" placeholder="Senha"/>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" style="width: 5px;" name="stayConnected"><span style="margin-left: 10px;">Mantenha-me conectado</span>
              </label>
            </div>
          </div>
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>
          </div>
        </div>
      </form>
      <a href="#" onclick="showForgotMyPasswordDiv();">Esqueci minha senha</a><br>
    </div>
    <br/><br/>
    <div class="login-box-body" id="forgotMyPassword" style="display: none;">
      <form action="/esqueci-minha-senha" method="post">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="user" placeholder="Usu&aacute;rio"/>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Enviar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
  </script>
  <?php
  if($utilities->getParameter('msg') == "login-incorreto") {
    ?>
    <script>
    vex.dialog.alert('Informa&ccedil;&otilde;es incorretas!');
    </script>
    <?php
  } elseif($utilities->getParameter('msg') == "usuario-invalido") {
    ?>
    <script>
    vex.dialog.alert('O usu&aacute;rio inserido &eacute; inv&aacute;lido!');
    </script>
    <?php
  } elseif($utilities->getParameter('msg') == "senha-enviada") {
    ?>
    <script>
    vex.dialog.alert('Senha enviada com sucesso!');
    </script>
    <?php
  }
  ?>
  <script>
  function showForgotMyPasswordDiv() {
    if(document.getElementById('forgotMyPassword').style.display == '') {
      document.getElementById('forgotMyPassword').style.display = 'none';
    } else {
      document.getElementById('forgotMyPassword').style.display = '';
    }
  }
  </script>
</body>
</html>
