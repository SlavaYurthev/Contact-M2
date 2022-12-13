<?php
/**
 * Contact
 *
 * @author Slava Yurthev
 */
namespace SY\Contact\Block;

class ContactForm extends \Magento\Contact\Block\ContactForm {
	private $_fields;

    /** @var \Magento\Customer\Model\Session */
    protected $_customerSession;

	private $helper;

	private $json;

	protected $formKey;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Email\Model\Template\FilterFactory $templateFilter,
        \Magento\Customer\Model\Session $_customerSession,
		\SY\Contact\Helper\Data $helper,
		\Magento\Framework\Serialize\Serializer\Json $json,
		\Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
        )
    {
        parent::__construct($context, $data);
        $this->templateFilter = $templateFilter;
        $this->_customerSession = $_customerSession;
		$this->helper = $helper;
		$this->json = $json;
		$this->formKey = $formKey;
    }

    public function getFields(){
		if(!$this->_fields){
			$fields = $this->helper->getConfig(
				'general/fields',
				$this->_storeManager->getStore()->getId()
			);
			$fields = this->json->unserialize($fields);
			if(count($fields)>0){
				foreach ($fields as $key => $field) {
					$object = new \Magento\Framework\DataObject;
					$field['default_value'] = $this->runDirectives($field['default_value']);
					$object->addData($field);
					$fields[$key] = $object;
				}
			}
			$this->_fields = $fields;
		}
		return $this->_fields;
	}
	public function getFormKey(){
		return $this->formKey->getFormKey();
	}

	protected function collectVariables() {
        $customer = $this->_customerSession->getCustomer();

        $customerDefaultBilling = $customer->getDefaultBillingAddress();
        $customerDefaultShipping = $customer->getDefaultShippingAddress();

        return compact('customer', 'customerDefaultBilling', 'customerDefaultShipping');
    }

    /**
     * Runs email template directives on the value, enabling us to use default values like
     * {{customer.firstname}}
     * {{customerDefaultBilling.city}}
     * and such
     *
     * @param $value
     */
	protected function runDirectives($value) {
	    $filter = $this->templateFilter->create([
	        'variables' => $this->collectVariables()
        ]);
	    $result = $filter->filter($value);
	    return $result;
    }

    protected function getCustomContactFormData()
    {
        $fields = $this->getFields();
        $result = [];
        foreach($fields as $field) {
            $result[$field->getKey()] = [
                'show_if' => $field->getShowIf()
            ];

        }

        return $result;
    }

    public function getJsFormConfig()
    {
        return json_encode([
            "validation" => new \stdClass(), "customContactForm" => $this->getCustomContactFormData()
        ]);
    }
}
