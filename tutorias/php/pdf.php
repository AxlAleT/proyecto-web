<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('fpdf/fpdf.php'); // Asegúrate de que la ruta sea correcta

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
    e.id_tipo_tutoria, 
    e.fecha_registro,
    et.id_tutor
FROM 
    estudiantes e
LEFT JOIN 
    (SELECT id_estudiante, id_tutor 
     FROM estudianteTutor 
     ORDER BY id_estudiante DESC 
     LIMIT 1) et
ON 
    e.boleta = et.id_estudiante
ORDER BY 
    e.fecha_registro DESC
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
        $correo = $row['correo'];
        $idTutor = $row['id_tutor']; 
        $idTipoTutoria = $row['id_tipo_tutoria']; 
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

// Agregar una imagen en la parte superior izquierda
// Asegúrate de que la ruta de la imagen sea correcta
$imagePath = '..\assets\img\IPN-Logo.png';
$pdf->Image($imagePath, 10, 10, 60, 30); // (ruta, x, y, ancho, alto)

$imageRightPath = '..\assets\img\logoescom.png'; // Ruta relativa a la imagen derecha
$imageWidth = 40; // Ancho de la imagen
$pageWidth = $pdf->GetPageWidth();
$rightImageX = $pageWidth - $imageWidth - 10; // Calcula la posición x para la imagen de la derecha
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

$conn->close();
?>
