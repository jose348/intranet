<?php
require_once("../config/conexion.php");
require_once("../models/Persona.php");
 
 
$persona = new Persona();

switch ($_GET["op"]) {

    case "mostrar":
        if (isset($_SESSION["id"])) {
            $datos = $persona->get_persona_por_id($_SESSION["id"]);
            if (!empty($datos)) {
                echo json_encode($datos[0]); // Enviar el primer registro como JSON, incluyendo 'pers_foto' en Base64
            } else {
                echo json_encode(["error" => "No se encontraron datos para el ID especificado en la sesión."]);
            }
        } else {
            echo json_encode(["error" => "No se encontró el ID en la sesión."]);
        }
        break;
    
        case "situacion_laboral":
            if (isset($_SESSION["id"])) {
                $datos = $persona->get_situacion_laboral($_SESSION["id"]);
                echo json_encode($datos ? $datos : ["error" => "No se encontraron datos de situación laboral."]);
            }
            break;
    
    }