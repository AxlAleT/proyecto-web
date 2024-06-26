<?php
// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $adminUsuario = $_POST["adminUsuario"];
    $adminEmail = $_POST["adminEmail"];
    $adminContrasena = $_POST["adminContrasena"];

    // Hashear la contraseña antes de almacenarla en la base de datos
    $passwordHash = password_hash($adminContrasena, PASSWORD_DEFAULT);

    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "escom_registro_tutorias";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Preparar la consulta SQL para insertar el nuevo administrador
    $stmt = $conn->prepare("INSERT INTO administradores (username, password_hash, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $adminUsuario, $passwordHash, $adminEmail);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "El administrador se registró correctamente.";
    } else {
        echo "Error al registrar el administrador: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
}
?>
