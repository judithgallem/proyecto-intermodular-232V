# Proyecto Intermodular ASIR 232V

Alumno: Judith Gallego Martínez  
Dominio AD: asi-232V.cifpaviles.com  
NETBIOS: ASI-232V  
Servidor virtualización: 31  
Grupo servidor: 8  

---


## 0. Preparaci&#243;n del informe y criterio de entrega

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Crear carpeta de trabajo para capturas con nombres por apartado del informe
  - **Entregable:** docs/informe/capturas/
- [ ] Definir convenci&#243;n de nombres para capturas: numero-apartado-maquina-descripcion.png
  - **Nota:** Ejemplo: 03-router01-ip-address.png
- [ ] Preparar documento final desde Proyecto_intermodular_2ASIR.informe.docx
  - **Entregable:** docs/informe/Proyecto_intermodular_2ASIR.informe.Nombre_Apellidos.pdf
- [ ] Anotar que esta infraestructura act&#250;a como entorno de producci&#243;n cuando el enunciado distinga desarrollo/pruebas y producci&#243;n
  - **Informe:** Observaciones generales del informe

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Revisar que cada captura usa FQDN cuando el informe pide imagen de una URL
  - **Informe:** Instrucciones de la plantilla
- [ ] Separar evidencias que conviene pegar como texto de las que conviene pegar como imagen
  - **Nota:** La plantilla recomienda texto para archivos grandes.
- [ ] Mantener una tabla de correspondencia entre captura, apartado del informe y m&#225;quina
  - **Entregable:** docs/informe/indice-capturas.md o equivalente

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Rellenar portada: fecha, nombre, host de virtualizaci&#243;n, grupo y puesto de aula
  - **Informe:** Cabecera del informe
