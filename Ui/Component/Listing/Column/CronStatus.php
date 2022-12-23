<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Ui\Component\Listing\Column;

class CronStatus implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var String
     */
    const STATUS_PENDING = 1;
    
    /**
     * @var String
     */
    const STATUS_RUNNING = 2;
    
    /**
     * @var String
     */
    const STATUS_CLOSED  = 3;

    public function toOptionArray()
    {
        return [
            [
                'value' => self::STATUS_PENDING,
                'label' => __('Pending/New'),
            ],
            [
                'value' => self::STATUS_RUNNING,
                'label' => __('Running/Applied'),
            ],
            [
                'value' => self::STATUS_CLOSED,
                'label' => __('Closed/RollBacked'),
            ],

        ];
    }

     public function toOptionArrayVal()
    {
        $return = [];
        $opts = $this->toOptionArray();
        foreach($opts as $opt){
            $return[$opt['value']] = $opt['label']; 
        } 
        return $return;
    }
}
