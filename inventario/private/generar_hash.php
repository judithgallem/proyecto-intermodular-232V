<?php

$cuentas = [
    'dominio' => [
        'andres' => 'Password2026',
        'manuel' => 'Password2026',
        'luisma' => 'Password2026',
        'toni' => 'Password2026',
        'guillermo' => 'Password2026',
        'ruben' => 'Password2026',
        'luis' => 'Password2026',
        'olga' => 'Password2026',
        'manu' => 'Password2026',
        'victor' => 'Password2026',
        'judith' => 'Password2026',
    ],
    'locales' => [
        'FILER02-232V/root' => 'asir232v',
        'LAMP01-232V/administrador' => 'asir232v',
        'EXTERNA01-232V/admin' => 'asir232v',
        'WS01-232V/admin' => 'asir232v',
        'WS02-232V/admin' => 'asir232v',
        'WS03-232V/administrador' => 'asir232v',
        'DC01-232V/administrator' => 'asir232v',
        'DC02-232V/administrator' => 'asir232v',
        'ROUTER01-232V/administrador' => 'ASIR_fp24',
    ],
    'docker_app' => [
        'mysql_root' => 'root_password',
        'wordpress_user' => 'wp_password',
        'moodle_user' => 'moodle_password',
        'inventario_user' => 'inv_password',
    ],
];

foreach ($cuentas as $grupo => $usuarios) {
    echo "== " . strtoupper($grupo) . " ==" . PHP_EOL;

    foreach ($usuarios as $usuario => $password) {
        echo $usuario . " -> " . password_hash($password, PASSWORD_DEFAULT) . PHP_EOL;
    }

    echo PHP_EOL;
}
