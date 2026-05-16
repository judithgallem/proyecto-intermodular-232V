USE inventario_db;
SET NAMES utf8mb4;

INSERT INTO parametros (parametro, valor) VALUES
('Alumno', 'Judith Gallego Martínez'),
('AlumnoAD', 'Judith.gallego'),
('Puesto de clase', '23'),
('Identificador de proyecto', '232V'),
('Servidor de virtualización', '31'),
('Grupo en el servidor', '8'),
('Nombre de dominio AD', 'asi-232V.cifpaviles.com'),
('Nombre de dominio NETBIOS', 'ASI-232V'),
('Nombre de reino kerberos', 'ASI-232V.CIFPAVILES.COM'),
('Dirección IP pública del router', '10.31.8.254');

INSERT INTO redes (nombre, direccion_red, gateway, interfaz_router, descripcion) VALUES
('EXTERNA_SUMARIZADA', '10.0.0.0/8', NULL, NULL, 'Red externa intranet del centro'),
('EXTERNA_SUBRED', '10.31.8.0/24', '10.31.8.1', 'eth0', 'Subred externa que actúa como internet del centro. IP externa del router: 10.31.8.254'),
('DMZ', '172.16.23.0/24', '172.16.23.1', 'eth1', 'Red aislada para servidores con servicios publicados'),
('SERVIDORES_INTERNOS', '172.20.23.0/24', '172.20.23.1', 'eth2', 'Controladores de dominio y servicios internos'),
('ALMACENAMIENTO', '172.22.23.0/24', '172.22.23.1', 'eth3', 'Red dedicada para servidor de archivos y datastores'),
('GESTION', '172.24.23.0/24', '172.24.23.1', 'eth4', 'Red de gestión para ESXi, vCenter y administración'),
('ESTACIONES', '172.28.23.0/24', '172.28.23.1', 'eth5', 'Red de estaciones de usuario'),
('VPN', '172.31.23.0/24', '172.31.23.1', 'tun0', 'Clientes VPN asignados mediante interfaz virtual tun0');

INSERT INTO equipos (hostname, dominio, dhcp, mac, id_red, sistema_operativo, servicios) VALUES
('ROUTER01-232V', 'WORKGROUP', FALSE, '00:50:56:be:bd:87', (SELECT id_red FROM redes WHERE nombre = 'EXTERNA_SUBRED'), 'Ubuntu Linux', 'Gateway, NAT, DHCP, VPN, Firewall'),
('LAMP01-232V', 'WORKGROUP', FALSE, '00:50:56:be:ce:d4', (SELECT id_red FROM redes WHERE nombre = 'DMZ'), 'Ubuntu Server 24.04', 'Apache2, MySQL, PHP, FTP'),
('DC01-232V', 'asi-232V.cifpaviles.com', FALSE, '00:50:56:be:30:10', (SELECT id_red FROM redes WHERE nombre = 'SERVIDORES_INTERNOS'), 'Windows Server 2019', 'AD DS, DNS, DHCP'),
('DC02-232V', 'asi-232V.cifpaviles.com', FALSE, '00:50:56:be:4f:b5', (SELECT id_red FROM redes WHERE nombre = 'SERVIDORES_INTERNOS'), 'Windows Server 2022', 'AD DS, DNS'),
('FILER02-232V', 'asi-232V.cifpaviles.com', FALSE, '00:50:56:be:60:c7', (SELECT id_red FROM redes WHERE nombre = 'ALMACENAMIENTO'), 'TrueNAS', 'SMB, NFS, iSCSI'),
('VCENTER01-232V', 'asi-232V.cifpaviles.com', FALSE, NULL, (SELECT id_red FROM redes WHERE nombre = 'GESTION'), 'vCenter Appliance', 'vCenter Server'),
('HV01-232V', 'asi-232V.cifpaviles.com', FALSE, '00:50:56:be:0a:88', (SELECT id_red FROM redes WHERE nombre = 'GESTION'), 'VMware ESXi', 'ESXi Hypervisor'),
('HV02-232V', 'asi-232V.cifpaviles.com', FALSE, '00:50:56:be:17:64', (SELECT id_red FROM redes WHERE nombre = 'GESTION'), 'VMware ESXi', 'ESXi Hypervisor'),
('WS01-232V', 'asi-232V.cifpaviles.com', TRUE, '00:50:56:be:01:8d', (SELECT id_red FROM redes WHERE nombre = 'ESTACIONES'), 'Windows 10 Pro', 'Cliente de dominio, VPN'),
('WS02-232V', 'asi-232V.cifpaviles.com', TRUE, '00:50:56:be:01:ef', (SELECT id_red FROM redes WHERE nombre = 'ESTACIONES'), 'Windows 11 Pro', 'Cliente de dominio'),
('WS03-232V', 'asi-232V.cifpaviles.com', TRUE, '00:50:56:be:0d:30', (SELECT id_red FROM redes WHERE nombre = 'ESTACIONES'), 'Ubuntu Desktop 24.04', 'Cliente de red'),
('EXTERNA01-232V', 'WORKGROUP', FALSE, '00:50:56:be:37:75', (SELECT id_red FROM redes WHERE nombre = 'EXTERNA_SUBRED'), 'Windows 10', 'Cliente externo, OpenVPN');

