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
        <script>
    function habilitarCampos() {
        document.getElementById("boleta").disabled = false;
        document.getElementById("nombre").disabled = false;
        document.getElementById("AP").disabled = false;
        document.getElementById("AM").disabled = false;
        document.getElementById("tel").disabled = false;
        document.getElementById("semestre").disabled = false;
        document.getElementById("carrera").disabled = false;
        document.getElementById("genero_tutor").disabled = false;
        document.getElementById("tipo_tutoria").disabled = false;
        document.getElementById("tutor").disabled = false;
        document.getElementById("correo").disabled = false;
        document.getElementById("contrasena").disabled = false;
    }
</script>
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
                        <li class="nav-item"><a class="nav-link" href="#Administrador">Administrador</a></li>
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

        <form id="confirmarForm" action="registro.php" method="POST">
            <h3>Confirmación de Registro</h3>
            <p>Formulario enviado con éxito</p>
            <strong>No. de Boleta:</strong><input type="text" class="form-control" id="boleta" name="boleta" value="<?php echo htmlspecialchars($_POST['boleta']); ?>" disabled>
            <strong>Nombre:</strong><input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre']); ?>" disabled>
            <strong>Apellido paterno:</strong><input type="text" class="form-control" id="AP" name="AP" value="<?php echo htmlspecialchars($_POST['AP']); ?>" disabled>
            <strong>Apellido materno:</strong><input type="text" class="form-control" id="AM" name="AM" value="<?php echo htmlspecialchars($_POST['AM']); ?>" disabled>
            <strong>Telefono:</strong><input type="tel" class="form-control" id="tel" name="tel" value="<?php echo htmlspecialchars($_POST['tel']); ?>" disabled>
            <strong>Semestre:</strong><input type="text" class="form-control" id="semestre" name="semestre" value="<?php echo htmlspecialchars($_POST['semestre']); ?>" disabled>
            <strong>Carrera:</strong><input type="text" class="form-control" id="carrera" name="carrera" value="<?php echo htmlspecialchars($_POST['carrera']); ?>" disabled>
            <strong>Genero del tutor:</strong><input type="text" class="form-control" id="genero_tutor" name="genero_tutor" value="<?php echo htmlspecialchars($_POST['genero_tutor']); ?>" disabled>
            <strong>Tutor:</strong><input type="text" class="form-control" id="tutor" name="tutor" value="<?php echo htmlspecialchars($_POST['tutor']); ?>" disabled>
            <strong>Tipo de tutoria:</strong><input type="text" class="form-control" id="tipo_tutoria" name="tipo_tutoria" value="<?php echo htmlspecialchars($_POST['tipo_tutoria']); ?>" disabled>
            <strong>Correo:</strong><input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($_POST['correo']); ?>" disabled>
            <strong>Contraseña:</strong><input type="text" class="form-control" id="contrasena" name="contrasena" value="<?php echo htmlspecialchars($_POST['contrasena']); ?>" disabled>
            <br><br>
            <button type="submit" class="btn btn-primary" onclick="habilitarCampos()">Aceptar</button>
            <button type="button" class="btn btn-secondary" onclick="habilitarCampos()">Modificar</button>
            <br><br>
        </form>

        

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
    <script src="../js/scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>
</html>