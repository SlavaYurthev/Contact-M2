<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Controller\Adminhtml\Requests;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Save extends Action {
	protected $_resultPageFactory;
	protected $_resultPage;
	protected $request;
	protected $session;
	
	public function __construct(
			\Magento\Backend\App\Action\Context $context, 
			\Magento\Framework\View\Result\PageFactory $resultPageFactory,
			\SY\Contact\Model\Request $request,
			\Magento\Backend\Model\Session $session
		){
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
		$this->request = $request;
		$this->session = $session;
	}
	public function execute(){
		$data = $this->getRequest()->getPostValue();
		$resultRedirect = $this->resultRedirectFactory->create();
		$id = $this->getRequest()->getParam('id');
		$model = $this->request;
		if($id) {
			$model->load($id);
		}
		$model->setData($data);
		try {
			$model->save();
			$this->messageManager->addSuccess(__('Saved.'));
			if ($this->getRequest()->getParam('back')) {
				return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
			}
			$this->session->setFormData(false);
			return $resultRedirect->setPath('*/*/');
		} catch (\Exception $e) {
			$this->messageManager->addException($e, __('Something went wrong.'));
		}
		$this->_getSession()->setFormData($data);
		return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
	}
	protected function _isAllowed(){
		return $this->_authorization->isAllowed('SY_Contact::requests');
	}
}
