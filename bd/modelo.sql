
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
                nombre VARCHAR NOT NULL,
                interno VARCHAR NOT NULL,
                nro_telefono VARCHAR NOT NULL,
                CONSTRAINT telefonos_pk PRIMARY KEY (id_telefono)
);


ALTER SEQUENCE eaprender.telefonos_id_telefono_seq OWNED BY eaprender.telefonos.id_telefono;

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
