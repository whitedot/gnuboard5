---
name: cleanup-legacy-class-inline-style
description: Remove legacy class and inline style attributes from PHP templates while preserving JS behavior hooks and ui-kit compatibility. Use when refactoring Gnuboard/Youngcart theme files (for example theme/basic) for Tailwind migration with rules like "keep id", "keep JS-related classes", and "keep ui-kit related classes".
---

# Legacy Attr Cleanup

Use this skill to remove presentational legacy attributes safely.

## Run Workflow

1. Check current workspace status and avoid reverting unrelated user changes.
2. Run a dry-run first.
3. Apply cleanup.
4. Validate syntax and spot-check diffs.
5. Report what was removed and what was intentionally preserved.

## Cleanup Rules

1. Keep `id` attributes unchanged.
2. Apply class cleanup:
- Keep tokens referenced by project JS selectors and inline `<script>` selectors.
- Keep ui-kit and behavior prefixes: `hs-*`, `ui-*`, `js-*`.
- Keep class tokens that include ui-kit option syntax like `[--...:...]`.
- Preserve embedded PHP blocks inside class attributes.
- Remove empty `class` attributes after token filtering.
3. Apply style cleanup:
- Remove inline style attributes by default.
- Keep only styles containing `display:none` (JS toggle compatibility).
4. Do not modify content inside `<script>...</script>` blocks.

## Script

Use the bundled script:

`php skills/cleanup-legacy-class-inline-style/scripts/cleanup_legacy_attrs.php --target=theme/basic --mode=both --dry-run`

Then apply:

`php skills/cleanup-legacy-class-inline-style/scripts/cleanup_legacy_attrs.php --target=theme/basic --mode=both`

## Options

- `--target=<path>`: PHP file or directory to process (default: `theme/basic`)
- `--mode=class|style|both`: cleanup mode (default: `both`)
- `--js-root=<path>`: JS root used for class keep-set extraction (default: `js`)
- `--dry-run`: calculate and report changes without writing files

## Validation

1. Verify remaining inline styles are only intended exceptions:
- `rg -n "style=" theme/basic -g "*.php"`
2. Lint changed PHP files:
- Run `php -l` on changed files.
3. Spot-check dynamic class lines in diffs:
- Ensure PHP expressions in class attributes are still syntactically valid.
