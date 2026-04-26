const {
  fs,
  path,
  projectRoot,
  runPhpLint,
  assertNoMatches,
} = require('./lib/refactor-check-utils');

const htmlLib = path.join(projectRoot, 'lib/support/html.lib.php');
const alertPage = path.join(projectRoot, 'alert.php');
const alertClosePage = path.join(projectRoot, 'alert_close.php');
const confirmPage = path.join(projectRoot, 'confirm.php');
const targetFiles = [htmlLib, alertPage, alertClosePage, confirmPage];

runPhpLint(targetFiles, 'PHP executable was not found; support html PHP lint skipped.');

assertNoMatches('legacy alert template locals', alertPage, /\$msg\b|\$msg2\b|\$url\b|\$post\b|\$_POST|\$error\b|\$header2\b/);
assertNoMatches('legacy alert close template locals', alertClosePage, /\$msg\b|\$msg2\b|\$msg3\b|\$error\b|\$header2\b/);
assertNoMatches('legacy confirm template locals', confirmPage, /\$msg\b|\$header\b|\$url1\b|\$url2\b|\$url3\b/);

const htmlLibText = fs.readFileSync(htmlLib, 'utf8');
const builderMatches = htmlLibText.match(/build_alert_page_view\(|build_alert_close_page_view\(|build_confirm_page_view\(/g) || [];
if (builderMatches.length < 3) {
  console.error('missing support html view builders');
  process.exit(1);
}

console.log('Support html refactor checks passed.');
