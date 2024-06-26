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

$expected_fields = ['boleta', 'nombre', 'AP', 'AM', 'tel', 'semestre', 'carrera', 'correo', 'contrasena', 'tipo_tutoria'];
foreach ($expected_fields as $field) {
    if (!isset($_POST[$field])) {
        die(json_encode(["error" => "Falta el campo $field"]));
    }
}

// Sanitizar y asignar variables aquí...

$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

$sql = "UPDATE estudiantes SET nombre=?, apellido_paterno=?, apellido_materno=?, telefono=?, semestre=?, carrera=?, correo=?, contrasena=?, id_tipo_tutoria=? WHERE boleta=?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
    exit();
}

$stmt->bind_param("ssssisssii", $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $semestre, $carrera, $correo, $contrasena, $idTipoTutoria, $boleta);

if ($stmt->execute()) {
    echo json_encode(["mensaje" => "Alumno actualizado exitosamente"]);
} else {
    echo json_encode(["error" => "Error al actualizar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>