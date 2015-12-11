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

namespace Mittwald\Varnishcache\Service;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;

class TyposcriptPluginSettingsService {

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     * @inject
     */
    protected $configurationManager;

    /**
     * @var array
     */
    protected $configuration = array();


    /**
     * @return array
     */
    public function getConfiguration() {

        if (empty($this->configuration)) {
            $fullConfig = $this->configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
            $this->configuration = $fullConfig['plugin.']['varnishcache.']['settings.'];
        }
        return $this->configuration;
    }

}
