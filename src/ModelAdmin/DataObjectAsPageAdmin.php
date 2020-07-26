<?php

namespace arambalakjian\DataObjectAsPage\ModelAdmin;

use Silverstripe\Admin\ModelAdmin;
use Silverstripe\Versioned\Versioned;
use Silverstripe\View\Requirements;
use arambalakjian\DataObjectAsPage\Forms\VersionedGridFieldDeleteAction;
use arambalakjian\DataObjectAsPage\Forms\VersionedGridFieldDetailForm_ItemRequest;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;

class DataObjectAsPageAdmin extends ModelAdmin
{
    private static $url_segment = 'doapadmin';

    public function init()
    {
        parent::init();

        //if versioned we need to tell ModelAdmin to read from stage
        if (Singleton($this->modelClass)->isVersioned) {
            Versioned::set_stage('Stage');
        }
        //Styling for preview links and status
        Requirements::CSS('/vendor/arambalakjian/dataobjectaspage/css/dataobjectaspageadmin.css');
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id = null, $fields = null);

        if (Singleton($this->modelClass)->isVersioned) {
            $listfield = $form->Fields()->fieldByName($this->modelClass);

            $gridFieldConfig = $listfield->getConfig();

            $gridFieldConfig->getComponentByType(GridFieldDetailForm::class)
                ->setItemRequestClass(VersionedGridFieldDetailForm_ItemRequest::class);

            $gridFieldConfig->removeComponentsByType(GridFieldDeleteAction::class);
            $gridFieldConfig->addComponent(new VersionedGridFieldDeleteAction());
        }

        return $form;
    }
}
