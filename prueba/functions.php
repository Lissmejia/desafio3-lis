<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'saveForm()';
        break;
    }
}

function saveForm() 
{
    global $conexion;
    extract($_POST);
    include "db.php";

    $consulta = "INSERT INTO usuario(id, nombreCompleto, correo, conntraseña , fechaNacimiento) VALUES ('$id',
    '$nombreCompleto', '$correo', '$contraseña', '$fechaNacimiento')";

    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        $response = array (
            'status' => 'success',
            'message'=> 'Guardado'
        );
    } else {
        $response = array (
            'status' => 'error',
            'message' => 'Ocurrio un error'
        );
    }

    echo json_encode($response);
}