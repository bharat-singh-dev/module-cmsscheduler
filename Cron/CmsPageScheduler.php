<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Cron;

use \Psr\Log\LoggerInterface;

class CmsPageScheduler extends CronSchedulerAbstract
{
    /**
     * @var  \Magento\Cms\Model\PageFactory
     */
    protected $pageModelFactory;

    /**
     * @var  \SixtySeven\CMSScheduler\Model\PageRevisionFactory 
     */
    protected $pageRevisionFactory;

    /**
     * @var  \SixtySeven\CMSScheduler\Model\PageScheduleFactory
     */
    protected $pageScheduleFactory;

    /**
     * @param LoggerInterface
     * @param \SixtySeven\CMSScheduler\Helper\CacheManager
     * @param \Magento\Framework\Registry
     * @param \Magento\Cms\Model\PageFactory
     * @param \SixtySeven\CMSScheduler\Model\PageScheduleFactory
     * @param \SixtySeven\CMSScheduler\Model\PageRevisionFactory
     */
    public function __construct(
        LoggerInterface $logger,
        \SixtySeven\CMSScheduler\Helper\CacheManager $cacheManagerHelper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\PageFactory $pageModelFactory,
        \SixtySeven\CMSScheduler\Model\PageScheduleFactory $pageScheduleFactory,
        \SixtySeven\CMSScheduler\Model\PageRevisionFactory $pageRevisionFactory
    ) {
        $this->pageScheduleFactory = $pageScheduleFactory;
        $this->pageRevisionFactory = $pageRevisionFactory;
        $this->pageModelFactory   = $pageModelFactory;
        parent::__construct($logger, $cacheManagerHelper, $coreRegistry);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $this->checkAndActivateBlockContents();
        $this->checkAndRollbackBlockContents();
        $this->logger->info('Cron Works');
    }

    private function checkAndActivateBlockContents()
    {
        $collection = $this->pageScheduleFactory->create()->getCollection();
        //get all pending state that needs to be apply to respective cms block
        $collection->addFieldToFilter('cron_status', 1); //1:means pending and not running
        $collection->addFieldToFilter('schedule_from', ['lteq' => date('Y-m-d H:i:s')]);
        $collection->addFieldToFilter('schedule_to', ['gt' => date('Y-m-d H:i:s')]);
        if ($collection->getSize() > 0) {
            $pageIds          = array_unique($collection->getColumnValues('page_id'));
            $versionCollection = $this->getLastVersionOfPageByIds($pageIds);
            foreach ($collection as $_item) {

                $lastRevision = $versionCollection->getItemByColumnValue('page_id', $_item->getPageId());

                $cmsPagemodel = $this->pageModelFactory->create();

                $cmsPagemodel->load($_item->getPageId());

                if (!$cmsPagemodel->getIsActive()) {
                    continue;
                }
                
                if($lastRevision && $lastRevision->getId())
                    $_item->setRollbackVersionId($lastRevision->getId());

                $_item->setCronStatus(2); //2: means schedule applied and running
                $this->coreRegistry->register('restroring_vcs_comment', 'Scheduled Content');
                $cmsPagemodel->setContent($_item->getPageContent());
                $cmsPagemodel->setTitle($_item->getPageTitle());
                $cmsPagemodel->save();
                $_item->save();

                //release memory
                unset($cmsPagemodel);
                unset($lastRevision);
            }

            $this->cacheManagerHelper->flushPageCache();
        }
    }

    private function checkAndRollbackBlockContents()
    {
        $collection = $this->pageScheduleFactory->create()->getCollection();
        //get all pending state that needs to be apply to respective cms block
        $collection->addFieldToFilter('cron_status', ['in' => [1, 2]]); //1:means pending and not running
        $collection->addFieldToFilter('schedule_from', ['lt' => date('Y-m-d H:i:s')]);
        $collection->addFieldToFilter('schedule_to', ['lteq' => date('Y-m-d H:i:s')]);

        if ($collection->getSize() > 0) {
            $versionIds        = array_filter(array_unique($collection->getColumnValues('rollback_version_id')));
            $versionCollection = $this->getVersionOfPageByIds($versionIds);
            foreach ($collection as $_item) {

                if ($_item->getRollbackVersionId()) {
                    $lastRevision = $versionCollection->getItemById($_item->getRollbackVersionId());

                    $cmsPagemodel = $this->pageModelFactory->create();

                    $cmsPagemodel->load($_item->getPageId());

                    if ($cmsPagemodel->getIsActive()) {
                        $this->coreRegistry->register('restroring_vcs_from', $lastRevision);
                        $cmsPagemodel->setTitle($lastRevision->getPageTitle());
                        $cmsPagemodel->setContent($lastRevision->getPageContent());
                        $cmsPagemodel->save();
                    }
                }

                $_item->setCronStatus(3); //3: means rollbacked and closed schedule

                $_item->save();

                //release memory
                unset($cmsPagemodel);
                unset($lastRevision);
            }

            $this->cacheManagerHelper->flushPageCache();
        }
    }

    private function getLastVersionOfPageByIds($blockIds)
    {
        $collection = $this->pageRevisionFactory->create()->getCollection();
        $collection->addFieldToSelect('page_id');
        $collection->addFieldToSelect(new \Zend_Db_Expr('MAX(revision_id) AS revision_id'));
        $collection->addFieldToFilter('page_id', ['in' => $blockIds]);
        $collection->getSelect()->group('page_id');
        return $collection;
    }

    private function getVersionOfPageByIds($versionIds)
    {
        $collection = $this->pageRevisionFactory->create()->getCollection();
        $collection->addFieldToFilter('revision_id', ['in' => $versionIds]);
        return $collection;
    }
}
