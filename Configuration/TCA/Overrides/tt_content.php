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

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$tempColumns = array(
        'exclude_from_cache' => array(
                'exclude' => 1,
                'label' => 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:tt_content.exclude_from_cache',
                'config' => array(
                        'type' => 'check',
                )
        ),
        'alternative_content' => array(
                'exclude' => 1,
                'label' => 'LLL:EXT:varnishcache/Resources/Private/Language/locallang_db.xlf:tt_content.alternative_content',
                'config' => array(
                        'type' => 'group',
                        'internal_type' => 'db',
                        'allowed' => 'tt_content',
                        'size' => 1,
                        'wizards' => array(
                                'suggest' => array(
                                        'type' => 'suggest'
                                ),
                        ),
                ),
        ),

);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;Caching,exclude_from_cache, alternative_content');
