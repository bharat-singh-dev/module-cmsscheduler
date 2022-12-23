<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Observer;

use Magento\Framework\Event\Observer;

use Magento\Framework\Event\ObserverInterface;

class LayoutLoadBefore implements ObserverInterface
{	
	private $request;
	private $pageConfig;

	public function __construct(
		\Magento\Framework\App\RequestInterface $request,
		\Magento\Framework\View\Page\Config $pageConfig
	){
		$this->request = $request;
		$this->pageConfig = $pageConfig;
	}
    public function execute(Observer $observer)
    {
    	$forwardedArray = $this->request->getBeforeForwardInfo();

    	$fullActionName = $observer->getEvent()->getFullActionName();

        if($fullActionName=="cms_page_edit" || $fullActionName=="cms_block_edit" ){
        	if(!empty($forwardedArray)){
				$fullaction = $forwardedArray['route_name'].'_'.$forwardedArray['controller_name'].'_'.$forwardedArray['action_name'];
				if($fullaction=='cms_page_new'){
					$this->pageConfig->addBodyClass('cms_page_new');
				}

				if($fullaction=='cms_block_new'){
					$this->pageConfig->addBodyClass('cms_block_new');
				}
			}
        }
		return $this;
    }

}
