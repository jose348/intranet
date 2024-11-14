<?php
class Capacitacion extends Conectar
{

    // Obtener las capacitaciones entre un rango de fechas
    public function get_capacitaciones($start, $end)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM sc_intranet.tb_capacitaciones WHERE capa_fecha_inicio >= ? 
        AND capa_fecha_fin <= ? and capa_estado='activa' and capa_depe='GTIE' ";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $start);
        $sql->bindValue(2, $end);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // elimanr el estado de la capacitación
    public function cancelar_capacitacion($capa_id)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE sc_intranet.tb_capacitaciones SET capa_estado = 'cancelada' WHERE capa_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $capa_id);
        $sql->execute();
    }

    public function listarpersonas()
    {
        $conectar = parent::conexion();

        // Verificar si el motor es MySQL y usar CONCAT
        $sql = "SELECT pers_id,
                    CONCAT(pers_nombre, ' ', pers_apelpat) AS nombre_completo,
                    pers_dni
                FROM sc_escalafon.tb_persona 
                WHERE pers_estado = 'A'";

        $sql = $conectar->prepare($sql);
        $sql->execute();

        // Retornar los datos
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }



    public function eliminar_persona($pers_id)
    {
        $conectar = parent::conexion();
        $sql = "DELETE FROM sc_escalafon.tb_persona WHERE pers_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindParam(1, $pers_id, PDO::PARAM_INT);
        $sql->execute();
    }


 

    public function guardarCapacitacion($titulo, $expositor, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $flyer, $archivos, $video, $link) {
        $conectar = parent::conexion();
    
        // Solo guarda el nombre del archivo en la base de datos
        $flyer = basename($flyer);
        $archivos = basename($archivos);
        $video = basename($video);
    
        $sql = "INSERT INTO sc_intranet.tb_capacitaciones 
                (capa_titulo, capa_expositor, capa_fecha_inicio, capa_hora_inicio, capa_fecha_fin, capa_hora_fin, capa_flyer, capa_archivo, capa_video, capa_estado, capa_link, capa_depe) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'activa', ?,'GTIE')";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$titulo, $expositor, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $flyer, $archivos, $video, $link]);
    }
    
 


    public function obtenerCapacitacionesPorMes($mes, $anio)
    {
        $conectar = parent::conexion();

        // Log para verificar que los parámetros lleguen correctamente
        error_log("Mes: $mes, Año: $anio");

        $sql = " SELECT * FROM sc_intranet.tb_capacitaciones
WHERE EXTRACT(MONTH FROM capa_fecha_inicio) = ?
  AND EXTRACT(YEAR FROM capa_fecha_inicio) = ?
  AND capa_estado = 'activa' AND capa_depe='GTIE' ";

        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $mes, PDO::PARAM_INT);
        $sql->bindValue(2, $anio, PDO::PARAM_INT);
        $sql->execute();

        // Log para verificar si la consulta devuelve resultados
        $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
        error_log("Resultados: " . json_encode($resultados));

        return $resultados;
    }



    // Método para eliminar una capacitación
    // Método para eliminar una capacitación
    public function eliminar_capacitacion($capa_id)
    {
        $conectar = parent::conexion();
        $sql = "DELETE FROM sc_intranet.tb_capacitaciones WHERE capa_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $capa_id, PDO::PARAM_INT);
        $stmt->execute();
    }


    public function obtenerCapacitacionPorId($capa_id)
    {
        try {
            $conectar = parent::conexion();
            $sql = "SELECT capa_id, capa_titulo, capa_expositor, capa_fecha_inicio, capa_hora_inicio, 
                           capa_fecha_fin, capa_hora_fin, capa_flyer, capa_archivo, capa_video 
                    FROM sc_intranet.tb_capacitaciones WHERE capa_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindParam(1, $capa_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error al obtener la capacitación: " . $e->getMessage());
        }
    }




    public function actualizarCapacitacion($data)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE sc_intranet.tb_capacitaciones 
                SET capa_titulo = ?, capa_expositor = ?, capa_fecha_inicio = ?, 
                    capa_hora_inicio = ?, capa_fecha_fin = ?, capa_hora_fin = ?, 
                    capa_flyer = ?, capa_archivo = ?, capa_video = ? 
                WHERE capa_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data['titulo'],
            $data['expositor'],
            $data['fecha_inicio'],
            $data['hora_inicio'],
            $data['fecha_fin'],
            $data['hora_fin'],
            $data['flyer'],
            $data['archivo'],
            $data['video'],
            $data['id']
        ]);
    }



    // Método para listar todas las capacitaciones// Método para listar todas las capacitaciones
    // Método para listar todas las capacitaciones
    public function listarCapacitaciones()
    {
        $conectar = parent::conexion();
        try {

            // Obtiene la conexión desde la clase padre
            $sql = "SELECT capa_id, capa_titulo, capa_expositor, capa_fecha_inicio, capa_hora_inicio 
	    FROM sc_intranet.tb_capacitaciones where capa_estado='activa' and capa_depe='GTIE' ORDER BY capa_fecha_inicio desc";


            $stmt = $conectar->prepare($sql); // Preparar la consulta


            $stmt->execute(); // Ejecuta la consulta

            // Obtén todos los resultados en un array asociativo
            $capacitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null; // Libera el statement
            return $capacitaciones; // Retorna la lista de capacitaciones

        } catch (Exception $e) {
            // Captura y registra cualquier error ocurrido
            error_log($e->getMessage(), 0);
            return []; // Retorna un array vacío en caso de error
        }
    }



    public function guardarCapacitacionPersona($capa_id, $pers_ids)
{
    try {
        $conectar = parent::conexion();
        $conectar->beginTransaction(); // Iniciar transacción

        // Consulta para verificar si la combinación capa_id y pers_id ya existe
        $checkSql = "SELECT COUNT(*) FROM sc_intranet.tb_capacitacion_persona 
                     WHERE capa_id = :capa_id AND pers_id = :pers_id";
        $checkStmt = $conectar->prepare($checkSql);

        // Consulta para insertar la capacitación en caso de que no exista previamente
        $insertSql = "INSERT INTO sc_intranet.tb_capacitacion_persona 
                      (capa_id, pers_id, caper_confirmar, caper_is_envio) 
                      VALUES (:capa_id, :pers_id, false, true)";
        $insertStmt = $conectar->prepare($insertSql);

        foreach ($pers_ids as $pers_id) {
            // Verificar si el registro ya existe
            $checkStmt->bindParam(":capa_id", $capa_id, PDO::PARAM_INT);
            $checkStmt->bindParam(":pers_id", $pers_id, PDO::PARAM_INT);
            $checkStmt->execute();
            $exists = $checkStmt->fetchColumn();

            // Insertar solo si la combinación no existe
            if ($exists == 0) {
                $insertStmt->bindParam(":capa_id", $capa_id, PDO::PARAM_INT);
                $insertStmt->bindParam(":pers_id", $pers_id, PDO::PARAM_INT);
                $insertStmt->execute();
            }
        }

        $conectar->commit(); // Confirmar la transacción
        return true;
    } catch (Exception $e) {
        $conectar->rollBack(); // Revertir la transacción en caso de error
        error_log("Error al guardar capacitación: " . $e->getMessage()); // Registrar el error
        return false;
    }
}

    


    /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
    /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
    /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
    /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
    /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
    /*TODO APARTIR DE AHORA REALIZAMOS LAS NOTIFICACIONES PARA EL USUARIO  */
    // Función para obtener las notificaciones (capacitaciones asignadas no confirmadas)
    // Función para obtener las notificaciones (capacitaciones asignadas no confirmadas)
    // Función para obtener las notificaciones de capacitaciones asignadas no confirmadas 

    // Método para obtener las notificaciones no confirmadas
    public function obtener_notificaciones($pers_id)
    {
        $conectar = parent::conexion();

        $query = "SELECT cp.caper_id, c.capa_titulo, c.capa_expositor, c.capa_fecha_inicio, c.capa_hora_inicio, cp.caper_confirmar
                  FROM sc_intranet.tb_capacitacion_persona AS cp
                  JOIN sc_intranet.tb_capacitaciones AS c ON cp.capa_id = c.capa_id
                  WHERE cp.pers_id = :pers_id 
                  ORDER BY cp.caper_created_at DESC";

        $stmt = $conectar->prepare($query);
        $stmt->bindParam(':pers_id', $pers_id, PDO::PARAM_INT);
        $stmt->execute();
        $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['notificaciones' => $notificaciones]);
    }

    // Método para marcar una notificación como leída (confirmada)
    // Método para cambiar el estado de la notificación
    public function cambiar_estado_notificacion($caper_id, $nuevoEstado)
    {
        $conectar = parent::conexion();

        // Actualizar el estado de confirmación
        $query = "UPDATE sc_intranet.tb_capacitacion_persona
              SET caper_confirmar = :nuevoEstado, caper_fecha_confirmar = NOW()
              WHERE caper_id = :caper_id";

        $stmt = $conectar->prepare($query);
        $stmt->bindParam(':nuevoEstado', $nuevoEstado, PDO::PARAM_BOOL);
        $stmt->bindParam(':caper_id', $caper_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado de la notificación']);
        }
    }

    public function get_capacitaciones_por_usuario($pers_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT c.capa_titulo,c.capa_id, c.capa_titulo, c.capa_expositor, 
	c.capa_fecha_inicio, c.capa_hora_inicio, c.capa_fecha_fin, 
	c.capa_hora_fin 
	FROM sc_intranet.tb_capacitaciones c 
	inner join sc_intranet.tb_capacitacion_persona cp on cp.capa_id=c.capa_id
    WHERE cp.pers_id = :pers_id";
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':pers_id', $pers_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /*TODO  Cpacitaciones.php anda capacitaciones.js */
    /*TODO  Cpacitaciones.php anda capacitaciones.js */
    /*TODO  Cpacitaciones.php anda capacitaciones.js */
    public function obtenerFlyersPorUsuario($pers_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT c.capa_flyer
                FROM sc_intranet.tb_capacitaciones AS c
                INNER JOIN sc_intranet.tb_capacitacion_persona AS cp ON c.capa_id = cp.capa_id
                WHERE cp.pers_id = :pers_id AND c.capa_estado = 'activa' AND c.capa_flyer IS NOT NULL";

        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':pers_id', $pers_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_capacitaciones_por_usuario_tabla($pers_id)
    {
        $conectar = parent::conexion();
        $sql = "SELECT c.capa_titulo,c.capa_id, c.capa_expositor, 
	c.capa_fecha_inicio,  c.capa_link , c.capa_depe
	FROM sc_intranet.tb_capacitaciones c 
	inner join sc_intranet.tb_capacitacion_persona cp on cp.capa_id=c.capa_id
    WHERE cp.pers_id = :pers_id";
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':pers_id', $pers_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarAsistencia($caper_id, $asistencia) {
        $conectar = parent::conexion();
        $sql = "UPDATE sc_intranet.tb_capacitacion_persona SET caper_confirmar = :asistencia WHERE caper_id = :caper_id";
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(':asistencia', $asistencia, PDO::PARAM_BOOL);
        $stmt->bindParam(':caper_id', $caper_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    

    // En tu modelo (Capacitacion.php)
public function actualizarAsistencia($caper_id, $asistir) {
    $conectar = parent::conexion();
    $sql = "UPDATE sc_intranet.tb_capacitacion_persona 
            SET caper_asistir = :asistir
            WHERE caper_id = :caper_id";
    $stmt = $conectar->prepare($sql);
    $stmt->bindParam(':asistir', $asistir, PDO::PARAM_BOOL);
    $stmt->bindParam(':caper_id', $caper_id, PDO::PARAM_INT);
    $stmt->execute();
}


}
