# Admin Form Table to Div Conversion Priority

- Auto conversion entry: `adm/admin.head.php`
- Converter function: `convertFormTablesToDiv($scope)`
- UI class harmonization: `applyCommonFormElements($scope)`
- Target rule: files containing `tbl_frm01|tbl_frm02` with `input/select/textarea`

## Priority 1 (Core Admin)
- Count: 33
- Order:
  - `adm\_rewrite_config_form.php`
  - `adm\auth_list.php`
  - `adm\board_copy.php`
  - `adm\board_form_parts\auth.php`
  - `adm\board_form_parts\basic.php`
  - `adm\board_form_parts\design.php`
  - `adm\board_form_parts\function.php`
  - `adm\board_form_parts\point.php`
  - `adm\boardgroup_form.php`
  - `adm\config_form_parts\basic.php`
  - `adm\config_form_parts\board.php`
  - `adm\config_form_parts\cert.php`
  - `adm\config_form_parts\join.php`
  - `adm\config_form_parts\layout.php`
  - `adm\config_form_parts\mail.php`
  - `adm\config_form_parts\sms.php`
  - `adm\config_form_parts\sns.php`
  - `adm\contentform.php`
  - `adm\faqform.php`
  - `adm\faqmasterform.php`
  - `adm\mail_form.php`
  - `adm\mail_select_form.php`
  - `adm\member_form_parts\basic.php`
  - `adm\member_form_parts\consent.php`
  - `adm\member_form_parts\contact.php`
  - `adm\member_form_parts\history.php`
  - `adm\member_form_parts\media.php`
  - `adm\member_form_parts\profile.php`
  - `adm\menu_form_search.php`
  - `adm\newwinform.php`
  - `adm\point_list.php`
  - `adm\poll_form.php`
  - `adm\qa_config.php`

## Priority 2 (Shop Admin)
- Count: 28
- Order:
  - `adm\shop_admin\bannerform.php`
  - `adm\shop_admin\categoryform.php`
  - `adm\shop_admin\config_form_parts\delivery.php`
  - `adm\shop_admin\config_form_parts\etc.php`
  - `adm\shop_admin\config_form_parts\index.php`
  - `adm\shop_admin\config_form_parts\info.php`
  - `adm\shop_admin\config_form_parts\payment.php`
  - `adm\shop_admin\config_form_parts\sms.php`
  - `adm\shop_admin\couponform.php`
  - `adm\shop_admin\couponzoneform.php`
  - `adm\shop_admin\inorderform.php`
  - `adm\shop_admin\item_form_parts\category.php`
  - `adm\shop_admin\item_form_parts\cost.php`
  - `adm\shop_admin\item_form_parts\delivery.php`
  - `adm\shop_admin\item_form_parts\details.php`
  - `adm\shop_admin\item_form_parts\image.php`
  - `adm\shop_admin\item_form_parts\info.php`
  - `adm\shop_admin\item_form_parts\skin.php`
  - `adm\shop_admin\itemeventform.php`
  - `adm\shop_admin\iteminfo.php`
  - `adm\shop_admin\itemqaform.php`
  - `adm\shop_admin\itemuseform.php`
  - `adm\shop_admin\order_form_parts\address.php`
  - `adm\shop_admin\order_form_parts\pay_details.php`
  - `adm\shop_admin\orderpartcancel.php`
  - `adm\shop_admin\personalpaycopy.php`
  - `adm\shop_admin\personalpayform.php`
  - `adm\shop_admin\sendcostlist.php`

## Priority 3 (SMS Admin)
- Count: 3
- Order:
  - `adm\sms_admin\config.php`
  - `adm\sms_admin\form_write.php`
  - `adm\sms_admin\num_book_write.php`

## Notes
- Runtime conversion applies in page render order after `jQuery(function($){ ... })` executes.
- To exclude a specific form table from conversion, wrap it with `.no-auto-div-form`.
- Total current targets: 64
