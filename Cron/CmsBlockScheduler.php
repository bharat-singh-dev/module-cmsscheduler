<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Cron;

use \Psr\Log\LoggerInterface;

class CmsBlockScheduler extends CronSchedulerAbstract
{
    /**
     * @var  \Magento\Cms\Model\BlockFactory
     */
    protected $blockModelFactory;

    /**
     * @var  \SixtySeven\CMSScheduler\Model\BlockRevisionFactory 
     */
    protected $blockRevisionFactory;

    /**
     * @var  \SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     */
    protected $blockScheduleFactory;

    /**
     * @param LoggerInterface
     * @param \SixtySeven\CMSScheduler\Helper\CacheManager
     * @param \Magento\Framework\Registry
     * @param \Magento\Cms\Model\BlockFactory
     * @param \SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     * @param \SixtySeven\CMSScheduler\Model\BlockRevisionFactory
     */
    public function __construct(
        LoggerInterface $logger,
        \SixtySeven\CMSScheduler\Helper\CacheManager $cacheManagerHelper,
        \Magento\Framework\Registry $coreRegistry, 
        \Magento\Cms\Model\BlockFactory $blockModelFactory,
        \SixtySeven\CMSScheduler\Model\BlockScheduleFactory $blockScheduleFactory,
        \SixtySeven\CMSScheduler\Model\BlockRevisionFactory $blockRevisionFactory 
    ) {
        $this->blockScheduleFactory = $blockScheduleFactory;
        $this->blockRevisionFactory = $blockRevisionFactory;
        $this->blockModelFactory = $blockModelFactory; 
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

    private function checkAndActivateBlockContents(){
    	$collection = $this->blockScheduleFactory->create()->getCollection();
    	//get all pending state that needs to be apply to respective cms block
    	$collection->addFieldToFilter('cron_status',1); //1:means pending and not running
    	$collection->addFieldToFilter('schedule_from', ['lteq'=>date('Y-m-d H:i:s')]);
    	$collection->addFieldToFilter('schedule_to', ['gt'=>date('Y-m-d H:i:s')]);
    	if($collection->getSize()>0){
    		$blockIds = array_unique($collection->getColumnValues('block_id'));
    		$versionCollection = $this->getLastVersionOfBlockByIds($blockIds);
    		foreach ($collection as $_item) {
    			
    			$lastRevision = $versionCollection->getItemByColumnValue('block_id',$_item->getBlockId());

    			$cmsBlockmodel= $this->blockModelFactory->create();
    			
    			$cmsBlockmodel->load($_item->getBlockId());
    			
    			if(!$cmsBlockmodel->getIsActive()){
    				continue;
    			}
    			
                if($lastRevision && $lastRevision->getId())
                    $_item->setRollbackVersionId($lastRevision->getId());

    			$_item->setCronStatus(2); //2: means schedule applied and running
    			$this->coreRegistry->register('restroring_vcs_comment', 'Scheduled Content');
    			$cmsBlockmodel->setContent($_item->getBlockContent());
    			$cmsBlockmodel->setTitle($_item->getBlockTitle());
    			$cmsBlockmodel->save();
    			$_item->save();

    			//release memory
    			unset($cmsBlockmodel);
    			unset($lastRevision);
    		}

    		$this->cacheManagerHelper->flushPageCache();
    	}
    }

    private function checkAndRollbackBlockContents(){
    	$collection = $this->blockScheduleFactory->create()->getCollection();
    	//get all pending state that needs to be apply to respective cms block
    	$collection->addFieldToFilter('cron_status',['in'=>[1,2]]); //1:means pending and not running
    	$collection->addFieldToFilter('schedule_from', ['lt'=>date('Y-m-d H:i:s')]);
    	$collection->addFieldToFilter('schedule_to', ['lteq'=>date('Y-m-d H:i:s')]);
    	
    	if($collection->getSize()>0){
    		$versionIds = array_filter(array_unique($collection->getColumnValues('rollback_version_id')));
    		$versionCollection = $this->getVersionOfBlockByIds($versionIds);
    		foreach ($collection as $_item) {
    			
    			if($_item->getRollbackVersionId()){
    				$lastRevision = $versionCollection->getItemById($_item->getRollbackVersionId());

    				$cmsBlockmodel= $this->blockModelFactory->create();
    			
	    			$cmsBlockmodel->load($_item->getBlockId());
	    			
	    			if($cmsBlockmodel->getIsActive()){
	    				$this->coreRegistry->register('restroring_vcs_from', $lastRevision);
	    				$cmsBlockmodel->setTitle($lastRevision->getBlockTitle());
	    				$cmsBlockmodel->setContent($lastRevision->getBlockContent());
    					$cmsBlockmodel->save();
	    			}
    			}
    			 
    			$_item->setCronStatus(3); //3: means rollbacked and closed schedule
    			
    			$_item->save();

    			//release memory
    			unset($cmsBlockmodel);
    			unset($lastRevision);
    		}

    		$this->cacheManagerHelper->flushPageCache();
    	}
    }

    private function getLastVersionOfBlockByIds($blockIds){
    	$collection = $this->blockRevisionFactory->create()->getCollection();
    	$collection->addFieldToSelect('block_id');
    	$collection->addFieldToSelect(new \Zend_Db_Expr('MAX(revision_id) AS revision_id'));
    	$collection->addFieldToFilter('block_id', ['in'=>$blockIds]);
    	$collection->getSelect()->group('block_id');
    	return $collection;
    }

    private function getVersionOfBlockByIds($versionIds){
    	$collection = $this->blockRevisionFactory->create()->getCollection();
    	$collection->addFieldToFilter('revision_id', ['in'=>$versionIds]);
    	return $collection;
    }
}
