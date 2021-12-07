<?php

/****************************************************************
 *  Copyright notice
 *
 *  (C) Mittwald CM Service GmbH & Co. KG <opensource@mittwald.de>
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
 ***************************************************************/

namespace Mittwald\Varnishcache\Hooks;

use Mittwald\Varnishcache\Service\VarnishCacheService;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Cache
{
    protected ?VarnishCacheService $varnishCacheService = null;

    /**
     * Hook for clearing all frontend cache and single cache and even if an tt_content object has changed
     *
     * @param array $params
     * @param DataHandler $dataHandler
     */
    public function clearCachePostProc(array &$params, DataHandler $dataHandler)
    {
        if (isset($params['cacheCmd']) && $params['cacheCmd'] === 'pages') {
            $this->getVarnishCacheService()->flushCache(0);
            return;
        }

        if (isset($params['table']) &&
            ($params['table'] === 'pages' || $params['table'] === 'tt_content' || isset($params['cacheCmd'])) &&
            isset($params['pageIdArray']) && is_array($params['pageIdArray']) && !empty($params['pageIdArray'])
        ) {
            foreach ($params['pageIdArray'] as $pageId) {
                $this->getVarnishCacheService()->flushCache($pageId);
            }
        }
    }

    public function getVarnishCacheService(): VarnishCacheService
    {
        if (is_null($this->varnishCacheService)) {
            $this->varnishCacheService = GeneralUtility::makeInstance(VarnishCacheService::class);
        }
        return $this->varnishCacheService;
    }
}
