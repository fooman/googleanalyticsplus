<?php

/**
 * Fooman GoogleAnalyticsPlus
 *
 * @package   Fooman_GoogleAnalyticsPlus
 * @author    Kristof Ringleff <kristof@fooman.co.nz>
 * @author    Lucas van Staden (sales@proxiblue.com.au)
 * @copyright Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Fooman_GoogleAnalyticsPlus_Block_UserTracking extends Fooman_GoogleAnalyticsPlus_Block_Universal {

	protected function _construct() {
		parent::_construct();
		$this->setTemplate('ooman/googleanalyticsplus/usertracking.phtml');
	}

	/**
	 * should we include the universal snippet
	 *
	 * @return bool
	 */
	public function shouldInclude() {
		return ($this->isEnabled() && parent::shouldInclude() && Mage::getSingleton('customer/session')->isLoggedIn()) ? true : false;
	}

	/**
	 * Get the current logged in customer id
	 *
	 * @return mixed bool | integer
	 */
	public function getCustomerId() {
		if (Mage::getSingleton('customer/session')->isLoggedIn()) {
			return Mage::getSingleton('customer/session')->getCustomer()->getId();
		}
		return false;
	}

	/**
	 * are we using universal
	 *
	 * @return bool
	 */
	public function isEnabled() {
		return Mage::getStoreConfigFlag('google/analyticsplus_universal/userid_tracking');
	}

}
