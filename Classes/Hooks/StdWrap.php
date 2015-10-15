<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Kevin Purrmann <entwicklung@purrmann-websolutions.de>
 *
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

namespace Mittwald\Varnishcache\Hooks;

use Mittwald\Varnishcache\Service\TyposcriptPluginSettingsService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;


/**
 * Class StdWrap
 * @package Mittwald\Varnishcache\Hooks
 */
class StdWrap extends AbstractHook {

    /**
     * @var ContentObjectRenderer
     */
    public $cObj;


    /**
     * @var TyposcriptPluginSettingsService
     */
    protected $typoscriptPluginSettingsService;


    /**
     * @param string $content
     * @param array $params
     * @return string
     */
    public function addEsiTags($content, $params) {


        $typoScriptConfig = $this->getTyposcriptPluginSettingsService()->getConfiguration();

        if ($this->cObj->data['exclude_from_cache'] && $GLOBALS['TSFE']->type != $typoScriptConfig['typeNum']) {
            $link = $this->cObj->typoLink_URL(array(
                    'parameter' => $GLOBALS['TSFE']->id,
                    'additionalParams' => '&element=' . $this->cObj->data['uid'] . '&type=' . $typoScriptConfig['typeNum'],

            ));
            $content = '<!--esi <esi:include src="' . $link . '" />-->';

            if (($cUid = $this->cObj->data['alternative_content'])) {
                $cConf = array(
                        'tables' => 'tt_content',
                        'source' => $cUid,
                        'no_cache' => 1,
                        'dontCheckPid' => 1,
                );
                $content .= '<esi:remove>' . $this->cObj->cObjGetSingle('RECORDS', $cConf) . '</esi:remove>';
            }
        }

        return $content;
    }

    /**
     * @return TyposcriptPluginSettingsService
     */
    public function getTyposcriptPluginSettingsService() {
        if (is_null($this->typoscriptPluginSettingsService)) {
            try {
                $this->typoscriptPluginSettingsService = $this->objectManager->get('Mittwald\Varnishcache\Service\TyposcriptPluginSettingsService');
            } catch (\Exception $e) {
                echo 'TyposcriptPluginService could not be initialised: ' . $e->getCode() . $e->getMessage();
            }
        }
        return $this->typoscriptPluginSettingsService;
    }


}
