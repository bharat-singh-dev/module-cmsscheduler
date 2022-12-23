<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Cron;

use \Psr\Log\LoggerInterface;

abstract class CronSchedulerAbstract
{
    /**
     * @var  LoggerInterface
     */
    protected $logger;
    /**
     * @var  \SixtySeven\CMSScheduler\Helper\CacheManager
     */
    protected $cacheManagerHelper;
    /**
     * @var  \Magento\Framework\Registry
     */
    protected $coreRegistry;

    public function __construct(
        LoggerInterface $logger,
        \SixtySeven\CMSScheduler\Helper\CacheManager $cacheManagerHelper,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->logger             = $logger;
        $this->cacheManagerHelper = $cacheManagerHelper;
        $this->coreRegistry      = $coreRegistry;
    }
}
