# Proyecto Intermodular ASIR 232V

Alumno: Judith Gallego Martínez  
Dominio AD: asi-232V.cifpaviles.com  
NETBIOS: ASI-232V  
Servidor virtualización: 31  
Grupo servidor: 8  

---

# 1. Infraestructura base

## ROUTER01-232V
- [x] Interfaces configuradas
- [x] Routing entre redes
- [x] NAT salida a internet
- [x] NAT puertos DMZ
- [ ] Firewall segmentado
- [x] OpenVPN configurado
- [x] DNS externo
- [x] CA instalada
- [x] Certificados generados
- [x] Split-Horizon

## Redes internas
- [x] DMZ → 172.16.23.0/24
- [x] SERVIDORES → 172.20.23.0/24
- [x] ALMACENAMIENTO → 172.22.23.0/24
- [x] GESTION → 172.24.23.0/24
- [x] ESTACIONES → 172.28.23.0/24
- [x] VPN → 172.31.23.0/24

---

# 2. Active Directory

<details>
<summary>DC01-232V</summary>

- [x] Windows Server 2019
- [x] Promocionado a DC
- [x] DNS instalado
- [x] Catálogo global
- [x] Volumen DATOS
- [x] NTP configurado

</details>

<details>
<summary>DC02-232V</summary>

- [x] Windows Server 2022
- [x] Unido al dominio
- [x] Promocionado a DC
- [x] DNS replicado
- [x] Catálogo global
- [x] Replicación correcta

</details>

---

# 3. DNS

## DNS interno
- [ ] Zona directa
- [ ] Zonas inversas
- [ ] Registros A
- [ ] Registros PTR
- [x] Reenviadores
- [x] Registros www
- [x] Registros moodle
- [x] Registros wordpress
- [x] Registros inventario

## DNS externo
- [x] Delegación
- [x] Split-Horizon
- [x] Resolución externa correcta

---

# 4. Usuarios y grupos AD

## OUs
- [x] usuarios
- [x] empresa
- [x] gestion
- [x] tecnicos
- [x] N1
- [x] N2
- [x] N3
- [x] admins
- [x] clientes
- [x] equipos
- [x] servidores
- [x] estaciones

## Grupos
- [x] empresa
- [x] gestion
- [x] tecnicos
- [x] N1
- [x] N2
- [x] N3
- [x] admins
- [x] adminsEstaciones
- [x] clientes

## Usuarios
- [x] andres
- [x] manuel
- [x] luisma
- [x] toni
- [x] guillermo
- [x] ruben
- [x] luis
- [x] olga
- [x] manu
- [x] victor
- [x] judith

---

# 5. GPOs

- [x] Permitir contraseñas simples
- [x] Mensaje inicio sesión
- [ ] Restricciones estaciones
- [ ] Administración remota
- [ ] Restricciones usuarios
- [ ] Escritorio remoto
- [ ] Administradores locales estaciones
- [ ] Firewall RDP
- [ ] Firewall administración remota

---

# 6. DHCP

- [ ] Reservas DHCP
- [ ] Tiempo concesión
- [ ] Gateway
- [ ] DNS
- [ ] DHCP en router

---

# 7. Estaciones de trabajo

## WS01-232V
- [x] Windows 10
- [x] Unido dominio
- [x] RDP

## WS02-232V
- [x] Windows 11
- [x] Unido dominio
- [x] Perfiles móviles

## WS03-232V
- [x] Ubuntu Desktop 24
- [x] XRDP
- [x] Unido dominio
- [ ] LUKS
- [ ] /home cifrado

---

# 8. TrueNAS / FILER02-232V

## SMB
- [x] Recurso repo
- [x] Permisos N1
- [x] Permisos N2
- [x] Permisos N3
- [x] admins full control

## Usuarios
- [x] Unidad Z
- [x] perfiles$
- [x] usuarios$

## NFS
- [ ] DATOS
- [ ] DATASTORENET01-232V
- [ ] ISO datastore
- [ ] datastore VMs

---

