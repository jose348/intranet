<?php

class Token extends Conectar{

    public function updateToken($direct_id, $token_actual, $token_nuevo) {
        $cnn = parent::conexion();
        parent::set_names();

        // Lógica para verificar si la contraseña actual es correcta
        $sql = "SELECT * FROM sc_residuos_solidos.tb_directorio WHERE direct_id = ? AND direct_token = ?";
        $stmt = $cnn->prepare($sql);
        $stmt->execute([$direct_id, $token_actual]);

        if ($stmt->rowCount() > 0) {
            // Si la contraseña es correcta, actualizamos el token
            $sqlUpdate = "UPDATE sc_residuos_solidos.tb_directorio SET direct_token = ? WHERE direct_id = ?";
            $stmtUpdate = $cnn->prepare($sqlUpdate);
            $stmtUpdate->execute([$token_nuevo, $direct_id]);

            return ["status" => "success", "message" => "Token actualizado correctamente."];
        } else {
            return ["status" => "error", "message" => "Contraseña actual incorrecta."];
        }
    }
}

?>  