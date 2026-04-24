$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$lintFiles = @(
    Get-ChildItem -Path (Join-Path $projectRoot 'adm') -Filter *.php -File
    Get-ChildItem -Path (Join-Path $projectRoot 'lib\domain\admin') -Filter *.php -File
)

foreach ($file in $lintFiles) {
    & php -l $file.FullName | Out-Null
    if ($LASTEXITCODE -ne 0) {
        throw "PHP lint failed: $($file.FullName)"
    }
}

$forbiddenChecks = @(
    @{
        Name = 'extract() usage'
        Pattern = 'extract\s*\('
        Paths = @(
            (Join-Path $projectRoot 'adm')
            (Join-Path $projectRoot 'lib\domain\admin')
        )
    },
    @{
        Name = 'implicit member list globals'
        Pattern = 'isset\(\$sfl\)|isset\(\$stx\)|isset\(\$sst\)|isset\(\$sod\)|isset\(\$page\)|isset\(\$w\)|isset\(\$url\)'
        Paths = @(
            (Join-Path $projectRoot 'adm')
        )
    },
    @{
        Name = 'legacy admin template helpers'
        Pattern = 'subject_sort_link\s*\(|get_sideview\s*\('
        Paths = @(
            (Join-Path $projectRoot 'adm')
        )
    },
    @{
        Name = '$GLOBALS access'
        Pattern = '\$GLOBALS\['
        Paths = @(
            (Join-Path $projectRoot 'adm')
            (Join-Path $projectRoot 'lib\domain\admin')
        )
    }
)

foreach ($check in $forbiddenChecks) {
    $files = foreach ($path in $check.Paths) {
        Get-ChildItem -Path $path -Filter *.php -File -Recurse
    }

    $matches = $files | Select-String -Pattern $check.Pattern
    if ($matches) {
        $details = $matches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
        throw ($check.Name + " found:`n" + ($details -join "`n"))
    }
}

$legacyTemplateFiles = Get-ChildItem -Path (Join-Path $projectRoot 'adm') -Filter *.php -File -Recurse
$legacyTemplateMatches = $legacyTemplateFiles | Select-String -Pattern 'subject_sort_link\s*\(|get_sideview\s*\('
if ($legacyTemplateMatches) {
    $details = $legacyTemplateMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy admin template helpers found:`n" + ($details -join "`n"))
}

$helperLibFiles = Get-ChildItem -Path (Join-Path $projectRoot 'adm') -Filter *.lib.php -File -Recurse
$helperLibRequestMatches = $helperLibFiles | Select-String -Pattern '\$_GET|\$_POST|\$_REQUEST'
if ($helperLibRequestMatches) {
    $details = $helperLibRequestMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("raw request access inside admin helper libs found:`n" + ($details -join "`n"))
}

$memberFormPartFiles = Get-ChildItem -Path (Join-Path $projectRoot 'adm\member_form_parts') -Filter *.php -File
$memberFormAliasMatches = $memberFormPartFiles | Select-String -Pattern '\$w\b|\$mb\b|\$qstr\b|\$sfl\b|\$stx\b|\$sst\b|\$sod\b|\$page\b|\$member_level_options\b|\$mb_cert_history\b|\$display_mb_id\b|\$mask_preserved_id\b|\$sound_only\b|\$required_mb_id\b|\$required_mb_id_class\b|\$required_mb_password\b|\$mb_certify_yes\b|\$mb_certify_no\b|\$mb_adult_yes\b|\$mb_adult_no\b|\$mb_mailling_yes\b|\$mb_mailling_no\b|\$mb_open_yes\b|\$mb_open_no\b|\$mb_marketing_agree_yes\b|\$mb_marketing_agree_no\b'
if ($memberFormAliasMatches) {
    $details = $memberFormAliasMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member form aliases found:`n" + ($details -join "`n"))
}

$explicitRequestPages = @(
    (Join-Path $projectRoot 'adm\index.php')
    (Join-Path $projectRoot 'adm\member_list.php')
    (Join-Path $projectRoot 'adm\member_form.php')
    (Join-Path $projectRoot 'adm\member_list_update.php')
    (Join-Path $projectRoot 'adm\member_form_update.php')
    (Join-Path $projectRoot 'adm\member_delete.php')
    (Join-Path $projectRoot 'adm\member_list_exel.php')
    (Join-Path $projectRoot 'adm\member_list_exel_export.php')
    (Join-Path $projectRoot 'adm\member_list_file_delete.php')
)
$legacyRequestSkipDefines = foreach ($page in $explicitRequestPages) {
    Select-String -Path $page -Pattern "define\('G5_SKIP_REQUEST_GLOBAL_EXTRACT', true\);"
}
if ($legacyRequestSkipDefines) {
    $details = $legacyRequestSkipDefines | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy request global extract opt-out define found:`n" + ($details -join "`n"))
}

$legacyQueryAliasSkipFiles = foreach ($page in $explicitRequestPages) {
    Select-String -Path $page -Pattern "define\('G5_SKIP_QUERY_STATE_ALIAS_ASSIGNMENT', true\);"
}
if ($legacyQueryAliasSkipFiles) {
    $details = $legacyQueryAliasSkipFiles | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy query state alias opt-out define found:`n" + ($details -join "`n"))
}

$memberExportPageMatches = Select-String -Path (Join-Path $projectRoot 'adm\member_list_exel.php') -Pattern 'onclick="location\.href=''\\?''"|member_list_exel_export\.php\?\$\{query\}'
if ($memberExportPageMatches) {
    $details = $memberExportPageMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member export page wiring found:`n" + ($details -join "`n"))
}

$exportDomainGlobalMatches = Select-String -Path (Join-Path $projectRoot 'lib\domain\admin\export.lib.php') -Pattern 'global \$g5|global \$member'
if ($exportDomainGlobalMatches) {
    $details = $exportDomainGlobalMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy export globals found:`n" + ($details -join "`n"))
}

$adminRequestMatches = Select-String -Path (Join-Path $projectRoot 'lib\domain\admin\bootstrap.lib.php'), (Join-Path $projectRoot 'lib\domain\admin\token.lib.php') -Pattern '\$_REQUEST'
if ($adminRequestMatches) {
    $details = $adminRequestMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy admin request access found:`n" + ($details -join "`n"))
}

$adminSessionMatches = Get-ChildItem -Path (Join-Path $projectRoot 'lib\domain\admin') -Filter *.php -File -Recurse |
    Where-Object { $_.FullName -ne (Join-Path $projectRoot 'lib\domain\admin\token.lib.php') } |
    Select-String -Pattern 'get_session\(|\$_SESSION'
if ($adminSessionMatches) {
    $details = $adminSessionMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy admin session access found:`n" + ($details -join "`n"))
}

Write-Output 'Admin refactor checks passed.'
