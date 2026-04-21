# Admin Form Table to Div Conversion Priority

Member-only 전환 이후 이 문서는 현재 운영 중인 관리자 화면만 다룬다.
삭제된 기능이나 더 이상 존재하지 않는 파일은 범위에서 제외한다.

- Auto conversion entry: `adm/admin.head.php`
- Converter function: `convertFormTablesToDiv($scope)`
- UI class harmonization: `applyCommonFormElements($scope)`
- Target rule: files containing `tbl_frm01|tbl_frm02` with `input/select/textarea`

## Priority 1 (Core Admin)
- `adm\config_form_parts\basic.php`
- `adm\config_form_parts\cert.php`
- `adm\config_form_parts\join.php`
- `adm\config_form_parts\mail.php`
- `adm\member_form_parts\basic.php`
- `adm\member_form_parts\consent.php`
- `adm\member_form_parts\contact.php`
- `adm\member_form_parts\history.php`

## Removed From Scope
- `adm\_rewrite_config_form.php`
- `adm\config_form_parts\layout.php`
- `adm\config_form_parts\sms.php`
- `adm\config_form_parts\sns.php`
- `adm\contentform.php`
- `adm\member_form_parts\media.php`
- `adm\member_form_parts\profile.php`
- `adm\sms_admin\*`
