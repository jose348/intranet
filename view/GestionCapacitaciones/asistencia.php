<?php


require_once("../../config/conexion.php");
if (isset($_SESSION["id"])) {
    $usuario_id = $_SESSION["id"];
    echo "<script>console.log('Usuario ID en PHP: " . $usuario_id . "');</script>";
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php
        require_once("../Head/MainHead.php");
        ?>
        <title>Capacitaciones - Asistencias</title>
       
    </head>

    <body>
        <?php
        require_once("../Menu/menu.php");

        ?>
        <!-- ########## END: LEFT PANEL ########## -->

        <?php
        require_once("../Header/MainHeader.php");
        ?>

 








        <?php
        require_once("../Js/MainJs.php");
        ?>
 
        <script type="text/javascript" src="asistencia.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/404.php");
}
?>