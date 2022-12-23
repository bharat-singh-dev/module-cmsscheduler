<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class PageRevision  extends AbstractDb
{
    /**
     * @return \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     */
    protected $dateFactory;

    /**
     * @param Context
     * @param \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     * @param string
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory,
        string $connectionName = null
    ) {
        $this->dateFactory = $dateFactory; 
        parent::__construct($context, $connectionName);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cmspage_revisions', 'revision_id');
    } 

    /**
     * Perform actions before object save
     *
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $dateTime = $this->dateFactory->create();
        if($object->isObjectNew()){
            $object->setCreatedAt($dateTime->date());
        }
        $object->setUpdatedAt($dateTime->date());
        return parent::_beforeSave($object);
    }
}