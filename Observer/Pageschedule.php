<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Observer;

use Magento\Framework\Event\Observer;

class Pageschedule extends CmsAbstract
{

    public function execute(Observer $observer)
    {

        $cmsPageData = $observer->getEvent()->getDataObject();
        try {

            if (!$this->canSaveRevision($cmsPageData)) {
                return $this;
            }

            $pageSchedule = $this->pageScheduleFactory->create();

            $fromdate = date('Y-m-d H:i:s', strtotime($cmsPageData->getScheduleFrom() . ' ' . $cmsPageData->getScheduleFromTime()));
            $todate   = date('Y-m-d H:i:s', strtotime($cmsPageData->getScheduleTo() . ' ' . $cmsPageData->getScheduleToTime()));

            $pageSchedule->addData([
                'page_id'           => $cmsPageData->getPageId(),
                'page_title'        => $cmsPageData->getTitle(),
                'root_template'     => $cmsPageData->getPageLayout(),
                'meta_keywords'     => $cmsPageData->getMetaKeywords(),
                'meta_description'  => $cmsPageData->getMetaDescription(),
                'page_identifier'   => $cmsPageData->getIdentifier(),
                'page_content'      => $cmsPageData->getContent(),
                'is_active'         => $cmsPageData->getIsActive(),
                'store_ids'         => json_encode($cmsPageData->getStores()),
                'user_id'           => $this->getAdminUser()->getId(),
                'layout_update_xml' => $cmsPageData->getLayoutUpdateXml(),
                'schedule_from'     => $fromdate,
                'schedule_to'       => $todate,
            ]);

            $pageSchedule->save();
            $this->messageManager->addSuccess(__('Schedule has been saved. Please review in "Content->Scheduled Pages".'));

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
        ) ? false : true;
    }
}
