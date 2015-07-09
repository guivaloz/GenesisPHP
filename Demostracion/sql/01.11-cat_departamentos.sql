--
-- GenesisPHP
--
-- Catálogo Departamentos
--

CREATE TABLE cat_departamentos (
    id         serial                    PRIMARY KEY,

    nombre     character varying(128)    NOT NULL,

    notas      text,
    estatus    character(1)              DEFAULT 'A'::char NOT NULL
);

INSERT INTO cat_departamentos (nombre) VALUES ('Departamento Administrativo'); -- 1
INSERT INTO cat_departamentos (nombre) VALUES ('Departamento Producción');     -- 2
INSERT INTO cat_departamentos (nombre) VALUES ('Departamento Ventas');         -- 3
