--
-- GenesisPHP
--
-- Sesiones
--

CREATE TABLE sesiones (
    usuario           integer                     REFERENCES usuarios PRIMARY KEY,
    ingreso           timestamp without time zone NOT NULL,
    nombre            character varying(128)      NOT NULL,
    nom_corto         character varying(16)       NOT NULL,
    tipo              character(1)                NOT NULL,
    listado_renglones smallint                    DEFAULT 25 NOT NULL
);
