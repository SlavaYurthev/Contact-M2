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
	protected $_json;
	public function __construct(
		\Magento\Config\Model\ResourceModel\Config $resourceConfig,
		\Magento\Framework\Serialize\Serializer\Json $json
	){
		$this->_json = $json;
		$this->_resourceConfig = $resourceConfig;
	}
	public function install(
		ModuleDataSetupInterface $setup, 
		ModuleContextInterface $context
	) {
		$default = [
			'fields' => [
				[
					'key' => 'email',
					'label' => 'E-Mail',
					'field_class' => 'required validate-email',
					'field_type' => 'email'
				],
				[
					'key' => 'message',
					'label' => 'Message',
					'field_class' => 'required',
					'field_type' => 'textarea'
				]
			]
		];
		$this->_resourceConfig->saveConfig(
			'sy_contact/general/fields',
			$this->_json->serialize($default['fields']),
			'default',
			0
		);
	}
}