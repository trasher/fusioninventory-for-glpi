<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryWakeonlan extends PluginFusioninventoryCommunication {

   // Get all devices and put in taskjobstate each task for each device for each agent
   function prepareRun($taskjobs_id) {
      global $DB;

      $pfTask = new PluginFusioninventoryTask();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();

      $uniqid = uniqid();

      $pfTaskjob->getFromDB($taskjobs_id);
      $pfTask->getFromDB($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);

      $communication = $pfTask->fields['communication'];
      $a_definitions = importArrayFromDB($pfTaskjob->fields['definition']);

      $a_actions = importArrayFromDB($pfTaskjob->fields['action']);

      $a_agentList = array();

      if ((!strstr($pfTaskjob->fields['action'], '".1"'))
            AND (!strstr($pfTaskjob->fields['action'], '".2"'))) {

         foreach($a_actions as $a_action) {
            if ((!in_array('.1', $a_action))
               AND (!in_array('.2', $a_action))) {

               $query = "SELECT `glpi_plugin_fusioninventory_agents`.`id` as `a_id`, ip, subnet, token FROM `glpi_plugin_fusioninventory_agents`
                  LEFT JOIN `glpi_networkports` ON `glpi_networkports`.`items_id` = `glpi_plugin_fusioninventory_agents`.`items_id`
                  LEFT JOIN `glpi_computers` ON `glpi_computers`.`id` = `glpi_plugin_fusioninventory_agents`.`items_id`
                  WHERE `glpi_networkports`.`itemtype`='Computer'
                     AND  `glpi_plugin_fusioninventory_agents`.`id`='".current($a_action)."'";
               $result = $DB->query($query);
               if ($result) {
                  while ($data=$DB->fetch_array($result)) {
                     if ($communication == 'push') {
                        $agentStatus = $pfTaskjob->getStateAgent(1, $data['a_id']);
                        if ($agentStatus ==  true) {
                           $a_agentList[] = $data['a_id'];
                        }
                     } else if ($communication == 'pull') {
                        $a_agentList[] = $data['a_id'];
                     }
                  }
               }
            }
         }
      }
      /*
       * Case 3 : dynamic agent
       */
      else if (strstr($pfTaskjob->fields['action'], '".1"')) {
         $a_agentList = $this->getAgentsSubnet(count($a_definitions), $communication);
      }
      /*
       * Case 4 : dynamic agent same subnet
       */
      else if (in_array('.2', $a_actions)) {
         $subnet = '';
         foreach($a_definitions as $items_id) {
            $sql = "SELECT * FROM `glpi_networkports`
               WHERE `items_id`='".$items_id."'
                  AND `itemtype`='Computer'
                  AND `mac`!='' ";
            $result = $DB->query($sql);
            if ($result) {
               while ($data=$DB->fetch_array($result)) {
                  $subnet = $data['subnet'];
               }
            }
         }
         if ($subnet != '') {
            $a_agentList = $this->getAgentsSubnet(count($a_definitions), $communication, $subnet);
         }
      }

      if (count($a_agentList) == '0') {
         $a_input = array();
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state'] = 1;
         $a_input['plugin_fusioninventory_agents_id'] = 0;
         $a_input['itemtype'] = 'Computer';
         $a_input['items_id'] = 0;
         $a_input['uniqid'] = $uniqid;
         $Taskjobstates_id = $pfTaskjobstate->add($a_input);
            //Add log of taskjob
            $a_input['plugin_fusioninventory_taskjobstates_id'] = $Taskjobstates_id;
            $a_input['state'] = 7;
            $a_input['date'] = date("Y-m-d H:i:s");
            $pfTaskjoblog->add($a_input);

         $pfTaskjobstate->changeStatusFinish($Taskjobstates_id,
                                                                 0,
                                                                 'Computer',
                                                                 1,
                                                                 "Unable to find agent to run this job");
      } else {
         $nb_computers = ceil(count($a_definitions) / count($a_agentList));

         $a_input = array();
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state'] = 0;
         $a_input['itemtype'] = 'Computer';
         $a_input['uniqid'] = $uniqid;
         while(count($a_definitions) != 0) {
            $agent_id = array_pop($a_agentList);
            $a_input['plugin_fusioninventory_agents_id'] = $agent_id;
            for ($i=0; $i < $nb_computers; $i++) {
                //Add jobstate and put status
                $a_input['items_id'] = current(array_pop($a_definitions));
                $Taskjobstates_id = $pfTaskjobstate->add($a_input);
                  //Add log of taskjob
                  $a_input['plugin_fusioninventory_taskjobstates_id'] = $Taskjobstates_id;
                  $a_input['state'] = 7;
                  $a_input['date'] = date("Y-m-d H:i:s");
                  $pfTaskjoblog->add($a_input);
                  unset($a_input['state']);
                  if ($communication == "push") {
                     $_SESSION['glpi_plugin_fusioninventory']['agents'][$agent_id] = 1;
                  }
            }
         }
      }
      $pfTaskjob->fields['status'] = 1;
      $pfTaskjob->update($pfTaskjob->fields);

      return $uniqid;
   }



   /**
    *  When agent contact server, this function send datas to agent
    */
   function run($a_Taskjobstates) {

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $NetworkPort                        = new NetworkPort();

      $sxml_option = $this->message->addChild('OPTION');
      $sxml_option->addChild('NAME', 'WAKEONLAN');

      $changestate = 0;
      foreach ($a_Taskjobstates as $data) {
         $a_networkPort = $NetworkPort->find("`itemtype`='Computer' AND `items_id`='".$data['items_id']."' ");
         $computerip = 0;
         foreach ($a_networkPort as $datanetwork) {
            if ($datanetwork['ip'] != "127.0.0.1") {
               if ($datanetwork['mac'] != '') {
                  $computerip++;
                  $sxml_param = $sxml_option->addChild('PARAM');
                  $sxml_param->addAttribute('MAC', $datanetwork['mac']);
                  //$sxml_param->addAttribute('IP', $datanetwork['ip']);

                  if ($changestate == '0') {
                     $pfTaskjobstate->changeStatus($data['id'], 1);
                     $pfTaskjoblog->addTaskjoblog($data['id'],
                                             '0',
                                             'Computer',
                                             '1',
                                             '');
                     $changestate = $pfTaskjobstate->fields['id'];
                  } else {
                     $pfTaskjobstate->changeStatusFinish($data['id'],
                                                                       $data['items_id'],
                                                                       $data['itemtype'],
                                                                       0,
                                                                       "Merged with ".$changestate);
                  }

                  // Update taskjobstate (state = 3 : finish); Because we haven't return of agent on this action
                  $pfTaskjobstate->changeStatusFinish($data['id'],
                                                                        $data['items_id'],
                                                                        $data['itemtype'],
                                                                        0,
                                                                        'WakeOnLan have not return state',
                                                                        1);
               }
            }
         }
         if ($computerip == '0') {
            $pfTaskjobstate->changeStatusFinish($data['id'],
                                                                    $data['items_id'],
                                                                    $data['itemtype'],
                                                                    1,
                                                                    "No IP found on the computer");

         }
      }
      return $this->message;
   }



   function getAgentsSubnet($nb_computers, $communication, $subnet='') {
      global $DB;

      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $OperatingSystem = new OperatingSystem();

      // Number of computers min by agent
      $nb_computerByAgentMin = 20;
      $nb_agentsMax = ceil($nb_computers / $nb_computerByAgentMin);

      // Get ids of operating systems which can make real wakeonlan
      $a_os = $OperatingSystem->find(" `name` LIKE '%Linux%' ");
      $osfind = '(';
      $i = 0;
      foreach ($a_os as $os_id=>$data) {
         $comma = '';
         if ($i > 0) {
            $comma = ',';
         }
         $osfind .= $comma.$os_id;
         $i++;
      }
      $osfind .= ')';
      $pass_count = 1;
      if ($osfind == '()') {
         $osfind = '';
      } else {
         $pass_count++;
         $osfind = 'AND operatingsystems_id IN '.$osfind;
      }

      $a_agentList = array();
      for ($pass = 0; $pass < $pass_count; $pass++) {

         if ($pass == "1") {
            // It's not linux
            $osfind = str_replace('AND operatingsystems_id IN ', 'AND operatingsystems_id NOT IN ', $osfind);
         }

         if ($subnet != '') {
            $subnet = " AND subnet='".$subnet."' ";
         }
         $a_agents = $pfAgentmodule->getAgentsCanDo('WAKEONLAN');
         $a_agentsid = array();
         foreach($a_agents as $a_agent) {
            $a_agentsid[] = $a_agent['id'];
         }
         if (count($a_agentsid) == '0') {
            return $a_agentList;
         }

         $where = " AND `glpi_plugin_fusioninventory_agents`.`ID` IN (";
         $where .= implode(',', $a_agentsid);
         $where .= ")
            AND `ip` != '127.0.0.1' ";

         $query = "SELECT `glpi_plugin_fusioninventory_agents`.`id` as `a_id`, ip, subnet, token FROM `glpi_plugin_fusioninventory_agents`
            LEFT JOIN `glpi_networkports` ON `glpi_networkports`.`items_id` = `glpi_plugin_fusioninventory_agents`.`items_id`
            LEFT JOIN `glpi_computers` ON `glpi_computers`.`id` = `glpi_plugin_fusioninventory_agents`.`items_id`
            WHERE `glpi_networkports`.`itemtype`='Computer'
               ".$subnet."
               ".$osfind."
               ".$where." ";
         if ($result = $DB->query($query)) {
            while ($data=$DB->fetch_array($result)) {
               if ($communication == 'push') {
                  $agentStatus = $pfTaskjob->getStateAgent(1, $data['a_id']);
                  if ($agentStatus ==  true) {
                     if (!in_array($a_agentList,$data['a_id'])) {
                        $a_agentList[] = $data['a_id'];
                        if (count($a_agentList) >= $nb_agentsMax) {
                           return $a_agentList;
                        }
                     }
                  }
               } else if ($communication == 'pull') {
                  if (!in_array($a_agentList,$data['a_id'])) {
                     $a_agentList[] = $data['a_id'];
                     if (count($a_agentList) > $nb_agentsMax) {
                        return $a_agentList;
                     }
                  }
               }
            }
         }
      }
   }
}

?>