# AGENTS.md

## CSS workflow

- Treat `tailwind4/common.css` as the shared source of truth for reusable semantic UI classes used by both the UI kit and admin pages.
- When changing UI kit stylesheets or semantic form classes such as `ui-form-*`, update the shared semantic layer so admin screens stay in sync.
- After shared style changes, rebuild the generated outputs that depend on them, especially `adm/css/admin.css` via `npm run build:admin`.
- Do not hand-edit compiled CSS unless there is a strong reason. Prefer changing the Tailwind source files and regenerating outputs.