INSERT INTO ips (id_equipo, id_red, ip, mascara, interfaz, principal) VALUES
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'EXTERNA_SUBRED'), '10.31.8.254', '255.255.255.0', 'eth0', TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'DMZ'), '172.16.23.1', '255.255.255.0', 'eth1', FALSE),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'SERVIDORES_INTERNOS'), '172.20.23.1', '255.255.255.0', 'eth2', FALSE),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'ALMACENAMIENTO'), '172.22.23.1', '255.255.255.0', 'eth3', FALSE),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'GESTION'), '172.24.23.1', '255.255.255.0', 'eth4', FALSE),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'ESTACIONES'), '172.28.23.1', '255.255.255.0', 'eth5', FALSE),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'VPN'), '172.31.23.1', '255.255.255.0', 'tun0', FALSE),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), (SELECT id_red FROM redes WHERE nombre = 'DMZ'), '172.16.23.90', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC01-232V'), (SELECT id_red FROM redes WHERE nombre = 'SERVIDORES_INTERNOS'), '172.20.23.19', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC02-232V'), (SELECT id_red FROM redes WHERE nombre = 'SERVIDORES_INTERNOS'), '172.20.23.22', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'FILER02-232V'), (SELECT id_red FROM redes WHERE nombre = 'ALMACENAMIENTO'), '172.22.23.10', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'VCENTER01-232V'), (SELECT id_red FROM redes WHERE nombre = 'GESTION'), '172.24.23.211', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV01-232V'), (SELECT id_red FROM redes WHERE nombre = 'GESTION'), '172.24.23.201', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV02-232V'), (SELECT id_red FROM redes WHERE nombre = 'GESTION'), '172.24.23.202', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS01-232V'), (SELECT id_red FROM redes WHERE nombre = 'ESTACIONES'), '172.28.23.10', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS02-232V'), (SELECT id_red FROM redes WHERE nombre = 'ESTACIONES'), '172.28.23.11', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS03-232V'), (SELECT id_red FROM redes WHERE nombre = 'ESTACIONES'), '172.28.23.24', '255.255.255.0', NULL, TRUE),
((SELECT id_equipo FROM equipos WHERE hostname = 'EXTERNA01-232V'), (SELECT id_red FROM redes WHERE nombre = 'EXTERNA_SUBRED'), '10.31.8.253', '255.255.255.0', NULL, TRUE);

INSERT INTO hardware (id_equipo, componente, tamano) VALUES
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), 'CPU', '2 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), 'RAM', '2 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), 'DISCO', '25 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'CPU', '2 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'RAM', '4 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'DISCO', '25 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'DISCO', '25 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'DISCO', '5 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'DISCO', '5 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'DISCO', '5 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC01-232V'), 'CPU', '1 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC01-232V'), 'RAM', '3 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC01-232V'), 'DISCO', '40 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC01-232V'), 'DISCO', '10 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC02-232V'), 'CPU', '1 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC02-232V'), 'RAM', '3 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC02-232V'), 'DISCO', '40 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'DC02-232V'), 'DISCO', '40 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'FILER02-232V'), 'CPU', '1 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'FILER02-232V'), 'RAM', '2 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'FILER02-232V'), 'DISCO', '16 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'FILER02-232V'), 'DISCO', '50 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV01-232V'), 'CPU', '2 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV01-232V'), 'RAM', '10 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV01-232V'), 'DISCO', '40 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV01-232V'), 'DISCO', '50 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV01-232V'), 'DISCO', '50 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV02-232V'), 'CPU', '2 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV02-232V'), 'RAM', '4 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV02-232V'), 'DISCO', '40 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV02-232V'), 'DISCO', '50 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'HV02-232V'), 'DISCO', '50 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS01-232V'), 'CPU', '1 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS01-232V'), 'RAM', '3 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS01-232V'), 'DISCO', '40 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS02-232V'), 'CPU', '2 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS02-232V'), 'RAM', '4 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS02-232V'), 'DISCO', '52 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS03-232V'), 'CPU', '2 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS03-232V'), 'RAM', '3 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS03-232V'), 'DISCO', '50 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'WS03-232V'), 'DISCO', '10 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'EXTERNA01-232V'), 'CPU', '1 CPU'),
((SELECT id_equipo FROM equipos WHERE hostname = 'EXTERNA01-232V'), 'RAM', '3 GB'),
((SELECT id_equipo FROM equipos WHERE hostname = 'EXTERNA01-232V'), 'DISCO', '40 GB');

