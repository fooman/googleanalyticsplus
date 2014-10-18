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

class Fooman_GoogleAnalyticsPlus_Block_TagManager extends Fooman_GoogleAnalyticsPlus_Block_Common_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('fooman/googleanalyticsplus/tagmanager.phtml');
    }

    /**
     * should we include the tag manager snippet
     *
     * @return bool
     */
    public function shouldInclude()
    {
        if (parent::shouldInclude()) {
            return $this->isTagManagerEnabled() && (bool)$this->getTagManagerSnippet();
        } else {
            return false;
        }
    }

    /**
     * get tag manager snippet from settings
     *
     * @return string
     */
    public function getTagManagerSnippet()
    {
        return Mage::getStoreConfig('google/analyticsplus_tagmanager/snippet');
    }
}
