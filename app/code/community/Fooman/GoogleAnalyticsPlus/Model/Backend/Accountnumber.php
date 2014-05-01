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

class Fooman_GoogleAnalyticsPlus_Model_Backend_Accountnumber extends Mage_Core_Model_Config_Data
{

    protected function _beforeSave()
    {
        $data = $this->getData();
        $analyticsAccount = $data['groups']['analytics']['fields']['account']['value'];
        $analyticsPlusAccount = $data['groups']['analyticsplus_classic']['fields']['accountnumber2']['value'];
        if (!empty($analyticsPlusAccount) && !empty($analyticsAccount) && $analyticsAccount == $analyticsPlusAccount) {
            throw Mage::exception(
                'Mage_Core', Mage::helper('adminhtml')->__(
                    'Your alternative account number needs to be different to your existing account number.'
                )
            );
        }
        return true;
    }

}
