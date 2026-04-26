$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

& node (Join-Path $PSScriptRoot 'check-legacy-request-extract-retirement.js')
if ($LASTEXITCODE -ne 0) {
    throw 'Legacy request extract retirement checks failed.'
}
