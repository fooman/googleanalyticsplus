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

class Fooman_GoogleAnalyticsPlus_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_PATH_GOOGLEANALYTICSPLUS_SETTINGS = 'google/analyticsplus/';

    /**
     * Return store config value for key
     *
     * @param   string $key
     *
     * @param bool     $flag
     *
     * @return  string
     */
    public function getGoogleanalyticsplusStoreConfig($key, $flag = false)
    {
        $path = self::XML_PATH_GOOGLEANALYTICSPLUS_SETTINGS . $key;
        if ($flag) {
            return Mage::getStoreConfigFlag($path);
        } else {
            return Mage::getStoreConfig($path);
        }
    }

    /**
     * retrieve requested value from order or item
     * convert from base currency if configured
     * else return order currency
     *
     * @param      $object
     * @param      $field
     *
     * @return string
     */
    public function convert($object, $field)
    {
        if (!Mage::helper('googleanalyticsplus')->getGoogleanalyticsplusStoreConfig('convertcurrencyenabled')) {
            return $object->getDataUsingMethod($field);
        }
        //getPrice and getFinalPrice do not have equivalents
        if ($field != 'price' && $field != 'final_price') {
            $field = 'base_' . $field;
        }
        $basecur = Mage::app()->getStore($object->getStoreId())->getBaseCurrency();
        if ($basecur) {
            return sprintf(
                "%01.4f", Mage::app()->getStore()->roundPrice(
                    $basecur->convert(
                        $object->getDataUsingMethod($field),
                        Mage::helper('googleanalyticsplus')->getGoogleanalyticsplusStoreConfig('convertcurrency')
                    )
                )
            );
        } else {
            //unable to load base currency return zero
            return '0.0000';
        }
    }

    /**
     * currency for tracking
     *
     * @param $object
     *
     * @return string
     */
    public function getTrackingCurrency($object)
    {
        if (!Mage::helper('googleanalyticsplus')->getGoogleanalyticsplusStoreConfig('convertcurrencyenabled')) {
            return $object->getBaseCurrencyCode();
        } else {
            return Mage::helper('googleanalyticsplus')->getGoogleanalyticsplusStoreConfig('convertcurrency');
        }
    }

}
