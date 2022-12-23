<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model\ResourceModel\PageRevision;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{ 
	/**
     * @var string
     */
    protected $_idFieldName = 'revision_id';
    
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\SixtySeven\CMSScheduler\Model\PageRevision::class, \SixtySeven\CMSScheduler\Model\ResourceModel\PageRevision::class);
    }
}
