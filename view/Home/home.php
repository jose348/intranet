<?php

require_once("../../config/conexion.php");
if (isset($_SESSION["id"])) {
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <?php
    require_once("../Head/MainHead.php");
    ?>
    <title>Intranet::MPCH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
      /* Estilos para la sección de carpetas */
      .folder-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        /* Ajusta el tamaño mínimo de los elementos de la cuadrícula */
        grid-gap: 20px;
        padding: 20px;
        margin: 0 auto;
        /* Centrar el contenido */
        max-width: 1000px;
        /* Limitar el ancho máximo para mantener un buen espaciado */
      }

      .folder-item {
        display: flex;
        align-items: center;
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        cursor: pointer;
      }

      .folder-item:hover {
        background-color: #e9ecef;
        transform: scale(1.05);
      }

      .folder-icon {
        font-size: 30px;
        color: #007bff;
        margin-right: 15px;
      }

      .folder-info {
        display: flex;
        flex-direction: column;
      }

      .folder-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
      }

      .folder-path {
        font-size: 14px;
        color: #6c757d;
      }

      /* Estilos del encabezado principal */
      .main-header {
        text-align: center;
        font-size: 24px;
        color: #007bff;
        font-weight: bold;
        margin-bottom: 20px;
      }

      .main-header span {
        display: block;
        font-size: 16px;
        color: #6c757d;
        margin-top: 5px;
      }
    </style>
  </head>

  <body>

    <?php
    require_once("../Header/MainHeader.php");
    ?>

    <?php
    require_once("../Menu/menu.php");
    ?>

    <div class="br-mainpanel">
      <div class="br-pageheader pd-y-15 pd-l-20">
        <a class="breadcrumb-item" href="../Home/home.php">Inicio</a>
        <span class="breadcrumb-item active">Home</span>
      </div><!-- br-pageheader -->

      <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
        <section class="card">
          <header class="card-header">
            <div class="main-header">
              Sistema Intranet
              <span>Gestiona tu información de manera rápida, segura y eficiente, siempre disponible para ti.</span>
              <span>Accede a tu información cuando y donde la necesites, siempre al alcance de tu mano.</span>

              <span>La información que necesitas, de manera clara y accesible, a tu disposición.</span>



            </div>
          </header>
          <br>

          <!-- Sección de carpetas estilo "Acceso rápido" -->
          <div class="row folder-grid">

            <?php $variable = $_SESSION["acce_rol"];
            switch ($variable) {
              case "9":
            ?>

             
                <div class="col-14">
                  <!-- Carpeta de Escalafón -->
                  <a href="../GestionEscalafon/persona.php" class="folder-item">
                    <i class="folder-icon fa fa-sitemap"></i>
                    <div class="folder-info">
                      <div class="folder-title">Escalafón</div>
                      <div class="folder-path">Mi informacion Personal</div>
                    </div>
                  </a>
                </div>


                <div class="col-14">
                  <!-- Carpeta de Boletas de Pago -->
                  <a href="../GestionBoletas/boletas.php" class="folder-item">
                    <i class="folder-icon fa fa-file-invoice-dollar"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Boletas de Pago</div>
                      <div class="folder-path">Mis registros de pagos y sueldos</div>
                    </div>
                  </a>
                </div>
   


                <div class="col-14">
                  <!-- Carpeta de Escalafón -->
                  <a href="../GestionCapacitaciones/progr_calendario.php" class="folder-item">
                    <i class="folder-icon fa fa-graduation-cap"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Capacitaciones</div>
                      <div class="folder-path">Mis cursos y formaciones</div>
                    </div>
                  </a>
                </div>


                <div class="col-14">
                  <!-- Carpeta de Vacaciones -->
                  <div class="folder-item">
                    <i class="folder-icon fa fa-plane"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Vacaciones</div>
                      <div class="folder-path">Mis descansos y días libres</div>
                    </div>
                  </div>
                </div>



                <div class="col-14">
                  <div class="folder-item">
                    <i class="folder-icon fa fa-calendar-check"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Asistencias</div>
                      <div class="folder-path">Registro de mi asistencia laboral</div>
                    </div>
                  </div>
                </div>
              <?php
                break;
              default:

              ?>



               

                <div class="col-14">
                  <!-- Carpeta de Escalafón -->
                  <a href="../GestionEscalafon/persona.php" class="folder-item">
                    <i class="folder-icon fa fa-sitemap"></i>
                    <div class="folder-info">
                      <div class="folder-title">Escalafón</div>
                      <div class="folder-path">Mi informacion Personal</div>
                    </div>
                  </a>
                </div>


                <div class="col-14">
                  <!-- Carpeta de Boletas de Pago -->
                  <a href="../GestionEscalafon/persona.php" class="folder-item">
                    <i class="folder-icon fa fa-file-invoice-dollar"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Boletas de Pago</div>
                      <div class="folder-path">Mis registros de pagos y sueldos</div>
                    </div>
                  </a>
                </div>


                <div class="col-14">
                  <!-- Carpeta de Escalafón -->
                  <a href="../GestionCapacitaciones/calendario.php" class="folder-item">
                    <i class="folder-icon fa fa-graduation-cap"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Capacitaciones</div>
                      <div class="folder-path">Mis cursos y formaciones</div>
                    </div>
                  </a>
                </div>

                <div class="col-14">
                  <!-- Carpeta de Vacaciones -->
                  <div class="folder-item">
                    <i class="folder-icon fa fa-plane"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Vacaciones</div>
                      <div class="folder-path">Mis descansos y días libres</div>
                    </div>
                  </div>
                </div>



                <div class="col-14">
                  <div class="folder-item">
                    <i class="folder-icon fa fa-calendar-check"></i>
                    <div class="folder-info">
                      <div class="folder-title">Mis Asistencias</div>
                      <div class="folder-path">Registro de mi asistencia laboral</div>
                    </div>
                  </div>
                </div>
            <?php
                break;
            }
            ?>

          </div>
          <!-- Fin de sección de carpetas -->




        </section>
      </div>
    </div>

    <?php
    require_once("../Js/MainJs.php");
    ?>
    <script type="text/javascript" src="usuhome.js"></script>

  </body>

  </html>
<?php
} else {
  header("Location:" . Conectar::ruta() . "view/404/404.php");
}
?>