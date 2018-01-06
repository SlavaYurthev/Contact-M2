<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Info extends \Magento\Ui\Component\Listing\Columns\Column
{
	protected $storeManager;
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		StoreManagerInterface $storeManager,
		Json $json,
		array $components = [],
		array $data = []
	) {
		$this->json = $json;
		$this->storeManager = $storeManager;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}
	public function prepareDataSource(array $dataSource) {
		if(isset($dataSource['data']['items'])) {
			foreach($dataSource['data']['items'] as & $item) {
				if($item) {
					if(isset($item['info']) && (bool)$item['info'] !== false){
						$info = $item['info'];
						$info = $this->json->unserialize($info);
						$html = '';
						if(count($info)>0){
							foreach ($info as $field) {
								$html .= '<p><strong>'.$field['label'].':</strong> '.$field['value']."</p>";
							}
						}
						$item['info'] = $html;
					}
					else{
						$item['info'] = NULL;
					}
				}
				else{
					$item['info'] = NULL;
				}
			}
		}
		return $dataSource;
	}
}