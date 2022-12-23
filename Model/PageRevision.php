<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Model;
 
use SixtySeven\CMSScheduler\Model\ResourceModel\PageRevision as PageRevisionResource;
use Magento\Framework\Model\AbstractModel;

class PageRevision extends AbstractModel
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(PageRevisionResource::class);
    } 
   	
}