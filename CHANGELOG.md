# Changelog

All Notable changes to `studiobonito/silverstripe-mailchimp` will be documented in this file.

### [Unreleased]

## [0.2.1] - 2016-06-17
- Enabled the processing of the form to be called via AJAX.

## [0.2.0] - 2015-10-30
### Changed
- Added version number `~2.0.6` for `mailchimp/mailchimp` dependency.
- Added PHPDoc comments for `MailChimpExtension`.
- Added PHPDoc comments for `MailChimpForm`.
- Added comments to `MailChimpForm.ss`.
- Moved MailChimp settings into `Root.Services.MailChimp`.
- Reordered fields that get displayed when using Name Fields.
- Add test suite.
- Changed to PHP 5.3 syntax.
- Tidied up `use` statements and syntax when creating new classes.
- Added English language file and relevant copy.
- Dynamically creates the name of the action that is removed from the MergeVars array.
- Removed the redundant template.

### Fixed
- Updated `LICENSE.md` to latest BSD-2.
- useNameFields now works.
- Removed ZenValidator from dependencies.
- Removed unnecessary \ where the class is being called in the Use statements.


## [0.1.1] - 2015-07-08
### Changed
- Ability to choose to use First Name and Last Name fields
- Ability to set action name


## [0.1.0] - 2015-07-07
### Changed
- Base MailChimpForm class
- Base MailChimpExtension class
- Added ZenValidator validator for MailChimpForm

[Unreleased]: https://github.com/studiobonito/silverstripe-mailchimp/compare/0.2.0...HEAD
[0.2.1]: https://github.com/studiobonito/silverstripe-mailchimp/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/studiobonito/silverstripe-mailchimp/compare/0.1.1...0.2.0
[0.1.1]: https://github.com/studiobonito/silverstripe-mailchimp/compare/0.1.0...0.1.1
[0.1.0]: https://github.com/studiobonito/silverstripe-mailchimp/compare/bce62ad...0.1.0