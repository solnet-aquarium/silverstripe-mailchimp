<?php namespace StudioBonito\SilverStripe\MailChimp\Extensions;

use \Injector;
use \RequiredFields;
use \Controller;
use \Form;
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
     * Test that Mailchimp object is created when given a MailChimpAPIID
     */

    /**
     * Test that Email, SecurityID and button action are not added to merge var array
     */
    public function testMergeVarArrayOutput()
    {
        // Mock Data
        $data = [
            "url" => "/news/MailChimpForm",
            "FNAME" => "Test",
            "LNAME" => "mcTesterson",
            "Email" => "test@studiobonito.co.uk",
            "SecurityID" => "f8b12d9602d470a205ef1b2ca94ac7d7dbd91a68",
            "action_processMailChimpForm" => "Sign Up"
        ];

        // Expected results
        $expected = [
            "FNAME" => "Test",
            "LNAME" => "mcTesterson"
        ];

        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm');

        // Create mergeVar array
        $mergeVars = $form->createMergeVarArray($data);

        // Assert that $form->useNameFields is true
        $this->assertEquals($expected, $mergeVars);
    }

    /**
     * Test that doubleOptin setter
     */
    public function testDoubleOptinSetter()
    {
        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm');

        $form->setDoubleOptin(true);

        $doubleOptin = $form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(true, $doubleOptin);
    }

    /**
 * Test that doubleOptin setter with text
 */
    public function testDoubleOptinSetterWithText()
    {
        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm');

        $form->setDoubleOptin("This sould make double optin false");

        $doubleOptin = $form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(true, $doubleOptin);
    }

    /**
     * Test that doubleOptin setter with false
     */
    public function testDoubleOptinSetterWithFalse()
    {
        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm');

        $form->setDoubleOptin(false);

        $doubleOptin = $form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(false, $doubleOptin);
    }

    /**
     * Test that doubleOptin getter
     */
    public function testDoubleOptinGetter()
    {
        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm');

        $doubleOptin = $form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(false, $doubleOptin);
    }

    /**
     * Test for setting the variable $useNameFields
     */
    public function testSetUseNameFields()
    {
        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm');

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

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm', null, null);

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

    /**
     * Test for whether setting the variable $useNameFields as false removes the first and last name field
     */
    public function testRemovingUseNameFields()
    {
        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm', null, null);

        // Add the fields
        $form->setUseNameFields(true);

        // Remove the fields
        $form->setUseNameFields(false);

        // Get fields
        $fields = $form->Fields();

        // Get first name field
        $fname = $fields->fieldByName('FNAME');

        // Check if First Name field is there
        $this->assertEquals(null, $fname);

        // Get last name field
        $lname = $fields->fieldByName('LNAME');

        // Check if Last Name field is there
        $this->assertEquals(null, $lname);
    }

    /**
     * Test for the order of the fields to be correct
     */
    public function testAddingUseNameFieldsOrder()
    {
        // Mock Controller
        $controller = new Controller();

        // Set up the Mailchimp Form
        $form = MailChimpForm::create($controller, 'TestForm');

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
