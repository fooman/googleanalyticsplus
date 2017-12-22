<?php


class Fooman_GoogleAnalyticsPlus_Block_TagManagerHead extends Fooman_GoogleAnalyticsPlus_Block_TagManager
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/tagmanager_head.phtml');
    }


    public function getTagManagerId()
    {
        return Mage::getStoreConfig('google/analyticsplus_tagmanager/tagmanagerid');
    }

}
