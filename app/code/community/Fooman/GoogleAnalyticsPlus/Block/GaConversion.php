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

class  Fooman_GoogleAnalyticsPlus_Block_GaConversion extends Fooman_GoogleAnalyticsPlus_Block_Common_Abstract
{

    const URL_ADWORDS_CONVERSION = 'http://www.googleadservices.com/pagead/conversion.js';
    const URL_ADWORDS_CONVERSION_SECURE = 'https://www.googleadservices.com/pagead/conversion.js';

    protected  $_quote;
    protected  $_order;


    /**
     * helper to set the internal quote property
     *
     * @param $quote
     */
    public function setQuote($quote)
    {
        $this->_quote = $quote;
    }

    /**
     * is adwords conversion tracking enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_classic/conversionenabled');
    }

    /**
     * get Adword's conversion label from settings
     * can't be chosen freely since assigned from Google
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::getStoreConfig('google/analyticsplus_classic/conversionlabel');
    }

    /**
     * get a color - defaults to white
     *
     * @return string
     */
    public function getColor()
    {
        return '#FFFFFF';
    }

    /**
     * get the entered language
     *
     * @return string
     */
    public function getLanguage()
    {
        return Mage::getStoreConfig('google/analyticsplus_classic/conversionlanguage');
    }

    /**
     * get Google Adwords conversion id
     *
     * @return string
     */
    public function getConversionId()
    {
        return Mage::getStoreConfig('google/analyticsplus_classic/conversionid');
    }

    /**
     * get url for adwords conversion tracking secure/unsecure
     *
     * @return string
     */
    public function getConversionUrl()
    {
        return ($this->getRequest()->isSecure()) ? self::URL_ADWORDS_CONVERSION_SECURE
            : self::URL_ADWORDS_CONVERSION;
    }

    /**
     * get no script url for adwords conversion tracking secure/unsecure
     *
     * @return string
     */
    public function getNoScriptConversionUrl()
    {
        if ($this->getLabel()) {
            $url = sprintf(
                "//www.googleadservices.com/pagead/conversion/%s/?value=%s&amp;label=%s&amp;script=0",
                $this->getConversionId(),
                $this->getValue(),
                $this->getLabel()
            );
        } else {
            $url = sprintf(
                "//www.googleadservices.com/pagead/conversion/%s/?value=%s&amp;script=0",
                $this->getConversionId(),
                $this->getValue()
            );
        }
        return $url;
    }

    /**
     * get conversion value, convert if chosen to a differnt currency
     *
     * @return int|string
     */
    public function getValue()
    {
        $order = $this->_getOrder();
        if ($order) {
            return Mage::helper('googleanalyticsplus')->convert($order, 'subtotal');
        } else {
            return 0;
        }
    }

    /**
     * get order from the last quote id
     *
     * @return mixed
     */
    protected function _getOrder()
    {
        if (!$this->_quote) {
            $ids = Mage::registry('googleanalyticsplus_order_ids');
            $orderId = (is_array($ids) ? reset($ids) : null);

            if ($orderId) {
                $this->_order = Mage::getModel('sales/order')->load($orderId);
            } else {
                $this->_order = false;
            }
        } else {
            $this->_order = Mage::getModel('sales/order')->loadByAttribute('quote_id', $this->_quote->getId());
        }
        return $this->_order;
    }
}

