<?php

namespace arambalakjian\DataObjectAsPage\ModelAdmin;

use SilverStripe\Admin\ModalController;
use SilverStripe\Control\Controller;

/*
 * Temporary Fix for HTML editor Image/Link popup
 */
class ModelAdminHtmlEditorField_Toolbar extends ModalController
{
    public function forTemplate()
    {
        return sprintf(
            '<div id="cms-editor-dialogs" data-url-linkform="%s" data-url-mediaform="%s"></div>',
            Controller::join_links($this->controller->Link(), $this->name, 'LinkForm', 'forTemplate'),
            Controller::join_links($this->controller->Link(), $this->name, 'MediaForm', 'forTemplate')
        );
    }
}
