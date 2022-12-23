<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Preference\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\LocalizedException;

class Block extends \Magento\Cms\Model\ResourceModel\Block
{
    /**
     * Save object data
     *
     * @return $this
     * @throws \Exception
     *
     * @deprecated 100.1.0 because entities must not be responsible for their own persistence.
     * Service contracts should persist entities. Use resource model "save" to implement
     * service contract persistence operations.
     */
    public function save(AbstractModel $object)
    {

      	if(!$this->_checkIfOnlyForSchedule($object)){
	        parent::save($object);
	    }
        return $this;
    }

    protected function _checkIfOnlyForSchedule(AbstractModel $object)
    {

       if($object->getCanVersion()==1 && $object->getScheduleTo()!='' && $object->getScheduleFrom()!=''){

            $fromdate=date('Y-m-d H:i:s', strtotime($object->getScheduleFrom().' '.$object->getScheduleFromTime()));
            $todate=date('Y-m-d H:i:s', strtotime($object->getScheduleTo().' '.$object->getScheduleToTime()));
         
            if($this->hasDataChanged($object)){

                if($todate>$fromdate){
                    $object->getEventManager()->dispatch('cms_block_schedule_save_trigger', ['data_object' => $object]);
                    return true;     
                }else{
                    throw new LocalizedException(__('Schedule From Date should be less than Schedule to date.'));
                }

        }else{

             throw new LocalizedException(__('There is no changes found in the content for schedule.'));

        }
       }

    	return false;
    }

    public function hasDataChanged($object)
    {

        return (!$object->dataHasChangedFor('title') &&
            //!$object->dataHasChangedFor('identifier') &&
            //!$object->dataHasChangedFor('content_heading') &&
            !$object->dataHasChangedFor('content')
            //!$object->dataHasChangedFor('is_active') &&
            //!$object->dataHasChangedFor('meta_keywords') &&
            //!$object->dataHasChangedFor('meta_description') &&
            //!$object->dataHasChangedFor('layout_update_xml') &&
            //!$object->dataHasChangedFor('root_template') &&
            //!$object->dataHasChangedFor('custom_theme') &&
            //!$object->dataHasChangedFor('custom_root_template') &&
            //!$object->dataHasChangedFor('custom_layout_update_xml') &&
            //!$object->dataHasChangedFor('custom_theme_from') &&
            //!$object->dataHasChangedFor('custom_theme_to')
        ) ? false : true;

    }
   
}
