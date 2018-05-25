<?php

namespace StudioBonito\SilverStripe\MailChimp\Forms;

use SilverStripe\Control\Director;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use Mailchimp;
use Mailchimp_Error;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\Validator;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Forms\TextField;

/**
 * The form used to collect and process data to add to a MailChimp mailing list.
 *
 * @author       Steve Heyes <steve.heyes@studiobonito.co.uk>
 * @copyright    Studio Bonito Ltd.
 */
class MailChimpForm extends Form
{

    /**
     * Check for if First Name and Last Name fields are included in the form.
     *
     * @var bool
     */
    private $useNameFields = false;

    /**
     * Variable used to set double optin in for MailChimp.
     *
     * @var bool
     */
    private $doubleOptin = false;

    /**
     * Create a new form, with the given fields and action buttons.
     * Fallback to default fields and action buttons if none are supplied.
     *
     * @param Controller $controller
     * @param String     $name
     * @param FieldList  $fields
     * @param FieldList  $actions
     * @param Validator  $validator
     */
    public function __construct(
        $controller,
        $name,
        FieldList $fields = null,
        FieldList $actions = null,
        $validator = null
    ) {
        // Get default fields
        if (!$fields || !$fields instanceof FieldList) {
            $fields = $this->getDefaultFields();
        }

        // Get default actions
        if (!$actions || !$actions instanceof FieldList) {
            $actions = $this->getDefaultActions();
        }

        // Get default Validator
        if (!$validator || !is_subclass_of($validator, Validator::class)) {
            $validator = $this->getDefaultValidator();
        }

        // Run parent construct
        parent::__construct($controller, $name, $fields, $actions, $validator);
    }

    /**
     * Create the default fields.
     *
     * @return FieldList
     */
    public function getDefaultFields()
    {
        // Create the field list.
        $fields = FieldList::create();

        // Add email field.
        $fields->push(
            EmailField::create('Email', _t('MailChimp.EMAILLABEL', 'Email:'))
        );

        // Return the field list.
        return $fields;
    }

    /**
     * Create the default actions.
     *
     * @return FieldList
     */
    public function getDefaultActions()
    {
        // Create Field list.
        $actions = FieldList::create();

        // Add submit button.
        $action = FormAction::create('process', _t('MailChimp.SUBMIT', 'Sign Up'));

        // Add button the field list.
        $actions->push($action);

        // Return the field list.
        return $actions;
    }

    /**
     * Create the default validator.
     *
     * @return Validator
     */
    public function getDefaultValidator()
    {
        // Return required field validator for Email.
        return new RequiredFields(array('Email'));
    }

    /**
     * Process the form and take the data and attempt to add it the mailing list.
     *
     * @param array $data
     * @param Form  $form
     *
     * @return mixed
     */
    public function process(array $data, Form $form)
    {
        // Get current site config.
        $siteConfig = SiteConfig::current_site_config();

        // Set up MailChimp.
        $mailChimp = new Mailchimp(
            $siteConfig->MailChimpApiID
        );
        $response = array();

        // Try adding the POST'ed data to the address book list.
        try {
            // Strip out merge vars.
            $mergeVars = $this->createMergeVarArray($data);

            // Add the email address and related data to the mailing list.
            $mailChimp->lists->subscribe(
                $siteConfig->MailListID,
                array(
                    'email' => $data['Email'],
                ),
                $mergeVars,
                'html',
                $this->doubleOptin,
                false,
                true,
                true
            );

            // Add a success message.
            $response = array(
                'message' => _t('MailChimp.SUCCESSMESSAGE', 'Thank you for subscribing'),
                'status' => 'good'
            );

        // Catch any errors that are shown and process them.
        } catch (Mailchimp_Error $e) {
            if ($e->getMessage()) {
                $response = array(
                    'message' => $e->getMessage(),
                    'status' => 'bad '
                );
            } else {
                $response = array(
                    'message' => _t('MailChimp.UNKNOWNERROR', 'An unknown error occured'),
                    'status' => 'bad'
                );
            }
        }

        // Check if it is an AJAX request
        if (Director::is_ajax()) {
            // Return a JSON object
            return json_encode($response);
        } else {
            // Set up a form session message
            $form->sessionMessage(
                $response['message'],
                $response['status']
            );

            // Redirect back
            $this->controller->redirectBack();
        }
    }

    /**
     * Sets the value for the useNameField variable.
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setUseNameFields($bool = false)
    {
        // Set the useNameFields variable.
        $this->useNameFields = (bool) $bool;

        // Get fields.
        $fields = $this->Fields();

        // Check $bool.
        if ($bool) {
            // If it's true, add First Name and Last Name fields.
            // Add text field for the first name.
            $fields->push(
                TextField::create('FNAME', _t('MailChimp.FNAMELABEL', 'First Name:'))
            );

            // Add text field for the last name.
            $fields->push(
                TextField::create('LNAME', _t('MailChimp.LNAMELABEL', 'Last Name:'))
            );

            // Set field order.
            $fields->changeFieldOrder(
                array(
                    'FNAME',
                    'LNAME',
                    'Email',
                )
            );
        } else {
            // If it's false, remove First Name and Last Name fields.
            $fields->removeByName('FNAME');
            $fields->removeByName('LNAME');
        }

        // Set update fields.
        $this->setFields($fields);

        // Return $this to enable chaining
        return $this;
    }

    /**
     * Getter for useNameFields.
     *
     * @return bool
     */
    public function getUseNameFields()
    {
        return $this->useNameFields;
    }

    /**
     * Creates an array of fields to be added into mergeVars for MailChimp.
     *
     * @param $data array of data
     *
     * @return array
     */
    public function createMergeVarArray($data)
    {
        // Black list
        $blackList = array(
            "url",
            "Email",
            "SecurityID",
            "action_process",
        );

        // Create array of data that is going to be sent to MailChimp
        $mergeVars = array();
        foreach ($data as $key => $value) {
            // Check is key is in the black list
            if (!in_array($key, $blackList)) {
                $mergeVars[$key] = $value;
            }
        }

        return $mergeVars;
    }

    /**
     * Getter for Double OptIn.
     *
     * @return bool
     */
    public function getDoubleOptin()
    {
        return $this->doubleOptin;
    }

    /**
     * Setter for DoubleOptin.
     *
     * @param bool $doubleOptin
     */
    public function setDoubleOptin($doubleOptin)
    {
        $this->doubleOptin = (bool) $doubleOptin;
    }
}
