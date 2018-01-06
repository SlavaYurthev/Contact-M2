<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Helper;

use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\ObjectManagerInterface;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\ScopeInterface;

class Email extends \SY\Contact\Helper\Data {
	const EMAIL_TYPE = 'email';
	protected $_json;
	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		\Magento\Framework\Serialize\Serializer\Json $json
	){
		$this->_json = $json;
		parent::__construct($context);
	}
	public function recive(\SY\Contact\Model\Request $request, $storeId = 0){
		$to = $this->getConfig('general/send_to');
		if((bool)$to !== false){
			$info = $request->getData('info');
			$info = $this->_json->unserialize($info);
			if(is_array($info) && count($info)>0){
				foreach ($info as $field) {
					if(@$field['type'] == self::EMAIL_TYPE){
						$this->send($field['value'], $to, $this->toVars($info), $storeId);
					}
				}
			}
		}
	}
	public function toVars($array){
		$vars = [];
		if(is_array($array) && count($array) > 0){
			foreach ($array as $field) {
				$vars[$field['key']] = $field['value'];
			}
		}
		return $vars;
	}
	public function send($from, $to, $vars, $storeId = 0){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$transport = $objectManager->get('Magento\Framework\Mail\Template\TransportBuilder');
		try {
			$transport->setTemplateIdentifier('custom_contact_email_template');
			$transport->setTemplateOptions([
					'area' => \Magento\Framework\App\Area::AREA_FRONTEND, 
					'store' => $storeId
				]);
			$transport->addTo([$to]);
			$transport->setFrom(['name'=>__('Customer'), 'email' => $from]);
			$transport->setTemplateVars($vars);
			$transport->getTransport()->sendMessage();
		} catch (\Exception $e) {}
	}
}