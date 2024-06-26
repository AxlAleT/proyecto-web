<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array("error" => "Error de conexión: " . $conn->connect_error)));
}

// Obtener el género del formulario (¡sanitizar en una aplicación real!)
$genero = $_POST['genero'];

// Consulta SQL preparada y segura con bind_param
$sql = "SELECT t.*
        FROM tutores t
        WHERE t.genero = ?
          AND t.id NOT IN (SELECT id_tutor FROM estudianteTutor)

        UNION 

        SELECT t.*
        FROM tutores t
        WHERE t.genero = ?
          AND t.id IN (
            SELECT te.id_tutor
            FROM estudianteTutor te
            GROUP BY te.id_tutor
            HAVING COUNT(te.id_estudiante) < 15
          )";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Verificar si la preparación fue exitosa
if (!$stmt) {
    die(json_encode(array("error" => "Error en la preparación de la consulta: " . $conn->error)));
}

// Enlazar el parámetro (repetido dos veces debido a la UNION)
$stmt->bind_param("ss", $genero, $genero);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();

// Verificar si la consulta tuvo resultados
if ($result->num_rows > 0) {
    $tutores = array();
    while ($row = $result->fetch_assoc()) {
        $tutores[] = $row;
    }
    echo json_encode($tutores);
} else {
    echo json_encode(array("mensaje" => "No se encontraron tutores"));
}

// Cerrar recursos
$stmt->close();
$conn->close();
?>
