<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Controller\Adminhtml\Scheduled;

use Magento\Framework\Exception\NotFoundException;

class Blocks extends \Magento\Framework\App\Action\Action
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
     * @var  CustomerFactory
     */
    protected $blockScheduleFactory;

    /**
     * @param \Magento\Framework\App\Action\Context              $context              
     * @param \Magento\Framework\View\Result\PageFactory         $resultPageFactory    
     * @param \Magento\Framework\Registry                        $coreRegistry         
     * @param \SixtySeven\CMSScheduler\Model\BlockRevisionFactory $blockRevisionFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \SixtySeven\CMSScheduler\Model\BlockScheduleFactory $blockScheduleFactory
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->_coreRegistry        = $coreRegistry;
        $this->blockScheduleFactory = $blockScheduleFactory;
        return parent::__construct($context);
    }

    /**
     * 
     * @return [type] [description]
     */
    public function execute()
    {   
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Scheduled Blocks List'));
        return $resultPage;
    }
}
