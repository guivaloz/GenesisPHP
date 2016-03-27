--
-- GenesisPHP
--
-- Catálogo Puestos
--

CREATE TABLE cat_puestos (
    id         serial                    PRIMARY KEY,

    nombre     character varying(128)    NOT NULL,

    notas      text,
    estatus    character(1)              DEFAULT 'A'::char NOT NULL
);

INSERT INTO cat_puestos (nombre) VALUES ('Gerente');  -- 1
INSERT INTO cat_puestos (nombre) VALUES ('Empleado'); -- 2
INSERT INTO cat_puestos (nombre) VALUES ('Vendedor'); -- 3
INSERT INTO cat_puestos (nombre) VALUES ('Limpieza'); -- 4

