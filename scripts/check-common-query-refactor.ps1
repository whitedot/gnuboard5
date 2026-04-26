$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

& node (Join-Path $PSScriptRoot 'check-common-query-refactor.js')
if ($LASTEXITCODE -ne 0) {
    throw 'Common query refactor checks failed.'
}
