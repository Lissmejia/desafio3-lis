<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include "db.php";

// Verifica que el método de solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

if (!isset($_POST['accion'])) {
    echo json_encode(['status' => 'error', 'message' => 'Acción no recibida']);
    exit;
}

switch ($_POST['accion']) {
    case 'saveForm':
        frmUsuario();
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}


// Ejecuta una función según el valor del parámetro 'accion'
function frmUsuario()
{
    global $conexion;

    if (empty($_POST['nombreCompleto']) || empty($_POST['correo']) || empty($_POST['contrasena']) || empty($_POST['fechaNacimiento'])) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        return;
    }

    $nombreCompleto = mysqli_real_escape_string($conexion, $_POST['nombreCompleto']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $fechaNacimiento = mysqli_real_escape_string($conexion, $_POST['fechaNacimiento']);

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Correo electrónico no válido']);
        return;
    }

    if (strlen($contrasena) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        return;
    }

    // Validar fecha futura y edad
    $fechaNacimientoTimestamp = strtotime($fechaNacimiento);
    $hoy = strtotime(date('Y-m-d'));

    if ($fechaNacimientoTimestamp > $hoy) {
        echo json_encode(['status' => 'error', 'message' => 'La fecha de nacimiento no puede ser en el futuro']);
        return;
    }

    $edad = date('Y') - date('Y', $fechaNacimientoTimestamp);
    if (date('md', $hoy) < date('md', $fechaNacimientoTimestamp)) {
        $edad--;
    }

    if ($edad < 12) {
        echo json_encode(['status' => 'error', 'message' => 'Debes tener al menos 12 años']);
        return;
    }

    // Verificar si el correo ya existe
    $existeQuery = "SELECT id FROM usuario WHERE correo = '$correo' LIMIT 1";
    $resultado = mysqli_query($conexion, $existeQuery);
    if (mysqli_num_rows($resultado) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Ya existe un usuario con ese correo']);
        return;
    }

    // Hashear contraseña y guardar
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);
    $query = "INSERT INTO usuario (nombreCompleto, correo, contrasena, fechaNacimiento)
              VALUES ('$nombreCompleto', '$correo', '$hash', '$fechaNacimiento')";

    if (mysqli_query($conexion, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario guardado']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conexion)]);
    }
}
