<?php
require_once("config/conexion.php");
//Enviarle el POST
if (isset($_POST["enviar"]) and $_POST["enviar"] == "si") {
  require_once("models/Usuario1.php");
  /*TODO: Inicializando Clase */
  $usuario = new Usuario1();
  $usuario->login();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="twitter:site" content="@themepixels">
  <meta name="twitter:creator" content="@themepixels">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Bracket">
  <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
  <meta name="twitter:image" content="http://themepixels.me/bracket/img/bracket-social.png">
  <meta property="og:url" content="http://themepixels.me/bracket">
  <meta property="og:title" content="Bracket">
  <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

  <meta property="og:image" content="http://themepixels.me/bracket/img/bracket-social.png">
  <meta property="og:image:secure_url" content="http://themepixels.me/bracket/img/bracket-social.png">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="600">
  <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
  <meta name="author" content="ThemePixels">

  <title>Municipalidad de Chiclayo</title>

  <link href="public/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="public/lib/Ionicons/css/ionicons.css" rel="stylesheet">
  <link rel="icon" href="public/img/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="public/css/bracket.css">
</head>

<body>

  <div class="d-flex align-items-center justify-content-center bg-br-primary ht-100v">

    <form action="" method="post">
      <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white rounded shadow-base">
        <div class="signin-logo tx-center tx-28 tx-bold tx-inverse">
          <img src="public/img/logo_2.png" width="60%">
        </div>
        <!-- Div en negrita que diga MPCH -->
        <div class="tx-center tx-16 tx-bold tx-inverse">Municipalidad Provincial de Chiclayo</div>
        <div class="tx-center tx-bold tx-inverse">Gerencia de tecnologías de la información y estadística</div>
        <div class="tx-center">Sistema de seguridad v1.0.1</div>
        <br>
        <?php
        if (isset($_GET["m"])) {
          switch ($_GET["m"]) {
            case "1":
        ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> Error
              </div>
            <?php
              break;
            case "2":
            ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> Campos vacios
              </div>
            <?php
              break;
            case "3":
            ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> No se encontraron datos
              </div>
            <?php
              break;
            case "4":
            ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> IP Persona no registrada
              </div>
            <?php
              break;
            case "5":
            ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> Persona inactiva
              </div>
            <?php
              break;
            case "6":
            ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> Fuera de la hora de acceso
              </div>
            <?php
              break;
            case "7":
            ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> Usuario no vigente
              </div>
            <?php
              break;
            case "8":
            ?>
              <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong class="d-block d-sm-inline-block-force">Error!</strong> Datos incorrectos
              </div>
        <?php
              break;
          }
        }
        ?>
        <div class="form-group">
          <input type="text" id="dni" name="dni" class="form-control" placeholder="Ingrese su dni">
        </div><!-- form-group -->
        <div class="form-group">
          <input type="password" id="pass" name="pass" class="form-control" placeholder="Ingrese su contraseña">
          <a href="view/USURecuperacionContra/index.php?sistema=Seguridad" class="tx-info tx-12 d-block mg-t-10">Olvidó su contraseña?</a>
        </div><!-- form-group -->
        <input type="hidden" name="enviar" class="form-control" value="si">
        <button type="submit" class="btn btn-info btn-block">Ingresar</button>
      </div><!-- login-wrapper -->
    </form>
  </div><!-- d-flex -->

  <script src="public/lib/jquery/jquery.js"></script>
  <script src="public/lib/popper.js/popper.js"></script>
  <script src="public/lib/bootstrap/bootstrap.js"></script>

</body>

</html>