<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2018 Teclib' and contributors.
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

namespace tests\units;

use \atoum;

/* Test for inc/toolbox.class.php */

class PluginFusioninventoryAgent extends \RestoreDatabaseTestCase {
   private $agent;

   public function beforeTestMethod($method) {
      $this->agent = $this->addAgent();
   }

   private function addAgent() {
      $pfAgent = new \PluginFusioninventoryAgent();
      $agent_id = $pfAgent->add(
         [
            'name'           => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
            'device_id'      => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
            'computers_id'   => 100
         ]
      );
      $this->integer($agent_id)->isGreaterThan(0);
      return $pfAgent;
   }

   public function testLinkNewAgentWithAsset() {
      $result = $this->agent->setAgentWithComputerid(
         100,
         'port004.bureau.siprossii.com-2013-01-01-16-27-27',
         1
      );
      $this->boolean($result)->isTrue("Problem when linking agent to asset");
   }

   public function agentExists() {
      $a_agents = $this->agent->find(
         "`device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27'"
      );
      $this->array($a_agents)
         ->hasSize(1);
   }

   public function newAgentLinkedToSameAsset() {
      $agent = $this->agent->find(
         "`device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27'",
         "",
         1
      );
      $this->array($agent)
         ->hasSize(1);

      $current_agent = current($agent);
      $agent_id = $current_agent['id'];
      $agent_from_asset = current($this->agent->find("`computers_id` = '100'"));

      $this->integer($agent_id)->isIdenticalTo($agent_from_asset['id']);
   }

   public function newAgentCheckEntity() {
      $a_agents = current($this->agent->find("`computers_id`='100'"));
      $this->integer($a_agents['entities_id'])->isIdenticalTo(1);
   }

   public function newAgentChangeEntity() {
      // Load Agent
      $this->boolean(
         $this->agent->getFromDBByQuery(
            "WHERE `device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27' ".
            "LIMIT 1"
         )
      )->isTrue("Could not load agent");

      $this->agent->setAgentWithComputerid(
         100,
         'port004.bureau.siprossii.com-2013-01-01-16-27-27',
         0
      );

      $a_agents = current($this->agent->find("`computers_id`='100'"));
      $this->integer($a_agents['entities_id'])->isIdenticalTo(0);
   }

   public function udpateNotLog() {
      global $DB;
      // test update last_contact field but not have logs/ history

      /*$DB->connect();*/

      $query = "UPDATE glpi_plugin_fusioninventory_agents SET `last_contact`='2015-01-01 00:00:01'";
      $DB->query($query);
      $arrayinventory = [
          'DEVICEID' => 'port004.bureau.siprossii.com-2013-01-01-16-27-27',
      ];
      $log = new Log();
      $nb = count($log->find());

      $this->agent->importToken($arrayinventory);
      $this->boolean(
         $this->agent->getFromDBByQuery(
            "WHERE `device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27' ".
            "LIMIT 1"
         )
      )->isTrue();
      $this->string(strstr($this->agent->fields['last_contact'], date('Y-m-d')))
         ->contains(date('Y-m-d'));
      $this->integer(count($log->find()))->isIdenticalTo($nb);
   }

   public function disconnectAgent() {
      $agent    = $this->agent->find(
         "`device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27'"
      );
      $this->array($agent)->hasSize(1);
      $current_agent = current($agent);
      $agent_id      = $current_agent['id'];

      //Disconnect the agent from the computer
      $this->agent->disconnect(['computers_id' => 100, 'id' => $agent_id]);
      $count = countElementsInTable(
         'glpi_plugin_fusioninventory_inventorycomputercomputers',
         "`computers_id`='100'"
      );
      $this->integer($count)->isIdenticalTo(0);

      //Check that computers_id has been set to 0
      $this->boolean(
         $this->agent->getFromDB($agent_id)
      )->isTrue();
      $this->integer($this->agent->fields['computers_id'])->isIdenticalTo(0);
   }
}
