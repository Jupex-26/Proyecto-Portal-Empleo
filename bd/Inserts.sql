USE empleo;

-- -----------------------------------------------------
-- Tabla: rol
-- -----------------------------------------------------
INSERT INTO rol (nombre) VALUES
('Administrador'),
('Empresa'),
('Alumno');

-- -----------------------------------------------------
-- Tabla: token
-- -----------------------------------------------------
INSERT INTO token (codigo, fecha_caducidad) VALUES
('ABC123', '2025-12-31'),
('XYZ789', '2026-01-15'),
('QWE456', '2025-11-30');

-- -----------------------------------------------------
-- Tabla: user
-- -----------------------------------------------------
INSERT INTO user (nombre, correo, passwd, rol_id, direccion, foto, token_id) VALUES
('Carlos Pérez', 'carlos.perez@admin.com', 'admin123', 1, 'Calle Mayor 123', 'foto1.jpg', 1),
('Innovatech SA', 'contacto@innovatech.com', 'empresa123', 2, 'Av. Industria 45', 'empresa1.png', 2),
('María López', 'maria.lopez@gmail.com', 'alumno123', 3, 'Calle Sol 22', 'maria.jpg', 3),
('SoftSolutions SL', 'rrhh@softsolutions.com', 'empresa456', 2, 'Paseo del Parque 5', 'empresa2.png', NULL),
('Juan García', 'juan.garcia@gmail.com', 'alumno456', 3, 'Calle Luna 10', 'juan.jpg', NULL);

-- -----------------------------------------------------
-- Tabla: alumno
-- -----------------------------------------------------
INSERT INTO alumno (id, ap1, ap2, cv, fecha_nacimiento, descripcion) VALUES
(3, 'Pérez', 'García', 'cv_maria.pdf', '2000-05-12',''),
(5, 'López', 'Martínez', 'cv_juan.pdf',  '1998-11-23','');



-- -----------------------------------------------------
-- Tabla: empresa
-- -----------------------------------------------------
INSERT INTO empresa (id, correoContacto, telefonoContacto, activo, descripcion) VALUES
(2, 'rrhh@innovatech.com', '600123456', 1, 'Empresa tecnológica especializada en IA.'),
(4, 'contacto@softsolutions.com', '611987654', 1, 'Consultora de software empresarial.');

-- -----------------------------------------------------
-- Tabla: familia
-- -----------------------------------------------------
INSERT INTO familia (nombre) VALUES
('Informática y Comunicaciones'),
('Administración y Gestión'),
('Electricidad y Electrónica');

-- -----------------------------------------------------
-- Tabla: ciclo
-- -----------------------------------------------------
INSERT INTO ciclo (familia_id, nivel, nombre) VALUES
(1, 'SUPERIOR', 'Desarrollo de Aplicaciones Web'),
(1, 'SUPERIOR', 'Administración de Sistemas Informáticos en Red'),
(2, 'MEDIO', 'Gestión Administrativa'),
(3, 'MEDIO', 'Instalaciones Eléctricas y Automáticas');

-- -----------------------------------------------------
-- Tabla: alum_cursado_ciclo
-- -----------------------------------------------------
INSERT INTO alum_cursado_ciclo (alumno_id, ciclo_id, fecha_inicio, fecha_fin) VALUES
(3, 1, '2023-09-01', '2025-06-30'),
(5, 2, '2022-09-01', NULL);

-- -----------------------------------------------------
-- Tabla: oferta
-- -----------------------------------------------------
INSERT INTO oferta (empresa_id, nombre, descripcion, fecha_inicio, fecha_fin) VALUES
(2, 'Desarrollador Web Junior', 'Puesto para prácticas en desarrollo web.', '2025-10-01 09:00:00', '2025-12-31 17:00:00'),
(4, 'Técnico de Sistemas', 'Asistente en administración de redes y sistemas.', '2025-09-15 09:00:00', '2025-11-30 17:00:00');



-- -----------------------------------------------------
-- Tabla: ciclo-tiene-oferta
-- -----------------------------------------------------
INSERT INTO `ciclo-tiene-oferta` (ciclo_id, oferta_id, requerido) VALUES
(1, 1, 1),
(2, 2, 1);

-- -----------------------------------------------------
-- Tabla: solicitud
-- -----------------------------------------------------
INSERT INTO solicitud (alumno_id, oferta_id, estado) VALUES
(3, 1, 'PROCESO'),
(5, 2, 'ACEPTADO');
