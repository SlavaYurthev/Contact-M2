<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Block\Adminhtml\Requests\Edit;
 
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
 
class Tabs extends WidgetTabs{
	protected function _construct(){
		parent::_construct();
		$this->setId('request_edit_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(__('Request'));
	}
	protected function _beforeToHtml(){
		$this->addTab(
			'general_data',
			[
				'label' => __('Info'),
				'title' => __('Info'),
				'content' => $this->getLayout()->createBlock(
					'SY\Contact\Block\Adminhtml\Requests\Edit\Tab\General'
				)->toHtml(),
				'active' => true
			]
		);
		return parent::_beforeToHtml();
	}
}