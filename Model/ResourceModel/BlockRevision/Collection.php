<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model\ResourceModel\BlockRevision;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
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
        $this->_init(\SixtySeven\CMSScheduler\Model\BlockRevision::class, \SixtySeven\CMSScheduler\Model\ResourceModel\BlockRevision::class);
    }
}
