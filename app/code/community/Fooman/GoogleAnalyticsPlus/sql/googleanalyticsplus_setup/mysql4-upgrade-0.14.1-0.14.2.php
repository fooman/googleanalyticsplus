<?php
$installer = $this;
$installer->startSetup();

//migrate old config values to new paths
$oldToNewMap = array(
    'domainname'=>'domainname',
    'accountnumber2'=>'accountnumber2',
    'domainname2'=>'domainname2',
    'anonymise'=>'anonymise',
    'remarketing'=>'remarketing',
    'conversionenabled'=>'conversionenabled',
    'conversionid'=>'conversionid',
    'conversionlanguage'=>'conversionlanguage',
    'conversionlabel'=>'conversionlabel'
);

foreach ($oldToNewMap as $old => $new) {
    $installer->run(
        "UPDATE IGNORE {$this->getTable('core_config_data')}
        SET `path`='google/analyticsplus_classic/{$new}' WHERE `path`='google/analyticsplus/{$old}';"
    );
}

$installer->endSetup();
