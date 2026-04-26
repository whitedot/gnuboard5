const {
  rel,
  projectRoot,
  file,
  listFiles,
  runPhpLint,
  assertNoMatches,
  assertContains,
} = require('./lib/refactor-check-utils');

const targetFiles = [
  file('common.php'),
  file('lib/common.crypto.lib.php'),
  file('lib/bootstrap/runtime.lib.php'),
  file('lib/bootstrap/core.lib.php'),
  file('lib/common.data.lib.php'),
  file('head.sub.php'),
  file('lib/domain/member/page-shell.lib.php'),
];

runPhpLint(targetFiles, 'PHP executable was not found; common query PHP lint skipped.');

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
