<?php
// Configuración LDAP del dominio
$ldap_servidores = array("172.20.23.19", "172.20.23.22");
$ldap_dominio = "asi-232V.cifpaviles.com";
$ldap_base_dn = "DC=asi-232V,DC=cifpaviles,DC=com";

// Grupos permitidos para la aplicación (gestión y admins)
$ldap_grupo_admins = "CN=admins,OU=admins,OU=empresa,OU=usuarios,DC=asi-232V,DC=cifpaviles,DC=com";
$ldap_grupo_gestion = "CN=gestion,OU=gestion,OU=empresa,OU=usuarios,DC=asi-232V,DC=cifpaviles,DC=com";

function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// Comprobar si el usuario pertenece a un grupo LDAP específico
function pertenece_grupo($datos_usuario, $grupo_dn) {
    if (isset($datos_usuario["memberof"])) {
        for ($i = 0; $i < $datos_usuario["memberof"]["count"]; $i++) {
            if (strtolower($datos_usuario["memberof"][$i]) == strtolower($grupo_dn)) {
                return true;
            }
        }
    }

    return false;
}
// Función principal de autenticación LDAP
function autenticar_ldap($usuario, $password, &$rol, &$mensaje) {
    global $ldap_servidores, $ldap_dominio, $ldap_base_dn, $ldap_grupo_admins, $ldap_grupo_gestion;

    $rol = "";
    $mensaje = "";

    if (empty($usuario) || empty($password)) {
        $mensaje = "Usuario y contraseña son obligatorios.";
        return false;
    }
// Validación solo con el nombre de usuario
    if (!preg_match("/^[A-Za-z0-9._-]+$/", $usuario)) {
        $mensaje = "Usuario no válido.";
        return false;
    }
// lo busca en el servidor y obtiene el DN para el bind
    foreach ($ldap_servidores as $servidor) {
        $ldap = ldap_connect($servidor);

        if (!$ldap) {
            continue;
        }

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

// Para el bind se transforma el usuario completo con el dominio
        $usuario_bind = $usuario . "@" . $ldap_dominio;

        // Intento de bind de loguearse
        if (@ldap_bind($ldap, $usuario_bind, $password)) {
            $filtro = "(sAMAccountName=" . ldap_escape($usuario, "", LDAP_ESCAPE_FILTER) . ")";
            $atributos = array("samaccountname", "displayname", "memberof", "distinguishedname");
            $busqueda = ldap_search($ldap, $ldap_base_dn, $filtro, $atributos);
            //  problema de permisos o conexión
            if (!$busqueda) {
                $mensaje = "No se pudo buscar el usuario en LDAP.";
                ldap_close($ldap);
                return false;
            }
            // Se comprueba el grupo
            $resultado = ldap_get_entries($ldap, $busqueda);

            if ($resultado["count"] == 0) {
                $mensaje = "Usuario no encontrado en LDAP.";
                ldap_close($ldap);
                return false;
            }

            $datos_usuario = $resultado[0];
            $dn_usuario = strtolower($datos_usuario["distinguishedname"][0]);

            // Se asigna rol según grupo LDAP
            if (pertenece_grupo($datos_usuario, $ldap_grupo_admins) || strpos($dn_usuario, "ou=admins") !== false) {
                $rol = "admin";
                ldap_close($ldap);
                return true;
            }

            if (pertenece_grupo($datos_usuario, $ldap_grupo_gestion) || strpos($dn_usuario, "ou=gestion") !== false) {
                $rol = "gestion";
                ldap_close($ldap);
                return true;
            }
            // Conseguido!!!
            $mensaje = "Usuario autenticado, pero sin permisos para el inventario.";
            ldap_close($ldap);
            return false;
        }

        ldap_close($ldap);
    }

    $mensaje = "Usuario o contraseña incorrectos.";
    return false;
}
?>
