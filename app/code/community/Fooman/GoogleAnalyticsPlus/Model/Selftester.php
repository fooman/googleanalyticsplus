<?php

class Fooman_GoogleAnalyticsPlus_Model_Selftester extends Fooman_Common_Model_Selftester
{

    const MANUAL_URL = 'https://store.fooman.co.nz/to/GoogleAnalyticsPlus/manual';

    public function getManualLink()
    {
        return sprintf('<a href="%s">%s</a>', self::MANUAL_URL, 'Please read the manual for details.');
    }

    public function _getFiles()
    {
        //REPLACE
        return array(
            'app/design/frontend/base/default/layout/googleanalyticsplus.xml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/tagmanager_head.phtml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/tagmanager.phtml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/ajax-tracking.phtml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/optout.phtml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/universal.phtml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/ga-conversion.phtml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/universal-send.phtml',
            'app/design/frontend/base/default/template/fooman/googleanalyticsplus/remarketing.phtml',
            'app/etc/modules/Fooman_GoogleAnalyticsPlus.xml',
            'app/code/community/Fooman/GoogleAnalyticsPlus/etc/system.xml',
            'app/code/community/Fooman/GoogleAnalyticsPlus/etc/config.xml',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Model/Source/Productattribute.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Model/Backend/Accountnumber.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Model/Selftester.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Model/Observer.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Model/System/Dimension.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Model/System/Language.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/LICENSE.txt',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Helper/Data.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/Remarketing.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/GaConversion.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/Ajax.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/OptOut.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/Universal.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/TagManagerHead.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/TagManager.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/Block/Adminhtml/Extensioninfo.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/sql/googleanalyticsplus_setup/mysql4-install-0.0.1.php',
            'app/code/community/Fooman/GoogleAnalyticsPlus/sql/googleanalyticsplus_setup/mysql4-upgrade-0.14.1-0.14.2.php',
        );
        //REPLACE_END
    }

}
