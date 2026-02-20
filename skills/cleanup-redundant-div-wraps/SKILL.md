---
name: cleanup-redundant-div-wraps
description: Remove redundant wrapper div elements that remain after legacy class/style cleanup in PHP templates while preserving dynamic/PHP-sensitive markup. Use after cleanup-legacy-class-inline-style when Gnuboard/Youngcart theme files contain many plain <div> wrappers with no attributes.
---

# Redundant Div Wrap Cleanup

Use this skill to safely unwrap unnecessary `<div>` wrappers after legacy attr cleanup.

## Run Workflow

1. Check git status and avoid reverting unrelated user changes.
2. Run dry-run first to inspect impact.
3. Apply cleanup only after dry-run numbers look reasonable.
4. Lint changed PHP files and spot-check key templates.
5. Report removed wrapper counts and any safety skips.

## Safety Rules

1. Touch only `<div>` tags with no attributes.
2. Skip wrappers containing PHP blocks by default.
3. Unwrap only when either:
- Wrapper is empty except whitespace/comments.
- Wrapper has exactly one direct child tag and no meaningful text nodes.
4. Never modify content inside `<script>...</script>` blocks.
5. Run multiple passes to collapse nested wrappers gradually.

## Script

Run dry-run:

`php skills/cleanup-redundant-div-wraps/scripts/remove_redundant_div_wraps.php --target=theme/basic --mode=both --dry-run`

Then apply:

`php skills/cleanup-redundant-div-wraps/scripts/remove_redundant_div_wraps.php --target=theme/basic --mode=both`

## Options

- `--target=<path>`: PHP file or directory to process (default: `theme/basic`)
- `--mode=empty|single|both`: remove empty wrappers, single-child wrappers, or both (default: `both`)
- `--max-passes=<n>`: number of cleanup passes (default: `2`)
- `--single-child-tags=<csv|any>`: allowed direct-child tags for single-child unwrap (default: `div,section,article,main,aside,nav,header,footer,ul,ol,table,form,fieldset`)
- `--allow-php`: allow wrappers containing PHP blocks (off by default for safety)
- `--dry-run`: calculate and report changes without writing files

## Validation

1. Lint changed files:
- `php -l <changed-file>`
2. Spot-check wrappers in diffs:
- Ensure structure did not change around forms/tables/nav blocks.
3. Scan remaining plain wrappers when needed:
- `rg -n "^\s*<div>\s*$" theme/basic -g "*.php"`
