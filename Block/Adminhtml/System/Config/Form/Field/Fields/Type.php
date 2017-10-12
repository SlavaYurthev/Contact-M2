<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Block\Adminhtml\System\Config\Form\Field\Fields;

class Type extends \Magento\Framework\View\Element\Html\Select {
	public function __construct(
		\Magento\Framework\View\Element\Context $context,
		array $data = []
	){
		parent::__construct($context, $data);
	}
	public function setInputName($value){
		return $this->setName($value);
	}
}