--
-- GenesisPHP
--
-- Módulos
--

CREATE TABLE modulos (
    id                serial                   PRIMARY KEY,
    orden             smallint                 UNIQUE NOT NULL,
    clave             character varying(48)    UNIQUE NOT NULL,
    nombre            character varying(48)    NOT NULL,
    pagina            character varying(48),
    icono             character varying(48),
    padre             integer,
    permiso_maximo    smallint                 DEFAULT 5 NOT NULL,
    poder_minimo      smallint                 DEFAULT 1 NOT NULL,
    estatus           character(1)             DEFAULT 'A'::bpchar NOT NULL
);

-- Menú Usuarios, que incluye todo lo que tenía Sistema; note que Sistema está dado de baja
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo, estatus)        VALUES (901, 'sistema',           'Sistema',           'folder.png',                'sistema.php',           NULL, 7, 'B'); -- 1
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)                 VALUES (981, 'departamentos',     'Departamentos',     'applications-internet.png', 'departamentos.php',     6,    7);      -- 2
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)                 VALUES (983, 'modulos',           'Módulos',           'package-x-generic.png',     'modulos.php',           6,    7);      -- 3
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)                 VALUES (985, 'roles',             'Roles',             'applications-games.png',    'roles.php',             6,    7);      -- 4
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, poder_minimo)                 VALUES (987, 'integrantes',       'Integrantes',       'system-users.png',          'integrantes.php',       6,    7);      -- 5
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, permiso_maximo, poder_minimo) VALUES (951, 'usuarios',          'Usuarios',          'address-book-new.png',      'usuarios.php',          NULL, 5, 6);   -- 6
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, permiso_maximo, poder_minimo) VALUES (953, 'autentificaciones', 'Autentificaciones', 'system-lock-screen.png',    'autentificaciones.php', 6,    1, 6);   -- 7
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, permiso_maximo, poder_minimo) VALUES (961, 'bitacora',          'Bitácora',          'office-calendar.png',       'bitacora.php',          6,    1, 7);   -- 8
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre, permiso_maximo, poder_minimo) VALUES (971, 'sesiones',          'Sesiones',          'computer.png',              'sesiones.php',          6,    1, 6);   -- 9
