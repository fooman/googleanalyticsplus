<?php

class Fooman_GoogleAnalyticsPlus_Model_Observer
{
    public function addCheckoutStepTracking($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $transport = $observer->getEvent()->getTransport();
        if ($block instanceof Mage_Checkout_Block_Onepage_Billing) {
            $origBlockContent = $transport->getHtml();
            if (strpos($origBlockContent, 'FoomanGoogleAnalytics') === false) {
                $trackingJs = $block->getLayout()->createBlock('googleanalyticsplus/ajax')->toHtml();
                $transport->setHtml($trackingJs . $origBlockContent);
            }

        }
    }

    public function setOrder($observer)
    {
        Mage::register('googleanalyticsplus_order_ids', $observer->getEvent()->getOrderIds(), true);
    }

    public function checkoutCartAdd($observer)
    {
        $product = Mage::getModel('catalog/product')
                            ->load(Mage::app()->getRequest()->getParam('product', 0));

        if (!$product->getId())
        {
            return false;
        }

        Mage::getModel('core/session')->setProductIdAddedToCart( $product->getId() );
    }

    public function customerLogin($observer)
    {
        $customer = $observer->getCustomer();

        if ($customer) {
            Mage::getModel('core/session')->setCustomerIdLoggedIn( $customer->getId() );
        }
    }

    public function customerRegistration($observer)
    {
        $customer = $observer->getCustomer();

        if ($customer) {
            Mage::getModel('core/session')->setCustomerIdRegistered( $customer->getId() );
        }
    }
}
