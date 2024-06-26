<?php
function conectarBD() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "escom_registro_tutorias";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function load() {
    $conn = conectarBD();
    $sql = "SELECT * FROM estudiantes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $conn->close();
        return ['status' => 'success', 'data' => $data];
    } else {
        $conn->close();
        return ['status' => 'error', 'message' => 'No se encontraron datos'];
    }
}

function create($data) {
    $conn = conectarBD();

    $boleta = $data['boleta'];
    $nombre = $data['nombre'];
    $apellido_paterno = $data['AP'];
    $apellido_materno = $data['AM'];
    $telefono = $data['tel'];
    $semestre = $data['semestre'];
    $carrera = $data['carrera'];
    $tutor = $data['tutor'];
    $correo = $data['correo'];
    $fecha_registro = date("Y-m-d H:i:s");

    $sql = $conn->prepare("INSERT INTO estudiantes (boleta, nombre, apellido_paterno, apellido_materno, telefono, semestre, carrera, tutor, correo, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssssssss", $boleta, $nombre, $apellido_paterno, $apellido_materno, $telefono, $semestre, $carrera, $tutor, $correo, $fecha_registro);

    if ($sql->execute()) {
        $conn->close();
        return ['status' => 'success', 'message' => 'Estudiante creado correctamente'];
    } else {
        $conn->close();
        return ['status' => 'error', 'message' => 'Error al crear estudiante'];
    }
}

function read($data) {
    $conn = conectarBD();
    $boleta = $data['boleta'];

    $sql = $conn->prepare("SELECT * FROM estudiantes WHERE boleta = ?");
    $sql->bind_param("s", $boleta);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $conn->close();
        return ['status' => 'success', 'data' => $data];
    } else {
        $conn->close();
        return ['status' => 'error', 'message' => 'Estudiante no encontrado'];
    }
}

function update($data) {
    $conn = conectarBD();

    $boleta = $data['boleta'];
    $nombre = $data['nombre'];
    $apellido_paterno = $data['AP'];
    $apellido_materno = $data['AM'];
    $telefono = $data['tel'];
    $semestre = $data['semestre'];
    $carrera = $data['carrera'];
    $tutor = $data['tutor'];
    $correo = $data['correo'];
    $contrasena = $data['contrasena'];
    $contrasena_hashed = password_hash($contrasena, PASSWORD_BCRYPT);

    $sql = $conn->prepare("UPDATE estudiantes SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, semestre = ?, carrera = ?, tutor = ?, correo = ?, contrasena = ? WHERE boleta = ?");
    $sql->bind_param("ssssssssss", $nombre, $apellido_paterno, $apellido_materno, $telefono, $semestre, $carrera, $tutor, $correo, $contrasena_hashed, $boleta);

    if ($sql->execute()) {
        $conn->close();
        return ['status' => 'success', 'message' => 'Estudiante actualizado correctamente'];
    } else {
        $conn->close();
        return ['status' => 'error', 'message' => 'Error al actualizar estudiante'];
    }
}

function delete($data) {
    $conn = conectarBD();
    $boleta = $data['boleta'];

    $sql = $conn->prepare("DELETE FROM estudiantes WHERE boleta = ?");
    $sql->bind_param("s", $boleta);

    if ($sql->execute()) {
        $conn->close();
        return ['status' => 'success', 'message' => 'Estudiante eliminado correctamente'];
    } else {
        $conn->close();
        return ['status' => 'error', 'message' => 'Error al eliminar estudiante'];
    }
}
?>
