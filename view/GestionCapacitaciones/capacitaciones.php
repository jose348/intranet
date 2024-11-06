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

                // Llamar a la función para cargar los flyers
                cargarFlyers();
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