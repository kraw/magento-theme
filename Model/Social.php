<?php
namespace Ryker\Brenhouse\Model;

use \Magento\Framework\Model\AbstractModel;

class Social extends AbstractModel
{
    const CACHE_TAG = 'ryker_config';

    const SOCIAL_ID = 'id';

    protected $_key = '';

    protected $_value = '';

    protected $_idFieldName = self::SOCIAL_ID; // parent value is 'id'

    protected function _construct()
    {
        $this->_init('Ryker\Brenhouse\Model\ResourceModel\Social');
    }
}