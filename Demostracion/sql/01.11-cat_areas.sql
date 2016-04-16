--
-- GenesisPHP
--
-- Catálogo Áreas
--

CREATE TABLE cat_areas (
    id         serial                    PRIMARY KEY,

    nombre     character varying(128)    NOT NULL,

    notas      text,
    estatus    character(1)              DEFAULT 'A'::char NOT NULL
);

INSERT INTO cat_areas (nombre) VALUES ('Área Administrativa'); -- 1
INSERT INTO cat_areas (nombre) VALUES ('Área Producción');     -- 2
INSERT INTO cat_areas (nombre) VALUES ('Área Ventas');         -- 3

