<?php

/**
 * Reports_DashletController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */
/**
 * Reports Dashlet
 */
require_once 'FusionCharts/Code/PHP/Includes/FusionCharts.php';

/**
 *  Controller for Reports Dashlet
 */
class Reports_DashletController extends App_Controller_Base {

    /**
     * Reports_DashletController init
     */
    public function init() {
        parent::init();
        $this->_helper->layout->setLayout("dashlets");
    }

    /**
     * Stock Status
     * Inventory Management Dashlet
     */
    public function stockStatusAction() {
        $params = array();
        $dashlet = new Model_Dashlets();
        $params["level"] = $this->_request->getParam("level");
        $params["prov_id"] = $this->_request->getParam("province");
        $params["loc_id"] = $this->_request->getParam("district");
        $params["date"] = $this->_request->getParam("date");
        list($yy, $mm) = explode("-", $this->_request->getParam("date"));

        $dashlet->form_values = $params;
        $this->view->result = $dashlet->stockStatus();
        $this->view->monthyear = date('F, Y', mktime(0, 0, 0, $mm, 1, $yy));
    }

    /**
     * Stock Status Routine
     * Routine Immunization Dashlet Late
     */
    public function stockStatusRoutineAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = "2014-05";
        $params["item"] = 3;
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->stockStatusRoutine();
        $this->view->xmlstore = $xmlstore;
        $this->view->date = $params["date"];
        $this->view->item = $params["item"];
    }

    /**
     * ajaxStockStatusRoutine
     */
    public function ajaxStockStatusRoutineAction() {
        $this->_helper->layout->disableLayout();

        $params = array();
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        if (!empty($date)) {
            $params["date"] = $date;
        } else {
            $params["date"] = "2014-05";
        }
        $params["item"] = $item;

        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->stockStatusRoutine();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Reported Wastages
     * Main Dashboard Dashlet
     */
    public function reportedWastagesAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");
        $period = $this->_request->getParam("period", 39);
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["level"] = $level;

        $wh_data->form_values = $params;

        if ($level == 1) {
            $graphs = new Model_Graphs();
            $location = new Model_Locations();
            $provinces = $location->getPilotProvinces();
            foreach ($provinces as $row) {
                $graphs->form_values = array(
                    'products' => array($item),
                    'yearcomp' => array($date),
                    'all_provinces' => $row['pk_id'],
                    'all_districts' => '',
                    'period' => $period
                );
                $xmlstore[] = $graphs->MSGraphReportedWastage();
            }

            $this->view->xmlstore = $xmlstore;
        }
        if ($level == 2) {
            $xmlstore1 = $wh_data->getWastagesByDistricts($province);
            $this->view->xmlstore1 = $xmlstore1;
        }
        if ($level == 6) {
            $wh_data->form_values['prov_id'] = $province;
            $wh_data->form_values['dist_id'] = $district;

            $xmlstore61 = $wh_data->wastagesRate();
            $xmlstore62 = $wh_data->reportingRate();
            $this->view->xmlstore61 = $xmlstore61;
            $this->view->xmlstore62 = $xmlstore62;
        }

        $this->view->level = $level;
    }

    /**
     * Illegal Wastages
     */
    public function illegalWastagesAction() {

        $province = $this->_request->getParam("province");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $obj_item = new Model_ItemPackSizes();
        $items = $obj_item->getProductById($item);
        $allowed = $items->getWastageRateAllowed();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["allowed"] = $allowed;
        $params["province"] = $province;
        $wh_data->form_values = $params;

        $xmlstore = $wh_data->illegalWastages();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Wastages Comparison
     */
    public function wastagesComparisonAction() {
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $obj_product = new Model_ItemPackSizes();
        $prod_result = $obj_product->getProductById($item);
        $allowed = $prod_result->getWastageRateAllowed();
        $first = round($allowed / 3);
        $second = round($first * 2);

        $combo = array(
            "0-$first" => "0 to $first%",
            "$first-$second" => "$first% to $second%",
            "$second-$allowed" => "$second% to $allowed%",
            "N" => "more then $allowed%"
        );

        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values = array(
            'prov_id' => $province,
            'dist_id' => $district,
            'date' => $date,
            'item' => $item,
            'option' => "N",
            'allowed' => $allowed
        );

        $xmlstore = $wh_data->wastagesComparison();
        $this->view->xmlstore = $xmlstore;
        $this->view->combo = $combo;
    }

    /**
     * ajaxWastagesComparison
     */
    public function ajaxWastagesComparisonAction() {
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        $option = $this->_request->getParam("allowed");

        $obj_product = new Model_ItemPackSizes();
        $prod_result = $obj_product->getProductById($item);
        $allowed = $prod_result->getWastageRateAllowed();

        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values = array(
            'prov_id' => $province,
            'dist_id' => $district,
            'date' => $date,
            'item' => $item,
            'option' => $option,
            'allowed' => $allowed
        );
        $xmlstore = $wh_data->wastagesComparison();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Reported Wastage
     */
    public function reportedWastageAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["level"] = $level;

        $wh_data->form_values = $params;

        if ($level == 2) {
            $xmlstore1 = $wh_data->getWastagesByDistricts($province);
            $this->view->xmlstore1 = $xmlstore1;
        }
        if ($level == 6) {
            $wh_data->form_values['prov_id'] = $province;
            $wh_data->form_values['dist_id'] = $district;

            $xmlstore61 = $wh_data->wastagesRate();
            $xmlstore62 = $wh_data->reportingRate();
            $this->view->xmlstore61 = $xmlstore61;
            $this->view->xmlstore62 = $xmlstore62;
        }

        $this->view->level = $level;
    }

    /**
     * Reported Non Reported
     */
    public function reportedNonReportedAction() {
        $district = $this->_request->getParam("district");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;

        $params["loc_id"] = $district;
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->reportedNonReported();
        $data = $wh_data->getReportedLocation();
        $this->view->data = $data;
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * ajaxReportedNonReported
     */
    public function ajaxReportedNonReportedAction() {
        $data_arr = explode('|', $this->_request->getParam('param'));
        $district = $data_arr[0];
        $item = $data_arr[1];
        $date = $data_arr[2];
        $type = $data_arr[3];

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["loc_id"] = $district;
        $wh_data->form_values = $params;

        if ($type == 1) {
            $data = $wh_data->getReportedLocation();
        } else {
            $data = $wh_data->getNonReportedLocation();
        }

        $this->view->data = $data;
        $this->view->type = $type;
    }

    /**
     * Stock Status By Item
     */
    public function stockStatusByItemAction() {
        $district = $this->_request->getParam("district");
        $province = $this->_request->getParam("province");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        $level = $this->_request->getParam("level");

        $warehouse = new Model_Locations();
        $warehouse->form_values = array(
            'level' => $level,
            'prov_id' => $province,
            'loc_id' => $district
        );
        $wh_id = $warehouse->getWarehouseByLevel();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["wh_id"] = $wh_id;

        $wh_data->form_values = $params;
        $xmlstore = $wh_data->stockStatusByItem();
        $this->view->xmlstore = $xmlstore;

        $data = $wh_data->getReceiveWarehouses();
        $this->view->data = $data;
    }

    /**
     * ajaxStockStatusByItem
     */
    public function ajaxStockStatusByItemAction() {
        $data_arr = explode('|', $this->_request->getParam('param'));
        $wh_id = $data_arr[0];
        $item = $data_arr[1];
        $date = $data_arr[2];
        $type = $data_arr[3];

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["wh_id"] = $wh_id;
        $wh_data->form_values = $params;

        if ($type == 1) {
            $data = $wh_data->getReceiveWarehouses();
        } else {
            $data = $wh_data->getIssueWarehouses();
        }

        $this->view->data = $data;
        $this->view->type = $type;
    }

    /**
     * vvm Stage Status
     */
    public function vvmStageStatusAction() {
        $district = $this->_request->getParam("district");
        $province = $this->_request->getParam("province");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        $level = $this->_request->getParam("level");

        $warehouse = new Model_Locations();
        $warehouse->form_values = array(
            'level' => $level,
            'prov_id' => $province,
            'loc_id' => $district
        );
        $wh_id = $warehouse->getWarehouseByLevel();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["wh_id"] = $wh_id;
        $params["type"] = 1;

        $wh_data->form_values = $params;
        $xmlstore = $wh_data->vvmStageStatus();
        $this->view->xmlstore = $xmlstore;

        $data = $wh_data->vvmStageStatusByVvmStage();
        $this->view->data = $data;
    }

    /**
     * ajaxVvmStageStatus
     */
    public function ajaxVvmStageStatusAction() {
        $data_arr = explode('|', $this->_request->getParam('param'));
        $wh_id = $data_arr[0];
        $item = $data_arr[1];
        $date = $data_arr[2];
        $type = $data_arr[3];

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["wh_id"] = $wh_id;
        $params["type"] = $type;
        $wh_data->form_values = $params;

        $data = $wh_data->vvmStageStatusByVvmStage();

        $this->view->data = $data;
        $this->view->type = $type;
    }

    /**
     * Reported Non Reported Province
     */
    public function reportedNonReportedProvinceAction() {

        $province = $this->_request->getParam("province");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["province"] = $province;

        $wh_data->form_values = $params;
        $xmlstore = $wh_data->reportedNonReportedByProvince();
        $this->view->xmlstore = $xmlstore;

        $caption = "Districts Reporting Rate By Union Councils";
        $xml = $wh_data->getReportedLocationProvince();
        $xmlstore2 = "<chart yAxisMaxValue='100' exportEnabled='1' exportAction='Download' caption='$caption' exportFileName='Reporting Status " . date('Y-m-d H:i:s') . "' numberSuffix='%' showValues='1' theme='fint'>";
        foreach ($xml as $row) {
            $xmlstore2 .= "<set label='$row[districtName]' value='$row[perVal]' />";
        }
        $xmlstore2 .= "</chart>";

        $this->view->xmlstore2 = $xmlstore2;
    }

    /**
     * ajaxReportedNonReportedProvince
     */
    public function ajaxReportedNonReportedProvinceAction() {
        $data_arr = explode('|', $this->_request->getParam('param'));
        $province = $data_arr[0];
        $item = $data_arr[1];
        $date = $data_arr[2];
        $type = $data_arr[3];

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $date;
        $params["item"] = $item;
        $params["province"] = $province;
        $wh_data->form_values = $params;

        if ($type == 1) {
            $caption = "Districts Reporting Rate By Union Councils";
            $xml = $wh_data->getReportedLocationProvince();
        } else {
            $caption = "Districts Non Reporting Rate By Union Councils";
            $xml = $wh_data->getNonReportedLocationProvince();
        }

        $xmlstore = "<chart yAxisMaxValue='100' exportEnabled='1' exportAction='Download' caption='$caption' exportFileName='Reporting Status " . date('Y-m-d H:i:s') . "' numberSuffix='%' showValues='1' theme='fint'>";
        foreach ($xml as $row) {
            $xmlstore .= "<set label='$row[districtName]' value='$row[perVal]' />";
        }
        $xmlstore .= "</chart>";

        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Consumption Amc
     */
    public function consumptionAmcAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $period = $this->_request->getParam("period", 39);
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $graphs = new Model_Graphs();
        $location = new Model_Locations();
        $provinces = $location->getPilotProvinces();
        foreach ($provinces as $row) {
            $graphs->form_values = array(
                'products' => array($item),
                'yearcomp' => array($date),
                'all_provinces' => $row['pk_id'],
                'all_districts' => '',
                'optvals' => 2,
                'period' => $period
            );
            $xmlstore[] = $graphs->MSGraphOptionYear();
        }

        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Consumption Mos
     */
    public function consumptionMosAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $period = $this->_request->getParam("period");
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");

        $graphs = new Model_Graphs();
        $location = new Model_Locations();
        $provinces = $location->getPilotProvinces();
        foreach ($provinces as $row) {
            $graphs->form_values = array(
                'products' => array($item),
                'yearcomp' => array($date),
                'all_provinces' => $row['pk_id'],
                'all_districts' => '',
                'optvals' => 2,
                'period' => $period
            );
            $xmlstore[] = $graphs->getMSGraphConsMOS();
        }

        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Get SOH (Stock on Hand)
     */
    public function getSohAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");

        $wh_data->form_values = $params;

        if ($level == 1) {
            $xmlstore = $wh_data->getSOH();
        } else {
            $xmlstore = $wh_data->getSOHByDistricts($province);
        }
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Get MOS (Months of Stock) of Districts
     */
    public function getMosDistrictsAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->getMOSDistricts();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Get SOH (Stock on Hand) of Districts
     */
    public function getSohDistrictsAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->getSOH();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Get MOS (Months of Stock)
     */
    public function getMosAction() {
        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");

        $mos_scale = new Model_MosScale();
        $mos_scale->form_values = array(
            'item' => $this->_request->getParam("item"),
            'level' => 6
        );
        $combo = $mos_scale->getMosScaleByItem();

        $limit = $combo[0]['keyy'];
        list($start, $end) = explode("-", $limit);
        $params["start"] = $start;
        $params["end"] = $end;
        $wh_data->form_values = $params;

        switch ($level) {
            case 1:
                $xmlstore = $wh_data->getMOS();
                break;
            case 2:
                $xmlstore = $wh_data->getMOSByDistricts($province);
                break;
            case 6:
                $xmlstoreresult = $wh_data->getMOSByUc($district);
                $xmlstore = $xmlstoreresult['xmlstore'];
                $total = $xmlstoreresult['totalucs'];
                $number = $xmlstoreresult['currentucs'];
                $this->view->total = $total;
                $this->view->number = $number;
                break;
            default :
                break;
        }

        $this->view->xmlstore = $xmlstore;
        $this->view->combo = $combo;
    }

    /**
     * ajaxGetMos
     */
    public function ajaxGetMosAction() {

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $params["province"] = $this->_request->getParam("province");
        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");
        $limit = $this->_request->getParam("limit");

        list($start, $end) = explode("-", $limit);
        $params["start"] = $start;
        $params["end"] = $end;
        $wh_data->form_values = $params;

        switch ($level) {
            case 1:
                $xmlstore = $wh_data->getMOS();
                break;
            case 2:
                $xmlstore = $wh_data->getMOSByDistricts($province);
                break;
            case 6:
                $xmlstoreresult = $wh_data->getMOSByUc($district);
                $xmlstore = $xmlstoreresult['xmlstore'];
                $total = $xmlstoreresult['totalucs'];
                $number = $xmlstoreresult['currentucs'];
                $this->view->filter = $this->_request->getParam("filter");
                $this->view->total = $total;
                $this->view->number = $number;
                break;
            default :
                break;
        }

        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Get AMC (Average Monthly Consumption) 
     */
    public function getAmcAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");

        $wh_data->form_values = $params;

        switch ($level) {
            case 1:
                $xmlstore = $wh_data->getAMC();
                break;
            case 2:
                $xmlstore = $wh_data->getAMCByDistricts($province);
                break;
            case 6:
                $xmlstore = $wh_data->getAMCByUc($district);
                break;
            default :
                break;
        }

        $this->view->xmlstore = $xmlstore;
    }

     /**
     * Get Consumption
     */
    public function getConsumptionAction() {
       
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $params["wastages"] = $this->_request->getParam("wastages");
        $params["province"] = $this->_request->getParam("province");
        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");
        $item->form_values['pk_id'] = $params["item"];
        $this->view->wastage_rate = $item->getItemsWastage();
        $locations = new Model_Locations();
        $locations->form_values = array(
            'parent_id' => $district,
            'geo_level_id' => 5
        );
        $combo = $locations->getLocationsByLevelByTehsil();

        $role_id = $this->_identity->getRoleId();

        if ($role_id == 7) {
            $params["teh_id"] = $this->_identity->getTehsilId();
        } else {
            $params["teh_id"] = $combo[0]['key'];
        }

        $wh_data->form_values = $params;


        switch ($level) {
            case 1:
                $xmlstore = $wh_data->getConsumption();
                $this->view->xmltype = "Column2D.swf";
                break;
            case 2:
                $xmlstore = $wh_data->getConsumptionByDistricts($province);
                $this->view->xmltype = "MSCombi2D.swf";
                break;
            case 6:
                $xmlstore = $wh_data->getConsumptionByUc($district);
                $this->view->xmltype = "StackedColumn2DLine.swf";
                 break;
            default :
                break;
        }

        $this->view->xmlstore = $xmlstore;
        $this->view->combo = $combo;
        $this->view->level = $level;
    }

    /**
     * ajaxGetConsumption
     */
    public function ajaxGetConsumptionAction() {
        $wh_data = new Model_HfDataMaster();
$item = new Model_ItemPackSizes();
        $params["date"] = $this->_request->getParam("date");
        $params["wastages"] = $this->_request->getParam("wastages");
        $params["item"] = $this->_request->getParam("item");
        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");
        $tehsil = $this->_request->getParam("teh_id");
        $params["teh_id"] = $tehsil;
       $item->form_values['pk_id'] = $params["item"];
        $this->view->wastage_rate = $item->getItemsWastage();
        $wh_data->form_values = $params;

        switch ($level) {
            case 1:
                $xmlstore = $wh_data->getConsumption();
                $this->view->xmltype = "Column2D.swf";
                break;
            case 2:
                $xmlstore = $wh_data->getConsumptionByDistricts($province);
                $this->view->xmltype = "MSCombi2D.swf";
                break;
            case 6:
                $xmlstore = $wh_data->getConsumptionByUc($district);
                $this->view->xmltype = "StackedColumn2DLine.swf";
                break;
            default :
                break;
        }

        $this->view->xmlstore = $xmlstore;
    }


    /**
     * Stock Issue
     * Inventory Management Dashlet
     */
    public function stockIssueAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();

        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        $params["level"] = $this->_request->getParam("level");
        $params["loc_id"] = $this->_request->getParam("district");
        $params["prov_id"] = $this->_request->getParam("province");

        if (!empty($date)) {
            $params["date"] = $date;
        } else {
            $params["date"] = Zend_Registry::get('report_month');
        }
        if (!empty($item)) {
            $params["item"] = $item;
        } else {
            $params["item"] = 6;
        }

        $wh_data->form_values = $params;
        $xmlstore = $wh_data->stockIssue();
        $this->view->xmlstore = $xmlstore;
        $this->view->date = $params["date"];
        $this->view->item = $params["item"];
    }

    /**
     * ajaxStockIssue
     */
    public function ajaxStockIssueAction() {
        $this->_helper->layout->disableLayout();

        $params = array();
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        $params["level"] = $this->_request->getParam("level");
        $params["loc_id"] = $this->_request->getParam("district");
        $params["prov_id"] = $this->_request->getParam("province");

        if (!empty($date)) {
            $params["date"] = $date;
        } else {
            $params["date"] = Zend_Registry::get('report_month');
        }
        if (!empty($item)) {
            $params["item"] = $item;
        } else {
            $params["item"] = 6;
        }

        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->stockIssue();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Stock Receive
     * Inventory Management Dashlet Late
     */
    public function stockReceiveAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = "2014-05";
        $params["item"] = 6;
        $params["loc_id"] = $this->_request->getParam("district");

        $wh_data->form_values = $params;
        $xmlstore = $wh_data->stockReceive();
        $this->view->xmlstore = $xmlstore;
        $this->view->date = $params["date"];
        $this->view->item = $params["item"];
    }

    /**
     * ajaxStockReceive
     */
    public function ajaxStockReceiveAction() {
        $this->_helper->layout->disableLayout();

        $params = array();
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        $params["loc_id"] = $this->_request->getParam("district");

        if (!empty($date)) {
            $params["date"] = $date;
        } else {
            $params["date"] = "2014-05";
        }
        $params["item"] = $item;

        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->stockReceive();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Wastages Rate
     * Routine Immunization Dashlet Late
     */
    public function wastagesRateAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = "2014-05";
        $params["item"] = 6;
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->wastagesRate();
        $this->view->xmlstore = $xmlstore;
        $this->view->date = $params["date"];
        $this->view->item = $params["item"];
    }

    /**
     * ajaxWastagesRate
     */
    public function ajaxWastagesRateAction() {
        $this->_helper->layout->disableLayout();

        $params = array();
        $date = $this->_request->getParam("date");
        $item = $this->_request->getParam("item");
        if (!empty($date)) {
            $params["date"] = $date;
        } else {
            $params["date"] = "2014-05";
        }
        $params["item"] = $item;

        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->wastagesRate();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Reporting Rate
     * Routine Immunization Dashlet Late
     */
    public function reportingRateAction() {
        for ($i = 1; $i <= 6; $i++) {
            $months[] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
        }

        $dashlet = new Model_Dashlets();
        $dashlet->form_values = array_reverse($months);
        $this->view->result = $dashlet->reportingRate();
        $this->view->months = array_reverse($months);
    }

    /**
     * Stock Position
     * Routine Immunization Dashlet Late
     */
    public function stockPositionAction() {
        $wh_data = new Model_HfDataMaster();
        $xmlstore = $wh_data->stockPosition();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Day Wise Coverage
     * Campaign Management Dashlet
     */
    public function dayWiseCoverageAction() {
        $campaign = new Model_Campaigns();
        $this->view->campaigns = $campaign->getAllCampaigns();
        $location = new Model_Locations();
        $this->view->provinces = $location->getProvincesName();

        $wh_data = new Model_HfDataMaster();
        $params["level"] = $this->_request->getParam("level");
        $params["loc_id"] = $this->_request->getParam("district");
        $params["prov_id"] = $this->_request->getParam("province");
        $params["camp"] = $this->_request->getParam('camp');
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->dayWiseCoverage();
        $this->view->xmlstore = $xmlstore;
        $this->view->camp = $params["camp"];
        if (array_key_exists("prov", $params)) {
            $this->view->prov = $params["prov"];
        }
    }

    /**
     * Different Missed Types
     * Campaign Management Dashlet
     */
    public function differentMissedTypesAction() {
        $campaign = new Model_Campaigns();
        $this->view->campaigns = $campaign->getAllCampaigns();

        $wh_data = new Model_HfDataMaster();
        $params["level"] = $this->_request->getParam("level");
        $params["loc_id"] = $this->_request->getParam("district");
        $params["prov_id"] = $this->_request->getParam("province");
        $params["camp"] = $this->_request->getParam('camp');
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->differentMissedTypes();
        $this->view->xmlstore = $xmlstore;
        $this->view->camp = $params["camp"];
    }

    /**
     * Data Entry Status
     * Campaign Management Dashlet
     */
    public function dataEntryStatusAction() {
        $wh_data = new Model_HfDataMaster();
        $params["level"] = $this->_request->getParam("level");
        $params["loc_id"] = $this->_request->getParam("district");
        $params["prov_id"] = $this->_request->getParam("province");
        $params["camp"] = $this->_request->getParam('camp');
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->dataEntryStatus();
        $this->view->xmlstore = $xmlstore;
    }

    /**
     * Campaign Vaccines
     * Campaign Management Dashlet
     */
    public function campaignVaccinesAction() {
        $dashlet = new Model_Dashlets();
        $params["level"] = $this->_request->getParam("level");
        $params["loc_id"] = $this->_request->getParam("district");
        $params["prov_id"] = $this->_request->getParam("province");
        $params["camp"] = $this->_request->getParam('camp');
        $dashlet->form_values = $params;
        $this->view->result = $dashlet->campaignVaccines();
    }

    /**
     * Facility Stats
     * Cold Chain Equipment Management Dashlet
     */
    public function facilityStatsAction() {
        echo "Dashlet five display here";
    }

    /**
     * Refrigerator Freezer Type
     * Cold Chain Equipment Management Dashlet
     */
    public function refrigeratorFreezerTypeAction() {
        echo "Dashlet five display here";
    }

    /**
     * Assets Stats
     * Cold Chain Equipment Management Dashlet
     */
    public function assetsStatsAction() {
        echo "Dashlet five display here";
    }

    /**
     * Mode Of Vaccine Supplies
     * Cold Chain Equipment Management Dashlet
     */
    public function modeOfVaccineSuppliesAction() {
        echo "Dashlet five display here";
    }

    /**
     * Health Facility Stats
     * Cold Chain Equipment Management Dashlet
     */
    public function healthFacilityStatsAction() {
        echo "Dashlet five display here";
    }

    /**
     * Month Of Stock
     * Maps Dashlet
     */
    public function monthOfStockAction() {
        $base_url = Zend_Registry::get('baseurl');
        $this->view->headScript()->appendFile($base_url . '/js/OpenLayers-2.13/OpenLayers.js');
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $prov_id = $this->_identity->getProvinceId();
        $form->product->setValue('6');
        $this->view->form = $form;
        $baseurl = Zend_Registry::get('baseurl');

        $this->view->inlineScript()->prependScript('var prov_id = "' . $prov_id . '"');
        $this->view->inlineScript()->appendFile($baseurl . '/js/reports/dashlet/month-of-stock2.js');
    }

    /**
     * Expiry Schedule
     */
    public function expiryScheduleAction() {

        $level = $this->_request->getParam('level');
        $this->view->level = $level;

        switch ($level) {
            case 2:
                $params["loc_id"] = $this->_request->getParam('province');
                break;
            case 6:
                $params["loc_id"] = $this->_request->getParam('district');
                break;
            default :
                break;
        }
        $item = $this->_request->getParam('item');

        $wh_data = new Model_HfDataMaster();
        $params["item"] = $item;
        $params["level"] = $level;
        $wh_data->form_values = $params;
        $xmlstore = $wh_data->getExpirySchedule();
        $this->view->xmlstore = $xmlstore;

        $title = "Stock Expiring in <= 6 Months";
        $wh_data = new Model_HfDataMaster();
        $params["level"] = $level;
        $params["item_id"] = $item;
        $params["type"] = 1;
        $wh_data->form_values = $params;
        $data = $wh_data->getExpiryScheduleByType();

        $this->view->data = $data;
        $this->view->title = $title;
    }

    /**
     * ajaxExpirySchedule
     */
    public function ajaxExpiryScheduleAction() {
        $data_arr = explode('|', $this->_request->getParam('param'));
        $location = $data_arr[0];
        $item = $data_arr[1];
        $level = $data_arr[2];
        $type = $data_arr[3];

        if ($type == 1) {
            $title = "Stock Expiring in <= 6 Months";
        } else if ($type == 2) {
            $title = "Stock Expiring in <= 12 Months";
        } else if ($type == 3) {
            $title = "Stock Expiring in <= 18 Months";
        } else if ($type == 4) {
            $title = "Stock Expiring in > 18 Months";
        }

        $wh_data = new Model_HfDataMaster();
        $params["level"] = $level;
        $params["item_id"] = $item;
        $params["loc_id"] = $location;
        $params["type"] = $type;
        $wh_data->form_values = $params;
        $data = $wh_data->getExpiryScheduleByType();

        $this->view->data = $data;
        $this->view->type = $type;
        $this->view->title = $title;
    }

    /**
     * Cold Chain Capacity
     */
    public function ccCapacityAction() {
        $base_url = Zend_Registry::get('baseurl');
        $this->view->headScript()->appendFile($base_url . '/js/OpenLayers-2.13/OpenLayers.js');
        $form = new Form_Maps_Mos();
        $form->province->setValue($this->_identity->getProvinceId());
        $form->coldchain_type1->setValue("1");
        $prov_id = $this->_identity->getProvinceId();
        $this->view->form = $form;

        $baseurl = Zend_Registry::get('baseurl');

        $this->view->inlineScript()->prependScript('var prov_id = "' . $prov_id . '"');
        $this->view->inlineScript()->appendFile($baseurl . '/js/reports/dashlet/cc-capacity2.js');
    }

    /**
     * Vaccine Storage Capacity At 2 to 8
     */
    public function vaccineStorageCapacityAt2to8Action() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();

        $form_values['office'] = $this->_request->getParam('level', '');
        $form_values['combo1'] = $this->_request->getParam('province', '');
        $form_values['combo2'] = $this->_request->getParam('district', '');

        $ccm_warehouse->form_values = $form_values;
        $data_arr = $ccm_warehouse->vaccineStorageCapacityAt2to8Graph();

        $main_heading = "Vaccine storage capacity at +2c to +8c";
        $str_sub_heading = "";
        $number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';

        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Surplus > 30%" >';
        $dataset_2 = '<dataset seriesname="Surplus 10-30%" >';
        $dataset_3 = '<dataset seriesname="Match +/- 30%" >';
        $dataset_4 = '<dataset seriesname="Shortage 10-30%" >';
        $dataset_5 = '<dataset seriesname="Shortage > 30%" >';

        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['surplus30'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['surplus1030'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['match10'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['shortage1030'] . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['shortage30'] . '" />';
        }

        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $dataset_5 .= '</dataset>';

        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .= $dataset_5;

        $xmlstore .="</chart>";

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = "StackedColumn2DLine";
        $this->view->xmlstore = $xmlstore;
        $this->view->width = '100%';
        $this->view->height = '400';
    }

    /**
     * Vaccine Storage Capacity At 20
     */
    public function vaccineStorageCapacityAt20Action() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();

        $form_values['office'] = $this->_request->getParam('level', '');
        $form_values['combo1'] = $this->_request->getParam('province', '');
        $form_values['combo2'] = $this->_request->getParam('district', '');

        $ccm_warehouse->form_values = $form_values;
        $data_arr = $ccm_warehouse->vaccineStorageCapacityAt20Graph();

        $main_heading = "Vaccine storage capacity at -20c";
        $str_sub_heading = "";
        $number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';

        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Surplus > 30%" >';
        $dataset_2 = '<dataset seriesname="Surplus 10-30%" >';
        $dataset_3 = '<dataset seriesname="Match +/- 30%" >';
        $dataset_4 = '<dataset seriesname="Shortage 10-30%" >';
        $dataset_5 = '<dataset seriesname="Shortage > 30%" >';

        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['surplus30'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['surplus1030'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['match10'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['shortage1030'] . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['shortage30'] . '" />';
        }

        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $dataset_5 .= '</dataset>';

        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .= $dataset_5;

        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedColumn2DLine';
        $this->view->width = '100%';
        $this->view->height = '400';
    }

    /**
     * Icepack Freezing Capacity Against Routine Requirements
     */
    public function icepackFreezingCapacityAgainstRoutineRequirementsAction() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();
        $form_values['office'] = $this->_request->getParam('level', '');
        $form_values['combo1'] = $this->_request->getParam('province', '');
        $form_values['combo2'] = $this->_request->getParam('district', '');

        $ccm_warehouse->form_values = $form_values;
        $data_arr = $ccm_warehouse->icepackFreezingCapacityAgainstSIARequirementsGraph();

        $main_heading = "Icepack freezing capacity";
        $str_sub_heading = "";
        $number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';

        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Surplus > 30%" >';
        $dataset_2 = '<dataset seriesname="Surplus 10-30%" >';
        $dataset_3 = '<dataset seriesname="Match +/- 30%" >';
        $dataset_4 = '<dataset seriesname="Shortage 10-30%" >';
        $dataset_5 = '<dataset seriesname="Shortage > 30%" >';
        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['surplus30'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['surplus1030'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['match10'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['shortage1030'] . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['shortage30'] . '" />';
        }

        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $dataset_5 .= '</dataset>';

        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .= $dataset_5;

        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedColumn2DLine';
        $this->view->width = '100%';
        $this->view->height = '400';
    }

    /**
     * Cold Chain Capacity
     * Cold Chain Equipment Management Dashlet
     */
    public function coldChainCapacityAction() {
        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_Graphs();
        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->to_date = $to_date;
        $xmlstore1 = $graphs->coldChainCapacity(1);
        $this->view->xmlstore1 = $xmlstore1;
        $xmlstore2 = $graphs->coldChainCapacity(3);
        $this->view->xmlstore2 = $xmlstore2;
        $this->view->data = $graphs->coldChainCapacity(2);

        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();

        if ($role_id == 4 || $role_id == 5 || $role_id == 6 || $role_id == 7 || $role_id == 54) {
            $stock_master = new Model_StockMaster();
            $this->view->pending_receive = $stock_master->getPendingReceive();
        }

        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
    }

    /**
     * Cold Chain Capacity Print
     */
    public function coldChainCapacityPrintAction() {
        $this->_helper->layout->setLayout("print");
        $graphs = new Model_Graphs();
        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->data = $graphs->coldChainCapacity(2);
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    /**
     * Cold Chain Capacity Product
     */
    public function coldChainCapacityProductAction() {
        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_Graphs();
        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->to_date = $to_date;
        $xmlstore1 = $graphs->coldChainCapacityProduct(15);
        $this->view->xmlstore1 = $xmlstore1;
        $xmlstore2 = $graphs->coldChainCapacityProduct(16);
        $this->view->xmlstore2 = $xmlstore2;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->data = $graphs->coldChainCapacityProduct(2);

        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();

        if ($role_id == 4 || $role_id == 5 || $role_id == 6 || $role_id == 7) {
            $stock_master = new Model_StockMaster();
            $this->view->pending_receive = $stock_master->getPendingReceive();
        }
        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/reports/dashlet/cold-chain-capacity.js');
    }

    /**
     * Cold Chain Capacity Vvm
     */
    public function coldChainCapacityVvmAction() {
        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_Graphs();
        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->to_date = $to_date;
        $xmlstore1 = $graphs->coldChainCapacityVvm(15);
        $this->view->xmlstore1 = $xmlstore1;
        $xmlstore2 = $graphs->coldChainCapacityVvm(16);
        $this->view->xmlstore2 = $xmlstore2;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->data = $graphs->coldChainCapacityVvm(2);

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/reports/dashlet/cold-chain-capacity.js');
    }

    /**
     * Donor's Contribution
     * Donor's Contribution Dashlet
     */
    //donorContributions
    public function donorContributionsAction() {
        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_Graphs();
        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', '2015');
        }

        $graphs->form_values['to_date'] = $to_date;

        $this->view->to_date = $to_date;
        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values['to_date'] = $to_date;
        // Donor's Contribution
        $xmlstore = $wh_data->donorContribution();
        $this->view->xmlstore = $xmlstore;
        // Contribution Breakup
        $xmlstore1 = $graphs->contributionBreakup();
        $this->view->xmlstore1 = $xmlstore1;
        // Product wise Contribution
        $xmlstore2 = $graphs->productWiseContribution();
        $this->view->xmlstore2 = $xmlstore2;
        // Provincially Vaccination
        $xmlstore3 = $wh_data->provinciallyVaccination();
        $this->view->xmlstore3 = $xmlstore3;

        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();


        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    public function ajaxContributionBreakupAction() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_Graphs();

        $to_date = $this->_request->getParam('to_date');
        $warehouse_id = $this->_request->getParam('wh_id');
        $graphs->form_values['to_date'] = $to_date;
        $graphs->form_values['wh_id'] = $warehouse_id;
        $this->view->to_date = $to_date;
        // Contribution Breakup
        $xmlstore1 = $graphs->contributionBreakup();
        $this->view->xmlstore1 = $xmlstore1;
    }

    public function ajaxProductWiseContributionAction() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_Graphs();

        $to_date = $this->_request->getParam('to_date');
        $warehouse_id = $this->_request->getParam('wh_id');
        $graphs->form_values['to_date'] = $to_date;
        $graphs->form_values['wh_id'] = $warehouse_id;
        $this->view->to_date = $to_date;
        // Product wise Contribution
        $xmlstore2 = $graphs->productWiseContribution();
        $this->view->xmlstore2 = $xmlstore2;
    }

    public function ajaxProvinciallyVaccinationAction() {
        $this->_helper->layout->disableLayout();

        $wh_data = new Model_HfDataMaster();
        $to_date = $this->_request->getParam('to_date');
        $warehouse_id = $this->_request->getParam('wh_id');
        $wh_data->form_values['to_date'] = $to_date;
        $wh_data->form_values['wh_id'] = $warehouse_id;
        $this->view->to_date = $to_date;
        // Provincially Vaccination
        $xmlstore3 = $wh_data->provinciallyVaccination();
        $this->view->xmlstore3 = $xmlstore3;
    }

  /**
     * Get Consumption
     */
    public function getEvacConsumptionAction() {
        $item = new Model_ItemPackSizes();
        $this->view->items = $item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $params["province"] = $this->_request->getParam("province");
        $level = $this->_request->getParam("level");
        $province = $this->_request->getParam("province");
        $district = $this->_request->getParam("district");

        $locations = new Model_Locations();
        $locations->form_values = array(
            'district_id' => $district
        );
        $combo = $locations->getTehsilsByDistrict();




        $params["uc_id"] = $combo[0]['pkId'];


        $wh_data->form_values = $params;
        $xmlstore = $wh_data->getEvacConsumptionByUc();
        $this->view->xmltype = "StackedColumn2DLine.swf";


        $this->view->xmlstore = $xmlstore;
        $this->view->combo = $combo;
        $this->view->level = $level;
    }

    /**
     * ajaxGetConsumption
     */
    public function ajaxGetEvacConsumptionAction() {
        $wh_data = new Model_HfDataMaster();
        $params["date"] = $this->_request->getParam("date");
        $params["item"] = $this->_request->getParam("item");
        $level = $this->_request->getParam("level");

        $tehsil = $this->_request->getParam("uc_id");
        $params["uc_id"] = $tehsil;

        $wh_data->form_values = $params;

        $xmlstore = $wh_data->getEvacConsumptionByUc();
        $this->view->xmltype = "StackedColumn2DLine.swf";


        $this->view->xmlstore = $xmlstore;
        $this->view->combo = $combo;
        $this->view->level = $level;
    }

}
