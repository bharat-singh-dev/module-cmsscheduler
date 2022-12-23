<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Block\Adminhtml\System\Config;

class Button extends \Magento\Config\Block\System\Config\Form\Field
{
    
    /**
     * 
     * @param \Magento\Backend\Block\Template\Context $context [description]
     * @param array                                   $data    [description]
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->getButtonHtml();
    }
 
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id'    => 'btnid',
                'label' => __('Force Sync CMS For Revision'),
                'onclick' => "window.location.href='".$this->getUrl('cmsrevision/sync/cms')."'"
            ]
        );

        return $button->toHtml();
    }
}
