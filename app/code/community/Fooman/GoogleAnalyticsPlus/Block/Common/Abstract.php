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
        $attributeCode = Mage::getStoreConfig(
            Fooman_GoogleAnalyticsPlus_Helper_Data::XML_PATH_GOOGLEANALYTICSPLUS_SETTINGS
            . 'categorytrackingattribute'
        );
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
}
