<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Mage
 * @package     Mage_GoogleAnalytics
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Google Analytics block
 *
 * @category   Fooman
 * @package    Fooman_GoogleAnalyticsPlus
 * @author     Magento Core Team <core@magentocommerce.com>
 * @author     Fooman, Kristof Ringleff <kristof@fooman.co.nz>
 */
class Fooman_GoogleAnalyticsPlus_Block_Ga extends Fooman_GoogleAnalyticsPlus_Block_Common_Abstract
{

    const URL_GA_STANDARD = 'http://www.google-analytics.com/ga.js';
    const URL_GA_STANDARD_SECURE = 'https://ssl.google-analytics.com/ga.js';
    const URL_DOUBLECLICK = 'http://stats.g.doubleclick.net/dc.js';
    const URL_DOUBLECLICK_SECURE = 'https://stats.g.doubleclick.net/dc.js';

    protected $_helper;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/ga.phtml');
        $this->_helper = Mage::helper('googleanalyticsplus');
    }


    /**
     * get the current url to send to Google Analytics
     *
     * @return string
     */
    public function getUrlToTrack()
    {
        $optPageURL = trim($this->getPageName());
        if ($optPageURL && preg_match('/^\/.*/i', $optPageURL)) {
            $optPageURL = "{$this->jsQuoteEscape($optPageURL)}";
        }
        return $optPageURL;
    }

    /**
     * things have change in Magento 1.4.2
     *
     * @return bool
     */
    public function isNew()
    {
        //Mage 1.4.2 +
        return method_exists(Mage::helper('googleanalytics'), 'isGoogleAnalyticsAvailable');
    }

    /**
     * get Google Analytics profile id
     *
     * @return mixed|string
     */
    public function getMainAccountId()
    {
        if (!Mage::helper('googleanalytics')->isGoogleAnalyticsAvailable()) {
            return '';
        }
        return Mage::getStoreConfig(Mage_GoogleAnalytics_Helper_Data::XML_PATH_ACCOUNT);
    }

    /**
     * get alternative Google Analytics profile id
     *
     * @return mixed
     */
    public function getAlternativeAccountId()
    {
        return Mage::getStoreConfig('google/analyticsplus_classic/accountnumber2');
    }

    /**
     * should we include tracking code
     * if cookies have not been accepted - do no track
     *
     * @return bool
     */
    public function shouldIncludeTracking()
    {
        $coreHelperDir = Mage::getConfig()->getModuleDir('', 'Mage_Core') . DS . 'Helper' . DS;
        if (file_exists($coreHelperDir . 'Cookie.php')) {
            if (Mage::helper('core/cookie')->isUserNotAllowSaveCookie()) {
                return false;
            }
        }
        return (bool)$this->getMainAccountId();
    }

    /**
     * which GA script are we using?
     *
     * @return string
     */
    public function getGaLocation()
    {
        $secure = Mage::app()->getStore()->isCurrentlySecure() ? 'true' : 'false';
        if ($secure == 'true') {
            if (Mage::getStoreConfig('google/analyticsplus_classic/remarketing')) {
                return self::URL_DOUBLECLICK_SECURE;
            } else {
                return self::URL_GA_STANDARD_SECURE;
            }
        } else {
            if (Mage::getStoreConfig('google/analyticsplus_classic/remarketing')) {
                return self::URL_DOUBLECLICK;
            } else {
                return self::URL_GA_STANDARD;
            }
        }
    }

    /**
     * determine if we are on the order success page
     *
     * @return bool
     */
    public function isSuccessPage()
    {
        $orderIds = $this->getOrderIds();
        return parent::isSuccessPage() || (!empty($orderIds) && is_array($orderIds));
    }


    /**
     * Render information about specified orders and their items
     *
     * @link http://code.google.com/apis/analytics/docs/gaJS/gaJSApiEcommerce.html#_gat.GA_Tracker_._addTrans
     *
     * @param bool $accountIdAlt
     *
     * @return string
     */
    public function getOrdersTrackingCode($accountIdAlt = false)
    {
        $html = '';
        $orderIds = $this->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return $html;
        }

        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('entity_id', array('in' => $orderIds));
        $result = array();
        foreach ($collection as $order) {
            if ($order->getIsVirtual()) {
                $address = $order->getBillingAddress();
            } else {
                $address = $order->getShippingAddress();
            }

            $result[] = sprintf(
                "_gaq.push(['_addTrans', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']);",
                $order->getIncrementId(),
                $this->jsQuoteEscape(Mage::app()->getStore()->getFrontendName()),
                Mage::helper('googleanalyticsplus')->convert($order, 'grand_total'),
                Mage::helper('googleanalyticsplus')->convert($order, 'tax_amount'),
                Mage::helper('googleanalyticsplus')->convert($order, 'shipping_amount'),
                $this->jsQuoteEscape($address->getCity()),
                $this->jsQuoteEscape($address->getRegion()),
                $this->jsQuoteEscape($address->getCountry())
            );

            foreach ($order->getAllVisibleItems() as $item) {
                $result[] = sprintf(
                    "_gaq.push(['_addItem', '%s', '%s', '%s', '%s', '%s', '%s']);",
                    $order->getIncrementId(),
                    $this->jsQuoteEscape($item->getSku()),
                    $this->jsQuoteEscape($item->getName()),
                    $this->jsQuoteEscape($this->getCategory($item)),
                    Mage::helper('googleanalyticsplus')->convert($item, 'price'),
                    (int)$item->getQtyOrdered()
                );
            }
            $result[] = "_gaq.push(['_trackTrans']);";
            $html = implode("\n", $result);
        }

        if ($accountIdAlt) {
            $html .= str_replace('_gaq.push([\'_', '_gaq.push([\'t2._', $html);
        }
        return $html;
    }

    /**
     * set CustomVars for customers
     *
     * @param bool $accountIdAlt
     *
     * @return string
     */
    public function getCustomerVars($accountIdAlt = false)
    {
        //set customer variable for the current visitor c=1
        return "

    _gaq.push(['_setCustomVar', 5, 'c', '1', 1]);
    " . ($accountIdAlt ? "_gaq.push(['t2._setCustomVar', 5, 'c', '1', 1]);" : "");
    }

    /**
     * @param          $accountId
     * @param bool|int $accountIdAlt
     *
     * @return string
     */
    public function getPageTrackingCode($accountId, $accountIdAlt = false)
    {
        //url to track
        $optPageURL = $this->getUrlToTrack();

        //main profile tracking
        $html= "_gaq.push(['_setAccount', '" . $this->jsQuoteEscape($accountId) . "']";
        if ($domainName = Mage::getStoreConfig('google/analyticsplus_classic/domainname')) {
            $html .= " ,['_setDomainName','" . $domainName . "']";
        }

        //anonymise
        $anonymise = Mage::getStoreConfigFlag('google/analyticsplus_classic/anonymise');
        if ($anonymise) {
            $html .= ", ['_gat._anonymizeIp']";
        }

        //send track page view
        $html .= ", ['_trackPageview','" . $optPageURL . "']";

        $html .= ");";

        //track to alternative profile (optional)
        if ($accountIdAlt) {
            $html .= $this->getPageTrackingAlt($accountIdAlt, $optPageURL);
        }

        return $html;
    }

    /**
     * get tracking code for alternative profile
     *
     * @param $accountIdAlt
     * @param $optPageURL
     *
     * @return string
     */
    public function getPageTrackingAlt($accountIdAlt, $optPageURL)
    {
        $html = "
            _gaq.push(['t2._setAccount', '" . $this->jsQuoteEscape($accountIdAlt) . "']";
        $domainNameAlt = Mage::getStoreConfig('google/analyticsplus_classic/domainname2');
        if ($domainNameAlt) {
            $html .= " ,['t2._setDomainName','" . $domainNameAlt . "']";
        }
        $html .= ", ['t2._trackPageview','" . $optPageURL . "']";

        $html .= ");";
        return $html;
    }

    /**
     * return code to track AJAX requests
     *
     * @param bool|int $accountIdAlt
     *
     * @return string
     */
    public function getAjaxPageTracking($accountIdAlt = false)
    {

        $parts = parse_url($this->getPageName());
        $query = '';
        if (isset($parts['query']) && !empty($parts['query'])) {
            $query = '?' . $parts['query'];
        }
        unset($parts['query']);
        unset($parts['fragment']);
        $baseUrl = Mage::getSingleton('core/url')->escape(
            rtrim(
                str_replace(
                    'index/', '',
                    Mage::app()->getRequest()->getBaseUrl() . Mage::app()->getRequest()->getRequestString()
                ), '/'
            )
        );

        return "

            if(typeof Ajax !== \"undefined\" && Ajax.Responders){
                Ajax.Responders.register({
                  onComplete: function(transport){
                    if(!transport.url.include('progress') && !transport.url.include('getAdditional')){
                        goto_section = false;
                        if (transport && transport.transport.responseText){
                            try{
                                response = eval('(' + transport.transport.responseText + ')');
                                if(response && response.goto_section){
                                    goto_section = '/opc-' + encodeURIComponent(response.goto_section);
                                }
                            }
                            catch (e) {
                                goto_section = false;
                            }
                        }
                        if(transport.url.include('saveOrder')){
                            _gaq.push(['_trackPageview', '".$baseUrl."'
                                + '/opc-review-placeOrderClicked".$query."']);"
        .($accountIdAlt?"
                            _gaq.push(['t2._trackPageview', '".$baseUrl."'
                                + '/opc-review-placeOrderClicked".$query."']);":"")."
                        }else if(goto_section){
                            _gaq.push(['_trackPageview', '".$baseUrl."'
                                + goto_section + '".$query."']);"
        .($accountIdAlt?"
                            _gaq.push(['t2._trackPageview', '".$baseUrl."'
                                + goto_section + '".$query."']);":"")."
                        }else if(accordion && accordion.currentSection){
                            _gaq.push(['_trackPageview', '".$baseUrl."/'
                                + accordion.currentSection + '".$query."']);"
        .($accountIdAlt?"
                            _gaq.push(['t2._trackPageview', '".$baseUrl."/'
                                + accordion.currentSection + '".$query."']);":"")."
                        }
                    }
                  }
                });
            }
";
    }

}
