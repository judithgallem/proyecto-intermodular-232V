<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin"));

require_once __DIR__ . '/../private/conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($mensaje)) {
    if (!isset($_FILES["archivo"]) || $_FILES["archivo"]["error"] != UPLOAD_ERR_OK) {
        $mensaje = "Debes seleccionar un archivo YAML vÃ¡lido.";
    } else {
        // yaml_parse_file lee el archivo YAML y lo convierte en array
        $datos = yaml_parse_file($_FILES["archivo"]["tmp_name"]);

        if ($datos === false) {
            $mensaje = "El archivo YAML no tiene un formato vÃ¡lido.";
        } else {
            // Para no duplicar lógica, se convierte a JSON y se guarda temporalmente
            $temporal = tempnam(sys_get_temp_dir(), "inventario_yaml_");
            file_put_contents($temporal, json_encode($datos));

            $_FILES["archivo"]["tmp_name"] = $temporal;
            $_FILES["archivo"]["error"] = UPLOAD_ERR_OK;

            // Se reutiliza la misma estructura de importaciÃ³n que JSON
            $contenido = file_get_contents($temporal);
            $datos = json_decode($contenido, true);
            unlink($temporal);

            mysqli_begin_transaction($conn);
            $ok = true;

            $tablas_borrar = ["accesos", "usuarios", "hardware", "ips", "equipos", "redes", "parametros"];
            foreach ($tablas_borrar as $tabla) {
                if (!mysqli_query($conn, "DELETE FROM $tabla")) {
                    $ok = false;
                }
            }

            if ($ok && isset($datos["parametros"])) {
                foreach ($datos["parametros"] as $fila) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO parametros (parametro, valor) VALUES (?, ?)");
                    mysqli_stmt_bind_param($stmt, "ss", $fila["parametro"], $fila["valor"]);
                    if (!mysqli_stmt_execute($stmt)) $ok = false;
                    mysqli_stmt_close($stmt);
                }
            }

            if ($ok && isset($datos["redes"])) {
                foreach ($datos["redes"] as $fila) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO redes (id_red, nombre, direccion_red, gateway, interfaz_router, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "isssss", $fila["id_red"], $fila["nombre"], $fila["direccion_red"], $fila["gateway"], $fila["interfaz_router"], $fila["descripcion"]);
                    if (!mysqli_stmt_execute($stmt)) $ok = false;
                    mysqli_stmt_close($stmt);
                }
            }

            if ($ok && isset($datos["equipos"])) {
                foreach ($datos["equipos"] as $fila) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO equipos (id_equipo, hostname, dominio, dhcp, mac, id_red, sistema_operativo, servicios) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "issisiss", $fila["id_equipo"], $fila["hostname"], $fila["dominio"], $fila["dhcp"], $fila["mac"], $fila["id_red"], $fila["sistema_operativo"], $fila["servicios"]);
                    if (!mysqli_stmt_execute($stmt)) $ok = false;
                    mysqli_stmt_close($stmt);
                }
            }

            if ($ok && isset($datos["ips"])) {
                foreach ($datos["ips"] as $fila) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO ips (id_ip, id_equipo, id_red, ip, mascara, interfaz, principal) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "iiisssi", $fila["id_ip"], $fila["id_equipo"], $fila["id_red"], $fila["ip"], $fila["mascara"], $fila["interfaz"], $fila["principal"]);
                    if (!mysqli_stmt_execute($stmt)) $ok = false;
                    mysqli_stmt_close($stmt);
                }
            }

            if ($ok && isset($datos["hardware"])) {
                foreach ($datos["hardware"] as $fila) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO hardware (id_hardware, id_equipo, componente, tamano) VALUES (?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "iiss", $fila["id_hardware"], $fila["id_equipo"], $fila["componente"], $fila["tamano"]);
                    if (!mysqli_stmt_execute($stmt)) $ok = false;
                    mysqli_stmt_close($stmt);
                }
            }

            if ($ok && isset($datos["usuarios"])) {
                foreach ($datos["usuarios"] as $fila) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO usuarios (id_usuario, usuario, nombre, apellidos, id_equipo, tipo_usuario, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "isssiss", $fila["id_usuario"], $fila["usuario"], $fila["nombre"], $fila["apellidos"], $fila["id_equipo"], $fila["tipo_usuario"], $fila["password_hash"]);
                    if (!mysqli_stmt_execute($stmt)) $ok = false;
                    mysqli_stmt_close($stmt);
                }
            }

            if ($ok && isset($datos["accesos"])) {
                foreach ($datos["accesos"] as $fila) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO accesos (id_acceso, id_equipo, id_usuario, tipo_acceso) VALUES (?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "iiis", $fila["id_acceso"], $fila["id_equipo"], $fila["id_usuario"], $fila["tipo_acceso"]);
                    if (!mysqli_stmt_execute($stmt)) $ok = false;
                    mysqli_stmt_close($stmt);
                }
            }

            if ($ok) {
                mysqli_commit($conn);
                $mensaje = "Datos importados correctamente.";
            } else {
                mysqli_rollback($conn);
                $mensaje = "Error SQL: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Importar YAML</title>
</head>
<body>

<h1>Importar YAML</h1>

<?php
if (!empty($mensaje)) {
    echo "<p>" . htmlspecialchars($mensaje) . "</p>";
}
?>

<form method="post" action="importar_yaml.php" enctype="multipart/form-data">
    <p>
        Archivo YAML:
        <input type="file" name="archivo" accept=".yaml,.yml">
    </p>
    <button type="submit">Importar</button>
</form>

<?php
mysqli_close($conn);
?>

<p><a href="../index.php"><button>Volver al índice</button></a></p>

</body>
</html>
