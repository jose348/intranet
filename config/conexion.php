<?php 
session_start();
    class Conectar{
        protected $dbh;

        protected function Conexion(){


 //$host="localhost"; local 
 //$dbname="mpch"; local
  //$password="postgres"; loca
  //$username="postgres"; local

 //$host="10.10.10.16";
 //$dbname="db_simcix";
 //$username="postgres";
 //$password="Mpch*2023*";

 $host="10.10.10.9";
 $dbname="dbsimcix";
 $username="postgres";
 $password="D4t44Dm1n";

    try{
        $conectar=$this->dbh=new PDO("pgsql:host=$host; dbname=$dbname", $username,$password);
        return $conectar;
            }catch(Exception $e){
        print "No se Conecto A la base de Datos".$e->getMessage()."</br>";
            die();
        }
           
        }
        public function set_names() {
            return $this->dbh->query("SET NAMES 'utf8'");
        }

        public static function ruta(){
            return "http://10.10.10.13/Intranet/";
            //return "http://10.10.10.16/Intranet/"; pruebas
           // return "http://localhost/Intranet/"; local
             
        }
    }

?>