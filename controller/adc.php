<?php
require_once("../config/conexion.php");
require_once("../models/Adc.php");

$adc = new Adc();
switch ($_GET["op"]) {
    case "obtener_capacitaciones":
        // Verificar que 'start' y 'end' están definidos en la solicitud
        if (!isset($_GET['start']) || !isset($_GET['end'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Los parámetros start y end son necesarios."
            ]);
            exit();
        }
        // Obtener las capacitaciones entre el rango de fechas
        $start = $_GET['start'];
        $end = $_GET['end'];

        $datos = $adc->get_capacitaciones($start, $end);

        // Lista de colores predefinidos
        $colores = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'];

        // Preparar el array de eventos en el formato esperado por FullCalendar
        $eventos = [];
        $color_index = 0;

        foreach ($datos as $row) {
            // Asignar un color a cada evento de la lista de colores
            $color = $colores[$color_index % count($colores)];
            $color_index++;

            $eventos[] = [
                'id' => $row['capa_id'],
                'title' => $row['capa_titulo'],
                'expositor' => $row['capa_expositor'], // Asegúrate de enviar esta propiedad
                'start' => $row['capa_fecha_inicio'] . 'T' . $row['capa_hora_inicio'],
                'end' => $row['capa_fecha_fin'] . 'T' . $row['capa_hora_fin'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#fff'
            ];
        }

        echo json_encode($eventos);
        break;


        // Cambiar el estado a "cancelada" al eliminar una capacitación



        // Listar personas desde la tabla persona del escalafón
    case "listar_personas":
        try {
            $datos = $adc->listarpersonas();
            echo json_encode($datos);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

    case "eliminar_persona":
        $pers_id = $_POST['id']; // ID de la persona a eliminar
        $adc->eliminar_persona($pers_id);
        echo json_encode(["status" => "success"]);
        break;





        case "guardar_capacitacion":
            // Recoger datos del formulario
            $titulo = $_POST['tituloCapacitacion'] ?? null;
            $expositor = $_POST['expositorCapacitacion'] ?? null;
            $fechaInicio = $_POST['fechaInicio'] ?? null;
            $horaInicio = $_POST['horaInicio'] ?? null;
            $fechaFin = $_POST['fechaFin'] ?? null;
            $horaFin = $_POST['horaFin'] ?? null;
            $linkCapacitacion = $_POST['linkCapacitacion'] ?? null;
        
            // Definir directorio de subida
            $uploadDir = '../uploads/';
            $flyerPath = null;
            $archivoPath = null;
            $videoPath = null;
        
            // Guardar el flyer
            if (!empty($_FILES['flyerCapacitacion']['name'])) {
                $flyerFileName = basename($_FILES['flyerCapacitacion']['name']);
                $flyerPath = $uploadDir . $flyerFileName;
                if (!move_uploaded_file($_FILES['flyerCapacitacion']['tmp_name'], $flyerPath)) {
                    echo json_encode(["status" => "error", "message" => "Error al subir el flyer."]);
                    exit;
                }
            }
        
            // Guardar los archivos
            if (!empty($_FILES['archivoCapacitacion']['name'][0])) {
                $archivos = [];
                foreach ($_FILES['archivoCapacitacion']['name'] as $key => $name) {
                    $archivoFileName = basename($name);
                    $archivoPath = $uploadDir . $archivoFileName;
                    if (!move_uploaded_file($_FILES['archivoCapacitacion']['tmp_name'][$key], $archivoPath)) {
                        echo json_encode(["status" => "error", "message" => "Error al subir uno de los archivos."]);
                        exit;
                    }
                    $archivos[] = $archivoPath;
                }
                $archivoPath = implode(',', $archivos); // Guardar como string separado por comas
            }
        
            // Guardar el video
            if (!empty($_FILES['videoCapacitacion']['name'])) {
                $videoFileName = basename($_FILES['videoCapacitacion']['name']);
                $videoPath = $uploadDir . $videoFileName;
                if (!move_uploaded_file($_FILES['videoCapacitacion']['tmp_name'], $videoPath)) {
                    echo json_encode(["status" => "error", "message" => "Error al subir el video."]);
                    exit;
                }
            }
        
            // Guardar en la base de datos usando el modelo
            $adc->guardarCapacitacion($titulo, $expositor, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $flyerPath, $archivoPath, $videoPath, $linkCapacitacion);
            echo json_encode(["status" => "success"]);
            break;
        






    case "listar_capacitaciones_mes":
        try {
            $mes = $_GET['mes']; // Mes seleccionado
            $anio = $_GET['anio']; // Año seleccionado

            // Asegúrate de que mes y año se reciban correctamente
            if (empty($mes) || empty($anio)) {
                throw new Exception("Parámetros mes o año no proporcionados");
            }

            $datos = $adc->obtenerCapacitacionesPorMes($mes, $anio);

            $capacitaciones_pasadas = [];
            $capacitaciones_en_proceso = [];
            $capacitaciones_futuras = [];
            $hoy = new DateTime();

            foreach ($datos as $evento) {
                $fecha_inicio = new DateTime($evento['capa_fecha_inicio']);
                $fecha_fin = new DateTime($evento['capa_fecha_fin']);

                if ($fecha_inicio < $hoy && $fecha_fin < $hoy) {
                    $capacitaciones_pasadas[] = $evento;
                } elseif ($fecha_inicio <= $hoy && $fecha_fin >= $hoy) {
                    $capacitaciones_en_proceso[] = $evento;
                } else {
                    $capacitaciones_futuras[] = $evento;
                }
            }

            echo json_encode([
                'pasadas' => $capacitaciones_pasadas,
                'en_proceso' => $capacitaciones_en_proceso,
                'futuras' => $capacitaciones_futuras,
            ]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;


    case "eliminar_capacitacion":
        try {
            if (isset($_POST['id'])) {
                $capa_id = $_POST['id'];
                $adc->eliminar_capacitacion($capa_id);

                echo json_encode([
                    "status" => "success",
                    "message" => "Capacitación eliminada correctamente."
                ]);
            } else {
                throw new Exception("ID de la capacitación no proporcionado.");
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }


        break;




    case "obtener_capacitacion":
        if (isset($_POST['id'])) {
            $capa_id = $_POST['id'];
            $capacitacion = $adc->obtenerCapacitacionPorId($capa_id);
            echo json_encode(["status" => "success", "capacitacion" => $capacitacion]);
        } else {
            echo json_encode(["status" => "error", "message" => "ID no proporcionado."]);
        }
        break;






    case "actualizar_capacitacion":
        try {
            $id = $_POST['capacitacionId'];
            $titulo = $_POST['tituloCapacitacion'];
            $expositor = $_POST['expositorCapacitacion'];
            $fechaInicio = $_POST['fechaInicio'];
            $horaInicio = $_POST['horaInicio'];
            $fechaFin = $_POST['fechaFin'];
            $horaFin = $_POST['horaFin'];

            $uploadDir = '../uploads/';

            // Obtener los archivos anteriores
            $capacitacionExistente = $adc->obtenerCapacitacionPorId($id);
            $flyerPath = $capacitacionExistente['capa_flyer'];
            $archivoPath = $capacitacionExistente['capa_archivo'];
            $videoPath = $capacitacionExistente['capa_video'];

            // Verificar si se sube un nuevo flyer
            if (isset($_FILES['flyerCapacitacion']) && !empty($_FILES['flyerCapacitacion']['name'])) {
                $flyerPath = $uploadDir . basename($_FILES['flyerCapacitacion']['name']);
                move_uploaded_file($_FILES['flyerCapacitacion']['tmp_name'], $flyerPath);
            }

            // Verificar si se suben nuevos archivos
            if (isset($_FILES['archivoCapacitacion']['name'][0]) && !empty($_FILES['archivoCapacitacion']['name'][0])) {
                $archivos = [];
                foreach ($_FILES['archivoCapacitacion']['name'] as $key => $name) {
                    $path = $uploadDir . basename($name);
                    move_uploaded_file($_FILES['archivoCapacitacion']['tmp_name'][$key], $path);
                    $archivos[] = $path;
                }
                $archivoPath = implode(',', $archivos);
            }

            // Verificar si se sube un nuevo video
            if (isset($_FILES['videoCapacitacion']) && !empty($_FILES['videoCapacitacion']['name'])) {
                $videoPath = $uploadDir . basename($_FILES['videoCapacitacion']['name']);
                move_uploaded_file($_FILES['videoCapacitacion']['tmp_name'], $videoPath);
            }

            // Actualizar la capacitación
            $adc->actualizarCapacitacion([
                'id' => $id,
                'titulo' => $titulo,
                'expositor' => $expositor,
                'fecha_inicio' => $fechaInicio,
                'hora_inicio' => $horaInicio,
                'fecha_fin' => $fechaFin,
                'hora_fin' => $horaFin,
                'flyer' => $flyerPath,
                'archivo' => $archivoPath,
                'video' => $videoPath,
            ]);

            echo json_encode(["status" => "success", "message" => "Capacitación actualizada correctamente."]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        break;

        /*TODO GUARDAMOS LA CAPACITACION-PERSONAS  JUNTO CON LAS PERSONAS PARA ENVIARLAS */
        /*TODO GUARDAMOS LA CAPACITACION-PERSONAS  JUNTO CON LAS PERSONAS PARA ENVIARLAS */
    case 'listar_capacitacionest':
        $capacitaciones = $adc->listarCapacitaciones();
        if (count($capacitaciones) > 0) {
            echo json_encode(['status' => 'success', 'data' => $capacitaciones]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron capacitaciones activas.']);
        }
        break;




    case "guardar_capacitacion_persona":
        // Obtener el cuerpo de la solicitud en formato JSON
        $input = json_decode(file_get_contents("php://input"), true);

        // Verificar que los datos necesarios estén presentes
        if (!isset($input["capa_id"]) || !isset($input["pers_ids"]) || !is_array($input["pers_ids"])) {
            echo json_encode([
                "status" => "error",
                "message" => "Datos incompletos o formato incorrecto."
            ]);
            exit();
        }

        $capa_id = $input["capa_id"]; // ID de la capacitación
        $pers_ids = $input["pers_ids"]; // Array de IDs de personas

        // Llamar al método del modelo para guardar las asignaciones
        $resultado = $adc->guardarCapacitacionPersona($capa_id, $pers_ids);

        if ($resultado) {
            echo json_encode([
                "status" => "success",
                "message" => "Capacitación asignada correctamente."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No se pudo asignar la capacitación."
            ]);
        }
        break;

        /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
        /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
        /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
        /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
        /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
        /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
    case 'obtener_notificaciones':
        if (isset($_GET['pers_id'])) {
            $capacitacion->obtener_notificaciones($_GET['pers_id']);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "ID de usuario no proporcionado."
            ]);
        }
        break;

    case 'cambiar_estado_notificacion':
        if (isset($_POST['caper_id']) && isset($_POST['accion'])) {
            $caper_id = $_POST['caper_id'];
            $accion = $_POST['accion'];

            // Determinar el nuevo estado según la acción
            $nuevoEstado = $accion === "asistir" ? true : false;
            $capacitacion->cambiar_estado_notificacion($caper_id, $nuevoEstado);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "ID de notificación o acción no proporcionados."
            ]);
        }
        break;


    case "obtener_capacitaciones_id":
        $pers_id = $_GET['pers_id'];

        if (!empty($pers_id)) {
            $datos = $capacitacion->get_capacitaciones_por_usuario($pers_id);

            $eventos = [];
            $hoy = new DateTime();

            foreach ($datos as $row) {
                // Calcular las fechas de inicio y fin de la capacitación
                $fecha_inicio = new DateTime($row['capa_fecha_inicio']);
                $fecha_fin = new DateTime($row['capa_fecha_fin']);

                // Determinar el color según el estado
                if ($fecha_fin < $hoy) {
                    // Capacitación pasada
                    $color = '#dc3545'; // Rojo
                } elseif ($fecha_inicio <= $hoy && $fecha_fin >= $hoy) {
                    // Capacitación en proceso
                    $color = '#ffc107'; // Amarillo
                } else {
                    // Capacitación futura
                    $color = '#28a745'; // Verde
                }

                // Agregar el evento al array de eventos
                $eventos[] = [
                    'id' => $row['capa_id'],
                    'title' => $row['capa_titulo'],
                    'expositor' => $row['capa_expositor'],
                    'start' => $row['capa_fecha_inicio'] . 'T' . $row['capa_hora_inicio'],
                    'end' => $row['capa_fecha_fin'] . 'T' . $row['capa_hora_fin'],
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#fff',
                     
                ];
            }

            echo json_encode($eventos);
        } else {
            echo json_encode([]);
        }
        break;



        /*TODO  Cpacitaciones.php anda capacitaciones.js */
        /*TODO  Cpacitaciones.php anda capacitaciones.js */
        /*TODO  Cpacitaciones.php anda capacitaciones.js */
    case 'obtener_flyers_por_usuario':
        if (isset($_GET['pers_id'])) {
            $pers_id = $_GET['pers_id'];
            $datos = $capacitacion->obtenerFlyersPorUsuario($pers_id);

            // Agrega un log para ver los datos devueltos por el modelo
            error_log(json_encode($datos));

            echo json_encode([
                "status" => "success",
                "data" => $datos
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "ID de usuario no proporcionado."
            ]);
        }
        break;


    case 'obtener_capacitaciones_usuario':
        if (isset($_GET['pers_id'])) {
            $pers_id = $_GET['pers_id'];
            $datos = $capacitacion->get_capacitaciones_por_usuario_tabla($pers_id);

            echo json_encode(['status' => 'success', 'data' => $datos]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado.']);
        }
        break;


// En tu controlador PHP (capacitacion.php)
case 'actualizar_asistencia':
    if (isset($_POST['caper_id']) && isset($_POST['asistir'])) {
        $caper_id = $_POST['caper_id'];
        $asistir = $_POST['asistir'] === 'true' ? true : false; // Convertir a booleano

        $capacitacion->actualizarAsistencia($caper_id, $asistir); // Llama a la función del modelo
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Faltan parámetros."]);
    }
    break;

       

       
            
        
        
}