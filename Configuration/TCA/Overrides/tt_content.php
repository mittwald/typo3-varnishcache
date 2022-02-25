<?php

defined('TYPO3') or die();

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
            'internal_type' => 'db',
            'allowed' => 'tt_content',
            'default' => 0,
            'size' => 1,
        ],
    ],

];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Caching,exclude_from_cache, alternative_content'
);
