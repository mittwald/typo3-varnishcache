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

namespace Mittwald\Varnishcache\Service;


use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Routing\PageRouter;
use TYPO3\CMS\Core\Routing\SiteRouteResult;
use TYPO3\CMS\Core\Routing\UrlGenerator;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class FrontendUrlGenerator
{
    /**
     * @var PageRouter
     */
    protected $pageRouter;
    /**
     * @var SiteFinder
     */
    private $siteFinder;
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;
    /**
     * @var UriBuilder
     */
    private $uriBuilder;

    /**
     * FrontendUrlGenerator constructor.
     * @param PageRouter $pageRouter
     */
    public function __construct(SiteFinder $siteFinder, UriBuilder $uriBuilder)
    {
        $this->siteFinder = $siteFinder;
    }

    /**
     * @param $uid
     * @return string
     */
    public function getFrontendUrl($uid): string
    {
        if ($this->isRootPage($uid)) {
            return '/';
        }
        $site = $this->getSite($uid);
        return (string)$site->getRouter()->generateUri($uid);
    }

    /**
     * @param $uid
     * @return Site
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public function getSite($uid): Site
    {
        return $this->siteFinder->getSiteByPageId($uid);
    }

    /**
     * @param $uid
     * @return bool
     */
    protected function isRootPage($uid): bool
    {
        $rootline = BackendUtility::BEgetRootLine($uid);
        if (is_array($rootline) && count($rootline) > 1) {
            return ($uid == $rootline[1]['uid']);
        }
        return false;
    }
}
