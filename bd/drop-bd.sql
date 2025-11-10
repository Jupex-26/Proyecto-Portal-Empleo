USE empleo;

-- 🔒 Desactivar comprobación de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- 🧨 Eliminar todas las tablas
DROP TABLE IF EXISTS `ciclo-tiene-oferta`;
DROP TABLE IF EXISTS `alum_cursado_ciclo`;
DROP TABLE IF EXISTS `solicitud`;
DROP TABLE IF EXISTS `oferta`;
DROP TABLE IF EXISTS `empresa`;
DROP TABLE IF EXISTS `alumno`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `token`;
DROP TABLE IF EXISTS `rol`;
DROP TABLE IF EXISTS `ciclo`;
DROP TABLE IF EXISTS `familia`;

-- 🔓 Reactivar comprobación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;
