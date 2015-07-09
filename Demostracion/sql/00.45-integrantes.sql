--
-- GenesisPHP
--
-- Integrantes
--

CREATE TABLE integrantes (
    id              serial          PRIMARY KEY,
    usuario         integer         REFERENCES usuarios NOT NULL,
    departamento    integer         REFERENCES departamentos NOT NULL,
    poder           smallint        DEFAULT 1 NOT NULL,
    estatus         character(1)    DEFAULT 'A'::bpchar NOT NULL,
    UNIQUE (usuario, departamento)
);

-- Sin integrantes de Desarrollo de Sistemas(1)
INSERT INTO integrantes (usuario, departamento, poder) VALUES (1, 1, 7); -- Sistema(1)
INSERT INTO integrantes (usuario, departamento, poder) VALUES (2, 1, 7); -- Administrador(2)
