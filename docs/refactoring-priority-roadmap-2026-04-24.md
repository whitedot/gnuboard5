# Refactoring Priority And Roadmap

## 1. Purpose

- Align the current G5 member-only codebase with a refactoring order that legacy developers can follow and AI agents can reason about quickly.
- Start with changes that reduce hidden runtime contracts before larger structural cleanup.

## 2. Priority

### P1. Request And Input Contract Cleanup

- Reduce hidden dependencies on bootstrap globals such as `$sfl`, `$stx`, `$sst`, `$sod`, `$page`, `$w`, `$qstr`, `$url`.
- Move member/admin entry points toward explicit request arrays passed into domain functions.
- Keep validation in request/domain layers and keep DB escaping centered on prepared statements.

### P2. Bootstrap Side Effect Reduction

- Split `common.php` and `lib/bootstrap/*` into clearer stages:
  - environment and path resolution
  - request normalization
  - database and session bootstrap
  - auth and member restore
  - redirect guards
  - output preparation

### P3. Admin Domain Contract Completion

- Keep `adm/*.php` as thin controllers.
- Move page data preparation into `lib/domain/admin/*`.
- Remove page-local hidden contracts where possible.

### P4. Compatibility Shim Reduction

- Mark `common.*.lib.php`, `member.*.lib.php`, and small support shims as compatibility layers.
- Prefer direct use of `lib/domain/*`, `lib/bootstrap/*`, and `lib/support/*` in new work.

### P5. Legacy Noise Cleanup

- Remove unused legacy trees such as `lib/PHPExcel` only after runtime verification is complete.
- Trim dead compatibility branches that no longer match the supported PHP/runtime target.

### P6. Regression Guards

- Keep PHP lint as a baseline check.
- Add fast checks for forbidden patterns and key admin flows.

## 3. Execution Roadmap

### Phase 1. Explicit Member Admin Request State

- Replace hidden request-global reads in member admin controllers with explicit request parsing.
- Use sanitized query state for list filters, return links, and post-action redirects.
- First targets:
  - `adm/member_list.php`
  - `adm/member_form.php`
  - `adm/member_list_update.php`
  - `adm/member_form_update.php`
  - `adm/member_delete.php`

### Phase 2. Bootstrap Decomposition

- Reduce the amount of state assembly done inline in `common.php`.
- Make redirect-capable steps explicit and locally discoverable.
- Keep `request_context` as the primary runtime contract and isolate any remaining legacy request-global behavior behind a single bootstrap shim.

### Phase 2A. Legacy Request Global Extract Retirement

- Current state:
  - No admin/member explicit entry point relies on request-global extraction.
  - Legacy request-global extract helpers have been removed from bootstrap.
  - `common.php` now boots from sanitized superglobals and `request_context` only.
  - Regression checks fail if request-global extract hooks or hidden request alias usage are reintroduced.
  - Retirement guard audits root/install/plugin PHP paths for legacy extract hooks and hidden request alias usage.
- Removal conditions:
  - no application entry point depends on implicit globals such as `$w`, `$url`, `$sfl`, `$stx`, `$sst`, `$sod`, `$page`, `$sca`
  - no plugin or skin integration requires legacy request-global extraction for compatibility
  - manual verification passes without legacy extract fallback
- Removal sequence:
  - keep request parsing centered on `request_context`
  - document any legacy compatibility exception before adding a new hook
  - block request-global extract hook reintroduction in regression checks
  - preserve `common.php` as request-context-only bootstrap

### Phase 3. Admin Page Contract Completion

- Current state:
  - Admin head/tail shell behavior lives in `adm/admin.js` instead of mixed PHP templates.
  - `member_list`, `member_form`, `config_form`, `member_list_exel`, and `index` use explicit page/section view contracts.
  - Admin entry files read request data through runtime request context helpers instead of direct `$_GET`/`$_POST`/`$_SERVER` access.
- Remaining condition:
  - Keep the same contract shape for newly added admin pages and block regressions through `npm run check:refactor`.

### Phase 4. Shim And Vendor Cleanup

- Remove or deprecate thin wrapper files after references are migrated.
- Reassess `PHPExcel` removal after export compatibility checks.

### Phase 5. Automated Guards

- Add quick local checks for:
  - PHP lint
  - forbidden patterns such as `extract(` and new `$GLOBALS[...]` usage
  - core admin regression paths

## 4. Started On 2026-04-24

- Phase 1 starts with member admin flows.
- Current implementation target:
  - stop reading member list and member form filter state from implicit bootstrap globals
  - derive return query strings from explicit request arrays in controllers
  - preserve current behavior while lowering context-hunting cost
- Current bootstrap status:
  - `common.php` no longer assigns query alias locals.
  - request-global extraction helpers have been removed from bootstrap.
  - the next cleanup target is expanding the same explicit-contract pattern into remaining legacy helper layers.
