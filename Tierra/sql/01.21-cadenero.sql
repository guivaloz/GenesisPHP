--
-- Génesis | Tierra
--
-- Cadenero
--

CREATE TABLE cadenero (
    usuario    integer                      REFERENCES sesiones                                 NOT NULL,
    form_name  character varying                                                                NOT NULL,
    clave      character varying            UNIQUE                                              NOT NULL,
    creado     timestamp without time zone  DEFAULT ('now'::text)::timestamp without time zone  NOT NULL,
    recibido   boolean                      DEFAULT false                                       NOT NULL
);
