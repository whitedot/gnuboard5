$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

& node (Join-Path $PSScriptRoot 'check-admin-refactor.js')
if ($LASTEXITCODE -ne 0) {
    throw 'Admin refactor checks failed.'
}
