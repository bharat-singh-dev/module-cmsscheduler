<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model;

use SixtySeven\CMSScheduler\Model\ResourceModel\BlockSchedule as BlockScheduleResource;
use Magento\Framework\Model\AbstractModel;

class BlockSchedule extends AbstractModel
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BlockScheduleResource::class);
    } 
   	
}