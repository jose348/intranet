<?php
class Bitacora extends Conectar
{
    
    //Funcion para actualizar la pers_id de la tabla tb_bitacora, usando esta función
    public function update_bitacora_varios($pers_id, $bita_idmin)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $bita_idmax = $this->get_max_id()[0]["bita_id"];
        //Imprimir $bita_idmin pero sin usar ech
        
        $sql = "UPDATE sc_seguridad.tb_bitacora SET pers_id = ? WHERE bita_id BETWEEN ? AND ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $pers_id);
        $sql->bindValue(2, $bita_idmin);
        $sql->bindValue(3, $bita_idmax);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    //Funcion para actualizar la pers_id de la tabla tb_bitacora, usando esta función
    public function update_bitacora($pers_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $bita_id = $this->get_max_id()[0]["bita_id"]; //5910    //5915      5
        $sql = "UPDATE sc_seguridad.tb_bitacora SET pers_id = ? WHERE bita_id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $pers_id);
        $sql->bindValue(2, $bita_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    //Funcion para devolver el maximo id de la tabla tb_bitacora, usando esta función
    public function get_max_id()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT MAX(bita_id) AS bita_id FROM sc_seguridad.tb_bitacora;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }




    

}
?>