<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Controller\Form;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory; 

class Post extends Action {
	public function execute() {
		$validator = $this->_objectManager->get('Magento\Framework\Data\Form\FormKey\Validator');
		if ($validator->validate($this->getRequest())) {
			$helper = $this->_objectManager->get('SY\Contact\Helper\Data');
			$json = $this->_objectManager->get('Magento\Framework\Serialize\Serializer\Json');
			$store = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
			$fields = $helper->getConfig('general/fields', $store->getId());
			$fields = $json->unserialize($fields);
			$info = [];
			if(count($fields)>0){
				foreach ($fields as $field) {
					try {
						if($this->getRequest()->getParam($field['key'])){
							$info[] = [
								'key' => $field['key'],
								'label' => $field['label'],
								'type' => $field['field_type'],
								'value' => $this->getRequest()->getParam($field['key'])
							];
						}
					} catch (\Exception $e) {}
				}
			}
			if(count($info)>0){
				$model = $this->_objectManager->get('SY\Contact\Model\Request');
				$model->setData('info', $json->serialize($info));
				//used to support hidden fields
                $model->setData('originalParams', $this->getRequest()->getParams());
				try {
					$model->save();
					if($model->getId()){
						$email = $this->_objectManager->get('SY\Contact\Helper\Email');
						$email->recive($model, $store->getId());
						$messageManager = $this->_objectManager->get('Magento\Framework\Message\ManagerInterface');
						$messageManager->addSuccess(__('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.'));
					}
				} catch (\Exception $e) {}
			}
		}
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$resultRedirect->setUrl($this->_redirect->getRefererUrl());
		return $resultRedirect; 
	}
}