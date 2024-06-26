<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "escom_registro_tutorias";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Función para sanitizar entradas
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Obtener y sanitizar datos del formulario
    $boleta = sanitize_input($_POST['boleta']);
    $nombre = sanitize_input($_POST['nombre']);
    $apellido_paterno = sanitize_input($_POST['AP']);
    $apellido_materno = sanitize_input($_POST['AM']);
    $telefono = sanitize_input($_POST['tel']);
    $semestre = sanitize_input($_POST['semestre']);
    $carrera = sanitize_input($_POST['carrera']);
    $tutor = sanitize_input($_POST['tutor']);
    $correo = sanitize_input($_POST['correo']);
    $fecha_registro = date("Y-m-d H:i:s");

    // Validar que los datos no estén vacíos y que cumplan con las expectativas
    if (empty($boleta) || empty($nombre) || empty($apellido_paterno) || empty($apellido_materno) || empty($telefono) || empty($semestre) || empty($carrera) || empty($tutor) || empty($correo)) {
        die("Todos los campos son obligatorios.");
    }

    // Comprobar si el semestre es válido
    $valid_semestres = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10');
    if (!in_array($semestre, $valid_semestres)) {
        die("Semestre inválido.");
    }

    // Comprobar si la carrera tiene el formato adecuado (ajustar según tus necesidades)
    if (!preg_match("/^[A-Z]{3}$/", $carrera)) {
        die("Carrera inválida.");
    }

    // Comprobar si el correo es válido
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico inválido.");
    }

    // Insertar datos en la base de datos
    $sql = $conn->prepare("INSERT INTO estudiantes (boleta, nombre, apellido_paterno, apellido_materno, telefono, semestre, carrera, genero_tutor, correo, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($sql === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $sql->bind_param("ssssssssss", $boleta, $nombre, $apellido_paterno, $apellido_materno, $telefono, $semestre, $carrera, $tutor, $correo, $fecha_registro);

    if ($sql->execute()) {
        echo "Registro exitoso";
        $sql->close();
        $conn->close();
        header("Location: mostrar_registro.php");
        exit();
    } else {
        if ($conn->errno == 1062) {
            $error_message = "La boleta ya está registrada.";
            header("Location: ../html/registro.html?error=" . urlencode($error_message));
        } else {
            echo "Error: " . $sql->error;
        }
    }

    $sql->close();
    $conn->close();
}
?>
