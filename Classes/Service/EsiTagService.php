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

namespace Mittwald\Varnishcache\Service;

use Mittwald\Varnishcache\Utility\HmacUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class EsiTagService
{
    protected ContentObjectRenderer $contentObjectRenderer;
    protected TyposcriptPluginSettingsService $typoscriptPluginSettingsService;

    public function __construct(
        ContentObjectRenderer $contentObjectRenderer,
        TyposcriptPluginSettingsService $typoscriptPluginSettingsService
    ) {
        $this->contentObjectRenderer = $contentObjectRenderer;
        $this->typoscriptPluginSettingsService = $typoscriptPluginSettingsService;
    }

    /**
     * Returns the ESI tag
     */
    public function render(string $content, ContentObjectRenderer $contentObjectRenderer): string
    {
        $this->contentObjectRenderer = $contentObjectRenderer;
        $typoScriptConfig = $this->typoscriptPluginSettingsService->getConfiguration();

        $isIntObject = $this->isIntObject($content);
        $excludeFromCache = $this->contentObjectRenderer->data['exclude_from_cache'] ?? false;
        if ($isIntObject && $excludeFromCache) {
            // If we have an INT object and ESI is turned on, return URL
            $key = $this->getKey($content);
            $link = $this->contentObjectRenderer->typoLink_URL([
                'parameter' => $this->getTypoScriptFrontendController()->id,
                'forceAbsoluteUrl' => 1,
                'additionalParams' => '&type=' . $typoScriptConfig['typeNum']
                    . '&identifier=' . $this->getTypoScriptFrontendController()->newHash
                    . '&key=' . $this->getKey($content)
                    . '&hmac=' . HmacUtility::hmac(json_encode([$key, $this->getTypoScriptFrontendController()->newHash]))
                    . '&varnish=1',

            ]);
            $content = $this->wrapEsiTag($link);
        } elseif ($excludeFromCache &&
            (int)$this->getTypoScriptFrontendController()->getPageArguments()->getPageType() !== (int)($typoScriptConfig['typeNum'] ?? 0)
        ) {
            // Only, if no INT object and ESI is turned on
            $link = $this->contentObjectRenderer->typoLink_URL([
                'parameter' => $this->getTypoScriptFrontendController()->id,
                'forceAbsoluteUrl' => 1,
                'additionalParams' => '&element=' . $this->contentObjectRenderer->data['uid']
                    . '&type=' . ($typoScriptConfig['typeNum'] ?? 0)
                    . '&hmac=' . HmacUtility::hmac(json_encode([$this->contentObjectRenderer->data['uid'], $this->contentObjectRenderer->data['tstamp']]))
                    . '&varnish=1',

            ]);
            $content = $this->wrapEsiTag($link);

            if (($cUid = $this->contentObjectRenderer->data['alternative_content'])) {
                $cConf = [
                    'tables' => 'tt_content',
                    'source' => $cUid,
                    'dontCheckPid' => 1,
                ];
                $content .= '<esi:remove>' . $this->contentObjectRenderer->cObjGetSingle('RECORDS', $cConf) . '</esi:remove>';
            }
        }

        return $content;
    }

    protected function getKey(string $content): string
    {
        $content = str_replace(['<!--', '-->'], '###', $content);
        $matches = [];
        preg_match('/###INT_SCRIPT\.(.*)###/', $content, $matches);

        return $matches[1];
    }

    protected function wrapEsiTag(string $content): string
    {
        return '<!--esi <esi:include src="' . $content . '" />-->';
    }

    protected function isIntObject($content): bool
    {
        return str_contains($content, 'INT_SCRIPT');
    }

    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
