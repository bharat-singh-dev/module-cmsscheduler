<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Helper;

use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Helper\Context;

class CacheManager extends \Magento\Framework\App\Helper\AbstractHelper 
{

	/**
     * @var  Manager
     */
    private $cacheManager;

	/**
     * @param Context
     * @param Manager
     */
    public function __construct(
        Context $context, 
        Manager $cacheManager
    ) {
        $this->cacheManager = $cacheManager; 
        parent::__construct($context);
    }

    public function flushPageCache(){
    	$types = ['config','layout','block_html','full_page','translate','config_webservice'];
    	$this->cacheManager->flush($types);
    }
}