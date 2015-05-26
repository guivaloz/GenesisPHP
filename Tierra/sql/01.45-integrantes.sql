--
-- Génesis | Tierra
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

-- IMPLAN DESARROLLO(1)
INSERT INTO integrantes (usuario, departamento, poder) VALUES (1,  1, 7); -- Sistema(1)
INSERT INTO integrantes (usuario, departamento, poder) VALUES (2,  1, 7); -- Guillermo(2)
INSERT INTO integrantes (usuario, departamento, poder) VALUES (3,  1, 7); -- Administrador(3)

-- DIR. DE INVESTIGACION ESTRATEGICA(3)
INSERT INTO integrantes (usuario, departamento, poder) VALUES (4,  3, 7); -- Usuario(3)
