<?php namespace StudioBonito\SilverStripe\MailChimp\Forms;

use \EmailField;
use \FormAction;
use \FieldList;
use \ZenValidator;
use \Constraint_type;
use \Mailchimp;
use \SiteConfig;

/**
 * MailChimpForm.
 *
 * @author       Steve Heyes <steve.heyes@studiobonito.co.uk>
 * @copyright    Studio Bonito Ltd.
 */
class MailChimpForm extends \Form
{
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
        if (!$fields || !$fields instanceof FieldList) {
            $fields = $this->getDefaultFields();
        }
        if (!$actions || !$actions instanceof FieldList) {
            $actions = $this->getDefaultActions();
        }
        if (!$validator || !$validator instanceof Validator) {
            $validator = $this->getDefaultValidator();
        }

        parent::__construct($controller, $name, $fields, $actions, $validator);
    }

    /**
     * Create the default fields.
     *
     * @return FieldList
     */
    public function getDefaultFields()
    {
        $fields = FieldList::create();

        $email = \EmailField::create('Email', _t('MailChimp.EMAIL', 'Email:'));

        $fields->push($email);

        return $fields;
    }

    /**
     * Create the default actions.
     *
     * @return FieldList
     */
    public function getDefaultActions()
    {
        $actions = FieldList::create();

        $action = \FormAction::create('processMailChimpForm', _t('MailChimp.SUBMIT', 'Sign Up'));

        $actions->push($action);

        return $actions;
    }

    /**
     * Create the default validator.
     *
     * @return Validator
     */
    public function getDefaultValidator()
    {
        $validator = \ZenValidator::create();

        $validator->addRequiredFields(
            array(
                'Email' => _t('MailChimp.EMAILERROR', 'Please enter your email')
            )
        );

        $validator->setConstraint('Email', Constraint_type::create('email'));

        return $validator;
    }

    public function processMailChimpForm(array $data, \Form $form)
    {
        $siteConfig = \SiteConfig::current_site_config();
        $mailChimp = new \Mailchimp(
            $siteConfig->MailChimpApiID
        );

        try {
            $mailChimp->lists->subscribe(
                $siteConfig->MailListID,
                array(
                    'email' => $data['Email']
                )
            );
            $form->sessionMessage(
                _t('MailChimp.SUCCESSMESSAGE', 'Thank you for subscribing'),
                'good'
            );
        } catch (Mailchimp_Error $e) {
            if ($e->getMessage()) {
                $form->sessionMessage(
                    $e->getMessage(),
                    'bad '
                );
            } else {
                $form->sessionMessage(
                    _t('MailChimp.UNKNOWNERROR', 'An unknown error occured'),
                    'bad '
                );
            }
        }
        $this->controller->redirectBack();
    }
}