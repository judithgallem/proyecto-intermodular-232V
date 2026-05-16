<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salir</title>
</head>
<body>

<h1>Sesión cerrada</h1>

<p><a href="login.php"><button>Volver al login</button></a></p>

</body>
</html>
