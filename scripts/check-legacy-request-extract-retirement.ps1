$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$auditedFiles = New-Object System.Collections.Generic.List[string]

$rootPhpFiles = Get-ChildItem -Path $projectRoot -Filter *.php -File
foreach ($file in $rootPhpFiles) {
    $auditedFiles.Add($file.FullName)
}

if (Test-Path (Join-Path $projectRoot 'install')) {
    $installPhpFiles = Get-ChildItem -Path (Join-Path $projectRoot 'install') -Recurse -Filter *.php -File
    foreach ($file in $installPhpFiles) {
        $auditedFiles.Add($file.FullName)
    }
}

if (Test-Path (Join-Path $projectRoot 'plugin')) {
    $pluginPhpFiles = Get-ChildItem -Path (Join-Path $projectRoot 'plugin') -Recurse -Filter *.php -File |
        Where-Object {
            $_.FullName -notmatch '\\plugin\\htmlpurifier\\' -and
            $_.FullName -notmatch '\\plugin\\PHPMailer\\'
        }

    foreach ($file in $pluginPhpFiles) {
        $auditedFiles.Add($file.FullName)
    }
}

$auditedFiles = $auditedFiles | Sort-Object -Unique

foreach ($file in $auditedFiles) {
    & php -l $file | Out-Null
    if ($LASTEXITCODE -ne 0) {
        throw "PHP lint failed: $file"
    }
}

$legacyExtractDefineMatches = $auditedFiles |
    Select-String -Pattern 'G5_FORCE_REQUEST_GLOBAL_EXTRACT|G5_SKIP_REQUEST_GLOBAL_EXTRACT'
if ($legacyExtractDefineMatches) {
    $details = $legacyExtractDefineMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy request extract define usage found:`n" + ($details -join "`n"))
}

$legacyExtractCallMatches = $auditedFiles |
    Select-String -Pattern 'g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|g5_extract_request_globals\('
if ($legacyExtractCallMatches) {
    $details = $legacyExtractCallMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy request extract call usage found:`n" + ($details -join "`n"))
}

$hiddenAliasPatterns = @(
    'isset\(\$(w|url|sfl|stx|sst|sod|page|sca)\)'
    'empty\(\$(w|url|sfl|stx|sst|sod|page|sca)\)'
    '!empty\(\$(w|url|sfl|stx|sst|sod|page|sca)\)'
    'global \$(w|url|sfl|stx|sst|sod|page|sca)\b'
    '\$GLOBALS\[(?:''|")(w|url|sfl|stx|sst|sod|page|sca)(?:''|")\]'
)

$hiddenAliasMatches = $auditedFiles |
    Where-Object { $_ -ne (Join-Path $projectRoot 'common.php') } |
    Select-String -Pattern $hiddenAliasPatterns
if ($hiddenAliasMatches) {
    $details = $hiddenAliasMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("implicit request alias usage found in retirement scope:`n" + ($details -join "`n"))
}

Write-Output 'Legacy request extract retirement checks passed.'
