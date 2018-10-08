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
   @since     2013

   ------------------------------------------------------------------------
 */

class ComputerLog extends RestoreDatabase_TestCase {

   private $a_inventory = [];


   public function testLog() {
      global $DB;

      //$this->assertEquals(0, countElementsInTable('glpi_logs'), "Log must be empty");

      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      $computer         = new Computer();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();

      $date = date('Y-m-d H:i:s');

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $this->a_inventory = [
          'fusioninventorycomputer' => [
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => $date,
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
          'SOFTWARES'      => [],
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
      $this->a_inventory['Computer'] = [
          'name'                             => 'pc',
          'users_id'                         => 0,
          'operatingsystems_id'              => 'freebsd',
          'operatingsystemversions_id'       => '9.1-RELEASE',
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'domains_id'                       => 'mydomain.local',
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

      $this->a_inventory['processor'] = [
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequency_default' => 2400
                ],
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequency_default' => 2400
                ],
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequency_default' => 2400
                ],
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequency_default' => 2400
                ]
        ];

      $this->a_inventory['memory'] = [
            [
                    'size'                 => 2048,
                    'serial'               => '98F6FF18',
                    'frequence'            => '1067',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 1
                ],
            [
                    'size'                 => 2048,
                    'serial'               => '95F1833E',
                    'frequence'            => '1067',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 2
                ]
        ];

      $this->a_inventory['monitor'] = [
            [
                    'name'              => '',
                    'serial'            => '',
                    'manufacturers_id'  => ''
                ]
      ];

      $this->a_inventory['networkport'] = [
            'em0-00:23:18:cf:0d:93' => [
                    'name'                 => 'em0',
                    'netmask'              => '255.255.255.0',
                    'subnet'               => '192.168.30.0',
                    'mac'                  => '00:23:18:cf:0d:93',
                    'instantiation_type'   => 'NetworkPortEthernet',
                    'virtualdev'           => 0,
                    'ssid'                 => '',
                    'gateway'              => '',
                    'dhcpserver'           => '',
                    'logical_number'       => 0,
                    'ipaddress'            => ['192.168.30.198']
                ],
            'lo0-' => [
                    'name'                 => 'lo0',
                    'virtualdev'           => 1,
                    'mac'                  => '',
                    'instantiation_type'   => 'NetworkPortLocal',
                    'subnet'               => '',
                    'ssid'                 => '',
                    'gateway'              => '',
                    'netmask'              => '',
                    'dhcpserver'           => '',
                    'logical_number'       => 1,
                    'ipaddress'            => ['::1', 'fe80::1', '127.0.0.1']
                ]
        ];

      $this->a_inventory['software'] = [
            'gentiumbasic$$$$110$$$$1$$$$0$$$$0' => [
                    'name'                   => 'GentiumBasic',
                    'version'                => 110,
                    'manufacturers_id'       => 1,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0,
                    'is_dynamic'             => 1,
                    'operatingsystems_id'    => 0
                ],
            'imagemagick$$$$6.8.0.7_1$$$$2$$$$0$$$$0' => [
                    'name'                   => 'ImageMagick',
                    'version'                => '6.8.0.7_1',
                    'manufacturers_id'       => 2,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0,
                    'is_dynamic'             => 1,
                    'operatingsystems_id'    => 0
                ],
            'orbit2$$$$2.14.19$$$$3$$$$0$$$$0' => [
                    'name'                   => 'ORBit2',
                    'version'                => '2.14.19',
                    'manufacturers_id'       => 3,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0,
                    'is_dynamic'             => 1,
                    'operatingsystems_id'    => 0
                ]
          ];

      $this->a_inventory = $pfFormatconvert->replaceids($this->a_inventory, 'Computer', 0);

      $serialized = gzcompress(serialize($this->a_inventory));
      $this->a_inventory['fusioninventorycomputer']['serialized_inventory'] =
               Toolbox::addslashes_deep($serialized);

      $cid = $computer->add([
         'serial'       => 'XB63J7D',
         'entities_id'  => 0
      ]);

      // truncate glpi_logs
      $DB->query('DELETE FROM `glpi_logs`;');

      $this->assertEquals(0, countElementsInTable('glpi_logs'), "Log must be empty (truncate)");

      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerLib->updateComputer($this->a_inventory, $cid, true);

      $a_logs = getAllDatasFromTable('glpi_logs');
      foreach ($a_logs as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $a_logs[$id] = $data;
      }

