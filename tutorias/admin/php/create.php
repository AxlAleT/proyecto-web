<?php
session_start();
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";



// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(array("error" => "Error de conexión: " . $conn->connect_error)));
}

// Obtener datos del formulario (POST) y sanitizarlos
$boleta = filter_var($_POST['boleta'], FILTER_SANITIZE_NUMBER_INT);
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
$apellidoPaterno = filter_var($_POST['AP'], FILTER_SANITIZE_STRING);
$apellidoMaterno = filter_var($_POST['AM'], FILTER_SANITIZE_STRING);
$telefono = filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT);
$semestre = filter_var($_POST['semestre'], FILTER_SANITIZE_NUMBER_INT);
$carrera = filter_var($_POST['carrera'], FILTER_SANITIZE_STRING);
$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$contrasena = $_POST['contrasena'];
$idTutor = filter_var($_POST['tutor'], FILTER_SANITIZE_NUMBER_INT);
$idTipoTutoria = filter_var($_POST['tipo_tutoria'], FILTER_SANITIZE_NUMBER_INT);

// Hashear la contraseña
$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

// Preparar la consulta SQL para insertar el nuevo alumno
$sql = "INSERT INTO estudiantes (boleta, nombre, apellido_paterno, apellido_materno, telefono, semestre, carrera, correo, contrasena, id_tipo_tutoria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(array("error" => "Error en la preparación de la consulta: " . $conn->error));
    exit();
}

$stmt->bind_param("issssisssi", $boleta, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $semestre, $carrera, $correo, $contrasena, $idTipoTutoria);

if ($stmt->execute()) {
    // Verificar si se insertó correctamente
    if ($stmt->affected_rows > 0) {
        // Insertar relación en estudianteTutor
        $sql = "INSERT INTO estudianteTutor (id_estudiante, id_tutor) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(array("error" => "Error al asignar tutor: " . $conn->error));
            exit();
        }
        $stmt->bind_param("ii", $boleta, $idTutor);
        if ($stmt->execute()) {
            echo json_encode(array("mensaje" => "Alumno creado exitosamente"));
        } else {
            echo json_encode(array("error" => "Error al asignar el tutor: " . $stmt->error));
        }
    } else {
        echo json_encode(array("error" => "Error al registrar estudiante: " . $stmt->error));
    }
} else {
    echo json_encode(array("error" => "Error al registrar: " . $stmt->error));
}

$stmt->close();
$conn->close();
?>