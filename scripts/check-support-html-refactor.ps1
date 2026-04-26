$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

& node (Join-Path $PSScriptRoot 'check-support-html-refactor.js')
if ($LASTEXITCODE -ne 0) {
    throw 'Support html refactor checks failed.'
}
