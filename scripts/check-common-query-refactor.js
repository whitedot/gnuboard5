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
    const message = 'PHP executable was not found; common query PHP lint skipped.';
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

function read(filePath) {
  return fs.readFileSync(filePath, 'utf8');
}

function matchFiles(files, pattern, options = {}) {
  const matches = [];
  for (const target of files) {
    const lines = read(target).split(/\r?\n/);
    const selectedLines = options.head ? lines.slice(0, options.head) : lines;
    selectedLines.forEach((line, index) => {
      if (pattern.test(line)) {
        matches.push(`${rel(target)}:${index + 1}: ${line.trim()}`);
      }
    });
  }
  return matches;
}

function assertNoMatches(name, files, pattern, options = {}) {
  const matches = matchFiles(files, pattern, options);
  if (matches.length) {
    console.error(`${name} found:\n${matches.join('\n')}`);
    process.exit(1);
  }
}

function assertContains(filePath, pattern, message) {
  if (!pattern.test(read(filePath))) {
    console.error(message);
    process.exit(1);
  }
}

const targetFiles = [
  file('common.php'),
  file('lib/common.crypto.lib.php'),
  file('lib/bootstrap/runtime.lib.php'),
  file('lib/bootstrap/core.lib.php'),
  file('lib/common.data.lib.php'),
  file('head.sub.php'),
  file('lib/domain/member/page-shell.lib.php'),
];

runPhpLint(targetFiles);

assertNoMatches('legacy subject_sort_link globals', [file('lib/common.data.lib.php')], /global \$sst,\s*\$sod,\s*\$sfl,\s*\$stx,\s*\$page,\s*\$sca/);
assertContains(file('lib/bootstrap/runtime.lib.php'), /function g5_get_runtime_query_state\(/, 'missing runtime query state helper');
assertContains(file('lib/bootstrap/core.lib.php'), /function g5_get_runtime_table_name\(/, 'missing runtime table name helper');

assertNoMatches(
  'legacy request extract hooks',
  [file('lib/bootstrap/runtime.lib.php')],
  /g5_apply_legacy_request_global_extract\(|function g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|function g5_should_extract_request_globals\(|g5_extract_request_globals\(|function g5_extract_request_globals\(|g5_get_runtime_entrypoint_path\(|function g5_get_runtime_entrypoint_path\(|g5_get_request_global_extract_allow_paths\(|function g5_get_request_global_extract_allow_paths\(|G5_FORCE_REQUEST_GLOBAL_EXTRACT|G5_SKIP_REQUEST_GLOBAL_EXTRACT/
);

assertContains(
  file('lib/bootstrap/runtime.lib.php'),
  /function g5_build_query_state\(array \$source,\s*\$request_uri = /,
  'legacy runtime query builder signature found'
);

assertNoMatches('legacy runtime query request access', [file('lib/bootstrap/runtime.lib.php')], /\$_REQUEST\[/);
assertNoMatches('legacy request global extract keys', [file('lib/bootstrap/runtime.lib.php')], /'gr_id'|'sop'|'spt'/, { head: 20 });
assertContains(file('lib/common.data.lib.php'), /build_subject_sort_link_query_state\(/, 'missing subject sort query helper');
assertNoMatches('legacy subject sort fallback globals', [file('lib/common.data.lib.php')], /\$GLOBALS\[/);
assertNoMatches('legacy common crypto member table globals', [file('lib/common.crypto.lib.php')], /\$GLOBALS\['g5'\]\['member_table'\]/);
assertNoMatches('legacy sca alias access', [file('head.sub.php'), file('lib/domain/member/page-shell.lib.php')], /\$sca\b/);

assertNoMatches(
  'legacy common query alias assignments',
  [file('common.php')],
  /\$qstr\s*=\s*\$request_state|\$sca\s*=\s*\$request_state|\$sfl\s*=\s*\$request_state|\$stx\s*=\s*\$request_state|\$sst\s*=\s*\$request_state|\$sod\s*=\s*\$request_state|\$sop\s*=\s*\$request_state|\$spt\s*=\s*\$request_state|\$page\s*=\s*\$request_state|\$w\s*=\s*\$request_state|\$url\s*=\s*\$request_state|\$urlencode\s*=\s*\$request_state|\$gr_id\s*=\s*\$request_state/
);

assertNoMatches(
  'legacy common request extract calls',
  [file('common.php')],
  /g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|g5_extract_request_globals\(/
);

const repoPhpFiles = listFiles(projectRoot, {
  recursive: true,
  filter: target => {
    const normalized = rel(target);
    return normalized.endsWith('.php')
      && !normalized.startsWith('vendor/')
      && !normalized.startsWith('plugin/')
      && !normalized.startsWith('lib/PHPExcel/');
  },
});

assertNoMatches('legacy request extract define usage', repoPhpFiles, /G5_FORCE_REQUEST_GLOBAL_EXTRACT|G5_SKIP_REQUEST_GLOBAL_EXTRACT/);
assertNoMatches('legacy request extract call usage', repoPhpFiles, /g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|g5_extract_request_globals\(/);

console.log('Common query refactor checks passed.');
