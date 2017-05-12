
CREATE SEQUENCE eaprender.tipos_documentos_id_tipodocumento_seq;

CREATE TABLE eaprender.tipos_documentos (
                id_tipodocumento BIGINT NOT NULL DEFAULT nextval('eaprender.tipos_documentos_id_tipodocumento_seq'),
                nombre VARCHAR NOT NULL,
                CONSTRAINT tipos_documentos_pk PRIMARY KEY (id_tipodocumento)
);


ALTER SEQUENCE eaprender.tipos_documentos_id_tipodocumento_seq OWNED BY eaprender.tipos_documentos.id_tipodocumento;

CREATE SEQUENCE eaprender.personas_id_persona_seq;

CREATE TABLE eaprender.personas (
                id_persona BIGINT NOT NULL DEFAULT nextval('eaprender.personas_id_persona_seq'),
                id_tipodocumento BIGINT NOT NULL,
                nro_documento INTEGER NOT NULL,
                nombres VARCHAR NOT NULL,
                apellidos VARCHAR NOT NULL,
                CONSTRAINT personas_pk PRIMARY KEY (id_persona)
);


ALTER SEQUENCE eaprender.personas_id_persona_seq OWNED BY eaprender.personas.id_persona;

ALTER TABLE eaprender.personas ADD CONSTRAINT tipos_documentos_personas_fk
FOREIGN KEY (id_tipodocumento)
REFERENCES eaprender.tipos_documentos (id_tipodocumento)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;
