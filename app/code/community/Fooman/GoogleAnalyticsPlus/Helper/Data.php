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
        if (!Mage::getStoreConfig('google/analyticsplus/convertcurrencyenabled')) {
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
                        Mage::getStoreConfig('google/analyticsplus/convertcurrency')
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
        if (!Mage::getStoreConfig('google/analyticsplus/convertcurrencyenabled')) {
            return $object->getBaseCurrencyCode();
        } else {
            return Mage::getStoreConfig('google/analyticsplus/convertcurrency');
        }
    }

}
