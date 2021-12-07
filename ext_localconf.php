<?php

defined('TYPO3') or die();

use Mittwald\Varnishcache\Frontend\ContentObject\ContentObjectArrayInternalContentObject;

call_user_func(function () {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects']['COA_INT'] = ContentObjectArrayInternalContentObject::class;

    // On save page
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['varnishcache'] =
        \Mittwald\Varnishcache\Hooks\Cache::class . '->clearCachePostProc';

    // On flush cache
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearPageCacheEval']['varnishcache'] =
        \Mittwald\Varnishcache\Hooks\Cache::class . '->clearCachePostProc';
});
