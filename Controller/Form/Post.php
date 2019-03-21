<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Controller\Form;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory; 

class Post extends Action {
	
	protected $request;
	protected $helper;
	protected $json;
	protected $validator;
	protected $_storemanagerinterface;
	protected $email;
	protected $messageManager;
	
	public function __construct(
			\Magento\Framework\App\Action\Context $context, 
			\SY\Contact\Model\Request $request,
			\SY\Contact\Helper\Data $helper,
			\Magento\Framework\Serialize\Serializer\Json $json,
			\Magento\Framework\Data\Form\FormKey\Validator $validator,
			\Magento\Store\Model\StoreManagerInterface $_storemanagerinterface,
			\SY\Contact\Helper\Email $email,
			\Magento\Framework\Message\ManagerInterface $messageManager
		){
		parent::__construct($context);
		$this->request = $request;
		$this->helper = $helper;
		$this->json = $json;
		$this->validator = $validator;
		$this->storeManager = $_storemanagerinterface;
		$this->email = $email;
		$this->messageManager = $messageManager;
	}
	
	public function execute() {
		$validator = $this->validator;
		if ($validator->validate($this->getRequest())) {
			$store = $this->storeManager->getStore();
			$fields = $this->helper->getConfig('general/fields', $store->getId());
			$fields = $this->json->unserialize($fields);
			$info = [];
			if(count($fields)>0){
				foreach ($fields as $field) {
					try {
						if($this->getRequest()->getParam($field['key'])){
							$info[] = [
								'key' => $field['key'],
								'label' => $field['label'],
								'type' => $field['field_type'],
								'value' => $this->getRequest()->getParam($field['key'])
							];
						}
					} catch (\Exception $e) {}
				}
			}
			if(count($info)>0){
				$model = $this->request;
				$model->setData('info', $this->json->serialize($info));
				try {
					$model->save();
					if($model->getId()){
						$email = $this->email;
						$email->recive($model, $store->getId());
						$messageManager = $this->messageManager;
						$messageManager->addSuccess(__('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.'));
					}
				} catch (\Exception $e) {}
			}
		}
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		$resultRedirect->setUrl($this->_redirect->getRefererUrl());
		return $resultRedirect; 
	}
}
