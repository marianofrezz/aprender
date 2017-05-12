DROP SCHEMA eaprender CASCADE;

CREATE SCHEMA eaprender;

CREATE SEQUENCE eaprender.tipos_telefonos_id_tipotelefono_seq_1;

CREATE TABLE eaprender.tipos_telefonos (
                id_tipotelefono INTEGER NOT NULL DEFAULT nextval('eaprender.tipos_telefonos_id_tipotelefono_seq_1'),
                nombre VARCHAR NOT NULL,
                numero BOOLEAN NOT NULL,
                interno BOOLEAN NOT NULL,
                CONSTRAINT tipos_telefonos_pk PRIMARY KEY (id_tipotelefono)
);


ALTER SEQUENCE eaprender.tipos_telefonos_id_tipotelefono_seq_1 OWNED BY eaprender.tipos_telefonos.id_tipotelefono;

CREATE SEQUENCE eaprender.paises_id_pais_seq;

CREATE TABLE eaprender.paises (
                id_pais INTEGER NOT NULL DEFAULT nextval('eaprender.paises_id_pais_seq'),
                nombre VARCHAR NOT NULL,
                CONSTRAINT paises_pk PRIMARY KEY (id_pais)
);


ALTER SEQUENCE eaprender.paises_id_pais_seq OWNED BY eaprender.paises.id_pais;

CREATE UNIQUE INDEX paises_idx
 ON eaprender.paises
 ( nombre );

CREATE SEQUENCE eaprender.provincias_id_provincia_seq;

CREATE TABLE eaprender.provincias (
                id_provincia BIGINT NOT NULL DEFAULT nextval('eaprender.provincias_id_provincia_seq'),
                id_pais INTEGER NOT NULL,
                nombre VARCHAR NOT NULL,
                CONSTRAINT provincias_pk PRIMARY KEY (id_provincia)
);


ALTER SEQUENCE eaprender.provincias_id_provincia_seq OWNED BY eaprender.provincias.id_provincia;

CREATE SEQUENCE eaprender.localidades_id_localidad_seq_1;

CREATE TABLE eaprender.localidades (
                id_localidad BIGINT NOT NULL DEFAULT nextval('eaprender.localidades_id_localidad_seq_1'),
                id_provincia BIGINT NOT NULL,
                nombre VARCHAR NOT NULL,
                CONSTRAINT localidades_pk PRIMARY KEY (id_localidad)
);


ALTER SEQUENCE eaprender.localidades_id_localidad_seq_1 OWNED BY eaprender.localidades.id_localidad;

CREATE SEQUENCE eaprender.tipos_documentos_id_tipodocumento_seq_1;

CREATE TABLE eaprender.tipos_documentos (
                id_tipodocumento INTEGER NOT NULL DEFAULT nextval('eaprender.tipos_documentos_id_tipodocumento_seq_1'),
                nombre VARCHAR NOT NULL,
                CONSTRAINT tipos_documentos_pk PRIMARY KEY (id_tipodocumento)
);


ALTER SEQUENCE eaprender.tipos_documentos_id_tipodocumento_seq_1 OWNED BY eaprender.tipos_documentos.id_tipodocumento;

CREATE SEQUENCE eaprender.personas_id_persona_seq;

CREATE TABLE eaprender.personas (
                id_persona BIGINT NOT NULL DEFAULT nextval('eaprender.personas_id_persona_seq'),
                id_tipodocumento INTEGER NOT NULL,
                nro_documento VARCHAR NOT NULL,
                apellidos VARCHAR NOT NULL,
                nombres VARCHAR NOT NULL,
                id_localidad BIGINT NOT NULL,
                CONSTRAINT personas_pk PRIMARY KEY (id_persona)
);


ALTER SEQUENCE eaprender.personas_id_persona_seq OWNED BY eaprender.personas.id_persona;

CREATE SEQUENCE eaprender.legajos_id_legajo_seq;

CREATE TABLE eaprender.legajos (
                id_legajo BIGINT NOT NULL DEFAULT nextval('eaprender.legajos_id_legajo_seq'),
                id_persona BIGINT NOT NULL,
                nro_legajo VARCHAR NOT NULL,
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
                CONSTRAINT legajos_pk PRIMARY KEY (id_legajo)
);


ALTER SEQUENCE eaprender.legajos_id_legajo_seq OWNED BY eaprender.legajos.id_legajo;

CREATE SEQUENCE eaprender.inscripciones_id_inscripcion_seq;

CREATE TABLE eaprender.inscripciones (
                id_inscripcion BIGINT NOT NULL DEFAULT nextval('eaprender.inscripciones_id_inscripcion_seq'),
                id_legajo BIGINT NOT NULL,
                fecha_inscripcion TIMESTAMP NOT NULL,
                anio_cursado VARCHAR NOT NULL,
                CONSTRAINT inscripciones_pk PRIMARY KEY (id_inscripcion)
);


ALTER SEQUENCE eaprender.inscripciones_id_inscripcion_seq OWNED BY eaprender.inscripciones.id_inscripcion;

CREATE SEQUENCE eaprender.telefonos_id_telefono_seq;

CREATE TABLE eaprender.telefonos (
                id_telefono BIGINT NOT NULL DEFAULT nextval('eaprender.telefonos_id_telefono_seq'),
                id_persona BIGINT NOT NULL,
                interno VARCHAR,
                nro_telefono VARCHAR NOT NULL,
                id_tipotelefono INTEGER NOT NULL,
                CONSTRAINT telefonos_pk PRIMARY KEY (id_telefono)
);


ALTER SEQUENCE eaprender.telefonos_id_telefono_seq OWNED BY eaprender.telefonos.id_telefono;

ALTER TABLE eaprender.telefonos ADD CONSTRAINT tipos_telefonos_telefonos_fk
FOREIGN KEY (id_tipotelefono)
REFERENCES eaprender.tipos_telefonos (id_tipotelefono)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE eaprender.provincias ADD CONSTRAINT paises_provincias_fk
FOREIGN KEY (id_pais)
REFERENCES eaprender.paises (id_pais)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE eaprender.localidades ADD CONSTRAINT provincias_localidades_fk
FOREIGN KEY (id_provincia)
REFERENCES eaprender.provincias (id_provincia)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE eaprender.personas ADD CONSTRAINT localidades_personas_fk
FOREIGN KEY (id_localidad)
REFERENCES eaprender.localidades (id_localidad)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE eaprender.personas ADD CONSTRAINT tipos_documentos_personas_fk
FOREIGN KEY (id_tipodocumento)
REFERENCES eaprender.tipos_documentos (id_tipodocumento)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE eaprender.telefonos ADD CONSTRAINT personas_telefonos_fk
FOREIGN KEY (id_persona)
REFERENCES eaprender.personas (id_persona)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE eaprender.legajos ADD CONSTRAINT personas_legajos_fk
FOREIGN KEY (id_persona)
REFERENCES eaprender.personas (id_persona)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE eaprender.inscripciones ADD CONSTRAINT legajos_inscripciones_fk
FOREIGN KEY (id_legajo)
REFERENCES eaprender.legajos (id_legajo)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;
