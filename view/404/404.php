<!DOCTYPE html>
<html lang="en" class="pos-relative">

<head>
<?php require_once("../Head/MainHead.php") ?>
  
    <title>SISE</title>

</head>

<body class="pos-relative">

    <div class="ht-100v d-flex align-items-center justify-content-center">
        <div class="wd-lg-70p wd-xl-50p tx-center pd-x-40">
            <h1 class="tx-100 tx-xs-140 tx-normal tx-inverse tx-roboto mg-b-0">Error.!</h1>
            <h5 class="tx-xs-24 tx-normal tx-info mg-b-30 lh-5">SISTEMA INTRANET.</h5>
            <p class="tx-16 mg-b-30">Pagina de error 404, regresa al Inicio y vuelve a interactuar con el Sistema</p>

            <div class="d-flex justify-content-center">
                <div class="input-group wd-xs-300">
                   
                    
                        <a href="../Logout/logout.php"><button   type="" class="btn btn-primary ">Regresar al Inicio de Session..!</button></a>
                   
                    <!-- input-group-btn -->
                </div>
                <!-- input-group -->
            </div>
            <!-- d-flex -->
        </div>
    </div>
    <!-- ht-100v -->
<?php
    require_once("../Js/MainJs.php");
?>

</body>

</html>