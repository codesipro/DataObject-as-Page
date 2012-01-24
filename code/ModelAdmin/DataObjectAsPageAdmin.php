<?php

class DataObjectAsPageAdmin extends ModelAdmin 
{
	public static $record_controller_class = "DataObjectAsPageAdmin_RecordController";
	
	public function init() 
	{
	    parent::init();
		
	    // Remove all the junk that will break ModelAdmin
	    Requirements::javascript(MOD_DOAP_DIR . '/javascript/jquery.dataobjectaspageadmin.js');
		Requirements::CSS(MOD_DOAP_DIR . '/css/dataobjectaspageadmin.css');
	}
}

class DataObjectAsPageAdmin_RecordController extends ModelAdmin_RecordController
{	
	public function doPublish($data, $form, $request)
	{
		$form->saveInto($this->currentRecord);

		$this->currentRecord->doPublish();

        if(Director::is_ajax()) {
           	return $this->edit($request);
        } else {
            Director::redirectBack();
        }
	}	
	
	public function doUnpublish($data, $form, $request)
	{
		$form->saveInto($this->currentRecord);
		
		$this->currentRecord->doUnpublish();
		
        if(Director::is_ajax()) {
           	return $this->edit($request);
        } else {
            Director::redirectBack();
        }
	}	
	
	public function doDelete($data, $form, $request)
	{
		$this->currentRecord->doDelete();
		
        if(Director::is_ajax()) {
           	$this->edit($request);
        } else {
            Director::redirectBack();
        }
	}	
	
	public function duplicate($data, $form, $request) { 
         
        //Duplicate the object
        $Clone = $this->currentRecord->duplicate();

        //Set the view to be our new duplicate
        $this->currentRecord = $Clone;
 
        if(Director::is_ajax()) {
           	return $this->edit($request);
        } else {
            Director::redirectBack();
        }
    }	
}