# 9. LAMP01-232V

## Base
- [x] Ubuntu Server 24
- [x] RAID configurado
- [x] Docker instalado
- [x] Docker Compose

## Contenedores
- [ ] nginx
- [ ] apache
- [ ] mysql
- [ ] phpmyadmin
- [ ] vsftpd

## Sitios
- [ ] www
- [ ] wordpress
- [ ] moodle
- [ ] inventario

## HTTPS
- [x] Certificados
- [x] HTTPS funcional
- [x] Redirección HTTP→HTTPS

---

# 10. WordPress

- [x] Página inicio
- [x] Quienes somos
- [ ] Actividad
- [x] Contacto
- [ ] Captcha
- [x] Slideshow
- [x] FAQ
- [x] Blog
- [ ] SEO
- [ ] URLs amigables
- [x] Portal bilingüe
- [ ] LDAP
- [x] HTTPS forzado

## Tienda
- [x] Tienda
- [ ] Carrito
- [ ] Mi cuenta
- [x] 3 categorías
- [x] 3 productos por categoría
- [x] Ofertas
- [ ] Galería imágenes

---

# 11. Moodle

- [ ] Instalación Git
- [ ] LDAP
- [ ] DOCSN1
- [ ] DOCSN2
- [ ] DOCSN3
- [ ] Categoría Documentos
- [ ] Matriculación LDAP
- [ ] Roles personalizados

---

# 12. Inventario PHP + MySQL

## Base de datos
- [ ] Tabla parámetros
- [ ] Tabla redes
- [ ] Tabla equipos
- [ ] Tabla hardware
- [ ] Tabla usuarios

## SQL
- [ ] DDL
- [ ] DML
- [ ] Triggers
- [ ] Procedimientos
- [ ] Funciones

## Aplicación PHP
- [ ] CRUD
- [ ] Validación formularios
- [ ] Import JSON
- [ ] Export JSON
- [ ] Import YAML
- [ ] Export YAML

---

# 13. VPN

## OpenVPN usuarios
- [x] Certificados
- [x] Cliente Windows
- [x] EXTERNA01-232V
- [x] Acceso redes internas

## VPN Azure
- [ ] ROUTER02
- [ ] Túnel site-to-site
- [ ] Red 192.168.241.0/24

---

# 14. Azure

- [ ] Red frontend
- [ ] Red backend
- [ ] ROUTER02
- [ ] LAMP02
- [ ] Migración Moodle
- [ ] Migración Inventario

---

# 15. vCenter / ESXi

## Hosts
- [x] HV01
- [x] HV02

## vCenter
- [ ] VCENTER01
- [ ] Integración AD
- [ ] Roles
- [ ] Permisos
- [ ] Datacenter ASI-232V

## Datastores
- [ ] Datastore local
- [ ] Datastore NFS
- [ ] Migración VMs

---

# 16. Certificados

- [x] CA01
- [x] Certificados HTTPS
- [x] Certificados VPN
- [ ] Importar CA clientes

---

# 17. SSH Keys

- [ ] Clave pública
- [ ] Clave privada
- [ ] Scripts bash
- [ ] Acceso sin contraseña

---

# 18. GitHub

- [x] Repo creado
- [ ] README
- [ ] Documentación
- [ ] Scripts
- [ ] Apps
- [ ] Esquemas
- [ ] Markdown

---

# 19. NTP

- [x] PDC Emulator
- [x] time.informatica.cifpaviles.pa
- [x] GPO servidor horario
- [x] Servicio NTP
- [x] time.asi-232V.cifpaviles.com
- [x] time2.asi-232V.cifpaviles.com

---

# 20. Extras / PoC

- [x] Proxy inverso nginx
- [ ] Ansible
- [ ] GLPI
- [ ] Monitorización
- [ ] Proxmox

---

# 21. Documentación

- [ ] Informe final
- [ ] Capturas
- [ ] Esquemas red
- [ ] Esquemas AD
- [ ] Scripts comentados
- [ ] README final
- [ ] Backup documentación