INSERT INTO usuarios (usuario, nombre, apellidos, id_equipo, tipo_usuario, password_hash) VALUES
('andres', 'Andrés', 'Marina', NULL, 'DOMINIO', '$2y$10$VIIIAHjKE1Yqco8fqUIatewzhrb6R03MxmtmaskQd3ZaG85/tVfuq'),
('manuel', 'Jose Manuel', 'Arabia', NULL, 'DOMINIO', '$2y$10$q2n0bKnc6r68pOgrGagzteXtpslf2h55MRgML8qHWqZpeCV8ii7ry'),
('luisma', 'Luisma', 'Alvarez', NULL, 'DOMINIO', '$2y$10$A9RvNQypLSaZohaRqafAGu.Wu3l4a3Z8l/GdEhvB3PXROVWev85o2'),
('toni', 'Antonio', 'Matachana', NULL, 'DOMINIO', '$2y$10$6EhbSR/6JTaFX2a08tEViu8sMq/4oxLeDbsmQ/DmC1AZmjR7vbK4a'),
('guillermo', 'Guillermo', 'Gonzalez', NULL, 'DOMINIO', '$2y$10$T866Hqa.7lkywktOMIvC7uTn80j8duEA3nAyM1Xc58S/IqgQx/x2G'),
('ruben', 'Ruben', 'Pardiño', NULL, 'DOMINIO', '$2y$10$ISaxSpeD9oX6Ez.m/ieF6uZWHvFQWvdSsNlYmjVfQl3veHJRONERC'),
('luis', 'Luis', 'Leston', NULL, 'DOMINIO', '$2y$10$tl0XldVhVJFFHUtBqsWXk.zsl5sCt0ia5l2zoQay9XdOP0TDAB7GS'),
('olga', 'Olga', 'Hevia', NULL, 'DOMINIO', '$2y$10$9VJlb5DzHLP1CZV9BhCiEe9yQeAfdV7R2R6.3rNjAxanbHok9Jq36'),
('manu', 'Manuel', 'Varo', NULL, 'DOMINIO', '$2y$10$HvobGpCWF290gkjaJkXuPe8BTY8T1uYshajf/is6nCZWXDpKdoQ8e'),
('victor', 'Victor Manuel', 'Rubio', NULL, 'DOMINIO', '$2y$10$InliG5t0Szn4hdBQpSJ.NuLOGQlyI1yPKFM/Bwonqx6GBB1PEfVfK'),
('judith', 'Judith', 'Gallego Martinez', NULL, 'DOMINIO', '$2y$10$OhPatOdSsYfciD1aClADV.oRoOpMvC/2xw4Ar/K5R9BUfvE81ZL5G');