- [ ] Completar el apartado Credenciales con el m&#233;todo de gesti&#243;n, sin exponer contrase&#241;as innecesarias
  - **Informe:** Credenciales (usuarios y contrase&#241;as)
- [ ] Completar Otros con ampliaciones, limitaciones y observaciones finales
  - **Informe:** Otros

</details>

## 1. Inventario VMware y nombrado de m&#225;quinas virtuales

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Comprobar que todas las m&#225;quinas siguen la nomenclatura con sufijo ID
  - **Informe:** Nombrado de m&#225;quinas virtuales
- [ ] Ubicar todas las m&#225;quinas en la carpeta proyecto de vCenter
  - **Captura:** Inventario de m&#225;quinas en VMware vCenter mostrando carpeta proyecto y nombres
- [ ] Rellenar Summary/Annotations en cada m&#225;quina virtual
  - **Captura:** Atributos y texto de notas de cada VM
- [ ] Comprobar que cada m&#225;quina est&#225; conectada a su red LAN virtual correcta
  - **Informe:** Ubicaci&#243;n de m&#225;quinas en redes

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Capturar inventario completo de vCenter con todas las VM visibles
  - **Captura:** vCenter / carpeta proyecto
- [ ] Capturar anotaciones de todas las VM creadas
  - **Captura:** Summary/Annotations

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar inventario de m&#225;quinas virtuales
  - **Informe:** Nombrado de m&#225;quinas virtuales
- [ ] Pegar atributos y notas de m&#225;quinas virtuales
  - **Informe:** Anotaciones de m&#225;quinas virtuales

</details>

## 2. Router Zentyal ROUTER01-ID, red, DHCP, NAT y VPN

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Revisar configuraci&#243;n b&#225;sica de ROUTER01-ID
  - **Comando:** hostnamectl; lsb_release -a; cat /etc/hostname; cat /etc/hosts; ip address
- [ ] Documentar mapeo de interfaces f&#237;sicas y l&#243;gicas
  - **Informe:** Router Zentyal ROUTER01-ID, configuraci&#243;n b&#225;sica
- [ ] Comprobar archivo de red usado por Zentyal
  - **Comando:** cat /etc/network/interfaces o cat /etc/netplan/*.yaml
- [ ] Crear o revisar objetos DHCP con IP y MAC de clientes
  - **Captura:** Zentyal / Red / Objetos / Listas de objetos / Miembros
- [ ] Configurar DHCP con DNS internos para los clientes
  - **Comando:** cat /etc/dhcp/dhcpd.conf
- [ ] Configurar puertas de enlace, reglas de cortafuegos y NAT
  - **Captura:** Zentyal / Red / Puertas de enlace; Cortafuegos; Redirecciones de puertos
- [ ] Configurar NAT 3389 hacia WS01-ID como acceso alternativo
  - **Captura:** Zentyal / Cortafuegos / Redirecciones de puertos
- [ ] Configurar VPN de acceso remoto para EXTERNA01-ID
  - **Captura:** Zentyal / VPN / Servidores / Configuraci&#243;n

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Validar conexi&#243;n VPN desde EXTERNA01-ID
  - **Comando:** ipconfig /all
  - **Captura:** Cliente Windows con VPN establecida
- [ ] Validar rutas remotas anunciadas al cliente VPN
  - **Comando:** route print
  - **Captura:** Cliente Windows con VPN establecida
- [ ] Probar acceso SSH/RDP por IP 172 desde EXTERNA01-ID mediante VPN
  - **Comando:** ssh usuario@IP o mstsc /v:IP
- [ ] Comprobar que los clientes reciben DNS internos por DHCP
  - **Comando:** ipconfig /all o resolvectl status

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar comandos de configuraci&#243;n b&#225;sica de ROUTER01-ID
  - **Informe:** Router Zentyal ROUTER01-ID, configuraci&#243;n b&#225;sica
- [ ] Pegar capturas de enrutamiento, cortafuegos y NAT
  - **Informe:** Router Zentyal ROUTER01-ID, enrutamiento, cortafuegos y NAT
- [ ] Pegar objetos DHCP y texto completo de dhcpd.conf
  - **Informe:** Router Zentyal ROUTER01-ID, servicio DHCP
- [ ] Pegar configuraci&#243;n del servidor VPN, ipconfig y route print del cliente
  - **Informe:** Router Zentyal ROUTER01-ID, servicio VPN de acceso remoto

</details>

## 3. Active Directory, DNS interno, UO, usuarios, grupos y GPO

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Revisar que DC01-ID es controlador principal y tiene volumen DATOS
  - **Captura:** Administraci&#243;n de discos y ADUC / Domain Controllers
- [ ] Revisar que DC02-ID est&#225; promocionado como segundo controlador
  - **Captura:** ADUC / Domain Controllers
- [ ] Comprobar RAID-1 o particionamiento de DC02-ID
  - **Captura:** Administraci&#243;n de discos
- [ ] Recrear o revisar UO equipos, usuarios y moodleroles tras el script viejo
  - **Captura:** ADUC con View / Users, Contacts, Groups and Computers as containers
- [ ] Revisar grupos base: usuariosASI-ID, empresa, gestion, tecnicos, N1, N2, N3, admins, clientes
  - **Captura:** Propiedades General, Miembros y Miembro de por grupo
- [ ] Revisar grupos de Moodle en UO moodleroles
  - **Captura:** General y Miembros de cada grupo moodleroles
- [ ] Revisar grupos de vCenter e hipervisores
  - **Captura:** admins de vcenter, usuarios de vcenter, usuariosHV01-ID, usuariosHV02-ID y grupos .usu1-.usu4
- [ ] Configurar o reparar GPO de contrase&#241;as simples
  - **Captura:** GPMC / GPO / Configuraci&#243;n / Mostrar todo
- [ ] Configurar o reparar GPO de administraci&#243;n remota
  - **Captura:** GPMC / GPO / Configuraci&#243;n / Mostrar todo
- [ ] Configurar o reparar GPO de escritorio remoto y grupo empresa en RDP
  - **Captura:** GPMC / v&#237;nculos sobre UO y configuraci&#243;n
- [ ] Preparar despliegue de CA/certificado ra&#237;z a equipos mediante GPO o script
  - **Comando:** certutil -addstore -f Root ruta\certificado.cer
  - **Nota:** Priorizar GPO: Computer Configuration / Policies / Windows Settings / Security Settings / Public Key Policies / Trusted Root Certification Authorities.
- [ ] Configurar zonas DNS directas e inversas, registros host y PTR
  - **Captura:** Consola DNS con zona directa, zonas inversas y registros
- [ ] Configurar reenviadores DNS internos en cada DC
  - **Captura:** Consola DNS / Propiedades del servidor / Reenviadores

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Ejecutar winver en cada controlador de dominio
  - **Comando:** winver
  - **Captura:** DC01-ID y DC02-ID
- [ ] Capturar configuraci&#243;n IP de cada DC
  - **Comando:** ipconfig /all
  - **Captura:** TCP-IP / Propiedades e ipconfig /all
- [ ] Validar replicaci&#243;n y salud b&#225;sica del dominio
  - **Comando:** repadmin /replsummary; dcdiag
- [ ] Validar resoluci&#243;n directa e inversa desde cliente
  - **Comando:** nslookup DC01-ID; nslookup IP_DC01; nslookup ROUTER01-ID
- [ ] Validar GPO aplicadas en estaciones Windows
  - **Comando:** gpupdate /force; gpresult /r
- [ ] Validar certificado ra&#237;z instalado en clientes Windows
  - **Comando:** certutil -store Root

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar Domain Controllers con DC01-ID y DC02-ID
  - **Informe:** Controladores de dominio
- [ ] Pegar UO equipos desplegada por completo
  - **Informe:** UO equipos
- [ ] Pegar zona directa, zonas inversas y reenviadores DNS
  - **Informe:** Servicio DNS interno
- [ ] Pegar winver, particionamiento e IP de cada DC
  - **Informe:** Controladores de dominio: versi&#243;n, particionamiento e IP
- [ ] Pegar UO usuarios, grupos, usuario personal, unidad Z: y perfil m&#243;vil
  - **Informe:** UO usuarios, grupos, usuario personal, unidad de red y perfil m&#243;vil
- [ ] Pegar &#225;rbol de GPMC y definici&#243;n de cada GPO
  - **Informe:** Directivas de dominio
- [ ] Pegar UO moodleroles y grupos de usuarios en moodleroles
  - **Informe:** UO moodleroles

</details>

## 4. Estaciones Windows y Linux integradas en dominio

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Integrar WS01-ID y WS02-ID en el dominio
  - **Captura:** Propiedades del sistema o panel de dominio
- [ ] Agregar grupo empresa a usuarios de escritorio remoto por GPO o localmente
  - **Captura:** Usuarios de escritorio remoto
- [ ] Integrar WS03-ID Linux en el dominio
  - **Comando:** realm list o equivalente usado
- [ ] Configurar cifrado de datos de usuarios en estaci&#243;n Linux
  - **Comando:** cat /etc/fstab; lsblk -f; blkid /dev/sdb1

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Ejecutar winver en cada estaci&#243;n Windows
  - **Comando:** winver
- [ ] Capturar configuraci&#243;n IP de cada estaci&#243;n Windows
  - **Comando:** ipconfig /all
  - **Captura:** TCP-IP / Propiedades e ipconfig /all
- [ ] Capturar configuraci&#243;n b&#225;sica de WS03-ID
  - **Comando:** hostnamectl; lsb_release -a; cat /etc/hostname; cat /etc/hosts; cat /etc/netplan/*.yaml; cat /etc/resolv.conf; resolvectl status | tail -n 5; ip address
- [ ] Probar inicio de sesi&#243;n con usuarios de dominio en WS01-ID, WS02-ID y WS03-ID
  - **Captura:** Sesi&#243;n iniciada o whoami/id
- [ ] Probar RDP a WS01-ID y WS02-ID con usuario del grupo empresa
  - **Comando:** mstsc /v:WS01-ID o mstsc /v:IP

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar winver de estaciones Windows
  - **Informe:** Estaciones de trabajo Windows: versi&#243;n del sistema operativo
- [ ] Pegar IP de estaciones Windows
  - **Informe:** Configuraci&#243;n IP de estaciones de trabajo Windows
- [ ] Pegar comandos de configuraci&#243;n de WS03-ID
  - **Informe:** Configuraci&#243;n de estaci&#243;n de trabajo Linux
- [ ] Pegar fstab, lsblk y blkid de cifrado en Linux
  - **Informe:** Cifrado de datos de usuarios en estaci&#243;n de trabajo Linux

</details>

## 5. LAMP01-ID, Docker, Nginx, bases de datos y aplicaciones web

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Revisar RAID-1 del sistema y RAID-5 de datos en LAMP01-ID
  - **Comando:** cat /proc/mdstat; cat /etc/mdadm/mdadm.conf; cat /etc/fstab; ls -al /mnt/raid5/
- [ ] Comprobar enlaces simb&#243;licos de servicios ubicados en RAID-5
  - **Comando:** ls -al enlace_simbolico_a_raid
- [ ] Revisar configuraci&#243;n b&#225;sica de LAMP01-ID
  - **Comando:** hostnamectl; lsb_release -a; cat /etc/hostname; cat /etc/hosts; cat /etc/netplan/*.yaml; cat /etc/resolv.conf; resolvectl status | tail -n 5; ip address
- [ ] Comprobar servicios web, PHP, MySQL y phpMyAdmin o justificar equivalencia Docker/Nginx
  - **Comando:** dpkg -l apache2 php libapache2-mod-php php-mysql mysql-server phpmyadmin
- [ ] Comprobar extensiones PHP necesarias
  - **Comando:** dpkg -l php-intl php-xmlrpc php-soap php-ldap
- [ ] Revisar estado de contenedores
  - **Comando:** docker ps; docker compose ps
- [ ] Revisar compose, redes Docker, segmentos, hostnames internos y puertos mapeados
  - **Entregable:** archivos/docker/docker-compose.yml y docs/esquemas/red/docker
- [ ] Revisar Nginx Reverse Proxy y certificados por FQDN
  - **Comando:** cat /mnt/raid5/infra/nginx/conf.d/default.conf
- [ ] Revisar acceso a phpMyAdmin y tablas por cada aplicaci&#243;n
  - **Captura:** phpMyAdmin con bases de datos y tablas
- [ ] Revisar cuentas MySQL y privilegios espec&#237;ficos por aplicaci&#243;n
  - **Captura:** phpMyAdmin / Cuentas de usuarios / Privilegios espec&#237;ficos
- [ ] Preparar triggers, procedimientos y funciones SQL con ejemplos
  - **Entregable:** scripts/sql/DDL y scripts/sql/DML; inventario/triggers_procedimientos_funciones.sql
- [ ] Separar c&#243;digo propio de inventario de aplicaciones no propias
  - **Entregable:** apps/inventario/

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Probar acceso por FQDN a p&#225;gina principal
  - **Captura:** https://asi-232v.cifpaviles.com
- [ ] Probar acceso por FQDN a inventario
  - **Captura:** https://inventario.asi-232v.cifpaviles.com
- [ ] Probar acceso por FQDN a Moodle
  - **Captura:** https://moodle.asi-232v.cifpaviles.com
- [ ] Probar acceso por FQDN a WordPress
  - **Captura:** https://wordpress.asi-232v.cifpaviles.com
- [ ] Validar que los certificados son confiables en clientes tras desplegar CA
  - **Captura:** Candado/certificado del navegador
- [ ] Validar importaci&#243;n y exportaci&#243;n JSON/YAML en inventario
  - **Captura:** Pantallas de importar/exportar y archivos generados
- [ ] Validar triggers/procedimientos/funciones con ejemplos ejecutados
  - **Comando:** mysql -u usuario -p base &lt; triggers_procedimientos_funciones.sql

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar particionamiento, RAID y enlaces de LAMP01-ID
  - **Informe:** Particionamiento de servidor LAMP01-ID
- [ ] Pegar configuraci&#243;n b&#225;sica de LAMP01-ID
  - **Informe:** Configuraci&#243;n de servidor LAMP01-ID
- [ ] Pegar instalaci&#243;n de servicios, extensiones PHP y salidas Docker
  - **Informe:** Servidor LAMP01-ID: Instalaci&#243;n de servicios y aplicaciones
- [ ] Pegar phpMyAdmin con bases, tablas, usuarios y privilegios
  - **Informe:** Acceso a phpMyAdmin y cuentas MySQL
- [ ] Pegar bloques server de Nginx como equivalencia de sitios virtuales
  - **Informe:** Servidor LAMP01-ID: Definici&#243;n de sitios virtuales
- [ ] Pegar navegador con URL por nombre DNS para cada aplicaci&#243;n
  - **Informe:** Servidor LAMP01-ID: Acceso a aplicaciones web por nombre DNS

</details>

## 6. Moodle

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Verificar versi&#243;n y entorno de Moodle
  - **Captura:** Administraci&#243;n del sitio / Servidor / Entorno
- [ ] Revisar ajustes de p&#225;gina principal
  - **Captura:** Nombre completo, nombre corto y descripci&#243;n
- [ ] Revisar identificaci&#243;n LDAP
  - **Informe:** Copiar como texto URL host, DN, contextos, atributos y data mapping
- [ ] Revisar matriculaciones LDAP ya configuradas
  - **Informe:** Copiar como texto URL, usuario de enlace, mapeos de roles, contextos, atributos y plantilla
- [ ] Revisar cursos en categor&#237;a Documentos
  - **Captura:** Administrar cursos y categor&#237;as / Documentos
- [ ] Revisar usuarios visibles en Moodle
  - **Captura:** Usuarios / Cuentas / Examinar lista de usuarios

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Probar login de Moodle con usuario LDAP
  - **Captura:** Sesi&#243;n iniciada con URL visible
- [ ] Validar que los roles LDAP matriculan en cursos/categor&#237;as esperadas
  - **Captura:** Participantes o matriculaciones del curso
- [ ] Ejecutar o revisar script moodle_roles.ps1 si se usa para grupos
  - **Comando:** Get-Content scripts/powershell/moodle_roles.ps1

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar versi&#243;n instalada y ajustes de portada
  - **Informe:** Aplicaci&#243;n Moodle: versi&#243;n instalada y ajustes de la p&#225;gina principal
- [ ] Pegar identificaci&#243;n LDAP y matriculaciones LDAP como texto
  - **Informe:** Aplicaci&#243;n Moodle: identificaci&#243;n LDAP y matriculaciones LDAP
- [ ] Pegar cursos en categor&#237;a Documentos y lista de usuarios
  - **Informe:** Aplicaci&#243;n Moodle: cursos y usuarios

</details>

## 7. WordPress

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Terminar configuraci&#243;n LDAP de WordPress
  - **Captura:** Plugin LDAP o configuraci&#243;n usada
- [ ] Configurar apartado Actividad
  - **Captura:** P&#225;gina o men&#250; Actividad visible
- [ ] Configurar apartado Tienda
  - **Captura:** P&#225;gina o plugin de tienda visible
- [ ] Revisar configuraci&#243;n wp-config.php y conexi&#243;n con base de datos
  - **Comando:** cat infra/images/wordpress/html/wp-config.php
- [ ] Revisar que no se entrega c&#243;digo fuente propio de WordPress salvo configuraci&#243;n necesaria
  - **Entregable:** archivos/docker/wordpress y no apps/wordpress

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Probar acceso por FQDN a WordPress
  - **Captura:** https://wordpress.asi-232v.cifpaviles.com
- [ ] Probar login LDAP en WordPress
  - **Captura:** Usuario LDAP autenticado
- [ ] Capturar Actividad y Tienda funcionando
  - **Captura:** URL visible y contenido cargado

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar acceso a WordPress por nombre DNS
  - **Informe:** Servidor LAMP01-ID: Acceso a aplicaciones web por nombre DNS
- [ ] A&#241;adir WordPress a ampliaciones si el informe no tiene apartado espec&#237;fico
  - **Informe:** Otros / Ampliaciones

</details>

## 8. Servicio DNS externo

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Configurar reenviadores del DNS externo
  - **Comando:** cat /etc/bind/named.conf.options
- [ ] Configurar definici&#243;n de zonas
  - **Comando:** cat /etc/bind/named.conf.local
- [ ] Configurar zona directa del dominio externo
  - **Comando:** cat /etc/bind/db.asi-ID.cifpaviles.com
- [ ] Configurar zona inversa externa
  - **Comando:** cat /etc/bind/db.GRUPO.SERVIDOR.10

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Validar resoluci&#243;n de FQDN p&#250;blicos desde cliente
  - **Comando:** nslookup wordpress.asi-232v.cifpaviles.com; nslookup moodle.asi-232v.cifpaviles.com; nslookup inventario.asi-232v.cifpaviles.com
- [ ] Validar zona inversa externa
  - **Comando:** nslookup IP_PUBLICA_O_RED_EXTERNA

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar named.conf.options
  - **Informe:** Servicio DNS externo: reenviadores
- [ ] Pegar named.conf.local
  - **Informe:** Servicio DNS externo: definici&#243;n de zonas
- [ ] Pegar zona directa y zona inversa
  - **Informe:** Servicio DNS externo: zona directa y zona inversa

</details>

## 9. vSphere, ESXi y permisos

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Configurar hipervisores HV01-ID y HV02-ID
  - **Captura:** Interfaz web de cada host con usuario root y URL visible
- [ ] Configurar appliance vCenter
  - **Captura:** Interfaz 5480 y vCenter 443
- [ ] Configurar red de vCenter
  - **Captura:** Appliance vCenter / Redes / Configuraci&#243;n de red
- [ ] Crear datacenter y a&#241;adir los dos hosts ESXi
  - **Captura:** Vista f&#237;sica con datacenter y hosts
- [ ] Revisar certificado autogenerado de vCenter
  - **Captura:** Certificado mostrado al acceder a la URL
- [ ] Configurar red de cada ESXi
  - **Captura:** Hostname, dominio, IP, m&#225;scara, puerta de enlace, DNS y dominio de b&#250;squeda
- [ ] Configurar datastores locales y de red
  - **Captura:** Almacenes de datos por host
- [ ] Integrar vCenter en dominio
  - **Captura:** Administraci&#243;n / SSO / Configuraci&#243;n / Dominio de Active Directory
- [ ] Configurar origen de identidad LDAP
  - **Captura:** SSO / Or&#237;genes de identidad / dominio AD / Editar
- [ ] Crear roles personalizados y asignar permisos globales
  - **Captura:** Control de acceso / Funciones y Permisos globales
- [ ] Crear carpetas en vista l&#243;gica y almacenamiento
  - **Captura:** M&#225;quinas virtuales y plantillas; Datastores
- [ ] Asignar permisos por host, carpetas l&#243;gicas y carpetas de almacenamiento
  - **Captura:** Pesta&#241;a Permisos de cada objeto

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Validar acceso web a cada host ESXi con root
  - **Captura:** Pantalla principal con URL visible
- [ ] Validar acceso a vCenter con Administrator@vsphere.local
  - **Captura:** Vista f&#237;sica con hosts
- [ ] Validar acceso con usuario de dominio del grupo tecnicos
  - **Captura:** Vista f&#237;sica, l&#243;gica y almacenamiento desplegadas
- [ ] Validar acceso con usuario de dominio del grupo admins
  - **Captura:** Vista f&#237;sica, l&#243;gica y almacenamiento desplegadas

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar accesos web a hosts, appliance 5480 y vCenter 443
  - **Informe:** Acceso web a hosts, appliance y vCenter
- [ ] Pegar red de vCenter, certificado y red de hosts
  - **Informe:** Configuraci&#243;n de red y certificado
- [ ] Pegar datastores, integraci&#243;n en dominio e identity source
  - **Informe:** Datastores e integraci&#243;n con dominio
- [ ] Pegar permisos globales, roles definidos, uso de roles y permisos por objeto
  - **Informe:** Roles y permisos
- [ ] Pegar vistas con permisos de usuarios tecnicos y admins
  - **Informe:** Permisos de usuario de dominio sobre objetos

</details>

## 10. SSH sin contrase&#241;a y scripts bash

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Dise&#241;ar infraestructura de claves SSH: clientes, servidores y usuarios
  - **Informe:** Descripci&#243;n de la infraestructura SSH
- [ ] Generar claves SSH en el cliente administrativo
  - **Comando:** ssh-keygen -t ed25519 -C &quot;proyecto-ASIR&quot;
- [ ] Copiar clave p&#250;blica a servidores Linux
  - **Comando:** ssh-copy-id usuario@servidor
- [ ] Crear script bash para desplegar o comprobar claves
  - **Entregable:** scripts/bash/
- [ ] Documentar ubicaci&#243;n de claves privadas, p&#250;blicas y authorized_keys
  - **Informe:** Archivos de clave generados y ubicaci&#243;n

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Entrar por SSH sin petici&#243;n de contrase&#241;a
  - **Comando:** ssh usuario@servidor hostname
  - **Captura:** Entrada remota sin pedir contrase&#241;a
- [ ] Comprobar permisos de .ssh y authorized_keys
  - **Comando:** ls -la ~/.ssh; stat ~/.ssh/authorized_keys

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar descripci&#243;n, claves generadas, ubicaciones, captura de acceso y scripts bash
  - **Informe:** Infraestructura de clave p&#250;blica/privada para acceso ssh sin contrase&#241;a

</details>

## 11. Azure y t&#250;nel con on-premises

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Crear o revisar grupos de recursos necesarios
  - **Captura:** Grupo de recursos / Informaci&#243;n general
- [ ] Crear o revisar IP p&#250;blicas con nombre DNS asociado
  - **Captura:** Direcci&#243;n IP p&#250;blica / Informaci&#243;n general
- [ ] Crear red virtual y espacio de direcciones
  - **Captura:** Red virtual / Informaci&#243;n general
- [ ] Crear subredes de Azure
  - **Captura:** Red virtual / Configuraci&#243;n / Subredes
- [ ] Crear m&#225;quinas virtuales necesarias: ROUTER02-ID, LAMP02-ID y dem&#225;s si aplica
  - **Captura:** M&#225;quina virtual / Informaci&#243;n general / Informaci&#243;n esencial
- [ ] Configurar grupos de seguridad de red
  - **Captura:** Informaci&#243;n esencial, reglas de entrada, reglas de salida e interfaces de red
- [ ] Configurar tablas de rutas
  - **Captura:** Tabla de rutas / Rutas y Subredes
- [ ] Configurar ROUTER02-ID en Azure con red b&#225;sica
  - **Comando:** hostnamectl; lsb_release -a; cat /etc/hostname; cat /etc/hosts; cat /etc/netplan/*.yaml; cat /etc/resolv.conf; ip address
- [ ] Configurar objetos de red de ROUTER02-ID
  - **Captura:** Zentyal / Red / Objetos / Miembros
- [ ] Configurar enrutamiento, cortafuegos y NAT en ROUTER02-ID
  - **Captura:** Puertas de enlace, reglas de los 4 tipos y redirecciones
- [ ] Configurar t&#250;nel VPN entre ROUTER02-ID y ROUTER01-ID
  - **Captura:** Servidor VPN Azure, redes anunciadas y cliente VPN on-premises
- [ ] Configurar LAMP02-ID en Azure
  - **Comando:** hostnamectl; lsb_release -a; cat /etc/hostname; cat /etc/hosts; cat /etc/netplan/*.yaml; cat /etc/resolv.conf; resolvectl status | tail -n 5; ip address
- [ ] Instalar servicios, extensiones PHP, MySQL y phpMyAdmin en LAMP02-ID
  - **Comando:** dpkg -l apache2 php libapache2-mod-php php-mysql mysql-server phpmyadmin; dpkg -l php-intl php-xmlrpc php-soap php-ldap
- [ ] Migrar o desplegar aplicaciones previstas en Azure
  - **Captura:** phpMyAdmin y aplicaci&#243;n por IP p&#250;blica/FQDN

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Validar rutas del t&#250;nel VPN en ambos routers
  - **Comando:** ip route en ROUTER02-ID y ROUTER01-ID
- [ ] Validar acceso a phpMyAdmin de Azure mediante IP p&#250;blica
  - **Captura:** URL por IP p&#250;blica, login y bases de datos
- [ ] Validar resoluci&#243;n DNS a IP p&#250;blica para aplicaciones ubicadas en Azure
  - **Comando:** nslookup nombre-aplicacion
- [ ] Validar acceso a aplicaciones ubicadas en Azure por URL
  - **Captura:** Navegador con URL visible

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar grupos de recursos, IP p&#250;blicas, red virtual, subredes, VM, NSG y rutas
  - **Informe:** Azure: recursos base
- [ ] Pegar configuraci&#243;n b&#225;sica, objetos, NAT, cortafuegos y t&#250;nel VPN de ROUTER02-ID
  - **Informe:** Azure: Router Zentyal ROUTER02-ID
- [ ] Pegar configuraci&#243;n, servicios, phpMyAdmin, usuarios MySQL y sitios virtuales de LAMP02-ID
  - **Informe:** Azure: Servidor LAMP02-ID
- [ ] Pegar acceso a aplicaciones en Azure y DNS resolviendo a IP p&#250;blica
  - **Informe:** Azure: Acceso a aplicaciones ubicadas en Azure

</details>

## 12. Sincronizaci&#243;n horaria

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Configurar sincronizaci&#243;n horaria de controladores de dominio
  - **Comando:** w32tm /query /status; w32tm /query /configuration
- [ ] Configurar sincronizaci&#243;n horaria de sistemas Linux
  - **Comando:** systemctl status systemd-timesyncd
- [ ] Configurar sincronizaci&#243;n horaria de hipervisores ESXi
  - **Comando:** chkconfig ntpd; cat /etc/ntp.conf
- [ ] Configurar sincronizaci&#243;n horaria de vCenter
  - **Captura:** Appliance vCenter 5480 / Hora
- [ ] Configurar sincronizaci&#243;n horaria de ROUTER01-ID
  - **Captura:** Zentyal / Sistema / Fecha y hora

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Comprobar que DC, Linux, ESXi, vCenter y Zentyal tienen hora coherente
  - **Captura:** Salidas o pantallas de cada sistema

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar evidencias de sincronizaci&#243;n horaria de todos los sistemas solicitados
  - **Informe:** Sincronizaci&#243;n horaria

</details>

## 13. FILER01-ID, SMB, NFS, unidades y perfiles

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Configurar pools y datasets en FILER01-ID
  - **Captura:** Storage / Pools desplegado con datasets
- [ ] Configurar recursos SMB: usuarios$, perfiles$ y repo
  - **Captura:** Sharing / SMB con nombres y rutas
- [ ] Configurar recursos NFS necesarios
  - **Captura:** Sharing / NFS
- [ ] Configurar unidad Z: de usuarios en AD
  - **Captura:** Usuario / Propiedades / Perfil / Carpeta particular
- [ ] Configurar perfiles m&#243;viles para miembros del grupo gestion
  - **Captura:** Usuario gestion / Propiedades / Perfil / Perfil de usuario

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Abrir \\FILER01-ID\usuarios$ desde explorador
  - **Captura:** Contenido del recurso compartido
- [ ] Abrir \\FILER01-ID\perfiles$ desde explorador
  - **Captura:** Contenido del recurso compartido
- [ ] Abrir \\FILER01-ID\repo desde explorador
  - **Captura:** Contenido del recurso compartido
- [ ] Iniciar sesi&#243;n con usuario con unidad Z: y validar montaje
  - **Captura:** Explorador con unidad Z:
- [ ] Iniciar sesi&#243;n con usuario de gestion y validar carpeta de perfil
  - **Captura:** Contenido de carpeta de perfil

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Pegar pools, datasets y recursos SMB
  - **Informe:** FILER01-ID: Pools y datasets; Recursos compartidos SMB
- [ ] Pegar recursos usuarios$, perfiles$ y repo
  - **Informe:** Recursos compartidos FILER01-ID
- [ ] Pegar unidad Z: y perfil m&#243;vil en los apartados de AD
  - **Informe:** Unidad de red de usuarios y perfil m&#243;vil

</details>

## 14. Esquemas y documentaci&#243;n t&#233;cnica

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Crear esquema Visio de red interna, conexi&#243;n externa e Internet
  - **Entregable:** docs/esquemas/red/
- [ ] Crear esquema Visio de conexi&#243;n con Azure y redes Azure
  - **Entregable:** docs/esquemas/red/
- [ ] Crear esquema de red Docker con segmentos, IP, hostnames internos y puertos mapeados
  - **Entregable:** docs/esquemas/red/docker/
- [ ] Crear esquema de Active Directory: dominio, UO, grupos principales y controladores
  - **Entregable:** docs/esquemas/AD/
- [ ] Crear esquema E-R de la aplicaci&#243;n inventario
  - **Entregable:** docs/esquemas/BD/
- [ ] Crear dise&#241;o de tablas de la base de datos inventario
  - **Entregable:** docs/esquemas/BD/

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Comprobar que los esquemas coinciden con IP, nombres y puertos reales
- [ ] Comprobar que cada esquema exportado se abre correctamente

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Incluir esquemas en la documentaci&#243;n de infraestructura y en el comprimido final
  - **Entregable:** docs/esquemas/

</details>

## 15. Entregables finales y ZIP

<details>
<summary><strong>FASE - Implementaci&#243;n</strong></summary>

- [ ] Crear carpeta ra&#237;z: Entrega proyecto final m&#243;dulo ASIR Nombre y apellidos
  - **Entregable:** Carpeta ra&#237;z del ZIP
- [ ] Crear docs/informe con el PDF final del informe
  - **Entregable:** docs/informe/Proyecto_intermodular_2ASIR.informe.Nombre y apellidos.pdf
- [ ] Crear docs/esquemas/red con Visio de red interna, Azure y Docker
  - **Entregable:** docs/esquemas/red/
- [ ] Crear docs/esquemas/BD con E-R y dise&#241;o de tablas
  - **Entregable:** docs/esquemas/BD/
- [ ] Copiar scripts bash
  - **Entregable:** scripts/bash/
- [ ] Copiar scripts PowerShell
  - **Entregable:** scripts/powershell/
- [ ] Copiar scripts PowerCLI si existen
  - **Entregable:** scripts/powercli/
- [ ] Copiar scripts SQL DDL
  - **Entregable:** scripts/sql/DDL/
- [ ] Copiar scripts SQL DML, triggers, procedimientos y funciones
  - **Entregable:** scripts/sql/DML/
- [ ] Copiar c&#243;digo fuente propio de la aplicaci&#243;n inventario
  - **Entregable:** apps/inventario/
- [ ] Copiar archivos Docker y configuraci&#243;n relacionada
  - **Entregable:** archivos/docker/docker-compose.yml, Dockerfiles, nginx, .env de ejemplo sin secretos
- [ ] Copiar archivos JSON/YAML necesarios
  - **Entregable:** archivos/
- [ ] A&#241;adir otros archivos necesarios no encajables en carpetas anteriores
  - **Entregable:** otros/

</details>

<details>
<summary><strong>FASE - Validaci&#243;n</strong></summary>

- [ ] Verificar que no se incluyen contrase&#241;as reales, claves privadas ni secretos en el ZIP
- [ ] Verificar que no se sube c&#243;digo fuente de Moodle o WordPress salvo configuraci&#243;n propia necesaria
- [ ] Abrir el PDF final y comprobar que todas las capturas son legibles
- [ ] Comprobar que el ZIP contiene una &#250;nica carpeta ra&#237;z
- [ ] Comprobar fecha l&#237;mite de entrega: 20 de mayo de 2026 a las 23:59

</details>

<details>
<summary><strong>FASE - Informe</strong></summary>

- [ ] Exportar informe principal a PDF con el nombre exigido
  - **Entregable:** Proyecto_intermodular_2ASIR.informe.Nombre y apellidos.pdf
- [ ] Crear comprimido final con la estructura pedida
  - **Entregable:** Entrega proyecto final m&#243;dulo ASIR Nombre y apellidos.zip

</details>


