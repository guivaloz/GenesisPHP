--
-- GenesisPHP
--
-- Modulos Roles Insertar
--

-- Menú Catálogos (si la primer opcion comienza con guion, se omite en el menú secundario)
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre) VALUES (801, 'cat_catalogos',     '-Catálogos',    'file-manager.png',              'catdepartamentos.php', NULL); -- 10
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre) VALUES (811, 'cat_departamentos', 'Departamentos', 'fsview.png',                    'catdepartamentos.php',   10); -- 11
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre) VALUES (813, 'cat_puestos',       'Puestos',       'preferences-desktop-theme.png', 'catpuestos.php',         10); -- 12

-- Roles de Desarrollo de Sistemas(1) en el menú Catálogos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1, 10, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1, 11, 5);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1, 12, 5);

-- Roles de Dirección General(2) en el menú Catálogos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (2, 10, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (2, 11, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (2, 12, 1);

-- Roles de Dirección Administrativa(3) en el menú Catálogos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3, 10, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3, 11, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3, 12, 1);

-- Roles de Dirección de Recursos Humanos(4) en el menú Catálogos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (4, 10, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (4, 11, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (4, 12, 1);

-- Menú Expedientes
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre) VALUES (101, 'exp_personas',            'Personas',   'folder.png',                   'exppersonas.php',           NULL); -- 13
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre) VALUES (105, 'exp_personas_fotos',      'Fotos',      'camera-photo.png',             'exppersonasfotos.php',        13); -- 14
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre) VALUES (111, 'exp_personas_domicilios', 'Domicilios', 'preferences-desktop-user.png', 'exppersonasdomicilios.php',   13); -- 15
INSERT INTO modulos (orden, clave, nombre, icono, pagina, padre) VALUES (113, 'exp_personas_familiares', 'Familiares', 'scanner.png',                  'spcpersonasfamiliares.php',   13); -- 16

-- Roles de Desarrollo de Sistemas(1) en el menú Expedientes
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1, 13, 5); -- 5 = puede ver, modificar, agregar, eliminar y recuperar
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1, 14, 5);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1, 15, 5);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1, 16, 5);

-- Roles de Dirección General(2) en el menú Expedientes
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (2, 13, 1); -- 1 = solo ver
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (2, 14, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (2, 15, 1);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (2, 16, 1);

-- Roles de Dirección Administrativa(3) en el menú Expedientes
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3, 13, 2); -- 2 = puede ver y modificar
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3, 14, 3); -- 3 = puede ver, modificar y agregar
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3, 15, 3);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3, 16, 3);

-- Roles de Dirección de Recursos Humanos(4) en el menú Expedientes
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (4, 13, 4); -- 4 = puede ver, modificar, agregar y eliminar
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (4, 14, 5);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (4, 15, 5);
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (4, 16, 5);
