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
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class EsiTagService
{
    public function __construct(
        protected readonly TyposcriptPluginSettingsService $typoscriptPluginSettingsService
    ) {}

    /**
     * Returns the ESI tag
     */
    public function render(string $content, ContentObjectRenderer $contentObjectRenderer): string
    {
        $typoScriptConfig = $this->typoscriptPluginSettingsService->getConfiguration();

        $request = $contentObjectRenderer->getRequest();

        // Only GET requests are cached by varnish
        if ($request->getMethod() !== 'GET') {
            return $content;
        }

        /** @var PageArguments $routing */
        $routing = $request->getAttribute('routing');

        // The deprecated TypoScriptFrontendController is used on intention, since this is currently
        // the only possibility to determine the `newHash` variable (= page cache identifier) in the
        // rendering process. @todo: evaluate alternative method in v14
        /** @var TypoScriptFrontendController $frontendController */
        $frontendController = $request->getAttribute('frontend.controller');

        $isIntObject = $this->isIntObject($content);
        $excludeFromCache = $contentObjectRenderer->data['exclude_from_cache'] ?? false;
        if ($isIntObject && $excludeFromCache) {
            // If we have an INT object and ESI is turned on, return URL
            $key = $this->getKey($content);
            $link = $contentObjectRenderer->typoLink_URL([
                'parameter' => $routing->getPageId(),
                'forceAbsoluteUrl' => 1,
                'additionalParams' => '&type=' . $typoScriptConfig['typeNum']
                    . '&identifier=' . $frontendController->newHash
                    . '&key=' . $key
                    . '&hmac=' . HmacUtility::hmac(json_encode([$key, $frontendController->newHash]))
                    . '&varnish=1',

            ]);
            $content = $this->wrapEsiTag($link);
        } elseif ($excludeFromCache &&
            (int)$routing->getPageType() !== (int)($typoScriptConfig['typeNum'] ?? 0)
        ) {
            // Only, if no INT object and ESI is turned on
            $link = $contentObjectRenderer->typoLink_URL([
                'parameter' => $routing->getPageId(),
                'forceAbsoluteUrl' => 1,
                'additionalParams' => '&element=' . $contentObjectRenderer->data['uid']
                    . '&type=' . ($typoScriptConfig['typeNum'] ?? 0)
                    . '&hmac=' . HmacUtility::hmac(json_encode([$contentObjectRenderer->data['uid'], $contentObjectRenderer->data['tstamp']]))
                    . '&varnish=1',

            ]);
            $content = $this->wrapEsiTag($link);

            if (($cUid = $contentObjectRenderer->data['alternative_content'])) {
                $cConf = [
                    'tables' => 'tt_content',
                    'source' => $cUid,
                    'dontCheckPid' => 1,
                ];
                $content .= '<esi:remove>' . $contentObjectRenderer->cObjGetSingle('RECORDS', $cConf) . '</esi:remove>';
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
}
