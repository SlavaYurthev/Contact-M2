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

class Closed extends \Magento\Ui\Component\Listing\Columns\Column
{
	protected $storeManager;
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		StoreManagerInterface $storeManager,
		array $components = [],
		array $data = []
	) {
		$this->storeManager = $storeManager;
		parent::__construct($context, $uiComponentFactory, $components, $data);
	}
	public function prepareDataSource(array $dataSource) {
		if(isset($dataSource['data']['items'])) {
			foreach($dataSource['data']['items'] as & $item) {
				if($item) {
					$color = ($item['closed'] == 1 ? 'green' : 'red');
					$item['closed'] = '<span style="color:'.$color.'; font-weight:bold;">'.($item['closed'] == 1 ? __('Yes') : __('No')).'</span>';
				}
			}
		}
		return $dataSource;
	}
}