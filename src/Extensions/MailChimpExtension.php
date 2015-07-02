<?php namespace StudioBonito\SilverStripe\MailChimp\Extensions;

use TextField;

/**
 * MailChimpExtension.
 *
 * @author       Tom Densham <tom.densham@studiobonito.co.uk>
 * @copyright    Studio Bonito Ltd.
 */
class MailChimpExtension extends \DataExtension
{

    /**
     * List of database fields. {@link DataObject::$db}
     *
     * @var array
     */
    private static $db = array(
        'MailChimpApiID' => 'Varchar',
        'MailListID' => 'Varchar',
    );

    /**
     * Returns a FieldList with which to create the main editing form. {@link DataObject::getCMSFields()}
     *
     * @return FieldList The fields to be displayed in the CMS.
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.MailChimp',
            array(
                TextField::create('MailChimpApiID', _t('MailChimp.APIID', 'MailChimp API ID')),
                TextField::create('MailListID', _t('MailChimp.LISTID', 'MailChimp List ID'))
            )
        );
    }
}