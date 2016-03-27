--
-- Smd Administrador Integrantes
--

CREATE TABLE adm_integrantes (
    id            serial        PRIMARY KEY,

    usuario       integer       REFERENCES adm_usuarios      NOT NULL,
    departamento  integer       REFERENCES adm_departamentos NOT NULL,
    poder         smallint      DEFAULT 1                    NOT NULL,

    estatus       character(1)  DEFAULT 'A'::bpchar          NOT NULL,
    UNIQUE (usuario, departamento)
);

-- Integrantes
INSERT INTO adm_integrantes (usuario, departamento, poder) VALUES (1, 1, 7); -- Sistema(1)
INSERT INTO adm_integrantes (usuario, departamento, poder) VALUES (2, 1, 7); -- Guillermo(2)
INSERT INTO adm_integrantes (usuario, departamento, poder) VALUES (3, 1, 6); -- Administrador(3)

