<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Controller\Adminhtml\Revision;

use Magento\Framework\Exception\NotFoundException;

class Details extends \Magento\Backend\App\Action
{
    /**
     * @var  Registry
     */
    protected $_coreRegistry;

    /**
     * @var  PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var  BlockRevisionFactory
     */
    protected $blockRevisionFactory;

    /**
     * @var  PageRevisionFactory
     */
    protected $pageRevisionFactory;

    /**
     * @param \Magento\Framework\App\Action\Context              $context
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory
     * @param \Magento\Framework\Registry                        $coreRegistry
     * @param \SixtySeven\CMSScheduler\Model\BlockRevisionFactory $blockRevisionFactory
     * @param \SixtySeven\CMSScheduler\Model\PageRevisionFactory $pageRevisionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \SixtySeven\CMSScheduler\Model\BlockRevisionFactory $blockRevisionFactory,
        \SixtySeven\CMSScheduler\Model\PageRevisionFactory $pageRevisionFactory
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->_coreRegistry        = $coreRegistry;
        $this->blockRevisionFactory = $blockRevisionFactory;
        $this->pageRevisionFactory  = $pageRevisionFactory;
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

        $this->_coreRegistry->register('version_data', $revision);
        $this->_coreRegistry->register('version_type', $type);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Review %1 stored version', $type));
        return $resultPage;
    }
    
}
