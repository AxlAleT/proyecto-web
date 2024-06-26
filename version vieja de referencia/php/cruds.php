<?php
require 'funcionesBD.php';

session_start();

if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

function validateAndSanitize($data) {
    $validatedData = [];

    if (isset($data['boleta']) && preg_match('/^\d{10}$/', $data['boleta'])) {
        $validatedData['boleta'] = $data['boleta'];
    } else {
        return ['status' => 'error', 'message' => 'Boleta inválida'];
    }

    if (isset($data['nombre']) && preg_match('/^[a-zA-Z\s]+$/', $data['nombre'])) {
        $validatedData['nombre'] = filter_var($data['nombre'], FILTER_SANITIZE_STRING);
    } else {
        return ['status' => 'error', 'message' => 'Nombre inválido'];
    }

    if (isset($data['AP']) && preg_match('/^[a-zA-Z\s]+$/', $data['AP'])) {
        $validatedData['AP'] = filter_var($data['AP'], FILTER_SANITIZE_STRING);
    } else {
        return ['status' => 'error', 'message' => 'Apellido paterno inválido'];
    }

    if (isset($data['AM']) && preg_match('/^[a-zA-Z\s]+$/', $data['AM'])) {
        $validatedData['AM'] = filter_var($data['AM'], FILTER_SANITIZE_STRING);
    } else {
        return ['status' => 'error', 'message' => 'Apellido materno inválido'];
    }

    if (isset($data['tel']) && preg_match('/^\d{10}$/', $data['tel'])) {
        $validatedData['tel'] = $data['tel'];
    } else {
        return ['status' => 'error', 'message' => 'Teléfono inválido'];
    }

    if (isset($data['semestre']) && filter_var($data['semestre'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]])) {
        $validatedData['semestre'] = $data['semestre'];
    } else {
        return ['status' => 'error', 'message' => 'Semestre inválido'];
    }

    $validCarreras = ['ISC', 'LCD', 'IA'];
    if (isset($data['carrera']) && in_array($data['carrera'], $validCarreras)) {
        $validatedData['carrera'] = $data['carrera'];
    } else {
        return ['status' => 'error', 'message' => 'Carrera inválida'];
    }

    $validTutores = ['H', 'M'];
    if (isset($data['tutor']) && in_array($data['tutor'], $validTutores)) {
        $validatedData['tutor'] = $data['tutor'];
    } else {
        return ['status' => 'error', 'message' => 'Tutor inválido'];
    }

    if (isset($data['correo']) && filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
        $validatedData['correo'] = filter_var($data['correo'], FILTER_SANITIZE_EMAIL);
    } else {
        return ['status' => 'error', 'message' => 'Correo inválido'];
    }

    if (isset($data['contrasena'])) {
        $validatedData['contrasena'] = $data['contrasena'];
    } else {
        return ['status' => 'error', 'message' => 'Contraseña requerida'];
    }

    return ['status' => 'success', 'data' => $validatedData];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $operacion = $_POST['operacion'] ?? '';

    $validationResult = validateAndSanitize($_POST);
    if ($validationResult['status'] === 'error') {
        echo json_encode($validationResult);
        exit;
    }
    $sanitizedData = $validationResult['data'];

    switch ($operacion) {
        case 'load':
            $response = load();
            break;
        case 'create':
            $response = create($sanitizedData);
            break;
        case 'read':
            $response = read($sanitizedData);
            break;
        case 'update':
            $response = update($sanitizedData);
            break;
        case 'delete':
            $response = delete($sanitizedData);
            break;
        default:
            $response = ['status' => 'error', 'message' => 'Operación no válida'];
    }

    echo json_encode($response);
}
?>
