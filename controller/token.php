<?php
require_once("../config/conexion.php");
require_once("../models/Token.php");

$token = new Token();

switch ($_GET["op"]) {
    // Controlador para actualizar el token
case "update_token":
    $direct_id = $_POST['direct_id'];
    $token_actual = $_POST['token_actual'];
    $token_nuevo = $_POST['token_nuevo'];

    // Lógica para verificar la contraseña actual y actualizar el token
    $resultado = $token->updateToken($direct_id, $token_actual, $token_nuevo);
    echo json_encode($resultado);
    break;

}