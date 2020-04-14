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

class ComputerUpdateTest extends RestoreDatabase_TestCase {

   public $items_id = 0;
   public $datelatupdate = '';


   /**
    * @test
    */
   public function AddComputer() {
      global $DB;

      $DB->connect();

      $_SESSION['plugin_fusioninventory_classrulepassed'] = '';

      $date = date('Y-m-d H:i:s');

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = [
          'fusioninventorycomputer' => [
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => $date,
              'last_boot'                       => '2018-06-11 08:03:32',
              'items_operatingsystems_id'       => [
                  'operatingsystems_id'              => 'freebsd',
                  'operatingsystemversions_id'       => '9.1-RELEASE',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                        => '',
                  'license_number'                   => ''
              ]
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
      $a_inventory['Computer'] = [
          'name'                             => 'pc',
          'users_id'                         => 0,
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => 'XB63J7D',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      ];

      $a_inventory['processor'] = [
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'frequency_default' => 2400
                ],
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'frequency_default' => 2400
                ],
            [
                    'nbcores'           => 4,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2405,
                    'frequence'         => 2405,
                    'nbthreads'         => 4,
                    'frequency_default' => 2405
                ],
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2600,
                    'frequence'         => 2600,
                    'nbthreads'         => 4,
                    'frequency_default' => 2600
                ]
        ];

