const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectRoot = path.resolve(__dirname, '..');
const phpCommand = process.platform === 'win32' ? 'php.exe' : 'php';

function rel(filePath) {
  return path.relative(projectRoot, filePath).split(path.sep).join('/');
}

function commandExists(command) {
  const result = spawnSync(command, ['-v'], { stdio: 'ignore' });
  return !result.error && result.status === 0;
}

function runPhpLint(files) {
  if (!commandExists(phpCommand)) {
    const message = 'PHP executable was not found; support html PHP lint skipped.';
    if (process.env.CI) {
      console.error(message);
      process.exit(1);
    }
    console.warn(message);
    return;
  }

  for (const file of files) {
    const result = spawnSync(phpCommand, ['-l', file], { stdio: 'pipe', encoding: 'utf8' });
    if (result.status !== 0) {
      process.stderr.write(result.stderr || result.stdout || '');
      console.error(`PHP lint failed: ${rel(file)}`);
      process.exit(1);
    }
  }
}

function matchFile(file, pattern) {
  const matches = [];
  const text = fs.readFileSync(file, 'utf8');
  text.split(/\r?\n/).forEach((line, index) => {
    if (pattern.test(line)) {
      matches.push(`${rel(file)}:${index + 1}: ${line.trim()}`);
    }
  });
  return matches;
}

function assertNoMatches(name, file, pattern) {
  const matches = matchFile(file, pattern);
  if (matches.length) {
    console.error(`${name} found:\n${matches.join('\n')}`);
    process.exit(1);
  }
}

const htmlLib = path.join(projectRoot, 'lib/support/html.lib.php');
const alertPage = path.join(projectRoot, 'alert.php');
const alertClosePage = path.join(projectRoot, 'alert_close.php');
const confirmPage = path.join(projectRoot, 'confirm.php');
const targetFiles = [htmlLib, alertPage, alertClosePage, confirmPage];

runPhpLint(targetFiles);

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
