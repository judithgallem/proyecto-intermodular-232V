CREATE DATABASE IF NOT EXISTS inventario_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_spanish_ci;
USE inventario_db;

CREATE TABLE parametros (
    parametro VARCHAR(80) PRIMARY KEY,
    valor VARCHAR(255) NOT NULL
);

CREATE TABLE redes (
    id_red INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(60) NOT NULL UNIQUE,
    direccion_red VARCHAR(18) NOT NULL,
    gateway VARCHAR(15),
    interfaz_router VARCHAR(30),
    descripcion VARCHAR(255)
);

CREATE TABLE equipos (
    id_equipo INT AUTO_INCREMENT PRIMARY KEY,
    hostname VARCHAR(60) NOT NULL UNIQUE,
    dominio VARCHAR(120),
    dhcp BOOLEAN NOT NULL DEFAULT FALSE,
    mac VARCHAR(17) UNIQUE,
    id_red INT NOT NULL,
    sistema_operativo VARCHAR(80),
    servicios VARCHAR(255),
    CONSTRAINT fk_equipos_redes FOREIGN KEY (id_red) REFERENCES redes(id_red)
        ON UPDATE CASCADE
);

CREATE TABLE ips (
    id_ip INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    id_red INT NOT NULL,
    ip VARCHAR(15) NOT NULL UNIQUE,
    mascara VARCHAR(15) NOT NULL,
    interfaz VARCHAR(30),
    principal BOOLEAN NOT NULL DEFAULT FALSE,
    CONSTRAINT fk_ips_equipos FOREIGN KEY (id_equipo) REFERENCES equipos(id_equipo)
        ON UPDATE CASCADE,
    CONSTRAINT fk_ips_redes FOREIGN KEY (id_red) REFERENCES redes(id_red)
        ON UPDATE CASCADE
);

CREATE TABLE hardware (
    id_hardware INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    componente VARCHAR(50) NOT NULL,
    tamano VARCHAR(50) NOT NULL,
    CONSTRAINT fk_hardware_equipos FOREIGN KEY (id_equipo) REFERENCES equipos(id_equipo)
        ON UPDATE CASCADE
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    nombre VARCHAR(50),
    apellidos VARCHAR(100),
    id_equipo INT,
    tipo_usuario ENUM('DOMINIO','LOCAL','APP') NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    CONSTRAINT fk_usuarios_equipos FOREIGN KEY (id_equipo) REFERENCES equipos(id_equipo)
        ON UPDATE CASCADE,
    UNIQUE (usuario, id_equipo)
);

CREATE TABLE accesos (
    id_acceso INT AUTO_INCREMENT PRIMARY KEY,
    id_equipo INT NOT NULL,
    id_usuario INT NOT NULL,
    tipo_acceso VARCHAR(50) NOT NULL,
    CONSTRAINT fk_accesos_equipos FOREIGN KEY (id_equipo) REFERENCES equipos(id_equipo)
        ON UPDATE CASCADE,
    CONSTRAINT fk_accesos_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE,
    UNIQUE (id_equipo, id_usuario, tipo_acceso)
);
