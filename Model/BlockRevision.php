<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model;

use SixtySeven\CMSScheduler\Model\ResourceModel\BlockRevision as BlockRevisionResource;
use Magento\Framework\Model\AbstractModel;

class BlockRevision extends AbstractModel
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BlockRevisionResource::class);
    } 
   	
}