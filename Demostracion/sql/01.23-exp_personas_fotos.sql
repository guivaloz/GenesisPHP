--
-- GenesisPHP
--
-- Expedientes Personas Fotos
--

CREATE TABLE exp_personas_fotos (
    id                 serial                         PRIMARY KEY,
    persona            integer                        REFERENCES exp_personas NOT NULL,

    caracteres_azar    character varying(8)           NOT NULL,

    creado             timestamp without time zone    DEFAULT ('now'::text)::timestamp without time zone NOT NULL,
    estatus            character(1)                   DEFAULT 'A'::char NOT NULL
);