      $a_reference = [
          1 => [
              'itemtype'         => 'DeviceProcessor',
              'itemtype_link'    => '0',
              'linked_action'    => '20',
              'user_name'        => '',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
          2 => [
              'itemtype'         => 'DeviceMemory',
              'itemtype_link'    => '0',
              'linked_action'    => '20',
              'user_name'        => '',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
          3 => [
              'itemtype'         => 'Software',
              'itemtype_link'    => '',
              'linked_action'    => '20',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
          4 => [
              'itemtype'         => 'Software',
              'itemtype_link'    => '',
              'linked_action'    => '20',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
          5 => [
              'itemtype'         => 'Software',
              'itemtype_link'    => '',
              'linked_action'    => '20',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
          6 => [
              'itemtype'         => 'SoftwareVersion',
              'itemtype_link'    => '',
              'linked_action'    => '20',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
          7 => [
              'itemtype'         => 'SoftwareVersion',
              'itemtype_link'    => '',
              'linked_action'    => '20',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
          8 => [
              'itemtype'         => 'SoftwareVersion',
              'itemtype_link'    => '',
              'linked_action'    => '20',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => '',
              'new_value'        => ''
              ],
      ];

      $this->checkLogs(
         $a_reference,
         $a_logs
      );

      // Update a second time and must not have any new lines in glpi_logs
      $pfiComputerLib->updateComputer($this->a_inventory, $cid, false);

      $a_logs = getAllDatasFromTable('glpi_logs');
      $a_reference = [];

      $this->checkLogs(
         $a_reference,
         $a_logs,
         "Log may be empty at second update ".print_r($a_logs, true)
      );

      // * Modify: contact
      // * remove a processor
      // * Remove a software
      $this->a_inventory['Computer']['contact'] = 'root';
      unset($this->a_inventory['processor'][3]);
      unset($this->a_inventory['software']['orbit2$$$$2.14.19$$$$3$$$$0$$$$0']);

      $DB->query('DELETE FROM `glpi_logs`');
      $pfiComputerLib->updateComputer($this->a_inventory, $cid, false);

      $a_logs = getAllDatasFromTable('glpi_logs');
      foreach ($a_logs as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $a_logs[$id] = $data;
      }
      $a_reference = [
          1 => [
              'itemtype'         => 'Computer',
              'itemtype_link'    => '',
              'linked_action'    => '0',
              'user_name'        => '',
              'id_search_option' => '7',
              'old_value'        => 'ddurieux',
              'new_value'        => 'root'
          ],
          2 => [
              'itemtype'         => 'Computer',
              'itemtype_link'    => 'DeviceProcessor',
              'linked_action'    => '3',
              'user_name'        => '',
              'id_search_option' => '0',
              'old_value'        => 'Core i3 (1)',
              'new_value'        => ''
          ],
          3 => [
              'itemtype'         => 'Computer',
              'itemtype_link'    => 'SoftwareVersion',
              'linked_action'    => '5',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => 'ORBit2 - 2.14.19 (3)',
              'new_value'        => ''
          ],
          4 => [
              'itemtype'         => 'SoftwareVersion',
              'itemtype_link'    => 'Computer',
              'linked_action'    => '5',
              'user_name'        => 'Plugin_FusionInventory',
              'id_search_option' => '0',
              'old_value'        => 'pc (1)',
              'new_value'        => ''
          ]
      ];

      $this->checkLogs(
         $a_reference,
         $a_logs,
         "May have 5 logs (update contact, remove processor and remove a software)"
      );
   }

   /**
    * Check logs entries
    *
    * @param array  $a_reference Expected entries
    * @param array  $a_logs      Entries found in DB
    * àparam string $message     Error message
    *
    * @return void
    */
   private function checkLogs($a_reference, $a_logs, $message = '') {
      global $DB;

      if ($message === '') {
         $message = "Log must be ".count($a_reference)." ".print_r($a_logs, true);
      }
      $this->assertEquals(count($a_reference), count($a_logs), $message);
      $a_logs = array_values($a_logs);
      foreach (array_values($a_reference) as $key => $reference) {
         $log = $a_logs[$key];
         $this->assertGreaterThan(0, $log['items_id']);
         unset($log['id']); //ids are not predictable...
         unset($log['items_id']); //ids are not predictable...
         if (preg_match('/.*(\(\d+\))$/', $reference['old_value'], $match)) {
            $log['old_value'] = preg_replace(
               '/\(\d+\)$/',
               $match[1],
               $log['old_value']
            );
         }
         if (preg_match('/.*(\(\d+\))$/', $reference['new_value'], $match)) {
            $log['new_value'] = preg_replace(
               '/\(\d+\)$/',
               $match[1],
               $log['new_value']
            );
         }
         $this->assertEquals($reference, $log, "Logs must be identical!");
      }
      $DB->query('DELETE FROM `glpi_logs`');
   }
}
