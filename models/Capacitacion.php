<?php
class Capacitacion extends Conectar
{

    // Obtener las capacitaciones entre un rango de fechas
    public function get_capacitaciones($start, $end)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM sc_intranet.tb_capacitaciones WHERE capa_fecha_inicio >= ? AND capa_fecha_fin <= ? and capa_estado='activa' ";
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
        $sql = "SELECT 
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




    public function guardarCapacitacion($titulo, $expositor, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $flyer, $archivos, $video)
    {
        $conectar = parent::conexion();
        $sql = "INSERT INTO sc_intranet.tb_capacitaciones 
                (capa_titulo, capa_expositor, capa_fecha_inicio, capa_hora_inicio, capa_fecha_fin, capa_hora_fin, capa_flyer, capa_archivo, capa_video, capa_estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'activa')";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([$titulo, $expositor, $fechaInicio, $horaInicio, $fechaFin, $horaFin, $flyer, $archivos, $video]);
    }



    public function obtenerCapacitacionesPorMes($mes, $anio)
    {
        $conectar = parent::conexion();

        // Log para verificar que los parámetros lleguen correctamente
        error_log("Mes: $mes, Año: $anio");

        $sql = " SELECT * FROM sc_intranet.tb_capacitaciones
WHERE EXTRACT(MONTH FROM capa_fecha_inicio) = ?
  AND EXTRACT(YEAR FROM capa_fecha_inicio) = ?
  AND capa_estado = 'activa'";

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
    public function eliminar_capacitacion($capa_id) {
        $conectar = parent::conexion();
        $sql = "DELETE FROM sc_intranet.tb_capacitaciones WHERE capa_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindParam(1, $capa_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
 
    public function obtenerCapacitacionPorId($capa_id) {
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
        
    
    

    public function actualizarCapacitacion($data) {
        $conectar = parent::conexion();
        $sql = "UPDATE sc_intranet.tb_capacitaciones 
                SET capa_titulo = ?, capa_expositor = ?, capa_fecha_inicio = ?, 
                    capa_hora_inicio = ?, capa_fecha_fin = ?, capa_hora_fin = ?, 
                    capa_flyer = ?, capa_archivo = ?, capa_video = ? 
                WHERE capa_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->execute([
            $data['titulo'], $data['expositor'], $data['fecha_inicio'], 
            $data['hora_inicio'], $data['fecha_fin'], $data['hora_fin'], 
            $data['flyer'], $data['archivo'], $data['video'], $data['id']
        ]);
    }
    
     
    
 // Método para listar todas las capacitaciones// Método para listar todas las capacitaciones
      // Método para listar todas las capacitaciones
      public function listarCapacitaciones() {
        try {
            $conectar = parent::conexion(); // Obtiene la conexión desde la clase padre
            $sql = "SELECT capa_id, capa_titulo, capa_expositor, capa_fecha_inicio, capa_hora_inicio 
	    FROM sc_intranet.tb_capacitaciones where capa_estado='activa' ORDER BY capa_fecha_inicio desc";


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
    

}