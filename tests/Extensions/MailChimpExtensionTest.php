<?php namespace StudioBonito\SilverStripe\MailChimp\Tests\Extensions;

// Silverstripe
use \FieldList;
use \Tab;
use \TabSet;

// StudioBonito/Mailchimp
use StudioBonito\SilverStripe\MailChimp\Extensions\MailChimpExtension;

/**
 * MailChimpExtension Test.
 *
 * @author       Steve Heyes <steve.heyes@studiobonito.co.uk>
 * @copyright    Studio Bonito Ltd.
 */
class MailChimpExtensionTest extends \PHPUnit_Framework_TestCase
{

    /*
     * A test for adding the MailChimp API and List ID fields into the CMS.
     */
    public function testUpdateCMSFields()
    {
        $extension = new MailChimpExtension();

        $fields = new FieldList();
        $fields->push(new TabSet('Root', new Tab('Main')));

        $extension->updateCMSFields($fields);

        $this->assertNotNull($fields->dataFieldByName('MailChimpApiID'));
        $this->assertNotNull($fields->dataFieldByName('MailListID'));
    }
}
