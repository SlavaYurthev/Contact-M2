<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Model;

use Magento\Framework\Model\AbstractModel;

class Request extends AbstractModel {
	protected function _construct() {
		$this->_init('SY\Contact\Model\ResourceModel\Request');
	}
}