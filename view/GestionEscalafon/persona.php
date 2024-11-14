<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["id"] )) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require_once("../Head/MainHead.php");
    ?>
    <title>Escalafón - Persona</title>

    <style>
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 180px;
            height: 220px;
            border-radius: 5%;
            border: 3px solid #007bff;
            object-fit: cover;
            object-position: center;
        }

        .header h2 {
            margin-top: 10px;
            color: #007bff;
            font-size: 22px;
            font-weight: bold;
        }

        .info-section {
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
        }

        .info-header {
            background-color: transparent; /* Fondo transparente */
            color: #007bff; /* Color de texto azul */
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none; /* Sin borde */
            box-shadow: none; /* Sin sombra */
            transition: background-color 0.3s ease;
        }

        .info-header span {
            font-size: 20px;
            transition: transform 0.3s ease;
            color: #007bff;
        }

        .info-header.collapsed span {
            transform: rotate(0deg);
        }

        .info-header:not(.collapsed) span {
            transform: rotate(180deg);
        }

        .info-content {
            padding: 15px;
            background-color: #f9f9f9;
            display: none;
            border-radius: 8px;
        }

        .info-item {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .info-item strong {
            color: #333;
            flex-basis: 40%;
        }

        .info-item span {
            color: #555;
            flex-basis: 60%;
            text-align: left;
        }
    </style>
</head>

<body>
    <?php require_once("../Menu/menu.php"); ?>
    <?php require_once("../Header/MainHeader.php"); ?>

    <div class="br-mainpanel">
        <div class="br-pageheader pd-y-15 pd-l-20">
            <a class="breadcrumb-item" href="../Home/home.php">Inicio</a>
            <span class="breadcrumb-item active">Persona</span>
        </div>

        <div class="pd-x-20 pd-sm-x-30 pd-t-20 pd-sm-t-30">
            <section class="card">
                <header class="card-header">
                    <h3 class="text-primary">Datos Personales</h3>
                    <p>Visualiza tus datos personales.</p>
                </header>

                <div class="container">
                    <br>
                    <!-- Sección de Encabezado con Foto y Nombre Completo -->
                    <div class="header">
                        <img id="userPhoto" src="" alt="Foto de Usuario" />
                        <h2 id="userName">Nombre Completo</h2>
                    </div>

                    <!-- Sección de Información Personal -->
                    <div class="info-section">
                        <button class="col-12 info-header" onclick="toggleSection(this)">
                            Información Personal
                            <span>&#9660;</span>
                        </button>
                        <div class="info-content">
                            <div class="info-item"><strong>Nombre:</strong> <span id="nombre"></span></div>
                            <div class="info-item"><strong>Apellidos:</strong> <span id="apellido"></span></div>
                            <div class="info-item"><strong>DNI:</strong> <span id="dni"></span></div>
                            <div class="info-item"><strong>Fecha de Nacimiento:</strong> <span id="fechaNacimiento"></span></div>
                            <div class="info-item"><strong>Estado Civil:</strong> <span id="estadoCivil"></span></div>
                        </div>
                    </div>

                    <!-- Sección de Información de Contacto -->
                    <div class="info-section">
                        <button class="col-12 info-header" onclick="toggleSection(this)">
                            Información de Contacto
                            <span>&#9660;</span>
                        </button>
                        <div class="info-content">
                            <div class="info-item"><strong>Teléfono Fijo:</strong> <span id="telefijo"></span></div>
                            <div class="info-item"><strong>Celular 1:</strong> <span id="celu01"></span></div>
                            <div class="info-item"><strong>Celular 2:</strong> <span id="celu02"></span></div>
                            <div class="info-item"><strong>Email Personal:</strong> <span id="emailp"></span></div>
                            <div class="info-item"><strong>Email Institucional:</strong> <span id="emailm"></span></div>
                            <div class="info-item"><strong>Dirección:</strong> <span id="direccion"></span></div>
                        </div>
                    </div>


                    <!-- Sección de formacion profesional -->
                    <div class="info-section">
    <button class="col-12 info-header" onclick="toggleSection(this)">
        Situación Laboral
        <span>&#9660;</span>
    </button>
    <div class="info-content">
        <div class="info-item"><strong>Dependencia:</strong> <span id="dependencia"></span></div>
        <div class="info-item"><strong>Cargo:</strong> <span id="cargo"></span></div>
        <div class="info-item"><strong>Tipo de Empleado:</strong> <span id="tipoEmpleado"></span></div>
        <div class="info-item"><strong>Condición Laboral:</strong> <span id="condicionLaboral"></span></div>
        <div class="info-item"><strong>Grupo Ocupacional:</strong> <span id="grupoOcupacional"></span></div>
    </div>
</div>

                </div>
            </section>
        </div>
    </div>

    <?php require_once("../Js/MainJs.php"); ?>
    <script type="text/javascript" src="persona.js"></script>

    
</body>

</html>

<?php
} else {
    header("Location:" . Conectar::ruta() . "view/404/404.php");
}
?>
