<?php
include "db.php";
header('Content-Type: application/json');

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = (int) $_POST['id'];

    $stmt = mysqli_prepare($conexion, "DELETE FROM usuario WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conexion)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID no válido o no recibido']);
}
?>
