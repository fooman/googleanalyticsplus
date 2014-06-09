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

class Fooman_GoogleAnalyticsPlus_Model_Source_Productattribute
{

    public function toOptionArray()
    {
        $options = array();
        $collection = Mage::getResourceModel('catalog/product_attribute_collection');
        $options[] = array(
            'value' => '',
            'label' => ''
        );
        $options[] = array(
                'value' => 'entity_id',
                'label' => 'Entity Id'
        );
        foreach ($collection as $attribute) {
            $options[] = array(
                'value' => $attribute->getAttributeCode(),
                'label' => ($attribute->getFrontendLabel() ? $attribute->getFrontendLabel()
                        : $attribute->getAttributeCode())
            );
        }
        return $options;
    }

}
