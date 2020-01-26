<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Plugin;

class ContactForm {
	protected $_storeManagerInterface;
	protected $_helper;
	public function __construct(
			\Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
			\SY\Contact\Helper\Data $helper
		){
		$this->_storeManagerInterface = $storeManagerInterface;
		$this->_helper = $helper;
	}
	public function beforeToHtml($subject){
		if($this->_helper->getContactConfig(
				'general/active', 
				$this->_storeManagerInterface->getStore()->getId()
			) == "1"){
			$subject->setTemplate('SY_Contact::form.phtml');
		}
	}
}