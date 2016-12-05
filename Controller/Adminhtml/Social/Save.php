<?php
namespace Ryker\Brenhouse\Controller\Adminhtml\Social;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Forward;
use Ryker\Brenhouse\Model\Social;

class Save extends Action
{
    const FIRST_CONFIG = 1;
    /**
     * @var Social
     */
    protected $_model;

    /**
     * @param Context $context
     * @param Social $model
     */
    public function __construct(
        Context $context,
        Social $model
    ) {
        $this->_model = $model;
        parent::__construct($context);
    }

    /**
     * Forward to edit
     *
     * @return Forward
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $error = false;
        if ($data) {
            $model = $this->_model;
            foreach ($_FILES as $key => $files) {
                $allowed = array('gif', 'png', 'jpg', 'jpeg');
                $filename = $files['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (getimagesize($files["tmp_name"]) !== false && in_array($ext, $allowed)) {
                    $filePath = BP . "/app/design/frontend/ryker/brenhouse/web/images/" . time() . ".jpg";
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    if (!move_uploaded_file($files['tmp_name'], $filePath)) {
                        $error = true;
                        $this->messageManager->addErrorMessage(__("Can't upload image file"));
                    }
                    if ($this->insert($model, $key, $filePath)) {
                        break;
                    }
                }
            }

            foreach($data as $key => $value) {
                if (strpos($key, 'key') === false) {
                    if ($this->insert($model, $key, $value)) {
                        break;
                    }
                }
            }

            if ($error) {
                $this->messageManager->addErrorMessage(__("Smth went wrong"));
            } else {
                $this->messageManager->addSuccessMessage(__("Saved successfully"));
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');

    }

    private function insert($model, $key, $value) {
        $keyAndId = explode('-', $key);
        $key = $keyAndId[0];
        $id = intval($keyAndId[1]);
        $model = $model->load($id);

        $dataInsert = array(
            'key' => $key,
            'value' => $value
        );
        if ($model->hasData()) {
            $model->addData($dataInsert);
        } else {
            $model->setData($dataInsert);
        }

        $error = false;
        try {
            $model->save();
        } catch (\Exception $e) {
            $error = true;
        }
        return $error;
    }
}
