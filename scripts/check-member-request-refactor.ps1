$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$targetFiles = @(
    (Join-Path $projectRoot 'lib\domain\member\request.lib.php')
    (Join-Path $projectRoot 'lib\domain\member\request-auth.lib.php')
    (Join-Path $projectRoot 'lib\domain\member\request-account.lib.php')
    (Join-Path $projectRoot 'lib\domain\member\request-register.lib.php')
    (Join-Path $projectRoot 'lib\domain\member\page-controller.lib.php')
    (Join-Path $projectRoot 'lib\domain\member\render-page-view.lib.php')
    (Join-Path $projectRoot 'lib\domain\member\render-register-form.lib.php')
    (Join-Path $projectRoot 'member\login.php')
    (Join-Path $projectRoot 'member\login_check.php')
    (Join-Path $projectRoot 'member\logout.php')
    (Join-Path $projectRoot 'member\member_confirm.php')
    (Join-Path $projectRoot 'member\member_leave.php')
    (Join-Path $projectRoot 'member\member_cert_refresh.php')
    (Join-Path $projectRoot 'member\member_cert_refresh_update.php')
    (Join-Path $projectRoot 'member\register_form.php')
    (Join-Path $projectRoot 'member\register_form_update.php')
    (Join-Path $projectRoot 'member\email_certify.php')
    (Join-Path $projectRoot 'member\email_stop.php')
    (Join-Path $projectRoot 'member\ajax.mb_email.php')
    (Join-Path $projectRoot 'member\ajax.mb_hp.php')
    (Join-Path $projectRoot 'member\ajax.mb_id.php')
    (Join-Path $projectRoot 'member\ajax.mb_nick.php')
    (Join-Path $projectRoot 'member\password_lost2.php')
    (Join-Path $projectRoot 'member\register_email.php')
    (Join-Path $projectRoot 'member\register_email_update.php')
    (Join-Path $projectRoot 'member\password_reset.php')
    (Join-Path $projectRoot 'member\password_reset_update.php')
    (Join-Path $projectRoot 'member\password_lost_certify.php')
    (Join-Path $projectRoot 'member\password_lost.php')
    (Join-Path $projectRoot 'member\register_email.php')
    (Join-Path $projectRoot 'member\register_result.php')
    (Join-Path $projectRoot 'member\register.php')
    (Join-Path $projectRoot 'member\views\basic\password_reset.skin.php')
    (Join-Path $projectRoot 'member\views\basic\password_lost.skin.php')
    (Join-Path $projectRoot 'member\views\basic\member_cert_refresh.skin.php')
    (Join-Path $projectRoot 'member\views\basic\register_form.skin.php')
    (Join-Path $projectRoot 'member\views\basic\login.skin.php')
    (Join-Path $projectRoot 'member\views\basic\register_email.skin.php')
    (Join-Path $projectRoot 'member\views\basic\register_result.skin.php')
    (Join-Path $projectRoot 'member\views\basic\register.skin.php')
    (Join-Path $projectRoot 'member\views\basic\member_confirm.skin.php')
)

foreach ($file in $targetFiles) {
    & php -l $file | Out-Null
    if ($LASTEXITCODE -ne 0) {
        throw "PHP lint failed: $file"
    }
}

$legacyMemberTableGlobals = Get-ChildItem -Path (Join-Path $projectRoot 'lib\domain\member') -Filter *.php -File -Recurse |
    Select-String -SimpleMatch -Pattern "`$GLOBALS['g5']['member_table']"
if ($legacyMemberTableGlobals) {
    $details = $legacyMemberTableGlobals | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member table globals found:`n" + ($details -join "`n"))
}

$legacyMemberFlowConfigGlobals = Select-String -Path `
    (Join-Path $projectRoot 'lib\domain\member\flow-auth.lib.php'), `
    (Join-Path $projectRoot 'lib\domain\member\flow-core.lib.php'), `
    (Join-Path $projectRoot 'lib\domain\member\flow-register.lib.php') `
    -Pattern 'global \$config'
if ($legacyMemberFlowConfigGlobals) {
    $details = $legacyMemberFlowConfigGlobals | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member flow config globals found:`n" + ($details -join "`n"))
}

