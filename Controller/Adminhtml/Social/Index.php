<?php
namespace Ryker\Brenhouse\Controller\Adminhtml\Social;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Ryker\Brenhouse\Model\Social;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Ryker_Brenhouse::social';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Social
     */
    protected $_model;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ryker_Brenhouse::social')
            ->addBreadcrumb(__('Ryker Setup'), __('Ryker Setup'))
            ->addBreadcrumb(__('Social Network'), __('Social Network'));
        $resultPage->getConfig()->getTitle()->prepend(__('Social Network'));
        return $resultPage;
    }
}
