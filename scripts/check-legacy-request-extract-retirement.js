const {
  path,
  projectRoot,
  rel,
  listFiles,
  runPhpLint,
  assertNoMatches,
} = require('./lib/refactor-check-utils');

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
runPhpLint(uniqueAuditedFiles, 'PHP executable was not found; legacy request extract PHP lint skipped.');

assertNoMatches('legacy request extract define usage', uniqueAuditedFiles, /G5_FORCE_REQUEST_GLOBAL_EXTRACT|G5_SKIP_REQUEST_GLOBAL_EXTRACT/);
assertNoMatches('legacy request extract call usage', uniqueAuditedFiles, /g5_apply_legacy_request_global_extract\(|g5_should_extract_request_globals\(|g5_extract_request_globals\(/);
assertNoMatches(
  'implicit request alias usage in retirement scope',
  uniqueAuditedFiles.filter(target => rel(target) !== 'common.php'),
  /isset\(\$(w|url|sfl|stx|sst|sod|page|sca)\)|empty\(\$(w|url|sfl|stx|sst|sod|page|sca)\)|!empty\(\$(w|url|sfl|stx|sst|sod|page|sca)\)|global \$(w|url|sfl|stx|sst|sod|page|sca)\b|\$GLOBALS\[(?:'|")(w|url|sfl|stx|sst|sod|page|sca)(?:'|")\]/
);

console.log('Legacy request extract retirement checks passed.');
