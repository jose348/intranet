<?php
class Documentos extends Conectar {
    // Obtiene el ID de persona basado en el DNI
    public function get_id_persona_por_dni($dni) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT pers_id FROM sc_escalafon.tb_persona WHERE pers_dni = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $dni);
        $stmt->execute();

        return $stmt->fetchColumn(); // Devuelve el `pers_id`
    }

    // Obtiene los documentos del usuario basado en `pers_id`
    public function get_documentos_por_usuario($pers_id) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT 
                    dope_id, 
                    dope_nombre, 
                    dope_ruta, 
                    dope_tipo, 
                    dope_tamano, 
                    dope_fecha_subida
                FROM sc_intranet.tb_documentos_personales 
                WHERE pers_id = ? and dope_estado='1' order by dope_fecha_subida desc";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $pers_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function guardar_archivo($dni, $nombre, $ruta, $tipo, $tamano) {
        $conectar = parent::Conexion();
        parent::set_names();

        // Obtener el ID de la persona basado en el DNI
        $sqlPersona = "SELECT pers_id FROM sc_escalafon.tb_persona WHERE pers_dni = ?";
        $stmtPersona = $conectar->prepare($sqlPersona);
        $stmtPersona->bindValue(1, $dni, PDO::PARAM_STR);
        $stmtPersona->execute();
        $result = $stmtPersona->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Usuario no encontrado.");
        }

        $pers_id = $result["pers_id"];

        // Insertar el archivo en la base de datos
        $sql = "INSERT INTO sc_intranet.tb_documentos_personales (pers_id, dope_nombre, dope_ruta, dope_tipo, dope_tamano,dope_estado) 
                VALUES (?, ?, ?, ?, ?,1)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $pers_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $nombre, PDO::PARAM_STR);
        $stmt->bindValue(3, $ruta, PDO::PARAM_STR);
        $stmt->bindValue(4, $tipo, PDO::PARAM_STR);
        $stmt->bindValue(5, $tamano, PDO::PARAM_INT);
        $stmt->execute();
    }

 
    public function eliminar_archivo($dope_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
    
            $sql = "UPDATE sc_intranet.tb_documentos_personales 
                    SET dope_estado = 0 
                    WHERE dope_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $dope_id, PDO::PARAM_INT);
    
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al eliminar archivo con dope_id $dope_id: " . $e->getMessage());
            return false;
        }
    }
    
  
    
  
}
