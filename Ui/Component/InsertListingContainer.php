<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Ui\Component;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponent\DataSourceInterface;
use Magento\Framework\View\Element\UiComponent\ObserverInterface;
use Magento\Framework\Data\ValueSourceInterface;


class InsertListingContainer extends \Magento\Ui\Component\AbstractComponent
{
    const NAME = 'container';

    /**
     * Get component name
     *
     * @return string
     */
    public function getComponentName()
    {
        $type = $this->getData('type');
        return static::NAME . ($type ? ('.' . $type): '');
    }

    /**
     * @return void
     */
    protected function prepareFilterUrlParams(UiComponentInterface $component)
    {
    
        $config = $component->getData('config');
        if (!isset($config['filter_url_params'])) {
            return;
        }
        foreach ($config['filter_url_params'] as $paramName => $paramValue) {
            if ('*' == $paramValue) {
                $paramValue = $this->getContext()->getRequestParam($paramName);
            }
             
            if ($paramValue) {
                $config['update_url'] = $this->getContext()->getUrl(sprintf(
                    '%s%s/%s/',
                    $config['update_url'],
                    $paramName,
                    $paramValue
                ));

                $config['render_url'] = $this->getContext()->getUrl(sprintf(
                    '%s%s/%s/',
                    $config['render_url'],
                    $paramName,
                    $paramValue
                ));
            }
        }
        $component->setData('config', (array)$config);
    }

    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        $config = $this->getData('config');
        if (isset($config['value']) && $config['value'] instanceof ValueSourceInterface) {
            $config['value'] = $config['value']->getValue($this->getName());
        }
        $this->setData('config', (array)$config);
        $this->prepareFilterUrlParams($this); 
        
        $jsConfig = $this->getJsConfig($this);
        if (isset($jsConfig['provider'])) {
            unset($jsConfig['extends']);
            $this->getContext()->addComponentDefinition($this->getName(), $jsConfig);
        } else {
            $this->getContext()->addComponentDefinition($this->getComponentName(), $jsConfig);
        }

        if ($this->hasData('actions')) {
            $this->getContext()->addActions($this->getData('actions'), $this);
        }

        if ($this->hasData('html_blocks')) {
            $this->getContext()->addHtmlBlocks($this->getData('html_blocks'), $this);
        }

        if ($this->hasData('buttons')) {
            $this->getContext()->addButtons($this->getData('buttons'), $this);
        }
        
        $this->context->getProcessor()->register($this);
        $this->getContext()->getProcessor()->notify($this->getComponentName());
    }
}
