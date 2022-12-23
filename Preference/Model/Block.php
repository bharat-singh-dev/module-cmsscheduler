<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Preference\Model;

class Block extends \Magento\Cms\Model\Block {

    public function getEventManager(){
        return $this->_eventManager;
    }
}