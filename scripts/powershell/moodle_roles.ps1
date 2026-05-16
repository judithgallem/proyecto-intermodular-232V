<#
- Función: Configuracion LDAP para Moodle (cursos, roles y matriculaciones)
- Los nuevos grupos se van a anidar en los que existen en mi entorno
- Autora: Judith Gallego Martinez
- Fecha: 14/05/2026
#> 


Import-Module ActiveDirectory

# 
# Dominio
# 

$domainDN = "DC=asi-232V,DC=cifpaviles,DC=com"

# OU principal para los roles de Moodle
$moodleRolesOU = "OU=moodleroles,$domainDN"

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#       Grupos en Active Directory
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
# Se usaran como miembros de los grupos de Moodle.

$grupoN1     = "CN=N1,OU=N1,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"
$grupoN2     = "CN=N2,OU=N2,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"
$grupoN3     = "CN=N3,OU=N3,OU=tecnicos,OU=empresa,OU=usuarios,$domainDN"
$grupoAdmins = "CN=admins,OU=admins,OU=empresa,OU=usuarios,$domainDN"

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#            OUs para Moodle
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$roleOUs = @(
    "Gestor",
    "Estudiante",
    "Creador de curso"
)

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#      Definicion de cursos y accesos
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$cursos = @(
    @{
        ShortName = "DOCSN1"
        FullName  = "Documentos N1"
        Summary   = "Documentacion para tecnicos de ASI-232V, Nivel 1"
        Students  = @($grupoN1, $grupoN2, $grupoN3)
        Managers  = @($grupoAdmins)
    },
    @{
        ShortName = "DOCSN2"
        FullName  = "Documentos N2"
        Summary   = "Documentacion para tecnicos de ASI-232V, Nivel 2"
        Students  = @($grupoN2, $grupoN3)
        Managers  = @($grupoAdmins)
    },
    @{
        ShortName = "DOCSN3"
        FullName  = "Documentos N3"
        Summary   = "Documentacion para tecnicos de ASI-232V, Nivel 3"
        Students  = @($grupoN3)
        Managers  = @($grupoAdmins)
    }
)

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#        Crear OU si no existe
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

function Ensure-OU {
    param (
        [string]$Name,
        [string]$Path
    )
	
	# DistinguishedName completo
    $ouDN = "OU=$Name,$Path"

    $exists = Get-ADOrganizationalUnit `
        -LDAPFilter "(ou=$Name)" `
        -SearchBase $Path `
        -SearchScope OneLevel `
        -ErrorAction SilentlyContinue

    if (-not $exists) {
        New-ADOrganizationalUnit `
            -Name $Name `
            -Path $Path `
            -ProtectedFromAccidentalDeletion $false

        Write-Host "OU creada: $ouDN" -ForegroundColor Cyan
    } else {
        Write-Host "OU ya existe: $ouDN" -ForegroundColor Gray
    }

    return $ouDN
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#      Crear grupo si no existe
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
# Se usa samaccountname diferente 

function Ensure-Group {
    param (
        [string]$Name,
        [string]$SamAccountName,
        [string]$Path,
        [string]$Description,
        [string]$Info
    )

    $group = Get-ADGroup `
        -LDAPFilter "(cn=$Name)" `
        -SearchBase $Path `
        -SearchScope OneLevel `
        -Properties description,info `
        -ErrorAction SilentlyContinue

    if (-not $group) {
        New-ADGroup `
            -Name $Name `
            -SamAccountName $SamAccountName `
            -GroupCategory Security `
            -GroupScope Global `
            -Path $Path `
            -Description $Description

        $group = Get-ADGroup `
            -Identity "CN=$Name,$Path" `
            -Properties description,info

        Write-Host "Grupo creado: CN=$Name,$Path ; sAMAccountName=$SamAccountName" -ForegroundColor Cyan
    } else {
        Write-Host "Grupo ya existe: CN=$Name,$Path" -ForegroundColor Gray
    }

    # Actualiza los atributos que Moodle usara para crear los cursos.
    Set-ADObject `
        -Identity $group.DistinguishedName `
        -Replace @{
            description = $Description
            info        = $Info
        }

    return $group.DistinguishedName
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#             Anadir miembros 
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

function Add-MemberSafe {
    param (
        [string]$TargetGroupDN,
        [string[]]$Members
    )

    foreach ($member in $Members) {
        try {
            Add-ADGroupMember `
                -Identity $TargetGroupDN `
                -Members $member `
                -ErrorAction Stop

            Write-Host "Miembro anadido: $member -> $TargetGroupDN" -ForegroundColor Cyan
        }
        catch {
            if ($_.Exception.Message -like "*already a member*") {
                Write-Host "Ya era miembro: $member -> $TargetGroupDN" -ForegroundColor Gray
            } else {
                Write-Host "ERROR anadiendo $member -> $TargetGroupDN" -ForegroundColor Red
                Write-Host $_.Exception.Message -ForegroundColor Red
            }
        }
    }
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#      Creacion de estructura LDAP
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "~~~ Creando OU principal de Moodle ~~~" -ForegroundColor DarkMagenta
Ensure-OU -Name "moodleroles" -Path $domainDN | Out-Null

Write-Host "`n~~~ Creando OUs necesarias de roles ~~~" -ForegroundColor DarkMagenta
foreach ($roleOU in $roleOUs) {
    Ensure-OU -Name $roleOU -Path $moodleRolesOU | Out-Null
}

$gestorOU       = "OU=Gestor,$moodleRolesOU"
$estudianteOU   = "OU=Estudiante,$moodleRolesOU"
$creadorCursoOU = "OU=Creador de curso,$moodleRolesOU"

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
# Creacion de grupos de curso y matriculaciones
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "`n~~~ Creando grupos de cursos para Estudiante y Gestor ~~~" -ForegroundColor DarkMagenta

foreach ($curso in $cursos) {
    $shortName = $curso.ShortName

    # Grupo del curso para rol Estudiante/Tecnico
    $grupoEstudianteDN = Ensure-Group `
        -Name $shortName `
        -SamAccountName "$shortName-est" `
        -Path $estudianteOU `
        -Description $curso.FullName `
        -Info $curso.Summary

    Add-MemberSafe `
        -TargetGroupDN $grupoEstudianteDN `
        -Members $curso.Students

    # Grupo del curso para rol Gestor/Administrador
    $grupoGestorDN = Ensure-Group `
        -Name $shortName `
        -SamAccountName "$shortName-ges" `
        -Path $gestorOU `
        -Description $curso.FullName `
        -Info $curso.Summary

    Add-MemberSafe `
        -TargetGroupDN $grupoGestorDN `
        -Members $curso.Managers
}

#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#     Grupo  creadores de curso
#~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Write-Host "`n~~~ Creando grupo de creadores de curso ~~~" -ForegroundColor DarkMagenta

$creadorCursoDN = Ensure-Group `
    -Name "crea-cursos" `
    -SamAccountName "crea-cursos" `
    -Path $creadorCursoOU `
    -Description "Creadores de curso Moodle" `
    -Info "Usuarios autorizados para crear cursos en Moodle"

Add-MemberSafe `
    -TargetGroupDN $creadorCursoDN `
    -Members @($grupoAdmins, $grupoN3)

Write-Host "`n~~~ Configuracion LDAP para Moodle completada ~~~" -ForegroundColor Cyan
