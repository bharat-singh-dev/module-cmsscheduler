<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Controller\Adminhtml\Sync;

use Magento\Backend\App\Action\Context;

class Cms extends \Magento\Backend\App\Action
{

    /**
     *@var \Magento\Cms\Model\BlockFactory
     */
    private $blockModelFactory;

    /**
     *@var \Magento\Cms\Model\PageFactory
     */
    private $pageModelFactory;

    /**
     *@var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * 
     * @param Context                         $context           [description]
     * @param \Magento\Cms\Model\BlockFactory $blockModelFactory [description]
     * @param \Magento\Cms\Model\PageFactory  $pageModelFactory  [description]
     * @param \Magento\Framework\Registry     $coreRegistry      [description]
     */
    public function __construct(
        Context $context,
        \Magento\Cms\Model\BlockFactory $blockModelFactory,
        \Magento\Cms\Model\PageFactory $pageModelFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->blockModelFactory = $blockModelFactory;
        $this->pageModelFactory  = $pageModelFactory;
        $this->coreRegistry  = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Customer login form page
     * @return json
     */
    public function execute()
    {
        $this->coreRegistry->register('sycn_force_vcs',1);
        try{
            $this->syncCmsBlocks();
            $this->syncCmsPages();
            $this->messageManager->addSuccess(__('Force sync cms has applied, please check revisions in respective cms block and pages.'));
        } catch (\Exception $e){
            $this->messageManager->addError($e->getMessage());
        }
        
        return $this->resultRedirectFactory->create()->setPath('adminhtml/system_config/edit/', ['_secure' => true, 'section' => 'cms']);
    }

    private function syncCmsBlocks(){
        $collection  = $this->blockModelFactory->create()->getCollection();
        $collection->load();
        $collection->walk('save');
    }

    private function syncCmsPages(){
        $collection  = $this->pageModelFactory->create()->getCollection();
        $collection->load();
        $collection->walk('save');
    }
}
