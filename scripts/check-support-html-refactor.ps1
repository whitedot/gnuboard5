$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$targetFiles = @(
    (Join-Path $projectRoot 'lib\support\html.lib.php')
    (Join-Path $projectRoot 'alert.php')
    (Join-Path $projectRoot 'alert_close.php')
    (Join-Path $projectRoot 'confirm.php')
)

foreach ($file in $targetFiles) {
    & php -l $file | Out-Null
    if ($LASTEXITCODE -ne 0) {
        throw "PHP lint failed: $file"
    }
}

$legacyAlertTemplateMatches = Select-String -Path (Join-Path $projectRoot 'alert.php') -Pattern '\$msg\b|\$msg2\b|\$url\b|\$post\b|\$_POST|\$error\b|\$header2\b'
if ($legacyAlertTemplateMatches) {
    $details = $legacyAlertTemplateMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy alert template locals found:`n" + ($details -join "`n"))
}

$legacyAlertCloseTemplateMatches = Select-String -Path (Join-Path $projectRoot 'alert_close.php') -Pattern '\$msg\b|\$msg2\b|\$msg3\b|\$error\b|\$header2\b'
if ($legacyAlertCloseTemplateMatches) {
    $details = $legacyAlertCloseTemplateMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy alert close template locals found:`n" + ($details -join "`n"))
}

$legacyConfirmTemplateMatches = Select-String -Path (Join-Path $projectRoot 'confirm.php') -Pattern '\$msg\b|\$header\b|\$url1\b|\$url2\b|\$url3\b'
if ($legacyConfirmTemplateMatches) {
    $details = $legacyConfirmTemplateMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy confirm template locals found:`n" + ($details -join "`n"))
}

$missingAlertViewBuilder = Select-String -Path (Join-Path $projectRoot 'lib\support\html.lib.php') -Pattern 'build_alert_page_view\(|build_alert_close_page_view\(|build_confirm_page_view\('
if (($missingAlertViewBuilder | Measure-Object).Count -lt 3) {
    throw 'missing support html view builders'
}

Write-Output 'Support html refactor checks passed.'
