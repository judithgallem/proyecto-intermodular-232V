<#
- Función: Automatización completa de la infraestructura de usuarios y grupos.
- Autora: Judith Gallego Martínez 
- Fecha: 29/04/2026
#>

Import-Module ActiveDirectory

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#                Variables  
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$domainDNS = "asi-232V.cifpaviles.com"
$domainDN  = "DC=asi-232V,DC=cifpaviles,DC=com"
$csvPath   = "C:\temp\usuarios.csv"
$passwordSecret = ConvertTo-SecureString "Password2026" -AsPlainText -Force

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#                OU Raíz  
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "~~~ Creando estructura de OUs ~~~" -ForegroundColor Magenta

$OUs = @(
    "OU=usuarios,$domainDN",
    "OU=empresa,OU=usuarios,$domainDN",
    "OU=clientes,OU=usuarios,$domainDN",
    "OU=gestion,OU=empresa,OU=usuarios,$domainDN",
    "OU=tecnicos,OU=empresa,OU=usuarios,$domainDN",
    "OU=admins,OU=empresa,OU=usuarios,$domainDN",
    "OU=N1,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN",
    "OU=N2,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN",
    "OU=N3,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"
)

foreach ($ou in $OUs) {

    # Comprueba si la OU ya existe
    $existe = Get-ADOrganizationalUnit `
        -Filter "DistinguishedName -eq '$ou'" `
        -ErrorAction SilentlyContinue

    # Obtiene nombre y ruta padre de la OU
    if (-not $existe) {

        $nombre = ($ou -split ',')[0].Replace("OU=","")
        $camino = $ou.Substring($ou.IndexOf(",") + 1)

        try {

            New-ADOrganizationalUnit `
                -Name $nombre `
                -Path $camino `
                -ProtectedFromAccidentalDeletion $true

            Write-Host "OU creada: $nombre" -ForegroundColor Cyan

        } catch {

            Write-Host "ERROR creando OU: $nombre" -ForegroundColor Red
        }

    } else {

        Write-Host "OU existente: $ou (Omitiendo)" -ForegroundColor Gray
    }
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#            Creación de Grupos  
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "`n~~~ Creando Grupos ~~~" -ForegroundColor DarkMagenta

$gruposConfig = @(
    @{Name="usuariosASI-232V"; Path="OU=usuarios,$domainDN"},
    @{Name="empresa"; Path="OU=empresa,OU=usuarios,$domainDN"},
    @{Name="clientes"; Path="OU=clientes,OU=usuarios,$domainDN"},
    @{Name="gestion"; Path="OU=gestion,OU=empresa,OU=usuarios,$domainDN"},
    @{Name="tecnicos"; Path="OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"},
    @{Name="N1"; Path="OU=N1,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"},
    @{Name="N2"; Path="OU=N2,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"},
    @{Name="N3"; Path="OU=N3,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"},
    @{Name="admins"; Path="OU=admins,OU=empresa,OU=usuarios,$domainDN"},
    @{Name="adminsEstaciones"; Path="OU=usuarios,$domainDN"}
)

foreach ($g in $gruposConfig) {

    $grupoExiste = Get-ADGroup `
        -Filter "Name -eq '$($g.Name)'" `
        -ErrorAction SilentlyContinue

    if (-not $grupoExiste) {

        # Crea grupo de seguridad global
        New-ADGroup `
            -Name $g.Name `
            -GroupScope Global `
            -GroupCategory Security `
            -Path $g.Path

        Write-Host "Grupo creado: $($g.Name)" -ForegroundColor Cyan

    } else {

        Write-Host "Grupo existente: $($g.Name) (Omitiendo)" -ForegroundColor Gray
    }
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#          Carga de CSV y Usuarios  
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "`n~~~ Procesando Usuarios ~~~" -ForegroundColor DarkMagenta

if (Test-Path $csvPath) {

    $usuarios = Import-Csv -Path $csvPath -Delimiter ","

    foreach($u in $usuarios){

        $samAccount = $u.Usuario.Trim()

        Write-Host "> TRATANDO USUARIO: $samAccount" -ForegroundColor Magenta
        
        try {

            #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            #       Comprobación de existencia
            #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

            # SamAccountName: nombre interno del usuario en AD
            $usuarioExistente = Get-ADUser `
                -Filter "SamAccountName -eq '$samAccount'" `
                -ErrorAction SilentlyContinue

            #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            #            Creación de usuario
            #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

            if (-not $usuarioExistente) {

                New-ADUser `
                    -Name "$($u.Nombre.Trim()) $($u.Apellidos.Trim())" `
                    -DisplayName "$($u.Nombre.Trim()) $($u.Apellidos.Trim())" `
                    -GivenName $u.Nombre.Trim() `
                    -Surname $u.Apellidos.Trim() `
                    -SamAccountName $samAccount `
                    -UserPrincipalName "$samAccount@$domainDNS" `
                    -EmailAddress "$samAccount@$domainDNS" `
                    -Path $u.OU.Trim() `
                    -AccountPassword $passwordSecret `
                    -Enabled $true `
                    -PasswordNeverExpires $true

                Write-Host "  [OK] Usuario creado: $samAccount" -ForegroundColor Cyan

            } else {

                #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                #          Actualización de usuario
                #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

                Set-ADUser `
                    -Identity $samAccount `
                    -GivenName $u.Nombre.Trim() `
                    -Surname $u.Apellidos.Trim() `
                    -DisplayName "$($u.Nombre.Trim()) $($u.Apellidos.Trim())" `
                    -EmailAddress "$samAccount@$domainDNS"

                Write-Host "  [OK] Usuario actualizado: $samAccount" -ForegroundColor Yellow
            }

            #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            #        Asignación a grupo principal
            #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

            if ($u.Grupo) {

                # Obtiene miembros de un grupo AD
                # Filtra: si no pertenece, se añade
                $miembroExiste = Get-ADGroupMember `
                    -Identity $u.Grupo `
                    -ErrorAction SilentlyContinue |
                    Where-Object {
                        $_.SamAccountName -eq $samAccount
                    }

                if (-not $miembroExiste) {

                    Add-ADGroupMember `
                        -Identity $u.Grupo `
                        -Members $samAccount `
                        -ErrorAction SilentlyContinue

                    Write-Host "  [+] Añadido a grupo: $($u.Grupo)" -ForegroundColor Cyan

                } else {

                    Write-Host "  [=] Ya pertenece a: $($u.Grupo)" -ForegroundColor Gray
                }
            }

        } catch {

            Write-Host "   [ERROR] En $samAccount $($_.Exception.Message)" -ForegroundColor Red
        }
    }

} else {

    Write-Host "ERROR: No se encuentra el archivo CSV en $csvPath" -ForegroundColor Red
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#          Jerarquía y Anidación
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "`n~~~ Estableciendo Jerarquía y Roles de Sistema ~~~" -ForegroundColor DarkMagenta

$anidaciones = @(
    @{Grupo="usuariosASI-232V"; Miembros=@("empresa","clientes")},
    @{Grupo="empresa"; Miembros=@("gestion","tecnicos","admins")},
    @{Grupo="tecnicos"; Miembros=@("N1","N2","N3")},
    @{Grupo="adminsEstaciones"; Miembros=@("N2")}
)

foreach ($a in $anidaciones) {

    foreach ($miembro in $a.Miembros) {

        $existe = Get-ADGroupMember `
            -Identity $a.Grupo `
            -ErrorAction SilentlyContinue |
            Where-Object {
                $_.SamAccountName -eq $miembro
            }

        if (-not $existe) {

            Add-ADGroupMember `
                -Identity $a.Grupo `
                -Members $miembro `
                -ErrorAction SilentlyContinue

            Write-Host " [+] $miembro -> $($a.Grupo)" -ForegroundColor Cyan

        } else {

            Write-Host " [=] $miembro ya pertenece a $($a.Grupo)" -ForegroundColor Gray
        }
    }
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#     Vinculación con grupos del sistema
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$gruposPrivilegiados = @(
    @{GrupoSistema="Domain Admins"; Miembro="admins"},
    @{GrupoSistema="Schema Admins"; Miembro="admins"},
    @{GrupoSistema="Enterprise Admins"; Miembro="admins"}
)

foreach ($g in $gruposPrivilegiados) {

    try {

        $existe = Get-ADGroupMember `
            -Identity $g.GrupoSistema `
            -ErrorAction SilentlyContinue |
            Where-Object {
                $_.SamAccountName -eq $g.Miembro
            }

        if (-not $existe) {

            Add-ADGroupMember `
                -Identity $g.GrupoSistema `
                -Members $g.Miembro `
                -ErrorAction SilentlyContinue

            Write-Host " [+] $($g.Miembro) -> $($g.GrupoSistema)" -ForegroundColor Cyan

        } else {

            Write-Host " [=] $($g.Miembro) ya pertenece a $($g.GrupoSistema)" -ForegroundColor Gray
        }

    } catch {

        Write-Host " [ERROR] No se pudo vincular $($g.Miembro) con $($g.GrupoSistema)" -ForegroundColor Red
    }
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#              Finalización
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "`n*************************************" -ForegroundColor DarkCyan
Write-Host "          PROCESO COMPLETADO         " -ForegroundColor Cyan
Write-Host "*************************************" -ForegroundColor DarkCyan