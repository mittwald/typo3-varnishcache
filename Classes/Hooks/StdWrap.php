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

namespace Mittwald\Varnishcache\Hooks;

use Mittwald\Varnishcache\Service\EsiTagService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;


/**
 * Class StdWrap
 * @package Mittwald\Varnishcache\Hooks
 */
class StdWrap extends AbstractHook
{

    /**
     * @var ContentObjectRenderer
     */
    public $cObj;


    /**
     * @var \Mittwald\Varnishcache\Service\EsiTagService
     */
    protected $esiTagService;

    /**
     * StdWrap constructor.
     * @param EsiTagService $esiTagService
     */
    public function __construct()
    {
        parent::__construct();
        $this->esiTagService = $this->objectManager->get(EsiTagService::class);
    }


    /**
     * @param string $content
     * @param array $params
     * @return string
     */
    public function addEsiTags($content, $params)
    {
        return $this->getEsiTagService()->render($content, $this->cObj);
    }

    /**
     * @return EsiTagService
     */
    public function getEsiTagService(): EsiTagService
    {
        return $this->esiTagService;
    }


}
