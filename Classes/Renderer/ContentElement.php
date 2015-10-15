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

namespace Mittwald\Varnishcache\Renderer;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ContentElement {

    /**
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     * @param string $content
     * @param array $config
     * @return string|void
     */
    public function render($content, array $config) {

        if (!($cUid = GeneralUtility::_GET('element'))) {
            return '';
        }

        $configArray = array(
                'tables' => 'tt_content',
                'source' => $cUid,
                'dontCheckPid' => 1,
        );

        return $this->cObj->cObjGetSingle('RECORDS', $configArray);

    }
}
