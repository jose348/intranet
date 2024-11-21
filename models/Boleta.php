<?php
class Boleta extends Conectar {
    public function get_boletas_por_dni($dni) {
        $conectar = parent::Conexion("dbsimcix");
        parent::set_names();

        $sql = "SELECT bdc.anio, bdc.mes, bdc.bodc_archivo, tipoporc.tiproc_nombre
                FROM sc_remuneraciones.boletadetallecab bdc
                INNER JOIN sc_remuneraciones.trabajador_activo tr ON bdc.codigo = tr.codigo
                INNER JOIN sc_remuneraciones.tipoproceso tipoporc ON bdc.tipoproc = tipoporc.tiproc_id
                WHERE tr.numdoc = ?
                ORDER BY bdc.anio DESC, CAST(bdc.mes AS INTEGER) DESC";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $dni, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
