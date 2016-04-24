--
-- Smd Administrador Módulos
--

CREATE TABLE adm_modulos (
    id              serial                 PRIMARY KEY,

    orden           smallint               UNIQUE NOT NULL,
    clave           character varying(48)  UNIQUE NOT NULL,
    nombre          character varying(48)  NOT NULL,
    pagina          character varying(48),
    icono           character varying(48),
    padre           integer,
    permiso_maximo  smallint               DEFAULT 5 NOT NULL,
    poder_minimo    smallint               DEFAULT 1 NOT NULL,

    estatus         character(1)           DEFAULT 'A'::bpchar NOT NULL
);

-- Menú Usuarios
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)
    VALUES (951, 'adm_usuarios',          'Usuarios',          'address-book-new.png',      'admusuarios.php',          NULL,    6); -- 1
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, permiso_maximo, poder_minimo)
    VALUES (953, 'adm_autentificaciones', 'Autentificaciones', 'system-lock-screen.png',    'admautentificaciones.php', 1,    1, 6); -- 2
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, permiso_maximo, poder_minimo)
    VALUES (961, 'adm_bitacora',          'Bitácora',          'office-calendar.png',       'admbitacora.php',          1,    1, 6); -- 3
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, permiso_maximo, poder_minimo)
    VALUES (971, 'adm_sesiones',          'Sesiones',          'computer.png',              'admsesiones.php',          1,    1, 6); -- 4
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)
    VALUES (981, 'adm_departamentos',     'Departamentos',     'applications-internet.png', 'admdepartamentos.php',     1,       7); -- 5
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)
    VALUES (983, 'adm_modulos',           'Módulos',           'package-x-generic.png',     'admmodulos.php',           1,       7); -- 6
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)
    VALUES (985, 'adm_roles',             'Roles',             'applications-games.png',    'admroles.php',             1,       7); -- 7
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)
    VALUES (987, 'adm_integrantes',       'Integrantes',       'system-users.png',          'admintegrantes.php',       1,       7); -- 8

-- Por compatibilidad se crea la novena entrada dada de baja
INSERT INTO adm_modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo, estatus)
    VALUES (950, 'sistema',           'Sistema',           'folder.png',                'sistema.php',           NULL,    6, 'B'); -- 9

