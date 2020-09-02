<?php

if (! defined('TYPO3_MODE')) {
    die('Access denied.');
}

return (static function () {
    $languageFile = 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:';
    $generalLanguageFile = 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:';

    return [
        'ctrl' => [
            'title' => $languageFile. 'tx_varnishcache_domain_model_sysdomain.label',
            'label' => 'domain_name',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'cruser_id' => 'cruser_id',
            'dividers2tabs' => true,
            'rootLevel' => -1,
            'delete' => 'deleted',
            'enablecolumns' => [
                'disabled' => 'hidden',
                'starttime' => 'starttime',
                'endtime' => 'endtime',
            ],
            'searchFields' => 'domain_name,',
            'typeicon_classes' => [
                'default' => 'apps-pagetree-page-domain'
            ]
        ],
        'types' => [
            '0' => [
                'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general, --palette--;;general,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, --palette--;;hidden, --palette--;;access,'
            ],
        ],
        'palettes' => [
            'general' => ['showitem' => 'domain_name'],
            'access' => ['showitem' => 'hidden, --linebreak--, starttime, endtime'],
        ],
        'columns' => [
            'hidden' => [
                'exclude' => 1,
                'label' => $generalLanguageFile . 'LGL.hidden',
                'config' => [
                    'type' => 'check',
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
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ],
                    'range' => [
                        'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
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
                    'behaviour' => [
                        'allowLanguageSynchronization' => true
                    ],
                    'range' => [
                        'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                    ],
                ],
            ],
            'domain_name' => [
                'exclude' => 0,
                'label' => $languageFile. 'tx_varnishcache_domain_model_sysdomain.domain_name',
                'config' => [
                    'type' => 'input',
                    'size' => 10,
                    'eval' => 'required'
                ]
            ],
            'servers' => [
                'config' => [
                    'type' => 'passthrough'
                ]
            ]
        ]
    ];
})();
