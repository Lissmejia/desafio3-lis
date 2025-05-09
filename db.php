<?php 
$host = "localhost";
$usuario = "root";
$contrasena = "";
$baseDatos = "desafio3-lis";

$conexion = mysqli_connect($host, $usuario, $contrasena, $baseDatos);

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
