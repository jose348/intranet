<?php
require_once("../config/conexion.php");
require_once("../models/Boleta.php");
require_once("../libs/fpdf/fpdf.php"); // Asegúrate de incluir FPDF

$boleta = new Boleta();
switch ($_GET["op"]) {

   
    case "listar":
        // Verifica que el DNI esté en la sesión
        if (isset($_SESSION["acce_dni"])) {
            $dni = $_SESSION["acce_dni"];
            $datos = $boleta->get_boletas_por_dni($dni);

            // Enviar los datos como respuesta JSON
            echo json_encode($datos);
        } else {
            echo json_encode(["error" => "No se encontró el DNI en la sesión"]);
        }
        break;


        case "visualizar_pdf":
            if (isset($_GET["ruta"])) {
                $ruta = urldecode($_GET["ruta"]);
                if (file_exists($ruta)) {
                    header("Content-Type: application/pdf");
                    readfile($ruta);
                } else {
                    echo "El archivo no existe.";
                }
            } else {
                echo "Ruta no proporcionada.";
            }
            break;
        
        case "descargar_pdf":
            if (isset($_GET["ruta"])) {
                $ruta = urldecode($_GET["ruta"]);
                if (file_exists($ruta)) {
                    header("Content-Disposition: attachment; filename=\"" . basename($ruta) . "\"");
                    header("Content-Type: application/pdf");
                    readfile($ruta);
                } else {
                    echo "El archivo no existe.";
                }
            } else {
                echo "Ruta no proporcionada.";
            }
            break;
        
    }
    ?>