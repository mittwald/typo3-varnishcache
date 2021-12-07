<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(function () {
    ExtensionManagementUtility::allowTableOnStandardPages('tx_varnishcache_domain_model_server');
    ExtensionManagementUtility::allowTableOnStandardPages('tx_varnishcache_domain_model_sysdomain');
});
