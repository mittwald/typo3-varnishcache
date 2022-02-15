<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (C) 2015 Mittwald CM Service GmbH & Co. KG <opensource@mittwald.de>
 *
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

namespace Mittwald\Varnishcache\Renderer;


use Mittwald\Varnishcache\Utility\HmacUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ContentElement {

    /**
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     * @return string
     */
    public function render() {
        $hmac = (string)GeneralUtility::_GET('hmac') ?? '';
        if ($hmac === '') {
            return '';
        }

        if (($identifier = GeneralUtility::_GET('identifier')) &&
            ($key = GeneralUtility::_GET('key')) &&
            hash_equals(HmacUtility::hmac(json_encode([$key, $identifier])), $hmac)
        ) {
            if ($row = $this->getCacheManager()->get($identifier)) {
                /* @var $INTiS_cObj ContentObjectRenderer */
                $key = 'INT_SCRIPT.' . $key;
                $INTiS_cObj = unserialize($row['cache_data']['INTincScript'][$key]['cObj']);
                $INTiS_cObj->INT_include = 1;
                return $INTiS_cObj->cObjGetSingle('USER_INT', $row['cache_data']['INTincScript'][$key]['conf']);
            }
        }

        if (!($cUid = GeneralUtility::_GET('element'))) {
            return '';
        }

        $contentElement = BackendUtility::getRecord('tt_content', $cUid);
        if (!hash_equals(HmacUtility::hmac(json_encode([$cUid, $contentElement['tstamp']])), $hmac)) {
            return '';
        }

        $configArray = array(
                'tables' => 'tt_content',
                'source' => $cUid,
                'dontCheckPid' => 1,
        );

        return $this->cObj->cObjGetSingle('RECORDS', $configArray);

    }

    /**
     * @return VariableFrontend
     */
    protected function getCacheManager() {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('cache_pages');
    }
}
