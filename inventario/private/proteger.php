<?php
session_start();

// Función para verificar si el usuario tiene alguno de los roles permitidos.
function usuario_tiene_rol($roles_permitidos) {
    if (!isset($_SESSION["rol"])) {
        return false;
    }

    return in_array($_SESSION["rol"], $roles_permitidos);
}

// Proteger página según roles.
function proteger_pagina($roles_permitidos) {
    if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true || !isset($_SESSION["usuario"]) || !isset($_SESSION["rol"])) {
        if (strpos($_SERVER["SCRIPT_NAME"], "/public/") !== false) {
            header("Location: login.php");
        } else {
            header("Location: public/login.php");
        }
        exit;
    }

    if (!usuario_tiene_rol($roles_permitidos)) {
        echo "No tienes permisos para acceder a esta página.";

        if (strpos($_SERVER["SCRIPT_NAME"], "/public/") !== false) {
            echo "<p><a href='../index.php'><button>Volver al índice</button></a></p>";
        } else {
            echo "<p><a href='index.php'><button>Volver al índice</button></a></p>";
        }
        exit;
    }
}
?>
