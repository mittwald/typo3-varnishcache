<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$languageFile = 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf';

$GLOBALS['TCA']['tx_varnishcache_domain_model_server'] = array(
        'ctrl' => $GLOBALS['TCA']['tx_varnishcache_domain_model_server']['ctrl'],
        'interface' => array(
                'showRecordFieldList' => ' hidden, ip, port, method, protocol, strip_slashes, domains',
        ),
        'types' => array(
                '1' => array(
                        'showitem' => 'hidden;;1,ip, port, method, protocol, strip_slashes, domains'
                ),
        ),
        'palettes' => array(
                '1' => array('showitem' => ''),
        ),
        'columns' => array(
                'hidden' => array(
                        'exclude' => 1,
                        'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
                        'config' => array(
                                'type' => 'check',
                        ),
                ),
                'starttime' => array(
                        'exclude' => 1,
                        'l10n_mode' => 'mergeIfNotBlank',
                        'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
                        'config' => array(
                                'type' => 'input',
                                'size' => 13,
                                'max' => 20,
                                'eval' => 'datetime',
                                'checkbox' => 0,
                                'default' => 0,
                                'range' => array(
                                        'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                                ),
                        ),
                ),
                'endtime' => array(
                        'exclude' => 1,
                        'l10n_mode' => 'mergeIfNotBlank',
                        'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
                        'config' => array(
                                'type' => 'input',
                                'size' => 13,
                                'max' => 20,
                                'eval' => 'datetime',
                                'checkbox' => 0,
                                'default' => 0,
                                'range' => array(
                                        'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                                ),
                        ),
                ),
                'ip' => array(
                        'exclude' => 1,
                        'label' => $languageFile . ':tx_varnishcache_domain_model_server.ip',
                        'config' => array(
                                'type' => 'input',
                                'size' => 30,
                                'eval' => 'trim, required'
                        ),
                ),
                'port' => array(
                        'exclude' => 1,
                        'label' => $languageFile . ':tx_varnishcache_domain_model_server.port',
                        'config' => array(
                                'type' => 'input',
                                'size' => 30,
                                'default' => 80,
                                'eval' => 'trim,int'
                        ),
                ),
                'method' => array(
                        'exclude' => 1,
                        'label' => $languageFile . ':tx_varnishcache_domain_model_server.method',
                        'config' => array(
                                'type' => 'input',
                                'size' => 30,
                                'default' => 'BAN',
                                'eval' => 'trim, required'
                        ),
                ),
                'protocol' => array(
                        'exclude' => 1,
                        'label' => $languageFile . ':tx_varnishcache_domain_model_server.protocol',
                        'config' => array(
                                'type' => 'select',
                                'size' => 1,
                                'minitems' => 1,
                                'items' => array(
                                        array('HTTP 1.0', '1'),
                                        array('HTTP 1.1', '2')
                                ),
                                'eval' => 'trim'
                        ),
                ),
                'strip_slahes' => array(
                        'label' => $languageFile . ':tx_varnishcache_domain_model_server.strip_slashes',
                        'config' => array(
                                'type' => 'check',
                        )
                ),
                'domains' => array(
                        'label' => $languageFile . ':tx_varnishcache_domain_model_server.domains',
                        'exclude' => 0,
                        'config' => array(
                                'type' => 'select',
                                'foreign_table' => 'sys_domain',
                                'MM' => 'tx_varnishcache_domain_model_server_sysdomain_mm',
                                'size' => 10,
                                'minitems' => 1,
                                'maxitems' => 99999,
                        )
                )
        ),
);
