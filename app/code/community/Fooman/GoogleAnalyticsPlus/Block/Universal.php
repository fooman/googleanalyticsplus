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
    const TRACKER_TWO_NAME = 'tracker2';
    CONST URL_ANALYTICS = 'https://www.google-analytics.com/analytics.js';
    CONST URL_ANALYTICS_DEBUG = '//www.google-analytics.com/analytics_debug.js';

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
     * @return bool
     */
    public function getUniversalAnonymise()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/anonymise');
    }

    /**
     * @return bool
     */
    public function getUniversalForceSSL()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/force_ssl');
    }

    /**
     * Build any params that is passed on create of analytics object
     *
     * @param bool $createTrackerTwo
     *
     * @return string
     */
    public function getUniversalParams($createTrackerTwo = false)
    {
        $params = array();
        if (Mage::getStoreConfig('google/analyticsplus_universal/domainname')) {
            $params['cookieDomain'] = Mage::getStoreConfig('google/analyticsplus_universal/domainname');
        }
        if ($this->canUseUniversalUserTracking()) {
            $params['userId'] = $this->getCustomerId();
        }
        if ($createTrackerTwo) {
            $params['name'] = self::TRACKER_TWO_NAME;
        }
        if (Mage::getStoreConfig('google/analyticsplus_universal/sitespeedsamplerate')) {
            $params['siteSpeedSampleRate'] = intval(
                Mage::getStoreConfig('google/analyticsplus_universal/sitespeedsamplerate')
            );
        }
        if (Mage::getStoreConfig('google/analyticsplus_universal/allowlinker')) {
            $params['allowLinker'] = boolval(Mage::getStoreConfig('google/analyticsplus_universal/allowlinker'));
        }
        if (count($params) == 0) {
            return "'auto'";
        }
        return str_replace('"', "'", json_encode($params));
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

    /**
     * Is universal user tracking available.
     * Must be enabled in admin and a user must be logged in
     * TODO: Use persistent login data!
     *
     * @return bool
     */
    public function canUseUniversalUserTracking()
    {
        return (Mage::getStoreConfigFlag('google/analyticsplus_universal/userid_tracking')
            && Mage::getSingleton(
                'customer/session'
            )->isLoggedIn()) ? true : false;
    }

    /**
     * Get the current logged in customer id
     *
     * @return mixed bool | integer
     */
    public function getCustomerId()
    {
        if (Mage::getSingleton('customer/session')->isLoggedIn()
            && is_object(
                Mage::getSingleton('customer/session')->getCustomer()
            )
        ) {
            return Mage::getSingleton('customer/session')->getCustomer()->getId();
        }
        return false;
    }

    /**
     * Get the right analytics.js
     *
     * @return string
     */
    public function getAnalyticsLocation()
    {
        $debug = Mage::getStoreConfigFlag('google/analyticsplus_universal/debug');

        if ($debug) {
            return self::URL_ANALYTICS_DEBUG;
        }

        return self::URL_ANALYTICS;
    }

    /**
     * Get the exclude shipping settings
     *
     * @return bool
     */
    public function shouldExcludeShipping()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/exclude_shipping');
    }

    /**
     * Get the exclude Tax Settings....
     *
     * @return bool
     */
    public function shouldExcludeTax()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/exclude_tax');
    }

    /**
     * Get the "enhanced link attribution" setting.
     * See https://support.google.com/analytics/answer/2558867?hl=en
     *
     * @return bool
     */
    public function isEnhancedLinkAttrEnabled()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_universal/enhanced_link_attribution');
    }

    /**
     * We don't want to send ecommerce transactions if enhanced ecommerce transactions are being sent
     *
     * @return bool
     */
    public function sendEcommerceTracking()
    {
        if (Mage::helper('core')->isModuleEnabled('Mediotype_GoogleEnhancedEcommerce')) {
            return !Mage::helper('mediotype_ee')->getEnabled();
        }
        return true;
    }

}
