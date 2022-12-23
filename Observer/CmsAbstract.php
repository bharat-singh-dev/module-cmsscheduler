<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Observer;

use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

//use Magento\Framework\App\RequestInterface;

abstract class CmsAbstract implements ObserverInterface
{


    /**
     * @var string
     */
    const MODIFIED = 'Modified';
    
    /**
     * @var string
     */
    const CREATED = 'Created';
    
    /**
     * @var string
     */
    const RESTORED_FROM_REVISION = 'Restored from revision';

    /**
     * @var \SixtySeven\CMSScheduler\Model\BlockRevisionFactory
     */
    protected $blockRevisionFactory;
    /**
     * @var \SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     */
    protected $blockScheduleFactory;
    /**
     * @var \SixtySeven\CMSScheduler\Model\PageScheduleFactory
     */
    protected $pageScheduleFactory;
    /**
     * @var \SixtySeven\CMSScheduler\Model\PageRevisionFactory
     */
    protected $pageRevisionFactory;
    /**
     * @var AuthSession
     */
    protected $authSession;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param \SixtySeven\CMSScheduler\Model\BlockRevisionFactory
     * @param \SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     * @param \SixtySeven\CMSScheduler\Model\PageScheduleFactory
     * @param \SixtySeven\CMSScheduler\Model\PageRevisionFactory
     * @param AuthSession
     * @param ManagerInterface
     * @param LoggerInterface
     * @param Registry
     */
    public function __construct(
        \SixtySeven\CMSScheduler\Model\BlockRevisionFactory $blockRevisionFactory,
        \SixtySeven\CMSScheduler\Model\BlockScheduleFactory $blockScheduleFactory,
        \SixtySeven\CMSScheduler\Model\PageScheduleFactory $pageScheduleFactory,
        \SixtySeven\CMSScheduler\Model\PageRevisionFactory $pageRevisionFactory,
        AuthSession $authSession,
        ManagerInterface $messageManager,
        LoggerInterface $logger,
        Registry $coreRegistry
    ) {
        $this->blockRevisionFactory = $blockRevisionFactory;
        $this->blockScheduleFactory = $blockScheduleFactory;
        $this->pageScheduleFactory = $pageScheduleFactory;
        $this->pageRevisionFactory  = $pageRevisionFactory;
        $this->authSession          = $authSession;
        $this->messageManager       = $messageManager;
        $this->logger               = $logger;
        $this->coreRegistry         = $coreRegistry;
    }

    protected function getAdminUser()
    {
        return $this->authSession->getUser();
    }

    protected function logError($exception)
    {
        $this->logger->log($exception->__toString(), 1);
    }

    protected function canSaveRevision($cmsData)
    {
        /* if (!$this->getAdminUser()->getId()) {
            return false;
        } */

        
        $canVersion = $cmsData->getCanVersion();
        if ($canVersion != 1) {
            return false;
        }

        if (!$this->hasDataChanged($cmsData)) {
            return false;
        }

        return true;
    }

    abstract protected function hasDataChanged($object);

}
