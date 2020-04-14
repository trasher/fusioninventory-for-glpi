<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2014

   ------------------------------------------------------------------------
 */

class VirtualmachineTest extends RestoreDatabase_TestCase {

   public $items_id = 0;
   public $datelatupdate = '';
   public $computer_inventory = [];


   function __construct() {
      parent::__construct();
      $a_inventory = [
          'fusioninventorycomputer' => [
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => date('Y-m-d H:i:s'),
              'last_boot'                       => '2018-06-11 08:03:32',
          ],
          'soundcard'      => [],
          'graphiccard'    => [],
          'controller'     => [],
          'processor'      => [],
          'computerdisk'   => [],
          'memory'         => [],
          'monitor'        => [],
          'printer'        => [],
          'peripheral'     => [],
          'networkport'    => [],
          'software'       => [],
          'harddrive'      => [],
          'virtualmachine' => [],
          'antivirus'      => [],
          'storage'        => [],
          'licenseinfo'    => [],
          'networkcard'    => [],
          'drive'          => [],
          'batteries'      => [],
          'remote_mgmt'    => [],
          'bios'           => [],
          'itemtype'       => 'Computer'
          ];
      $a_inventory['Computer'] = [
          'name'                             => 'pc',
          'users_id'                         => 0,
          'operatingsystems_id'              => 'freebsd',
          'operatingsystemversions_id'       => '9.1-RELEASE',
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'os_licenseid'                     => '',
          'os_license_number'                => '',
          'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => 'XB63J7D',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      ];
      $a_inventory['fusioninventorycomputer'] = [
          'last_fusioninventory_update' => date('Y-m-d H:i:s'),
          'serialized_inventory'        => 'something'
      ];

      $a_inventory['virtualmachine'][] = [
          'ram'                      => '1024',
          'name'                     => 'Windows 7',
          'comment'                  => 'comment',
          'virtualmachinestates_id'  => 'up',
          'virtualmachinesystems_id' => 'vbox',
          'uuid'                     => '2961ecf6-7e94-488d-ae0d-e427123078b3',
          'vcpu'                     => '1',
          'virtualmachinetypes_id'   => 'virtualbox',
          'is_dynamic'               => '1'
      ];

      $this->computer_inventory = $a_inventory;
   }


   /**
    * @test
    */
   public function AddComputer() {
      global $DB;

      $DB->connect();

      $date = date('Y-m-d H:i:s');

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = $this->computer_inventory;

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();

      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', 0);

      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] =
               Toolbox::addslashes_deep($serialized);

      $this->items_id = $computer->add(['serial'      => 'XB63J7D',
                                             'entities_id' => 0]);

      $this->assertGreaterThan(0, $this->items_id, false);
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, true);

      // To be sure not have 2 same informations
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, false);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }


   /**
    * Create VirtualMachine in computer
    *
    * @test
    */
   public function ComputerVirtualmachineCreate() {
      global $DB;

      $DB->connect();

      $a_data = getAllDataFromTable("glpi_computervirtualmachines");

      foreach ($a_data as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $a_data[$id] = $data;
      }

      $a_reference = [
          '1' => [
                     'id'                       => '1',
                     'entities_id'              => '0',
                     'computers_id'             => '1',
                     'name'                     => 'Windows 7',
                     'comment'                  => 'comment',
                     'virtualmachinestates_id'  => '1',
                     'virtualmachinesystems_id' => '1',
                     'virtualmachinetypes_id'   => '1',
                     'uuid'                     => '2961ecf6-7e94-488d-ae0d-e427123078b3',
                     'vcpu'                     => '1',
                     'ram'                      => '1024',
                     'is_deleted'               => '0',
                     'is_dynamic'               => '1',
                 ],
      ];
      $this->assertEquals($a_reference, $a_data);
   }


   /**
    * Update VirtualMachine in computer
    *
    * @test
    */
   public function ComputerVirtualmachineUpdateMemory() {
      global $DB;

      $DB->connect();

      $a_inventory = $this->computer_inventory;

      $a_inventory['virtualmachine'][0]['ram'] = '2048';

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();

      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', 1);

      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] =
               Toolbox::addslashes_deep($serialized);

      $pfiComputerLib->updateComputer($a_inventory, 1, false);

      $nbvm = countElementsInTable("glpi_computervirtualmachines");

      $this->assertEquals(1, $nbvm, 'May have only 1 VM');

      $a_data = getAllDataFromTable("glpi_computervirtualmachines");

      foreach ($a_data as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $a_data[$id] = $data;
      }

      $a_reference = [
          '1' => [
                     'id'                       => '1',
                     'entities_id'              => '0',
                     'computers_id'             => '1',
                     'name'                     => 'Windows 7',
                     'comment'                  => 'comment',
                     'virtualmachinestates_id'  => '1',
                     'virtualmachinesystems_id' => '1',
                     'virtualmachinetypes_id'   => '1',
                     'uuid'                     => '2961ecf6-7e94-488d-ae0d-e427123078b3',
                     'vcpu'                     => '1',
                     'ram'                      => '2048',
                     'is_deleted'               => '0',
                     'is_dynamic'               => '1',
                 ],
      ];
      $this->assertEquals($a_reference, $a_data);

   }


}

