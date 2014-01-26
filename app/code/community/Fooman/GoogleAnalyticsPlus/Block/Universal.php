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

class Fooman_GoogleAnalyticsPlus_Block_Universal extends Fooman_GoogleAnalyticsPlus_Block_GaConversion
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/universal.phtml');
    }

    /**
     * should we include the universal snippet
     *
     * @return bool
     */
    public function shouldInclude()
    {
        if (parent::shouldInclude()) {
            return $this->isUniversalEnabled() && (bool)$this->getUniversalSnippet();
        } else {
            return false;
        }
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
     * get universal snippet from settings
     *
     * @return string
     */
    public function getUniversalSnippet()
    {
        return Mage::getStoreConfig('google/analyticsplus_universal/snippet');
    }
}
