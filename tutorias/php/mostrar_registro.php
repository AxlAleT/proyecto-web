<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutorias";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el registro más reciente
$sql = "SELECT e.boleta, e.nombre, e.apellido_paterno, e.apellido_materno, e.telefono, e.semestre, e.carrera, t.genero AS genero_tutor, e.correo, e.fecha_registro
FROM estudiantes e
LEFT JOIN estudianteTutor et ON e.boleta = et.id_estudiante
LEFT JOIN tutores t ON et.id_tutor = t.id
ORDER BY e.fecha_registro DESC
LIMIT 1";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        // Mostrar los datos del registro más reciente
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
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Tutorias-ESCOM</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="https://www.escom.ipn.mx/">ESCOM</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                        <li class="nav-item"><a class="nav-link" href="../index.html">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="../registro.html">Registro</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin.html">Administrador</a></li>
                        <li class="nav-item"><a class="nav-link" href="pdf.php">Recuperar PDF</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container">
                <div class="masthead-subheading">ESCOM</div>
                <div class="masthead-heading text-uppercase">Registro</div>
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
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>
</html>
