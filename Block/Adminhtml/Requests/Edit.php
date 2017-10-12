<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Block\Adminhtml\Requests;

class Edit extends \Magento\Backend\Block\Widget\Form\Container {
	protected $_coreRegistry = null;
	public function __construct(
		\Magento\Backend\Block\Widget\Context $context,
		\Magento\Framework\Registry $registry,
		array $data = []
	) {
		$this->_coreRegistry = $registry;
		parent::__construct($context, $data);
	}
	protected function _construct(){
		$this->_objectId = 'request_id';
		$this->_blockGroup = 'SY_Contact';
		$this->_controller = 'adminhtml_requests';
		parent::_construct();
		if ($this->_isAllowedAction('SY_Contact::requests')) {
			$this->buttonList->remove('reset');
			$this->buttonList->update('save', 'label', __('Update Request'));
			$this->buttonList->add(
				'saveandcontinue',
				[
					'label' => __('Update and Continue Edit'),
					'class' => 'save',
					'data_attribute' => [
						'mage-init' => [
							'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
						],
					]
				],
				-100
			);
		} else {
			$this->buttonList->remove('save');
		}
		$this->buttonList->remove('delete');
	}
	public function getHeaderText(){
		if ($this->_coreRegistry->registry('request')->getId()) {
			return __("Edit Request '%1'", $this->escapeHtml($this->_coreRegistry->registry('request')->getId()));
		} else {
			return __('New Request');
		}
	}
	protected function _isAllowedAction($resourceId){
		return $this->_authorization->isAllowed($resourceId);
	}
	protected function _getSaveAndContinueUrl(){
		return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
	}
	protected function _prepareLayout(){
		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('general_content') == null) {
					tinyMCE.execCommand('mceAddControl', false, 'general_content');
				} else {
					tinyMCE.execCommand('mceRemoveControl', false, 'general_content');
				}
			};
		";
		return parent::_prepareLayout();
	}
}