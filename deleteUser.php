<?php
include "db.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM usuario WHERE id = $id";
    if (mysqli_query($conexion, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conexion)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID no recibido']);
}
?>
