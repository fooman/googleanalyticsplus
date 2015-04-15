<?php

class Fooman_GoogleAnalyticsPlus_Block_Common_Abstract extends Mage_Core_Block_Template
{
    const XML_PATH_SUCCESS_PAGE_BLOCK_HANDLES = 'google/analyticsplus_abstract/success_page_handles';
    
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
        foreach (array_keys(Mage::getStoreConfig(self::XML_PATH_SUCCESS_PAGE_BLOCK_HANDLES)) as $handle) {
            if (in_array($handle, $handles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * get product category for tracking purposes
     * either based on chosen category tracking attribute
     * or first category encountered
     *
     * @param $product
     *
     * @return mixed
     */
    public function getProductCategory($product)
    {
        $attributeCode = Mage::getStoreConfig('google/analyticsplus/categorytrackingattribute');
        if ($attributeCode) {
            if ($product->getResource()->getAttribute($attributeCode)) {
                $attributeValue = $product->getAttributeText($attributeCode);
                if (!$attributeValue) {
                    return $product->getDataUsingMethod($attributeCode);
                } else {
                    return $attributeValue;
                }
            }
        } else {
            $catIds = $product->getCategoryIds();
            foreach ($catIds as $catId) {
                $category = Mage::getModel('catalog/category')->load($catId);
                if ($category) {
                    //we use the first category
                    return $category->getName();
                }
            }
        }
        return false;
    }

    /**
     * retrieve an item's category
     * if no product attribute chosen use the product's first category
     *
     * @param $item
     *
     * @return mixed|null
     */
    protected function getCategory($item)
    {

        $product = Mage::getModel('catalog/product')->load($item->getProductId());
        if ($product) {
            return $this->getProductCategory($product);
        }
        return null;
    }

    /**
     * Return REQUEST_URI for current page
     * Magento default analytics reports can include the same page as
     * /checkout/onepage/index/ and   /checkout/onepage/
     * filter out index/ here and unify to no trailing /
     *
     * @return string
     */
    public function getPageName()
    {
        if (!$this->hasData('page_name')) {
            $parts = parse_url(
                Mage::getSingleton('core/url')->escape(
                    Mage::app()->getRequest()->getServer('REQUEST_URI')
                )
            );
            $query = '';
            if (isset($parts['query']) && !empty($parts['query'])) {
                $query = '?' . $parts['query'];
            }

            $url = Mage::getSingleton('core/url')->escape(
                rtrim(
                    str_replace(
                        'index/', '',
                        Mage::app()->getRequest()->getBaseUrl() . Mage::app()->getRequest()->getRequestString()
                    ), '/'
                ). $query
            );
            $this->setPageName($url);
        }
        return $this->getData('page_name');
    }

    public function getBasePageName()
    {
        return Mage::getSingleton('core/url')->escape(
            rtrim(
                str_replace(
                    'index/', '',
                    Mage::app()->getRequest()->getBaseUrl() . Mage::app()->getRequest()->getRequestString()
                ), '/'
            )
        );
    }

    public function getPageQuery()
    {
        $parts = parse_url($this->getPageName());
        $query = '';
        if (isset($parts['query']) && !empty($parts['query'])) {
            $query = '?' . $parts['query'];
        }
        return $query;
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
     * get Google Analytics universal account id
     *
     * @return mixed
     */
    public function getUniversalAccount()
    {
        return Mage::getStoreConfig('google/analyticsplus_universal/accountnumber');
    }

    /**
     * get Google Analytics universal account id for alternative profile
     *
     * @return mixed
     */
    public function getAlternativeUniversalAccount()
    {
        return Mage::getStoreConfig('google/analyticsplus_universal/altaccountnumber');
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
     * are we using tag manager
     *
     * @return bool
     */
    public function isTagManagerEnabled()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus_tagmanager/enabled');
    }
}
