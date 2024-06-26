<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "escom_registro_tutorias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT e.boleta, e.nombre, e.apellido_paterno, e.apellido_materno, e.telefono, e.semestre, e.carrera, e.genero_tutor, e.correo, e.fecha_registro
FROM estudiantes e
ORDER BY e.fecha_registro DESC
LIMIT 1";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $boleta = $row['boleta'];
        $nombre = $row['nombre'];
        $apellido_paterno = $row['apellido_paterno'];
        $apellido_materno = $row['apellido_materno'];
        $telefono = $row['telefono'];
        $semestre = $row['semestre'];
        $carrera = $row['carrera'];
        $genero_tutor = $row['genero_tutor'];
        $correo = $row['correo'];
    } else {
        echo "No se encontró ningún registro.";
        exit();
    }
    $result->free();
} else {
    die("Error en la consulta SQL: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - ESCOM</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="menu container">
           <a href="https://www.escom.ipn.mx/" class="logo">ESCOM</a>
       
           <input type="checkbox" id="menu">
           <label for="menu">
               <img src="images/menu.png" class="menu-icono" alt="menu">
           </label>
       
           <nav class="navbar">
               <ul>
                   <li><a href="../html/index.html">Inicio</a></li>
                   <li><a href="../html/registro.html">Registro</a></li>
                   <li><a href="../html/admin.html">Ingreso</a></li>
               </ul>
           </nav>
        </div>
        <div class="header-content container">
            <h1>Registro de Tutorías</h1>
        </div>
    </header>
    
    <div class="container mt-4">
        <br><br>
        <h3>Registro Exitoso</h3>
        <table class="table table-bordered">
            <tr>
                <th>No. de Boleta</th>
                <td><?php echo htmlspecialchars($boleta); ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo htmlspecialchars($nombre); ?></td>
            </tr>
            <tr>
                <th>Apellido Paterno</th>
                <td><?php echo htmlspecialchars($apellido_paterno); ?></td>
            </tr>
            <tr>
                <th>Apellido Materno</th>
                <td><?php echo htmlspecialchars($apellido_materno); ?></td>
            </tr>
            <tr>
                <th>Telefono</th>
                <td><?php echo htmlspecialchars($telefono); ?></td>
            </tr>
            <tr>
                <th>Semestre</th>
                <td><?php echo htmlspecialchars($semestre); ?></td>
            </tr>
            <tr>
                <th>Carrera</th>
                <td><?php echo htmlspecialchars($carrera); ?></td>
            </tr>
            <tr>
                <th>Genero del Tutor</th>
                <td><?php echo htmlspecialchars($genero_tutor); ?></td>
            </tr>
            <tr>
                <th>Correo Electrónico</th>
                <td><?php echo htmlspecialchars($correo); ?></td>
            </tr>
        </table>
        <br><br>
    </div>

    <footer class="footer">
        <div class="footer-content container">
            <div class="link">
                <h3>Instituto Politécnico Nacional</h3>
                <ul>
                    <li>D.R. Instituto Politécnico Nacional (IPN). Av. Luis Enrique Erro S/N, Unidad Profesional Adolfo López Mateos, Zacatenco, Alcaldía Gustavo A. Madero, C.P. 07738, Ciudad de México. Conmutador: 55 57 29 60 00 / 55 57 29 63 00.
                        Esta página es una obra intelectual protegida por la Ley Federal del Derecho de Autor, puede ser reproducida con fines no lucrativos, siempre y cuando no se mutile, se cite la fuente completa y su dirección electrónica; su uso para otros fines, requiere autorización previa y por escrito de la Dirección General del Instituto.
                    </li>
                </ul>
                <img src="../images/educacion2.png" alt="educacion2.png">
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
