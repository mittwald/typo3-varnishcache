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

namespace Mittwald\Varnishcache\Frontend\ContentObject;

use Mittwald\Varnishcache\Service\EsiTagService;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;


/**
 * Class ContentObjectArrayInternalContentObject
 * @package Mittwald\Varnishcache\Frontend\ContentObject
 */
class ContentObjectArrayInternalContentObject extends \TYPO3\CMS\Frontend\ContentObject\ContentObjectArrayInternalContentObject
{

    /**
     * @var \Mittwald\Varnishcache\Service\EsiTagService
     */
    protected $esiTagService;

    public function __construct(ContentObjectRenderer $cObj, EsiTagService $esiTagService)
    {
        parent::__construct($cObj);
        $this->esiTagService = $esiTagService;
    }

    /**
     * @param array $conf
     * @return string
     */
    public function render($conf = array()): string
    {
        $content = parent::render($conf);

        if (! ($formVarnish = GeneralUtility::_GET('varnish'))) {
            $content = $this->getEsiTagService()->render($content, $this->getContentObjectRenderer());
        }

        return $content;
    }

    /**
     * @return EsiTagService
     */
    protected function getEsiTagService(): EsiTagService
    {
        return $this->esiTagService;
    }
}
