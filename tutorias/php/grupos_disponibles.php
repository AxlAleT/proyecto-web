<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$id_tutor = isset($_POST['id_tutor']) ? $_POST['id_tutor'] : '';
$id_tipo_tutoria = 2;

if (empty($id_tutor)) {
    echo json_encode(["error" => "El id_tutor es requerido"]);
    $conn->close();
    exit();
}

$sql = "SELECT g.id_grupo, g.codigo_grupo 
        FROM grupo g 
        WHERE g.id_tutor = ? AND g.id_tipo_tutoria = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_tutor, $id_tipo_tutoria);
$stmt->execute();
$result = $stmt->get_result();

$grupos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $grupos[] = $row;
    }
    echo json_encode($grupos);
} else {
    echo json_encode(["mensaje" => "No se encontraron grupos para el tutor y tipo de tutoría especificados"]);
}

$conn->close();
?>