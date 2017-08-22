-- Ejemplo función recursiva

CREATE OR REPLACE FUNCTION concatenartelefonospersona(v_id_persona bigint, v_min_id_telefono bigint) RETURNS VARCHAR
    AS $$ 
    DECLARE v_id_tel BIGINT; v_cant_tels INTEGER; 
    BEGIN 
    SELECT MIN(tel0.id_telefono), COUNT(*)
      INTO v_id_tel, v_cant_tels
      FROM eaprender.telefonos tel0 
     WHERE tel0.id_persona = v_id_persona 
       AND tel0.id_telefono > COALESCE(v_min_id_telefono, 0);
       
    RETURN (SELECT tels.nro_telefono::VARCHAR 
              || CASE WHEN v_cant_tels > 1 
                      THEN '; ' || concatenartelefonospersona(v_id_persona, v_id_tel) 
                      ELSE '' END 
         FROM eaprender.telefonos tels 
        WHERE tels.id_telefono = v_id_tel); END $$
    LANGUAGE plpgsql
    
SELECT *, concatenarTelefonosPersona(pers.id_persona::BIGINT, NULL) telefonos_concatenados FROM eaprender.personas pers