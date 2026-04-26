const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectRoot = path.resolve(__dirname, '..');
const phpCommand = process.platform === 'win32' ? 'php.exe' : 'php';

function rel(filePath) {
  return path.relative(projectRoot, filePath).split(path.sep).join('/');
}

function file(...parts) {
  return path.join(projectRoot, ...parts);
}

function listFiles(dir, options = {}) {
  const { recursive = false, filter = () => true } = options;
  if (!fs.existsSync(dir)) {
    return [];
  }

  const entries = fs.readdirSync(dir, { withFileTypes: true });
  const files = [];
  for (const entry of entries) {
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      if (recursive) {
        files.push(...listFiles(fullPath, options));
      }
      continue;
    }
    if (entry.isFile() && filter(fullPath)) {
      files.push(fullPath);
    }
  }
  return files;
}

function phpFiles(dir, recursive = false) {
  return listFiles(dir, { recursive, filter: filePath => filePath.endsWith('.php') });
}

function commandExists(command) {
  const result = spawnSync(command, ['-v'], { stdio: 'ignore' });
  return !result.error && result.status === 0;
}

function runPhpLint(files) {
  if (!commandExists(phpCommand)) {
    const message = 'PHP executable was not found; member request PHP lint skipped.';
    if (process.env.CI) {
      console.error(message);
      process.exit(1);
    }
    console.warn(message);
    return;
  }

  for (const target of files) {
    const result = spawnSync(phpCommand, ['-l', target], { stdio: 'pipe', encoding: 'utf8' });
    if (result.status !== 0) {
      process.stderr.write(result.stderr || result.stdout || '');
      console.error(`PHP lint failed: ${rel(target)}`);
      process.exit(1);
    }
  }
}

function matchFiles(files, pattern) {
  const matches = [];
  for (const target of files) {
    const text = fs.readFileSync(target, 'utf8');
    text.split(/\r?\n/).forEach((line, index) => {
      if (pattern.test(line)) {
        matches.push(`${rel(target)}:${index + 1}: ${line.trim()}`);
      }
    });
  }
  return matches;
}

function assertNoMatches(name, files, pattern) {
  const matches = matchFiles(files, pattern);
  if (matches.length) {
    console.error(`${name} found:\n${matches.join('\n')}`);
    process.exit(1);
  }
}

function assertContains(filePath, pattern, message) {
  const text = fs.readFileSync(filePath, 'utf8');
  if (!pattern.test(text)) {
    console.error(`${message}:\n${rel(filePath)}`);
    process.exit(1);
  }
}

const targetFiles = [
  file('lib/domain/member/request.lib.php'),
  file('lib/domain/member/request-auth.lib.php'),
  file('lib/domain/member/request-account.lib.php'),
  file('lib/domain/member/request-register.lib.php'),
  file('lib/domain/member/page-controller.lib.php'),
  file('lib/domain/member/render-page-view.lib.php'),
  file('lib/domain/member/render-register-form.lib.php'),
  file('member/login.php'),
  file('member/login_check.php'),
  file('member/logout.php'),
  file('member/member_confirm.php'),
  file('member/member_leave.php'),
  file('member/member_cert_refresh.php'),
  file('member/member_cert_refresh_update.php'),
  file('member/register_form.php'),
  file('member/register_form_update.php'),
  file('member/email_certify.php'),
  file('member/email_stop.php'),
  file('member/ajax.mb_email.php'),
  file('member/ajax.mb_hp.php'),
  file('member/ajax.mb_id.php'),
  file('member/ajax.mb_nick.php'),
  file('member/password_lost2.php'),
  file('member/register_email.php'),
  file('member/register_email_update.php'),
  file('member/password_reset.php'),
  file('member/password_reset_update.php'),
  file('member/password_lost_certify.php'),
  file('member/password_lost.php'),
  file('member/register_result.php'),
  file('member/register.php'),
  file('member/views/basic/password_reset.skin.php'),
  file('member/views/basic/password_lost.skin.php'),
  file('member/views/basic/member_cert_refresh.skin.php'),
  file('member/views/basic/register_form.skin.php'),
  file('member/views/basic/login.skin.php'),
  file('member/views/basic/register_email.skin.php'),
  file('member/views/basic/register_result.skin.php'),
  file('member/views/basic/register.skin.php'),
  file('member/views/basic/member_confirm.skin.php'),
];

