INSERT INTO eaprender.tipos_documentos(nombre) VALUES ('DNI'), ('LC');

INSERT INTO eaprender.paises(nombre)
VALUES ('ARGENTINA'), ('BRASIL'), ('PARAGUAY');

INSERT INTO eaprender.provincias(nombre, id_pais)
VALUES ('MISIONES', 1), ('CORRIENTES', 1), ('MISIONES', 3);

INSERT INTO eaprender.localidades(nombre, id_provincia)
VALUES ('PUETO IGUAZÚ', 1), ('PUERTO ESPERANZA', 1);

INSERT INTO eaprender.tipos_telefonos(nombre, numero, interno)
VALUES ('PRINCIPAL', TRUE, FALSE), ('EMPRESARIAL', TRUE, TRUE), ('FIJO', TRUE, FALSE), ('MOVIL', TRUE, FALSE);
