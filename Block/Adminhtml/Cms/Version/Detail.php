<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Block\Adminhtml\Cms\Version;

use Magento\Framework\Registry;

class Detail extends \Magento\Backend\Block\Template
{
    /**
     * @var \SixtySeven\CMSScheduler\Model\BlockRevisionFactory
     */
    protected $blockRevisionFactory;

    /**
     * @var \SixtySeven\CMSScheduler\Model\PageRevisionFactory
     */
    protected $pageRevisionFactory;

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
     * @var int
     */
    protected $typeObjectId;

    /**
     * @var Object
     */
    protected $currentObjectData;

    /**
     * @param \Magento\Backend\Block\Template\Context
     * @param \SixtySeven\CMSScheduler\Model\BlockRevisionFactory
     * @param \SixtySeven\CMSScheduler\Model\PageRevisionFactory
     * @param \Magento\Cms\Model\PageFactory
     * @param \Magento\Cms\Model\BlockFactory
     * @param Registry
     * @param array
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \SixtySeven\CMSScheduler\Model\BlockRevisionFactory $blockRevFactory,
        \SixtySeven\CMSScheduler\Model\PageRevisionFactory $pageRevFactory,
        \Magento\Cms\Model\PageFactory $pageModelFactory,
        \Magento\Cms\Model\BlockFactory $blockModelFactory,
        Registry $registry,
        array $data = []
    ) {

        $this->blockRevisionFactory = $blockRevFactory;
        $this->pageRevisionFactory  = $pageRevFactory;
        $this->pageFactory          = $pageModelFactory;
        $this->blockFactory         = $blockModelFactory;
        $this->registry             = $registry;
        parent::__construct($context, $data);
    }

    public function getVersionData()
    {
        return $this->registry->registry('version_data');
    }

    public function getVersionType()
    {
        return $this->registry->registry('version_type');
    }

    protected function getCurrentObjectInfo()
    {
        $type        = $this->getVersionType();
        $versionInfo = $this->getVersionData();
        if ($type == "block") {
            $model              = $this->blockFactory->create();
            $this->typeObjectId = $versionInfo->getBlockId();
        } else if ($type == "page") {
            $model              = $this->pageFactory->create();
            $this->typeObjectId = $versionInfo->getPageId();
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
                'revision_id'=> ['revision_id','text',[]],
                'title'      => ['block_title', 'text', []],
                'identifier' => ['block_identifier', 'text', []],
                'meta_keywords'    => ['meta_keywords', 'text', []],
                'meta_description' => ['meta_description', 'text', []],
                'root_template'    => ['root_template', 'text', []],
                'content'    => ['block_content', 'textarea', []],
                'updated_at' => ['updated_at', 'text', []],
                'is_active'  => ['is_active', 'boolean', [1 => __('No'), 1 => __('Yes')]],
            ],
            'page'  => [
                'page_id'    => ['page_id', 'text', []],
                'title'      => ['page_title', 'text', []],
                'identifier' => ['page_identifier', 'text', []],
                'meta_keywords'    => ['meta_keywords', 'textarea', []],
                'meta_description' => ['meta_description', 'text', []],
                'root_template'    => ['root_template', 'text', []],
                'content'    => ['page_content', 'textarea', []],
                'updated_at' => ['updated_at', 'text', []],
                'is_active'  => ['is_active', 'boolean', [0 => __('No'), 1 => __('Yes')]],
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
            case 'boolean':
                $html = isset($source[$value]) ? $source[$value] : $value;
                break;
            default:
                $html = $value;
                break;
        }
        return $html;
    }

    public function getValueLabel($value){
    	return ucwords(preg_replace("/[_]/",' ',$value));
    }
}
