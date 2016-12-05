<?php

namespace Ryker\Brenhouse\Model\ResourceModel\Social;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
  /**
   * Define model & resource model
   */
  protected function _construct()
  {
    $this->_init(
      'Ryker\Brenhouse\Model\Social',
      'Ryker\Brenhouse\Model\ResourceModel\Social'
    );
  }
}