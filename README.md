# MailChimp Module

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Overview

Provide MailChimp integration for SilverStripe CMS.

## Requirements

- SilverStripe 3.1 or newer.

## Install

### Via Composer

``` bash
$ composer require studiobonito/silverstripe-mailchimp
```

### Manually

Copy the 'silverstripe-mailchimp' folder to the root of your SilverStripe installation.

## Usage

The module provides the ability to create a form that updates a MailChimp mailing list. 

The module needs a MailChimp API and List ID. To add these, fill the relevant fields in the CMS found in the tab at 
`Settings > Services > Mail Chimp`.

To initliase the form, do so as you would usually in a Controller, but use MailChimpFilm class:

``` php
public function MailChimpForm()
{
    return = new MailChimpForm($this, 'MailChimpForm');
}
```

Don't forget to add the correct action in the allowed action variable. In our example we would need to add:

``` php
private static $allowed_actions = array(
    'MailChimpForm'
);
```

You can then use `$MailChimpForm` in your template.

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email support@studiobonito.co.uk instead of using the issue tracker.

## Credits

- [Steve Heyes](https://github.com/mrsteveheyes)
- [Tom Densham](https://github.com/nedmas)
- [All Contributors](../../contributors)

## License

The BSD-2-Clause License. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/studiobonito/silverstripe-mailchimp.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/studiobonito/silverstripe-mailchimp/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/studiobonito/silverstripe-mailchimp.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/studiobonito/silverstripe-mailchimp.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/studiobonito/silverstripe-mailchimp.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/studiobonito/silverstripe-mailchimp
[link-travis]: https://travis-ci.org/studiobonito/silverstripe-mailchimp
[link-scrutinizer]: https://scrutinizer-ci.com/g/studiobonito/silverstripe-mailchimp/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/studiobonito/silverstripe-mailchimp
[link-downloads]: https://packagist.org/packages/studiobonito/silverstripe-mailchimp
[link-author]: https://github.com/mrsteveheyes
[link-contributors]: ../../contributors