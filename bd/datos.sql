INSERT INTO eaprender.tipos_documentos(nombre) VALUES ('DNI'), ('LC');

INSERT INTO eaprender.paises(nombre)
VALUES ('ARGENTINA'), ('BRASIL'), ('PARAGUAY');

INSERT INTO eaprender.provincias(nombre, id_pais)
VALUES ('MISIONES', 1), ('CORRIENTES', 1), ('MISIONES', 3);

INSERT INTO eaprender.localidades(nombre, id_provincia)
VALUES ('PUETO IGUAZÚ', 1), ('PUERTO ESPERANZA', 1);
