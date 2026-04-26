$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

& node (Join-Path $PSScriptRoot 'check-member-request-refactor.js')
if ($LASTEXITCODE -ne 0) {
    throw 'Member request refactor checks failed.'
}
