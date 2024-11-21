<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["id"])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../Head/MainHead.php"); ?>
    <title>Boletas - Mis Boletas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            margin-top: 20px;
        }
        .search-bar {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }
        .pagination button {
            padding: 5px 10px;
            margin: 0 2px;
            cursor: pointer;
        }

            #paginationControls button {
            margin: 0 5px;
            padding: 5px 10px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            cursor: pointer;
        }

        #paginationControls button:hover {
            background-color: #007bff;
            color: white;
        }

        #paginationControls button:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

    </style>
</head>

<body>
    <?php require_once("../Menu/menu.php"); ?>
    <?php require_once("../Header/MainHeader.php"); ?>

    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <a class="breadcrumb-item" href="../Home/home.php">Inicio</a>
            <span class="breadcrumb-item active">Mis Boletas de Pago</span>
        </div>

        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <section class="card">
                <header class="card-header">
                    <h3 class="text-primary">Boletas de Pago</h3>
                    <p>Envía solicitud por tu boleta.</p>
                </header>
                <br>

                <div class="container">
                    
                        <!-- Barra de Búsqueda por Año con Bootstrap -->
                        <div class=" col -4 input-group mb-3">
                            <input type="text" id="searchYear" class="form-control" placeholder="Buscar por Año" aria-label="Buscar por Año">
                            <button id="btnBuscar" class="btn btn-primary" type="button">Buscar</button>
                        </div>
                    
                    <table id="boletasTable">
                        <thead>
                            <tr>
                            <th>Año</th>
                            <th>Mes</th>
                            <th>Tipo Boleta</th>
                            <th>Ver PDF</th>
                            <th>Descargar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se cargarán los datos de la tabla mediante JavaScript -->
                        </tbody>
                    </table>
 
                    <br>
                    

                    <!-- Controles de Paginación -->
                    <div id="paginationControls" class="mt-3"></div>
                    <br>
                </div>
            </section>
        </div>
    </div>

    <?php require_once("../Js/MainJs.php"); ?>
    <script type="text/javascript" src="boletas.js"></script>
</body>
</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/404.php");
}
