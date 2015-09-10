<?php namespace StudioBonito\SilverStripe\MailChimp\Extensions;

use \Injector;
use \RequiredFields;
use \Controller;
use \Mockery as m;
use StudioBonito\SilverStripe\MailChimp\Forms\MailChimpForm;

/**
 * Tests for the MailChimpForm
 *
 * @author       Steve Heyes <steve.heyes@studiobonito.co.uk>
 * @copyright    Studio Bonito Ltd.
 */
class MailChimpFormTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test for setting the variable $useNameFields
     */
    public function testSetUseNameFields()
    {
        // Mock Controller
        $controller = new Controller();

        // TODO: Figure out and fix why ZenValidator isn't working
        // Mock up basic validator to stop ZenValidator from running
        $mockValidator = new RequiredFields();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm', null, null, $mockValidator);

        // Set UserNameFields to true
        $form->setUseNameFields(true);

        // Assert that $form->useNameFields is true
        $this->assertEquals(true, $form->useNameFields);
    }

    /**
     * Test for whether setting the variable $useNameFields actually adds the name fields to the list
     */
    public function testAddingUseNameFields()
    {
        // Mock Controller
        $controller = new Controller();

        // TODO: Figure out and fix why ZenValidator isn't working
        // Mock up basic validator to stop ZenValidator from running
        $mockValidator = new RequiredFields();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm', null, null, $mockValidator);

        // Set UserNameFields to true
        $form->setUseNameFields(true);

        // Get fields
        $fields = $form->Fields();

        // Get first name field
        $fname = $fields->fieldByName('FNAME');

        // Check if First Name field is there
        $this->assertEquals('FNAME', $fname->getName());

        // Get last name field
        $lname = $fields->fieldByName('LNAME');

        // Check if Last Name field is there
        $this->assertEquals('LNAME', $lname->getName());
    }

    public function testAddingUseNameFieldsOrder()
    {
        // Mock Controller
        $controller = new Controller();

        // TODO: Figure out and fix why ZenValidator isn't working
        // Mock up basic validator to stop ZenValidator from running
        $mockValidator = new RequiredFields();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm', null, null, $mockValidator);

        // Set UserNameFields to true
        $form->setUseNameFields(true);

        // Get fields
        $fields = $form->Fields();

        // Test that the three items are the expected results: FNAME, LNAME, Email
        $this->assertEquals('0', $fields->fieldPosition('FNAME'));
        $this->assertEquals('1', $fields->fieldPosition('LNAME'));
        $this->assertEquals('2', $fields->fieldPosition('Email'));

    }
}
