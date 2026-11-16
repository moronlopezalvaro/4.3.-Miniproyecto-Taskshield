DROP DATABASE IF EXISTS taskshield;
CREATE DATABASE taskshield CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE taskshield;

CREATE TABLE tecnicos (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(100) NOT NULL,
    tareas_activas INT NOT NULL DEFAULT 0
) ENGINE = InnoDB;   -- InnoDB es necesario para que funcionen las transacciones

CREATE TABLE incidencias (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(150) NOT NULL,
    descripcion   TEXT NOT NULL,
    prioridad     ENUM('Baja', 'Media', 'Alta') NOT NULL DEFAULT 'Media',
    email_cliente VARCHAR(150) NOT NULL,
    estado        ENUM('Pendiente', 'En Proceso', 'Resuelta') NOT NULL DEFAULT 'Pendiente',
    tecnico_id    INT NULL,
    fecha_alta    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

INSERT INTO tecnicos (nombre, tareas_activas) VALUES
    ('Laura Gómez', 0),
    ('Javier Ruiz', 1),
    ('Marta Sánchez', 0);

INSERT INTO incidencias (titulo, descripcion, prioridad, email_cliente, estado, tecnico_id) VALUES
    ('Impresora no imprime', 'La impresora de la 2ª planta da error de papel.', 'Media', 'ana@empresa.com', 'Pendiente', NULL),
    ('Sin acceso a internet', 'El equipo de recepción no tiene conexión.', 'Alta', 'pedro@empresa.com', 'En Proceso', 2),
    ('Cambiar ratón', 'El ratón del puesto 14 no funciona.', 'Baja', 'lucia@empresa.com', 'Pendiente', NULL),
    ('Instalar Office', 'Equipo nuevo de contabilidad.', 'Media', 'carlos@empresa.com', 'Resuelta', 1);
