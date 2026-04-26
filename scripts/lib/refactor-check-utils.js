const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectRoot = path.resolve(__dirname, '../..');
const phpCommand = process.platform === 'win32' ? 'php.exe' : 'php';

function rel(filePath) {
  return path.relative(projectRoot, filePath).split(path.sep).join('/');
}

function file(...parts) {
  return path.join(projectRoot, ...parts);
}

function listFiles(dir, options = {}) {
  const normalizedOptions = typeof options === 'function'
    ? { recursive: true, filter: options }
    : options;
  const { recursive = false, filter = () => true } = normalizedOptions;

  if (!fs.existsSync(dir)) {
    return [];
  }

  const files = [];
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      if (recursive) {
        files.push(...listFiles(fullPath, normalizedOptions));
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
  return listFiles(dir, {
    recursive,
    filter: filePath => filePath.endsWith('.php'),
  });
}

function commandExists(command) {
  const result = spawnSync(command, ['-v'], { stdio: 'ignore' });
  return !result.error && result.status === 0;
}

function runPhpLint(files, skipMessage) {
  if (!commandExists(phpCommand)) {
    const message = skipMessage || 'PHP executable was not found; PHP lint skipped.';
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

function matchFile(target, pattern, options = {}) {
  return matchFiles([target], pattern, options);
}

function fail(name, matches) {
  console.error(`${name} found:\n${matches.join('\n')}`);
  process.exit(1);
}

function assertNoMatches(name, files, pattern, options = {}) {
  const targetFiles = Array.isArray(files) ? files : [files];
  const matches = matchFiles(targetFiles, pattern, options);
  if (matches.length) {
    fail(name, matches);
  }
}

function assertContains(filePath, pattern, message) {
  if (!pattern.test(read(filePath))) {
    console.error(message);
    process.exit(1);
  }
}

function assertFileMissing(filePath, message) {
  if (fs.existsSync(filePath)) {
    console.error(`${message}:\n${rel(filePath)}`);
    process.exit(1);
  }
}

module.exports = {
  fs,
  path,
  projectRoot,
  rel,
  file,
  listFiles,
  phpFiles,
  runPhpLint,
  read,
  matchFiles,
  matchFile,
  assertNoMatches,
  assertContains,
  assertFileMissing,
};
