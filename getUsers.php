<?php
include "db.php";

$query = "SELECT * FROM usuario";
$result = mysqli_query($conexion, $query);

$usuarios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
?>
