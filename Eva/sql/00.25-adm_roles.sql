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
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  1, 5); -- usuarios
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  2, 1); --   autentificaciones
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  3, 1); --   bitácora
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  4, 1); --   sesiones
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  5, 5); --   departamentos
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  6, 5); --   módulos
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  7, 5); --   roles
INSERT INTO adm_roles (departamento, modulo, permiso_maximo) VALUES (1,  8, 5); --   integrantes

