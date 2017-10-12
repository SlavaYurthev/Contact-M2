<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface {
	protected $_resourceConfig;
	public function __construct(
		\Magento\Config\Model\ResourceModel\Config $resourceConfig
	){
		$this->_resourceConfig = $resourceConfig;
	}
	public function install(
		ModuleDataSetupInterface $setup, 
		ModuleContextInterface $context
	) {
		$default = [
			'fields' => [
				'_1506991973917_917' => [
					'key' => 'email',
					'label' => 'E-Mail',
					'field_class' => 'required validate-email',
					'field_type' => 'email'
				],
				'_1506991977533_533' => [
					'key' => 'message',
					'label' => 'Message',
					'field_class' => 'required',
					'field_type' => 'textarea'
				]
			]
		];
		$this->_resourceConfig->saveConfig(
			'sy_contact/general/fields',
			serialize($default['fields']),
			'default',
			0
		);
	}
}