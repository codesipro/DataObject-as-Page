<?php

namespace arambalakjian\DataObjectAsPage\Pages;

use Silverstripe\Forms\HeaderField;
use Silverstripe\Forms\CheckboxField;
use Silverstripe\Forms\NumericField;
use Silverstripe\Control\Controller;

class DataObjectAsPageHolder extends \Page
{
    private static $table_name = 'DataObjectAsPageHolder';

    private static $hide_ancestor = DataObjectAsPageHolder::class;

    private static $db = [
        'ItemsPerPage' => 'Int',
        'ItemsAsChildren' => 'Boolean',
        'Paginate' => 'Boolean'
    ];

    private static $defaults = [
        'ItemsPerPage' => 10,
        'Paginate' => true,
        'ItemsAsChildren' => false
    ];

    public function getSettingsFields()
    {
        $fields = parent::getSettingsFields();

        $fields->addFieldToTab('Root.Settings', new HeaderField('DOAP', 'DataObject Item Display'));
        $fields->addFieldToTab('Root.Settings', new CheckboxField('Paginate', 'Paginate Items'));
        $fields->addFieldToTab('Root.Settings', new NumericField('ItemsPerPage', 'Items per page (if paginated)'));
        $fields->addFieldToTab('Root.Settings', new CheckboxField('ItemsAsChildren', 'Show DataObjects as Children of this page'));

        return $fields;
    }

    /*
    * Get Items which are to be displayed on this listing page
    */
    public function FetchItems($itemClass, $filter = null, $sort = Null, $limit = Null)
    {
        $results = $itemClass::get();

        if ($filter) {
            if (is_array($filter)) {
                foreach ($filter as $key => $value) {
                    if ($key == "filterany" || $key == "filter" || $key == "where") {
                        $results = $results->$key($value);
                    }
                }
            } else {
                $results = $results->filter($filter);
            }
        }

        if ($sort) {
            $results = $results->sort($sort);
        }

        if ($limit) {
            $results = $results->limit($limit);
        }

        return $results;
    }

    /*
     * This is to prevent the DataObjects from being deleted when we unpublish the page if they are set as children
     */
    public function onBeforeDelete()
    {
        if ($this->ItemsAsChildren) {
            $CurrentVal = $this->get_enforce_strict_hierarchy();
            $this->set_enforce_strict_hierarchy(false);

            parent::onBeforeDelete();

            $this->set_enforce_strict_hierarchy($CurrentVal);
        } else {
             parent::onBeforeDelete();
        }
    }

    /*
     * If ItemsAsChildren is enabled it returns the DataObjects as Children of this page
     */
    public function Children()
    {
        if ($this->ItemsAsChildren && (Controller::curr() instanceof DataObjectAsPageHolderController)) {
            return Controller::curr()->Items();
        } else {
            return parent::Children();
        }
    }
}
