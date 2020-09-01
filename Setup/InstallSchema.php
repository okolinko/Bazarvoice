<?php


namespace Hunters\Bazaarvoice\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (!$installer->tableExists('bazaarvoice_index')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('bazaarvoice_index'))
                ->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ], 'Entity id')
				->addColumn(
					'id',
					Table::TYPE_INTEGER,
					null,
					[
						'nullable' => false,
						'default' => 0
					],
					'id'
				)
				->addColumn(
					'statistic',
					Table::TYPE_TEXT,
					100000000,
					['nullable => false'],
					'Statistic product'
				)
				->addColumn(
					'sku',
					Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Sku Product'
				)
				->addColumn(
					'data',
					Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Data message'
				)
				->addColumn(
					'type_text',
					Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Type text'
				)
				->addColumn(
					'context_data_values',
					Table::TYPE_TEXT,
					100000000,
					['nullable => false'],
					'ContextDataValues'
				)
				->addColumn(
					'text',
					Table::TYPE_TEXT,
					100000000,
					['nullable => false'],
					'Text'
				)
				->addColumn(
					'title_text',
					Table::TYPE_TEXT,
					10000000,
					['nullable => false'],
					'Title'
				)
				->addColumn(
					'name',
					Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Name'
				)
				->addColumn(
					'photo',
					Table::TYPE_TEXT,
					100000000,
					['nullable => false'],
					'photo'
				)
				->addColumn(
					'advice_use',
					Table::TYPE_TEXT,
					1000000,
					['nullable => false'],
					'Advice on use'
				)
				->addColumn(
					'address',
					Table::TYPE_TEXT,
					2550,
					['nullable => false'],
					'Address'
				)
				->addColumn(
					'age',
					Table::TYPE_INTEGER,
					null,
					[
						'default' => 0
					],
					'Age'
				)
				->addColumn(
					'rating',
					Table::TYPE_INTEGER,
					null,
					[
						'default' => 0
					],
					'Rating'
				);

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
