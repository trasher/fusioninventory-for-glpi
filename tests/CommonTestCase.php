<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the configuration of the plugin.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Johan Cwiklinski
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

abstract class CommonTestCase extends atoum {

   protected function restore_database() {
      self::drop_database();
      self::load_mysql_file('./save.sql');
   }

   public static function load_mysql_file($filename) {

      self::assertFileExists($filename, 'File '.$filename.' does not exist!');

      $DBvars = get_class_vars('DB');

      $result = load_mysql_file(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword'],
         $filename
      );

      self::assertEquals( 0, $result['returncode'],
         "Failed to restore database:\n".
         implode("\n", $result['output'])
      );
   }

   public static function drop_database() {

      $DBvars = get_class_vars('DB');

      $result = drop_database(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword']
      );

      self::assertEquals( 0, $result['returncode'],
         "Failed to drop GLPI database:\n".
         implode("\n", $result['output'])
      );

   }


   protected function setUp() {
      global $CFG_GLPI,$DB;
      $DB = new DB();
      // Force profile in session to SuperAdmin
      $_SESSION['glpiprofiles'] = ['4' => ['entities' => 0]];
      $_SESSION['glpi_plugin_fusioninventory_profile']['unmanaged'] = 'w';
      $_SESSION['glpiactiveentities'] = [0, 1];
      $_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;

      $plugin = new Plugin();
      $DB->connect();
      $plugin->getFromDBbyDir("fusioninventory");
      $plugin->activate($plugin->fields['id']);

      file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
      file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');

      /*$dir = GLPI_ROOT."/files/_files/_plugins/fusioninventory";
      if (file_exists($dir)) {
         $objects = scandir($dir);

         foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
               if (filetype($dir."/".$object) == "dir") {
               } else {
                  unlink($dir."/".$object);
               }
            }
         }
      }*/

      // Security of PHP_SELF
      $_SERVER['PHP_SELF']=Html::cleanParametersURL($_SERVER['PHP_SELF']);

      ini_set("memory_limit", "-1");
      ini_set("max_execution_time", "0");
   }

   /**
    * Test if log file is empty and reset it
    *
    * @return void
    */
   protected function testLogFile($name) {
      $filecontent = file_get_contents(GLPI_ROOT . "/files/_log/$name.log");
      $this->string($filecontent)->isEmpty("$name.log is not empty");
      file_put_contents(GLPI_ROOT . "/files/_log/$name.log", '');
   }

   /**
    * Test if SQL log file is empty and reset it
    *
    * @return void
    */
   protected function testSQLlogs() {
      $this->testLogFile('sql-errors');
   }

   /**
    * Test if PHP log file is empty and reset it
    *
    * @return void
    */
   protected function testPHPlogs() {
      $this->testLogFile('php-errors');
   }

   protected function tearDown() {
      $this->testSQLlogs();
      $this->testPHPlogs();
   }
}
