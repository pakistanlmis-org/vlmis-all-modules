<?php

/**
 * Api_GeoController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Api
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Controller for Api Geo
*/

class Api_GeoController extends App_Controller_Base {

    /**
     * Api_GeoController init
     */
    public function init() {
        parent::init();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $this->_helper->layout()->disableLayout();
    }

    /**
     * Api_GeoController index
     */
    public function indexAction() {
        echo "Hello";
    }

    /**
     * Get Mos Map Data
     */
    public function getMosMapDataAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        $product = $this->_request->getParam('product', '');
        $level = $this->_request->getParam('level', '');
        $type = $this->_request->getParam('type', '');

        $geoModel = new Model_Geo();
        if ($type == 4) {
            $result = $geoModel->getDistrictMos($year, $month, $province, $product, $level);
        } else if ($type == 5) {
            $result = $geoModel->getTehsilMos($year, $month, $province, $district, $product);
        } else {
            $result = $geoModel->getProvinceMos($year, $month, $province, $product, $level);
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Get Amc Map Data
     */
    public function getAmcMapDataAction() {
        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $product = $this->_request->getParam('product', '');
        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        $amctype = $this->_request->getParam('type', '');
        $level = $this->_request->getParam('level', '');

        $geoModel = new Model_Geo();
        if ($level == 4) {
            $result = $geoModel->getAmcMapData($year, $month, $product, $province, $amctype);
        } else if ($level == 5) {
            $result = $geoModel->getAmcTehsilMapData($year, $month, $province, $district, $product, $amctype);
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Get Reporting Rate
     */
    public function getReportingRateAction() {


        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        $level = $this->_request->getParam('level', '');

        $geoModel = new Model_Geo();

        if ($level == 4) {
            $return = $geoModel->getReportingDistrict($year, $month, $province);
        } else {
            $return = $geoModel->getReportingTehsil($year, $month, $province, $district);
        }


        echo Zend_Json::encode($return);
    }

    /**
     * Get Wastage Map Data
     */
    public function getWastageMapDataAction() {

        $return = array();
        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        $product = $this->_request->getParam('product', '');
        $level = $this->_request->getParam('level', '');

        $geoModel = new Model_Geo();
        $return[0] = $geoModel->getAcceptableWastages($product);
        if ($level == 4) {
            $return[1] = $geoModel->getWastagesDistrict($year, $month, $province, $product);
        } else {
            $return[1] = $geoModel->getWastagesTehsil($year, $month, $province, $district, $product);
        }


        echo Zend_Json::encode($return);
    }

    /**
     * Get Wastages Vs Reporting
     */
    public function getWastagesVsReportingAction() {

        $return = array();

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $product = $this->_request->getParam('product', '');

        $geoModel = new Model_Geo();
        $return[0] = $geoModel->getWastagesRateDistrict($year, $month, $province, $product);
        $return[1] = $geoModel->getReportingDistrict($year, $month, $province);

        echo Zend_Json::encode($return);
    }

    /**
     * Get Expiry Alert
     */
    public function getExpiryAlertAction() {

        $province = $this->_request->getParam('province', '');
        $product = $this->_request->getParam('product', '');
        $district = $this->_request->getParam('district', '');
        $level = $this->_request->getParam('level', '');

        $geoModel = new Model_Geo();

        if ($level == 4) {
            $return = $geoModel->getExpiryDistrict($province, $product);
        } else {
            $return = $geoModel->getExpiryTehsil($province, $district, $product);
        }

        echo Zend_Json::encode($return);
    }

    /**
     * Get Vaccine Coverage
     */
    public function getVaccineCoverageAction() {


        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        $product = $this->_request->getParam('product', '');

        $geoModel = new Model_Geo();
        if ($district != "") {
            $return = $geoModel->getVaccineCoverageTehsil($year, $month, $province, $district, $product);
        } else {
            $return = $geoModel->getVaccineCoverage($year, $month, $province, $product);
        }

        echo Zend_Json::encode($return);
    }

    /**
     * Get Coldchain Capacity
     */
    public function getColdchainCapacityAction() {

        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        $type = $this->_request->getParam('type', '');
        $level = $this->_request->getParam('level', '');
        $level = (empty($level)) ? 1 : $level;

        $geoModel = new Model_Geo();
        if ($level == 1) {
            $return = $geoModel->getColdChainProvince($type,$province);
        } else if ($level == 4) {
            $return = $geoModel->getColdChainDistrict($province, $type);
        } else {
            $return = $geoModel->getColdchainTehsil($province, $district, $type);
        }



        echo Zend_Json::encode($return);
    }

    /**
     * Get Color Classes
     */
    public function getColorClassesAction() {
        $id = $this->_request->getParam('id', '');

        $geoModel = new Model_Geo();
        $result = $geoModel->getColorClasses($id);
        
        echo Zend_Json::encode($result);
    }

    /**
     * Get Month Range
     */
    public function getMonthRangeAction() {
        $id = $this->_request->getParam('month', '');
        $geoModel = new Model_Geo();
        $result = $geoModel->getMonthRange($id);
        echo Zend_Json::encode($result);
    }

    /**
     * Get Reporting Rate Trend
     */
    public function getReportingRateTrendAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $district = $this->_request->getParam('district', '');

        $geoModel = new Model_Geo();
        $return = $geoModel->getReportingRateTrend($year, $month, $district);

        echo Zend_Json::encode($return);
    }

    /**
     * Get Wastages Ucs List
     */
    public function getWastagesUcsListAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $product = $this->_request->getParam('product', '');
        $district = $this->_request->getParam('district', '');
        $tehsil = $this->_request->getParam('tehsil', '');
        $province = $this->_request->getParam('province', '');
        $limit = $this->_request->getParam('limit', '');

        $geoModel = new Model_Geo();
        $return = $geoModel->getWastagesUcsList($year, $month, $district, $province, $tehsil, $product, $limit);

        echo Zend_Json::encode($return);
    }

    /**
     * Get Batch Map Data
     */
    public function getBatchMapDataAction() {
        $batch = $this->_request->getParam('batch', '');

        $geoModel = new Model_Geo();
        $result = $geoModel->getBatchMapData($batch);

        echo Zend_Json::encode($result);
    }

    /**
     * Get Stock Batch Detail
     */
    public function getStockBatchDetailAction() {

        $product = $this->_request->getParam('product', '');
        $district = $this->_request->getParam('district', '');
        $tehsil = $this->_request->getParam('tehsil', '');

        $geoModel = new Model_Geo();
        if ($district != "") {
            $return = $geoModel->getDistrictStockBatch($district, $product);
        } else {
            $return = $geoModel->getTehsilStockBatch($tehsil, $product);
        }


        echo Zend_Json::encode($return);
    }

    /**
     * Get Cold Chain Asset Detail
     */
    public function getColdChainAssetDetailAction() {

        $district = $this->_request->getParam('district', '');
        $tehsil = $this->_request->getParam('tehsil', '');
        $type = $this->_request->getParam('type', '');

        $geoModel = new Model_Geo();
        if ($district != "") {
            $return = $geoModel->getColdChainAssetDistrict($district, $type);
        } else {
            $return = $geoModel->getColdChainAssetTehsil($tehsil, $type);
        }


        echo Zend_Json::encode($return);
    }

    /**
     * Get Demographic Map Data
     */
    public function getDemographicMapDataAction() {

        $id = $this->_request->getParam('id', '');
        $district_id = $this->_request->getParam('districtId', '');

        if ($id != "" && $district_id != "") {
            $geoModel = new Model_Geo();
            $return = $geoModel->getDemographicAssests($id, $district_id);
            echo Zend_Json::encode($return);
        } else {
            $return = array();
            $geoModel = new Model_Geo();
            $return[0] = $geoModel->getDemographicMapData();
            $return[1] = $geoModel->getDemographicDetail();

            echo Zend_Json::encode($return);
        }
    }

    /**
     * Get District List
     */
    public function getDistrictListAction() {

        $province = $this->_request->getParam('province', '');
        $geoModel = new Model_Geo();
        $return = $geoModel->getDistrictList($province);
        echo $return;
    }

    /**
     * Get Non Reported Ucs
     */
    public function getNonReportedUcsAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $province = $this->_request->getParam('province', '');
        $district = $this->_request->getParam('district', '');
        $tehsil = $this->_request->getParam('tehsil', '');

        $geoModel = new Model_Geo();
        if ($district != "") {
            $return = $geoModel->getNonReportedUcsByDistrict($month, $year, $province, $district);
        } else {
            $return = $geoModel->getNonReportedUcsByTehsil($month, $year, $province, $tehsil);
        }

        echo Zend_Json::encode($return);
    }

    /**
     * Get Uc Wise Mos
     */
    public function getUcWiseMosAction() {

        $year = $this->_request->getParam('year', '');
        $month = $this->_request->getParam('month', '');
        $product = $this->_request->getParam('product', '');
        $tehsilId = $this->_request->getParam('tehsilId', '');
        $param = $this->_request->getParam('param', '');
        $type = $this->_request->getParam('type', '');

        $geoModel = new Model_Geo();
        if ($param == 'mos') {
            $return = $geoModel->getUcWiseMos($year, $month, $product, $tehsilId);
        } else {
            $return = $geoModel->getUcWiseConsumption($year, $month, $product, $tehsilId, $type);
        }
        echo Zend_Json::encode($return);
    }

}
