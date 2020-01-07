<?php
/**
 * Contact
 * 
 * @author Slava Yurthev
 */
namespace SY\Contact\Helper;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Translate\Inline\StateInterface;
use \Magento\Framework\App\Helper\Context;
use SY\Contact\Model\Request;

class Email extends Data
{
	const EMAIL_TYPE = 'email';
	protected $_json;

    /**
     * Used to access request from plugins
     * @var Request
     */
	public $request;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StateInterface
     */
    private $inlineTranslate;

    /**
     * Email constructor.
     *
     * @param Context $context
     * @param Json $json
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslate
     */
    public function __construct(
		Context $context,
		Json $json,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslate
	){
		$this->_json = $json;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslate = $inlineTranslate;
        parent::__construct($context);
    }
	public function recive(Request $request, $storeId = 0){
	    $this->request = $request;
		$to = $this->getConfig('general/send_to');
		if((bool)$to !== false){
			$info = $this->request->getData('info');
			$info = $this->_json->unserialize($info);
			if(is_array($info) && count($info)>0){
				foreach ($info as $field) {
					if(@$field['type'] == self::EMAIL_TYPE){
						$this->send($field['value'], $to, $this->toVars($info), $storeId);
					}
				}
			}
		}
	}
	public function toVars($array){
		$vars = [];
		if(is_array($array) && count($array) > 0){
			foreach ($array as $field) {
				$vars[$field['key']] = $field['value'];
			}
		}
		return $vars;
	}
	public function send($from, $to, $vars, $storeId = 0){
		try {
			$this->inlineTranslate->suspend();
			$this->transportBuilder->setTemplateIdentifier(
				$this->getConfig('general/email_template', $storeId)
			);
			$this->transportBuilder->setTemplateOptions([
					'area' => Area::AREA_FRONTEND,
					'store' => $storeId
				]);
			$this->transportBuilder->addTo($to);
			$this->transportBuilder->setFromByScope(['name'=>__('Customer')->render(), 'email' => $from]);
			$this->transportBuilder->setTemplateVars($vars);
			$this->transportBuilder->getTransport()->sendMessage();
			$this->inlineTranslate->resume();
		} catch (Exception $e) {
		    $this->_logger->critical($e->getMessage(), ['exception' => $e]);
        }
	}
}