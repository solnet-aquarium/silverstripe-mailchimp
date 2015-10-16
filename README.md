# Google Analytics Module

[![Latest Version](https://img.shields.io/github/release/studiobonito/silverstripe-mailchimp.svg?style=flat-square)](https://github.com/studiobonito/silverstripe-mailchimp/releases)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)

## Overview

Provide Mailchimp integration for SilverStripe CMS.

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

Don't forget to add the correct action in the allowed action variable. In our example we would then need to add:

``` php
    private static $allowed_actions = [
        'MailChimpForm',
    ];
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