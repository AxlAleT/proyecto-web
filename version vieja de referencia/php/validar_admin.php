<?php
session_start();

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "escom_registro_tutorias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar si los datos del formulario están configurados
if (isset($_POST['adminUsuario']) && isset($_POST['adminContrasena'])) {
    $adminUsuario = $_POST['adminUsuario'];
    $adminContrasena = $_POST['adminContrasena'];

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare('SELECT id, password_hash FROM administradores WHERE username = ?');
    if ($stmt) {
        $stmt->bind_param('s', $adminUsuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $passwordHash);
            $stmt->fetch();

            // Verificar la contraseña
            if (password_verify($adminContrasena, $passwordHash)) {
                $_SESSION['admin'] = $adminUsuario;
                header("Location: bienvenida.php");
                exit();
            } else {
                header("Location: ../html/admin.html?error=invalid_credentials");
                exit();
            }
        } else {
            // El usuario no existe
            header("Location: ../html/admin.html?error=user_not_found");
            exit();
        }

        $stmt->close();
    } else {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
} else {
    header("Location: ../html/admin.html?error=missing_credentials");
    exit();
}

$conn->close();
?>
