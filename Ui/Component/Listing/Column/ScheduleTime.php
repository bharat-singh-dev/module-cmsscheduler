<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Ui\Component\Listing\Column;

class ScheduleTime implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
    	$array = [];
    	for($i=0; $i<24; $i++){

    		$hour=sprintf("%02d", $i);
    		$array[] = [
                'value' => $hour.':00:00',
                'label' => $hour.':00:00'
            ];
            $array[] = [
                'value' => $hour.':30:00',
                'label' => $hour.':30:00'
            ];

    	}
        return $array;
    }
}
