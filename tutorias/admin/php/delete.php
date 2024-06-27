<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../admin.html");
    exit();
}

// Verificar si se recibió la boleta por POST
if (!isset($_POST['boleta'])) {
    echo json_encode(['error' => 'No se proporcionó la boleta del alumno.']);
    exit();
}

$boleta = $_POST['boleta'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar la consulta para eliminar al alumno
$sql = "DELETE FROM estudiantes WHERE boleta = ?";

// Preparar sentencia
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar la consulta: ' . $conn->error]);
    $conn->close();
    exit();
}

// Vincular parámetros
$stmt->bind_param("i", $boleta);

// Ejecutar sentencia
if ($stmt->execute()) {
    echo json_encode(['success' => 'Alumno eliminado correctamente.']);
} else {
    echo json_encode(['error' => 'Error al eliminar al alumno: ' . $stmt->error]);
}

// Cerrar sentencia
$stmt->close();

// Cerrar conexión
$conn->close();
?>