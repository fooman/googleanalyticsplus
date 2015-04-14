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

class Fooman_GoogleAnalyticsPlus_Block_TagManager extends Fooman_GoogleAnalyticsPlus_Block_Common_Abstract
{
    protected  $_quote;
    protected  $_order;
    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/tagmanager.phtml');
    }

    /**
     * should we include the tag manager snippet
     *
     * @return bool
     */
    public function shouldInclude()
    {
        if (parent::shouldInclude()) {
            return $this->isTagManagerEnabled() && (bool)$this->getTagManagerSnippet();
        } else {
            return false;
        }
    }

    /**
     * get tag manager snippet from settings
     *
     * @return string
     */
    public function getTagManagerSnippet()
    {
        return Mage::getStoreConfig('google/analyticsplus_tagmanager/snippet');
    }
    
    
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
            $quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
            if ($quoteId) {
                $this->_order = Mage::getModel('sales/order')->loadByAttribute('quote_id', $quoteId);
            } else {
                $this->_order = false;
            }
        } else {
            $this->_order = Mage::getModel('sales/order')->loadByAttribute('quote_id', $this->_quote->getId());
        }
        return $this->_order;
    }
}
