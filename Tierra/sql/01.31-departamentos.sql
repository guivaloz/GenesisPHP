--
-- Génesis | Tierra
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

INSERT INTO departamentos (nombre, clave) VALUES ('IMPLAN DESARROLLO',                     'DESARR'); -- 1
INSERT INTO departamentos (nombre, clave) VALUES ('DIRECTOR GENERAL EJECUTIVO',            'DIRGEN'); -- 2
INSERT INTO departamentos (nombre, clave) VALUES ('DIR. DE INVESTIGACION ESTRATEGICA',     'INVEST'); -- 3
INSERT INTO departamentos (nombre, clave) VALUES ('DIR. DE PLANEACION URBANA SUSTENTABLE', 'PLAURS'); -- 4
INSERT INTO departamentos (nombre, clave) VALUES ('DIR. DE PROYECTOS ESTRATEGICOS',        'PRYEST'); -- 5
INSERT INTO departamentos (nombre, clave) VALUES ('DIR. DE COMPETITIVIDAD SECTORIAL',      'COMSEC'); -- 6
INSERT INTO departamentos (nombre, clave) VALUES ('COORDINACION ADMINISTRATIVA',           'COOADM'); -- 7
INSERT INTO departamentos (nombre, clave) VALUES ('COORDINACION JURIDICA',                 'COOJUR'); -- 8
