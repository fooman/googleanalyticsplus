<?php

class Fooman_GoogleAnalyticsPlus_Model_Observer
{
    public function addCheckoutStepTracking($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $transport = $observer->getEvent()->getTransport();
        if ($block instanceof Mage_Checkout_Block_Onepage_Billing) {
            $origBlockContent = $transport->getHtml();
            $trackingJs = $block->getLayout()->createBlock('googleanalyticsplus/ajax')->toHtml();
            $transport->setHtml($trackingJs . $origBlockContent);
        }
    }

    public function setOrder($observer)
    {
        Mage::register('googleanalyticsplus_order_ids', $observer->getEvent()->getOrderIds(), true);
    }
}
