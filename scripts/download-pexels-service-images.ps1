param(
    [string]$EnvironmentFile = (Join-Path $PSScriptRoot '..\.env'),
    [string]$OutputDirectory = (Join-Path $PSScriptRoot '..\public\img\service-types')
)

$ErrorActionPreference = 'Stop'

$serviceTypes = @(
    @{ id = 7;  name = 'Carpintería';                           slug = 'carpinteria';                  query = 'professional carpenter woodworking' },
    @{ id = 8;  name = 'Cerrajería';                            slug = 'cerrajeria';                   query = 'professional locksmith door lock' },
    @{ id = 9;  name = 'Limpieza a domicilio';                  slug = 'limpieza-a-domicilio';         query = 'professional house cleaning' },
    @{ id = 10; name = 'Albañilería';                           slug = 'albanileria';                   query = 'professional bricklayer masonry' },
    @{ id = 11; name = 'Tarrajeo';                              slug = 'tarrajeo';                      query = 'professional wall plastering' },
    @{ id = 12; name = 'Jardinería';                            slug = 'jardineria';                    query = 'professional gardener landscaping' },
    @{ id = 13; name = 'Reparación de electrodomésticos';       slug = 'reparacion-electrodomesticos';  query = 'appliance repair technician' },
    @{ id = 14; name = 'Instalación de equipos';                slug = 'instalacion-equipos';           query = 'home equipment installation technician' },
    @{ id = 15; name = 'Mudanzas y transporte';                 slug = 'mudanzas-transporte';           query = 'professional moving service boxes truck' },
    @{ id = 16; name = 'Mantenimiento de piscinas';             slug = 'mantenimiento-piscinas';        query = 'swimming pool maintenance professional' },
    @{ id = 17; name = 'Fumigación y control de plagas';        slug = 'control-plagas';                query = 'pest control worker spraying house' },
    @{ id = 18; name = 'Decoración de interiores';              slug = 'decoracion-interiores';         query = 'interior designer modern home' },
    @{ id = 19; name = 'Servicios de remodelación';             slug = 'servicios-remodelacion';        query = 'professional home remodeling construction' },
    @{ id = 20; name = 'Techado y reparación de tejados';       slug = 'reparacion-tejados';            query = 'professional roofer roof repair' },
    @{ id = 21; name = 'Servicio de seguridad';                 slug = 'servicio-seguridad';            query = 'home security professional camera installation' },
    @{ id = 22; name = 'Asesoría y diseño arquitectónico';      slug = 'diseno-arquitectonico';         query = 'architect working blueprints modern house' },
    @{ id = 23; name = 'Reformas integrales';                   slug = 'reformas-integrales';           query = 'home renovation professional interior construction' },
    @{ id = 24; name = 'Pintura';                               slug = 'pintura';                       query = 'professional house painter wall' },
    @{ id = 25; name = 'Vaciado de pisos';                      slug = 'vaciado-pisos';                 query = 'apartment cleanout moving boxes empty room' },
    @{ id = 26; name = 'Electricista';                          slug = 'electricista';                  query = 'professional electrician working home' },
    @{ id = 27; name = 'Fontanería';                            slug = 'fontaneria';                    query = 'professional plumber working sink' },
    @{ id = 28; name = 'Alarmas';                               slug = 'alarmas';                       query = 'burglar alarm keypad security installation' },
    @{ id = 29; name = 'Luz';                                   slug = 'luz';                           query = 'professional lighting installation home' },
    @{ id = 30; name = 'Gas';                                   slug = 'gas';                           query = 'gas boiler repair technician' },
    @{ id = 31; name = 'Internet';                              slug = 'internet';                      query = 'internet technician installing router home' },
    @{ id = 32; name = 'Telefonía';                             slug = 'telefonia';                     query = 'telecommunications technician wiring' },
    @{ id = 33; name = 'Cristalería';                           slug = 'cristaleria';                   query = 'glazier installing glass window' },
    @{ id = 34; name = 'Camperizaciones';                       slug = 'camperizaciones';               query = 'camper van conversion interior workshop' }
)

if (-not (Test-Path -LiteralPath $EnvironmentFile)) {
    throw "No se encontró el archivo de entorno: $EnvironmentFile"
}

$apiKeyLine = Get-Content -LiteralPath $EnvironmentFile |
    Where-Object { $_ -match '^PEXELS_API_KEY=' } |
    Select-Object -First 1

$apiKey = if ($apiKeyLine) { ($apiKeyLine -replace '^PEXELS_API_KEY=', '').Trim().Trim('"').Trim("'") } else { '' }
if ([string]::IsNullOrWhiteSpace($apiKey)) {
    throw 'PEXELS_API_KEY no está configurada en el archivo .env.'
}

New-Item -ItemType Directory -Path $OutputDirectory -Force | Out-Null

$headers = @{ Authorization = $apiKey }
$usedPhotoIds = @{}
$credits = @()

foreach ($serviceType in $serviceTypes) {
    $encodedQuery = [uri]::EscapeDataString($serviceType.query)
    $searchUrl = "https://api.pexels.com/v1/search?query=$encodedQuery&orientation=landscape&size=large&per_page=15"
    $response = Invoke-RestMethod -Uri $searchUrl -Headers $headers -Method Get
    $photo = $response.photos |
        Where-Object { -not $usedPhotoIds.ContainsKey([string]$_.id) } |
        Select-Object -First 1

    if (-not $photo) {
        throw "Pexels no devolvió una imagen nueva para $($serviceType.name)."
    }

    $usedPhotoIds[[string]$photo.id] = $true
    $sourceBaseUrl = ([string]$photo.src.original -split '\?')[0]
    $downloadUrl = "$sourceBaseUrl`?auto=compress&cs=tinysrgb&w=1600&h=1000&fit=crop"
    $filename = ('{0:D2}-{1}.jpg' -f [int]$serviceType.id, $serviceType.slug)
    $destination = Join-Path $OutputDirectory $filename

    Invoke-WebRequest -Uri $downloadUrl -OutFile $destination

    $credits += [ordered]@{
        service_type_id = [int]$serviceType.id
        service_type = $serviceType.name
        filename = $filename
        width = 1600
        height = 1000
        pexels_photo_id = [int64]$photo.id
        photographer = [string]$photo.photographer
        photographer_url = [string]$photo.photographer_url
        photo_url = [string]$photo.url
        alt = [string]$photo.alt
    }

    Write-Host ("Descargada {0}: {1}" -f $serviceType.name, $filename)
}

$manifestPath = Join-Path $OutputDirectory 'credits.json'
$credits | ConvertTo-Json -Depth 4 | Set-Content -LiteralPath $manifestPath -Encoding utf8

Write-Host ("Completadas {0} imágenes en {1}" -f $credits.Count, $OutputDirectory)