INSERT INTO usuarios (usuario, nombre, apellidos, id_equipo, tipo_usuario, password_hash) VALUES
('root', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'FILER02-232V'), 'LOCAL', '$2y$10$uwGY.bg1oKKqCbRTgBqS3ucF6DLteHglD/NQznvxWMxGHaplaKw2m'),
('administrador', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'LOCAL', '$2y$10$32IWcsOynMZGZf7l9xRZb.tKqmZjyYbbKjqrsZ.ad8Myirh7B5jvG'),
('admin', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'EXTERNA01-232V'), 'LOCAL', '$2y$10$eA0p05AKCHBdMOvyE8iccObBTc3ddfQo7vFZJBtwQhDOa1kS3HIsu'),
('admin', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'WS01-232V'), 'LOCAL', '$2y$10$2qTA/xyv/mGiehDTfvLdjucZ4rsxVRGlMZT1RvfdLCyS8YJ9eIFgS'),
('admin', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'WS02-232V'), 'LOCAL', '$2y$10$Tbe5Mfir4IncvGR2ncC9NOMJ9zq7KFiGRpt4se7EWnh.Y.Qpn2mHe'),
('administrador', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'WS03-232V'), 'LOCAL', '$2y$10$D5ZHz7AE4jdLmlgYrbrTJuxiYIYsQLOYkMtiqQT6rFwGkz21kf4.e'),
('administrator', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'DC01-232V'), 'LOCAL', '$2y$10$8YDGJ3FdJ3sbs02ukb/cHeaWTCJcmXVDPKDCIy3CCYE1oC6xGwMHa'),
('administrator', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'DC02-232V'), 'LOCAL', '$2y$10$B3HxHyseo3.UfRjXNb4tMeKpKnQagS1/mFLg2eQZLMrMFxNN8sIUm'),
('administrador', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'ROUTER01-232V'), 'LOCAL', '$2y$10$9BNfkvdU8g3q81jUcQQdReLUtPVU6hbhmHQgkaE1AlPmkdb5k/JFy');

INSERT INTO usuarios (usuario, nombre, apellidos, id_equipo, tipo_usuario, password_hash) VALUES
('mysql_root', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'APP', '$2y$10$brTebxu0a5RjqpaAGqyGGeZl0Vm.uJLjtVilTX4zlkd9l4Bp/OkX2'),
('wordpress_user', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'APP', '$2y$10$d2GPqjybzUpKLy8UEYMP.OfIj74ICgO6ziJvJMuP0/Jl50ySgGABW'),
('moodle_user', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'APP', '$2y$10$BTeC/6dAJSK7EUbgmOYfRemyELI/JMumMqphw.mJNch.MS3E3E/Mu'),
('inventario_user', NULL, NULL, (SELECT id_equipo FROM equipos WHERE hostname = 'LAMP01-232V'), 'APP', '$2y$10$2vnSXbcDIJisUexTstN/w.PsPhd68woO1L1xhn9RUSfH9hoxGPz8a');

INSERT INTO accesos (id_equipo, id_usuario, tipo_acceso)
SELECT e.id_equipo, u.id_usuario, 'RDP'
FROM equipos e
JOIN usuarios u ON u.tipo_usuario = 'DOMINIO'
WHERE e.hostname IN ('WS01-232V', 'WS02-232V');

INSERT INTO accesos (id_equipo, id_usuario, tipo_acceso)
SELECT e.id_equipo, u.id_usuario, 'ADMIN_LOCAL'
FROM equipos e
JOIN usuarios u ON u.usuario IN ('guillermo', 'ruben') AND u.tipo_usuario = 'DOMINIO'
WHERE e.hostname IN ('WS01-232V', 'WS02-232V');

INSERT INTO accesos (id_equipo, id_usuario, tipo_acceso)
SELECT e.id_equipo, u.id_usuario, 'SUDO'
FROM equipos e
JOIN usuarios u ON u.usuario IN ('guillermo', 'ruben', 'manu', 'victor', 'judith') AND u.tipo_usuario = 'DOMINIO'
WHERE e.hostname = 'WS03-232V';

INSERT INTO accesos (id_equipo, id_usuario, tipo_acceso)
SELECT e.id_equipo, u.id_usuario, 'ADMIN_DOMINIO'
FROM equipos e
JOIN usuarios u ON u.usuario IN ('manu', 'victor', 'judith') AND u.tipo_usuario = 'DOMINIO'
WHERE e.hostname IN ('DC01-232V', 'DC02-232V');

INSERT INTO accesos (id_equipo, id_usuario, tipo_acceso)
SELECT id_equipo, id_usuario, 'LOCAL_ADMIN'
FROM usuarios
WHERE tipo_usuario = 'LOCAL';

INSERT INTO accesos (id_equipo, id_usuario, tipo_acceso)
SELECT id_equipo, id_usuario, 'MYSQL_ADMIN'
FROM usuarios
WHERE usuario = 'mysql_root' AND tipo_usuario = 'APP';

INSERT INTO accesos (id_equipo, id_usuario, tipo_acceso)
SELECT id_equipo, id_usuario, 'MYSQL_APP'
FROM usuarios
WHERE usuario IN ('wordpress_user', 'moodle_user', 'inventario_user') AND tipo_usuario = 'APP';
