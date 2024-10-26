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

            /* CSS personalizado para aumentar el ancho del modal */
            .modal-dialog {
                max-width: 900px;
                /* Ajusta este valor al ancho que prefieras */
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


                    <!-- Botón personalizado para abrir el modal y agregar nueva capacitación -->
                    <!-- Contenedor de los botones con Flexbox -->
                    <!-- Contenedor para centrar los botones -->
                    <div class="d-flex justify-content-center align-items-center mt-3 ">
                        <!-- Botón para agregar nueva capacitación -->
                        <button class="btn btn-agregar-capacitacion mr-3" data-toggle="modal" data-target="#addCapacitacionModal">
                            <i class="fa fa-plus"></i> Agregar Capacitación
                        </button>

                        <!-- Botón para seleccionar público objetivo -->
                        <button type="button" class="btn btn-agregar-capacitacion" id="btnCargarPersonas" data-toggle="modal" data-target="#modalSeleccionarPublico">
                            <i class="fa fa-users"></i> Seleccionar Público Objetivo
                        </button>
                    </div>
                    <br>

                    <!-- Contenedor de la tabla de personas -->

                    <style>
                        /* Ajustar el contenedor de la lista de personas seleccionadas */
                        #listaPersonasSeleccionadas {
                            max-height: 400px;
                            /* Ajusta este valor según el tamaño de tu tabla */
                            overflow-y: auto;
                            border: 1px solid #ddd;
                            /* Opcional: Borde para que se vea más claro */
                            padding: 10px;
                            margin-top: 10px;
                        }

                        /* Asegurar que las columnas mantengan el mismo tamaño */
                        #contenedorTablaPersonas .col-md-6 {
                            max-height: 450px;
                            /* Asegurar que ambas columnas tengan la misma altura */
                            overflow: hidden;
                            /* Evitar que el contenido salga del contenedor */
                        }
                    </style>
                    <div id="contenedorTablaPersonas" class="mt-4" style="display: none;">
                        <div class="row">
                            <!-- Columna para la Tabla de Personas -->
                            <div class="col-md-6">
                                <h5>Selecciona las Personas:</h5>
                                <input type="text" id="buscarPersona" class="form-control mb-2" placeholder="Buscar por DNI o nombre...">
                                <table id="tablaPersonas" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll" /></th> <!-- Checkbox para seleccionar todos -->
                                            <th>DNI</th>
                                            <th>Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los datos de la tabla se llenarán dinámicamente -->
                                    </tbody>
                                </table>
                                <style>

                                </style>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center" id="paginationControls">
                                        <!-- Los controles de paginación se generarán dinámicamente -->
                                    </ul>
                                </nav>

                            </div>

                            <!-- Columna para la Lista de Personas Seleccionadas -->
                            <div class="col-md-6">
                                <h5>Personas Seleccionadas:</h5>
                                <!-- Botón para enviar la capacitación a las personas seleccionadas -->
                                <div class="d-flex justify-content-center">
                                    <button id="btnEnviarCapacitacion" class="btn btn-success mt-3">
                                        <i class="fa fa-paper-plane"></i> Enviar Capacitación
                                    </button>
                                </div>
                                <!--TODO ahora creamos el modal cuando ponga enviar capacitacion -->
                                <!-- Modal para Listar Capacitaciones -->
                                <div class="modal fade" id="modalListarCapacitaciones" tabindex="-1" aria-labelledby="modalListarCapacitacionesLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalListarCapacitacionesLabel">Selecciona una Capacitación</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table id="tablaCapacitaciones" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Título</th>
                                                            <th>Expositor</th>
                                                            <th>Fecha</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="capacitacionTableBody">
                                                        <!-- Las capacitaciones se llenarán dinámicamente aquí -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>








                                <ul id="listaPersonasSeleccionadas" class="list-group">
                                    <!-- Personas seleccionadas aparecerán aquí -->
                                </ul>




                            </div>


                        </div>
                    </div>






                    <br>
                    <br>

                    <style>
                        /* Estilo general del evento */
                        .fc-event {
                            color: black !important;
                            /* Asegura que el texto de los eventos sea negro */
                            background-color: transparent;
                            border: none;
                            font-weight: bold;

                            font-size: 12px;
                            /* Texto en color neutro */
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        }

                        .fc-event a {
                            color: black !important;
                            /* Asegura que los enlaces dentro de los eventos también sean negros */
                            text-decoration: none;
                            /* Elimina el subrayado del enlace */
                        }

                        /* Ícono de estado (pasado y futuro) */
                        .fc-event .estado-punto {
                            width: 10px;
                            height: 10px;
                            border-radius: 100%;
                            display: inline-block;
                        }

                        /* Eventos pasados: punto amarillo */
                        .fc-event-pasado .estado-punto {
                            background-color: #ffc107;
                        }

                        /* Eventos futuros: punto verde */
                        .fc-event-futuro .estado-punto {
                            background-color: #28a745;
                        }

                        /* Hover del evento */
                        .fc-event:hover {
                            background-color: rgba(0, 0, 0, 0.05);
                            /* Sutil fondo gris al hacer hover */
                            border-radius: 50px;
                        }

                        /* Ajuste del calendario */
                        #calendar {
                            max-width: 900px;
                            margin: 0 auto;
                            padding: 29px;
                            background-color: #f8f9fa;
                            border-radius: 18px;
                            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
                        }

                        .fc-toolbar-title {
                            font-size: 60px;
                            font-weight: bold;
                            color: #343a40;
                        }

                        .list-group {
                            padding: 0;
                            margin: 0;
                        }

                        .list-group-item {
                            border: none;
                            border-radius: 0;
                            padding: 10px 15px;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            background-color: #f8f9fa;
                            margin-bottom: 2px;
                            /* Asegura el mismo espaciado entre ítems */
                        }

                        .list-group-item .botones-accion {
                            display: flex;
                            gap: 5px;
                            /* Espacio entre botones */
                        }

                        .list-group-item:last-child {
                            margin-bottom: 0;
                            /* Elimina el margen inferior del último elemento */
                        }

                        .botones-accion button {
                            width: 35px;
                            height: 35px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            padding: 0;
                        }

                        .list-group-item:hover {
                            background-color: #e9ecef;
                            /* Color de fondo al hacer hover */
                        }

                        .list-group {
                            padding: 0;
                            margin: 0;
                        }

                        .list-group-item {
                            border: none;
                            padding: 8px 15px;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            background-color: #f8f9fa;
                            margin: 0;
                            /* Eliminar márgenes */
                            border-bottom: 1px solid #ddd;
                            /* Línea divisoria entre ítems */
                        }

                        .list-group-item:last-child {
                            border-bottom: none;
                            /* Quitar línea al último ítem */
                        }

                        .botones-accion {
                            display: flex;
                            gap: 8px;
                            /* Espacio entre botones */
                        }

                        .botones-accion button {
                            width: 35px;
                            height: 35px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            padding: 0;
                        }
                    </style>

                    <!-- Cdor del calendario -->


                    <div class="row">
                        <!-- Columna del Calendario -->
                        <div class="col-md-9">
                            <div id="calendar"></div>
                        </div>

                        <!-- Columna de la Leyenda -->

                        <style>
                            /* Estilos para los puntos de la leyenda */
                            .dot {
                                width: 12px;
                                height: 12px;
                                border-radius: 30%;
                                display: inline-block;
                            }
                        </style>

                        <div class="col-md-3">

                            <h5>Leyenda de Capacitaciones</h5>
                            <ul class="list-group">
                                <!-- Capacitaciones Pasadas -->
                                <li class="list-group-item">
                                    <span class="dot bg-danger mr-2"></span>
                                    <strong>Capacitaciones Pasadas</strong>
                                </li>
                                <ul id="listaPasadas" class="list-group"></ul>
                                <br>

                                <!-- Capacitaciones en Proceso -->
                                <li class="list-group-item">
                                    <span class="dot bg-warning mr-2"></span>
                                    <strong>Capacitaciones en Proceso</strong>
                                </li>
                                <ul id="listaEnProceso" class="list-group"></ul>
                                <br>

                                <!-- Capacitaciones Futuras -->
                                <li class="list-group-item">
                                    <span class="dot bg-success mr-2"></span>
                                    <strong>Capacitaciones Futuras</strong>
                                </li>
                                <ul id="listaFuturas" class="list-group"></ul>
                            </ul>
                        </div>





                    </div>




                </section>
            </div>
        </div>

        <!-- Modal para agregar nueva capacitación -->
        <!-- Modal con estilos personalizados para hacerlo más ancho -->
        <style>
            /* Ocultar el campo de archivo real */
            input[type="file"] {
                display: none;
            }

            /* Botón estético */
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
                color: white;
                padding: 5px 5px;
                border-radius: 5px;
                cursor: pointer;

                transition: background-color 0.3s ease;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #004085;
            }

            .btn-primary i {
                margin-right: 5px;
                /* Añadir margen al ícono */
            }
        </style>
        <!--TODO MODAL PARA GUARDAR CAPACITACION -->
        <div class="modal fade" id="addCapacitacionModal" tabindex="-1" aria-labelledby="addCapacitacionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCapacitacionModalLabel">Agregar Capacitación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="capacitacionForm" method="POST" enctype="multipart/form-data">



                            <div class="form-group  col-12">
                                <label for="tituloCapacitacion">Título de la Capacitación</label>
                                <input type="text" class="form-control" id="tituloCapacitacion" name="tituloCapacitacion" required>
                            </div>

                            <div class="form-group  col-12">
                                <label for="expositorCapacitacion">Expositor</label>
                                <input type="text" class="form-control" id="expositorCapacitacion" name="expositorCapacitacion" required>
                            </div>


                            <div class="row">
                                <!-- Fila para Fecha y Hora de Inicio -->
                                <div class="col-12 d-flex">
                                    <div class="form-group col-md-6">
                                        <label for="fechaInicio">Fecha de Inicio</label>
                                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="horaInicio">Hora de Inicio</label>
                                        <input type="time" class="form-control" id="horaInicio" name="horaInicio" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Fila para Fecha y Hora de Fin -->
                                <div class="col-12 d-flex">
                                    <div class="form-group col-md-6">
                                        <label for="fechaFin">Fecha de Fin</label>
                                        <input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="horaFin">Hora de Fin</label>
                                        <input type="time" class="form-control" id="horaFin" name="horaFin" required>
                                    </div>
                                </div>
                            </div>


                            <br>
                            <br>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <div class="form-group mr-2">
                                        <input type="file" class="form-control-file" id="flyerCapacitacion" name="flyerCapacitacion" accept=".jpg,.jpeg,.png" hidden>
                                        <button type="button" class="btn btn-primary mb-2 col-12" id="btnFlyer">
                                            <i class="fa fa-upload"></i> Seleccionar Flyer
                                        </button>
                                        <span id="flyerSeleccionado" class="ml-2  col-12 text-muted">Ningún archivo seleccionado</span>
                                    </div>

                                    <div class="form-group mr-2">
                                        <input type="file" class="form-control-file" id="archivoCapacitacion" name="archivoCapacitacion[]" accept=".ppt,.pdf,.doc" multiple hidden>
                                        <button type="button" class="btn btn-primary mb-2 col-12" id="btnArchivo">
                                            <i class="fa fa-upload"></i> Seleccionar Archivos
                                        </button>
                                        <span id="archivoSeleccionado" class="ml-2 col-12 text-muted">Ningún archivo seleccionado</span>
                                    </div>

                                    <div class="form-group mr-2">
                                        <input type="file" class="form-control-file" id="videoCapacitacion" name="videoCapacitacion" accept=".mp4,.avi" hidden>
                                        <button type="button" class="btn btn-primary mb-2 col-12" id="btnVideo">
                                            <i class="fa fa-upload"></i> Seleccionar Video
                                        </button>
                                        <span id="videoSeleccionado" class="ml-2 col-12 text-muted">Ningún archivo seleccionado</span>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="pagination justify-content-center">
                                <button type="submit" class="mr-3 col-3 btn btn-outline-info">Guardar</button>
                                <button type="button" class="col-3 md-10 btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </form>





                    </div>
                </div>
            </div>
        </div>

        <!--TODO MODAL PARA EDITAR CAPACITACION -->
        <!-- Modal para Editar Capacitación -->
        <!-- Modal para Editar Capacitación -->
        <!-- Modal para Editar Capacitación -->
        <!-- Modal para Editar Capacitación -->
        <div class="modal fade" id="editCapacitacionModal" tabindex="-1" aria-labelledby="editCapacitacionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 900px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCapacitacionModalLabel">Editar Capacitación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="capacitacionFormEditar" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="editCapacitacionId" name="capacitacionId">

                            <div class="form-group col-12">
                                <label for="editTituloCapacitacion">Título de la Capacitación</label>
                                <input type="text" class="form-control" id="editTituloCapacitacion" name="tituloCapacitacion" required>
                            </div>

                            <div class="form-group col-12">
                                <label for="editExpositorCapacitacion">Expositor</label>
                                <input type="text" class="form-control" id="editExpositorCapacitacion" name="expositorCapacitacion" required>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex">
                                    <div class="form-group col-md-6">
                                        <label for="editFechaInicio">Fecha de Inicio</label>
                                        <input type="date" class="form-control" id="editFechaInicio" name="fechaInicio" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="editHoraInicio">Hora de Inicio</label>
                                        <input type="time" class="form-control" id="editHoraInicio" name="horaInicio" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 d-flex">
                                    <div class="form-group col-md-6">
                                        <label for="editFechaFin">Fecha de Fin</label>
                                        <input type="date" class="form-control" id="editFechaFin" name="fechaFin" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="editHoraFin">Hora de Fin</label>
                                        <input type="time" class="form-control" id="editHoraFin" name="horaFin" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <div class="form-group mr-2">
                                        <input type="file" class="form-control-file" id="flyerCapacitacionEditar" name="flyerCapacitacion" accept=".jpg,.jpeg,.png" hidden>
                                        <button type="button" class="btn btn-primary mb-2 col-12" id="btnFlyerEditar">
                                            <i class="fa fa-upload"></i> Seleccionar Flyer
                                        </button>
                                        <span id="editFlyerSeleccionado" class="ml-2 col-12 text-muted">Ningún archivo seleccionado</span>
                                    </div>

                                    <div class="form-group mr-2">
                                        <input type="file" class="form-control-file" id="archivoCapacitacionEditar" name="archivoCapacitacion[]" accept=".ppt,.pdf,.doc" multiple hidden>
                                        <button type="button" class="btn btn-primary mb-2 col-12" id="btnArchivoEditar">
                                            <i class="fa fa-upload"></i> Seleccionar Archivos
                                        </button>
                                        <span id="editArchivoSeleccionado" class="ml-2 col-12 text-muted">Ningún archivo seleccionado</span>
                                    </div>

                                    <div class="form-group mr-2">
                                        <input type="file" class="form-control-file" id="videoCapacitacionEditar" name="videoCapacitacion" accept=".mp4,.avi" hidden>
                                        <button type="button" class="btn btn-primary mb-2 col-12" id="btnVideoEditar">
                                            <i class="fa fa-upload"></i> Seleccionar Video
                                        </button>
                                        <span id="editVideoSeleccionado" class="ml-2 col-12 text-muted">Ningún archivo seleccionado</span>
                                    </div>

                                </div>
                            </div>
                            <div class="row justify-content-center ">
                                <div class="col-12 d-flex justify-content-around">
                                    <button type="submit" class="col-3 btn btn-outline-info">Actualizar</button>
                                    <button type="button" class="col-3 btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>








        <!-- Modal de Previsualización -->
        <!-- Modal de Previsualización -->
        <!-- Modal de Previsualización -->
        <!-- Modal de Previsualización -->
        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm"> <!-- Modal pequeño -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">Vista Previa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalPreviewContent">
                        <!-- El contenido dinámico se cargará aquí -->
                    </div>
                </div>
            </div>
        </div>
















        <!--TODO MODAL DE LA CAPACITACION dentro del calendario -->
        <!-- Modal para mostrar la información de la capacitación -->
        <div class="modal fade" id="eventInfoModal" tabindex="-1" aria-labelledby="eventInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitulo">Capacitación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-labexl="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="modalExpositor"></p>
                        <p id="modalPublicoObjetivo"></p> <!-- Mostrar a quién va dirigida la capacitación -->
                        <img id="modalFlyer" src="" alt="Flyer de la Capacitación" style="max-width: 100%;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar Capacitación</button>
                        <button type="button" class="btn btn-warning" id="btnEditar">Editar Capacitación</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>



        <?php
        require_once("../Js/MainJs.php");
        ?>
        <!-- FullCalendar JS -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script type="text/javascript" src="prog_calendario.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/404.php");
}
?>