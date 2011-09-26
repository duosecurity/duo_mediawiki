<?php

$wgExtensionCredits['specialpage'][] = array(
        'name' => 'DuoAuth',
        'author' => 'Steve Buck',
        'url' => 'http://duosecurity.com',
        'description' => 'Provides Duo Authentication for the wiki.',
        'descriptionmsg' => 'duo-auth-desc',
        'version' => '0.9.2',
);

$dir = dirname(__FILE__) . '/';
 
$wgAutoloadClasses['SpecialDuoAuth'] = $dir . 'SpecialDuoAuth.php'; # Location of the SpecialMyExtension class (Tell MediaWiki to load this file)
$wgExtensionMessagesFiles['DuoAuth'] = $dir . 'DuoAuth.i18n.php'; # Location of a messages file (Tell MediaWiki to load this file)
$wgSpecialPages['DuoAuth'] = 'SpecialDuoAuth'; # Tell MediaWiki about the new special page and its class name

$wgSpecialPageGroups['DuoAuth'] = 'other';
?>
