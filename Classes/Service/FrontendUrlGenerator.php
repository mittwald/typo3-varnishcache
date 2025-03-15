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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;

class FrontendUrlGenerator
{
    public function __construct(protected readonly SiteFinder $siteFinder) {}

    /**
     * Returns the frontend URL (path) for the given page UID
     */
    public function getFrontendUrl(int $uid): string
    {
        if ($uid === 0 || $this->isRootPage($uid)) {
            return '/';
        }
        $site = $this->getSite($uid);
        return $site->getRouter()->generateUri($uid)->getPath();
    }

    /**
     * Returns the Site object by the given page UID
     */
    public function getSite(int $uid): Site
    {
        return $this->siteFinder->getSiteByPageId($uid);
    }

    /**
     * Returns, if the given page UID is a root page
     */
    protected function isRootPage(int $uid): bool
    {
        $rootline = BackendUtility::BEgetRootLine($uid);
        if (is_array($rootline) && count($rootline) > 1) {
            return $uid == $rootline[1]['uid'];
        }
        return false;
    }
}