      $a_inventory['memory'] = [
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
                ],
            [
                    'size'                 => 2048,
                    'serial'               => '95F1833G',
                    'frequence'            => '1066',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 3
                ],
            [
                    'size'                 => 2048,
                    'serial'               => '95F1833H',
                    'frequence'            => '1333',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 4
                ]
        ];

      $a_inventory['monitor'] = [
            [
                    'name'              => 'ThinkPad Display 1280x800',
                    'serial'            => 'UBYVUTFYEIUI',
                    'manufacturers_id'  => 'Lenovo',
                    'is_dynamic'        => 1
                ]
      ];

      $a_inventory['printer'] = [
            [
                    'name'      => 'HP Deskjet 5700 Series',
                    'serial'    => 'MY47L1W1JHEB6',
                    'have_usb'  => 1,
                    'is_dynamic' => 1
                ]
      ];

      $a_inventory['networkport'] = [
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
                    'logical_number'       => 1,
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
                    'logical_number'       => 0,
                    'ipaddress'            => ['::1', 'fe80::1', '127.0.0.1']
                ]
        ];

      $a_inventory['software'] = [
            'gentiumbasic$$$$110$$$$1$$$$0$$$$0' => [
                    'name'                   => 'GentiumBasic',
                    'version'                => 110,
                    'manufacturers_id'       => 1,
                    'entities_id'            => 0,
                    'is_template_item'   => 0,
                    'is_deleted_item'    => 0,
                    'operatingsystems_id'    => 0
                ],
            'imagemagick$$$$6.8.0.7_1$$$$2$$$$0$$$$0' => [
                    'name'                   => 'ImageMagick',
                    'version'                => '6.8.0.7_1',
                    'manufacturers_id'       => 2,
                    'entities_id'            => 0,
                    'is_template_item'   => 0,
                    'is_deleted_item'    => 0,
                    'operatingsystems_id'    => 0
                ],
            'orbit2$$$$2.14.19$$$$3$$$$0$$$$0' => [
                    'name'                   => 'ORBit2',
                    'version'                => '2.14.19',
                    'manufacturers_id'       => 3,
                    'entities_id'            => 0,
                    'is_template_item'   => 0,
                    'is_deleted_item'    => 0,
                    'operatingsystems_id'    => 0,
                    'date_install'           => '2016-07-20'
                ]
          ];

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
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, false);

      // To be sure not have 2 same informations
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, false);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerGeneral() {
      global $DB;

      $DB->connect();

      $computer = new Computer();

      $computer->getFromDB(1);
      unset($computer->fields['date_mod']);
      unset($computer->fields['date_creation']);
      $a_reference = [
          'name'                             => 'pc',
          'id'                               => 1,
          'entities_id'                      => 0,
          'serial'                           => 'XB63J7D',
          'otherserial'                      => null,
          'contact'                          => 'ddurieux',
          'contact_num'                      => null,
          'users_id_tech'                    => 0,
          'groups_id_tech'                   => 0,
          'comment'                          => null,
          'autoupdatesystems_id'             => 0,
          'locations_id'                     => 0,
          'networks_id'                      => 0,
          'computermodels_id'                => 0,
          'computertypes_id'                 => 1,
          'is_template'                      => 0,
          'template_name'                    => null,
          'manufacturers_id'                 => 0,
          'is_deleted'                       => 0,
          'is_dynamic'                       => 1,
          'users_id'                         => 0,
          'groups_id'                        => 0,
          'states_id'                        => 0,
          'ticket_tco'                       => '0.0000',
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'is_recursive'                     => 0
      ];

      $this->assertEquals($a_reference, $computer->fields);

      //check if operating system has been created
      $ios = new Item_OperatingSystem();
      $this->assertEquals(1, $ios->countForItem($computer));
      $this->assertTrue(
         $ios->getFromDBByCrit([
            'itemtype' => 'Computer',
            'items_id' => $computer->getID()
         ])
      );

      $a_reference = [
         'id'                                => 1,
         'items_id'                          => 1,
         'itemtype'                          => 'Computer',
         'operatingsystems_id'               => 1,
         'operatingsystemversions_id'        => 1,
         'operatingsystemservicepacks_id'    => 1,
         'operatingsystemarchitectures_id'   => 0,
         'operatingsystemkernelversions_id'  => 0,
         'license_number'                    => '',
         'licenseid'                         => '',
         'operatingsystemeditions_id'        => 0,
         'is_deleted'                        => 0,
         'is_dynamic'                        => 1,
         'entities_id'                       => 0,
         'is_recursive'                      => 0
      ];

      unset($ios->fields['date_mod']);
      unset($ios->fields['date_creation']);
      $this->assertEquals($a_reference, $ios->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerExtension() {
      global $DB;

      $DB->connect();

      $pfiComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $a_computer = current($pfiComputerComputer->find(['computers_id' => 1], [], 1));
      unset($a_computer['last_fusioninventory_update']);
      $serialized_inventory = $a_computer['serialized_inventory'];
      unset($a_computer['serialized_inventory']);
      $a_reference = [
          'id'                                        => '1',
          'computers_id'                              => '1',
          'operatingsystem_installationdate'          => '2012-10-16 08:12:56',
          'last_boot'                                 => '2018-06-11 08:03:32',
          'winowner'                                  => 'test',
          'wincompany'                                => 'siprossii',
          'remote_addr'                               => null,
          'is_entitylocked'                           => '0',
          'oscomment'                                 => null,
          'hostid'                                    => null
      ];

      $this->assertEquals($a_reference, $a_computer);

      $this->assertNotEquals(null, $serialized_inventory);

   }


   /**
    * @test
    * @depends AddComputer
    */
   public function Softwareadded() {
      global $DB;

      $DB->connect();

      $nbsoftware = countElementsInTable("glpi_softwares");

      $this->assertEquals(3, $nbsoftware);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareGentiumBasicadded() {
      global $DB;

      $DB->connect();

      $software = new Software();

      $software->getFromDB(1);
      unset($software->fields['date_mod']);
      unset($software->fields['date_creation']);
      $a_reference = [
          'id'                      => '1',
          'name'                    => 'GentiumBasic',
          'entities_id'             => '0',
          'is_recursive'            => '0',
          'comment'                 => null,
          'locations_id'            => '0',
          'users_id_tech'           => '0',
          'groups_id_tech'          => '0',
          'is_update'               => '0',
          'softwares_id'            => '0',
          'manufacturers_id'        => '1',
          'is_deleted'              => '0',
          'is_template'             => '0',
          'template_name'           => null,
          'users_id'                => '0',
          'groups_id'               => '0',
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => '1',
          'softwarecategories_id'   => '0',
          'is_valid'                   => '1',
      ];

      $this->assertEquals($a_reference, $software->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareImageMagickadded() {
      global $DB;

      $DB->connect();

      $software = new Software();

      $software->getFromDB(2);
      unset($software->fields['date_mod']);
      unset($software->fields['date_creation']);
      $a_reference = [
          'id'                      => '2',
          'name'                    => 'ImageMagick',
          'entities_id'             => '0',
          'is_recursive'            => '0',
          'comment'                 => null,
          'locations_id'            => '0',
          'users_id_tech'           => '0',
          'groups_id_tech'          => '0',
          'is_update'               => '0',
          'softwares_id'            => '0',
          'manufacturers_id'        => '2',
          'is_deleted'              => '0',
          'is_template'             => '0',
          'template_name'           => null,
          'users_id'                => '0',
          'groups_id'               => '0',
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => '1',
          'softwarecategories_id'   => '0',
          'is_valid'                   => '1',
      ];

      $this->assertEquals($a_reference, $software->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareORBit2added() {
      global $DB;

      $DB->connect();

      $software = new Software();

      $software->getFromDB(3);
      unset($software->fields['date_mod']);
      unset($software->fields['date_creation']);
      $a_reference = [
          'id'                      => '3',
          'name'                    => 'ORBit2',
          'entities_id'             => '0',
          'is_recursive'            => '0',
          'comment'                 => null,
          'locations_id'            => '0',
          'users_id_tech'           => '0',
          'groups_id_tech'          => '0',
          'is_update'               => '0',
          'softwares_id'            => '0',
          'manufacturers_id'        => '3',
          'is_deleted'              => '0',
          'is_template'             => '0',
          'template_name'           => null,
          'users_id'                => '0',
          'groups_id'               => '0',
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => '1',
          'softwarecategories_id'   => '0',
          'is_valid'                   => '1',
      ];

      $this->assertEquals($a_reference, $software->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareVersionGentiumBasicadded() {
      global $DB;

      $DB->connect();

      $softwareVersion = new SoftwareVersion();

      $softwareVersion->getFromDB(1);
      unset($softwareVersion->fields['date_mod']);
      unset($softwareVersion->fields['date_creation']);
      $a_reference = [
          'id'                   => '1',
          'name'                 => '110',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'softwares_id'         => '1',
          'states_id'            => '0',
          'comment'              => null,
          'operatingsystems_id'  => '0'
      ];

      $this->assertEquals($a_reference, $softwareVersion->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareVersionImageMagickadded() {
      global $DB;

      $DB->connect();

      $softwareVersion = new SoftwareVersion();

      $softwareVersion->getFromDB(2);
      unset($softwareVersion->fields['date_mod']);
      unset($softwareVersion->fields['date_creation']);
      $a_reference = [
          'id'                   => '2',
          'name'                 => '6.8.0.7_1',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'softwares_id'         => '2',
          'states_id'            => '0',
          'comment'              => null,
          'operatingsystems_id'  => '0'
      ];

      $this->assertEquals($a_reference, $softwareVersion->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareVersionORBit2added() {
      global $DB;

      $DB->connect();

      $softwareVersion = new SoftwareVersion();

      $softwareVersion->getFromDB(3);
      unset($softwareVersion->fields['date_mod']);
      unset($softwareVersion->fields['date_creation']);
      $a_reference = [
          'id'                   => '3',
          'name'                 => '2.14.19',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'softwares_id'         => '3',
          'states_id'            => '0',
          'comment'              => null,
          'operatingsystems_id'  => '0'
      ];

      $this->assertEquals($a_reference, $softwareVersion->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerSoftwareGentiumBasic() {
      global $DB;

      $DB->connect();

      $computer_SoftwareVersion = new Item_SoftwareVersion();

      $computer_SoftwareVersion->getFromDB(1);

      $a_reference = [
         'id'                    => '1',
         'itemtype'              => 'Computer',
          'items_id'             => '1',
          'softwareversions_id'  => '1',
          'is_deleted_item'  => '0',
          'is_template_item' => '0',
          'entities_id'          => '0',
          'is_deleted'           => '0',
          'is_dynamic'           => '1',
          'date_install'         => null
      ];

      $this->assertEquals($a_reference, $computer_SoftwareVersion->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerSoftwareImageMagick() {
      global $DB;

      $DB->connect();

      $computer_SoftwareVersion = new Item_SoftwareVersion();

      $computer_SoftwareVersion->getFromDB(2);

      $a_reference = [
         'id'                   => '2',
         'itemtype'              => 'Computer',
          'items_id'             => '1',
          'softwareversions_id'  => '2',
          'is_deleted_item'  => '0',
          'is_template_item' => '0',
          'entities_id'          => '0',
          'is_deleted'           => '0',
          'is_dynamic'           => '1',
          'date_install'         => null
      ];

      $this->assertEquals($a_reference, $computer_SoftwareVersion->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerSoftwareORBit2() {
      global $DB;

      $DB->connect();

      $computer_SoftwareVersion = new Item_SoftwareVersion();

      $computer_SoftwareVersion->getFromDB(3);

      $a_reference = [
         'id'                   => '3',
         'itemtype'              => 'Computer',
          'items_id'             => '1',
          'softwareversions_id'  => '3',
          'is_deleted_item'  => '0',
          'is_template_item' => '0',
          'entities_id'          => '0',
          'is_deleted'           => '0',
          'is_dynamic'           => '1',
          'date_install'         => '2016-07-20'
      ];

      $this->assertEquals($a_reference, $computer_SoftwareVersion->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerProcessor() {
      global $DB;

      $DB->connect();

      $a_data = getAllDataFromTable("glpi_deviceprocessors");
      foreach ($a_data as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $a_data[$id] = $data;
      }
      $a_reference = [
          '1' => [
                     'id'                 => '1',
                     'designation'        => 'Core i3',
                     'frequence'                => '2400',
                     'comment'                  => null,
                     'manufacturers_id'         => '1',
                     'frequency_default'        => '2400',
                     'nbcores_default'          => null,
                     'nbthreads_default'        => null,
                     'entities_id'              => '0',
                     'is_recursive'             => '0',
                     'deviceprocessormodels_id' => null
                 ],
          '2' => [
                     'id'                       => '2',
                     'designation'              => 'Core i3',
                     'frequence'                => '2600',
                     'comment'                  => null,
                     'manufacturers_id'         => '1',
                     'frequency_default'        => '2600',
                     'nbcores_default'          => null,
                     'nbthreads_default'        => null,
                     'entities_id'              => '0',
                     'is_recursive'             => '0',
                     'deviceprocessormodels_id' => null
                 ]
      ];
      $this->assertEquals($a_reference, $a_data);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerProcessorLink() {
      global $DB;

      $DB->connect();

      $a_dataLink = getAllDataFromTable("glpi_items_deviceprocessors",
         ['itemtype' => 'Computer', 'items_id' => '1']);

      $a_reference = [
          '1' => [
                     'id'                    => '1',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => '1',
                     'frequency'             => '2400',
                     'serial'                => '',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'nbcores'               => '2',
                     'nbthreads'             => '2',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'busID'                 => null,
                     'otherserial'           => null,
                     'locations_id'          => '0',
                     'states_id'             => '0'
                 ],
          '2' => [
                     'id' => '2',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => '1',
                     'frequency'             => '2400',
                     'serial'                => '',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'nbcores'               => '2',
                     'nbthreads'             => '2',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'busID'                 => null,
                     'otherserial'           => null,
                     'locations_id'          => '0',
                     'states_id'             => '0'
                 ],
          '3' => [
                     'id' => 3,
                     'items_id'              => 1,
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => 1,
                     'frequency'             => '2405',
                     'serial'                => '',
                     'is_deleted'            => 0,
                     'is_dynamic'            => 1,
                     'nbcores'               => 4,
                     'nbthreads'             => 4,
                     'entities_id'           => 0,
                     'is_recursive'          => 0,
                     'busID'                 => null,
                     'otherserial'           => null,
                     'locations_id'          => 0,
                     'states_id'             => 0
                 ],
          '4' => [
                     'id' => '4',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => '2',
                     'frequency'             => '2600',
                     'serial'                => '',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'nbcores'               => '2',
                     'nbthreads'             => '4',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'busID'                 => null,
                     'otherserial'           => null,
                     'locations_id'          => '0',
                     'states_id'             => '0'
                 ]
      ];

      $this->assertEquals($a_reference, $a_dataLink);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerMemory() {
      global $DB;

      $DB->connect();

      $a_data = getAllDataFromTable("glpi_devicememories");
      foreach ($a_data as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $a_data[$id] = $data;
      }
      $a_reference = [
          '1' => [
                     'id'                    => '1',
                     'designation'           => 'DDR3 - SODIMM (None)',
                     'frequence'             => '1067',
                     'comment'               => null,
                     'manufacturers_id'      => '0',
                     'size_default'          => '0',
                     'devicememorytypes_id'  => '5',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'devicememorymodels_id' => null
                 ],
          '2' => [
                     'id'                    => '2',
                     'designation'           => 'DDR3 - SODIMM (None)',
                     'frequence'             => '1333',
                     'comment'               => null,
                     'manufacturers_id'      => '0',
                     'size_default'          => '0',
                     'devicememorytypes_id'  => '5',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'devicememorymodels_id' => null
                 ]
      ];
      $this->assertEquals($a_reference, $a_data);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerMemoryLink() {
      global $DB;

      $DB->connect();

      $a_dataLink = getAllDataFromTable("glpi_items_devicememories",
         ['itemtype' => 'Computer', 'items_id' => '1']);

      $a_reference = [
          '1' => [
                     'id'                    => '1',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'serial'                => '98F6FF18',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'devicememories_id'     => '1',
                     'size'                  => '2048',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'busID'                 => 1,
                     'otherserial'           => null,
                     'locations_id'          => '0',
                     'states_id'             => '0'
                 ],
          '2' => [
                     'id' => '2',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'serial'                => '95F1833E',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'devicememories_id'     => '1',
                     'size'                  => '2048',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'busID'                 => 2,
                     'otherserial'           => null,
                     'locations_id'          => '0',
                     'states_id'             => '0'
                 ],
          '3' => [
                     'id' => '3',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'serial'                => '95F1833G',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'devicememories_id'     => '1',
                     'size'                  => '2048',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'busID'                 => 3,
                     'otherserial'           => null,
                     'locations_id'          => '0',
                     'states_id'             => '0'
                 ],
          '4' => [
                     'id' => '4',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'serial'                => '95F1833H',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'devicememories_id'     => '2',
                     'size'                  => '2048',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'busID'                 => 4,
                     'otherserial'           => null,
                     'locations_id'          => '0',
                     'states_id'             => '0'
                 ]
      ];

      $this->assertEquals($a_reference, $a_dataLink);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerNetworkport() {
      global $DB;

      $DB->connect();

      $a_dataLink = getAllDataFromTable("glpi_networkports",
         ['itemtype' => 'Computer', 'items_id' => '1']);

      foreach ($a_dataLink as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $a_dataLink[$id] = $data;
      }

      $a_reference = [
          '1' => [
                     'id'                    => 1,
                     'items_id'              => 1,
                     'itemtype'              => 'Computer',
                     'is_deleted'            => 0,
                     'is_dynamic'            => 1,
                     'entities_id'           => 0,
                     'is_recursive'          => 0,
                     'logical_number'        => '1',
                     'name'                  => 'em0',
                     'instantiation_type'    => 'NetworkPortEthernet',
                     'mac'                   => '00:23:18:cf:0d:93',
                     'comment'               => null

                 ],
          '2' => [
                     'id'                    => 2,
                     'items_id'              => 1,
                     'itemtype'              => 'Computer',
                     'is_deleted'            => 0,
                     'is_dynamic'            => 1,
                     'entities_id'           => 0,
                     'is_recursive'          => 0,
                     'logical_number'        => 0,
                     'name'                  => 'lo0',
                     'instantiation_type'    => 'NetworkPortLocal',
                     'mac'                   => '',
                     'comment'               => null
                 ]
      ];

      $this->assertEquals($a_reference, $a_dataLink);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerMonitor() {
      global $DB;

      $DB->connect();

      $a_dataMonit = getAllDataFromTable("glpi_monitors");

      $this->assertEquals(1, count($a_dataMonit), "Must have 1 monitor created");

      $a_dataLink = getAllDataFromTable("glpi_computers_items",
         ['itemtype' => 'Monitor', 'computers_id' => '1']);

      $this->assertEquals(1, count($a_dataLink), "Number of monitors not right");

      $a_dataLink = current($a_dataLink);

      $monitor = new Monitor();
      $monitor->getFromDB($a_dataLink['items_id']);

      unset($monitor->fields['date_mod']);
      unset($monitor->fields['date_creation']);

      $a_reference = [
          'id'                => '1',
          'entities_id'       => '0',
          'name'              => 'ThinkPad Display 1280x800',
          'contact'           => 'ddurieux',
          'contact_num'       => null,
          'users_id_tech'     => '0',
          'groups_id_tech'    => '0',
          'comment'           => null,
          'serial'            => 'UBYVUTFYEIUI',
          'otherserial'       => null,
          'size'              => '0.00',
          'have_micro'        => '0',
          'have_speaker'      => '0',
          'have_subd'         => '0',
          'have_bnc'          => '0',
          'have_dvi'          => '0',
          'have_pivot'        => '0',
          'have_hdmi'         => '0',
          'have_displayport'  => '0',
          'locations_id'      => '0',
          'monitortypes_id'   => '0',
          'monitormodels_id'  => '0',
          'manufacturers_id'  => '2',
          'is_global'         => '0',
          'is_deleted'        => '0',
          'is_template'       => '0',
          'template_name'     => null,
          'users_id'          => '0',
          'groups_id'         => '0',
          'states_id'         => '0',
          'ticket_tco'        => '0.0000',
          'is_dynamic'        => '1',
          'is_recursive'      => '0'
      ];

      $this->assertEquals($a_reference, $monitor->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerPrinter() {
      global $DB;

      $DB->connect();

      $a_dataLink = getAllDataFromTable("glpi_computers_items",
         ['itemtype' => 'Printer', 'computers_id' => '1']);

      $this->assertEquals(1, count($a_dataLink), "Number of printers not right");

      $a_dataLink = current($a_dataLink);

      $printer = new Printer();
      $printer->getFromDB($a_dataLink['items_id']);

      unset($printer->fields['date_mod']);
      unset($printer->fields['date_creation']);

      $a_reference = [
          'id'                   => '1',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'name'                 => 'HP Deskjet 5700 Series',
          'contact'              => 'ddurieux',
          'contact_num'          => null,
          'users_id_tech'        => '0',
          'groups_id_tech'       => '0',
          'serial'               => 'MY47L1W1JHEB6',
          'otherserial'          => null,
          'have_serial'          => '0',
          'have_parallel'        => '0',
          'have_usb'             => '1',
          'have_wifi'            => '0',
          'have_ethernet'        => '0',
          'comment'              => null,
          'memory_size'          => null,
          'locations_id'         => '0',
          'networks_id'          => '0',
          'printertypes_id'      => '0',
          'printermodels_id'     => '0',
          'manufacturers_id'     => '0',
          'is_global'            => '0',
          'is_deleted'           => '0',
          'is_template'          => '0',
          'template_name'        => null,
          'init_pages_counter'   => '0',
          'last_pages_counter'   => '0',
          'users_id'             => '0',
          'groups_id'            => '0',
          'states_id'            => '0',
          'ticket_tco'           => '0.0000',
          'is_dynamic'           => '1',
      ];

      $this->assertEquals($a_reference, $printer->fields);
   }


   /**
    * @test
    */
   public function SoftwareUniqueForTwoComputers() {
      global $DB;

      $DB->connect();

      $date = date('Y-m-d H:i:s');

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = [
          'fusioninventorycomputer' => [
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => $date,
              'last_boot'                       => 'NULL',
              'items_operatingsystems_id'       => [
                  'operatingsystems_id'              => 'freebsd',
                  'operatingsystemversions_id'       => '9.1-RELEASE',
                  'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                        => '',
                  'license_number'                   => ''
              ]
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
      $a_inventory['Computer'] = [
          'name'                             => 'pcJ1',
          'comment'                          => 'amd64/-1-11-30 22:04:44',
          'users_id'                         => 0,
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => 'XB63J7J1',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      ];
      $a_inventory['software'] = [
            'acrobat_reader_9.2$$$$1.0.0.0$$$$192$$$$0$$$$0' => [
                    'name'                   => 'acrobat_Reader_9.2',
                    'version'                => '1.0.0.0',
                    'manufacturers_id'       => 192,
                    'entities_id'            => 0,
                    'is_template_item'   => 0,
                    'is_deleted_item'    => 0,
                    'operatingsystems_id'    => 0
                ]
          ];

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      $software         = new Software();

      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', 0);

      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] =
               Toolbox::addslashes_deep($serialized);

      $this->items_id = $computer->add(['serial'      => 'XB63J7J1',
                                             'entities_id' => 0]);

      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, false);

      $a_software = $software->find(['name' => 'acrobat_Reader_9.2']);
      $this->assertEquals(1, count($a_software), "First computer added");

      $a_inventory['Computer']['name'] = "pcJ2";
      $a_inventory['Computer']['serial'] = "XB63J7J2";
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, false);

      $a_software = $software->find(['name' => 'acrobat_Reader_9.2']);
      $this->assertEquals(1, count($a_software), "Second computer added");
   }


}
