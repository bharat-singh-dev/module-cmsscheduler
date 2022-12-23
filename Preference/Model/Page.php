<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Preference\Model;

class Page extends \Magento\Cms\Model\Page {

    public function getEventManager(){
        return $this->_eventManager;
    }
}