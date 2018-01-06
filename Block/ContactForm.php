<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Block;

class ContactForm extends \Magento\Contact\Block\ContactForm {
	private $_fields;
	public function getFields(){
		if(!$this->_fields){
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$helper = $objectManager->get('SY\Contact\Helper\Data');
			$json = $objectManager->get('Magento\Framework\Serialize\Serializer\Json');
			$fields = $helper->getConfig(
				'general/fields', 
				$this->_storeManager->getStore()->getId()
			);
			$fields = $json->unserialize($fields);
			if(count($fields)>0){
				foreach ($fields as $key => $field) {
					$object = new \Magento\Framework\DataObject;
					$object->addData($field);
					$fields[$key] = $object;
				}
			}
			$this->_fields = $fields;
		}
		return $this->_fields;
	}
	public function getFormKey(){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$formKey = $objectManager->get('Magento\Framework\Data\Form\FormKey'); 
		return $formKey->getFormKey();
	}
}