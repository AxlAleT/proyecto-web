<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../admin.html");
    exit();
}
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar si se ha enviado una boleta
if (isset($_POST['boleta'])) {
    $boleta = $conn->real_escape_string($_POST['boleta']);

    $sql = "SELECT e.boleta, e.nombre AS nombre_estudiante, e.apellido_paterno, e.apellido_materno, e.telefono, e.semestre, e.carrera, e.correo, e.id_grupo, g.codigo_grupo, t.id AS id_tutor, t.nombre AS nombre_tutor, t.apellido_paterno AS apellido_paterno_tutor, t.apellido_materno AS apellido_materno_tutor, tt.id_tipo_tutoria, tt.nombre AS nombre_tipo_tutoria
            FROM estudiantes e
            JOIN grupo g ON e.id_grupo = g.id_grupo
            JOIN tutores t ON g.id_tutor = t.id
            JOIN tipo_tutoria tt ON g.id_tipo_tutoria = tt.id_tipo_tutoria
            WHERE e.boleta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $boleta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $alumno = [
            'boleta' => $row['boleta'],
            'nombre' => $row['nombre_estudiante'],
            'apellido_paterno' => $row['apellido_paterno'],
            'apellido_materno' => $row['apellido_materno'],
            'telefono' => $row['telefono'],
            'semestre' => $row['semestre'],
            'carrera' => $row['carrera'],
            'correo' => $row['correo'],
            'grupo' => [
                'id_grupo' => $row['id_grupo'],
                'codigo_grupo' => $row['codigo_grupo']
            ],
            'tutor' => [
                'id_tutor' => $row['id_tutor'],
                'nombre' => $row['nombre_tutor'],
                'apellido_paterno' => $row['apellido_paterno_tutor'],
                'apellido_materno' => $row['apellido_materno_tutor']
            ],
            'tipo_tutoria' => [
                'id_tipo_tutoria' => $row['id_tipo_tutoria'],
                'nombre' => $row['nombre_tipo_tutoria']
            ]
        ];
        echo json_encode(['success' => true, 'alumno' => $alumno]);
    } else {
        // No se encontró el alumno
        echo json_encode(['success' => false, 'message' => 'No se encontró el alumno con la boleta proporcionada.']);
    }
    $stmt->close();
} else {
    // No se proporcionó una boleta
    echo json_encode(['success' => false, 'message' => 'No se proporcionó una boleta.']);
}

$conn->close();
?>