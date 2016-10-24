--
-- ARCHIVO.sql
--

CREATE TABLE xxx_tabla (
    id         serial               PRIMARY KEY,

    nombre     character varying    NOT NULL,

    notas      text,
    estatus    character(1)         DEFAULT 'A'::bpchar NOT NULL
);
