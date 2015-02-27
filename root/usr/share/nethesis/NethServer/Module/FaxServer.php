<?php
namespace NethServer\Module;

/*
 * Copyright (C) 2012 Nethesis S.r.l.
 *
 * This script is part of NethServer.
 *
 * NethServer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * NethServer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NethServer.  If not, see <http://www.gnu.org/licenses/>.
 */

use Nethgui\System\PlatformInterface as Validate;


/**
 * Configure Hylafax and modem
 *
 * @author Giacomo Sanchietti <giacomo.sanchietti@nethesis.it>
 * @since 1.0
 */
class FaxServer extends \Nethgui\Controller\AbstractController
{
    /**
     *
     *
     * @var \Nethgui\System\ProcessInterface
     */
    private $process;

    /**
     * @var Array list of valid devices with labels
     */
    private $devices = array('ttyS0'=>'ttyS0_label','ttyS1'=>'ttyS1_label','ttyS2'=>'ttyS2_label','ttyACM0'=>'ttyACM0_label','ttyUSB0'=>'ttyUSB0_label', 'ttyIAX' => 'ttyIAX_label');
    
    /**
     * @var Array list of valid device labels
     */
    private $deviceLabels = array('ttyS0','ttyS1','ttyS2','ttyACM0','ttyUSB0');


    protected function initializeAttributes(\Nethgui\Module\ModuleAttributesInterface $base)
    {
        return \Nethgui\Module\SimpleModuleAttributesProvider::extendModuleAttributes($base, 'Configuration', 50);
    }

    public function initialize()
    {
        parent::initialize();
        $this->declareParameter('CountryCode', Validate::ANYTHING, array('configuration', 'hylafax', 'CountryCode'));
        $this->declareParameter('AreaCode', Validate::ANYTHING, array('configuration', 'hylafax', 'AreaCode'));
        $this->declareParameter('FaxNumber', Validate::ANYTHING, array('configuration', 'hylafax', 'FaxNumber'));
        $this->declareParameter('FaxName', Validate::ANYTHING, array('configuration', 'hylafax', 'FaxName'));

        $fdValidator = $this->createValidator()->memberOf(array_keys($this->devices));
        $this->declareParameter('FaxDeviceType', $this->createValidator()->memberOf(array('custom','known')), array());
        $this->declareParameter('FaxDeviceCustom', Validate::ANYTHING, array());
        $this->declareParameter('FaxDeviceKnown', $fdValidator, array());
        $this->declareParameter('FaxDevice', FALSE, array('configuration', 'hylafax', 'FaxDevice')); # not accessibile from UI, position is IMPORTANT
        $modeValidator = $this->createValidator()->memberOf(array('send','receive','both'));
        $this->declareParameter('Mode', $modeValidator, array('configuration', 'hylafax', 'Mode'));
        $this->declareParameter('WaitDialTone', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'WaitDialTone'));
        $this->declareParameter('PBXPrefix', Validate::ANYTHING, array('configuration', 'hylafax', 'PBXPrefix'));
        $nmValidator = $this->createValidator()->memberOf(array('always','never','errors'));
        $this->declareParameter('NotifyMaster', $nmValidator, array('configuration', 'hylafax', 'NotifyMaster'));

        $this->declareParameter('SendToType', $this->createValidator()->memberOf(array('faxmaster','custom')), array());
        $this->declareParameter('SendToCustom', Validate::EMAIL, array());
        $this->declareParameter('SendTo', FALSE, array('configuration', 'hylafax', 'SendTo')); # not accessibile from UI, position is IMPORTANT
        $this->declareParameter('DispatchFileTypeList', Validate::ANYTHING_COLLECTION, array('configuration', 'hylafax', 'DispatchFileType', ','));
        $this->declareParameter('NotifyFileTypeList', Validate::ANYTHING_COLLECTION, array('configuration', 'hylafax', 'NotifyFileType', ','));

