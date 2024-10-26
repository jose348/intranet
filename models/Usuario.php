<?php
class Usuario extends Conectar{

public function Login(){
    $conectar=parent::Conexion();
    parent::set_names();
    if(isset($_POST["enviar"])){
        $dni=$_POST["dni"];
        $pass=$_POST["pass"];
        if(empty($dni) and empty($pass)  ){
            header("Location:".conectar::ruta()."index.php?m=2");
            exit();
        }else{
            $sql="SELECT * FROM sc_remuneraciones.acceso WHERE acce_dni=? AND acce_password=? AND acce_estado=1";
            $stmt = $conectar->prepare($sql);
            $stmt -> bindValue(1,$dni);
            $stmt -> bindValue(2,$pass);
            $stmt->execute();
            $resultado = $stmt->fetch();
             
            if(is_array($resultado) and count($resultado) >0){
                $_SESSION["id"]=$resultado["id"];
                $_SESSION["acce_nombre"]=$resultado["acce_nombre"];
                $_SESSION["acce_apellidos"]=$resultado["acce_apellidos"];
                $_SESSION["acce_dni"]=$resultado["acce_dni"];
                $_SESSION["acce_rol"]=$resultado["acce_rol"];
                
                header("Location:".Conectar::ruta()."view/Home/home.php");

            }else{
                header("Location:".Conectar::ruta()."index.php?m=1");
                exit();
            }
        
        }
    }
}

}
?>