runPhpLint(targetFiles);

assertNoMatches(
  'legacy member table globals',
  phpFiles(file('lib/domain/member'), true),
  /\$GLOBALS\['g5'\]\['member_table'\]/
);

assertNoMatches(
  'legacy member flow config globals',
  [
    file('lib/domain/member/flow-auth.lib.php'),
    file('lib/domain/member/flow-core.lib.php'),
    file('lib/domain/member/flow-register.lib.php'),
  ],
  /global \$config/
);

const explicitRequestPages = [
  'login.php',
  'login_check.php',
  'logout.php',
  'member_confirm.php',
  'member_leave.php',
  'member_cert_refresh.php',
  'member_cert_refresh_update.php',
  'register_form.php',
  'register_form_update.php',
  'password_lost.php',
  'password_lost2.php',
  'ajax.mb_email.php',
  'ajax.mb_hp.php',
  'ajax.mb_id.php',
  'ajax.mb_nick.php',
  'register_email.php',
  'register_result.php',
  'register.php',
].map(name => file('member', name));

assertNoMatches(
  'legacy member request global extract opt-out define',
  explicitRequestPages,
  /define\('G5_SKIP_REQUEST_GLOBAL_EXTRACT', true\);/
);

assertNoMatches(
  'legacy member query alias opt-out define',
  explicitRequestPages,
  /define\('G5_SKIP_QUERY_STATE_ALIAS_ASSIGNMENT', true\);/
);

