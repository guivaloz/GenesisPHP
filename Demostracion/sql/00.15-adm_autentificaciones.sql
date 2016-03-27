--
-- Smd Administrador Autentificaciones
--

CREATE TABLE adm_autentificaciones (
    usuario    integer                      REFERENCES adm_usuarios,
    fecha      timestamp without time zone  DEFAULT ('now'::text)::timestamp without time zone NOT NULL,
    nom_corto  character varying(16),
    tipo       character(1)                 NOT NULL,
    ip         inet
);

