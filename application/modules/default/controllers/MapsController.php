<?php

/**
 * MapsController
 *
 * 
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
* Controller for Maps
*/

class MapsController extends App_Controller_Base {

    /**
     * MapsController init
     */
    public function init() {
        parent::init();
        $base_url = Zend_Registry::get('baseurl');
        $this->view->headScript()->appendFile($base_url . '/js/OpenLayers-2.13/OpenLayers.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/html2canvas.js');
    }

    /**
     * MapsController index
     */
    public function indexAction() {
        
    }

    /**
     * MOS
     */
    public function mosAction() {
        $id = $this->_request->getParam('id', '');
        if ($id == 4 || $id == "") {
            $form = new Form_Maps_Mos();
            $form->province->setValue($this->_identity->getProvinceId());
            $form->product->setValue('6');
     
            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('mos');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/mos-district.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        } else {

            $form = new Form_Maps_Mos();

            if ($this->_identity->getProvinceId()) {
                $form->prov->setValue($this->_identity->getProvinceId());
            }
            if ($this->_identity->getDistrictId()) {
                $form->dist->setValue($this->_identity->getDistrictId());
            } else {
                $form->dist->setValue('33');
            }
            $form->dist->setValue('33');
            $form->product->setValue('6');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('mos-tehsil');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/mos-tehsil.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        }
    }

    /**
     * AMC
     */
    public function amcAction() {
        $id = $this->_request->getParam('id', '');
        if ($id == 4 || $id == "") {
            $form = new Form_Maps_Mos();
            $form->province->setValue($this->_identity->getProvinceId());
            $form->product->setValue('6');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('amc');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/amc-district.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/IntervalLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        } else {

            $form = new Form_Maps_Mos();
            if ($this->_identity->getProvinceId()) {
                $form->prov->setValue($this->_identity->getProvinceId());
            }
            if ($this->_identity->getDistrictId()) {
                $form->dist->setValue($this->_identity->getDistrictId());
            } else {
                $form->dist->setValue('all');
            }
            $form->dist->setValue('all');
            $form->product->setValue('26');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('amc-tehsil');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/amc-tehsil.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/IntervalLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        }
    }

    /**
     * Reporting Rate
     */
    public function reportingRateAction() {

        $id = $this->_request->getParam('id', '');
        if ($id == 4 || $id == "") {
            $form = new Form_Maps_Mos();
            $form->province->setValue($this->_identity->getProvinceId());
            $form->product->setValue('6');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;
            $this->render('reporting-district');
            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/reporting-district.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        } else {

            $form = new Form_Maps_Mos();
            if ($this->_identity->getProvinceId()) {
                $form->prov->setValue($this->_identity->getProvinceId());
            }

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;
            $this->render('reporting-tehsil');
            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/reporting-tehsil.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        }
    }

    /**
     * Wastages
     */
    public function wastagesAction() {

        $id = $this->_request->getParam('id', '');
        if ($id == 4 || $id == "") {
            $form = new Form_Maps_Mos();
            $form->province->setValue($this->_identity->getProvinceId());
            $form->product->setValue('6');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;
            $this->render('wastages-district');
            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/wastages-district.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        } else {
            $form = new Form_Maps_Mos();
            if ($this->_identity->getProvinceId()) {
                $form->prov->setValue($this->_identity->getProvinceId());
            }

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));
            $form->product->setValue('6');

            $this->view->form = $form;
            $this->render('wastages-tehsil');
            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/wastages-tehsil.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        }
    }

    /**
     * Wastages Reporting
     */
    public function wastagesReportingAction() {
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->product->setValue('6');

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        if ($day > 10) {
            $date->sub('1', 'MM');
        } else {
            $date->sub('2', 'MM');
        }
        $form->year->setValue($date->get(Zend_Date::YEAR));
        $form->month->setValue($date->get(Zend_Date::MONTH));

        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/wastagesReportingLegend.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download2Frame.js');
    }

    /**
     * Expiry Alert
     */
    public function expiryAlertAction() {

        $id = $this->_request->getParam('id', '');
        if ($id == 4 || $id == "") {
            $form = new Form_Maps_Mos();
            $form->province->setValue($this->_identity->getProvinceId());
            $form->product->setValue('26');

            $this->view->form = $form;

            $this->render('expiry-district');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/expiry-district.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        } else {
            $form = new Form_Maps_Mos();
            if ($this->_identity->getProvinceId()) {
                $form->prov->setValue($this->_identity->getProvinceId());
            }
            if ($this->_identity->getDistrictId()) {
                $form->dist->setValue($this->_identity->getDistrictId());
            } else {
                $form->dist->setValue('33');
            }
            $form->product->setValue('26');

            $this->view->form = $form;

            $this->render('expiry-tehsil');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/expiry-tehsil.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        }
    }

    /**
     * Vaccine Coverage
     */
    public function vaccineCoverageAction() {

        $id = $this->_request->getParam('id', '');
        if ($id == 4 || $id == "") {
            $form = new Form_Maps_Mos();
            $form->province->setValue($this->_identity->getProvinceId());
            $form->product->setValue('6');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('coverage-district');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/coverage-district.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        } else {

            $form = new Form_Maps_Mos();
            if ($this->_identity->getProvinceId()) {
                $form->prov->setValue($this->_identity->getProvinceId());
            }
            if ($this->_identity->getDistrictId()) {
                $form->dist->setValue($this->_identity->getDistrictId());
            } else {
                $form->dist->setValue('33');
            }
            $form->dist->setValue('33');
            $form->product->setValue('6');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('coverage-tehsil');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/coverage-tehsil.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Legend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        }
    }

    /**
     * Cold Chain Capacity
     */
    public function coldChainCapacityAction() {

        $id = $this->_request->getParam('id', '');
        if ($id == 4 || $id == "") {
            $form = new Form_Maps_Mos();
            $form->province->setValue($this->_identity->getProvinceId());
            $form->coldchain_type->setValue('1');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('cold-chain-district');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/ccc-district.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/IntervalLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        } else {

            $form = new Form_Maps_Mos();
            if ($this->_identity->getProvinceId()) {
                $form->prov->setValue($this->_identity->getProvinceId());
            }
            if ($this->_identity->getDistrictId()) {
                $form->dist->setValue($this->_identity->getDistrictId());
            } else {
                $form->dist->setValue('33');
            }
            $form->coldchain_type->setValue('1');

            $date = new Zend_Date();
            $day = $date->get(Zend_Date::DAY);
            if ($day > 10) {
                $date->sub('1', 'MM');
            } else {
                $date->sub('2', 'MM');
            }
            $form->year->setValue($date->get(Zend_Date::YEAR));
            $form->month->setValue($date->get(Zend_Date::MONTH));

            $this->view->form = $form;

            $this->render('cold-chain-tehsil');

            $baseurl = Zend_Registry::get('baseurl');

            $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/ccc-tehsil.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/IntervalLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/Filter.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/refineLegend.js');
            $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
        }
    }

    /**
     * Demographic
     */
    public function demographicAction() {
        $baseurl = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

    /**
     * Batch Tracking
     */
    public function batchTrackingAction() {

        $form = new Form_Maps_Mos();
        $form->batch_no->setValue('AOPVB977AA');
        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->headLink()->appendStylesheet($baseurl . '/css/default/maps/map.css');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/symbology.js');
        $this->view->inlineScript()->appendFile($baseurl . '/js/default/maps/download.js');
    }

}
