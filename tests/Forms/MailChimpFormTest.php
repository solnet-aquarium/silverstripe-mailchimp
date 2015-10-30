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
    protected $mockController;
    protected $form;

    /**
     * Sets up controller and form.
     */
    protected function setUp()
    {
        // Mock Controller
        $this->mockController = new Controller();

        // Mock Form
        $this->form = MailChimpForm::create($this->mockController, 'TestForm');
    }

    /**
     * Tears down controller and form.
     */
    protected function tearDown()
    {
        $this->mockController = null;
        $this->form = null;
    }

    /**
     * Test that Email, SecurityID and button action are not added to merge var array.
     */
    public function testMergeVarArrayOutput()
    {
        $name = $this->form->getName();
        // Mock Data
        $data = array(
            "url" => "/news/MailChimpForm",
            "FNAME" => "Test",
            "LNAME" => "mcTesterson",
            "Email" => "test@studiobonito.co.uk",
            "SecurityID" => "f8b12d9602d470a205ef1b2ca94ac7d7dbd91a68",
            "action_process".$name => "Sign Up"
        );

        // Expected results
        $expected = array(
            "FNAME" => "Test",
            "LNAME" => "mcTesterson"
        );

        // Create mergeVar array
        $mergeVars = $this->form->createMergeVarArray($data);

        // Assert that $form->useNameFields is true
        $this->assertEquals($expected, $mergeVars);
    }

    /**
     * Test that doubleOptin setter.
     */
    public function testDoubleOptinSetter()
    {
        $this->form->setDoubleOptin(true);

        $doubleOptin = $this->form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(true, $doubleOptin);
    }

    /**
    * Test that doubleOptin setter with text.
    */
    public function testDoubleOptinSetterWithText()
    {
        $this->form->setDoubleOptin("This sould make double optin false");

        $doubleOptin = $this->form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(true, $doubleOptin);
    }

    /**
     * Test that doubleOptin setter with false.
     */
    public function testDoubleOptinSetterWithFalse()
    {
        $this->form->setDoubleOptin(false);

        $doubleOptin = $this->form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(false, $doubleOptin);
    }

    /**
     * Test that doubleOptin getter.
     */
    public function testDoubleOptinGetter()
    {
        $doubleOptin = $this->form->getDoubleOptin();

        // Assert that $doubleOptin is false as that is the default
        $this->assertEquals(false, $doubleOptin);
    }

    /**
     * Test for setting the variable $useNameFields.
     */
    public function testSetUseNameFields()
    {
        // Set UserNameFields to true
        $this->form->setUseNameFields(true);

        // Assert that $this->form->useNameFields is true
        $this->assertEquals(true, $this->form->useNameFields);
    }

    /**
     * Test for whether setting the variable $useNameFields actually adds the name fields to the list.
     */
    public function testAddingUseNameFields()
    {
        // Set UserNameFields to true
        $this->form->setUseNameFields(true);

        // Get fields
        $fields = $this->form->Fields();

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
     * Test for whether setting the variable $useNameFields as false removes the first and last name field.
     */
    public function testRemovingUseNameFields()
    {
        // Add the fields
        $this->form->setUseNameFields(true);

        // Remove the fields
        $this->form->setUseNameFields(false);

        // Get fields
        $fields = $this->form->Fields();

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
     * Test for the order of the fields to be correct.
     */
    public function testAddingUseNameFieldsOrder()
    {
        // Set UserNameFields to true
        $this->form->setUseNameFields(true);

        // Get fields
        $fields = $this->form->Fields();

        // Test that the three items are the expected results: FNAME, LNAME, Email
        $this->assertEquals('0', $fields->fieldPosition('FNAME'));
        $this->assertEquals('1', $fields->fieldPosition('LNAME'));
        $this->assertEquals('2', $fields->fieldPosition('Email'));

    }
}
