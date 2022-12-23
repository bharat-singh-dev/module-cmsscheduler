<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Controller\Adminhtml\Revision;

use Magento\Framework\Exception\NotFoundException;

class Apply extends \Magento\Backend\App\Action
{
    /**
     * @var  Registry
     */
    protected $_coreRegistry;

    /**
     * @var  ResultFactory
     */
    protected $resultFactory;

    /**
     * @var  BlockRevisionFactory
     */
    protected $blockRevisionFactory;

    /**
     * @var  PageRevisionFactory
     */
    protected $pageRevisionFactory;

    /**
     * @var  PageFactory
     */
    protected $pageFactory;

    /**
     * @var  BlockFactory
     */
    protected $blockFactory;

    /**
     * @var  CacheManager
     */
    protected $cacheManagerHelper;

    /**
     * @param \Magento\Backend\App\Action\Context
     * @param \Magento\Framework\Controller\ResultFactory
     * @param \Magento\Framework\Registry
     * @param \SixtySeven\CMSScheduler\Model\BlockRevisionFactory
     * @param \SixtySeven\CMSScheduler\Model\PageRevisionFactory
     * @param \Magento\Cms\Model\PageFactory
     * @param \Magento\Cms\Model\BlockFactory
     * @param \SixtySeven\CMSScheduler\Helper\CacheManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Framework\Registry $coreRegistry,
        \SixtySeven\CMSScheduler\Model\BlockRevisionFactory $blockRevisionFactory,
        \SixtySeven\CMSScheduler\Model\PageRevisionFactory $pageRevisionFactory,
        \Magento\Cms\Model\PageFactory $pageModelFactory,
        \Magento\Cms\Model\BlockFactory $blockModelFactory,
        \SixtySeven\CMSScheduler\Helper\CacheManager $cacheManagerHelper
    ) {
        $this->resultFactory    = $resultFactory;
        $this->_coreRegistry        = $coreRegistry;
        $this->blockRevisionFactory = $blockRevisionFactory;
        $this->pageRevisionFactory  = $pageRevisionFactory;
        $this->pageFactory          = $pageModelFactory;
        $this->blockFactory         = $blockModelFactory;
        $this->cacheManagerHelper   = $cacheManagerHelper;
        return parent::__construct($context);
    }

    /**
     * @return [type] [description]
     */
    public function execute()
    {
        $revId = $this->getRequest()->getParam('id');
        $type  = $this->getRequest()->getParam('type');
        //get model loaded and validate
        switch ($type) {
            case 'block':
                $model = $this->blockRevisionFactory->create();
                break;
            case 'page':
                $model = $this->pageRevisionFactory->create();
                break;
            default:
                throw new NotFoundException(__('Version not found.'));
                break;
        }

        $revision = $model->load($revId);
        if (!$revision->getId()) {
            throw new NotFoundException(__('Version not found.'));
        }
        $objectId = '';
        
        $this->_coreRegistry->register('restroring_vcs_from', $revision);

        if('block'==$type){
            $cmsBlock = $this->blockFactory->create();
            $cmsBlock->load($revision->getBlockId());
            $objectId = $revision->getBlockId();
            //restoring content only for now
            $cmsBlock->setContent($revision->getBlockContent());
            $cmsBlock->setTitle($revision->getBlockTitle());

            $cmsBlock->save();
        } elseif('page'==$type) {
            $cmsPage = $this->pageFactory->create();
            $cmsPage->load($revision->getPageId());
            $objectId = $revision->getPageId();
            //restoring content only for now
            $cmsPage->setContent($revision->getPageContent());
            $cmsPage->setTitle($revision->getPageTitle());

            $cmsPage->save();
        }
        
        $this->cacheManagerHelper->flushPageCache();

        $this->messageManager->addSuccess(__('Content has been restored successfully'));
        $this->messageManager->addSuccess(__('Cache has been flushed'));
        
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_url->getUrl('cms/'.$type.'/edit',['_secure'=>true,$type.'_id'=>$objectId]));

        return $resultRedirect;         
    }

    public function getItemKeysToShow($object)
    {
        return array_keys($object->getData());
    }
}
