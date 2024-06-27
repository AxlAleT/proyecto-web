USE tutorias;

CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipo_tutoria (
    id_tipo_tutoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    cupo_maximo INT DEFAULT 15
);

CREATE TABLE tutores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL,
    genero VARCHAR(1) NOT NULL
);

CREATE TABLE grupo (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    codigo_grupo VARCHAR(10),
    id_tipo_tutoria INT,
    id_tutor INT,
    FOREIGN KEY (id_tipo_tutoria) REFERENCES tipo_tutoria(id_tipo_tutoria),
    FOREIGN KEY (id_tutor) REFERENCES tutores(id)
);

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
    id_grupo INT,
    fecha_registro TIMESTAMP,
    FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo)
);

INSERT INTO administradores (id, email, password_hash, created_at)
VALUES (1, 'admin@ipn.mx', '$2y$10$iDARE96VriTRjI6fgTEtju.uYU66/NVbmx.1YloNbOU1FVvLm1JUe', '2024-05-29 18:31:17');

INSERT INTO tipo_tutoria (nombre, cupo_maximo) VALUES
    ('Individual', 6),
    ('Grupal', 30),
    ('Recuperación Académica', 300),
    ('Regularización', 15),
    ('Entre Pares', 15);

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

INSERT INTO grupo (codigo_grupo, id_tipo_tutoria, id_tutor) VALUES
    ('NULL', 1, 1),
    ('3CV4', 2, 2),
    ('NULL', 3, 3),
    ('NULL', 4, 1),
    ('NULL', 5, 2),
    ('4CM5', 2, 3),
    ('NULL', 1, 4),
    ('4CM1', 2, 5),
    ('NULL', 3, 6),
    ('NULL', 4, 7),
    ('NULL', 5, 8),
    ('4CV4', 2, 9),
    ('NULL', 1, 10),
    ('5CV3', 2, 11),
    ('NULL', 3, 12),
    ('NULL', 4, 13),
    ('NULL', 5, 14),
    ('5CV4', 2, 15),
    ('NULL', 1, 16),
    ('5CV1', 2, 17),
    ('NULL', 3, 18),
    ('NULL', 4, 19),
    ('NULL', 5, 20);
