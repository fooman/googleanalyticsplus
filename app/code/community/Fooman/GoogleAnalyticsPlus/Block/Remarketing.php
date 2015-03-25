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

class Fooman_GoogleAnalyticsPlus_Block_Remarketing extends Fooman_GoogleAnalyticsPlus_Block_GaConversion
{

    const GA_PAGETYPE_HOME = 'home';
    const GA_PAGETYPE_SEARCHRESULTS = 'searchresults';
    const GA_PAGETYPE_CATEGORY = 'category';
    const GA_PAGETYPE_PRODUCT = 'product';
    const GA_PAGETYPE_CART = 'cart';
    const GA_PAGETYPE_PURCHASE = 'purchase';
    const GA_PAGETYPE_OTHER = 'other';

    protected $_pageType = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/remarketing.phtml');
    }

    /**
     * are we using dynamic remarketing
     *
     * @return bool
     */
    public function shouldInclude()
    {
        if (parent::shouldInclude()) {
            return Mage::getStoreConfigFlag('google/analyticsplus_dynremarketing/enabled')
                && $this->getConversionId();
        } else {
            return false;
        }
    }

    /**
     * get no script url for remarketing tracking
     *
     * @return string
     */
    public function getNoScriptConversionUrl()
    {
        if ($this->getConversionLabel()) {
            $url = sprintf(
                "//googleads.g.doubleclick.net/pagead/viewthroughconversion/%s/?value=0&amp;label=%s&amp;guid=ON&amp;script=0",
                $this->getConversionId(),
                $this->getConversionLabel()
            );
        } else {
            $url = sprintf(
                "//googleads.g.doubleclick.net/pagead/viewthroughconversion/%s/?value=0&amp;guid=ON&amp;script=0",
                $this->getConversionId()
            );
        }
        return $url;
    }

    /**
     * product category for product page
     *
     * @return string
     */
    public function getEcommCategory()
    {
        if ($this->getPageType() == self::GA_PAGETYPE_PRODUCT) {
            return parent::getProductCategory(Mage::registry('current_product'));
        }
        return false;
    }

    /**
     * value of product for product page
     *
     * @return string
     */
    public function getEcommPValue()
    {
        if ($this->getPageType() == self::GA_PAGETYPE_PRODUCT) {
            return sprintf(
                "%01.2f", Mage::helper('googleanalyticsplus')->convert(
                    Mage::registry('current_product'),
                    'final_price',
                    Mage::app()->getStore()->getCurrentCurrencyCode()
                )
            );
        }

    }

    /**
     * get value for current page, if order present use it's subtotal
     * otherwise use current quote
     *
     * @return string
     */
    public function getPageValue()
    {
        $values = array();
        switch ($this->getPageType()) {
            case self::GA_PAGETYPE_PRODUCT:
                return $this->getEcommPValue();
                break;
            case self::GA_PAGETYPE_CART:
                $quote = Mage::getSingleton('checkout/session')->getQuote();
                if (count($quote->getAllVisibleItems())) {
                    foreach ($quote->getAllVisibleItems() as $basketItem) {
                        $values[] = sprintf(
                            '%01.2f', Mage::helper('googleanalyticsplus')->convert($basketItem, 'row_total')
                        );
                    }
                }
                return $this->getArrayReturnValue($values, '0.00');
                break;
            case self::GA_PAGETYPE_PURCHASE:
                if ($this->_getOrder()) {
                    foreach ($this->_getOrder()->getAllVisibleItems() as $orderItem) {
                        $values[] = sprintf(
                            '%01.2f', Mage::helper('googleanalyticsplus')->convert($orderItem, 'row_total')
                        );
                    }
                }
                return $this->getArrayReturnValue($values, '0.00');
        }
        return "''";
    }

    /**
     * get list of product ids for current page
     *
     * @return string
     */
    public function getProdId()
    {
        $products = array();
        switch ($this->getPageType()) {
            case self::GA_PAGETYPE_PRODUCT:
                $products[] = $this->getConfiguredFeedId(Mage::registry('current_product'));
                return $this->getArrayReturnValue($products, "''");
                break;
            case self::GA_PAGETYPE_CART:
                $quote = Mage::getSingleton('checkout/session')->getQuote();
                if ($quote) {
                    foreach ($quote->getAllItems() as $item) {
                        $products[] = $this->getConfiguredFeedId($item->getProduct());
                    }
                }
                return $this->getArrayReturnValue($products, "''", true);
                break;
            case self::GA_PAGETYPE_PURCHASE:
                if ($this->_getOrder()) {
                    foreach ($this->_getOrder()->getAllItems() as $item) {
                        $products[] = $this->getConfiguredFeedId($item->getProduct());
                    }
                }
                return $this->getArrayReturnValue($products, "''", true);
                break;
        }
        return "''";
    }
    
    /**
     * @param Mage_Catalog_Model_Product $product
     * @return string|integer
     */
    public function getConfiguredFeedId ($product) {
        $idAttr = Mage::getStoreConfig('google/analyticsplus_dynremarketing/feed_product_id');
        $id = $product->getDataUsingMethod($idAttr);
        return $id;
    }

    /**
     * determine current page type for dynamic remarketing
     *
     * @return null|string
     */
    public function getPageType()
    {
        if (is_null($this->_pageType)) {
            if (Mage::registry('current_product')) {
                $this->_pageType = self::GA_PAGETYPE_PRODUCT;
                return $this->_pageType;
            }
            if (Mage::registry('current_category')) {
                $this->_pageType = self::GA_PAGETYPE_CATEGORY;
                return $this->_pageType;
            }
            $module = Mage::app()->getRequest()->getModuleName();
            $controller = Mage::app()->getRequest()->getControllerName();
            $action = Mage::app()->getRequest()->getActionName();

            switch ($module) {
                case 'cms':
                    if ($controller == 'index' && $action == 'index') {
                        $this->_pageType = self::GA_PAGETYPE_HOME;
                    } else {
                        $this->_pageType = self::GA_PAGETYPE_OTHER;
                    }
                    break;
                case 'checkout':
                    if ($controller == 'cart') {
                        $this->_pageType = self::GA_PAGETYPE_CART;
                    } elseif ($action == 'success') {
                        $this->_pageType = self::GA_PAGETYPE_PURCHASE;
                    } else {
                        $this->_pageType = self::GA_PAGETYPE_OTHER;
                    }
                    break;
                case 'catalogsearch':
                    if ($controller == 'result') {
                        $this->_pageType = self::GA_PAGETYPE_SEARCHRESULTS;
                    } else {
                        $this->_pageType = self::GA_PAGETYPE_OTHER;
                    }
                    break;
                default:
                    $this->_pageType = self::GA_PAGETYPE_OTHER;
            }
        }
        return $this->_pageType;
    }

    /**
     * get Adword's conversion label from settings
     * can't be chosen freely since assigned from Google
     *
     * @return string
     */
    public function getConversionLabel()
    {
        return Mage::getStoreConfig('google/analyticsplus_dynremarketing/conversionlabel');
    }

    /**
     * get Google Adwords conversion id
     *
     * @return string
     */
    public function getConversionId()
    {
        return Mage::getStoreConfig('google/analyticsplus_dynremarketing/conversionid');
    }

    /**
     * utility function to convert array of values as single or multiple value notation
     *
     * @param        $values
     * @param string $default
     * @param bool   $sort
     *
     * @return string
     */
    public function getArrayReturnValue($values, $default = '', $sort = false)
    {
        if (empty($values)) {
            return $default;
        }
        if ($sort) {
            asort($values);
        }

        array_walk($values, array($this, 'prepareValue'));
        if (sizeof($values) == 1) {
            return current($values);
        } else {
            return '[' . implode(',', $values) . ']';
        }
    }

    /**
     * escape all quotes and additionally quote all strings
     * @param $value
     *
     * @return mixed|string
     */
    public function prepareValue(&$value)
    {
        $value = $this->jsQuoteEscape($value);
        // quote if value is not numeric
        if (!ctype_digit($value)) {
            $value = "'$value'";
        }
        return $value;
    }
}
