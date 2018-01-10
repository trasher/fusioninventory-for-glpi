<?php
include_once 'bootstrap.php';
include_once 'CommonFunction.php';

include_once GLPI_ROOT . '/inc/based_config.php';
include_once GLPI_ROOT . '/inc/dbmysql.class.php';
include_once GLPI_CONFIG_DIR . '/config_db.php';

/*
 * Helper class to restore database from some SQL restore point file
 */
abstract class RestoreDatabaseTestCase extends CommonTestCase {
   public static function beforeTestMethod($method) {
      self::restore_database();
      parent::beforeTestMethod($method);
   }
}
