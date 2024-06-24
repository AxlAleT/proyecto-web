<?php
session_start();
header('Content-Type: application/json');

// Verificar si la sesión de admin está activa
if (!isset($_SESSION['admin'])) {
    echo json_encode(array("error" => "Acceso denegado"));
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(array("error" => "Error de conexión: " . $conn->connect_error)));
}

// Obtener el número de página desde POST y calcular el offset
$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$alumnosPorPagina = 50;
$offset = ($pagina - 1) * $alumnosPorPagina;

// Preparar la consulta SQL para obtener los alumnos con paginación
$sql = "SELECT * FROM estudiantes ORDER BY boleta ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(array("error" => "Error en la preparación de la consulta: " . $conn->error)));
}

// Enlazar los parámetros y ejecutar
$stmt->bind_param("ii", $alumnosPorPagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si hay resultados
if ($result->num_rows > 0) {
    $alumnos = array();
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = $row;
    }
    echo json_encode($alumnos);
} else {
    echo json_encode(array("mensaje" => "No se encontraron alumnos"));
}

// Cerrar recursos
$stmt->close();
$conn->close();
?>