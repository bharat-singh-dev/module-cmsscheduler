<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Observer;

use Magento\Framework\Event\Observer;

class Pagerevision extends CmsAbstract
{

    public function execute(Observer $observer)
    {
        $cmsPageData = $observer->getEvent()->getDataObject();
        
        try {

            $isForced = $this->coreRegistry->registry('sycn_force_vcs');
            
            $forceRun = ($isForced && $isForced==1)? true: false;

            if (!$this->canSaveRevision($cmsPageData) && !$forceRun) {
                return $this;
            }
             
            $pageevisionobj = $this->pageRevisionFactory->create();
            $pageevisionobj->addData([
                'page_id'           => $cmsPageData->getPageId(),
                'page_title'        => $cmsPageData->getTitle(),
                'root_template'     => $cmsPageData->getPageLayout(),
                'meta_keywords'     => $cmsPageData->getMetaKeywords(),
                'meta_description'  => $cmsPageData->getMetaDescription(),
                'page_identifier'   => $cmsPageData->getIdentifier(),
                'page_content'      => $cmsPageData->getContent(),
                'is_active'         => $cmsPageData->getIsActive(),
                'store_ids'         => json_encode($cmsPageData->getStores()),
                'user_id'           => $this->getAdminUser()? $this->getAdminUser()->getId(): 0,
                'layout_update_xml' => $cmsPageData->getLayoutUpdateXml(),
            ]);

            $statusComment  = parent::MODIFIED;
            if ($cmsPageData->isObjectNew() || $forceRun) {
                $statusComment  = parent::CREATED;
            }

            $isRestroring   = $this->coreRegistry->registry('restroring_vcs_from');
            $hasAddtComment = $this->coreRegistry->registry('restroring_vcs_comment');

            if ($isRestroring && $isRestroring->getId()) {
                $pageevisionobj->setRestoredFromId($isRestroring->getId());
                $statusComment = parent::RESTORED_FROM_REVISION;
            }

            if ($hasAddtComment) {
                $statusComment = $hasAddtComment;
            }

            $pageevisionobj->setStatusComment($statusComment);

            $pageevisionobj->save();

        } catch (\Exception $e) {
            $this->logError($e);
            $this->messageManager->addError($e->getMessage());
        }
        return $this;

    }

    protected function hasDataChanged($object)
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
