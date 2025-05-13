<?php
include "db.php";

header('Content-Type: application/json');

$query = "SELECT id, nombreCompleto, correo, fechaNacimiento FROM usuario";
$result = mysqli_query($conexion, $query);

$usuarios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
?>
