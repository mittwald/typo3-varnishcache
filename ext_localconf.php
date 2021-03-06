<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (C) 2015 Mittwald CM Service GmbH & Co. KG <opensource@mittwald.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

if (! defined('TYPO3_MODE')) {
    die ('Access denied.');
}

(static function () {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects']['COA_INT'] = \Mittwald\Varnishcache\Frontend\ContentObject\ContentObjectArrayInternalContentObject::class;

    if (TYPO3_MODE === 'BE') {
        // On save page
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] =
            'Mittwald\Varnishcache\Hooks\Cache->clearCachePostProc';

        // On flush cache
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearPageCacheEval'][] =
            'Mittwald\Varnishcache\Hooks\Cache->clearCachePostProc';
    }
})();
