const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const projectRoot = path.resolve(__dirname, '..');
const phpCommand = process.platform === 'win32' ? 'php.exe' : 'php';

function toPosix(filePath) {
  return filePath.split(path.sep).join('/');
}

function rel(filePath) {
  return toPosix(path.relative(projectRoot, filePath));
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
  return listFiles(dir, {
    recursive,
    filter: filePath => filePath.endsWith('.php'),
  });
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

function fail(name, matches) {
  console.error(`${name} found:\n${matches.join('\n')}`);
  process.exit(1);
}

function assertNoMatches(name, files, pattern) {
  const matches = matchFiles(files, pattern);
  if (matches.length) {
    fail(name, matches);
  }
}

function assertFileMissing(filePath, message) {
  if (fs.existsSync(filePath)) {
    console.error(`${message}:\n${rel(filePath)}`);
    process.exit(1);
  }
}

function commandExists(command) {
  const result = spawnSync(command, ['-v'], { stdio: 'ignore' });
  return !result.error && result.status === 0;
}

function runPhpLint(files) {
  if (!commandExists(phpCommand)) {
    const message = 'PHP executable was not found; admin PHP lint skipped.';
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

const admDir = path.join(projectRoot, 'adm');
const adminDomainDir = path.join(projectRoot, 'lib/domain/admin');

const lintFiles = [
  ...phpFiles(admDir, false),
  ...phpFiles(adminDomainDir, false),
];
runPhpLint(lintFiles);

const forbiddenChecks = [
  {
    name: 'extract() usage',
    pattern: /extract\s*\(/,
    files: [...phpFiles(admDir, true), ...phpFiles(adminDomainDir, true)],
  },
  {
    name: 'implicit member list globals',
    pattern: /isset\(\$sfl\)|isset\(\$stx\)|isset\(\$sst\)|isset\(\$sod\)|isset\(\$page\)|isset\(\$w\)|isset\(\$url\)/,
    files: phpFiles(admDir, true),
  },
  {
    name: 'legacy admin template helpers',
    pattern: /subject_sort_link\s*\(|get_sideview\s*\(/,
    files: phpFiles(admDir, true),
  },
  {
    name: '$GLOBALS access',
    pattern: /\$GLOBALS\[/,
    files: [...phpFiles(admDir, true), ...phpFiles(adminDomainDir, true)],
  },
];

for (const check of forbiddenChecks) {
  assertNoMatches(check.name, check.files, check.pattern);
}

assertNoMatches(
  'legacy admin template helpers',
  phpFiles(admDir, true),
  /subject_sort_link\s*\(|get_sideview\s*\(/
);

assertNoMatches(
  'raw request access inside admin helper libs',
  listFiles(admDir, { recursive: true, filter: filePath => filePath.endsWith('.lib.php') }),
  /\$_GET|\$_POST|\$_REQUEST/
);

assertNoMatches(
  'legacy member form aliases',
  phpFiles(path.join(admDir, 'member_form_parts'), false),
  /\$w\b|\$mb\b|\$qstr\b|\$sfl\b|\$stx\b|\$sst\b|\$sod\b|\$page\b|\$member_level_options\b|\$mb_cert_history\b|\$display_mb_id\b|\$mask_preserved_id\b|\$sound_only\b|\$required_mb_id\b|\$required_mb_id_class\b|\$required_mb_password\b|\$mb_certify_yes\b|\$mb_certify_no\b|\$mb_adult_yes\b|\$mb_adult_no\b|\$mb_mailling_yes\b|\$mb_mailling_no\b|\$mb_open_yes\b|\$mb_open_no\b|\$mb_marketing_agree_yes\b|\$mb_marketing_agree_no\b/
);

assertNoMatches(
  'legacy member form view-model keys',
  [path.join(adminDomainDir, 'member-form.lib.php')],
  /'sound_only'|'required_mb_id'|'required_mb_id_class'|'required_mb_password'|'mb_certify_yes'|'mb_certify_no'|'mb_adult_yes'|'mb_adult_no'|'mb_mailling_yes'|'mb_mailling_no'|'mb_open_yes'|'mb_open_no'|'mb_marketing_agree_yes'|'mb_marketing_agree_no'/
);

const explicitRequestPages = [
  'index.php',
  'member_list.php',
  'member_form.php',
  'member_list_update.php',
  'member_form_update.php',
  'member_delete.php',
  'member_list_exel.php',
  'member_list_exel_export.php',
  'member_list_file_delete.php',
].map(file => path.join(admDir, file));

assertNoMatches(
  'legacy request global extract opt-out define',
  explicitRequestPages,
  /define\('G5_SKIP_REQUEST_GLOBAL_EXTRACT', true\);/
);

assertNoMatches(
  'legacy query state alias opt-out define',
  explicitRequestPages,
  /define\('G5_SKIP_QUERY_STATE_ALIAS_ASSIGNMENT', true\);/
);

assertNoMatches(
  'legacy member export page wiring',
  [path.join(admDir, 'member_list_exel.php')],
  /onclick="location\.href='\\?'"|member_list_exel_export\.php\?\$\{query\}/
);

assertNoMatches(
  'legacy member list inline behavior',
  [path.join(admDir, 'member_list.php')],
  /<script>|onclick=|onsubmit=|function deleteMember|function fmemberlist_submit/
);

assertNoMatches(
  'legacy member export inline behavior',
  [path.join(admDir, 'member_list_exel.php')],
  /<script>|EventSource\(|function startExcelDownload|function showDownloadPopup|function handleProgressUpdate/
);

assertNoMatches(
  'legacy member form inline behavior',
  [path.join(admDir, 'member_form.php'), path.join(admDir, 'member_form_parts/history.php')],
  /onsubmit=|onclick=|fmember_submit\(|member_form_parts\/script\.php/
);

assertNoMatches(
  'legacy member form update shell wiring',
  [path.join(admDir, 'member_form_update.php')],
  /register\.lib\.php|admin_read_member_form_request\(\$_POST\)|member_read_admin_member_request\(\$_POST\)|admin_build_member_list_qstr\(\$_POST|admin_complete_member_form_update_request\(\$member_form_request/
);

assertNoMatches(
  'legacy member delete shell wiring',
  [path.join(admDir, 'member_delete.php')],
  /check_demo\(|admin_build_member_list_qstr\(\$_POST|admin_read_member_delete_request\(\$_POST\)|admin_complete_member_delete_request\(\$request,/
);

assertNoMatches(
  'legacy member export shell wiring',
  [path.join(admDir, 'member_list_exel.php'), path.join(admDir, 'member_list_exel_export.php')],
  /member_list_exel\.lib\.php|check_demo\(|admin_build_member_export_runtime_context\(\$g5|admin_complete_member_export_stream_request\(\$_GET|admin_run_member_export\(/
);

assertFileMissing(path.join(admDir, 'member_list_exel.lib.php'), 'legacy member export helper file found');

const exportFiles = listFiles(adminDomainDir, {
  recursive: false,
  filter: filePath => /^export.*\.php$/.test(path.basename(filePath)),
});

assertNoMatches(
  'legacy export naming',
  exportFiles,
  /\bget_export_config\s*\(|\bget_member_export_params\s*\(|\bmember_export_get_total_count\s*\(|\bmember_export_build_where\s*\(|\bmember_export_get_config\s*\(|\bmember_export_open_statement\s*\(|\bmember_export_fetch_sheet_rows\s*\(|\bmember_export_create_xlsx\s*\(|\bmember_export_create_zip\s*\(|\bmember_export_ensure_directory\s*\(|\bmember_export_delete\s*\(|\bmember_export_delete_directory\s*\(|\bmember_export_write_log\s*\(|\bmember_export_send_progress\s*\(|\bmember_export_set_sse_headers\s*\(/
);

assertNoMatches(
  'legacy export constant naming',
  exportFiles,
  /\bMEMBER_EXPORT_PAGE_SIZE\b|\bMEMBER_EXPORT_MAX_SIZE\b|\bMEMBER_BASE_DIR\b|\bMEMBER_BASE_DATE\b|\bMEMBER_EXPORT_DIR\b|\bMEMBER_LOG_DIR\b/
);

assertNoMatches(
  'legacy export globals',
  [path.join(adminDomainDir, 'export.lib.php')],
  /global \$g5|global \$member/
);

assertNoMatches(
  'legacy admin request access',
  [path.join(adminDomainDir, 'bootstrap.lib.php'), path.join(adminDomainDir, 'token.lib.php')],
  /\$_REQUEST/
);

assertNoMatches(
  'legacy admin session access',
  phpFiles(adminDomainDir, true).filter(file => path.basename(file) !== 'token.lib.php'),
  /get_session\(|\$_SESSION/
);

console.log('Admin refactor checks passed.');
