$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$auditedFiles = Get-ChildItem -Path $projectRoot -Recurse -Filter *.php -File |
    Where-Object {
        $_.FullName -notmatch '\\lib\\PHPExcel\\' -and
        $_.FullName -ne (Join-Path $projectRoot 'lib\PHPExcel.php') -and
        $_.FullName -notmatch '\\plugin\\htmlpurifier\\' -and
        $_.FullName -notmatch '\\plugin\\PHPMailer\\'
    }

foreach ($file in $auditedFiles) {
    & php -l $file.FullName | Out-Null
    if ($LASTEXITCODE -ne 0) {
        throw "PHP lint failed: $($file.FullName)"
    }
}

$matches = $auditedFiles | Select-String -Pattern 'PHPExcel|PHPEXCEL_ROOT'
if ($matches) {
    $details = $matches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy PHPExcel runtime references found:`n" + ($details -join "`n"))
}

Write-Output 'Legacy PHPExcel retirement checks passed.'
