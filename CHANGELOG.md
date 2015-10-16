# Changelog

All Notable changes to `studiobonito/silverstripe-mailchimp` will be documented in this file.

## [0.2.0] - 2015-08-28
### Changed
- Added version number `~2.0.6` for `mailchimp/mailchimp` dependency.
- Added PHPDoc comments for `MailChimpExtension`.
- Added PHPDoc comments for `MailChimpForm`.
- Added comments to `MailChimpForm.ss`.
- Moved MailChimp settings into `Root.Services.MailChimp`.
- Reordered fields that get displayed when using Name Fields.
- Add test for doubleOptin and mergeVar.
- Changed to PHP 5.3 syntax.
 

### Fixed
- Updated `LICENSE.md` to latest BSD-2.
- useNameFields now works.
- Removed ZenValidator from dependencies.
- Removed unecessary \ where the class is being called in the Use statements.
