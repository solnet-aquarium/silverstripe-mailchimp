<?php

namespace StudioBonito\SilverStripe\MailChimp\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 * The data extension for adding MailChimp settings.
 *
 * @author       Steve Heyes <steve.heyes@studiobonito.co.uk>
 * @copyright    Studio Bonito Ltd.
 */
class MailChimpExtension extends DataExtension
{

    /**
     * List of database fields. {@link DataObject::$db}
     *
     * @var array
     * @config
     */
    private static $db = array(
        'MailChimpApiID' => 'Varchar',
        'MailListID'     => 'Varchar',
    );

    /**
     * Returns a FieldList with which to create the main editing form. {@link DataObject::getCMSFields()}
     *
     * @param FieldList $fields The field list that is being extended
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Add fields to the CMS in the Services tab
        $fields->addFieldsToTab(
            'Root.Services.MailChimp',
            array(
                TextField::create('MailChimpApiID', _t('MailChimp.APIID', 'MailChimp API ID')),
                TextField::create('MailListID', _t('MailChimp.LISTID', 'MailChimp List ID')),
            )
        );
    }
}
