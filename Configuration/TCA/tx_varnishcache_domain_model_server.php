<?php

defined('TYPO3') or die();

return (static function () {
    $languageFile = 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:';
    $generalLanguageFile = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:';

    return [
        'ctrl' => [
            'title' => $languageFile . 'tx_varnishcache_domain_model_server.label',
            'label' => 'ip',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
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
                'exclude' => 1,
                'label' => $generalLanguageFile . 'LGL.hidden',
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'default' => 0,
                    'items' => [
                        [
                            0 => '',
                            1 => '',
                        ],
                    ],
                ],
            ],
            'starttime' => [
                'exclude' => 1,
                'label' => $generalLanguageFile . 'LGL.starttime',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputDateTime',
                    'size' => 13,
                    'eval' => 'datetime',
                    'checkbox' => 0,
                    'default' => 0,
                    'range' => [
                        'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                    ],
                ],
            ],
            'endtime' => [
                'exclude' => 1,
                'label' => $generalLanguageFile . 'LGL.endtime',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputDateTime',
                    'size' => 13,
                    'eval' => 'datetime',
                    'checkbox' => 0,
                    'default' => 0,
                    'range' => [
                        'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                    ],
                ],
            ],
            'ip' => [
                'exclude' => 1,
                'label' => $languageFile . 'tx_varnishcache_domain_model_server.ip',
                'config' => [
                    'type' => 'input',
                    'size' => 255,
                    'eval' => 'trim, required',
                ],
            ],
            'port' => [
                'exclude' => 1,
                'label' => $languageFile . 'tx_varnishcache_domain_model_server.port',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'default' => 80,
                    'eval' => 'trim,int',
                ],
            ],
            'method' => [
                'exclude' => 1,
                'label' => $languageFile . 'tx_varnishcache_domain_model_server.method',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'default' => 'BAN',
                    'eval' => 'trim, required',
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
                        ['HTTP 1.0', '1'],
                        ['HTTP 1.1', '2'],
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
                            0 => '',
                            1 => '',
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
})();
