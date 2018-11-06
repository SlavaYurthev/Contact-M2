<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Block\Adminhtml\System\Config\Form\Field;

class Fields extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray {
	private $_typeRenderer;
	private function getTypeRenderer(){
		if(!$this->_typeRenderer){
			$this->_typeRenderer = $this->getLayout()->createBlock(
				'SY\Contact\Block\Adminhtml\System\Config\Form\Field\Fields\Type',
				'',
				['data' => ['is_render_to_js_template' => true]]
			);
		}
		return $this->_typeRenderer
			->addOption('text', 'Single Line Text')
			->addOption('textarea', 'Multi Line Text')
			->addOption('email', 'E-Mail')
			->addOption('checkbox', 'Checkbox')
			->addOption('checkbox_list', 'Checkbox List')
			->addOption('select', 'Drop Down');
	}
	protected function _prepareToRender(){
		$this->addColumn('key', [
				'label' => __('Key'), 
				'style'=>'min-width:100px',
				'class' => 'input-text required'
			]);
		$this->addColumn('label', [
				'label' => __('Label'), 
				'style'=>'min-width:100px',
				'class' => 'input-text required'
			]);
		$this->addColumn('field_class', [
				'label' => __('Field Class'), 
				'style'=>'min-width:100px'
			]);
		$this->addColumn('default_value', [
				'label' => __('Default Value*'),
				'style'=>'min-width:100px'
			]);
		$this->addColumn('options', [
				'label' => __('Options'),
				'style'=>'min-width:100px'
			]);
        $this->addColumn('show_if', [
                'label' => __('Show If**'),
                'style'=>'min-width:100px'
            ]);
		$this->addColumn('field_type', [
				'label' => __('Type'), 
				'style'=>'min-width:100px',
				'renderer' => $this->getTypeRenderer()
			]);
		$this->_addAfter = false;
		$this->_addButtonLabel = __('Add');
	}
	protected function _prepareArrayRow(\Magento\Framework\DataObject $row){
		$options = [];

		$type = $row->getData('field_type');
		$key = 'option_' . $this->getTypeRenderer()->calcOptionHash($type);
		$options[$key] = 'selected="selected"';
		
		$row->setData('option_extra_attrs', $options);
	}
}