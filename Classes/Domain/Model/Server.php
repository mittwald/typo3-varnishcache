<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Kevin Purrmann <entwicklung@purrmann-websolutions.de>
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


use Mittwald\Varnishcache\Domain\Model\SysDomain;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Server extends AbstractDomainObject {


    /**
     * @var string
     */
    protected $ip;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var boolean
     */
    protected $stripSlashes;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Varnishcache\Domain\Model\SysDomain>
     */
    protected $domains;

    /**
     * VarnishServer constructor.
     */
    public function __construct() {
        $this->domains = new ObjectStorage();
    }

    /**
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip) {
        $this->ip = $ip;
    }

    /**
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getProtocol() {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol($protocol) {
        $this->protocol = $protocol;
    }

    /**
     * @return boolean
     */
    public function isStripSlashes() {
        return $this->stripSlashes;
    }

    /**
     * @param boolean $stripSlashes
     */
    public function setStripSlashes($stripSlashes) {
        $this->stripSlashes = $stripSlashes;
    }

    /**
     * @return ObjectStorage
     */
    public function getDomains() {
        return $this->domains;
    }

    /**
     * @param ObjectStorage $domains
     */
    public function setDomains($domains) {
        $this->domains = $domains;
    }

    /**
     * @param \Mittwald\Varnishcache\Domain\Model\SysDomain $domain
     */
    public function addDomain(SysDomain $domain) {
        $this->domains->attach($domain);
    }

    /**
     * @param \Mittwald\Varnishcache\Domain\Model\SysDomain $domain
     */
    public function removeDomain(SysDomain $domain) {
        $this->domains->detach($domain);
    }

    /**
     * Returns request url for curl
     *
     * @param $frontendUrl
     * @return string
     */
    public function getRequestUrlByFrontendUrl($frontendUrl) {
        return $this->getIp() . '/' . ltrim($frontendUrl, '/');
    }

}
