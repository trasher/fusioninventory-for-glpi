<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2017 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

error_reporting(E_ALL);

define('GLPI_CONFIG_DIR', __DIR__);
/*define('GLPI_URI', (getenv('GLPI_URI') ?: 'http://localhost:8088'));
define('TU_USER', '_test_user');
define('TU_PASS', 'PhpUnit_4');*/

if (!file_exists(GLPI_CONFIG_DIR . '/config_db.php')) {
   echo "\nConfiguration file for tests not found\n\n";
   die(1);
}
global $CFG_GLPI;

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', realpath(__DIR__ . '/../../../'));
   define('FUSINV_ROOT', GLPI_ROOT . DIRECTORY_SEPARATOR . '/plugins/fusioninventory');
}

include_once GLPI_ROOT . '/tests/GLPITestCase.php';
include_once GLPI_ROOT . '/tests/DbTestCase.php';
require_once GLPI_ROOT . '/inc/define.php';
require_once GLPI_ROOT . '/inc/includes.php';

//install plugin
$plugin = new \Plugin();
$plugin->getFromDBbyDir('fusioninventory');
//check from prerequisites as Plugin::install() does not!
if (!plugin_fusioninventory_check_prerequisites()) {
   echo "\nPrerequisites are not met!";
   die(1);
}

if (!$plugin->isInstalled('fusioninventory')) {
   call_user_func([$plugin, 'install'], $plugin->getID());
}
if (!$plugin->isActivated('fusioninventory')) {
   call_user_func([$plugin, 'activate'], $plugin->getID());
}

include_once __DIR__ . '/CommonTestCase.php';
include_once __DIR__ . '/RestoreDatabaseTestCase.php';

class GlpitestPHPerror extends Exception
{
}
class GlpitestPHPwarning extends Exception
{
}
class GlpitestPHPnotice extends Exception
{
}
class GlpitestSQLError extends Exception
{
}
