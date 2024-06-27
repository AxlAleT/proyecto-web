<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../../admin.html");
    exit();
}

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

$expected_fields = ['boleta', 'nombre', 'AP', 'AM', 'tel', 'semestre', 'carrera', 'correo', 'contrasena'];
foreach ($expected_fields as $field) {
    if (!isset($_POST[$field])) {
        die(json_encode(["error" => "Falta el campo $field"]));
    }
}

$boleta = filter_var($_POST['boleta'], FILTER_SANITIZE_NUMBER_INT);
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$apellidoPaterno = filter_var($_POST['AP'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$apellidoMaterno = filter_var($_POST['AM'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$telefono = filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT);
$semestre = filter_var($_POST['semestre'], FILTER_SANITIZE_NUMBER_INT);
$carrera = filter_var($_POST['carrera'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$contrasena = filter_var($_POST['contrasena'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

$idGrupo = isset($_POST['grupo']) ? filter_var($_POST['grupo'], FILTER_SANITIZE_NUMBER_INT) : null;

if ($idGrupo === -1 || $idGrupo === null || $idGrupo === "" || !isset($_POST['grupo'])) {
    // Buscar un grupo con disponibilidad
    $sqlBuscarGrupo = "SELECT g.id_grupo FROM grupo g JOIN tipo_tutoria tt ON g.id_tipo_tutoria = tt.id_tipo_tutoria WHERE g.id_tipo_tutoria = ? AND g.id_tutor = ? AND (SELECT COUNT(e.id_grupo) FROM estudiantes e WHERE e.id_grupo = g.id_grupo) < tt.cupo_maximo LIMIT 1";

    $idTipoTutoria = filter_var($_POST['id_tipo_tutoria'], FILTER_SANITIZE_NUMBER_INT);
    $idTutor = filter_var($_POST['id_tutor'], FILTER_SANITIZE_NUMBER_INT);

    // Preparar la consulta
    $stmtBuscarGrupo = $conn->prepare($sqlBuscarGrupo);
    if (!$stmtBuscarGrupo) {
        echo json_encode(["error" => "Error preparando consulta de búsqueda de grupo: " . $conn->error]);
        exit();
    }

    $stmtBuscarGrupo->bind_param("ii", $idTipoTutoria, $idTutor);

    // Ejecutar la consulta
    $stmtBuscarGrupo->execute();
    $resultadoGrupo = $stmtBuscarGrupo->get_result();

    if ($resultadoGrupo->num_rows > 0) {
        $filaGrupo = $resultadoGrupo->fetch_assoc();
        $idGrupo = $filaGrupo['id_grupo'];
    } else {
        die(json_encode(["error" => "No hay grupos disponibles"]));
    }
}

    if($_POST['contrasena'] != ""){
    
        $sqlDisponibilidad = "SELECT g.id_grupo, tt.cupo_maximo, COUNT(e.id_grupo) as conteo_actual FROM grupo g INNER JOIN tipo_tutoria tt ON g.id_tipo_tutoria = tt.id_tipo_tutoria LEFT JOIN estudiantes e ON g.id_grupo = e.id_grupo WHERE g.id_grupo = ? GROUP BY g.id_grupo, tt.cupo_maximo";

        $stmtDisponibilidad = $conn->prepare($sqlDisponibilidad);
        if (!$stmtDisponibilidad) {
            echo json_encode(["error" => "Error preparando consulta de disponibilidad: " . $conn->error]);
            exit();
        }

        $stmtDisponibilidad->bind_param("i", $idGrupo);
        $stmtDisponibilidad->execute();
        $resultadoDisponibilidad = $stmtDisponibilidad->get_result();
        $fila = $resultadoDisponibilidad->fetch_assoc();

        if ($fila['conteo_actual'] < $fila['cupo_maximo']) {

            $sql = "UPDATE estudiantes
        SET nombre = ?,
            apellido_paterno = ?,
            apellido_materno = ?,
            telefono = ?,
            semestre = ?,
            carrera = ?,
            correo = ?,
            contrasena = ?,
            id_grupo = ?,
            fecha_registro = ?
        WHERE boleta = ?;

            ";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit();
            }
            $fechaActual = date("Y-m-d H:i:s");

            $stmt->bind_param("sssiisssisi", $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $semestre, $carrera, $correo, $contrasena, $idGrupo, $fechaActual, $boleta);

            if ($stmt->execute()) {
                echo json_encode(["mensaje" => "Alumno_actualizado_exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al registrar: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            // Enviar mensaje de error
            echo json_encode(["error" => "No hay cupo disponible en el grupo seleccionado"]);
        }
    } else {
        $sqlDisponibilidad = "SELECT g.id_grupo, tt.cupo_maximo, COUNT(e.id_grupo) as conteo_actual FROM grupo g INNER JOIN tipo_tutoria tt ON g.id_tipo_tutoria = tt.id_tipo_tutoria LEFT JOIN estudiantes e ON g.id_grupo = e.id_grupo WHERE g.id_grupo = ? GROUP BY g.id_grupo, tt.cupo_maximo";

        $stmtDisponibilidad = $conn->prepare($sqlDisponibilidad);
        if (!$stmtDisponibilidad) {
            echo json_encode(["error" => "Error preparando consulta de disponibilidad: " . $conn->error]);
            exit();
        }

        $stmtDisponibilidad->bind_param("i", $idGrupo);
        $stmtDisponibilidad->execute();
        $resultadoDisponibilidad = $stmtDisponibilidad->get_result();
        $fila = $resultadoDisponibilidad->fetch_assoc();

        if ($fila['conteo_actual'] < $fila['cupo_maximo']) {

            $sql = "UPDATE estudiantes
        SET nombre = ?,
            apellido_paterno = ?,
            apellido_materno = ?,
            telefono = ?,
            semestre = ?,
            carrera = ?,
            correo = ?,
            id_grupo = ?,
            fecha_registro = ?
        WHERE boleta = ?;

            ";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]);
                exit();
            }
            $fechaActual = date("Y-m-d H:i:s");

            $stmt->bind_param("sssiissisi", $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $semestre, $carrera, $correo, $idGrupo, $fechaActual, $boleta);

            if ($stmt->execute()) {
                echo json_encode(["mensaje" => "Alumno_actualizado_exitosamente"]);
            } else {
                echo json_encode(["error" => "Error al registrar: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            // Enviar mensaje de error
            echo json_encode(["error" => "No hay cupo disponible en el grupo seleccionado"]);
        }
    }

$conn->close();
?>