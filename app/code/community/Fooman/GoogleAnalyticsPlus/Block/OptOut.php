<?php
/**
 * Fooman GoogleAnalyticsPlus
 *
 * @package   Fooman_GoogleAnalyticsPlus
 * @author    Kristof Ringleff <kristof@fooman.co.nz>
 * @author    Niklas Wolf <wolf[ät]mothership.de>
 * @copyright Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Fooman_GoogleAnalyticsPlus_Block_OptOut extends Fooman_GoogleAnalyticsPlus_Block_Common_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/optout.phtml');
    }

    /**
     * should opt out code be included
     * @return bool
     */
    public function shouldIncludeOptOut()
    {
        return Mage::getStoreConfigFlag('google/analyticsplus/enableoptoutcookie');

    }
}
