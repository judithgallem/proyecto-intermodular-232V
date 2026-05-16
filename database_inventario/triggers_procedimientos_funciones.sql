USE inventario_db;

DELIMITER //

CREATE TRIGGER evitar_ip_duplicada
BEFORE INSERT ON ips
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM ips
        WHERE ip = NEW.ip
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede insertar una IP duplicada';
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER evitar_ip_duplicada_update
BEFORE UPDATE ON ips
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM ips
        WHERE ip = NEW.ip
        AND id_ip <> OLD.id_ip
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede actualizar a una IP duplicada';
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER evitar_mac_duplicada
BEFORE INSERT ON equipos
FOR EACH ROW
BEGIN
    IF NEW.mac IS NOT NULL AND EXISTS (
        SELECT 1 FROM equipos
        WHERE mac = NEW.mac
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede insertar una MAC duplicada';
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER evitar_mac_duplicada_update
BEFORE UPDATE ON equipos
FOR EACH ROW
BEGIN
    IF NEW.mac IS NOT NULL AND EXISTS (
        SELECT 1 FROM equipos
        WHERE mac = NEW.mac
        AND id_equipo <> OLD.id_equipo
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede actualizar a una MAC duplicada';
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER evitar_borrar_red_con_equipos
BEFORE DELETE ON redes
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM equipos
        WHERE id_red = OLD.id_red
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede borrar una red con equipos asignados';
    END IF;

    IF EXISTS (
        SELECT 1 FROM ips
        WHERE id_red = OLD.id_red
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede borrar una red con IPs asignadas';
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE mostrar_equipos_por_red(IN nombre_red VARCHAR(60))
BEGIN
    DECLARE existe_red INT;

    SELECT COUNT(*) INTO existe_red
    FROM redes
    WHERE nombre = nombre_red;

    IF existe_red = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La red indicada no existe';
    END IF;

    SELECT
        e.id_equipo,
        e.hostname,
        e.dominio,
        e.dhcp,
        e.mac,
        e.sistema_operativo,
        e.servicios,
        r.nombre AS red,
        i.ip AS ip_principal,
        i.mascara
    FROM equipos e
    JOIN redes r ON e.id_red = r.id_red
    LEFT JOIN ips i ON e.id_equipo = i.id_equipo AND i.principal = TRUE
    WHERE r.nombre = nombre_red
    ORDER BY e.hostname;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE cambiar_equipo_red(
    IN nombre_equipo VARCHAR(60),
    IN nueva_red VARCHAR(60)
)
BEGIN
    DECLARE red_id INT;

    IF nombre_equipo = 'ROUTER01-232V' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No es posible cambiar de red al router';
    END IF;

    SELECT id_red INTO red_id
    FROM redes
    WHERE nombre = nueva_red;

    IF red_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La red indicada no existe';
    END IF;

    UPDATE equipos
    SET id_red = red_id
    WHERE hostname = nombre_equipo;

    UPDATE ips i
    JOIN equipos e ON i.id_equipo = e.id_equipo
    SET i.id_red = red_id
    WHERE e.hostname = nombre_equipo
    AND i.principal = TRUE;
END //

DELIMITER ;

DELIMITER //

CREATE FUNCTION contar_equipos_red(nombre_red VARCHAR(60))
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE existe_red INT;
    DECLARE total INT;

    SELECT COUNT(*) INTO existe_red
    FROM redes
    WHERE nombre = nombre_red;

    IF existe_red = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La red indicada no existe';
    END IF;

    SELECT COUNT(*) INTO total
    FROM equipos e
    JOIN redes r ON e.id_red = r.id_red
    WHERE r.nombre = nombre_red;

    RETURN total;
END //

DELIMITER ;

DELIMITER //

CREATE FUNCTION total_disco_equipo(nombre_equipo VARCHAR(60))
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE existe_equipo INT;
    DECLARE total INT;

    SELECT COUNT(*) INTO existe_equipo
    FROM equipos
    WHERE hostname = nombre_equipo;

    IF existe_equipo = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El equipo indicado no existe';
    END IF;

    SELECT IFNULL(SUM(CAST(REPLACE(tamano, ' GB', '') AS UNSIGNED)), 0) INTO total
    FROM hardware h
    JOIN equipos e ON h.id_equipo = e.id_equipo
    WHERE e.hostname = nombre_equipo
    AND h.componente = 'DISCO';

    RETURN total;
END //

DELIMITER ;
