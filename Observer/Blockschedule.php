<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Observer;

use Magento\Framework\Event\Observer;


class Blockschedule extends CmsAbstract
{

    public function execute(Observer $observer)
    {

        $cmsBlockData = $observer->getEvent()->getDataObject();
         
        try{
        	 
		    if (!$this->canSaveRevision($cmsBlockData)) {
		        return $this;
		    }

		    $blockSchedule = $this->blockScheduleFactory->create();
            $fromdate=date('Y-m-d H:i:s', strtotime($cmsBlockData->getScheduleFrom().' '.$cmsBlockData->getScheduleFromTime()));
            $todate=date('Y-m-d H:i:s', strtotime($cmsBlockData->getScheduleTo().' '.$cmsBlockData->getScheduleToTime()));
		     
		    $blockSchedule->addData([
		        'block_id'         => $cmsBlockData->getId(),
		        'block_title'      => $cmsBlockData->getTitle(),
		        'block_content'    => $cmsBlockData->getContent(),
		        'block_identifier' => $cmsBlockData->getIdentifier(),
		        'is_active'        => $cmsBlockData->getIsActive(),
                'schedule_from'    => $fromdate,
                'schedule_to'      => $todate,
		        'store_ids'        => json_encode($cmsBlockData->getStores()),
		        'user_id'          => $this->getAdminUser()->getId(),
                'cron_status'      => '1',
		    ]);

		    $blockSchedule->save();
             $this->messageManager->addSuccess(__('Schedule has been saved. Please review in "Content->Scheduled Blocks".'));

		} catch (\Exception $e) {
			$this->logError($e);
			$this->messageManager->addError($e->getMessage());
		}
		return $this;
    }

     

    protected function hasDataChanged($object)
    {
        return (!$object->dataHasChangedFor('title')
            //&& !$object->dataHasChangedFor('identifier')
            && !$object->dataHasChangedFor('content')
            //&& !$object->dataHasChangedFor('is_active')
        )? false: true;
    }
}
