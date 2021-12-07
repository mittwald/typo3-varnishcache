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

namespace Mittwald\Varnishcache\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Server extends AbstractDomainObject
{
    protected string $ip = '';
    protected int $port = 0;
    protected string $method = '';
    protected string $protocol = '';
    protected bool $stripSlashes = false;

    /**
     * @var ObjectStorage<SysDomain>
     */
    protected ObjectStorage $domains;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject()
    {
        $this->domains = new ObjectStorage();
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    public function isStripSlashes(): bool
    {
        return $this->stripSlashes;
    }

    public function setStripSlashes(bool $stripSlashes): void
    {
        $this->stripSlashes = $stripSlashes;
    }

    public function getDomains(): ?ObjectStorage
    {
        return $this->domains;
    }

    public function setDomains(ObjectStorage $domains)
    {
        $this->domains = $domains;
    }

    public function addDomain(SysDomain $domain)
    {
        $this->domains->attach($domain);
    }

    public function removeDomain(SysDomain $domain)
    {
        $this->domains->detach($domain);
    }

    /**
     * Returns request url for curl
     *
     * @param string $frontendUrl
     * @return string
     */
    public function getRequestUrlByFrontendUrl(string $frontendUrl): string
    {
        return $this->getIp() . '/' . ltrim($frontendUrl, '/');
    }
}
