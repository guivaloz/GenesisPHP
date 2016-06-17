--
-- GenesisPHP Roles
--

CREATE TABLE adm_roles (
    id              serial        PRIMARY KEY,

    departamento    integer       REFERENCES adm_departamentos NOT NULL,
    modulo          integer       REFERENCES adm_modulos       NOT NULL,
    permiso_maximo  smallint      NOT NULL,

    estatus         character(1)  DEFAULT 'A'::bpchar          NOT NULL,
    UNIQUE (departamento, modulo)
);

-- Desarrolladores
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  1, 5); -- Sistema
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  2, 5); --   Usuarios
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  3, 1); --   Autentificaciones
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  4, 1); --   Bitácora
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  5, 1); --   Sesiones
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  6, 5); --   Departamentos
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  7, 5); --   Módulos
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  8, 5); --   Roles
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  9, 5); --   Integrantes
