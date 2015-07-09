--
-- GenesisPHP
--
-- Departamentos
--

CREATE TABLE departamentos (
    id         serial                   PRIMARY KEY,

    nombre     character varying        NOT NULL,
    clave      character varying(16)    UNIQUE NOT NULL,

    notas      text,
    estatus    character(1)             DEFAULT 'A'::bpchar NOT NULL
);

INSERT INTO departamentos (nombre, clave) VALUES ('Desarrollo de Sistemas',        'DesSis'); -- 1
INSERT INTO departamentos (nombre, clave) VALUES ('Dirección General',             'DirGen'); -- 2
INSERT INTO departamentos (nombre, clave) VALUES ('Dirección Administrativa',      'Admin');  -- 3
INSERT INTO departamentos (nombre, clave) VALUES ('Dirección de Recursos Humanos', 'RecHum'); -- 4