        $this->declareParameter('ClientShowReceived', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'ClientShowReceived'));
        $this->declareParameter('PrintReceived', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'PrintReceived'));
        $this->declareParameter('PrinterName', Validate::ANYTHING, array('configuration', 'hylafax', 'PrinterName'));
        $this->declareParameter('SambaFax', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'SambaFax'));
        $this->declareParameter('SendReport', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'SendReport'));
        $this->declareParameter('SummaryReport', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'SummaryReport'));
    }

    public function readFaxDeviceKnown()
    {
        if ($this->parameters["FaxDeviceType"] === 'known') {
             return $this->parameters["FaxDevice"];
        } else {
             return "";
        }
    }

    public function writeFaxDeviceKnown($value)
    {
        if ($this->parameters["FaxDeviceType"] === 'known') {
             $this->parameters["FaxDevice"] = $value;
        }
        return true;
    }

    public function readFaxDeviceCustom()
    {
        if ($this->parameters["FaxDeviceType"] === 'custom') {
             return $this->parameters["FaxDevice"];
        } else {
             return "";
        }
    }

    public function writeFaxDeviceCustom($value)
    {
        if ($this->parameters["FaxDeviceType"] === 'custom') {
             $this->parameters["FaxDevice"] = $value;
        }
        return true;
    }

   public function readFaxDeviceType()
    {
        $current = $this->getPlatform()->getDatabase('configuration')->getProp('hylafax','FaxDevice');
        if (in_array($current,array_keys($this->devices))) {
            return "known";
        } else {
            return "custom";
        }
    }

    public function writeFaxDeviceType($value)
    {
        return true;
    }


    public function readSendToType()
    {
        $current = $this->getPlatform()->getDatabase('configuration')->getProp('hylafax','SendTo');
        if($current == "faxmaster") {
            return "faxmaster";
        } else {
            return "custom";
        }
    }

    public function writeSendToType($value)
    {
        if ($this->parameters["SendToType"] === 'faxmaster') {
             $this->parameters["SendTo"] = 'faxmaster';
             return 'faxmaster';
        } else {
             return "";
        }
    }

    public function readSendToCustom()
    {
        if ($this->parameters["SendToType"] === 'custom') {
             return $this->parameters["SendTo"];
        } else {
             return "";
        }
    }
    
    public function writeSendToCustom($value)
    {
        if ($this->parameters["SendToType"] === 'custom') {
             $this->parameters["SendTo"] = $value;
        }
        return true;
    }


    protected function onParametersSaved($changes)
    {
        $this->getPlatform()->signalEvent('nethserver-hylafax-save@post-process');
    }

    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
        parent::prepareView($view);
        $view['ModeDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt . '_label'));
        }, array('receive', 'send', 'both'));

        $devicies = array();
        foreach($this->devices as $device => $label) {
            $devices[] = array($device, $view->translate($label));
        }
        $view['FaxDeviceKnownDatasource'] = $devices;
        $view['DispatchFileTypeListDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt . '_label'));
        }, array('pdf', 'tif', 'ps'));
        $view['NotifyFileTypeListDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt . '_label'));
        }, array('pdf', 'tif', 'ps'));
        $view['NotifyMasterDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt . '_label'));
        }, array('always', 'never', 'errors'));
        
        $view['PrinterNameDatasource'] = $this->readPrinterNameDatasource($view);

    }

    private function readPrinterNameDatasource(\Nethgui\View\ViewInterface $view)
    {
        $bin = "/usr/bin/lpstat";
        $printers[] = array('',$view->translate('no_printer_label'));
        if ($this->getPhpWrapper()->file_exists($bin)) { # make call testable
            $printers = array();
            $lines = array();
            $lines = $this->getPlatform()->exec("$bin -a")->getOutputArray();
            foreach ($lines as $l) {
                $fields=explode(' ',$l);
                $field = trim($fields[0]);
                if ($field)
                    $printers[]=array($field,$field);
            }
        }
  
        return $printers;
    }

}
