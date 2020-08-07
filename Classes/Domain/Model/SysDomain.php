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

namespace Mittwald\Varnishcache\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;


class SysDomain extends AbstractEntity
{

    /**
     * @var string
     */
    protected $domainName = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Varnishcache\Domain\Model\Server>
     */
    protected $servers;

    /**
     * SysDomain constructor.
     */
    public function __construct()
    {
        $this->servers = new ObjectStorage();
    }

    /**
     * @return string
     */
    public function getDomainName()
    {
        return $this->domainName;
    }

    /**
     * @param string $domainName
     */
    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
    }

    /**
     * @return ObjectStorage
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * @param ObjectStorage $servers
     */
    public function setServers($servers)
    {
        $this->servers = $servers;
    }

    public function addServer(Server $server)
    {
        $this->servers->attach($server);
    }

    public function removeServer(Server $server)
    {
        $this->servers->detach($server);
    }
}
