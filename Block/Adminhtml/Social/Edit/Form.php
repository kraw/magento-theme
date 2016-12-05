<?php
namespace Ryker\Brenhouse\Block\Adminhtml\Social\Edit;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

class Form extends Generic
{

    protected $_socialFactory;
    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @param Context                    $context
     * @param Registry                   $registry
     * @param FormFactory                $formFactory
     * @param Store $systemStore
     * @param \Ryker\Brenhouse\Model\SocialFactory $factory,
     * @param array                      $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        \Ryker\Brenhouse\Model\SocialFactory $factory,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_socialFactory= $factory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /**
         * Checking if user have permission to save information
         */
        if($this->_isAllowedAction('Ryker_Brenhouse::social')){
            $isElementDisabled = false;
        }else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
                [
                    'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                    ]
                ]
            );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Social Network')]);

        $data = $this->_socialFactory->create();

        foreach($data->getCollection()->getItems() as $config) {
            $type = 'text';
            if (substr($config->getData('key'), 0, 4) == 'img_') {
                $type = 'file';
            }
            $fieldset->addField(
              $config->getData('key'),
              $type,
              [
                'label' => __($config->getData('title')),
                'title' => __($config->getData('title')),
                'name' => $config->getData('key') . '-' . $config->getData('id'),
                'disabled' => $isElementDisabled,
                'value' => $config->getData('value')
              ]
            );
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}