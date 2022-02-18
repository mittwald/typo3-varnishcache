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
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class VarnishCacheService
{
    protected ClientInterface $httpClient;
    protected RequestFactoryInterface $requestFactory;
    protected FrontendUrlGenerator $frontendUrlGenerator;
    protected ServerRepository $serverRepository;

    public function __construct(
        FrontendUrlGenerator $frontendUrlGenerator,
        ServerRepository $serverRepository,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory
    ) {
        $this->frontendUrlGenerator = $frontendUrlGenerator;
        $this->serverRepository = $serverRepository;
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
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
                            $varnishFlushed = $this->clearVarnishCache($domain, $server, $url);
                        }
                    }
                }
            }
        }

        return $varnishFlushed;
    }

    /**
     * Clears the varnish cache for the given frontend URL
     *
     * @param SysDomain $domain
     * @param Server $server
     * @param string $frontendUrl
     *
     * @return bool
     */
    protected function clearVarnishCache(SysDomain $domain, Server $server, string $frontendUrl): bool
    {
        $result = true;

        try {
            if ($server->isStripSlashes()) {
                $frontendUrl = rtrim($frontendUrl, '/');
            }

            $url = $server->getRequestUrlForVarnish($frontendUrl);
            $protocol = $server->getProtocol() === '1' ? '1.0' : '1.1';
            $request = $this->requestFactory->createRequest($server->getMethod(), $url)
                ->withAddedHeader('X-Host', $domain->getDomainName())
                ->withAddedHeader('X-Url', $frontendUrl)
                ->withProtocolVersion($protocol);

            $response = $this->httpClient->sendRequest($request);
            if ($response->getStatusCode() !== 200) {
                $result = false;
            } else {
                $this->getBackendUser()->writelog(
                    3,
                    1,
                    0,
                    0,
                    'User %s has cleared the varnishcache (server=%s,host=%s, url=%s)',
                    [
                        $this->getBackendUser()->user['username'],
                        $server->getIp(),
                        $domain->getDomainName(),
                        $frontendUrl,
                    ]
                );
            }
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }

    protected function getBackendUser(): ?BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
