<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Model\ResourceModel\Request;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection {
	protected function _construct() {
		$this->_init(
			'SY\Contact\Model\Request',
			'SY\Contact\Model\ResourceModel\Request'
		);
	}
}