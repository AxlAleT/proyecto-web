<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";
$conn = new mysqli($servername, $username, $password, $dbname);

if (!isset($_SESSION['admin'])) {
    header("Location: ../../admin.html");
    exit();
}

if ($conn->connect_error) {
    die(json_encode(array("error" => "Error de conexión: " . $conn->connect_error)));
}

if (!isset($_POST['boleta'])) {
    echo json_encode(array("error" => "No se proporcionó la boleta del alumno"));
    exit();
}

$boleta = $_POST['boleta'];

// Iniciar transacción
$conn->begin_transaction();

try {
    // Eliminar relaciones en estudianteTutor
    $sql = "DELETE FROM estudianteTutor WHERE id_estudiante = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $boleta);
    $stmt->execute();
    $stmt->close();

    // Eliminar alumno
    $sql = "DELETE FROM estudiantes WHERE boleta = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("i", $boleta);
    $stmt->execute();
    $stmt->close();

    // Si todo fue exitoso, commit
    $conn->commit();
    echo json_encode(array("mensaje" => "Alumno eliminado exitosamente"));
} catch (Exception $e) {
    // En caso de error, rollback
    $conn->rollback();
    echo json_encode(array("error" => $e->getMessage()));
}

$conn->close();
?>