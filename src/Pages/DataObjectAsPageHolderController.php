<?php

namespace arambalakjian\DataObjectAsPage\Pages;

use Silverstripe\ORM\PaginatedList;
use Silverstripe\Core\Convert;
use Silverstripe\Security\Security;
use Silverstripe\View\ArrayData;
use arambalakjian\DataObjectAsPage\DataObjects\DataObjectAsPage;

class DataObjectAsPageHolderController extends \PageController
{
    //Class Of Object Listed on this page
    private static $item_class = DataObjectAsPage::class;
    private static $item_sort = 'Created DESC';

    private static $allowed_actions = [
        'show'
    ];

    /*
     * Returns the items to list on this page pagintated or Limited
     */
    public function Items($limit = null)
    {
        //Set custom filter
        $where = ($this->hasMethod('getItemsWhere')) ? $this->getItemsWhere() : null;

        //Set custom sort
        $sort = ($this->hasMethod('getItemsSort')) ? $this->getItemsSort() : $this->config()->get('item_sort');

        //QUERY
        $items = $this->FetchItems($this->config()->get('item_class'), $where, $sort, $limit);

        //Paginate the list
        if (!$limit && $this->Paginate) {
            $items = new PaginatedList($items, $this->request);
            $items->setPageLength($this->ItemsPerPage);
        }

        $this->extend('updateItems', $items);

        return $items;
    }

    /*
     * Get the current DataObject Item from the URL if one exists
     */
    public function getCurrentItem($itemID = null)
    {
        $params = $this->request->allParams();
        $class = $this->config()->get('item_class');

        if ($itemID) {
            $item = $class::get()->byID($itemID);
        } elseif (isset($params['ID'])) {
            //Sanitize
            $URL = Convert::raw2sql($params['ID']);

            $item = $class::get()->filter('URLSegment', $URL)->first();
        }
        $this->extend('updateCurrentItem', $item);
        return $item;
    }

    /*
     * Renders the detail page for the current item passed into the URLs ID
     *
     * Uses DataObjectAsPageViewer_show.ss by default
     */
    public function show()
    {
        if ($item = $this->getCurrentItem()) {
            if ($item->canView()) {
                $data = [
                    'Item' => $item,
                    'Breadcrumbs' => $item->Breadcrumbs(),
                    'MetaTags' => $item->MetaTags(),
                    'BackLink' => base64_decode($this->request->getVar('backlink'))
                ];

                return $this->customise(new ArrayData($data));
            } else {
                return Security::permissionFailure($this);
            }
        } else {
            return $this->httpError(404);
        }
    }
}
