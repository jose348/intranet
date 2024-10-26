<?php

require_once("config/conexion.php");
if (isset($_POST["enviar"])  and $_POST["enviar"] = "si") {
  require_once("models/Usuario1.php");
  $usuario = new Usuario1();
  $usuario->Login();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Twitter -->
  <meta name="twitter:site" content="@themepixels">
  <meta name="twitter:creator" content="@themepixels">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Bracket">
  <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
  <meta name="twitter:image" content="http://themepixels.me/bracket/img/bracket-social.png">

  <!-- Facebook -->
  <meta property="og:url" content="http://themepixels.me/bracket">
  <meta property="og:title" content="Bracket">
  <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

  <meta property="og:image" content="http://themepixels.me/bracket/img/bracket-social.png">
  <meta property="og:image:secure_url" content="http://themepixels.me/bracket/img/bracket-social.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="600">

  <!-- Meta -->
  <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
  <meta name="author" content="ThemePixels">

  <title>SISE</title>

  <!-- vendor css -->
  <link href="./public/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="./public/lib/Ionicons/css/ionicons.css" rel="stylesheet">



  <!-- Bracket CSS -->
  <link rel="stylesheet" href="./public/css/bracket.css">
</head>

<body>

  <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">

    <form action="" method="POST" id="login-form">
      <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">

        <!-- Capturando los mensajes de erro del LOGIN -->
        <?php
        if (isset($_GET["m"])) {
          switch ($_GET["m"]) {
            case "1";
          ?>
              <div class="alert alert-warning" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">  X</span>
                </button>
                <div class="d-flex align-items-center justify-content-start">
                  <i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
                  <span><strong>Error</strong> Usuario y/o contraseña Incorrectos. </span>
                </div><!-- d-flex -->
      </div><!-- alert -->
      <?php
              break;
            case "2";
        ?>
      <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">  X</span>
        </button>
        <div class="d-flex align-items-center justify-content-start">
          <i class="icon ion-ios-checkmark alert-icon tx-32 mg-t-5 mg-xs-t-0"></i>
          <span><strong>Error!</strong> Ingrese Credenciales Correctamente. </span>
        </div><!-- d-flex -->
      </div><!-- alert -->
      <?php
              break;
          }
        }
        ?>
        


        <div class="signin-logo tx-center tx-28 tx-bold tx-inverse"><span class="tx-normal"></span> <img src="public/img/muni.png" alt="" width="200"> <span class="tx-normal"></span></div>
        <hr>
        <div class="tx-center mg-b-30">
          <h6 class="text text-info">Sistema de Servicios Integrales (SISE)</h6>
        </div>




        <div class="input-group">
          <span class="input-group-addon"><i class="icon ion-card tx-16 lh-0 op-6"></i></span>
          <input type="number" class="form-control" placeholder="Ingrese Dni" id="dni" name="dni">
        </div>
        <br>


        <div class="input-group">
          <span class="input-group-addon"><i class="icon ion-more tx-16 lh-0 op-6"></i></span>
          <input type="password" class="form-control" placeholder="Ingrese Contraseña" id="pass" name="pass">
        </div>

          <a href="http://216.244.171.252/sisSeguridad/view/USURecuperacionContra/index.php?sistema=Asistencia" class="tx-info tx-12 d-block mg-t-10">Olvidó su contraseña?</a>
        <br>
        <input type="hidden" name="enviar" class="form-control" value="si">
        <button type="submit" class="btn btn-info btn-block">Ingresar</button>


      </div><!-- login-wrapper -->
    </form>
    

  </div><!-- d-flex -->

  <script src="/public/lib/jquery/jquery.js"></script>
  <script src="/public/lib/popper.js/popper.js"></script>
  <script src="/public//bootstrap/bootstrap.js"></script>


 <!--  alert-IU -->
  <script src="files/js/lib/jquery/jquery.min.js"></script>
    <script src="files/js/lib/tether/tether.min.js"></script>
    <script src="files/js/lib/bootstrap/bootstrap.min.js"></script>
    <script src="files/js/plugins.js"></script>
    <script type="text/javascript" src="files/js/lib/match-height/jquery.matchHeight.min.js"></script>
    
    
    <script>
        $(function() {
            $('.page-center').matchHeight({
                target: $('html')
            });

            $(window).resize(function() {
                setTimeout(function() {
                    $('.page-center').matchHeight({
                        remove: true
                    });
                    $('.page-center').matchHeight({
                        target: $('html')
                    });
                }, 100);
            });
        });
    </script>
    <script src="files/js/app.js"></script>

</body>

</html>