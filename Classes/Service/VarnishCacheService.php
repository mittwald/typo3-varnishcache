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

use Mittwald\Varnishcache\Domain\Model\Server;
use Mittwald\Varnishcache\Domain\Model\SysDomain;
use Mittwald\Varnishcache\Domain\Repository\ServerRepository;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class VarnishCacheService
{
    protected FrontendUrlGenerator $frontendUrlGenerator;
    protected ServerRepository $serverRepository;

    /**
     * VarnishCacheService constructor.
     *
     * @param FrontendUrlGenerator $frontendUrlGenerator
     * @param ServerRepository $serverRepository
     */
    public function __construct(
        FrontendUrlGenerator $frontendUrlGenerator,
        ServerRepository $serverRepository
    ) {
        $this->frontendUrlGenerator = $frontendUrlGenerator;
        $this->serverRepository = $serverRepository;
    }

    /**
     * Flush cache for every found domain and given varnish server
     *
     * @param int $currentPageId
     * @return bool
     *
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public function flushCache(int $currentPageId): bool
    {
        $servers = $this->serverRepository->findAll();
        $varnishFlushed = true;

        if ($servers) {
            $url = $this->frontendUrlGenerator->getFrontendUrl($currentPageId);
            foreach ($servers as $server) {
                if (($domains = $server->getDomains())) {
                    foreach ($domains as $domain) {
                        if ($currentPageId === 0 ||
                            preg_match(
                                '/' . $domain->getDomainName() . '/',
                                (string)$this->frontendUrlGenerator->getSite($currentPageId)->getBase()
                            )
                        ) {
                            try {
                                $this->request($domain, $server, $url);
                            } catch (\Throwable $exception) {
                                $varnishFlushed = false;
                            }
                        }
                    }
                }
            }
        }

        return $varnishFlushed;
    }

    /**
     * Send curl request
     *
     * @param SysDomain $domain
     * @param Server $server
     * @param string $frontendUrl
     */
    protected function request(SysDomain $domain, Server $server, string $frontendUrl): void
    {
        if (! function_exists('curl_version')) {
            throw new \BadFunctionCallException('Curl is required but not loaded', 1444895510);
        }

        if ($server->isStripSlashes()) {
            $frontendUrl = rtrim($frontendUrl, '/');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        curl_setopt($curl, CURLOPT_PORT, $server->getPort());
        curl_setopt($curl, CURLOPT_URL, $server->getRequestUrlByFrontendUrl($frontendUrl));
        curl_setopt(
            $curl,
            CURLOPT_HTTP_VERSION,
            ($server->getProtocol() == 1) ? CURL_HTTP_VERSION_1_0 : CURL_HTTP_VERSION_1_1
        );

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $server->getMethod());

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'X-Host: ' . $domain->getDomainName(),
            'X-Url: ' . $frontendUrl,
        ]);
        curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
        $header = curl_exec($curl);
        curl_close($curl);

        $this->getBackendUser()->writelog(
            3,
            1,
            0,
            0,
            'User %s has cleared the varnishcache (server=%s,host=%s, url=%s)',
            [$this->getBackendUser()->user['username'], $server->getIp(), $domain->getDomainName(), $frontendUrl]
        );
    }

    protected function getBackendUser(): ?BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
