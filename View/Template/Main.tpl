<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

// Inclui pacotes necessarios
use App\Constants\Constants;
use App\Model\User;
use App\Utility\Utilities;
use App\Db\Dao\Impl\ScreenDaoImpl;
use App\Db\Dao\Impl\ModuleDaoImpl;
use App\Db\Dao\Impl\UserPermissionDaoImpl;
use App\Db\Dao\Impl\RequestDaoImpl;

$utilities = new Utilities();

$_CURRENT_USER = unserialize($_SESSION['QUIOSGRAMA']['USER']);

?>
<!DOCTYPE html>
<html id="tagHtml">
<head>
  <meta charset="UTF-8">
  <title>Quiosgrama<?php echo isset($page_title) ? " | " . $page_title : ""; ?></title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

  <!-- CSS -->

  <link href="/Resources/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/icheck/flat/blue.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/vex.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/vex-theme-os.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/image-picker.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
  <link href="/Resources/dist/css/quiosgrama.css" rel="stylesheet" type="text/css" />

  <!-- CSS -->

  <!-- JavaScript -->

  <!-- Atencao! Os JSs abaixo devem ficar no inicio da pagina para o funcionamento dos alerts personalizados, das constantes javascript, dos jquery forms e do efeito de retacao. -->

  <script src="/Resources/plugins/jQuery/jQuery-2.1.3.min.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/jquery-ui.min.js" type="text/javascript"></script>

  <script>
  $.widget.bridge('uibutton', $.ui.button);
  $.widget.bridge('uitooltip', $.ui.tooltip);
  </script>

  <!--<script src="/Resources/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>-->
  <script src="/Resources/dist/js/jquery.blockUI.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/vex.combined.min.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/app.min.js" type="text/javascript"></script>
  <script src="/Resources/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
  <script src="/Resources/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/jquery.jplayer.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/jplayer.playlist.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/raphael-min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/morris/morris.min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/image-picker.js" type="text/javascript"></script>
  <script src="/Resources/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/quiosgrama.useful.js" type="text/javascript"></script>
  <script type="text/javascript">
  vex.defaultOptions.className = 'vex-theme-os';
  var socketAddress = '<?php echo getenv('QUIOSGRAMA_SOCKET_ADDRESS') ?>';
  var socketPort = '<?php echo getenv('QUIOSGRAMA_SOCKET_PORT') ?>';
  var flagBlockUI = '';
  </script>

  <!-- JavaScript -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
