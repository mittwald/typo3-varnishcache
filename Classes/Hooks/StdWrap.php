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

use Mittwald\Varnishcache\Service\EsiTagService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class StdWrap
{
    /**
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     * @var EsiTagService
     */
    protected $esiTagService;

    public function __construct()
    {
        $this->esiTagService = GeneralUtility::makeInstance(EsiTagService::class);
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
