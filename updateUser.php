<?php
include "db.php";
header('Content-Type: application/json');

if (
    isset($_POST['usuarioId']) && is_numeric($_POST['usuarioId']) &&
    !empty($_POST['nombreCompleto']) &&
    !empty($_POST['correo']) &&
    !empty($_POST['fechaNacimiento'])
) {
    $id = (int)$_POST['usuarioId'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombreCompleto']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $fechaNacimiento = mysqli_real_escape_string($conexion, $_POST['fechaNacimiento']);

    // Validaciones
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Correo inválido']);
        exit;
    }

    // Verificar si el correo ya existe en otro usuario
    $check = "SELECT id FROM usuario WHERE correo = '$correo' AND id != $id";
    $result = mysqli_query($conexion, $check);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Correo ya está en uso por otro usuario']);
        exit;
    }

    // Actualizar
    $query = "UPDATE usuario SET nombreCompleto = '$nombre', correo = '$correo', fechaNacimiento = '$fechaNacimiento' WHERE id = $id";
    if (mysqli_query($conexion, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conexion)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o ID inválido']);
}
