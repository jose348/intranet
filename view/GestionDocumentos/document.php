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
        <title>Archivos - Documentos</title>
        <!-- FullCalendar CSS -->
        <style>
            .file-list {
                max-height: 300px;
                overflow-y: auto;
            }
            .file-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                border-bottom: 1px solid #ddd;
            }
            .file-item:last-child {
                border-bottom: none;
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
                <span class="breadcrumb-item active">Documentos Personales</span>
            </div><!-- br-pageheader -->

            <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
                <section class="card">
                    <header class="card-header">
                        <h3 class="text-primary">Mis Documentacion</h3>
                        <p>Visualiza y guarda tu Documentacion.</p>


                    </header>
                    <br>
                    <div class="card-body">
                        <div class="mb-3">
                            <h5>Subir Archivos</h5>

                            <style>
                                .input-group {
                                    display: flex;
                                    align-items: center;
                                }

                                .btn-outline-primary {
                                    cursor: pointer;
                                }

                                .btn-primary {
                                    margin-left: 5px;
                                }

                                .input-group {
                                    display: flex;
                                    align-items: center;
                                }

                                .btn-outline-primary {
                                    cursor: pointer;
                                }

                                .btn-primary {
                                    margin-left: 5px;
                                }

                                #fileName {
                                    margin-left: 10px;
                                    font-size: 0.9rem;
                                }
                            </style>
                              <form id="uploadForm" enctype="multipart/form-data">
                                <div class="input-group">
                                    <label class="btn btn-primary">
                                        <i class="fa fa-upload"></i> Seleccionar Archivo
                                        <input type="file" name="file" id="fileInput" class="form-control d-none" required>
                                    </label>
                                    <span id="fileName" class="form-control">Ningún archivo seleccionado</span>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-cloud-upload"></i> Subir
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="mb-3">
                            <h5>Archivos</h5>
                            <div class="file-list" id="fileList">
                                <!-- Archivos subidos se cargarán aquí dinámicamente -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                            <!-- Tabla para listar archivos -->
                            <div class="col-md-12">
                                
                                <div class="table-responsive file-table">
                                <table id="fileTable" class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
                                            <th>Tamaño</th>
                                            <th>Fecha de Creación</th>
                                            <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="fileTableBody">
                                            <!-- Los archivos se cargarán aquí dinámicamente -->
                                        </tbody>
                                    </table>
                                    <br>
                                </div>
                            </div>
                        </div>

      

                </section>
                <!-- Modal para Visualización de Documentos -->


            </div>
        </div>

    
    




        <?php
        require_once("../Js/MainJs.php");
        ?>
        <!-- FullCalendar JS -->
        
        <script type="text/javascript" src="document.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/404.php");
}
?>