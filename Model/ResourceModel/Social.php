<?php
namespace Ryker\Brenhouse\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Social extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('ryker_config', 'id');
    }
}