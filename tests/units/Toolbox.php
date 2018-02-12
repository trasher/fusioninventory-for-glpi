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

class PluginFusioninventoryToolbox extends \CommonTestCase {

   public function testFormatJson() {
      $expected = <<<JSON
{
    "test_text": "Lorem Ipsum",
    "test_number": 1234,
    "test_float": 1234.5678,
    "test_array": [
        1,
        2,
        3,
        4,
        "lorem_ipsum"
    ],
    "test_hash": {
        "lorem": "ipsum",
        "ipsum": "lorem"
    }
}
JSON;

      $this->string($expected)
         ->isIdenticalTo(
            \PluginFusioninventoryToolbox::formatJson(
               json_encode([
                  'test_text' => 'Lorem Ipsum',
                  'test_number' => 1234,
                  'test_float' => 1234.5678,
                  'test_array' => [ 1,2,3,4, 'lorem_ipsum' ],
                  'test_hash' => ['lorem' => 'ipsum', 'ipsum' => 'lorem']
               ])
            )
         )
      ;
   }

   public function testIsAFusionInventoryDevice() {
      $computer = new \Computer();

      $this->boolean(\PluginFusioninventoryToolbox::isAFusionInventoryDevice($computer))
         ->isFalse();

      $values = ['name'         => 'comp',
                 'is_dynamic'   => 1,
                 'entities_id'  => 0,
                 'is_recursive' => 0];
      $computers_id = $computer->add($values);
      $this->integer($computers_id)->isGreaterThan(0);

      $this->boolean(
         $computer->getFromDB($computers_id)
      )->isTrue();

      $this->boolean(\PluginFusioninventoryToolbox::isAFusionInventoryDevice($computer))
         ->isFalse();

      $pfComputer = new \PluginFusioninventoryInventoryComputerComputer();
      $this->integer(
         (int)$pfComputer->add(['computers_id' => $computers_id])
      )->isGreaterThan(0);
      $this->boolean(\PluginFusioninventoryToolbox::isAFusionInventoryDevice($computer))
         ->isTrue();

      $printer = new \Printer();
      $values  = ['name'         => 'printer',
                  'is_dynamic'   => 1,
                  'entities_id'  => 0,
                  'is_recursive' => 0];
      $printers_id = $printer->add($values);
      $this->integer($printers_id)->isGreaterThan(0);
      $this->boolean(
         $printer->getFromDB($printers_id)
      )->isTrue();
      $this->boolean(\PluginFusioninventoryToolbox::isAFusionInventoryDevice($printer))
         ->isFalse();

      $pfPrinter = new \PluginFusioninventoryPrinter();
      $this->integer(
         (int)$pfPrinter->add(['printers_id' => $printers_id])
      )->isGreaterThan(0);
      $this->boolean(\PluginFusioninventoryToolbox::isAFusionInventoryDevice($printer))
         ->isFalse();

      $values  = ['name'         => 'printer2',
                  'is_dynamic'   => 0,
                  'entities_id'  => 0,
                  'is_recursive' => 0];
      $printers_id_2 = $printer->add($values);
      $this->integer($printers_id_2)->isGreaterThan(0);
      $this->boolean(
         $printer->getFromDB($printers_id_2)
      )->isTrue();
      $this->integer(
         (int)$pfPrinter->add(['printers_id' => $printers_id_2])
      )->isGreaterThan(0);
      $this->boolean(\PluginFusioninventoryToolbox::isAFusionInventoryDevice($printer))
         ->isFalse();
   }

   public function testAddDefaultStateIfNeeded() {
      $input = [];

      $state = new \State();
      $states_id_computer = $state->importExternal('state_computer');
      $states_id_snmp = $state->importExternal('state_snmp');

      $config = new \PluginFusioninventoryConfig();
      $config->updateValue('states_id_snmp_default', $states_id_snmp);
      $config->updateValue('states_id_default', $states_id_computer);

      $result = \PluginFusioninventoryToolbox::addDefaultStateIfNeeded('computer', $input);
      $this->array(['states_id' => $states_id_computer])->isIdenticalTo($result);

      $result = \PluginFusioninventoryToolbox::addDefaultStateIfNeeded('snmp', $input);
      $this->array(['states_id' => $states_id_snmp])->isIdenticalTo($result);

      $result = \PluginFusioninventoryToolbox::addDefaultStateIfNeeded('foo', $input);
      $this->array($result)->isEmpty();
   }
}
