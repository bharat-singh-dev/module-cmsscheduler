<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Controller\Adminhtml\Scheduled;

class Delete extends \Magento\Framework\App\Action\Action
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
     * @param \Magento\Framework\App\Action\Context
     * @param \Magento\Framework\View\Result\PageFactory
     * @param \Magento\Framework\Registry
     * @param \SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     * @param \SixtySeven\CMSScheduler\Model\PageScheduleFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
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
     * 
     * @return [type] [description]
     */
    public function execute()
    {

        $scheduleId = $this->getRequest()->getParam('id');
        $type       = $this->getRequest()->getParam('type');
        
        //get model loaded and validate
        try {
            switch ($type) {
                case 'block':
                    $model = $this->blockScheduleFactory->create();
                    break;
                case 'page':
                    $model = $this->pageScheduleFactory->create();
                    break;
                default:
                    throw new \Exception(__('Shedule not found.'));
                    break;
            }

            $schedule = $model->load($scheduleId);

            if (!$schedule->getId()) {
                throw new \Exception(__('Shedule not found.'));
            }

            $schedule->delete();
            $this->messageManager->addSuccess(__('Schedule has been deleted.'));
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_url->getUrl('cmsrevision/scheduled/' . $type . 's', ['_secure' => true]));

        return $resultRedirect;
    }
}
