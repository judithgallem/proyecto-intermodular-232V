<?php
require_once __DIR__ . '/private/proteger.php';
proteger_pagina(array("admin", "gestion"));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario 232V</title>
</head>
<body>

    <h1>Inventario 232V</h1>

    <p>
        Usuario: <?php echo htmlspecialchars($_SESSION["usuario"]); ?>
        (<?php echo htmlspecialchars($_SESSION["rol"]); ?>)
    </p>

    <h2>Equipos</h2>
    <ul>
        <li><a href="public/listar_equipos.php">Listar equipos</a></li>

        <?php
        // Solo el admin puede dar de alta, cambiar red o borrar equipos.
        if ($_SESSION["rol"] == "admin") {
            echo "<li><a href='public/alta_equipo.php'>Alta de equipo</a></li>";
            echo "<li><a href='public/cambiar_equipo.php'>Cambiar equipo de red</a></li>";
            echo "<li><a href='public/borrar_equipo.php'>Borrar equipo</a></li>";
        }
        ?>
    </ul>

    <h2>Consultas</h2>
    <ul>
        <li><a href="public/mostrar_equipos_red.php">Mostrar equipos por red</a></li>
        <li><a href="public/resumen.php">Resumen del inventario</a></li>
    </ul>

    <h2>Importar / Exportar</h2>
    <ul>
        <li><a href="public/exportar_json.php">Exportar JSON</a></li>
        <li><a href="public/exportar_yaml.php">Exportar YAML</a></li>

        <?php
        // Solo el admin puede importar datos.
        if ($_SESSION["rol"] == "admin") {
            echo "<li><a href='public/importar_json.php'>Importar JSON</a></li>";
            echo "<li><a href='public/importar_yaml.php'>Importar YAML</a></li>";
        }
        ?>
    </ul>

    <p><a href="public/logout.php"><button>Cerrar sesión</button></a></p>

</body>
</html>

