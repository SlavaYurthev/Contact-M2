<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface {
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$setup->startSetup();

		$table = $setup->getConnection()->newTable($setup->getTable('sy_contact'))
			->addColumn(
				'id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				[
					'identity' => true, 
					'unsigned' => true, 
					'nullable' => false, 
					'primary' => true
				],
				'Id'
			)->addColumn(
				'info',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				null,
				[
					'nullable' => true
				],
				'Info'
			)->addColumn(
				'closed',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				1,
				[
					'nullable' => false,
					'default' => '0'
				],
				'Closed'
			)->addColumn(
				'created',
				\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
				null,
				[
					'nullable' => false,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
				],
				'Created'
			)->addColumn(
				'updated',
				\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
				null,
				[
					'nullable' => false,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
				],
				'Updated'
			)->setComment(
				'Contact Table'
			);
		$setup->getConnection()->createTable($table);

		$setup->endSetup();
	}
}