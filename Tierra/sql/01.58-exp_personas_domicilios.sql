--
-- GenesisPHP
--
-- Personas Domicilios
--

CREATE TABLE exp_personas_domicilios (
    id               serial                    PRIMARY KEY,
    persona          integer                   REFERENCES exp_personas NOT NULL,

    tipo             character(1)              DEFAULT 'C'::bpchar NOT NULL,

    calle            character varying(128),
    numero           character varying(24),
    entre_calles     character varying(128),
    colonia          character varying(128),
    codigo_postal    integer,
    telefonos        character varying(64),

    inicio           date,
    termino          date,
    estatus          character(1)              DEFAULT 'A'::char NOT NULL
);

-- Tipo
--   C Casa
--   F De un familiar
--   I La de la credencial IFE
--   T Trabajo
--   O Otro
