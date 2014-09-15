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
 * @author     Erik Dannenberg <erik.dannenberg@bbe-consulting.de>
 */
class Fooman_GoogleAnalyticsPlus_Block_OptOut extends Fooman_GoogleAnalyticsPlus_Block_Common_Abstract
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/optout.phtml');
    }

    /**
     * should we include opt out code
     *
     * @return bool
     */
    public function shouldIncludeOptOut()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus/enableoptoutcookie');
    }

    /**
     * get Google Analytics account id
     *
     * @return mixed
     */
    public function getAccountId()
    {
        if ($id = Mage::getStoreConfig(Mage_GoogleAnalytics_Helper_Data::XML_PATH_ACCOUNT)) {
            return $id;
        }
        if ($id = Mage::getStoreConfig('google/analyticsplus_universal/accountnumber')) {
            return $id;
        }
    }

}
