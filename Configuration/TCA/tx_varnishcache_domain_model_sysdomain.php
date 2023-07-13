<?php

defined('TYPO3') or die();

$languageFile = 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:';
$generalLanguageFile = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => $languageFile . 'tx_varnishcache_domain_model_sysdomain.label',
        'label' => 'domain_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'rootLevel' => -1,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'domain_name,',
        'typeicon_classes' => [
            'default' => 'apps-pagetree-page-domain',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                    domain_name,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, 
                        hidden, --palette--;;startstop',
        ],
    ],
    'palettes' => [
        'startstop' => ['showitem' => 'starttime, endtime'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => $generalLanguageFile . 'LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => false,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => $generalLanguageFile . 'LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => $generalLanguageFile . 'LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'domain_name' => [
            'exclude' => 0,
            'label' => $languageFile . 'tx_varnishcache_domain_model_sysdomain.domain_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'required' => true,
            ],
        ],
        'servers' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
