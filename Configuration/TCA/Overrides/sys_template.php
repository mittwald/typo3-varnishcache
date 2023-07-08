<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile(
    'varnishcache',
    'Configuration/TypoScript',
    'Varnishcache'
);
