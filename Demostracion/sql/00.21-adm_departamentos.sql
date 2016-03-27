--
-- Smd Administrador Departamentos
--

CREATE TABLE adm_departamentos (
    id       serial                 PRIMARY KEY,

    nombre   character varying      NOT NULL,
    clave    character varying(16)  UNIQUE NOT NULL,

    notas    text,
    estatus  character(1)           DEFAULT 'A'::bpchar NOT NULL
);

-- Departamento Desarrolladores
INSERT INTO adm_departamentos (nombre, clave) VALUES ('Desarrollo de Sistemas',        'SIS'); -- 1
INSERT INTO adm_departamentos (nombre, clave) VALUES ('Dirección General',             'GEN'); -- 2
INSERT INTO adm_departamentos (nombre, clave) VALUES ('Dirección Administrativa',      'ADM'); -- 3
INSERT INTO adm_departamentos (nombre, clave) VALUES ('Dirección de Recursos Humanos', 'RHU'); -- 4

