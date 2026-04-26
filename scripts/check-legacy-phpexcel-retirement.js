const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectRoot = path.resolve(__dirname, '..');
const phpCommand = process.platform === 'win32' ? 'php.exe' : 'php';

function rel(filePath) {
  return path.relative(projectRoot, filePath).split(path.sep).join('/');
}

function listFiles(dir, filter) {
  if (!fs.existsSync(dir)) {
    return [];
  }

  const entries = fs.readdirSync(dir, { withFileTypes: true });
  const files = [];
  for (const entry of entries) {
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      files.push(...listFiles(fullPath, filter));
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
    const message = 'PHP executable was not found; legacy PHPExcel retirement PHP lint skipped.';
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

function matchFiles(files, pattern) {
  const matches = [];
  for (const file of files) {
    const text = fs.readFileSync(file, 'utf8');
    text.split(/\r?\n/).forEach((line, index) => {
      if (pattern.test(line)) {
        matches.push(`${rel(file)}:${index + 1}: ${line.trim()}`);
      }
    });
  }
  return matches;
}

const auditedFiles = listFiles(projectRoot, file => {
  const normalized = rel(file);
  return normalized.endsWith('.php')
    && !normalized.startsWith('plugin/htmlpurifier/')
    && !normalized.startsWith('plugin/PHPMailer/');
});

runPhpLint(auditedFiles);

const legacyTree = path.join(projectRoot, 'lib/PHPExcel');
const legacyEntrypoint = path.join(projectRoot, 'lib/PHPExcel.php');
if (fs.existsSync(legacyTree) || fs.existsSync(legacyEntrypoint)) {
  console.error('legacy PHPExcel files must not be restored.');
  process.exit(1);
}

const matches = matchFiles(auditedFiles, /PHPExcel|PHPEXCEL_ROOT/);
if (matches.length) {
  console.error(`legacy PHPExcel runtime references found:\n${matches.join('\n')}`);
  process.exit(1);
}

console.log('Legacy PHPExcel retirement checks passed.');
