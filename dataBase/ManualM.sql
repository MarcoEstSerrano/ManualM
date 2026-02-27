CREATE DATABASE ManualM;
USE ManualM;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla de temas (con relación a usuarios y categorias)
CREATE TABLE temas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    usuario_id INT,
    categoria_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

INSERT INTO categorias (nombre) VALUES ('PHP');
INSERT INTO categorias (nombre) VALUES ('HTML');
INSERT INTO categorias (nombre) VALUES ('CSS');

SELECT * FROM usuarios;



INSERT INTO usuarios (nombre, email, password) VALUES ('Marco', 'marcoesteban116@hotmail.com', '123456');

SET FOREIGN_KEY_CHECKS = 0; -- Desactiva la revisión de llaves
TRUNCATE TABLE usuarios;    -- Limpia la tabla y reinicia el ID
SET FOREIGN_KEY_CHECKS = 1; -- Activa la revisión de nuevo

-- Tabla para las cuentas bancarias
CREATE TABLE cuentas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    banco ENUM('BAC', 'BCR') NOT NULL,
    saldo DECIMAL(15, 2) DEFAULT 0.00,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla para movimientos (Gastos e Ingresos)
CREATE TABLE movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('ingreso', 'gasto', 'capricho') NOT NULL,
    monto DECIMAL(15, 2) NOT NULL,
    descripcion VARCHAR(255),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cuenta_id INT,
    usuario_id INT,
    FOREIGN KEY (cuenta_id) REFERENCES cuentas(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
INSERT INTO cuentas (banco, saldo, usuario_id) VALUES ('BAC', 0, 1);
INSERT INTO cuentas (banco, saldo, usuario_id) VALUES ('BCR', 0, 1);

ALTER TABLE movimientos ADD COLUMN banco ENUM('BAC', 'BCR') AFTER descripcion;

CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    prioridad ENUM('Baja', 'Media', 'Alta') DEFAULT 'Media',
    estado ENUM('Pendiente', 'Completada') DEFAULT 'Pendiente',
    usuario_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE apuntes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    color VARCHAR(20) DEFAULT 'bg-gray-800',
    usuario_id INT,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE multimedia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    url_youtube VARCHAR(255) NOT NULL,
    usuario_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE musica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    archivo VARCHAR(255) NOT NULL, -- Nombre del archivo ej: rock.mp3
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

TRUNCATE TABLE musica; 
INSERT INTO musica (titulo, archivo, usuario_id) VALUES ('Mi Primera Canción', 'cancion_1.mp3', 1);
INSERT INTO musica (titulo, archivo, usuario_id) VALUES ('009_Sound_System_Trinity', 'cancion_2.mp3', 1);
INSERT INTO musica (titulo, archivo, usuario_id) VALUES ('009_Sound System_Born_To_Be_Wasted', 'cancion_3.mp3', 1);
INSERT INTO musica (titulo, archivo, usuario_id) VALUES ('009_Sound_System_With_A_Spirit', 'cancion_4.mp3', 1);