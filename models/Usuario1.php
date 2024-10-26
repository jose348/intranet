<?php 

class Usuario1 extends Conectar{


    public function get_Persona_por_id($pers_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM sc_escalafon.tb_persona 
        INNER JOIN sc_escalafon.tb_estado_civil ON sc_escalafon.tb_persona.esci_id = sc_escalafon.tb_estado_civil.esci_id
        WHERE pers_id = ?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $pers_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }
    public function login()
    {
        $conectar = parent::conexion();
        parent::set_names();
        if (isset($_POST["enviar"])) {
            $dni=$_POST["dni"];
            $pass=$_POST["pass"];
            if (empty($dni) and empty($pass)) {
                header("Location:" . conectar::ruta() . "index.php?m=2");
                exit();
            } else {

                $ip = $_SERVER['REMOTE_ADDR'];
                if (strpos($ip, '::') === 0) {
                    // Si es IPv6, intenta obtener la dirección IPv4 local
                    $ip = '::1'; // Dirección IPv6 de localhost

                    // REEMPLAZAR POR LA DIRECCIÓN IP DE LA PC
                    $ip = "192.168.12.44";
                }

                //comienzo API seguridad
                $ch = curl_init();
                //$ws_reniec = "http://192.168.12.77/sisSeguridad/ws/ws.php/?op=login&pers_dni=" . $dni . "&pers_contrasena=" . $pass . "&pers_ip=" . $ip . "&sist_inic=SISI";
                $ws_reniec = "https://www.munichiclayo.gob.pe/sisSeguridad/ws/ws.php/?op=login&pers_dni=" . $dni . "&pers_contrasena=" . $pass . "&pers_ip=" . $ip . "&sist_inic=INTRANET";


                curl_setopt($ch, CURLOPT_URL, $ws_reniec);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                    echo json_encode(array('error' => 'Error al conectarse al servicio'));
                } else {
                    curl_close($ch);
                    // Decodificar la respuesta JSON en un array asociativo
                    $data = json_decode($response, true);
                }
                //fin API

                //La respuesta me la trae así:
                //{"status":404,"detalle":"Datos incorrectos"}
                //Guardar en una variable el detalle
                $detalle = $data["detalle"];

                if ($detalle == "No se encontraron datos") {
                    header("Location:" . Conectar::ruta() . 'index.php?m=3');
                    exit();
                } elseif ($detalle == "IP Persona no registrada") {
                    header("Location:" . Conectar::ruta() . 'index.php?m=4');
                    exit();
                } elseif ($detalle == "Persona inactiva") {
                    header("Location:" . Conectar::ruta() . 'index.php?m=5');
                    exit();
                } elseif ($detalle == "Fuera de la hora de acceso") {
                    header("Location:" . Conectar::ruta() . 'index.php?m=6');
                    exit();
                } elseif ($detalle == "Usuario no vigente") {
                    header("Location:" . Conectar::ruta() . 'index.php?m=7');
                    exit();
                } elseif ($detalle == "Datos incorrectos") {
                    header("Location:" . Conectar::ruta() . 'index.php?m=8');
                    exit();
                } elseif ($detalle == "Blanqueamiento de clave") {
                    //header("Location:" . 'http://192.168.12.77/sisSeguridad/USURecuperacionContra/blanqueamiento.php?dni='.$dni.'&sistema=sisi');
                    header("Location:" . 'https://www.munichiclayo.gob.pe/sisSeguridad/view/USURecuperacionContra/blanqueamiento.php?dni='.$dni.'&sistema=INTRANET');
                    exit();
                }elseif($detalle=="Acceso concedido"){
                    $_SESSION["id"] = 0;
                    $_SESSION["acce_dni"] = $data["pers_dni"];
                    $_SESSION["acce_rol"] = $data["perm_tipo"];
                    $_SESSION["usua_estado"] = $data["pers_estado"];
                    $_SESSION["historial_login"] = $data["hise_id"];
                    $_SESSION["acce_apellidos"] = $data["pers_apelpat"] . " " . $data["pers_apelmat"];
                    $_SESSION["acce_nombre"] = $data["pers_nombre"];
                    $_SESSION["nombre_completo"] = $data["pers_nombre"] . " " . $data["pers_apelpat"] . " " . $data["pers_apelmat"];
                    $_SESSION["rol_nombre"] = $data["perf_nombre"];
                    $_SESSION["pers_emailm"] = $data["pers_emailm"];
                    $_SESSION["pers_celu01"] = $data["pers_celu01"];
                    
                    header("Location: " . Conectar::ruta() . "view/Home/home.php");
                    exit();
                }

                // Verificar si la decodificaciÃ³n fue exitosa
                if ($data !== null) {

                    $_SESSION["id"] = $data["pers_id"];
                    $_SESSION["acce_dni"] = $data["pers_dni"];
                    $_SESSION["acce_rol"] = $data["perf_id"];
                    $_SESSION["usua_estado"] = $data["pers_estado"];
                    $_SESSION["historial_login"] = $data["hise_id"];
                    $_SESSION["acce_apellidos"] = $data["pers_apelpat"] . " " . $data["pers_apelmat"];
                    $_SESSION["acce_nombre"] = $data["pers_nombre"];
                    $_SESSION["nombre_completo"] = $data["pers_nombre"] . " " . $data["pers_apelpat"] . " " . $data["pers_apelmat"];
                    $_SESSION["rol_nombre"] = $data["perf_nombre"];
                    $_SESSION["pers_emailm"] = $data["pers_emailm"];
                    $_SESSION["pers_celu01"] = $data["pers_celu01"];
                    $_SESSION["historial_login"] = $data["hise_id"];
                    $_SESSION["depe_id"] = $data["depe_id"];


                    //Guardar en la variable de sesión depe_nombre
                    $datos = $this->get_dependenciasactivasbyid($data["depe_id"]);

                    foreach ($datos as $row) {
                        $_SESSION["depe_denominacion"] = $row["depe_denominacion"];
                        $_SESSION["depe_abreviatura"] = $row["depe_abreviatura"];
                    }

                    $datos2 = $this->get_Persona_por_id($data["pers_id"]);
                    foreach ($datos2 as $row) {
                        if ($row["pers_foto"] == "") {
                            $_SESSION["pers_foto"] = "../../public/img/usuario.png";
                        } else {
                            $_SESSION["pers_foto"] = "data:image/png;base64," . $row["pers_foto"];
                        }
                    }

                    header("Location: " . Conectar::ruta() . "view/Home/home.php");
                    exit();



                } else {
                    header("Location:" . Conectar::ruta() . 'index.php?m=1');
                    exit();
                }
            }
        }
    }
    public function get_dependenciasactivasbyid($depe_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM public.tb_dependencia WHERE depe_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $depe_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function logout($hise_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $hise_id = $hise_id;

        //comienzo API seguridad
        $ch = curl_init();
        //$ws_reniec = "http://192.168.12.77/sisSeguridad/ws/ws.php/?op=logout&hise_id=" . $hise_id;
        $ws_reniec = "https://www.munichiclayo.gob.pe/sisSeguridad/ws/ws.php/?op=logout&hise_id=" . $hise_id;

        curl_setopt($ch, CURLOPT_URL, $ws_reniec);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            echo json_encode(array('error' => 'Error al conectarse al servicio'));
        } else {
            curl_close($ch);
        }

        session_destroy();
        header("Location:" . Conectar::ruta() . "index.php");
        exit();
    }


    public function cambiar_contrasena_API($pers_id, $claveantigua, $clave, $clave2)
    {
        $conectar = parent::conexion();
        parent::set_names();

        //comienzo API seguridad
        $ch = curl_init();
        //$ws_reniec = "http://192.168.12.77/sisSeguridad/ws/ws.php/?op=cambiar_contrasena&pers_id=" . $pers_id . "&claveantigua=" . $claveantigua . "&clave=" . $clave . "&clave2=" . $clave2;
        $ws_reniec = "https://www.munichiclayo.gob.pe/sisSeguridad/ws/ws.php/?op=cambiar_contrasena&pers_id=" . $pers_id . "&claveantigua=" . $claveantigua . "&clave=" . $clave . "&clave2=" . $clave2;

        curl_setopt($ch, CURLOPT_URL, $ws_reniec);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            echo json_encode(array('error' => 'Error al conectarse al servicio'));
        } else {
            curl_close($ch);
            // Decodificar la respuesta JSON en un array asociativo
            $data = $response;
        }

        return $data;
    }

}


?>