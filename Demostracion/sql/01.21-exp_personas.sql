--
-- GenesisPHP
--
-- Expedientes Personas
--

CREATE TABLE exp_personas (
    id                   serial                  PRIMARY KEY,
    area                 integer                 REFERENCES cat_areas   NOT NULL,
    puesto               integer                 REFERENCES cat_puestos NOT NULL,

    nombres              character varying(128),
    apellido_paterno     character varying(128),
    apellido_materno     character varying(128),
    nombre_completo      character varying(256)  NOT NULL,

    nacimiento_fecha     date,
    sexo                 character(1)            NOT NULL,
    estado_civil         character(1)            NOT NULL,
    curp                 character(18),

    nomina               integer                 UNIQUE,
    ingreso_fecha        date,

    notas                text,
    estatus              character(1)            DEFAULT 'A'::char NOT NULL
);

-- Sexo
--   M Masculino
--   F Femenino

-- Estado Civil
--   S Soltero
--   C Casado
--   D Divorciado
--   U Unión libre
--   V Viudo

