<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\ObjectManagerInterface;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper {
	public function getConfig($field, $storeId = 0){
		return $this->scopeConfig->getValue('sy_contact/'.$field, ScopeInterface::SCOPE_STORE, $storeId);
	}
}