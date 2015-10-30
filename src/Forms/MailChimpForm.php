<?php namespace StudioBonito\SilverStripe\MailChimp\Forms;

use \EmailField;
use \FieldList;
use \Form;
use \FormAction;
use \Mailchimp;
use \Mailchimp_Error;
use \RequiredFields;
use \SiteConfig;
use \TextField;

/**
 * The form used to collect and process data to add to a MailChimp mailing list.
 *
 * @author       Steve Heyes <steve.heyes@studiobonito.co.uk>
 * @copyright    Studio Bonito Ltd.
 */
class MailChimpForm extends \Form
{
    /**
     * The name of the action used on the submit button
     *
     * @var string
     */
    private $actionName = 'processMailChimpForm';

    /**
     * Check for if First Name and Last Name fields are included in the form
     *
     * @var boolean
     */
    private $useNameFields = false;

    /**
     * Variable used to set double optin in for Mailchimp
     *
     * @var boolean
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
        if (!$validator || !is_subclass_of($validator, 'Validator')) {
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
        // Create the field list
        $fields = FieldList::create();

        // Add email field
        $fields->push(
            EmailField::create('Email', _t('MailChimp.EMAILLABEL', 'Email:'))
        );

        // Return the field list
        return $fields;
    }

    /**
     * Create the default actions.
     *
     * @return FieldList
     */
    public function getDefaultActions()
    {
        // Creat Field list
        $actions = FieldList::create();

        // Add submit button
        $action = FormAction::create($this->actionName, _t('MailChimp.SUBMIT', 'Sign Up'));

        // Add button the field list
        $actions->push($action);

        // Return the field list
        return $actions;
    }

    /**
     * Create the default validator.
     *
     * @return Validator
     */
    public function getDefaultValidator()
    {
        // Return required field validator for Email
        return new RequiredFields(array('Email'));
    }

    /**
     * Process the form and take the data and attempt to add it the mailing list
     *
     * @param array $data
     * @param Form  $form
     */
    public function processMailChimpForm(array $data, Form $form)
    {
        // Get current site config
        $siteConfig = SiteConfig::current_site_config();

        // Set up Mailchimp
        $mailChimp = new Mailchimp(
            $siteConfig->MailChimpApiID
        );

        // Try adding the POST'ed data to the address book list
        try {
            // Strip out merge vats
            $mergeVars = $this->createMergeVarArray($data);

            // Add the email address and related data to the mailing list
            $mailChimp->lists->subscribe(
                $siteConfig->MailListID,
                array(
                    'email' => $data['Email'],
                ),
                $mergeVars,
                $this->doubleOptin
            );

            // Add a success message
            $form->sessionMessage(
                _t('MailChimp.SUCCESSMESSAGE', 'Thank you for subscribing'),
                'good'
            );
            // Catch any errors that are shown and process them
        } catch (Mailchimp_Error $e) {
            // Check to see if there is a message from the error
            if ($e->getMessage()) {
                // Add an error message to the form with the messsage from the caught error
                $form->sessionMessage(
                    $e->getMessage(),
                    'bad '
                );
            } else {
                // Add a generic error message to the form
                $form->sessionMessage(
                    _t('MailChimp.UNKNOWNERROR', 'An unknown error occured'),
                    'bad '
                );
            }
        }

        // Redirect back to the page where the form was submit from
        $this->controller->redirectBack();
    }

    /**
     * Sets the value for the useNameField variable
     *
     * @param bool|false $bool
     *
     * @return $this
     */
    public function setUseNameFields($bool = false)
    {
        // Set the useNameFields variable
        $this->useNameFields = $bool;

        // Get fields
        $fields = $this->Fields();

        // Check $bool
        if ($bool) {
            // If it's true, add First Name and Last Name fields
            // Add text field for the first name
            $fields->push(
                TextField::create('FNAME', _t('MailChimp.FNAMELABEL', 'First Name:'))
            );

            // Add text field for the last name
            $fields->push(
                TextField::create('LNAME', _t('MailChimp.LNAMELABEL', 'Last Name:'))
            );

            // Set field order
            $fields->changeFieldOrder(
                array(
                    'FNAME',
                    'LNAME',
                    'Email',
                )
            );
        } else {
            // If it's false, remove First Name and Last Name fields
            $fields->removeByName('FNAME');
            $fields->removeByName('LNAME');
        }

        // Set update fields
        $this->setFields($fields);

        // Return $this to enable chaining
        return $this;
    }

    /**
     * Getter for useNameFields
     *
     * @return bool
     */
    public function getUseNameFields()
    {
        return $this->useNameFields;
    }

    /**
     * Creates an array of fields to be added into mergeVars for MailChimp
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
            "action_processMailChimpForm",
        );

        // Create array of data that is going to be sent to Mailchimp
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
     * @return boolean
     */
    public function getDoubleOptin()
    {
        return $this->doubleOptin;
    }

    /**
     * @param boolean $doubleOptin
     */
    public function setDoubleOptin($doubleOptin)
    {
        if ($doubleOptin == true) {
            $this->doubleOptin = true;

            return;
        }
        $this->doubleOptin = false;
    }
}
