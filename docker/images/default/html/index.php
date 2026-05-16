<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proyecto Intermodular ASI-232V</title>

    <style>
        body {
            margin: 0;
            background: #ffffff;
            color: #1f1f1f;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
        }

        .cabecera {
            margin-top: 70px;
            margin-bottom: 75px;
        }

        h1 {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 38px;
            font-weight: normal;
            letter-spacing: 1px;
        }

        .autora {
            margin-top: 14px;
            font-size: 18px;
            color: #6f6a64;
        }

        .anio {
            margin-top: 6px;
            font-size: 16px;
            color: #6f6a64;
        }

        .servicios {
            position: relative;
            display: flex;
            justify-content: center;
            gap: 65px;
            padding: 0 30px;
        }

        .servicios::before {
            content: "";
            position: absolute;
            top: 35px;
            left: 0;
            width: 100%;
            height: 150px;
            background: #efe2b3;
            z-index: 0;
        }

        .circulo {
            position: relative;
            z-index: 1;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: #ffffff;
            color: #1f1f1f;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            padding: 28px;
        }

        .circulo:hover {
            background: #fbfaf7;
        }

        .titulo-servicio {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .descripcion {
            font-size: 14px;
            line-height: 1.4;
            color: #5f5a55;
        }

        .etiqueta {
            margin-top: 12px;
            font-size: 11px;
            color: #9b7b3d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .pie {
            margin-top: 80px;
            font-size: 13px;
            color: #7a746c;
        }

        @media (max-width: 800px) {
            .servicios {
                flex-direction: column;
                align-items: center;
                gap: 35px;
            }

            .servicios::before {
                top: 0;
                height: 100%;
                width: 90px;
                left: calc(50% - 45px);
            }

            h1 {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>

    <div class="cabecera">
        <h1>Proyecto Intermodular ASI-232V</h1>
        <div class="autora">Judith Gallego Martínez</div>
        <div class="anio">2026</div>
    </div>

    <div class="servicios">
	<a class="circulo" href="http://wordpress.asi-232v.cifpaviles.com" target="_blank" rel="noopener noreferrer">
            <div class="titulo-servicio">WordPress</div>
            <div class="descripcion">Creative Studio</div>
            <div class="etiqueta">Administración LDAP</div>
        </a>

	<a class="circulo" href="http://moodle.asi-232v.cifpaviles.com" target="_blank" rel="noopener noreferrer">
            <div class="titulo-servicio">Moodle</div>
            <div class="descripcion">Portal del Conocimiento</div>
            <div class="etiqueta">Usuarios plataforma</div>
        </a>

	<a class="circulo" href="http://inventario.asi-232v.cifpaviles.com" target="_blank" rel="noopener noreferrer">
            <div class="titulo-servicio">Aplicación</div>
            <div class="descripcion">Gestión de inventario</div>
            <div class="etiqueta">LDAP · admins / gestión</div>
        </a>
    </div>

    <div class="pie">
        CIFP Avilés · ASIR · Proyecto 232V
    </div>

</body>
</html>
