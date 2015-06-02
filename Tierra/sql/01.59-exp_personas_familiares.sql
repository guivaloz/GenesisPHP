--
-- GenesisPHP
--
-- Personas Familiares
--

CREATE TABLE exp_personas_familiares (
    id                   serial                  PRIMARY KEY,
    persona              integer                 REFERENCES exp_personas NOT NULL,

    nombre               character varying(128),
    parentesco           character(1),
    sexo                 character(1)            NOT NULL,
    nacimiento_fecha     date,

    domicilio            character varying(128),
    telefono_movil       character varying(64),

    notas                text,
    etapa                character(1)            DEFAULT 'A'::char NOT NULL,
    estatus              character(1)            DEFAULT 'A'::char NOT NULL
);
