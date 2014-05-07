<?php

/**
 * Fooman GoogleAnalyticsPlus
 *
 * @package   Fooman_GoogleAnalyticsPlus
 * @author    Kristof Ringleff <kristof@fooman.co.nz>
 * @copyright Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Fooman_GoogleAnalyticsPlus_Block_Universal extends Fooman_GoogleAnalyticsPlus_Block_GaConversion
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/universal.phtml');
    }

    /**
     * should we include the universal snippet
     *
     * @return bool
     */
    public function shouldInclude()
    {
        if (parent::shouldInclude()) {
            return $this->isUniversalEnabled() && (bool)$this->getUniversalAccount();
        } else {
            return false;
        }
    }

    /**
     * are we using universal
     *
     * @return bool
     */
    public function isUniversalEnabled()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/enabled');
    }

    /**
     * get universal snippet from settings
     *
     * @deprecated since 0.14.0
     * @return string
     */
    public function getUniversalSnippet()
    {
        return '';
    }

    /**
     * get Google Analytics account id
     *
     * @return mixed
     */
    public function getUniversalAccount()
    {
        return Mage::getStoreConfig('google/analyticsplus_universal/accountnumber');
    }

    /**
     * @return bool
     */
    public function getUniversalAnonymise()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/anonymise');
    }

    /**
     * @return string
     */
    public function getUniversalParams()
    {
        if (Mage::getStoreConfig('google/analyticsplus_universal/domainname')) {
            return sprintf(
                "{'cookieDomain': '%s'}",
                Mage::getStoreConfig('google/analyticsplus_universal/domainname')
            );
        } else {
            return "'auto'";
        }
    }

    /**
     * Enable the Display Advertising Features.
     *
     * @return bool
     */
    public function getUniversalDisplayAdvertising()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/display_advertising');
    }

    /**
     * Return cookiename for Display Advertising
     *
     * @return String
     */
    public function getUniversalDisplayAdvertisingCookieName()
    {
        return Mage::getStoreConfig('google/analyticsplus_universal/display_advertising_cookiename');
    }

}
