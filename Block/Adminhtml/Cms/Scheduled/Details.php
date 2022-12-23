<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Block\Adminhtml\Cms\Scheduled;

use Magento\Framework\Registry;
use Magento\Cms\Model\Template\FilterProvider;

class Details extends \Magento\Backend\Block\Template
{
    /**
     * @var SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     */
    protected $blockScheduleFactory;

    /**
     * @var \SixtySeven\CMSScheduler\Ui\Component\Listing\Column\CronStatus
     */
    protected $CronStatus;
    
    /**
     * @var \SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     */
    protected $pageScheduleFactory;

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $blockFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Int
     */
    protected $typeObjectId;

    /**
     * @var Object
     */
    protected $currentObjectData;

    /**
     * @var FilterProvider
     */
    protected $filter;

    /**
     * @param \Magento\Backend\Block\Template\Context
     * @param \SixtySeven\CMSScheduler\Model\BlockScheduleFactory
     * @param \SixtySeven\CMSScheduler\Model\PageScheduleFactory
     * @param \Magento\Cms\Model\PageFactory
     * @param \Magento\Cms\Model\BlockFactory
     * @param Registry
     * @param FilterProvider
     * @param array
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \SixtySeven\CMSScheduler\Model\BlockScheduleFactory $blockScheduleFactory,
        \SixtySeven\CMSScheduler\Ui\Component\Listing\Column\CronStatus $CronStatus,
        \SixtySeven\CMSScheduler\Model\PageScheduleFactory $pageScheduleFactory,
        \Magento\Cms\Model\PageFactory $pageModelFactory,
        \Magento\Cms\Model\BlockFactory $blockModelFactory,
        Registry $registry,
        FilterProvider $filter,
        array $data = []
    ) {

        $this->blockScheduleFactory = $blockScheduleFactory;
        $this->pageScheduleFactory  = $pageScheduleFactory;
        $this->pageFactory          = $pageModelFactory;
        $this->blockFactory         = $blockModelFactory;
        $this->CronStatus           = $CronStatus;
        $this->registry             = $registry;
        $this->filter               = $filter;
        parent::__construct($context, $data);
    }

    public function getScheduleData()
    {
        return $this->registry->registry('schedule_data');
    }

    public function getScheduleType()
    {
        return $this->registry->registry('schedule_type');
    }

    protected function getCurrentObjectInfo()
    {
        $type         = $this->getScheduleType();
        $scheduleInfo = $this->getScheduleData();
        if ($type == "block") {
            $model              = $this->blockFactory->create();
            $this->typeObjectId = $scheduleInfo->getBlockId();
        } else if ($type == "page") {
            $model              = $this->pageFactory->create();
            $this->typeObjectId = $scheduleInfo->getPageId();
        }
        $this->currentObjectData = $model->load($this->typeObjectId);
        return $this->currentObjectData;
    }

    public function getTypeObjectId()
    {
        if (!$this->typeObjectId) {
            $this->getCurrentObjectInfo();
        }

        return $this->typeObjectId;
    }

    public function getCurrentObjectData()
    {
        if (!$this->currentObjectData) {
            $this->getCurrentObjectInfo();
        }

        return $this->currentObjectData;
    }

    public function getItemKeysToShow($type)
    {
        $keys = [
            'block' => [
                'block_id'   => ['block_id', 'text', []],
                'title'      => ['block_title', 'text', []],
                'meta_keywords'    => ['meta_keywords', 'text', []],
                'meta_description'    => ['meta_description', 'text', []],
                'root_template'    => ['root_template', 'text', []],
                'identifier' => ['block_identifier', 'text', []],
                'is_active'  => ['is_active', 'boolean', [0 => __('No'), 1 => __('Yes')]],
                'content'    => ['block_content', 'filteredhtml', []],
                'schedule_to'    => ['schedule_to', 'text', []],
                'schedule_from'    => ['schedule_from', 'text', []],
                'cron_status'=> ['cron_status','boolean', $this->CronStatus->toOptionArrayVal()],

            ],
            'page'  => [
                'page_id'    => ['page_id', 'text', []],
                'title'      => ['page_title', 'text', []],
                'meta_keywords'    => ['meta_keywords', 'text', []],
                'meta_description'    => ['meta_description', 'text', []],
                'root_template'    => ['root_template', 'text', []],
                'identifier' => ['page_identifier', 'text', []],
                'is_active'  => ['is_active', 'boolean', [0 => __('No'), 1 => __('Yes')]],
                'content'    => ['page_content', 'textarea', []],
                'schedule_to'    => ['schedule_to', 'text', []],
                'schedule_from'    => ['schedule_from', 'text', []],
                'cron_status'=> ['cron_status','boolean', $this->CronStatus->toOptionArrayVal()],
            ],
        ];
        return ($keys[$type] ? $keys[$type] : []);
    }

    public function renderValuesByType($value, $type, $source = [])
    {
        switch ($type) {
            case 'textarea':
                $html = '<textarea style="width:100%;min-height:150px; height:auto;">' . $value . '</textarea>';
                break;
            case 'filteredhtml':
                $html = $this->filter->getPageFilter()->filter($value);
                break;
            case 'boolean':
                $html = isset($source[$value]) ? $source[$value] : $value;
                break;
            default:
                $html = $value;
                break;
        }
        return $html;
    }

    public function getValueLabel($value)
    {
        return ucwords(preg_replace("/[_]/", ' ', $value));
    }
}
