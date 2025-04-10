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

namespace Mittwald\Varnishcache\Renderer;

use Mittwald\Varnishcache\Utility\HmacUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ContentElement
{
    protected ContentObjectRenderer $cObj;

    public function setContentObjectRenderer(ContentObjectRenderer $cObj): void
    {
        $this->cObj = $cObj;
    }

    public function render(): string
    {
        $hmac = (string)($this->cObj->getRequest()->getQueryParams()['hmac'] ?? '');
        if ($hmac === '') {
            return '';
        }

        $identifier = $this->cObj->getRequest()->getQueryParams()['identifier'] ?? null;
        $key = $this->cObj->getRequest()->getQueryParams()['key'] ?? null;

        if ($identifier &&
            $key &&
            hash_equals(HmacUtility::hmac(json_encode([$key, $identifier])), $hmac)
        ) {
            $row = $this->getCacheManager()->get($identifier);
            if ($row) {
                /* @var $INTiS_cObj ContentObjectRenderer */
                $key = 'INT_SCRIPT.' . $key;
                $INTiS_cObj = unserialize($row['INTincScript'][$key]['cObj']);
                if (!$INTiS_cObj) {
                    return '';
                }

                $INTiS_cObj->INT_include = 1;
                return $INTiS_cObj->cObjGetSingle('USER_INT', $row['INTincScript'][$key]['conf']);
            }
        }

        if (!($cUid = $this->cObj->getRequest()->getQueryParams()['element'] ?? null)) {
            return '';
        }

        $contentElement = BackendUtility::getRecord('tt_content', $cUid);
        if (!$contentElement ||
            !hash_equals(HmacUtility::hmac(json_encode([(int)$cUid, $contentElement['tstamp']])), $hmac)
        ) {
            return '';
        }

        $configArray = [
            'tables' => 'tt_content',
            'source' => $cUid,
            'dontCheckPid' => 1,
        ];

        return $this->cObj->cObjGetSingle('RECORDS', $configArray);
    }

    protected function getCacheManager(): FrontendInterface
    {
        return GeneralUtility::makeInstance(CacheManager::class)->getCache('pages');
    }
}
