<?php

defined('TYPO3') or die();

$languageFile = 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:';
$generalLanguageFile = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:';

return [
    'ctrl' => [
        'title' => $languageFile . 'tx_varnishcache_domain_model_server.label',
        'label' => 'ip',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'rootLevel' => -1,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,',
        'typeicon_classes' => [
            'default' => 'ext-varnishcache-server',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                    ip, port, method, protocol, strip_slashes, domains,
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
        'ip' => [
            'exclude' => 1,
            'label' => $languageFile . 'tx_varnishcache_domain_model_server.ip',
            'config' => [
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'port' => [
            'exclude' => 1,
            'label' => $languageFile . 'tx_varnishcache_domain_model_server.port',
            'config' => [
                'type' => 'number',
                'size' => 30,
                'default' => 80,
            ],
        ],
        'method' => [
            'exclude' => 1,
            'label' => $languageFile . 'tx_varnishcache_domain_model_server.method',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'default' => 'BAN',
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'protocol' => [
            'exclude' => 1,
            'label' => $languageFile . 'tx_varnishcache_domain_model_server.protocol',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'size' => 1,
                'minitems' => 1,
                'items' => [
                    ['label' => 'HTTP 1.0', 'value' => '1'],
                    ['label' => 'HTTP 1.1', 'value' => '2'],
                ],
                'eval' => 'trim',
            ],
        ],
        'strip_slashes' => [
            'label' => $languageFile . 'tx_varnishcache_domain_model_server.strip_slashes',
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
        'domains' => [
            'label' => $languageFile . 'tx_varnishcache_domain_model_server.domains',
            'exclude' => 0,
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_varnishcache_domain_model_sysdomain',
                'MM' => 'tx_varnishcache_domain_model_server_sysdomain_mm',
                'size' => 10,
                'minitems' => 1,
                'maxitems' => 99999,
            ],
        ],
    ],
];
