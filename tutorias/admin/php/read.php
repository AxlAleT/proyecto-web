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

if (!isset($_POST['busquedaBoleta'])) {
    die(json_encode(["error" => "Falta el campo busquedaBoleta"]));
}

$boleta = filter_var($_POST['busquedaBoleta'], FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT e.boleta, e.nombre, e.apellido_paterno, e.apellido_materno, e.telefono, e.semestre, e.carrera, e.correo, tt.nombre AS tipo_tutoria, t.nombre AS nombre_tutor, t.apellido_paterno AS apellido_paterno_tutor, t.apellido_materno AS apellido_materno_tutor
        FROM estudiantes e
        LEFT JOIN tipoTutoria tt ON e.id_tipo_tutoria = tt.id_tipo_tutoria
        LEFT JOIN estudianteTutor et ON e.boleta = et.id_estudiante
        LEFT JOIN tutores t ON et.id_tutor = t.id
        WHERE e.boleta = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
    exit();
}

$stmt->bind_param("i", $boleta);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "No se encontró al estudiante con la boleta especificada"]);
    }
} else {
    echo json_encode(["error" => "Error al ejecutar la consulta: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>