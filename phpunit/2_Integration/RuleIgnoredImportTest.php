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

class RuleIgnoredImport extends Common_TestCase {


   protected function setUp() {
      global $DB;

      parent::setUp();

      self::restore_database();

      $DB->query("UPDATE `glpi_rules`
         SET `is_active`='0'
         WHERE `sub_type`='PluginFusioninventoryInventoryRuleImport'");

      // Add a rule to ignore import
      // Create rule for import into unknown devices
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = [];
      $input['is_active']=1;
      $input['name']='Ignore import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = 200;
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "name";
      $input['pattern']= '*';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_ignore_import';
      $input['value'] = '1';
      $ruleaction->add($input);
   }


   /**
    * @test
    * computer inventory
    */
   public function IgnoreComputerImport() {
      global $DB;

      $_SESSION['glpiactive_entity']         = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities']       = 1;
      $_SESSION['glpiname']                  = 'glpi';

      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $computer        = new Computer();
      $pfUnmanaged     = new PluginFusioninventoryUnmanaged();
      $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
      $rule            = new Rule();

      $a_inventory = [];
      $a_inventory['CONTENT']['HARDWARE'] = [
          'NAME' => 'pc1'
      ];
      $a_inventory['CONTENT']['SOFTWARES'][] = [];

      // ** Add agent
      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents_id = $pfAgent->add(['name'      => 'pc-2013-02-13',
                                         'device_id' => 'pc-2013-02-13']);
      $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

      $a_computers = $computer->find();
      $this->assertEquals(0, count($a_computers), 'Computer may not be added');

      $a_unknown = $pfUnmanaged->find();
      $this->assertEquals(0, count($a_unknown), 'Unmanaged may not be added');

      $a_ignored = $pfIgnoredimportdevice->find();
      $this->assertEquals(1, count($a_ignored), 'May have only one ignored device import');

      $rule_values = $rule->find("`name`='Ignore import'");
      $this->assertEquals(1, count($rule_values), 'Ignore import rule not found');
      $rule_ignore = array_pop($rule_values);

      $a_ignore = current($a_ignored);
      $a_reference = [
          'id'                               => '1',
          'name'                             => 'pc1',
          'itemtype'                         => 'Computer',
          'entities_id'                      => '0',
          'ip'                               => null,
          'mac'                              => null,
          'rules_id'                         => $rule_ignore['id'],
          'method'                           => 'inventory',
          'serial'                           => '',
          'uuid'                             => '',
          'plugin_fusioninventory_agents_id' => '1'
      ];
      unset($a_ignore['date']);
      $this->assertEquals($a_reference, $a_ignore, 'Ignored import computer');
      $pfIgnoredimportdevice->delete($a_ignore);
   }


   /**
    * @test
    * network discovery
    */
   public function IgnoreNetworkDiscoveryImport() {
      global $DB;

      $a_inventory = [
          'DNSHOSTNAME' => 'pctest',
          'ENTITY'      => 0,
          'IP'          => '192.168.20.3'
      ];

      $pfCommunicationNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $computer = new Computer();
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();
      $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
      $GLPIlog = new GLPIlogs();

      $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = 1;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id']    = '1';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype']    = 'Computer';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['state']       = 0;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']     = '';

      $pfCommunicationNetworkDiscovery->sendCriteria($a_inventory);

      $a_computers = $computer->find();
      $this->assertEquals(0, count($a_computers), 'Computer may not be added');

      $a_unknown = $pfUnmanaged->find();
      $this->assertEquals(0, count($a_unknown), 'Unmanaged may not be added');

      $a_ignored = $pfIgnoredimportdevice->find();
      $this->assertEquals(1, count($a_ignored), 'May have only one ignored device import');

   }


   /**
    * @test
    * network inventory
    */
   public function IgnoreNetworkInventoryImport() {
      $this->mark_incomplete();
   }


}