$explicitRequestPages = @(
    (Join-Path $projectRoot 'member\login.php')
    (Join-Path $projectRoot 'member\login_check.php')
    (Join-Path $projectRoot 'member\logout.php')
    (Join-Path $projectRoot 'member\member_confirm.php')
    (Join-Path $projectRoot 'member\member_leave.php')
    (Join-Path $projectRoot 'member\member_cert_refresh.php')
    (Join-Path $projectRoot 'member\member_cert_refresh_update.php')
    (Join-Path $projectRoot 'member\register_form.php')
    (Join-Path $projectRoot 'member\register_form_update.php')
    (Join-Path $projectRoot 'member\password_lost.php')
    (Join-Path $projectRoot 'member\password_lost2.php')
    (Join-Path $projectRoot 'member\ajax.mb_email.php')
    (Join-Path $projectRoot 'member\ajax.mb_hp.php')
    (Join-Path $projectRoot 'member\ajax.mb_id.php')
    (Join-Path $projectRoot 'member\ajax.mb_nick.php')
    (Join-Path $projectRoot 'member\register_email.php')
    (Join-Path $projectRoot 'member\register_result.php')
    (Join-Path $projectRoot 'member\register.php')
)

$missingRequestSkipFiles = foreach ($page in $explicitRequestPages) {
    Select-String -Path $page -Pattern "define\('G5_SKIP_REQUEST_GLOBAL_EXTRACT', true\);"
}
if ($missingRequestSkipFiles) {
    $details = $missingRequestSkipFiles | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member request global extract opt-out define found:`n" + ($details -join "`n"))
}

$legacyAliasOptOutDefines = foreach ($page in $explicitRequestPages) {
    Select-String -Path $page -Pattern "define\('G5_SKIP_QUERY_STATE_ALIAS_ASSIGNMENT', true\);"
}
if ($legacyAliasOptOutDefines) {
    $details = $legacyAliasOptOutDefines | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member query alias opt-out define found:`n" + ($details -join "`n"))
}

$legacyCallChecks = @(
    @{
        Name = 'legacy login page request alias'
        Path = (Join-Path $projectRoot 'member\login.php')
        Pattern = 'member_read_login_page_request\(\$url\)'
    }
    @{
        Name = 'legacy login submit alias'
        Path = (Join-Path $projectRoot 'member\login_check.php')
        Pattern = 'member_read_login_request\(\$_POST,\s*\$url\)'
    }
    @{
        Name = 'legacy login submit raw post'
        Path = (Join-Path $projectRoot 'member\login_check.php')
        Pattern = 'member_read_login_request\(\$_POST,'
    }
    @{
        Name = 'legacy logout alias'
        Path = (Join-Path $projectRoot 'member\logout.php')
        Pattern = 'member_read_logout_request\(\$url\)'
    }
    @{
        Name = 'legacy member confirm alias'
        Path = (Join-Path $projectRoot 'member\member_confirm.php')
        Pattern = 'member_read_confirm_request\(\$url\)'
    }
    @{
        Name = 'legacy member leave alias'
        Path = (Join-Path $projectRoot 'member\member_leave.php')
        Pattern = 'member_read_leave_request\(\$_POST,\s*\$url\)'
    }
    @{
        Name = 'legacy member leave raw post'
        Path = (Join-Path $projectRoot 'member\member_leave.php')
        Pattern = 'member_read_leave_request\(\$_POST,'
    }
    @{
        Name = 'legacy member cert refresh alias'
        Path = (Join-Path $projectRoot 'member\member_cert_refresh_update.php')
        Pattern = 'member_read_cert_refresh_request\(\$w,\s*\$_POST,\s*\$url\)'
    }
    @{
        Name = 'legacy member cert refresh raw post'
        Path = (Join-Path $projectRoot 'member\member_cert_refresh_update.php')
        Pattern = 'member_read_cert_refresh_request\(\$_POST,'
    }
    @{
        Name = 'legacy register form alias'
        Path = (Join-Path $projectRoot 'member\register_form.php')
        Pattern = 'member_read_register_form_request\(\$w,\s*\$_POST\)'
    }
    @{
        Name = 'legacy register submit alias'
        Path = (Join-Path $projectRoot 'member\register_form_update.php')
        Pattern = 'member_read_registration_request\(\$w,\s*\$_POST,\s*\$_SESSION\)'
    }
    @{
        Name = 'legacy register submit raw post/session'
        Path = (Join-Path $projectRoot 'member\register_form_update.php')
        Pattern = 'member_read_registration_request\(\$_POST,\s*\$_SESSION,'
    }
    @{
        Name = 'legacy email certify request source'
        Path = (Join-Path $projectRoot 'member\email_certify.php')
        Pattern = 'member_read_email_certify_request\(\$_GET\)'
    }
    @{
        Name = 'legacy email stop request source'
        Path = (Join-Path $projectRoot 'member\email_stop.php')
        Pattern = 'member_read_email_stop_request\(\$_REQUEST,\s*\$mb_md5\)'
    }
    @{
        Name = 'legacy register email request source'
        Path = (Join-Path $projectRoot 'member\register_email.php')
        Pattern = 'member_read_register_email_request\(\$_GET\)'
    }
    @{
        Name = 'legacy register email update raw post'
        Path = (Join-Path $projectRoot 'member\register_email_update.php')
        Pattern = 'member_read_register_email_update_request\(\$_POST\)'
    }
    @{
        Name = 'legacy password reset render source'
        Path = (Join-Path $projectRoot 'member\password_reset.php')
        Pattern = 'member_build_password_reset_page_view\(\)'
    }
    @{
        Name = 'legacy password reset page raw post/session'
        Path = (Join-Path $projectRoot 'member\password_reset.php')
        Pattern = 'member_read_password_reset_page_request\(\$_POST,\s*\$_SESSION\)'
    }
    @{
        Name = 'legacy password reset submit raw post/session'
        Path = (Join-Path $projectRoot 'member\password_reset_update.php')
        Pattern = 'member_read_password_reset_request\(\$_POST,\s*\$_SESSION\)'
    }
    @{
        Name = 'legacy password lost submit raw post'
        Path = (Join-Path $projectRoot 'member\password_lost2.php')
        Pattern = 'member_read_password_lost_request\(\$_POST\)'
    }
    @{
        Name = 'legacy password lost certify request source'
        Path = (Join-Path $projectRoot 'member\password_lost_certify.php')
        Pattern = 'member_read_password_lost_certify_request\(\$_GET\)'
    }
    @{
        Name = 'legacy register result raw session'
        Path = (Join-Path $projectRoot 'member\register_result.php')
        Pattern = 'member_read_register_result_request\(\$_SESSION\)'
    }
    @{
        Name = 'legacy ajax email raw post'
        Path = (Join-Path $projectRoot 'member\ajax.mb_email.php')
        Pattern = 'member_read_ajax_identity_request\(\$_POST\)'
    }
    @{
        Name = 'legacy ajax hp raw post'
        Path = (Join-Path $projectRoot 'member\ajax.mb_hp.php')
        Pattern = 'member_read_ajax_identity_request\(\$_POST\)'
    }
    @{
        Name = 'legacy ajax id raw post'
        Path = (Join-Path $projectRoot 'member\ajax.mb_id.php')
        Pattern = 'member_read_ajax_identity_request\(\$_POST\)'
    }
    @{
        Name = 'legacy ajax nick raw post'
        Path = (Join-Path $projectRoot 'member\ajax.mb_nick.php')
        Pattern = 'member_read_ajax_identity_request\(\$_POST\)'
    }
)

