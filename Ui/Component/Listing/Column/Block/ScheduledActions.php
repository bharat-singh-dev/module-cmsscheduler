<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Ui\Component\Listing\Column\Block;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;


class ScheduledActions extends Column
{

    /**
     * @var String
     */
    const URL_PATH_APPLY  = 'cmsrevision/scheduled/delete';
    
    /**
     * @var String
     */
    const URL_PATH_DETAILS = 'cmsrevision/scheduled/details';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['block_id'])) {
                    $dateTitle = '(#'.$this->getEscaper()->escapeHtml($item['schedule_id']);
                    $dateTitle .= ' '.$this->getEscaper()->escapeHtml($item['created_at']).' )';
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href'  => $this->urlBuilder->getUrl(
                                static::URL_PATH_DETAILS,
                                [
                                    'id'   => $item['schedule_id'],
                                    'type' => 'block',
                                ]
                            ),
                            'target'=>'_blank',
                            'label' => __('View'),
                        ],
                        'apply'=> [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_APPLY,
                                [
                                    'id' => $item['schedule_id'],
                                    'type' => 'block',
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1', $dateTitle),
                                'message' => __('Are you sure you want to delete this %1 schedule ?', $dateTitle)
                            ]
                        ]
                    ];

                }
            }
        }

        return $dataSource;
    }

    /**
     * Get instance of escaper
     * @copyfrom \Magento\Cms\Ui\Component\Listing\Column\BlockActions
     * @return Escaper
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}
