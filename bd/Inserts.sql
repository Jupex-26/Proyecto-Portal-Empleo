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
('Actividades Físicas y Deportivas'),       
('Administración y Gestión'),               
('Agraria'),                                
('Artes Gráficas'),                         
('Artes y Artesanías'),                     
('Comercio y Marketing'),                   
('Edificación y Obra Civil'),             
('Electricidad y Electrónica'),         
('Energía y Agua'),                      
('Fabricación Mecánica'),               
('Hostelería y Turismo'),                 
('Imagen Personal'),                      
('Imagen y Sonido'),
('Industrias Alimentarias'),     
('Industrias Extractivas'),         
('Informática y Comunicaciones'),      
('Instalación y Mantenimiento'),        
('Madera, Mueble y Corcho'),        
('Marítimo-Pesquera'),                  
('Química'),                    
('Sanidad'),                               
('Seguridad y Medio Ambiente'),             
('Servicios Socioculturales y a la Comunidad'),
('Textil, Confección y Piel'),             
('Transporte y Mantenimiento de Vehículos'),
('Vidrio y Cerámica');                      

-- -----------------------------------------------------
-- Tabla: ciclo
-- -----------------------------------------------------
INSERT INTO ciclo (familia_id, nivel, nombre) VALUES
(1, 'BASICO', 'Técnico Básico en Acceso y Conservación en Instalaciones Deportivas'),
(1, 'MEDIO', 'Técnico en Guía en el Medio Natural y de Tiempo Libre'),
(1, 'MEDIO', 'Técnico en Actividades Ecuestres'),
(1, 'SUPERIOR', 'Técnico Superior en Acondicionamiento Físico'),
(1, 'SUPERIOR', 'Técnico Superior en Enseñanza y Animación Sociodeportiva'),
(2, 'BASICO', 'Técnico Básico en Servicios Administrativos'),
(2, 'BASICO', 'Técnico Básico en Informática de Oficina'),
(2, 'MEDIO', 'Técnico en Gestión Administrativa'),
(2, 'SUPERIOR', 'Técnico Superior en Administración y Finanzas'),
(2, 'SUPERIOR', 'Técnico Superior en Asistencia a la Dirección'),
(3, 'BASICO', 'Técnico Básico en Agro-jardinería y Composiciones Florales'),
(3, 'BASICO', 'Técnico Básico en Actividades Agropecuarias'),
(3, 'BASICO', 'Técnico Básico en Aprovechamientos Forestales'),
(3, 'MEDIO', 'Técnico en Producción Agropecuaria'),
(3, 'MEDIO', 'Técnico en Aprovechamiento y Conservación del Medio Natural'),
(3, 'MEDIO', 'Técnico en Jardinería y Floristería'),
(3, 'MEDIO', 'Técnico en Producción Agroecológica'),
(3, 'SUPERIOR', 'Técnico Superior en Gestión Forestal y del Medio Natural'),
(3, 'SUPERIOR', 'Técnico Superior en Paisajismo y Medio Rural'),
(3, 'SUPERIOR', 'Técnico Superior en Ganadería y Asistencia en Sanidad Animal'),
(3, 'ESPECIALIZACION', 'Master en Floristería y Arte Floral'),
(4, 'BASICO', 'Técnico Básico en Artes Gráficas'),
(4, 'MEDIO', 'Técnico en Preimpresión Digital'),
(4, 'MEDIO', 'Técnico en Impresión Gráfica'),
(4, 'MEDIO', 'Técnico en Postimpresión y Acabados Gráficos'),
(4, 'SUPERIOR', 'Técnico Superior en Diseño y Gestión de la Producción Gráfica'),
(4, 'SUPERIOR', 'Técnico Superior en Diseño y Edición de Publicaciones Impresas y Multimedia'),
(5, 'SUPERIOR', 'Técnico Superior en Ebanistería Artística'),
(5, 'SUPERIOR', 'Técnico Superior en Artes Aplicadas a la Escultura'),
(5, 'SUPERIOR', 'Técnico Superior en Joyería Artística'),
(6, 'BASICO', 'Técnico Básico en Servicios Comerciales'),
(6, 'MEDIO', 'Técnico en Actividades Comerciales'),
(6, 'SUPERIOR', 'Técnico Superior en Comercio Internacional'),
(6, 'SUPERIOR', 'Técnico Superior en Marketing y Publicidad'),
(6, 'SUPERIOR', 'Técnico Superior en Gestión de Ventas y Espacios Comerciales'),
(6, 'SUPERIOR', 'Técnico Superior en Transporte y Logística'),
(6, 'ESPECIALIZACION', 'Master en Comercio Electrónico'),
(6, 'ESPECIALIZACION', 'Master en Posicionamiento en Buscadores (SEO/SEM) y Comunicación en Redes Sociales'),
(6, 'ESPECIALIZACION', 'Master en Redacción de Contenidos Digitales para Marketing y Ventas'),
(7, 'BASICO', 'Técnico Básico en Reforma y Mantenimiento de Edificios'),
(7, 'MEDIO', 'Técnico en Construcción'),
(7, 'MEDIO', 'Técnico en Obras de Interior, Decoración y Restauración'),
(7, 'SUPERIOR', 'Técnico Superior en Proyectos de Edificación'),
(7, 'SUPERIOR', 'Técnico Superior en Proyectos de Obra Civil'),
(7, 'SUPERIOR', 'Técnico Superior en Organización y Control de Obras de Construcción'),
(7, 'ESPECIALIZACION', 'Master en Modelado de la Información en la Construcción (BIM)'),
(8, 'BASICO', 'Técnico Básico en Electricidad y Electrónica'),
(8, 'BASICO', 'Técnico Básico en Instalaciones Electrotécnicas y Mecánica'),
(8, 'MEDIO', 'Técnico en Instalaciones Eléctricas y Automáticas'),
(8, 'MEDIO', 'Técnico en Instalaciones de Telecomunicaciones'),
(8, 'SUPERIOR', 'Técnico Superior en Automatización y Robótica Industrial'),
(8, 'SUPERIOR', 'Técnico Superior en Sistemas Electrotécnicos y Automatizados'),
(8, 'SUPERIOR', 'Técnico Superior en Mantenimiento Electrónico'),
(8, 'SUPERIOR', 'Técnico Superior en Sistemas de Telecomunicaciones e Informáticos'),
(8, 'SUPERIOR', 'Técnico Superior en Electromedicina Clínica'),
(8, 'ESPECIALIZACION', 'Master en Ciberseguridad en Entornos de las Tecnologías de Operación'),
(8, 'ESPECIALIZACION', 'Master en Implementación de Redes 5G'),
(8, 'ESPECIALIZACION', 'Master en Robótica Colaborativa'),
(8, 'ESPECIALIZACION', 'Master en Sistemas de Señalización y Telecomunicaciones Ferroviarias'),
(9, 'MEDIO', 'Técnico en Redes y Estaciones de Tratamiento de Aguas'),
(9, 'SUPERIOR', 'Técnico Superior en Energías Renovables'),
(9, 'SUPERIOR', 'Técnico Superior en Eficiencia Energética y Energía Solar Térmica'),
(9, 'SUPERIOR', 'Técnico Superior en Gestión del Agua'),
(9, 'ESPECIALIZACION', 'Master en Auditoría Energética'),
(10, 'BASICO', 'Técnico Básico en Fabricación y Montaje'),
(10, 'BASICO', 'Técnico Básico en Fabricación de Elementos Metálicos'),
(10, 'MEDIO', 'Técnico en Mecanizado'),
(10, 'MEDIO', 'Técnico en Soldadura y Calderería'),
(10, 'MEDIO', 'Técnico en Conformado por Moldeo de Metales y Polímeros'),
(10, 'SUPERIOR', 'Técnico Superior en Diseño en Fabricación Mecánica'),
(10, 'SUPERIOR', 'Técnico Superior en Programación de la Producción en Fabricación Mecánica'),
(10, 'SUPERIOR', 'Técnico Superior en Construcciones Metálicas'),
(10, 'ESPECIALIZACION', 'Master en Fabricación Aditiva'),
(10, 'ESPECIALIZACION', 'Master en Materiales Compuestos en la Industria Aeroespacial'),
(11, 'BASICO', 'Técnico Básico en Cocina y Restauración'),
(11, 'BASICO', 'Técnico Básico en Actividades de Panadería y Pastelería'),
(11, 'BASICO', 'Técnico Básico en Alojamiento y Lavandería'),
(11, 'MEDIO', 'Técnico en Cocina y Gastronomía'),
(11, 'MEDIO', 'Técnico en Servicios en Restauración'),
(11, 'MEDIO', 'Técnico en Comercialización de Productos Alimentarios'),
(11, 'SUPERIOR', 'Técnico Superior en Dirección de Cocina'),
(11, 'SUPERIOR', 'Técnico Superior en Dirección de Servicios de Restauración'),
(11, 'SUPERIOR', 'Técnico Superior en Gestión de Alojamientos Turísticos'),
(11, 'SUPERIOR', 'Técnico Superior en Guía, Información y Asistencias Turísticas'),
(11, 'ESPECIALIZACION', 'Master en Coordinación del Personal en Reuniones Profesionales, Congresos, Ferias, Exposiciones y Eventos'),
(11, 'ESPECIALIZACION', 'Master en Panadería y Bollería Artesanales'),
(12, 'BASICO', 'Técnico Básico en Peluquería y Estética'),
(12, 'MEDIO', 'Técnico en Peluquería y Cosmética Capilar'),
(12, 'MEDIO', 'Técnico en Estética y Belleza'),
(12, 'SUPERIOR', 'Técnico Superior en Estilismo y Dirección de Peluquería'),
(12, 'SUPERIOR', 'Técnico Superior en Asesoría de Imagen Personal y Corporativa'),
(12, 'SUPERIOR', 'Técnico Superior en Caracterización y Maquillaje Profesional'),
(12, 'SUPERIOR', 'Técnico Superior en Estética Integral y Bienestar'),
(12, 'SUPERIOR', 'Técnico Superior en Termalismo y Bienestar'),
(13, 'MEDIO', 'Técnico en Vídeo Disc-Jockey y Sonido'),
(13, 'SUPERIOR', 'Técnico Superior en Producción de Audiovisuales y Espectáculos'),
(13, 'SUPERIOR', 'Técnico Superior en Iluminación, Captación y Tratamiento de Imagen'),
(13, 'SUPERIOR', 'Técnico Superior en Sonido para Audiovisuales y Espectáculos'),
(13, 'ESPECIALIZACION', 'Master en Audiodescripción y Subtitulación'),
(14, 'BASICO', 'Técnico Básico en Industrias Alimentarias'),
(14, 'MEDIO', 'Técnico en Panadería, Repostería y Confitería'),
(14, 'MEDIO', 'Técnico en Elaboración de Productos Alimenticios'),
(14, 'MEDIO', 'Técnico en Aceites de Oliva y Vinos'),
(14, 'SUPERIOR', 'Técnico Superior en Procesos y Calidad en la Industria Alimentaria'),
(14, 'SUPERIOR', 'Técnico Superior en Vitivinicultura'),
(14, 'ESPECIALIZACION', 'Master en Tecnología y Gestión Quesera'),
(15, 'MEDIO', 'Técnico en Piedra Natural'),
(15, 'MEDIO', 'Técnico en Excavaciones y Sondeos'),
(15, 'SUPERIOR', 'Técnico Superior en Exploración y Sondajes'),
(16, 'BASICO', 'Técnico Básico en Informática y Comunicaciones'),
(16, 'BASICO', 'Técnico Básico en Informática de Oficina'),
(16, 'MEDIO', 'Técnico en Sistemas Microinformáticos y Redes'),
(16, 'SUPERIOR', 'Técnico Superior en Desarrollo de Aplicaciones Web (DAW)'),
(16, 'SUPERIOR', 'Técnico Superior en Administración de Sistemas Informáticos en Red (ASIR)'),
(16, 'SUPERIOR', 'Técnico Superior en Desarrollo de Aplicaciones Multiplataforma (DAM)'),
(16, 'ESPECIALIZACION', 'Master en Ciberseguridad en Entornos de las Tecnologías de la Información'),
(16, 'ESPECIALIZACION', 'Master en Inteligencia Artificial y Big Data'),
(16, 'ESPECIALIZACION', 'Master en Desarrollo de Videojuegos y Realidad Virtual'),
(16, 'ESPECIALIZACION', 'Master en Desarrollo de Aplicaciones en Lenguaje Python'),
(16, 'ESPECIALIZACION', 'Master en Administración de Recursos y Servicios en la Nube'),
(17, 'BASICO', 'Técnico Básico en Mantenimiento de Viviendas'),
(17, 'MEDIO', 'Técnico en Mantenimiento Electromecánico'),
(17, 'MEDIO', 'Técnico en Instalaciones Frigoríficas y de Climatización'),
(17, 'MEDIO', 'Técnico en Instalaciones de Producción de Calor'),
(17, 'SUPERIOR', 'Técnico Superior en Mecatrónica Industrial'),
(17, 'SUPERIOR', 'Técnico Superior en Desarrollo de Proyectos de Instalaciones Térmicas y de Fluidos'),
(17, 'SUPERIOR', 'Técnico Superior en Mantenimiento de Instalaciones Térmicas y de Fluidos'),
(17, 'SUPERIOR', 'Técnico Superior en Mantenimiento Aeromecánico de Aviones con Motor de Turbina'),
(17, 'SUPERIOR', 'Técnico Superior en Mantenimiento Aeromecánico de Helicópteros con Motor de Turbina'),
(17, 'ESPECIALIZACION', 'Master en Digitalización del Mantenimiento Industrial'),
(17, 'ESPECIALIZACION', 'Master en Fabricación Inteligente'),
(17, 'ESPECIALIZACION', 'Master en Modelado de la Información en la Construcción (BIM)'),
(18, 'BASICO', 'Técnico Básico en Carpintería y Mueble'),
(18, 'MEDIO', 'Técnico en Carpintería y Mueble'),
(18, 'MEDIO', 'Técnico en Instalación y Amueblamiento'),
(18, 'SUPERIOR', 'Técnico Superior en Diseño y Amueblamiento'),
(19, 'BASICO', 'Técnico Básico en Mantenimiento de Embarcaciones Deportivas y de Recreo'),
(19, 'BASICO', 'Técnico Básico en Actividades Marítimo-Pesqueras'),
(19, 'MEDIO', 'Técnico en Navegación y Pesca de Litoral'),
(19, 'MEDIO', 'Técnico en Mantenimiento y Control de la Maquinaria de Buques y Embarcaciones'),
(19, 'SUPERIOR', 'Técnico Superior en Transporte Marítimo y Pesca de Altura'),
(19, 'SUPERIOR', 'Técnico Superior en Organización del Mantenimiento de Maquinaria de Buques y Embarcaciones'),
(20, 'MEDIO', 'Técnico en Planta Química'),
(20, 'SUPERIOR', 'Técnico Superior en Química Industrial'),
(20, 'SUPERIOR', 'Técnico Superior en Laboratorio de Análisis y de Control de Calidad'),
(20, 'ESPECIALIZACION', 'Master en Cultivos Celulares'),
(21, 'MEDIO', 'Técnico en Cuidados Auxiliares de Enfermería'),
(21, 'MEDIO', 'Técnico en Farmacia y Parafarmacia'),
(21, 'MEDIO', 'Técnico en Emergencias Sanitarias'),
(21, 'SUPERIOR', 'Técnico Superior en Laboratorio Clínico y Biomédico'),
(21, 'SUPERIOR', 'Técnico Superior en Higiene Bucodental'),
(21, 'SUPERIOR', 'Técnico Superior en Radioterapia y Dosimetría'),
(21, 'SUPERIOR', 'Técnico Superior en Imagen para el Diagnóstico y Medicina Nuclear'),
(21, 'SUPERIOR', 'Técnico Superior en Prótesis Dentales'),
(21, 'SUPERIOR', 'Técnico Superior en Dietética'),
(22, 'MEDIO', 'Técnico en Emergencias y Protección Civil'),
(22, 'SUPERIOR', 'Técnico Superior en Coordinación de Emergencias y Protección Civil'),
(22, 'SUPERIOR', 'Técnico Superior en Educación y Control Ambiental'),
(22, 'SUPERIOR', 'Técnico Superior en Química y Salud Ambiental'),
(23, 'BASICO', 'Técnico Básico en Actividades Domésticas y Limpieza de Edificios'),
(23, 'SUPERIOR', 'Técnico Superior en Educación Infantil'),
(23, 'SUPERIOR', 'Técnico Superior en Integración Social'),
(23, 'SUPERIOR', 'Técnico Superior en Promoción de Igualdad de Género'),
(23, 'SUPERIOR', 'Técnico Superior en Mediación Comunicativa'),
(23, 'SUPERIOR', 'Técnico Superior en Animación Sociocultural y Turística'),
(23, 'SUPERIOR', 'Técnico Superior en Formación para la Movilidad Segura y Sostenible'),
(24, 'BASICO', 'Técnico Básico en Tapicería y Cortinaje'),
(24, 'BASICO', 'Técnico Básico en Arreglo y Reparación de Artículos Textiles y de Piel'),
(24, 'MEDIO', 'Técnico en Confección y Moda'),
(24, 'MEDIO', 'Técnico en Calzado y Complementos de Moda'),
(24, 'SUPERIOR', 'Técnico Superior en Patronaje y Moda'),
(24, 'SUPERIOR', 'Técnico Superior en Diseño Técnico en Textil y Piel'),
(25, 'BASICO', 'Técnico Básico en Mantenimiento de Vehículos'),
(25, 'MEDIO', 'Técnico en Electromecánica de Vehículos Automóviles'),
(25, 'MEDIO', 'Técnico en Carrocería'),
(25, 'SUPERIOR', 'Técnico Superior en Automoción'),
(25, 'SUPERIOR', 'Técnico Superior en Mantenimiento de Sistemas Electrónicos y Aviónicos en Aeronaves'),
(25, 'ESPECIALIZACION', 'Master en Mantenimiento de Vehículos Híbridos y Eléctricos'),
(25, 'ESPECIALIZACION', 'Master en Aeronaves Pilotadas de Forma Remota-Drones'),
(25, 'ESPECIALIZACION', 'Mantenimiento Avanzado de Sistemas de Material Rodante Ferroviario'),
(25, 'ESPECIALIZACION', 'Digitalización del Mantenimiento Industrial (M. Aeronáutica)'),
(26, 'BASICO', 'Técnico Básico en Vidriería y Alfarería'),
(26, 'SUPERIOR', 'Técnico Superior en Desarrollo y Fabricación de Productos Cerámicos');

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
