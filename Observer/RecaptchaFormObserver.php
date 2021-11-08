<?php

namespace SY\Contact\Observer;

use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface;
use Magento\ReCaptchaUi\Model\RequestHandlerInterface;

class RecaptchaFormObserver implements ObserverInterface
{
    private const RECAPTCHA_FORM_KEY = 'sy_contact_form';

    private IsCaptchaEnabledInterface $isCaptchaEnabled;

    private RequestHandlerInterface $requestHandler;

    private RedirectInterface $redirect;

    private Http $httpResponse;

    public function __construct(
        IsCaptchaEnabledInterface $isCaptchaEnabled,
        RequestHandlerInterface $requestHandler,
        RedirectInterface $redirect,
        Http $httpResponse
    ) {
        $this->isCaptchaEnabled = $isCaptchaEnabled;
        $this->requestHandler = $requestHandler;
        $this->redirect = $redirect;
        $this->httpResponse = $httpResponse;
    }

    public function execute(Observer $observer): void
    {
        if ($this->isCaptchaEnabled->isCaptchaEnabledFor(self::RECAPTCHA_FORM_KEY)) {
            $request = $observer->getRequest();
            $this->requestHandler->execute(
                self::RECAPTCHA_FORM_KEY,
                $request,
                $this->httpResponse,
                $this->redirect->getRefererUrl()
            );
        }
    }
}
