<?php
class Persona extends Conectar
{
     //Funcion para buscar un Persona por su id, usando la siguiente funcion:

     public function get_persona_por_id($pers_id)
     {
         $conectar = parent::conexion();
         parent::set_names();
         $sql = "SELECT 
                     tb_persona.pers_id,
                     tb_persona.pers_apelpat,
                     tb_persona.pers_apelmat,
                     tb_persona.pers_nombre,
                     tb_persona.pers_dni,
                     tb_persona.pers_fechanac,
                     tb_persona.pers_sexo,
                     tb_persona.pers_telefijo,
                     tb_persona.pers_celu01,
                     tb_persona.pers_celu02,
                     tb_persona.pers_emailp,
                     tb_persona.pers_emailm,
                     tb_persona.pers_estatura,
                     tb_persona.pers_tallacam,
                     tb_persona.pers_tallapan,
                     tb_persona.pers_tallacal,
                     tb_persona.pers_esdisc,
                     tb_persona.pers_esffaa,
                     tb_persona.pers_essalud,
                     tb_persona.pers_direccion,
                     tb_persona.pers_foto,  -- Aquí añadimos la columna de la foto
                     tb_estado_civil.esci_id,
                     tb_estado_civil.esci_denom
                 FROM 
                     sc_escalafon.tb_persona 
                 INNER JOIN 
                     sc_escalafon.tb_estado_civil 
                 ON 
                     tb_persona.esci_id = tb_estado_civil.esci_id
                 WHERE 
                     tb_persona.pers_id = ?";
         $sql = $conectar->prepare($sql);
         $sql->bindValue(1, $pers_id, PDO::PARAM_INT);
         $sql->execute();
         return $sql->fetchAll(PDO::FETCH_ASSOC);
     }


 // Método para obtener situación laboral por ID
 public function get_situacion_laboral($pers_id)
 {
     $conectar = parent::conexion();
     parent::set_names();
     $sql = "SELECT depe.depe_denominacion, car.carg_denominacion, tiem.tiem_nombre,
                    cola.cola_denominacion, gpoc.gpoc_denominacion
             FROM sc_escalafon.tb_situacion_laboral sl
             INNER JOIN public.tb_dependencia depe ON depe.depe_id = sl.depe_id
             INNER JOIN sc_escalafon.tb_cargo car ON car.carg_id = sl.carg_id
             INNER JOIN sc_escalafon.tb_tipo_empleado tiem ON tiem.tiem_id = sl.tiem_id
             INNER JOIN sc_escalafon.tb_condicion_laboral cola ON cola.cola_id = sl.cola_id
             INNER JOIN sc_escalafon.tb_cargo_estructural caes ON caes.caes_id = sl.caes_id
             INNER JOIN sc_escalafon.tb_grupo_ocupacional gpoc ON gpoc.gpoc_id = caes.gpoc_id
             WHERE sl.pers_id = ? AND sl.sila_estado = 'A'";
             
     $stmt = $conectar->prepare($sql);
     $stmt->bindValue(1, $pers_id, PDO::PARAM_INT);
     $stmt->execute();
     return $stmt->fetch(PDO::FETCH_ASSOC);
 }

   
    
    }
       