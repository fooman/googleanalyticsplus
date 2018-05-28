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
    protected $_order;

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
            return $this->isTagManagerEnabled() && ((bool)$this->getTagManagerSnippet() || (bool)$this->getTagManagerId());
        }
        return false;
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
     * get order from the last quote id
     *
     * @return mixed
     */
    protected function _getOrder()
    {

        $quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
        if ($quoteId) {
            $this->_order = Mage::getModel('sales/order')->loadByAttribute('quote_id', $quoteId);
        } else {
            $this->_order = false;
        }

        return $this->_order;
    }
}
