<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Observer;

use Magento\Framework\Event\Observer;

class Blockrevision extends CmsAbstract
{

    public function execute(Observer $observer)
    {
        $cmsBlockData = $observer->getEvent()->getDataObject();

        try {

            $isRestroring   = $this->coreRegistry->registry('restroring_vcs_from');
            $isForced = $this->coreRegistry->registry('sycn_force_vcs');
            
            $forceRun = ($isForced && $isForced==1)? true: false;

            if (!$this->canSaveRevision($cmsBlockData) && !$forceRun) {
                return $this;
            }

            $blockRevision = $this->blockRevisionFactory->create();

            $blockRevision->addData([
                'block_id'         => $cmsBlockData->getId(),
                'block_title'      => $cmsBlockData->getTitle(),
                'block_content'    => $cmsBlockData->getContent(),
                'block_identifier' => $cmsBlockData->getIdentifier(),
                'is_active'        => $cmsBlockData->getIsActive(),
                'store_ids'        => json_encode($cmsBlockData->getStores()),
                'user_id'          => $this->getAdminUser()? $this->getAdminUser()->getId(): 0,
            ]);

            $statusComment  = parent::MODIFIED;

            if ($cmsBlockData->isObjectNew() || $forceRun) {
                $statusComment  = parent::CREATED;
            }

            
            $hasAddtComment = $this->coreRegistry->registry('restroring_vcs_comment');

            if ($isRestroring && $isRestroring->getId()) {
                $blockRevision->setRestoredFromId($isRestroring->getId());
                $statusComment = parent::RESTORED_FROM_REVISION;
            }

            if ($hasAddtComment) {
                $statusComment = $hasAddtComment;
            }

            $blockRevision->setStatusComment($statusComment);

            $blockRevision->save();

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
