<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin'])) {
    echo json_encode(array("error" => "Acceso denegado"));
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array("error" => "Error de conexión: " . $conn->connect_error)));
}

$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$alumnosPorPagina = 50;
$offset = ($pagina - 1) * $alumnosPorPagina;

$sql = "SELECT * FROM estudiantes ORDER BY boleta ASC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(array("error" => "Error en la preparación de la consulta: " . $conn->error)));
}

$stmt->bind_param("ii", $alumnosPorPagina, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $alumnos = array();
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = $row;
    }
} else {
    echo json_encode(array("mensaje" => "No se encontraron alumnos"));
    $stmt->close();
    $conn->close();
    exit();
}

foreach ($alumnos as &$alumno) {
    $id_tipo_tutoria = $alumno['id_tipo_tutoria'];
    $sql = "SELECT * FROM tipo_tutoria WHERE id_tipo_tutoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_tipo_tutoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $tipo_tutoria = $result->fetch_assoc();
    $alumno['tipo_tutoria'] = $tipo_tutoria;
}

foreach ($alumnos as &$alumno) {
    $id_alumno = $alumno['boleta'];
    $sql = "SELECT t.nombre, t.apellido_paterno, t.apellido_materno
            FROM tutores t
            JOIN estudianteTutor et ON t.id = et.id_tutor
            WHERE et.id_estudiante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_alumno);
    $stmt->execute();
    $result = $stmt->get_result();
    $tutor = $result->fetch_assoc();
    $nombre_tutor = $tutor['nombre'] . " " . $tutor['apellido_paterno'] . " " . $tutor['apellido_materno'];
    $alumno['nombre_tutor'] = $nombre_tutor;
}

echo json_encode($alumnos);

$stmt->close();
$conn->close();
?>