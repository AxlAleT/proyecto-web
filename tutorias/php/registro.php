<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Variables para almacenar errores
$errores = array();
$carrerasValidas = array("ISC", "LCD", "IIA");

// Obtener datos del formulario (POST) y sanitizarlos
$boleta = filter_var($_POST['boleta'], FILTER_SANITIZE_NUMBER_INT);
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
$apellidoPaterno = filter_var($_POST['AP'], FILTER_SANITIZE_STRING);
$apellidoMaterno = filter_var($_POST['AM'], FILTER_SANITIZE_STRING);
$telefono = filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT);
$semestre = filter_var($_POST['semestre'], FILTER_SANITIZE_NUMBER_INT);
$carrera = filter_var($_POST['carrera'], FILTER_SANITIZE_STRING);
$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$contrasena = $_POST['contrasena']; 
$idTutor = filter_var($_POST['tutor'], FILTER_SANITIZE_NUMBER_INT);
$idTipoTutoria = filter_var($_POST['tipo_tutoria'], FILTER_SANITIZE_NUMBER_INT);
    $fecha_registro = date("Y-m-d H:i:s");

// Validar datos
if (empty($boleta) || strlen($boleta) != 10) {
    $errores[] = "Número de boleta inválido (debe tener 10 dígitos).";
}
if (empty($nombre) || !preg_match("/^[a-zA-Z\s]+$/", $nombre)) {
    $errores[] = "Nombre inválido (solo letras y espacios).";
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Correo electrónico inválido.";
}
if (strlen($contrasena) < 8) {
    $errores[] = "La contraseña debe teneral menos 8 caracteres.";
}
if (empty($idTutor)) {
    $errores[] = "Debes seleccionar un tutor.";
}
if (empty($idTipoTutoria)) {
    $errores[] = "Debes seleccionar un tipo de tutoria.";
}
if (empty($apellidoPaterno) || !preg_match("/^[a-zA-Z\s]+$/", $apellidoPaterno)) {
    $errores[] = "Apellido Paterno inválido (solo letras y espacios).";
}
if (empty($apellidoMaterno) || !preg_match("/^[a-zA-Z\s]+$/", $apellidoMaterno)) {
    $errores[] = "Apellido Materno inválido (solo letras y espacios).";
}
if (empty($telefono) || strlen($telefono) != 10) {
    $errores[] = "Número de teléfono inválido (debe tener 10 dígitos).";
}
if ($semestre < 1 || $semestre > 10) {
    $errores[] = "Semestre inválido (debe estar entre 1 y 10).";
}
// Suponiendo que tienes un array $carrerasValidas con las carreras permitidas
if (!in_array($carrera, $carrerasValidas)) {
    $errores[] = "Carrera seleccionada inválida.";
}

// Si hay errores, mostrarlos y detener el script
if (!empty($errores)) {
    header("Location: ../registro.html?errorEnElFormulario=true");
    exit; // Detener el script
}

// Hashear la contraseña después de validarla
$contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

try{
    // Consulta SQL (usando sentencias preparadas para mayor seguridad)
    $stmt = $conn->prepare("INSERT INTO estudiantes (boleta, nombre, apellido_paterno, apellido_materno, telefono, semestre, carrera, correo, contrasena, id_tipo_tutoria) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssi", $boleta, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $semestre, $carrera, $correo, $contrasena, $idTipoTutoria);
    if ($stmt->execute()) {
        // Verifica si se ha insertado correctamente
        if ($stmt->affected_rows > 0) {
            // Consulta SQL para insertar datos en la tabla estudianteTutor
            $stmt = $conn->prepare("INSERT INTO estudianteTutor (id_estudiante, id_tutor) VALUES (?, ?)");
            $stmt->bind_param("ii", $boleta, $idTutor);

            if ($stmt->execute()) {
                echo "Registro exitoso. ¡Bienvenido!";
                $stmt->close();
                $conn->close();
                header("Location: mostrar_registro.php?error=" . urlencode($error_message));
                exit;
            } else {
                echo "Error al asignar el tutor: " . $stmt->error;
            }
        } else {
            echo "Error al registrar estudiante: " . $stmt->error;
        }
    } else {
        echo "Error al registrar: " . $stmt->error;
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) { 
        header("Location: ../registro.html?usuarioyaregistrado=true");
        exit;
    } else {
        header("Location: ../registro.html?errorDesconocido=true");
        exit;
    }
}

$stmt->close();
$conn->close();
?>