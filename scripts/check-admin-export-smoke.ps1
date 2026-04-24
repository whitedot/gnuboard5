$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

& php -r "exit(class_exists('ZipArchive') ? 0 : 2);"
if ($LASTEXITCODE -eq 2) {
    Write-Output 'Admin export smoke check skipped: ZipArchive extension is not available in CLI.'
    exit 0
}
if ($LASTEXITCODE -ne 0) {
    throw 'Unable to inspect ZipArchive availability.'
}

& php (Join-Path $PSScriptRoot 'check-admin-export-smoke.php')
if ($LASTEXITCODE -ne 0) {
    throw 'Admin export smoke check failed.'
}