foreach ($check in $legacyCallChecks) {
    $matches = Select-String -Path $check.Path -Pattern $check.Pattern
    if ($matches) {
        $details = $matches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
        throw ($check.Name + " found:`n" + ($details -join "`n"))
    }
}

$legacySessionAccess = Get-ChildItem -Path (Join-Path $projectRoot 'lib\domain\member') -Recurse -Filter '*.php' |
    Where-Object { $_.FullName -ne (Join-Path $projectRoot 'lib\domain\member\request.lib.php') } |
    Select-String -Pattern '\$_SESSION|get_session\('
if ($legacySessionAccess) {
    $details = $legacySessionAccess | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member domain session access found:`n" + ($details -join "`n"))
}

$memberRenderPages = @(
    (Join-Path $projectRoot 'member\login.php')
    (Join-Path $projectRoot 'member\member_cert_refresh.php')
    (Join-Path $projectRoot 'member\member_confirm.php')
    (Join-Path $projectRoot 'member\password_lost.php')
    (Join-Path $projectRoot 'member\password_reset.php')
    (Join-Path $projectRoot 'member\register.php')
    (Join-Path $projectRoot 'member\register_email.php')
    (Join-Path $projectRoot 'member\register_form.php')
    (Join-Path $projectRoot 'member\register_result.php')
)

$legacyTemplateBinding = foreach ($page in $memberRenderPages) {
    Select-String -Path $page -Pattern "MemberPageController::render\(\$page_view\['title'\],\s*'[^']+\.skin\.php'"
}
if ($legacyTemplateBinding) {
    $details = $legacyTemplateBinding | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member page template binding found:`n" + ($details -join "`n"))
}

$missingRenderPageCalls = foreach ($page in $memberRenderPages) {
    if (-not (Select-String -Path $page -Pattern 'MemberPageController::renderPage\(' -Quiet)) {
        $page
    }
}
if ($missingRenderPageCalls) {
    throw ("missing member renderPage call:`n" + ($missingRenderPageCalls -join "`n"))
}

$passwordResetSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\password_reset.skin.php') -Pattern '\$_POST'
if ($passwordResetSkinMatches) {
    $details = $passwordResetSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy password reset skin request access found:`n" + ($details -join "`n"))
}

$passwordResetSkinRuntimeMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\password_reset.skin.php') -Pattern 'get_text\('
if ($passwordResetSkinRuntimeMatches) {
    $details = $passwordResetSkinRuntimeMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy password reset skin runtime formatting found:`n" + ($details -join "`n"))
}

$registerFormSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\register_form.skin.php') -Pattern '\$w\b'
if ($registerFormSkinMatches) {
    $details = $registerFormSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy register form mode alias found:`n" + ($details -join "`n"))
}

$registerFormConfigMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\register_form.skin.php') -Pattern '\$config\[|date\(|switch\s*\('
if ($registerFormConfigMatches) {
    $details = $registerFormConfigMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy register form config branching found:`n" + ($details -join "`n"))
}

$registerFormScriptMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\register_form.skin.php') -Pattern 'G5_JS_URL|G5_JS_VER'
if ($registerFormScriptMatches) {
    $details = $registerFormScriptMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy register form script runtime access found:`n" + ($details -join "`n"))
}

$registerFormFactoryMatches = Select-String -Path (Join-Path $projectRoot 'lib\domain\member\render-register-form.lib.php') -Pattern '\$_REQUEST'
if ($registerFormFactoryMatches) {
    $details = $registerFormFactoryMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy register form request access found:`n" + ($details -join "`n"))
}

$memberCertRefreshSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\member_cert_refresh.skin.php') -Pattern '\$w\b|\$urlencode\b|\$config\['
if ($memberCertRefreshSkinMatches) {
    $details = $memberCertRefreshSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member cert refresh skin state found:`n" + ($details -join "`n"))
}

$memberCertRefreshSkinScriptMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\member_cert_refresh.skin.php') -Pattern 'G5_JS_URL|G5_JS_VER'
if ($memberCertRefreshSkinScriptMatches) {
    $details = $memberCertRefreshSkinScriptMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member cert refresh skin script runtime access found:`n" + ($details -join "`n"))
}

$memberCertRefreshSkinMemberMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\member_cert_refresh.skin.php') -Pattern '\$member\['
if ($memberCertRefreshSkinMemberMatches) {
    $details = $memberCertRefreshSkinMemberMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member cert refresh skin member access found:`n" + ($details -join "`n"))
}

$passwordLostSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\password_lost.skin.php') -Pattern '\$config\['
if ($passwordLostSkinMatches) {
    $details = $passwordLostSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy password lost skin config access found:`n" + ($details -join "`n"))
}

$passwordLostSkinScriptMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\password_lost.skin.php') -Pattern 'G5_JS_URL|G5_JS_VER'
if ($passwordLostSkinScriptMatches) {
    $details = $passwordLostSkinScriptMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy password lost skin script runtime access found:`n" + ($details -join "`n"))
}

$loginSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\login.skin.php') -Pattern '\$g5\[|G5_MEMBER_URL'
if ($loginSkinMatches) {
    $details = $loginSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy login skin runtime access found:`n" + ($details -join "`n"))
}

$registerResultSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\register_result.skin.php') -Pattern 'is_use_email_certify\(|\$mb\b|G5_URL'
if ($registerResultSkinMatches) {
    $details = $registerResultSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy register result skin runtime access found:`n" + ($details -join "`n"))
}

$registerSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\register.skin.php') -Pattern '\$config\[|G5_URL'
if ($registerSkinMatches) {
    $details = $registerSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy register intro skin runtime access found:`n" + ($details -join "`n"))
}

$memberConfirmSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\member_confirm.skin.php') -Pattern '\$g5\[|\$url\b'
if ($memberConfirmSkinMatches) {
    $details = $memberConfirmSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member confirm skin runtime access found:`n" + ($details -join "`n"))
}

$memberConfirmSkinMemberMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\member_confirm.skin.php') -Pattern '\$member\['
if ($memberConfirmSkinMemberMatches) {
    $details = $memberConfirmSkinMemberMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy member confirm skin member access found:`n" + ($details -join "`n"))
}

$registerEmailSkinMatches = Select-String -Path (Join-Path $projectRoot 'member\views\basic\register_email.skin.php') -Pattern '\$mb\[|G5_HTTPS_MEMBER_URL|G5_URL'
if ($registerEmailSkinMatches) {
    $details = $registerEmailSkinMatches | ForEach-Object { "$($_.Path):$($_.LineNumber): $($_.Line.Trim())" }
    throw ("legacy register email skin runtime access found:`n" + ($details -join "`n"))
}

Write-Output 'Member request refactor checks passed.'
