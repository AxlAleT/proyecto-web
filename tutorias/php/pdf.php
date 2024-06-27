<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('fpdf/fpdf.php'); 

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

if (isset($_POST['boleta']) && isset($_POST['contrasena'])) {
    $boleta = $_POST['boleta'];
    $contrasena = $_POST['contrasena'];
    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare('SELECT boleta, contrasena FROM estudiantes WHERE boleta = ?');
    if ($stmt) {
        $stmt->bind_param('s', $boleta);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($boletaEstudiante, $contrasenaHash);
            $stmt->fetch();
            // Verificar la contraseña
            if (password_verify($contrasena, $contrasenaHash)) {
                
                $boleta = $_POST['boleta'];

                $sql = "
                SELECT 
                    e.boleta, 
                    e.nombre, 
                    e.apellido_paterno, 
                    e.apellido_materno, 
                    e.telefono, 
                    e.semestre, 
                    e.carrera, 
                    e.correo, 
                    e.fecha_registro,
                    tt.nombre AS nombre_tipo_tutoria,
                    CONCAT(t.nombre, ' ', t.apellido_paterno, ' ', t.apellido_materno) AS nombre_tutor
                FROM 
                    (SELECT * FROM estudiantes WHERE boleta = ?) e
                LEFT JOIN 
                    grupo g ON e.id_grupo = g.id_grupo
                LEFT JOIN 
                    tutores t ON g.id_tutor = t.id
                LEFT JOIN 
                    tipo_tutoria tt ON g.id_tipo_tutoria = tt.id_tipo_tutoria
                ORDER BY 
                    e.fecha_registro DESC
                LIMIT 1;";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $boleta);
                $stmt->execute();
                $result = $stmt->get_result();


                if ($result) {
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $boleta = $row['boleta'];
                        $nombre = $row['nombre'];
                        $apellido_paterno = $row['apellido_paterno'];
                        $apellido_materno = $row['apellido_materno'];
                        $telefono = $row['telefono'];
                        $semestre = $row['semestre'];
                        $carrera = $row['carrera'];
                        $correo = $row['correo'];
                        $idTutor = $row['nombre_tutor']; 
                        $idTipoTutoria = $row['nombre_tipo_tutoria']; 
                        $fecha_registro = $row['fecha_registro']; 
                    } else {
                        echo "No se encontró ningún registro.";
                        exit();
                    }
                    $result->free();
                } else {
                    die("Error en la consulta SQL: " . $conn->error);
                }

                // Crear el PDF con FPDF
                $pdf = new FPDF();
                $pdf->AddPage();

                $imagePath = '../assets/img/IPN-Logo.png';
                $pdf->Image($imagePath, 10, 10, 60, 30); // (ruta, x, y, ancho, alto)

                $imageRightPath = '../assets/img/logoescom.png'; // Ruta relativa a la imagen derecha
                $imageWidth = 40; // Ancho de la imagen
                $pageWidth = $pdf->GetPageWidth();
                $rightImageX = $pageWidth - $imageWidth - 10; 
                $pdf->Image($imageRightPath, $rightImageX, 10, $imageWidth, 30); // (ruta, x, y, ancho, alto)

                // Configurar fuente
                $pdf->SetFont('Arial', 'B', 16);

                // Mover cursor abajo de la imagen
                $pdf->SetY(50);

                // Título
                $pdf->Cell(0, 10, 'REGISTRO EXITOSO', 0, 1, 'C');

                // Configurar fuente para el contenido
                $pdf->SetFont('Arial', '', 12);

                // Imprimir los datos
                $pdf->Cell(0, 10, 'Boleta: ' . $boleta, 0, 1);
                $pdf->Cell(0, 10, 'Nombre: ' . $nombre, 0, 1);
                $pdf->Cell(0, 10, 'Apellido Paterno: ' . $apellido_paterno, 0, 1);
                $pdf->Cell(0, 10, 'Apellido Materno: ' . $apellido_materno, 0, 1);
                $pdf->Cell(0, 10, 'Telefono: ' . $telefono, 0, 1);
                $pdf->Cell(0, 10, 'Semestre: ' . $semestre, 0, 1);
                $pdf->Cell(0, 10, 'Carrera: ' . $carrera, 0, 1);
                $pdf->Cell(0, 10, 'Tutor: ' . $idTutor, 0, 1);
                $pdf->Cell(0, 10, 'Tipo tutoria: ' . $idTipoTutoria, 0, 1);
                $pdf->Cell(0, 10, 'Correo: ' . $correo, 0, 1);
                $pdf->Cell(0, 10, 'Fecha Registro: ' . $fecha_registro, 0, 1);

                // Salida del PDF en el navegador
                $pdf->Output('I', 'registro_mas_reciente.pdf');

            } else {
                header("Location: ../pdf.html?error=invalid_credentials");
                exit();
            }
        } else {
            // El estudiante no existe
            header("Location: ../pdf.html?error=user_not_found");
            exit();
        }
        $stmt->close();
    } else {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
} else {
    header("Location: ../pdf.html?error=missing_credentials");
    exit();
}
$conn->close();
?>