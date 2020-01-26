<?php
/**
 * Contact
 *
 * @author Slava Yurthev
 */
namespace SY\Contact\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @param string $path
     * @param int $storeId
     *
     * @return mixed
     */
    public function getContactConfig(string $path, int $storeId = 0)
    {
        return $this->getConfig('sy_contact/' . $path, $storeId);
    }

    /**
     * @param string $path
     * @param int $storeId
     *
     * @return mixed
     */
    public function getConfig(string $path, int $storeId = 0)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }
}