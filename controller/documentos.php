<?php
require_once("../config/conexion.php");
require_once("../models/Documentos.php");

$documentos = new Documentos();

switch ($_GET["op"]) {
    case "listar_archivos":
        // Verifica si hay un DNI en la sesión
        if (isset($_SESSION["acce_dni"])) {
            $dni = $_SESSION["acce_dni"];

            // Obtén el ID del usuario basado en el DNI
            $pers_id = $documentos->get_id_persona_por_dni($dni);

            if ($pers_id) {
                $datos = $documentos->get_documentos_por_usuario($pers_id);
                echo json_encode($datos);
            } else {
                echo json_encode(["error" => "Usuario no encontrado."]);
            }
        } else {
            echo json_encode(["error" => "No se encontró el DNI en la sesión."]);
        }
        break;

      
        case "subir_archivo":
            if (isset($_FILES["file"]) && isset($_SESSION["acce_dni"])) {
                $file = $_FILES["file"];
                $dni = $_SESSION["acce_dni"];
    
                // Verificar tamaño máximo de 4 MB
                if ($file["size"] > 4 * 1024 * 1024) {
                    echo json_encode(["error" => "El archivo supera el límite de 4 MB."]);
                    exit();
                }
    
                // Validar extensión y limpiar nombre
                $allowedExtensions = ["jpg", "jpeg", "png", "pdf", "doc", "docx"];
                $fileExtension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
                if (!in_array($fileExtension, $allowedExtensions)) {
                    echo json_encode(["error" => "Tipo de archivo no permitido."]);
                    exit();
                }
    
                $uploadDir = "../document/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true); // Crear la carpeta si no existe
                }
    
                $cleanFileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $file["name"]);
                $uniqueName = uniqid() . "_" . $cleanFileName;
                $filePath = $uploadDir . $uniqueName;
    
                if (move_uploaded_file($file["tmp_name"], $filePath)) {
                    // Guardar en la base de datos
                    $documentos->guardar_archivo($dni, $cleanFileName, "/document/" . $uniqueName, $fileExtension, $file["size"]);
                    echo json_encode(["success" => "Archivo subido correctamente."]);
                } else {
                    error_log("Error al mover archivo: " . $file["tmp_name"] . " -> " . $filePath);
                    echo json_encode(["error" => "Error al mover el archivo."]);
                }
            } else {
                echo json_encode(["error" => "No se proporcionó ningún archivo o sesión inválida."]);
            }
            break;
            
            case 'eliminar_archivo':
                if (isset($_POST['dope_id'])) {
                    $dope_id = intval($_POST['dope_id']);
            
                    $result = $documentos->eliminar_archivo($dope_id);
            
                    if ($result) {
                        echo json_encode(['success' => 'Archivo eliminado correctamente.']);
                    } else {
                        error_log("Error al intentar cambiar el estado del archivo con ID $dope_id");
                        echo json_encode(['error' => 'No se pudo eliminar el archivo.']);
                    }
                } else {
                    echo json_encode(['error' => 'ID del archivo no proporcionado.']);
                }
                break;
            
            
               
                
            
    
      
    }
