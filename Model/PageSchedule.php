<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model;

use SixtySeven\CMSScheduler\Model\ResourceModel\PageSchedule as PageScheduleResource;
use Magento\Framework\Model\AbstractModel;

class PageSchedule extends AbstractModel
{

    /**
     * @return [type]
     */
    protected function _construct()
    {
        $this->_init(PageScheduleResource::class);
    } 
   	
}