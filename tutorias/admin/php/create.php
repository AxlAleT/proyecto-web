<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$expected_fields = ['boleta', 'nombre', 'AP', 'AM', 'tel', 'semestre', 'carrera', 'correo', 'contrasena', 'tipo_tutoria', 'tutor'];
foreach ($expected_fields as $field) {
    if (!isset($_POST[$field])) {
        die(json_encode(["error" => "Falta el campo $field"]));
    }
}

$boleta = filter_var($_POST['boleta'], FILTER_SANITIZE_NUMBER_INT);
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$apellidoPaterno = filter_var($_POST['AP'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$apellidoMaterno = filter_var($_POST['AM'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$telefono = filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT);
$semestre = filter_var($_POST['semestre'], FILTER_SANITIZE_NUMBER_INT);
$carrera = filter_var($_POST['carrera'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$contrasena = filter_var($_POST['contrasena'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idTipoTutoria = filter_var($_POST['tipo_tutoria'], FILTER_SANITIZE_NUMBER_INT);
$idTutor = filter_var($_POST['tutor'], FILTER_SANITIZE_NUMBER_INT);

$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

$sql = "INSERT INTO estudiantes (boleta, nombre, apellido_paterno, apellido_materno, telefono, semestre, carrera, correo, contrasena, id_tipo_tutoria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
    exit();
}

$stmt->bind_param("issssisssi", $boleta, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $semestre, $carrera, $correo, $contrasena, $idTipoTutoria);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $sql = "INSERT INTO estudianteTutor (id_estudiante, id_tutor) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(["error" => "Error al asignar tutor: " . $conn->error]);
            exit();
        }
        $stmt->bind_param("ii", $boleta, $idTutor);
        if ($stmt->execute()) {
            echo json_encode(["mensaje" => "Alumno creado exitosamente"]);
        } else {
            echo json_encode(["error" => "Error al asignar el tutor: " . $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "Error al registrar estudiante: " . $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Error al registrar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>