</head>
<body class="skin-blue" style="margin-bottom: -20px;">
  <div class="wrapper">
    <header class="main-header">
      <a href="/dashboard" class="logo" style="background-color: #6e4229; min-height: 60px;">
        <img src="/Resources/Img/logo.png" style="max-width: 200px; margin-top: 6px; margin-left: -5px;"/>
      </a>
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="padding: 20px 20px !important;">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" style="padding: 20px 20px !important;">
                <i class="fa fa-bell-o"></i>
                <?php
                  $daoRequests = new RequestDaoImpl();
                  $newRequestsCount = $daoRequests->countNewRequests();
                ?>
                <span class="label label-warning" style="top: 15px !important; right: 12px !important;" id="qtdRequestsPane"><?php echo $newRequestsCount[0]->count; ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="header" id="innerQtdRequestsPane"><?php
                    if($newRequestsCount[0]->count == 0) echo "N&atilde;o existem novos pedidos";
                    elseif($newRequestsCount[0]->count == 1) echo "Voc&ecirc; tem 1 novo pedido";
                    else echo "Voc&ecirc; tem " . $newRequestsCount[0]->count . " novos pedidos";
                  ?></li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;">
                    <ul class="menu" style="overflow: hidden; width: 100%; height: 200px;" id="newRequestsPane">
                      <?php
                        $newRequests = $daoRequests->listNewRequests(5);
                        foreach($newRequests as $newRequest) {
                          $currentDate = strtotime(date('M j, Y g:i:s A'));
                          $requestTime = strtotime($newRequest->time);
                          $diffMinutes = round(($currentDate - $requestTime) / 60);
                          ?>
                            <li><a href="#" class="requestItem" requestId="<?php echo $newRequest->request; ?>"><i class="fa fa-cutlery text-<?php
                              if($diffMinutes < 30) echo "green";
                              elseif($diffMinutes >= 30 && $diffMinutes < 60) echo "yellow";
                              else echo "red";
                            ?>"></i> Mesa <?php echo $newRequest->table_number; ?> - <?php echo $newRequest->products; ?> <?php echo $newRequest->products > 1 ? "itens" : "item"; ?></a></li>
                          <?php
                        }
                      ?>
                    </ul>
                    <div class="slimScrollBar" style="width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.122px; background: rgb(0, 0, 0);">
                    </div>
                    <div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);">
                    </div>
                  </div>
                </li>
                <li class="footer"><a href="#" id="seeAllRequests">Ver todos</a></li>
              </ul>
            </li>
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 20px 20px !important;">
                <!--<img src="/Resources/Img/default-image-user.png" class="user-image" alt="User Image"/>-->
                <span class="hidden-xs"><?php echo $_CURRENT_USER->getName(); ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header" style="height: 85px;">
                  <!--<img src="/Resources/Img/default-image-user.png" class="img-circle" alt="User Image" />-->
                  <p><?php echo $_CURRENT_USER->getName(); ?> <small>Membro Desde <?php
                  $resgisterDateAux = explode('-', $_CURRENT_USER->getRegisterDate());
                  echo $utilities->returnAbbreviatedMonthByNumber($resgisterDateAux[1]) . '-' . $resgisterDateAux[0];
                  ?></small></p>
                </li>
                <?php
                if($_CURRENT_USER->getAdministrator() == 1) {
                  ?>
                  <li class="user-body">
                    <div class="col-xs-12 text-center">
                      <a href="/edicao-de-usuario/<?php echo $_CURRENT_USER->getId(); ?>">Meu Cadastro</a>
                    </div>
                  </li>
                  <?php
                }
                ?>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="/alterar-minha-senha" class="btn btn-default btn-flat">Alterar Minha Senha</a>
                  </div>
                  <div class="pull-right">
                    <a href="/logout" class="btn btn-default btn-flat">Sair</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <aside class="main-sidebar" style="padding-top: 60px !important;">
      <section class="sidebar">
        <div class="user-panel">
          <!--<div class="pull-left image">
            <img src="/Resources/Img/default-image-user.png" class="img-circle" alt="User Image" />
          </div>-->
          <div class="pull-left info">
            <p><?php echo $_CURRENT_USER->getName(); ?></p>
            <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
          </div>
        </div>
        <ul class="sidebar-menu">
          <li class="header">MENU</li>
          <li><a href="/dashboard"><i class="fa fa-home"></i> <span>In&iacute;cio</span></a></li>
          <?php
          $daoModuleMain = new ModuleDaoImpl();
          $daoScreenMain = new ScreenDaoImpl();
          $daoPermissionMain = new UserPermissionDaoImpl();
          $modulesMain = $daoModuleMain->listAll();

          foreach($modulesMain as $moduleMain) {
            if($daoModuleMain->showModuleMenu($moduleMain->id)) {
              $screensMain = $daoScreenMain->listByModuleListOnTheScreen($moduleMain->id, $_CURRENT_USER->getAdministrator());
              ?>
              <li class="treeview">
                <a href="#">
                  <i class="fa fa-files-o"></i>&nbsp;<span><?php echo $moduleMain->description; ?></span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <?php
                  foreach ($screensMain as $screenMain) {
                    if($daoPermissionMain->accessPermitted($screenMain->url)) {
                      ?>
                      <li><a href="<?php echo $screenMain->url; ?>"><i class="fa fa-circle-o"></i> <?php echo $screenMain->description; ?></a></li>
                      <?php
                    }
                  }
                  ?>
                </ul>
              </li>
              <?php
            }
          }
          ?>
        </ul>
      </section>
    </aside>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <span class="headerText">
            <?php echo isset($body_page_title) ? $body_page_title : ""; ?>
            <small><?php echo isset($body_page_subtitle) ? $body_page_subtitle : ""; ?></small>
          </span>
        </h1>
        <ol class="breadcrumb">
          <?php
          $breadcrumb = explode('/', $_SERVER['PHP_SELF']);
          for ($i = 1; $i < count($breadcrumb); $i++) {
            $dir = $breadcrumb[$i];

            if ($i == 1 && count($breadcrumb) > 1) {
              echo "<li><a href='/dashboard'><i class='fa fa-home' style='font-size: 15px;'></i>&nbsp;&nbsp;&nbsp;In&iacute;cio</a></li>";
            } elseif ($i == 1 && count($breadcrumb) == 1) {
              echo "<li><i class='fa fa-home' style='font-size: 15px;'></i>&nbsp;&nbsp;&nbsp;In&iacute;cio</li>";
            } elseif (trim($dir) != "" && trim($dir) != "Home" && $utilities->allowBreadcrumb($dir)) {
              $dirOriginal = $dir;
              $dir = $utilities->replaceBreadcrumb($dir);

              if ($i == count($breadcrumb) - 1 || ($i == count($breadcrumb) - 2 && (!$utilities->allowBreadcrumb($breadcrumb[$i + 1]) || trim($breadcrumb[$i + 1] == "")))) {
                echo "<li class='active'>" . $dir . "</li>";
              } else {
                echo "<li><a href='" . $utilities->getBreadcrumbLink($dirOriginal) . "'> " . $dir . "</a></li>";
              }
            }
          }
          ?>
        </ol>
      </section>
      <section class="content">

        <!-- INICIO CONTEUDO -->

        <?php
        if (isset($inc_pg_body) && file_exists($inc_pg_body)) {
          include ($inc_pg_body);
        } else {
          header("Location:/erro-404");
        }
        ?>

      </section>
      <!-- FIM CONTEUDO -->

    </div>
    <footer class="main-footer"><strong>&copy; Copyright <?php echo date('Y'); ?> <a href="#" target="_blank"> Quiosgrama</a></strong></footer>
  </div>
  <div class="quios-clear"></div>
  <?php
    include(getenv('QUIOSGRAMA_SYSTEM_PATH') . "/View/RequestsPane/RequestsPane.php");
  ?>
  <div class="quios-clear"></div>
  <script src="/Resources/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
  <script src="/Resources/plugins/knob/jquery.knob.js" type="text/javascript"></script>
  <script src="/Resources/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
  <script src="/Resources/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
  <script src="/Resources/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/icheck/icheck.min.js" type="text/javascript"></script>
  <script src="/Resources/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <script src='/Resources/plugins/fastclick/fastclick.min.js'></script>
  <script src="/Resources/dist/js/jquery.maskedinput.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/quiosgrama.websocket.js" type="text/javascript"></script>
  <script src="/Resources/dist/js/quiosgrama.requestspane.js" type="text/javascript"></script>
  <!-- JavaScript -->

  <script type="text/javascript">
  $(".dateFromTo").daterangepicker();
  $(".date").datepicker();

  $(".cpf").mask("999.999.999-99");
  $(".cnpj").mask("99.999.999/9999-99");
  $(".phone").mask("(99) 9999-9999");

  $(".sparkline").each(function() { var $this = $(this); $this.sparkline('html', $this.data()); });

  $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

  $(".imagepicker").imagepicker();

  $(".colorpicker").colorpicker();
  </script>

  <!-- JavaScript -->
</body>
</html>
