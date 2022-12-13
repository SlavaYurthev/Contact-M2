<?php

namespace SY\Contact\Observer;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RecaptchaFormObserver implements ObserverInterface
{
    private const RECAPTCHA_FORM_KEY = 'sy_contact_form';

    private $isCaptchaEnabled = null;

    private $requestHandler = null;

    private RedirectInterface $redirect;

    private Http $httpResponse;

    public function __construct(
        ObjectManagerInterface $objectManager,
        RedirectInterface $redirect,
        Http $httpResponse
    ) {
        try {
            $this->isCaptchaEnabled = $objectManager->get('Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface');
            $this->requestHandler = $objectManager->get('Magento\ReCaptchaUi\Model\RequestHandlerInterface');
        } catch (\ReflectionException $e) {
            // ReCaptcha not available - we only weakly depend on it
        }
        $this->redirect = $redirect;
        $this->httpResponse = $httpResponse;
    }

    public function execute(Observer $observer): void
    {
        if (!($this->isCaptchaEnabled !== null
            && $this->requestHandler !== null
            && $this->isCaptchaEnabled->isCaptchaEnabledFor(self::RECAPTCHA_FORM_KEY))) {
            return;
        }

        $request = $observer->getRequest();
        $this->requestHandler->execute(
            self::RECAPTCHA_FORM_KEY,
            $request,
            $this->httpResponse,
            $this->redirect->getRefererUrl()
        );
    }
}
