const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectRoot = path.resolve(__dirname, '..');
const phpCommand = process.platform === 'win32' ? 'php.exe' : 'php';

function rel(filePath) {
  return path.relative(projectRoot, filePath).split(path.sep).join('/');
}

function listFiles(dir, options = {}) {
  const { recursive = false, filter = () => true } = options;
  if (!fs.existsSync(dir)) {
    return [];
  }

  const files = [];
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
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

function commandExists(command) {
  const result = spawnSync(command, ['-v'], { stdio: 'ignore' });
  return !result.error && result.status === 0;
}

function runPhpLint(files) {
  if (!commandExists(phpCommand)) {
    const message = 'PHP executable was not found; legacy request extract PHP lint skipped.';
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

const auditedFiles = [
  ...listFiles(projectRoot, { recursive: false, filter: target => target.endsWith('.php') }),
  ...listFiles(path.join(projectRoot, 'install'), { recursive: true, filter: target => target.endsWith('.php') }),
  ...listFiles(path.join(projectRoot, 'plugin'), {
    recursive: true,
    filter: target => {
      const normalized = rel(target);
      return normalized.endsWith('.php')
        && !normalized.startsWith('plugin/htmlpurifier/')
        && !normalized.startsWith('plugin/PHPMailer/');
    },
  }),
].sort();

const uniqueAuditedFiles = [...new Set(auditedFiles)];
runPhpLint(uniqueAuditedFiles);

assertNoMatches('legacy request extract define usage', uniqueAuditedFiles, /G5_FORCE_REQUEST_GLOBAL_EXTRACT|G5_SKIP_REQUEST_GLOBAL_EXTRACT/);
assertNoMatches('legacy request extract call usage', uniqueAuditedFiles, /g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|g5_extract_request_globals\(/);
assertNoMatches(
  'implicit request alias usage in retirement scope',
  uniqueAuditedFiles.filter(target => rel(target) !== 'common.php'),
  /isset\(\$(w|url|sfl|stx|sst|sod|page|sca)\)|empty\(\$(w|url|sfl|stx|sst|sod|page|sca)\)|!empty\(\$(w|url|sfl|stx|sst|sod|page|sca)\)|global \$(w|url|sfl|stx|sst|sod|page|sca)\b|\$GLOBALS\[(?:'|")(w|url|sfl|stx|sst|sod|page|sca)(?:'|")\]/
);

console.log('Legacy request extract retirement checks passed.');
