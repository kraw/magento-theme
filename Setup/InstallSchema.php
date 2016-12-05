<?php

namespace Ryker\Brenhouse\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface {

    public function install( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'posts'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable( 'ryker_config' )
        )->addColumn(
            'id',
            Table::TYPE_SMALLINT,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true ],
            'ID'
        )->addColumn(
          'title',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Title'
        )->addColumn(
            'key',
            Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, 'unique' => true ],
            'Key'
        )->addColumn(
            'value',
            Table::TYPE_TEXT,
            255,
            [ 'nullable' => true ],
            'Value'
        );

        $installer->getConnection()->createTable( $table );

        $installer->endSetup();
    }
}