$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

& node (Join-Path $PSScriptRoot 'check-legacy-phpexcel-retirement.js')
if ($LASTEXITCODE -ne 0) {
    throw 'Legacy PHPExcel retirement checks failed.'
}
