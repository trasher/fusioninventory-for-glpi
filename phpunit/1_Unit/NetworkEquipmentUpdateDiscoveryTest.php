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

class NetworkEquipmentUpdateDiscovery extends RestoreDatabase_TestCase {

   public $datelatupdate = '';


   public $networkports_reference = [
      1 => [
         'id'                  => '1',
         'items_id'            => '1',
         'itemtype'            => 'NetworkEquipment',
         'entities_id'         => '0',
         'is_recursive'        => '0',
         'logical_number'      => '0',
         'name'                => 'management',
         'instantiation_type'  => 'NetworkPortAggregate',
         'mac'                 => '38:22:d6:3c:da:e7',
         'comment'             => null,
         'is_deleted'          => '0',
         'is_dynamic'          => '0'
      ]
   ];

   public $ipaddresses_reference = [
      1 => [
         'id'            => '1',
         'entities_id'   => '0',
         'items_id'      => '1',
         'itemtype'      => 'NetworkName',
         'version'       => '4',
         'name'          => '99.99.10.10',
         'binary_0'      => '0',
         'binary_1'      => '0',
         'binary_2'      => '65535',
         'binary_3'      => '1667435018',
         'is_deleted'    => '0',
         'is_dynamic'    => '0',
         'mainitems_id'  => '1',
         'mainitemtype'  => 'NetworkEquipment'

      ]
   ];

   public $source_xmldevice = [
      'SNMPHOSTNAME' => 'switch H3C',
      'DESCRIPTION' => 'H3C Comware Platform Software, Software Version 5.20 Release 2208',
      'AUTHSNMP' => '1',
      'IP' => '99.99.10.10',
      'MAC' => '38:22:d6:3c:da:e7',
      'MANUFACTURER' => 'H3C'
   ];

   /**
    * Adds a new NetworkEquipment in database
    *
    * @retrun NetworkEquipment
    */
   private function addNetworkEquipment() {
      $networkEquipment = new NetworkEquipment();

      $input = [
          'name'        => 'switch H3C',
          'entities_id' => '0'
      ];
      $item_id = (int)$networkEquipment->add($input);
      $this->assertGreaterThan(0, $item_id);
      $this->assertTrue(
         $networkEquipment->getFromDB($item_id)
      );
      return $networkEquipment;
   }

   /**
    * @test
    */
   public function testAddNetworkEquipment() {
      global $DB;

      // Load session rights
      $_SESSION['glpidefault_entity'] = 0;
      Session::initEntityProfiles(2);
      Session::changeProfile(4);

      $pfCND = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkEquipment = $this->addNetworkEquipment();

      $_SESSION['SOURCE_XMLDEVICE'] = $this->source_xmldevice;
      $pfCND->importDevice($networkEquipment);
   }


   /**
    * @test
    */
   public function NewNetworkEquipmentHasPorts() {
      $networkports = getAllDatasFromTable('glpi_networkports');

      foreach ($networkports as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $networkports[$id] = $data;
      }

      $this->assertEquals($this->networkports_reference,
                          $networkports,
                          "Network ports does not match reference on first update");

   }


   /**
    * @test
    */
   public function NewNetworkEquipmentHasIpAdresses() {
      $ipaddresses = getAllDatasFromTable('glpi_ipaddresses');

      $this->assertEquals($this->ipaddresses_reference,
                          $ipaddresses,
                          "IP addresses does not match reference on first update");

   }


   /**
    * @test
    * @depends testAddNetworkEquipment
    */
   public function UpdateNetworkEquipment() {

      // Load session rights
      $_SESSION['glpidefault_entity'] = 0;
      Session::initEntityProfiles(2);
      Session::changeProfile(4);

      $pfCND = new PluginFusioninventoryCommunicationNetworkDiscovery();

      $networkEquipment = $this->addNetworkEquipment();

      $_SESSION['SOURCE_XMLDEVICE'] = $this->source_xmldevice;
      $pfCND->importDevice($networkEquipment);

      // Update 2nd time
      $pfCND->importDevice($networkEquipment);
   }


   /**
    * @test
    */
   public function UpdatedNetworkEquipmentHasPorts() {
      $networkports = getAllDatasFromTable('glpi_networkports');

      foreach ($networkports as $id=>$data) {
         unset($data['date_mod']);
         unset($data['date_creation']);
         $networkports[$id] = $data;
      }

      $this->assertEquals($this->networkports_reference,
                          $networkports,
                          "network ports does not match reference on second update");
   }


   /**
    * @test
    */
   public function UpdateNetworkEquipmentHasIpAdresses() {
      $ipaddresses = getAllDatasFromTable('glpi_ipaddresses');

      $this->assertEquals(
         $this->ipaddresses_reference,
         $ipaddresses,
         "IP addresses does not match reference on second update:\n".
         print_r($this->ipaddresses_reference, true)."\n".
         print_r($ipaddresses, true)."\n"
      );

   }


}
