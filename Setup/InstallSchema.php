<?php
/**
 * @category  CMSScheduler
 * @package   SixtySeven_CMSScheduler
 * @author    Bharat
 */
namespace SixtySeven\CMSScheduler\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();
        // Get tutorial_simplenews table
        $cmspage_revisions = $installer->getTable('cmspage_revisions');

        $cmsblock_revisions = $installer->getTable('cmsblock_revisions');

        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($cmspage_revisions) != true) {

            $table = $installer->getConnection()->newTable(
                $cmspage_revisions
            )->addColumn(
                'revision_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Revision Id'
            )->addColumn(
                'page_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Page ID'
            )->addColumn(
                'restored_from_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Restored From ID'
            )->addColumn(
                'page_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Page Title'
            )->addColumn(
                'root_template',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Root Template'
            )->addColumn(
                'meta_keywords',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Meta Keywords'
            )->addColumn(
                'meta_description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Meta Description'
            )->addColumn(
                'page_identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Page Identifier'
            )->addColumn(
                'page_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Page Content'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Is Active'
            )->addColumn(
                'store_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Store Ids Json'
            )->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false],
                'User Id'
            )->addColumn(
                'schedule_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Revision Schedule Date From'
            )->addColumn(
                'schedule_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Revision Schedule Date To'
            )->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Sort Order'
            )->addColumn(
                'layout_update_xml',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Layout Update Xml'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created Date'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Updated Date'
            )->setComment(
                'SixtySeven CMSPage Revision Taple'
            );
            

            $installer->getConnection()->createTable($table);

        }

        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($cmsblock_revisions) != true) {

            $table = $installer->getConnection()->newTable(
                $cmsblock_revisions
            )->addColumn(
                'revision_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Revision Id'
            )->addColumn(
                'block_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Block ID'
            )->addColumn(
                'restored_from_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Restored From ID'
            )->addColumn(
                'block_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'CMS Block Title'
            )->addColumn(
                'block_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Block Content'
            )->addColumn(
                'block_identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'CMS Block Identifier'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Is Active'
            )->addColumn(
                'store_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Store Ids Json'
            )->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false],
                'User Id'
            )->addColumn(
                'schedule_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Revision Schedule Date From'
            )->addColumn(
                'schedule_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Revision Schedule Date To'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created Date'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Updated Date'
            )->setComment(
                'SixtySeven CMSBLOCK Revision Taple'
            );

            $installer->getConnection()->createTable($table);
        }

        $cmspage_schedule = $installer->getTable('cmspage_schedule');

        $cmsblock_schedule = $installer->getTable('cmsblock_schedule');

        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($cmspage_schedule) != true) {

            $table = $installer->getConnection()->newTable(
                $cmspage_schedule
            )->addColumn(
                'schedule_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Schedule Id'
            )->addColumn(
                'page_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Page ID'
            )->addColumn(
                'rollback_version_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false],
                'rollback_version_id'
            )->addColumn(
                'page_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Page Title'
            )->addColumn(
                'root_template',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Root Template'
            )->addColumn(
                'meta_keywords',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Meta Keywords'
            )->addColumn(
                'meta_description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Meta Description'
            )->addColumn(
                'page_identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'Page Identifier'
            )->addColumn(
                'page_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Page Content'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Is Active'
            )->addColumn(
                'store_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Store Ids Json'
            )->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false],
                'User Id'
            )->addColumn(
                'schedule_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Revision Schedule Date From'
            )->addColumn(
                'schedule_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Revision Schedule Date To'
            )->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Sort Order'
            )->addColumn(
                'layout_update_xml',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Layout Update Xml'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created Date'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Updated Date'
            )->setComment(
                'SixtySeven CMSPage Schedule Taple'
            );
            $installer->getConnection()->createTable($table);

        }

        if ($installer->getConnection()->isTableExists($cmsblock_schedule) != true) {

            $table = $installer->getConnection()->newTable(
                $cmsblock_schedule
            )->addColumn(
                'schedule_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Schedule Id'
            )->addColumn(
                'block_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                6,
                ['nullable' => false],
                'Block ID'
            )->addColumn(
                'rollback_version_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false],
                'rollback_version_id'
            )->addColumn(
                'block_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'CMS Block Title'
            )->addColumn(
                'block_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Block Content'
            )->addColumn(
                'block_identifier',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                256,
                ['nullable' => false],
                'CMS Block Identifier'
            )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Is Active'
            )->addColumn(
                'store_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Store Ids Json'
            )->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false],
                'User Id'
            )->addColumn(
                'schedule_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Revision Schedule Date From'
            )->addColumn(
                'schedule_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Revision Schedule Date To'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created Date'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Updated Date'
            )->setComment(
                'SixtySeven CMSBLOCK Revision Taple'
            );

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
