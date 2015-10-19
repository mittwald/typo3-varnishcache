<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Kevin Purrmann <entwicklung@purrmann-websolutions.de>
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

namespace Mittwald\Varnishcache\Service;


use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class EsiTagService {

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObjectRenderer;

    /**
     * @var \Mittwald\Varnishcache\Service\TyposcriptPluginSettingsService
     * @inject
     */
    protected $typoscriptPluginSettingsService;

    /**
     * @var \Mittwald\Varnishcache\Service\TsConfigService
     * @inject
     */
    protected $tsConfigService;

    /**
     * @param $content
     * @param ContentObjectRenderer $contentObjectRenderer
     * @return string
     */
    public function render($content, ContentObjectRenderer $contentObjectRenderer) {

        if (!($this->tsConfigService->isEsiAllowed($GLOBALS['TSFE']->id))) {
            return $content;
        }

        $this->contentObjectRenderer = $contentObjectRenderer;
        $typoScriptConfig = $this->typoscriptPluginSettingsService->getConfiguration();

        if ($this->isIntObject($content)) {
            $link = $this->contentObjectRenderer->typoLink_URL(array(
                    'parameter' => $GLOBALS['TSFE']->id,
                    'forceAbsoluteUrl' => 1,
                    'additionalParams' => '&type=' . $typoScriptConfig['typeNum']
                            . '&identifier=' . $GLOBALS['TSFE']->newHash
                            . '&key=' . $this->getKey($content)
                            . '&varnish=1',

            ));
            $content = $this->wrapEsiTag($link);
        } elseif ($this->contentObjectRenderer->data['exclude_from_cache'] && $GLOBALS['TSFE']->type != $typoScriptConfig['typeNum']) {
            $link = $this->contentObjectRenderer->typoLink_URL(array(
                    'parameter' => $GLOBALS['TSFE']->id,
                    'forceAbsoluteUrl' => 1,
                    'additionalParams' => '&element=' . $this->contentObjectRenderer->data['uid']
                            . '&type=' . $typoScriptConfig['typeNum']
                            . '&varnish=1',

            ));
            $content = $this->wrapEsiTag($link);

            if (($cUid = $this->contentObjectRenderer->data['alternative_content'])) {
                $cConf = array(
                        'tables' => 'tt_content',
                        'source' => $cUid,
                        'dontCheckPid' => 1,
                );
                $content .= '<esi:remove>' . $this->contentObjectRenderer->cObjGetSingle('RECORDS', $cConf) . '</esi:remove>';
            }
        }

        return $content;
    }

    /**
     * @param $content
     * @return string
     */
    protected function getKey($content) {
        return $substKey = str_replace(array('<!--', '-->'), '', $content);
    }

    /**
     * @param $content
     * @return string
     */
    protected function wrapEsiTag($content) {
        return '<!--esi <esi:include src="' . $content . '" />-->';
    }

    /**
     * @param $content
     * @return bool
     */
    protected function isIntObject($content) {
        return (boolean)preg_match('/INT_SCRIPT/', $content);
    }

}
