USE tutorias;

CREATE TABLE tipoTutoria (
    id_tipo_tutoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

INSERT INTO tipoTutoria (nombre) VALUES
    ('Individual'),
    ('Grupal'),
    ('Recuperación Académica'),
    ('Regularización'),
    ('Entre Pares');

CREATE TABLE estudiantes (
    boleta INT PRIMARY KEY NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL,
    telefono VARCHAR(10) NOT NULL,
    semestre ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10') NOT NULL,
    carrera VARCHAR(3) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    id_tipo_tutoria INT,
    FOREIGN KEY (id_tipo_tutoria) REFERENCES tipoTutoria(id_tipo_tutoria),
    fecha_registro TIMESTAMP
);

CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tutores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL,
    genero VARCHAR(1) NOT NULL
);

CREATE TABLE estudianteTutor (
    id_estudiante INT,
    id_tutor INT,
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(boleta),
    FOREIGN KEY (id_tutor) REFERENCES tutores(id),
    PRIMARY KEY (id_estudiante, id_tutor)
);

INSERT INTO administradores (id, email, password_hash, created_at)
VALUES (1, 'admin@ipn.mx', '$2y$10$iDARE96VriTRjI6fgTEtju.uYU66/NVbmx.1YloNbOU1FVvLm1JUe', '2024-05-29 18:31:17');

INSERT INTO tutores (nombre, apellido_paterno, apellido_materno, genero)
VALUES
    ('Patricia', 'Escamilla', 'Miranda', 'M'),
    ('Martha Patricia', 'Jiménez', 'Villanueva', 'M'),
    ('Laura', 'Méndez', 'Segundo', 'M'),
    ('Laura', 'Muñoz', 'Salazar', 'M'),
    ('Judith Margarita', 'Tirado', 'Lule', 'M'),
    ('Karina', 'Viveros', 'Vela', 'M'),
    ('Rocio', 'Palacios', 'Solano', 'M'),
    ('Claudia', 'Díaz', 'Huerta', 'M'),
    ('Elia', 'Ramírez', 'Martínez', 'M'),
    ('Gabriela', 'López', 'Ruiz', 'M'),
    ('José Asunción', 'Enríquez', 'Zárate', 'H'),
    ('Alberto Jesús', 'Alcántara', 'Méndez', 'H'),
    ('Felipe de Jesús', 'Figueroa', 'del Prado', 'H'),
    ('Erick', 'Linares', 'Vallejo', 'H'),
    ('Edgar Armando', 'Catalán', '', 'H'),
    ('Jorge', 'Cortés', 'Galicia', 'H'),
    ('Edgardo', 'Franco', 'Martínez', 'H'),
    ('Vicente', 'García', 'Sales', 'H'),
    ('Iván', 'Mosso', 'García', 'H'),
    ('Miguel Ángel', 'Rodríguez', '', 'H');
