--
-- Génesis | Tierra
--
-- Roles
--

CREATE TABLE roles (
    id                serial          PRIMARY KEY,
    departamento      integer         REFERENCES departamentos NOT NULL,
    modulo            integer         REFERENCES modulos NOT NULL,
    permiso_maximo    smallint        NOT NULL,
    estatus           character(1)    DEFAULT 'A'::bpchar NOT NULL,
    UNIQUE (departamento, modulo)
);

-- IMPLAN DESARROLLO(1)
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  1, 1); -- sistema
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  2, 5); --   departamentos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  3, 5); --   modulos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  4, 5); --   roles
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  5, 5); --   integrantes
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  6, 5); -- usuarios
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  7, 1); --   autentificaciones
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  8, 1); --   bitacora
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (1,  9, 1); --   sesiones

-- DIR. DE INVESTIGACION ESTRATEGICA(3)
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  1, 1); -- sistema
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  2, 1); --   departamentos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  3, 1); --   modulos
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  4, 1); --   roles
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  5, 1); --   integrantes
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  6, 1); -- usuarios
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  7, 1); --   autentificaciones
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  8, 1); --   bitacora
INSERT INTO roles (departamento, modulo, permiso_maximo) VALUES (3,  9, 1); --   sesiones
