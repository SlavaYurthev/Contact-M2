<?php
/**
 * Contact
 *
 * @author Slava Yurthev
 */
namespace SY\Contact\Helper;

use Exception;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Translate\Inline\StateInterface;
use SY\Contact\Model\Request;

class Email extends Data
{
    const EMAIL_TYPE = 'email';

    /**
     * Used to access request from plugins
     * @var Request
     */
    public $request;

    protected $_json;

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
    ) {
        $this->_json = $json;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslate = $inlineTranslate;
        parent::__construct($context);
    }

    /**
     * @param Request $request
     * @param int $storeId
     */
    public function receive(Request $request, $storeId = 0)
    {
        $this->request = $request;
        $info = $this->request->getData('info');
        $info = $this->_json->unserialize($info);
        if (is_array($info) && count($info) > 0) {
            foreach ($info as $field) {
                if (@$field['type'] == self::EMAIL_TYPE) {
                    $this->send($field['value'], $this->toVars($info), $storeId);
                }
            }
        }
    }

    /**
     * @param $array
     *
     * @return array
     */
    public function toVars(array $array)
    {
        $vars = [];
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $field) {
                $vars[$field['key']] = $field['value'];
            }
        }

        return $vars;
    }

    /**
     * @param string $to
     * @param array $vars
     * @param int $storeId
     *
     * @throws LocalizedException
     * @throws MailException
     */
    public function send(string $to, array $vars, int $storeId = 0)
    {
    $this->inlineTranslate->suspend();
    $this->transportBuilder->setTemplateIdentifier(
        $this->getContactConfig('general/email_template', $storeId)
    );
    $this->transportBuilder->setTemplateOptions([
        'area' => Area::AREA_FRONTEND,
        'store' => $storeId,
    ]);
    $this->transportBuilder->addTo($to);
    $this->transportBuilder->addBcc($this->getRecipientAddress());
    $this->transportBuilder->setFromByScope($this->getFrom());
    $this->transportBuilder->setTemplateVars($vars);
    $this->transportBuilder->getTransport()->sendMessage();
    $this->inlineTranslate->resume();
    }

    /**
     * @return mixed
     */
    private function getRecipientAddress()
    {
        return $this->getContactConfig('general/send_to') ?? $this->getConfig('trans_email/ident_sales/email');
    }

    /**
     * @return array
     */
    private function getFrom()
    {
        return [
            'name' => $this->getFromName(),
            'email' => $this->getFromAddress(),
        ];
    }

    /**
     * @return mixed
     */
    private function getFromAddress()
    {
        return $this->getContactConfig('general/send_from') ?? $this->getConfig('trans_email/ident_sales/email');
    }

    /**
     * @return mixed
     */
    private function getFromName()
    {
       return  $this->getContactConfig('general/send_from_name') ?? $this->getConfig('trans_email/ident_sales/name');
    }
}
