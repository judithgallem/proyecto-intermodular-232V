<#
- Función: Exportar atributos LDAP más concretos
- Autora: Judith Gallego Martínez
- Fecha: 10/05/2026
#>

Import-Module ActiveDirectory

$baseOU = "OU=empresa,OU=usuarios,DC=asi-232V,DC=cifpaviles,DC=com"

Write-Host "~~~ EXPORT LDAP PARA MOODLE ~~~" -ForegroundColor Magenta


Get-ADUser `
    -Filter * `
    -SearchBase $baseOU `
    -Properties mail,memberOf |
    
Select-Object `
    SamAccountName,
    GivenName,
    Surname,
    mail,
    DistinguishedName,
    MemberOf |

Format-List