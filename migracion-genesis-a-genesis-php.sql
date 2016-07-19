--
-- Migración de Genesis a GenesisPHP
--

ALTER TABLE bitacora          RENAME TO adm_bitacora;
ALTER TABLE cadenero          RENAME TO adm_cadenero;
ALTER TABLE autentificaciones RENAME TO adm_autentificaciones;
ALTER TABLE sesiones          RENAME TO adm_sesiones;
ALTER TABLE modulos           RENAME TO adm_modulos;
ALTER TABLE roles             RENAME TO adm_roles;
ALTER TABLE integrantes       RENAME TO adm_integrantes;
ALTER TABLE departamentos     RENAME TO adm_departamentos;
ALTER TABLE usuarios          RENAME TO adm_usuarios;

ALTER SEQUENCE bitacora_id_seq          RENAME TO adm_bitacora_id_seq;
-- No existe   cadenero_id_seq
-- No existe   autentificaciones_id_seq
-- No existe   sesiones_id_seq
ALTER SEQUENCE modulos_id_seq           RENAME TO adm_modulos_id_seq;
ALTER SEQUENCE roles_id_seq             RENAME TO adm_roles_id_seq;
ALTER SEQUENCE integrantes_id_seq       RENAME TO adm_integrantes_id_seq;
ALTER SEQUENCE departamentos_id_seq     RENAME TO adm_departamentos_id_seq;
ALTER SEQUENCE usuarios_id_seq          RENAME TO adm_usuarios_id_seq;

UPDATE adm_modulos SET clave = 'adm_sistema',           pagina = 'admsistema.php'           WHERE clave = 'sistema';
UPDATE adm_modulos SET clave = 'adm_usuarios',          pagina = 'admusuarios.php'          WHERE clave = 'usuarios';
UPDATE adm_modulos SET clave = 'adm_autentificaciones', pagina = 'admautentificaciones.php' WHERE clave = 'autentificaciones';
UPDATE adm_modulos SET clave = 'adm_bitacora',          pagina = 'admbitacora.php'          WHERE clave = 'bitacora';
UPDATE adm_modulos SET clave = 'adm_sesiones',          pagina = 'admsesiones.php'          WHERE clave = 'sesiones';
UPDATE adm_modulos SET clave = 'adm_departamentos',     pagina = 'admdepartamentos.php'     WHERE clave = 'departamentos';
UPDATE adm_modulos SET clave = 'adm_modulos',           pagina = 'admmodulos.php'           WHERE clave = 'modulos';
UPDATE adm_modulos SET clave = 'adm_roles',             pagina = 'admroles.php'             WHERE clave = 'roles';
UPDATE adm_modulos SET clave = 'adm_integrantes',       pagina = 'admintegrantes.php'       WHERE clave = 'integrantes';

UPDATE adm_modulos SET nombre = '-Sistema', icono = 'preferences-desktop.png', estatus = 'A' WHERE clave = 'adm_sistema';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_usuarios';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_autentificaciones';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_bitacora';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_sesiones';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_departamentos';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_modulos';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_roles';
UPDATE adm_modulos SET padre = (SELECT id FROM adm_modulos WHERE clave = 'adm_sistema')      WHERE clave = 'adm_integrantes';
