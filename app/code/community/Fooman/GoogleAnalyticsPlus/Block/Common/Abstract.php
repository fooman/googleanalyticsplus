<?php

class Fooman_GoogleAnalyticsPlus_Block_Common_Abstract extends Mage_Core_Block_Template
{
    /**
     * where cookie opt in is present and not yet accepted do not track
     *
     * @return bool
     */
    public function shouldInclude()
    {
        $coreHelperDir = Mage::getConfig()->getModuleDir('', 'Mage_Core') . DS . 'Helper' . DS;
        if (file_exists($coreHelperDir . 'Cookie.php')) {
            if (Mage::helper('core/cookie')->isUserNotAllowSaveCookie()) {
                return false;
            }
        }
        return true;
    }

    /**
     * determine if we are on the order success page
     *
     * @return bool
     */
    public function isSuccessPage()
    {
        $handles = $this->getLayout()->getUpdate()->getHandles();
        return in_array('checkout_onepage_success', $handles)
        || in_array('checkout_multishipping_success', $handles);
    }
}
