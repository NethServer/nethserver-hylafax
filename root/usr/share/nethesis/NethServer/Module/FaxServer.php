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

    protected function initializeAttributes(\Nethgui\Module\ModuleAttributesInterface $base)
    {
        return \Nethgui\Module\SimpleModuleAttributesProvider::extendModuleAttributes($base, 'Configuration', 50);
    }


    public  function initialize()
    {
        parent::initialize();
        $this->declareParameter('CountryCode', Validate::ANYTHING, array('configuration', 'hylafax', 'CountryCode'));
        $this->declareParameter('AreaCode', Validate::ANYTHING, array('configuration', 'hylafax', 'AreaCode'));
        $this->declareParameter('FaxNumber', Validate::ANYTHING, array('configuration', 'hylafax', 'FaxNumber'));
        $this->declareParameter('FaxName', Validate::ANYTHING, array('configuration', 'hylafax', 'FaxName'));

        $this->declareParameter('FaxDevice', Validate::ANYTHING, array('configuration', 'hylafax', 'FaxDevice'));
        $this->declareParameter('Mode', Validate::ANYTHING, array('configuration', 'hylafax', 'Mode'));
        $this->declareParameter('WaitDialTone', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'WaitDialTone'));
        $this->declareParameter('PBXPrefix', Validate::ANYTHING, array('configuration', 'hylafax', 'PBXPrefix'));

        $this->declareParameter('SendTo', Validate::ANYTHING, array('configuration', 'hylafax', 'SendTo'));
        $this->declareParameter('DispatchFileTypeList', Validate::ANYTHING_COLLECTION, array('configuration', 'hylafax', 'DispatchFileType', ','));
        $this->declareParameter('NotifyFileTypeList', Validate::ANYTHING_COLLECTION, array('configuration', 'hylafax', 'NotifyFileType', ','));

        $this->declareParameter('ClientShowReceived', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'ClientShowReceived'));
        $this->declareParameter('PrintReceived', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'PrinterReceived'));
        $this->declareParameter('PrinterName', Validate::ANYTHING, array('configuration', 'hylafax', 'PrinterName'));
        $this->declareParameter('SambaFax', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'SambaFax'));
        $this->declareParameter('SendReport', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'SendReport'));
        $this->declareParameter('SummaryReport', Validate::SERVICESTATUS, array('configuration', 'hylafax', 'SummaryReport'));

    }

    protected function onParametersSaved($changes)
    {
        $this->getPlatform()->signalEvent('nethserver-hylafax-save@post-process');
    }

    public function prepareView(\Nethgui\View\ViewInterface $view)
    {
        parent::prepareView($view);
        $view['ModeDatasource'] = $this->readModeDatasource($view);
        $view['FaxDeviceDatasource'] = $this->readFaxDeviceDatasource($view);
        $view['PrinterNameDatasource'] = $this->readPrinterNameDatasource($view);
        $view['SendToDatasource'] = $this->readSendToDatasource();
        $view['DispatchFileTypeListDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt . '_label'));
        }, array('pdf', 'tiff', 'ps'));
        $view['NotifyFileTypeListDatasource'] = array_map(function($fmt) use ($view) {
            return array($fmt, $view->translate($fmt . '_label'));
        }, array('pdf', 'tiff', 'ps'));
    }

    private function readModeDatasource(\Nethgui\View\ViewInterface $view)
    {
        return array(
            array('receive',$view->translate('receive_label')),
            array('send',$view->translate('send_label')),
            array('both',$view->translate('both_label')),
        );
    }

    private function readFaxDeviceDatasource(\Nethgui\View\ViewInterface $view)
    {
        return array(
            array('ttyS0',$view->translate('ttyS0_label')),
            array('ttyS1',$view->translate('ttyS1_label')),
            array('ttyS2',$view->translate('ttyS2_label')),
            array('ttyACM0',$view->translate('ttyACM0_label')),
            array('ttyUSB0',$view->translate('ttyUSB0_label')),
            array('iax',$view->translate('iax_label')),
        );
    }
    
    private function readPrinterNameDatasource(\Nethgui\View\ViewInterface $view)
    {
        $bin = "/usr/bin/lpstat";
        $printers[] = array('',$view->translate('no_printer_label'));
        if (file_exists($bin)) {
            $printers = array();
            $lines = array();
            exec("$bin -a",$lines);
            foreach ($lines as $l) {
              $fields=split(' ',$l);
               $printers[]=array($fields[0],$fields[0]);
            }
        }
  
        return $printers;
    }

     private function readSendToDatasource()
    {
        $res = array();

        foreach ($this->getPlatform()->getDatabase('accounts')->getAll('pseudonym') as $key => $prop) {
            $res[] = array($key, $key);
        }

        return $res;
    }


}
