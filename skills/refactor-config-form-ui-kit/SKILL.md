---
name: refactor-config-form-ui-kit
description: Refactor Gnuboard admin config form UI (`adm/config_form.php`, `adm/config_form_parts/*.php`) to ui-kit semantics without DOM post-processing. Use when requests include direct markup refactor, section/card normalization, sticky tab + scrollspy behavior, hint-text alignment, and admin CSS build synchronization.
---

# Config Form UI-Kit Refactor

Refactor `config_form` by editing source PHP/HTML/CSS directly.

## Run Workflow

1. Inspect current structure in:
- `adm/config_form.php`
- `adm/config_form_parts/*.php`
- `adm/config_form_parts/script.php`
- `tailwind4/admin.css`
 - `docs/ui-kit/form-elements.php`
 - `docs/ui-kit/ui-buttons.php`
 - `docs/ui-kit/ui-tabs.php`
2. Refactor markup directly; do not use DOM post-processing at render time.
3. Keep section-based structure and ui-kit semantic classes.
4. Align behavior scripts (tab navigation, sticky, scrollspy) with updated markup.
5. Rebuild admin CSS and run syntax checks.
6. Report changed files and any environment constraints.

## Refactor Rules

1. Do not add DOM 후처리 (`preg_replace_callback`, runtime HTML rewriting) for class injection.
2. Edit each part file directly (`config_form_parts/*.php`), not generated output.
3. Keep `<form id="fconfigform">` unwrapped by card; apply `card` to each logical `<section>`.
4. Prefer reusable class-scoped styling over page-id-scoped styling:
- Add a reusable root class on target forms (example: `admin-form-layout`)
- Avoid adding new `#page_id > ...` selectors when a class selector can express the same structure
5. Extract repeated tab/anchor menu markup into reusable helper functions:
- Prefer shared helper (example: `admin_build_anchor_menu()`) over page-local closure builders
- Pass page-specific options (`nav_id`, classes, aria label) instead of duplicating HTML assembly logic
6. Use ui-kit classes consistently:
- Container: `card`, `card-header`, `card-title`, `card-body`
- Form controls: `form-input`, `form-select`, `form-textarea`, `form-checkbox`, `form-radio`, `form-label`
- Buttons: `btn` + semantic variants (`btn-solid-*`, `btn-soft-*`, `btn-sm` as needed)
 - Reference patterns must be aligned with `docs/ui-kit/form-elements.php`, `docs/ui-kit/ui-buttons.php`, `docs/ui-kit/ui-tabs.php`
7. Use `hint-text` for description/help text consistency.
8. Prefer explicit manual layout utility classes (for example `af-grid`, `af-row`, `af-field`) over mixed 1-column/2-column patterns in same reading flow.
9. Remove obsolete anchors or duplicate navigation markup inside part files (for example legacy `$pg_anchor` echoes).

## Tabs, Sticky, Scrollspy

1. Render tabs from `config_form.php` as a dedicated top block (no title/description card wrapper).
2. Keep tabs visually attached under topbar:
- sticky offset should follow `--admin-shell-bar-height`
- reduce extra top margin/padding so tabs appear directly below topbar
3. Use a stronger tab visual hierarchy when needed:
- tab bar can use card-level depth (`shadow`) to match nearby cards
- tab bar background should differ from section card background (for example `bg-default-100/95` vs `bg-card`)
- active tab should have distinct surface (`bg-card`), stronger text weight, and border separation
4. Implement scrollspy in `adm/config_form_parts/script.php`:
- click tab: smooth-scroll to target section with sticky offset compensation
- on scroll: update active tab by current visible `anc_cf_*` section
- keep `aria-selected` and active class synchronized
5. Add section `scroll-margin-top` so anchored headings are not hidden behind sticky bars.

## CSS Rules

1. Place source styling in `tailwind4/admin.css`.
2. Scope reusable form layout rules with reusable classes (example: `.admin-form-layout`) instead of hardcoded form IDs.
3. Keep tab container selectors explicit (`#config_tabs_bar`, `#config_tabs_nav`) when the tab element is page-specific.
4. Rebuild output after edits:
- `npm.cmd run build:admin`
5. Ensure generated file updates:
- `adm/css/admin.css`

## Validation

1. Lint changed PHP files:
- `C:\xampp\php\php.exe -l adm/config_form.php`
- `C:\xampp\php\php.exe -l adm/config_form_parts/script.php`
- run `php -l` for any additional changed part files
2. Verify no syntax errors and no broken section anchors (`#anc_cf_*`).
3. Manually check:
- sticky tabs remain below topbar while scrolling
- scrollspy active state tracks section position
- submit/captcha and existing config logic still work

## Expected Deliverables

1. Direct source-level refactor in `adm/config_form.php` and `adm/config_form_parts/*.php`
2. Updated behavior script in `adm/config_form_parts/script.php`
3. Updated source CSS in `tailwind4/admin.css`
4. Rebuilt output CSS in `adm/css/admin.css`
