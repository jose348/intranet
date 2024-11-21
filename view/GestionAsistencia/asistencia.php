<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["id"])) {
?>

<!DOCTYPE html>
<html lang="es">
 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css" />


<head>
    <?php require_once("../Head/MainHead.php"); ?>
    <title>Asistencias - Calendario</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px 30px;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
        }
        #fecha-select {
            flex: 1;
            max-width: 300px;
            padding: 8px 10px;
            font-size: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        #btn-cargar {
            padding: 8px 20px;
            font-size: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        #btn-cargar:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
        .verde {
            background-color: #d4edda; /* Verde claro */
            color: #155724;           /* Texto verde oscuro */
            font-weight: bold;
        }

        .rojo {
            background-color: #f8d7da; /* Rojo claro */
            color: #721c24;           /* Texto rojo oscuro */
            font-weight: bold;
        }

        .amarillo {
            background-color: #fff3cd; /* Amarillo claro */
            color: #856404;           /* Texto marrón oscuro */
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            color: gray;
        }


    </style>

    
</head>
<body>
    <?php require_once("../Menu/menu.php"); ?>
    <?php require_once("../Header/MainHeader.php"); ?>

    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <a class="breadcrumb-item" href="../Home/home.php">Inicio</a>
            <span class="breadcrumb-item active">Mis Asistencias</span>
        </div>

        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <section class="card">
                <header class="card-header">
                    <h3 class="text-primary">Asistencias</h3>
                    <p>Visualiza tus entradas y salidas en el calendario.</p>
                    
                </header>
                <div class="container">

                
                <div class="form-group">
    <label for="fecha-select">INGRESE FECHA</label>
    <input class="form-control col-6" type="text" id="fecha-select" placeholder="Seleccionar mes y año" />
    <button class="btn btn-primary col-4" id="btn-cargar">Cargar</button>
</div>


                    <table>
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-asistencias-body">
                            <tr class="no-data">
                                <td colspan="3">Selecciona un mes y haz clic en "Cargar" para ver los registros.</td>
                            </tr>
                        </tbody>
                    </table>
                


               
                    <br><h1></h1>
                <br>
             </div>
            </section>
         
        </div>

        <br>
        <br>
    </div>
    <br>
        <br>

    <?php require_once("../Js/MainJs.php"); ?>
     
    <script type="text/javascript" src="asistencia.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

</body>
</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/404.php");
}
?>
