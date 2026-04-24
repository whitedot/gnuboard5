$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$targetFiles = @(
    (Join-Path $projectRoot 'common.php')
    (Join-Path $projectRoot 'lib\common.crypto.lib.php')
    (Join-Path $projectRoot 'lib\bootstrap\runtime.lib.php')
    (Join-Path $projectRoot 'lib\bootstrap\core.lib.php')
    (Join-Path $projectRoot 'lib\common.data.lib.php')
    (Join-Path $projectRoot 'head.sub.php')
    (Join-Path $projectRoot 'lib\domain\member\page-shell.lib.php')
)

foreach ($file in $targetFiles) {
    & php -l $file | Out-Null
    if ($LASTEXITCODE -ne 0) {
        throw "PHP lint failed: $file"
    }
}

$legacySubjectSortGlobals = Select-String -Path (Join-Path $projectRoot 'lib\common.data.lib.php') -Pattern 'global \$sst,\s*\$sod,\s*\$sfl,\s*\$stx,\s*\$page,\s*\$sca'
if ($legacySubjectSortGlobals) {
    $details = $legacySubjectSortGlobals | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy subject_sort_link globals found:`n" + ($details -join "`n"))
}

$missingRuntimeQueryHelper = -not (Select-String -Path (Join-Path $projectRoot 'lib\bootstrap\runtime.lib.php') -Pattern 'function g5_get_runtime_query_state\(' -Quiet)
if ($missingRuntimeQueryHelper) {
    throw 'missing runtime query state helper'
}

$missingRuntimeTableHelper = -not (Select-String -Path (Join-Path $projectRoot 'lib\bootstrap\core.lib.php') -Pattern 'function g5_get_runtime_table_name\(' -Quiet)
if ($missingRuntimeTableHelper) {
    throw 'missing runtime table name helper'
}

$runtimeLibPath = Join-Path $projectRoot 'lib\bootstrap\runtime.lib.php'
$legacyRuntimeExtractHooks = Select-String -Path $runtimeLibPath -Pattern 'g5_apply_legacy_request_global_extract\(|function g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|function g5_should_extract_request_globals\(|g5_extract_request_globals\(|function g5_extract_request_globals\(|g5_get_runtime_entrypoint_path\(|function g5_get_runtime_entrypoint_path\(|g5_get_request_global_extract_allow_paths\(|function g5_get_request_global_extract_allow_paths\(|G5_FORCE_REQUEST_GLOBAL_EXTRACT|G5_SKIP_REQUEST_GLOBAL_EXTRACT'
if ($legacyRuntimeExtractHooks) {
    $details = $legacyRuntimeExtractHooks | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy request extract hooks found:`n" + ($details -join "`n"))
}

$legacyRuntimeQueryBuilderSignature = -not (Select-String -Path (Join-Path $projectRoot 'lib\bootstrap\runtime.lib.php') -Pattern 'function g5_build_query_state\(array \$source,\s*\$request_uri = ' -Quiet)
if ($legacyRuntimeQueryBuilderSignature) {
    throw 'legacy runtime query builder signature found'
}

$legacyRuntimeQueryRequestAccess = Select-String -Path (Join-Path $projectRoot 'lib\bootstrap\runtime.lib.php') -Pattern '\$_REQUEST\['
if ($legacyRuntimeQueryRequestAccess) {
    $details = $legacyRuntimeQueryRequestAccess | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy runtime query request access found:`n" + ($details -join "`n"))
}

$runtimeExtractHead = Get-Content -Path (Join-Path $projectRoot 'lib\bootstrap\runtime.lib.php') -TotalCount 20
$legacyExtractKeys = $runtimeExtractHead | Select-String -Pattern "'gr_id'|'sop'|'spt'"
if ($legacyExtractKeys) {
    $details = $legacyExtractKeys | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy request global extract keys found:`n" + ($details -join "`n"))
}

$missingSubjectSortQueryHelper = -not (Select-String -Path (Join-Path $projectRoot 'lib\common.data.lib.php') -Pattern 'build_subject_sort_link_query_state\(' -Quiet)
if ($missingSubjectSortQueryHelper) {
    throw 'missing subject sort query helper'
}

$legacySubjectSortFallbackGlobals = Select-String -Path (Join-Path $projectRoot 'lib\common.data.lib.php') -Pattern '\$GLOBALS\['
if ($legacySubjectSortFallbackGlobals) {
    $details = $legacySubjectSortFallbackGlobals | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy subject sort fallback globals found:`n" + ($details -join "`n"))
}

$legacyCommonCryptoMemberTableGlobals = Select-String -Path (Join-Path $projectRoot 'lib\common.crypto.lib.php') -SimpleMatch -Pattern "`$GLOBALS['g5']['member_table']"
if ($legacyCommonCryptoMemberTableGlobals) {
    $details = $legacyCommonCryptoMemberTableGlobals | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy common crypto member table globals found:`n" + ($details -join "`n"))
}

$legacyHeadScaAlias = Select-String -Path (Join-Path $projectRoot 'head.sub.php'), (Join-Path $projectRoot 'lib\domain\member\page-shell.lib.php') -Pattern '\$sca\b'
if ($legacyHeadScaAlias) {
    $details = $legacyHeadScaAlias | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy sca alias access found:`n" + ($details -join "`n"))
}

$legacyCommonAliasAssignments = Select-String -Path (Join-Path $projectRoot 'common.php') -Pattern '\$qstr\s*=\s*\$request_state|\$sca\s*=\s*\$request_state|\$sfl\s*=\s*\$request_state|\$stx\s*=\s*\$request_state|\$sst\s*=\s*\$request_state|\$sod\s*=\s*\$request_state|\$sop\s*=\s*\$request_state|\$spt\s*=\s*\$request_state|\$page\s*=\s*\$request_state|\$w\s*=\s*\$request_state|\$url\s*=\s*\$request_state|\$urlencode\s*=\s*\$request_state|\$gr_id\s*=\s*\$request_state'
if ($legacyCommonAliasAssignments) {
    $details = $legacyCommonAliasAssignments | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy common query alias assignments found:`n" + ($details -join "`n"))
}

$legacyCommonRequestExtractCalls = Select-String -Path (Join-Path $projectRoot 'common.php') -Pattern 'g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|g5_extract_request_globals\('
if ($legacyCommonRequestExtractCalls) {
    $details = $legacyCommonRequestExtractCalls | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy common request extract calls found:`n" + ($details -join "`n"))
}

$repoPhpFiles = Get-ChildItem -Path $projectRoot -Recurse -Filter *.php -File |
    Where-Object {
        $_.FullName -notmatch '\\vendor\\' -and
        $_.FullName -notmatch '\\plugin\\' -and
        $_.FullName -notmatch '\\lib\\PHPExcel\\'
    }

$legacyRequestExtractDefineMatches = $repoPhpFiles |
    Select-String -Pattern "G5_FORCE_REQUEST_GLOBAL_EXTRACT|G5_SKIP_REQUEST_GLOBAL_EXTRACT"
if ($legacyRequestExtractDefineMatches) {
    $details = $legacyRequestExtractDefineMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy request extract define usage found:`n" + ($details -join "`n"))
}

$legacyRequestExtractCallMatches = $repoPhpFiles |
    Select-String -Pattern 'g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|g5_extract_request_globals\('
if ($legacyRequestExtractCallMatches) {
    $details = $legacyRequestExtractCallMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy request extract call usage found:`n" + ($details -join "`n"))
}

Write-Output 'Common query refactor checks passed.'
