<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Controller\Adminhtml\Scheduled;

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
    protected $blockScheduleFactory;

    /**
     * @var  PageRevisionFactory
     */
    protected $pageScheduleFactory;

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
        \SixtySeven\CMSScheduler\Model\BlockScheduleFactory $blockScheduleFactory,
        \SixtySeven\CMSScheduler\Model\PageScheduleFactory $pageScheduleFactory
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->_coreRegistry        = $coreRegistry;
        $this->blockScheduleFactory = $blockScheduleFactory;
        $this->pageScheduleFactory  = $pageScheduleFactory;
        return parent::__construct($context);
    }

    /**
     * @return [type] [description]
     */
    public function execute()
    {
        $scheduleId = $this->getRequest()->getParam('id');
        $type  = $this->getRequest()->getParam('type');
       
        //get model loaded and validate
        switch ($type) {
            case 'block':
                $model = $this->blockScheduleFactory->create();
                break;
            case 'page':
                $model = $this->pageScheduleFactory->create();
                break;
            default:
                throw new NotFoundException(__('Schedule not found.'));
                break;
        }

        $schedule = $model->load($scheduleId);

        if (!$schedule->getId()) {
            throw new NotFoundException(__('Schedule not found.'));
        }

        $this->_coreRegistry->register('schedule_data', $schedule);
        $this->_coreRegistry->register('schedule_type', $type);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Review %1 stored schedule', $type));
        return $resultPage;
    }
    
}
