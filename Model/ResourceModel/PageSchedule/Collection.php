<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model\ResourceModel\PageSchedule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{ 
	/**
     * @var string
     */
    protected $_idFieldName = 'schedule_id';
    
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SixtySeven\CMSScheduler\Model\PageSchedule::class, \SixtySeven\CMSScheduler\Model\ResourceModel\PageSchedule::class);
    }
}