const legacyCallChecks = [
  ['legacy login page request alias', file('member/login.php'), /member_read_login_page_request\(\$url\)/],
  ['legacy login submit alias', file('member/login_check.php'), /member_read_login_request\(\$_POST,\s*\$url\)/],
  ['legacy login submit raw post', file('member/login_check.php'), /member_read_login_request\(\$_POST,/],
  ['legacy logout alias', file('member/logout.php'), /member_read_logout_request\(\$url\)/],
  ['legacy member confirm alias', file('member/member_confirm.php'), /member_read_confirm_request\(\$url\)/],
  ['legacy member leave alias', file('member/member_leave.php'), /member_read_leave_request\(\$_POST,\s*\$url\)/],
  ['legacy member leave raw post', file('member/member_leave.php'), /member_read_leave_request\(\$_POST,/],
  ['legacy member cert refresh alias', file('member/member_cert_refresh_update.php'), /member_read_cert_refresh_request\(\$w,\s*\$_POST,\s*\$url\)/],
  ['legacy member cert refresh raw post', file('member/member_cert_refresh_update.php'), /member_read_cert_refresh_request\(\$_POST,/],
  ['legacy register form alias', file('member/register_form.php'), /member_read_register_form_request\(\$w,\s*\$_POST\)/],
  ['legacy register submit alias', file('member/register_form_update.php'), /member_read_registration_request\(\$w,\s*\$_POST,\s*\$_SESSION\)/],
  ['legacy register submit raw post/session', file('member/register_form_update.php'), /member_read_registration_request\(\$_POST,\s*\$_SESSION,/],
  ['legacy email certify request source', file('member/email_certify.php'), /member_read_email_certify_request\(\$_GET\)/],
  ['legacy email stop request source', file('member/email_stop.php'), /member_read_email_stop_request\(\$_REQUEST,\s*\$mb_md5\)/],
  ['legacy register email request source', file('member/register_email.php'), /member_read_register_email_request\(\$_GET\)/],
  ['legacy register email update raw post', file('member/register_email_update.php'), /member_read_register_email_update_request\(\$_POST\)/],
  ['legacy password reset render source', file('member/password_reset.php'), /member_build_password_reset_page_view\(\)/],
  ['legacy password reset page raw post/session', file('member/password_reset.php'), /member_read_password_reset_page_request\(\$_POST,\s*\$_SESSION\)/],
  ['legacy password reset submit raw post/session', file('member/password_reset_update.php'), /member_read_password_reset_request\(\$_POST,\s*\$_SESSION\)/],
  ['legacy password lost submit raw post', file('member/password_lost2.php'), /member_read_password_lost_request\(\$_POST\)/],
  ['legacy password lost certify request source', file('member/password_lost_certify.php'), /member_read_password_lost_certify_request\(\$_GET\)/],
  ['legacy register result raw session', file('member/register_result.php'), /member_read_register_result_request\(\$_SESSION\)/],
  ['legacy ajax email raw post', file('member/ajax.mb_email.php'), /member_read_ajax_identity_request\(\$_POST\)/],
  ['legacy ajax hp raw post', file('member/ajax.mb_hp.php'), /member_read_ajax_identity_request\(\$_POST\)/],
  ['legacy ajax id raw post', file('member/ajax.mb_id.php'), /member_read_ajax_identity_request\(\$_POST\)/],
  ['legacy ajax nick raw post', file('member/ajax.mb_nick.php'), /member_read_ajax_identity_request\(\$_POST\)/],
];

for (const [name, target, pattern] of legacyCallChecks) {
  assertNoMatches(name, [target], pattern);
}

assertNoMatches(
  'legacy member domain session access',
  phpFiles(file('lib/domain/member'), true).filter(target => rel(target) !== 'lib/domain/member/request.lib.php'),
  /\$_SESSION|get_session\(/
);

const memberRenderPages = [
  'login.php',
  'member_cert_refresh.php',
  'member_confirm.php',
  'password_lost.php',
  'password_reset.php',
  'register.php',
  'register_email.php',
  'register_form.php',
  'register_result.php',
].map(name => file('member', name));

assertNoMatches(
  'legacy member page template binding',
  memberRenderPages,
  /MemberPageController::render\(\$page_view\['title'\],\s*'[^']+\.skin\.php'/
);

for (const page of memberRenderPages) {
  assertContains(page, /MemberPageController::renderPage\(/, 'missing member renderPage call');
}

assertNoMatches('legacy password reset skin request access', [file('member/views/basic/password_reset.skin.php')], /\$_POST/);
assertNoMatches('legacy password reset skin runtime formatting', [file('member/views/basic/password_reset.skin.php')], /get_text\(/);
assertNoMatches('legacy register form mode alias', [file('member/views/basic/register_form.skin.php')], /\$w\b/);
assertNoMatches('legacy register form config branching', [file('member/views/basic/register_form.skin.php')], /\$config\[|date\(|switch\s*\(/);
assertNoMatches('legacy register form script runtime access', [file('member/views/basic/register_form.skin.php')], /G5_JS_URL|G5_JS_VER/);
assertNoMatches('legacy register form request access', [file('lib/domain/member/render-register-form.lib.php')], /\$_REQUEST/);
assertNoMatches('legacy member cert refresh skin state', [file('member/views/basic/member_cert_refresh.skin.php')], /\$w\b|\$urlencode\b|\$config\[/);
assertNoMatches('legacy member cert refresh skin script runtime access', [file('member/views/basic/member_cert_refresh.skin.php')], /G5_JS_URL|G5_JS_VER/);
assertNoMatches('legacy member cert refresh skin member access', [file('member/views/basic/member_cert_refresh.skin.php')], /\$member\[/);
assertNoMatches('legacy password lost skin config access', [file('member/views/basic/password_lost.skin.php')], /\$config\[/);
assertNoMatches('legacy password lost skin script runtime access', [file('member/views/basic/password_lost.skin.php')], /G5_JS_URL|G5_JS_VER/);
assertNoMatches('legacy login skin runtime access', [file('member/views/basic/login.skin.php')], /\$g5\[|G5_MEMBER_URL/);
assertNoMatches('legacy register result skin runtime access', [file('member/views/basic/register_result.skin.php')], /is_use_email_certify\(|\$mb\b|G5_URL/);
assertNoMatches('legacy register intro skin runtime access', [file('member/views/basic/register.skin.php')], /\$config\[|G5_URL/);
assertNoMatches('legacy member confirm skin runtime access', [file('member/views/basic/member_confirm.skin.php')], /\$g5\[|\$url\b/);
assertNoMatches('legacy member confirm skin member access', [file('member/views/basic/member_confirm.skin.php')], /\$member\[/);
assertNoMatches('legacy register email skin runtime access', [file('member/views/basic/register_email.skin.php')], /\$mb\[|G5_HTTPS_MEMBER_URL|G5_URL/);

console.log('Member request refactor checks passed.');
