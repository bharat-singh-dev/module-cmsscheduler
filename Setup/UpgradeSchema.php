<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Get tutorial_simplenews table

        //handle all possible upgrade versions


         if (version_compare($context->getVersion(), '1.0.7') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable('cmsblock_revisions'),
                'status_comment',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'   => '255',
                    'default'  => '1',
                    'nullable' => false,
                    'comment'  => 'Status Comment',
                ]
            );

              $setup->getConnection()->addColumn(
                $setup->getTable('cmspage_revisions'),
                'status_comment',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length'   => '255',
                    'default'  => '1',
                    'nullable' => false,
                    'comment'  => 'Status Comment',
                ]
            );

        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable('cmsblock_schedule'),
                'cron_status',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length'   => '6',
                    'default'  => '1',
                    'nullable' => false,
                    'comment'  => 'Cron Status',
                ]
            );

              $setup->getConnection()->addColumn(
                $setup->getTable('cmspage_schedule'),
                'cron_status',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length'   => '6',
                    'default'  => '1',
                    'nullable' => false,
                    'comment'  => 'Cron Status',
                ]
            );

        }


        if (version_compare($context->getVersion(), '1.0.3') < 0) {

            $setup->getConnection()->addColumn(
                $setup->getTable('cms_page'),
                'can_version',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length'   => '6',
                    'default'  => '1',
                    'nullable' => false,
                    'comment'  => 'CMS Page Version',
                ]
            );

        }

        if (version_compare($context->getVersion(), '1.0.4') < 0) {

            $setup->getConnection()->addColumn(
                $setup->getTable('cms_block'),
                'can_version',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length'   => '6',
                    'default'  => '1',
                    'nullable' => false,
                    'comment'  => 'CMS Page Version',
                ]
            );

            $installer->endSetup();

        }
        

    }

}
