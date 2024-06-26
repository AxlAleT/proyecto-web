<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../html/admin.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="menu container">
           <a href="https://www.escom.ipn.mx/" class="logo">ESCOM</a>
       
           <input type="checkbox" id="menu">
           <label for="menu">
               <img src ="../images/menu.png" class="menu-icono" alt="menu">
           </label>
       
           <nav class ="navbar">
               <ul>
                   <li><a href="../index.html">Inicio</a></li>
                   <li><a href="../html/registro.html">Registro</a></li>
                   <li><a href="../html/admin.html">Ingreso</a></li>
               </ul>
           </nav>
        </div>
        <div class="header-content container">
            <h1>Administradores</h1>
    </header>
    <div class="container mt-4">
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin']); ?></h1>
        <p>Has ingresado correctamente al sistema.</p>
        <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
    </div>
    <br><br><br><br><br><br>
    <footer class="footer">
        <div class="footer-content container">

            <div class="link">
                <h3>Instituto Politécnico Nacional</h3>
            <ul>
                <li>D.R. Instituto Politécnico Nacional (IPN). Av. Luis Enrique Erro S/N, Unidad Profesional Adolfo López Mateos, Zacatenco, Alcaldía Gustavo A. Madero, C.P. 07738, Ciudad de México. Conmutador: 55 57 29 60 00 / 55 57 29 63 00.


                    Esta página es una obra intelectual protegida por la Ley Federal del Derecho de Autor, puede ser reproducida con fines no lucrativos, siempre y cuando no se mutile, se cite la fuente completa y su dirección electrónica; su uso para otros fines, requiere autorización previa y por escrito de la Dirección General del Instituto.</li>
            </ul>
            <img src ="../images/educacion2.png" alt="educacion2.png">
            </div>


       
        </div>

    </footer>
</body>
</html>
