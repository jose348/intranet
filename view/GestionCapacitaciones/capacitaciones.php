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
        <title>Capacitaciones - Flayers</title>
        <!-- FullCalendar CSS -->
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
        <style>


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
                <span class="breadcrumb-item active">Capacitaciones</span>
            </div><!-- br-pageheader -->

            <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
                <section class="card">
                    <header class="card-header">
                        <h3 class="text-primary">Capacitaciones</h3>
                        <p>Visualiza los flyers de tus capacitaciones.</p>
                    </header>
                    <!-- Slider de Imágenes -->
                    <style>
                        #carouselExample {
                            max-width: 50%;
                            /* Tamaño reducido del carrusel */
                            margin: 0 auto;
                            border-radius: 10px;
                            overflow: hidden;
                            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                        }

                        #flyerCarouselInner .carousel-item img {
                            border-radius: 10px;
                            height: 300px;
                            /* Ajusta la altura de las imágenes */
                            object-fit: cover;
                        }

                        .custom-carousel-control {
                            background-color: rgba(0, 0, 0, 0.5);
                            width: 40px;
                            height: 40px;
                            border-radius: 50%;
                        }

                        .carousel-control-prev-icon,
                        .carousel-control-next-icon {
                            filter: brightness(0) invert(1);
                            width: 20px;
                            height: 20px;
                        }
                    </style>
                    <div id="carouselExample" class="carousel slide" data-ride="carousel">
                        <div id="flyerCarouselInner" class="carousel-inner">
                            <!-- Las imágenes se cargarán aquí dinámicamente -->
                        </div>
                        <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <br>

                    <!-- Tabla de Capacitaciones -->
                    <div class="mt-4">
                        <h4 class="text-primary"> </h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título de la Capacitación</th>
                                    <th>Capacitador</th>
                                    <th>Hora de Inicio</th>
                                    <th>Link Completo</th>
                                </tr>
                            </thead>
                            <tbody id="capacitacionTableBody">
                                <!-- Las filas de la tabla se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>

                </section>



            </div>
        </div>






        <?php
        require_once("../Js/MainJs.php");
        ?>
        <script type="text/javascript">
            const usuarioId = <?php echo json_encode($usuario_id); ?>; // ID del usuario desde PHP

            document.addEventListener("DOMContentLoaded", function() {

                function cargarFlyers() {
                    fetch(`/Intranet/controller/capacitacion.php?op=obtener_flyers_por_usuario&pers_id=${usuarioId}`)
                        .then(response => response.json())
                        .then(data => {
                            const carouselInner = document.getElementById('flyerCarouselInner');
                            carouselInner.innerHTML = ''; // Limpiar el contenido actual

                            if (data.status === 'success' && data.data.length > 0) {
                                data.data.forEach((flyer, index) => {
                                    const itemDiv = document.createElement('div');
                                    itemDiv.className = `carousel-item ${index === 0 ? 'active' : ''}`;

                                    const img = document.createElement('img');
                                    img.src = `/Intranet${flyer.capa_flyer}`; // Asegurándote de que la ruta sea correcta
                                    img.className = 'd-block w-100';
                                    img.alt = 'Flyer de Capacitación';

                                    itemDiv.appendChild(img);
                                    carouselInner.appendChild(itemDiv);
                                });
                            } else {
                                carouselInner.innerHTML = '<p class="text-center">No hay flyers disponibles.</p>';
                            }
                        })
                        .catch(error => console.error("Error al cargar los flyers:", error));
                }

                function cargarCapacitaciones() {
                    fetch(`/Intranet/controller/capacitacion.php?op=obtener_capacitaciones_usuario&pers_id=${usuarioId}`)
                        .then(response => response.json())
                        .then(data => {
                            const tableBody = document.getElementById('capacitacionTableBody');
                            tableBody.innerHTML = ''; // Limpiar el contenido actual

                            if (data.status === 'success' && data.data.length > 0) {
                                data.data.forEach(capacitacion => {
                                    const row = document.createElement('tr');

                                    const titleCell = document.createElement('td');
                                    titleCell.textContent = capacitacion.capa_titulo;

                                    const instructorCell = document.createElement('td');
                                    instructorCell.textContent = capacitacion.capa_expositor;

                                    const startTimeCell = document.createElement('td');
                                    startTimeCell.textContent = `${capacitacion.capa_fecha_inicio}  `;

                                    const linkCell = document.createElement('td');
                                    const link = document.createElement('a');

                                    // Asigna el enlace completo al href
                                    link.href = capacitacion.capa_link;
                                    link.textContent = 'Ver Detalles';
                                    link.target = '_blank'; // Abre el enlace en una nueva pestaña
                                    linkCell.appendChild(link);

                                    row.appendChild(titleCell);
                                    row.appendChild(instructorCell);
                                    row.appendChild(startTimeCell);
                                    row.appendChild(linkCell);

                                    tableBody.appendChild(row);
                                });
                            } else {
                                const emptyRow = document.createElement('tr');
                                const emptyCell = document.createElement('td');
                                emptyCell.colSpan = 4;
                                emptyCell.className = 'text-center';
                                emptyCell.textContent = 'No hay capacitaciones disponibles.';
                                emptyRow.appendChild(emptyCell);
                                tableBody.appendChild(emptyRow);
                            }
                        })
                        .catch(error => console.error("Error al cargar las capacitaciones:", error));
                }

                // Llamar a las funciones para cargar los flyers y capacitaciones
                cargarFlyers();
                cargarCapacitaciones();
            });
        </script>


        <script type="text/javascript" src="capacitaciones.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/404.php");
}
?>