<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "escom_registro_tutorias";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Variables para almacenar errores
$errores = array();

// Obtener datos del formulario (POST) y sanitizarlos
$boleta = filter_var($_POST['boleta'], FILTER_SANITIZE_NUMBER_INT);
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
$apellidoPaterno = filter_var($_POST['AP'], FILTER_SANITIZE_STRING);
$apellidoMaterno = filter_var($_POST['AM'], FILTER_SANITIZE_STRING);
$telefono = filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT);
$semestre = filter_var($_POST['semestre'], FILTER_SANITIZE_NUMBER_INT);
$carrera = filter_var($_POST['carrera'], FILTER_SANITIZE_STRING);
$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$contrasena = $_POST['contrasena']; // No sanitizamos la contraseña antes de hashearla
$idTutor = filter_var($_POST['tutor'], FILTER_SANITIZE_NUMBER_INT);

// Validar datos
if (empty($boleta) || strlen($boleta) != 10) {
    $errores[] = "Número de boleta inválido (debe tener 10 dígitos).";
}
if (empty($nombre) || !preg_match("/^[a-zA-Z\s]+$/", $nombre)) {
    $errores[] = "Nombre inválido (solo letras y espacios).";
}
// ... (validaciones similares para otros campos)
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Correo electrónico inválido.";
}
if (strlen($contrasena) < 8) {
    $errores[] = "La contraseña debe teneral menos 8 caracteres.";
}
if (empty($idTutor)) {
    $errores[] = "Debes seleccionar un tutor.";
}

// Si hay errores, mostrarlos y detener el script
if (!empty($errores)) {
    echo "Errores en el formulario:<br>";
    foreach ($errores as $error) {
        echo "- $error<br>";
    }
    exit; // Detener el script
}

// Hashear la contraseña después de validarla
$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

// Consulta SQL (usando sentencias preparadas para mayor seguridad)
$stmt = $conn->prepare("INSERT INTO estudiantes (boleta, nombre, apellido_paterno, apellido_materno, telefono, semestre, carrera, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssisss", $boleta, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $semestre, $carrera, $correo, $contrasena);

if ($stmt->execute()) {
    $idEstudiante = $stmt->insert_id;

    // Consulta SQL para insertar datos en la tabla estudianteTutor
    $stmt = $conn->prepare("INSERT INTO estudianteTutor (id_estudiante, id_tutor) VALUES (?, ?)");
    $stmt->bind_param("ii", $idEstudiante, $idTutor);

    if ($stmt->execute()) {
        echo "Registro exitoso. ¡Bienvenido!";
    } else {
        echo "Error al asignar el tutor: " . $stmt->error;
    }
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
