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

$id_tipo_tutoria = isset($_POST['id_tipo_tutoria']) ? $_POST['id_tipo_tutoria'] : '';
$genero = isset($_POST['genero']) ? $_POST['genero'] : '';

if (empty($id_tipo_tutoria)) {
    echo json_encode(["error" => "El id_tipo_tutoria es requerido"]);
    $conn->close();
    exit();
}

if (empty($genero)) {
    echo json_encode(["error" => "El genero es requerido"]);
    $conn->close();
    exit();
}

$sql = "SELECT *
FROM tutores
WHERE id IN (
    SELECT id_tutor
    FROM grupo
    WHERE id_tipo_tutoria = ?
    AND id_grupo IN (
        SELECT g.id_grupo
        FROM grupo g
        JOIN tipo_tutoria t ON g.id_tipo_tutoria = t.id_tipo_tutoria
        LEFT JOIN estudiantes e ON g.id_grupo = e.id_grupo
        GROUP BY g.id_grupo, t.cupo_maximo
        HAVING COUNT(e.boleta) < t.cupo_maximo
    ) AND genero = ?
);
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id_tipo_tutoria, $genero);
$stmt->execute();
$result = $stmt->get_result();

$tutores = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tutores[] = $row;
    }
    echo json_encode($tutores);
} else {
    echo json_encode(["mensaje" => "No se encontraron tutores para el tipo de tutoría especificado"]);
}

$conn->close();
?>