<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$tempColumns = [
    'exclude_from_cache' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:tt_content.exclude_from_cache',
        'config' => [
            'type' => 'check',
        ],
    ],
    'alternative_content' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:tt_content.alternative_content',
        'config' => [
            'type' => 'group',
            'allowed' => 'tt_content',
            'default' => 0,
            'size' => 1,
        ],
    ],

];

ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Caching,exclude_from_cache, alternative_content'
);
