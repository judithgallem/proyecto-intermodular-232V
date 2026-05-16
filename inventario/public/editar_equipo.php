<?php
mysqli_report(MYSQLI_REPORT_OFF);

require_once __DIR__ . '/../private/proteger.php';
proteger_pagina(array("admin"));

require_once __DIR__ . '/../private/conexion.php';

// Limpieza de datos recibidos por URL o formulario.
function limpiar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error = "";
$mensaje = "";
// Se recibe el ID del equipo por GET para mostrar su información, y por POST para actualizarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = limpiar($_POST["id"]);
} else {
    $id = limpiar($_GET["id"]);
}

// El ID debe ser un número entero
if (empty($id) || !preg_match("/^[0-9]+$/", $id)) {
    echo "ID no válido.";
    exit;
}

// Buscar el equipo que se va a editar
$stmt = mysqli_prepare($conn,
    "SELECT e.id_equipo, e.hostname, e.dominio, e.dhcp, e.mac, e.sistema_operativo, e.servicios, r.nombre AS red
     FROM equipos e
     JOIN redes r ON e.id_red = r.id_red
     WHERE e.id_equipo = ?"
);
// Como viene por GET, se valida antes de usarlo en sql
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$equipo = mysqli_fetch_assoc($result);

if (!$equipo) {
    echo "No existe el equipo indicado.";
    echo "<p><a href='listar_equipos.php'>Volver</a></p>";
    exit;
}
// Si se ha enviado el formulario, se procesa la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = limpiar($_POST["hostname"]);
    $dominio = limpiar($_POST["dominio"]);
    $dhcp = limpiar($_POST["dhcp"]);
    $sistema_operativo = limpiar($_POST["sistema_operativo"]);
    $servicios = limpiar($_POST["servicios"]);

    // Validación de campos obligatorios y formatos
    if (empty($hostname) || empty($dominio) || $dhcp === "") {
        $error = "Hostname, dominio y DHCP son obligatorios.";
    } elseif (!preg_match("/^[A-Za-z0-9-]+$/", $hostname)) {
        $error = "Hostname no válido.";
    } elseif (!preg_match("/^([A-Za-z0-9-]+\.)+[A-Za-z]{2,}$|^WORKGROUP$/", $dominio)) {
        $error = "Dominio no válido. Ejemplo: asi-232V.cifpaviles.com o WORKGROUP.";
    } elseif ($dhcp != "0" && $dhcp != "1") {
        $error = "Valor DHCP no válido.";
    } elseif (!empty($sistema_operativo) && !preg_match("/^[A-Za-z0-9 ._-]+$/", $sistema_operativo)) {
        $error = "Sistema operativo no válido.";
    } elseif (!empty($servicios) && !preg_match("/^[A-Za-z0-9 .,;:_\/-]+$/", $servicios)) {
        $error = "Servicios no válidos.";
    } else {
        $stmt_update = mysqli_prepare($conn,
            "UPDATE equipos
             SET hostname = ?, dominio = ?, dhcp = ?, sistema_operativo = ?, servicios = ?
             WHERE id_equipo = ?"
        );

        mysqli_stmt_bind_param($stmt_update, "ssissi", $hostname, $dominio, $dhcp, $sistema_operativo, $servicios, $id);
        // El trigger de MySQL controla hostname duplicado y otros errores 
        if (mysqli_stmt_execute($stmt_update)) {
            $mensaje = "Equipo actualizado correctamente.";
            $equipo['hostname'] = $hostname;
            $equipo['dominio'] = $dominio;
            $equipo['dhcp'] = $dhcp;
            $equipo['sistema_operativo'] = $sistema_operativo;
            $equipo['servicios'] = $servicios;
        } else {
            $error = "Error SQL: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_update);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar equipo</title>
    <style>
        .error { color: red; }
        .ok { color: green; }
        .obligatorio { color: red; }
    </style>
</head>
<body>

<h1>Editar equipo</h1>

<?php
if (!empty($error)) {
    echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
}

if (!empty($mensaje)) {
    echo "<p class='ok'>" . htmlspecialchars($mensaje) . "</p>";
    echo "<p><a href='../index.html'><button>Volver al índice</button></a></p>";
}
?>

<p><span class="obligatorio">*</span> Campos obligatorios</p>

<form method="post" action="editar_equipo.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($equipo['id_equipo']); ?>">

    <p>
        Hostname <span class="obligatorio">*</span>:
        <input type="text" name="hostname" value="<?php echo htmlspecialchars($equipo['hostname']); ?>">
    </p>

    <p>
        Dominio <span class="obligatorio">*</span>:
        <input type="text" name="dominio" value="<?php echo htmlspecialchars($equipo['dominio']); ?>">
    </p>

    <p>
        DHCP <span class="obligatorio">*</span>:
        <select name="dhcp">
            <option value="0" <?php if (!$equipo['dhcp']) echo "selected"; ?>>false</option>
            <option value="1" <?php if ($equipo['dhcp']) echo "selected"; ?>>true</option>
        </select>
    </p>

    <p>Red actual: <?php echo htmlspecialchars($equipo['red']); ?></p>
    <p>MAC: <?php echo htmlspecialchars($equipo['mac'] ?? ''); ?></p>

    <p>
        Sistema operativo:
        <input type="text" name="sistema_operativo" value="<?php echo htmlspecialchars($equipo['sistema_operativo'] ?? ''); ?>">
    </p>

    <p>
        Servicios:
        <br>
        <textarea name="servicios" rows="4" cols="60"><?php echo htmlspecialchars($equipo['servicios'] ?? ''); ?></textarea>
    </p>

    <button type="submit">Actualizar equipo</button>
</form>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<p><a href="../index.html"><button>Volver al índice</button></a></p>

</body>
</html>
