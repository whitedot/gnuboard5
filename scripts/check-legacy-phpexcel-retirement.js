const {
  fs,
  path,
  projectRoot,
  rel,
  listFiles,
  runPhpLint,
  matchFiles,
} = require('./lib/refactor-check-utils');

const auditedFiles = listFiles(projectRoot, file => {
  const normalized = rel(file);
  return normalized.endsWith('.php')
    && !normalized.startsWith('plugin/htmlpurifier/')
    && !normalized.startsWith('plugin/PHPMailer/');
});

runPhpLint(auditedFiles, 'PHP executable was not found; legacy PHPExcel retirement PHP lint skipped.');

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
