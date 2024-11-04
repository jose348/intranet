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
            <title>Capacitaciones - Calendario</title>
            <!-- FullCalendar CSS -->
            <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
            <style>
                /* Estilos para hacer el calendario más grande */
                #calendar {
                    max-width: 1000px;
                    /* Ajusta el ancho máximo */
                    height: 700px;
                    /* Ajusta la altura */
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #f8f9fa;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    /* Sombra para dar mejor apariencia */
                }

                /* Estilos personalizados para el calendario */
                .fc-toolbar-title {
                    font-size: 24px;
                    color: #007bff;
                    /* Cambia el color del título */
                }

                /* Personalización del botón "Agregar Capacitación" */
                .btn-agregar-capacitacion {
                    background-color: #17a2b8;
                    /* Color alusivo al calendario */
                    border-color: #17a2b8;
                    color: white;
                }

                .btn-agregar-capacitacion:hover {
                    background-color: #138496;
                    border-color: #117a8b;
                }

                /* Aseguramos que el ícono se vea bien con margen */
                .btn-agregar-capacitacion i {
                    margin-right: 5px;
                }
            </style>
        </head>

        <body>
            <?php
            require_once("../Menu/menu.php");

            ?>
            <!-- ########## END: LEFT PANEL ########## -->

            <?php
            require_once("../Header/MainHeader.php");
            ?>

            <div class="br-mainpanel">
                <div class="br-pageheader pd-y-15 pd-l-20">
                    <a class="breadcrumb-item" href="../Home/home.php">Inicio</a>
                    <span class="breadcrumb-item active">Calendario de Capacitaciones</span>
                </div><!-- br-pageheader -->

                <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
                    <section class="card">
                        <header class="card-header">
                            <h3 class="text-primary">Calendario de Capacitaciones</h3>
                            <p>Visualiza y edita tus capacitaciones en este calendario.</p>


                        </header>
                        <br>

                        <!-- Icono de notificación en el menú o encabezado -->
                        <div id="notificaciones" class="col-12 notificaciones-container">
                            <button id="btnNotificaciones" class="btn btn-info">
                                <i class="fa fa-bell"></i>
                                <span id="notificacionesCount" class="badge badge-danger">0</span>
                            </button>
                        </div>

                        <!-- Modal de Notificaciones -->
                        <div class="modal fade" id="modalNotificaciones" tabindex="-1" aria-labelledby="modalNotificacionesLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalNotificacionesLabel">Notificaciones de Capacitaciones</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <ul id="listaNotificaciones" class="list-group">
                                            
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Contenedor del calendario -->

                        <div id="calendar"></div>



                    </section>
                </div>
            </div>

            <script type="text/javascript">
                const usuarioId = <?php echo json_encode($usuario_id); ?>; // ID del usuario de la sesión
               
            
            
            </script>





            <?php
            require_once("../Js/MainJs.php");
            ?>

            <!-- FullCalendar JS -->
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
            <script type="text/javascript" src="calendario.js"></script>

        </body>

        </html>

    <?php
    } else {
        header("Location:" . Conectar::ruta() . "view/404/404.php");
    }
    ?>