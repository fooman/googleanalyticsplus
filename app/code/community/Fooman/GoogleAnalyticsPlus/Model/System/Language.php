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
class Fooman_GoogleAnalyticsPlus_Model_System_Language
{

    public function toOptionArray()
    {
        $returnArray[] = array('value' => 'en', 'label' => Mage::helper('directory')->__('English'));
        $returnArray[] = array('value' => 'de', 'label' => Mage::helper('directory')->__('German'));
        $returnArray[] = array('value' => 'fr', 'label' => Mage::helper('directory')->__('French'));
        $returnArray[] = array('value' => 'es', 'label' => Mage::helper('directory')->__('Spanish'));
        $returnArray[] = array('value' => 'it', 'label' => Mage::helper('directory')->__('Italian'));
        $returnArray[] = array('value' => 'ja', 'label' => Mage::helper('directory')->__('Japanese'));
        $returnArray[] = array('value' => 'da', 'label' => Mage::helper('directory')->__('Danish'));
        $returnArray[] = array('value' => 'nl', 'label' => Mage::helper('directory')->__('Dutch'));
        $returnArray[] = array('value' => 'fi', 'label' => Mage::helper('directory')->__('Finnish'));
        $returnArray[] = array('value' => 'ko', 'label' => Mage::helper('directory')->__('Korean'));
        $returnArray[] = array('value' => 'no', 'label' => Mage::helper('directory')->__('Norwegian'));
        $returnArray[] = array('value' => 'pt', 'label' => Mage::helper('directory')->__('Portuguese'));
        $returnArray[] = array('value' => 'sv', 'label' => Mage::helper('directory')->__('Swedish'));
        $returnArray[] = array('value' => 'zh_CN', 'label' => Mage::helper('directory')->__('Chinese (simplified)'));
        $returnArray[] = array('value' => 'zh_TW', 'label' => Mage::helper('directory')->__('Chinese (traditional)'));
        $returnArray[] = array('value' => 'ar', 'label' => Mage::helper('directory')->__('Arabic'));
        $returnArray[] = array('value' => 'bg', 'label' => Mage::helper('directory')->__('Bulgarian'));
        $returnArray[] = array('value' => 'cs', 'label' => Mage::helper('directory')->__('Czech'));
        $returnArray[] = array('value' => 'el', 'label' => Mage::helper('directory')->__('Greek'));
        $returnArray[] = array('value' => 'hi', 'label' => Mage::helper('directory')->__('Hindi'));
        $returnArray[] = array('value' => 'hu', 'label' => Mage::helper('directory')->__('Hungarian'));
        $returnArray[] = array('value' => 'id', 'label' => Mage::helper('directory')->__('Indonesian'));
        $returnArray[] = array('value' => 'is', 'label' => Mage::helper('directory')->__('Icelandic'));
        $returnArray[] = array('value' => 'iw', 'label' => Mage::helper('directory')->__('Hebrew'));
        $returnArray[] = array('value' => 'lv', 'label' => Mage::helper('directory')->__('Latvian'));
        $returnArray[] = array('value' => 'lt', 'label' => Mage::helper('directory')->__('Lithuanian'));
        $returnArray[] = array('value' => 'pl', 'label' => Mage::helper('directory')->__('Polish'));
        $returnArray[] = array('value' => 'ru', 'label' => Mage::helper('directory')->__('Russian'));
        $returnArray[] = array('value' => 'ro', 'label' => Mage::helper('directory')->__('Romanian'));
        $returnArray[] = array('value' => 'sk', 'label' => Mage::helper('directory')->__('Slovak'));
        $returnArray[] = array('value' => 'sl', 'label' => Mage::helper('directory')->__('Slovenian'));
        $returnArray[] = array('value' => 'sr', 'label' => Mage::helper('directory')->__('Serbian'));
        $returnArray[] = array('value' => 'uk', 'label' => Mage::helper('directory')->__('Ukrainian'));
        $returnArray[] = array('value' => 'tr', 'label' => Mage::helper('directory')->__('Turkish'));
        $returnArray[] = array('value' => 'ca', 'label' => Mage::helper('directory')->__('Catalan'));
        $returnArray[] = array('value' => 'hr', 'label' => Mage::helper('directory')->__('Croatian'));
        $returnArray[] = array('value' => 'vi', 'label' => Mage::helper('directory')->__('Vietnamese'));
        $returnArray[] = array('value' => 'ur', 'label' => Mage::helper('directory')->__('Urdu'));
        $returnArray[] = array('value' => 'tl', 'label' => Mage::helper('directory')->__('Filipino'));
        $returnArray[] = array('value' => 'et', 'label' => Mage::helper('directory')->__('Estonian'));
        $returnArray[] = array('value' => 'th', 'label' => Mage::helper('directory')->__('Thai'));
        return $returnArray;
    }

}
