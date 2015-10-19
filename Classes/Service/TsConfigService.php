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

namespace Mittwald\Varnishcache\Service;

use TYPO3\CMS\Backend\Utility\BackendUtility;


/**
 * Class TsConfigService
 * @package Mittwald\Varnishcache\Service
 */
class TsConfigService {


    /**
     * @param $pid
     * @return array
     */
    public function getConfiguration($pid) {
        $config = BackendUtility::getModTSconfig($pid, 'mod.varnishcache');
        return $config['properties'];
    }

    /**
     * @param $pid
     * @return bool
     */
    public function isEsiAllowed($pid) {
        $varnishPageConfig = $this->getConfiguration($pid);
        return (isset($varnishPageConfig['esiDisallowed']) && (1 == $varnishPageConfig['esiDisallowed'])) ? FALSE : TRUE;
    }


}
