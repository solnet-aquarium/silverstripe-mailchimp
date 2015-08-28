<?php namespace StudioBonito\SilverStripe\MailChimp\Forms;

// Silverstripe
use \SiteConfig;
use \EmailField;
use \TextField;
use \FormAction;
use \FieldList;

// ZenValidator
use \ZenValidator;
use \Constraint_type;

// Mailchimp
use \Mailchimp;
use \Mailchimp_Error;

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
        if (!$validator || !$validator instanceof Validator) {
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
        $fields = \FieldList::create();

        // Add email field
        $fields->push(
            \EmailField::create('Email', _t('MailChimp.EMAIL', 'Email:'))
        );

        // Check to see if we are using the name fields
        if ($this->useNameFields) {
            // Add text field for the first name
            $fields->push(
                \TextField::create('FNAME', _t('MailChimp.FNAME', 'First Name:'))
            );

            // Add text field for the last name
            $fields->push(
                \TextField::create('LNAME', _t('MailChimp.LNAME', 'Last Name:'))
            );
        }

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
        $actions = \FieldList::create();

        // Add submit button
        $action = \FormAction::create($this->actionName, _t('MailChimp.SUBMIT', 'Sign Up'));

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
        // Set up ZenValidator
        $validator = \ZenValidator::create();

        // Add requirement for the email field to be filled in
        $validator->addRequiredFields(
            [
                'Email' => _t('MailChimp.EMAILERROR', 'Please enter your email'),
            ]
        );

        // Add constraint that the contents of the EEmail field follows the pattern for an email
        $validator->setConstraint('Email', Constraint_type::create('email'));

        // Return the Validator
        return $validator;
    }

    /**
     * Process the form and take the data and attempt to add it the mailing list
     *
     * @param array $data
     * @param \Form $form
     */
    public function processMailChimpForm(array $data, \Form $form)
    {
        // Get current site config
        $siteConfig = \SiteConfig::current_site_config();

        // Set up Mailchimp
        $mailChimp = new \Mailchimp(
            $siteConfig->MailChimpApiID
        );

        // Try adding the POST'ed data to the address book list
        try {
            // Create array of data that is going to be sent to Mailchimp
            $mergeVars = [];
            foreach ($data as $key => $value) {
                // Do not add the Email, SecurityID and the button action
                if ($key != 'Email' and $key != 'SecurityID' and $key != 'action_' . $this->actionName) {
                    $mergeVars[$key] = $value;
                }
            }

            // Set double optin option to false
            $doubleOptin = false;

            // Add the email address and related data to the mailing list
            $mailChimp->lists->subscribe(
                $siteConfig->MailListID,
                [
                    'email' => $data['Email'],
                ],
                $mergeVars,
                $doubleOptin
            );

            // Add a success message
            $form->sessionMessage(
                _t('MailChimp.SUCCESSMESSAGE', 'Thank you for subscribing'),
                'good'
            );

            // Catch any errors that are shown and process them
        } catch (\Mailchimp_Error $e) {
            // Check t see if there is a message from the error
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
     * @return $this
     */
    public function setUseNameFields($bool = false)
    {
        // Set the useNameFields variable
        $this->useNameFields = $bool;

        // Return $this to enable chaining
        return $this;
    }
}
