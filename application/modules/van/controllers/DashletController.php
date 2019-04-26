<?php

/**
 * Van_DashletController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @package Van
 * @author     Muhammad Imran <imranbinyaseen@gmail.com>
 * @version    2.5.1
 */
/**
 * Van Dashlet
 */
require_once 'FusionCharts/Code/PHP/Includes/FusionCharts.php';

/**
 *  Controller for Van Dashlet
 */
class Van_DashletController extends App_Controller_Base {

    public function vanAction() {
        $this->_helper->layout->setLayout("layout");
        //*******Default values********
        $selected_province = 2;
        $selected_district = 63;
//        $selected_year = date('Y');
//        $selected_month = date('m') - 1;
        $selected_year = date('Y') - 1;
        $selected_month = 05;
        $selected_product = 0;

        //********Filters*********
        //VAN Provinces: currently only Sindh
        $provinces = array("2" => "Sindh");
        //VAN Districts
        $consumption_summary = new Model_ConsumptionSummary();
        $districts = Array(
            0 => Array('district_id' => 63, 'district_name' => "Ghotki"),
            1 => Array('district_id' => 109, 'district_name' => "Larkana"),
            2 => Array('district_id' => 4279, 'district_name' => "Shaheed Benazir Abad")
        );
        //Year
        $current_year = date('Y');
        $years = array($current_year => $current_year, $current_year - 1 => $current_year - 1, $current_year - 2 => $current_year - 2);
        //Month
        $all_months = $consumption_summary->getAllMonths();
        //Vaccines
        $all_vaccines = $consumption_summary->getAllRoutineVaccines();

        //*******Geting form data*******
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            if (!empty($form_values['province'])) {
                $selected_province = $form_values['province'];
            }
            if (!empty($form_values['district'])) {
                $selected_district = $form_values['district'];
            }
            if (!empty($form_values['year'])) {
                $selected_year = $form_values['year'];
            }
            if (!empty($form_values['month'])) {
                $selected_month = $form_values['month'];
            }
            if (!empty($form_values['items'])) {
                $selected_product = $form_values['items'];
            }
        }
        //if month is single digit prepend 0
        if ($selected_month < 10) {
            $selected_month = "0" . $selected_month;
        }

        //geting district warehouse from district
        $district_wh = $consumption_summary->getDistrictWarehouse($selected_district);
        $district_wh_id = $district_wh[0]['pk_id'];
        $district_wh_name = $district_wh[0]['warehouse_name'];

        //geting provincial warehouse from province
        $provincial_wh = $consumption_summary->getProvincialWarehouse($selected_province);
        $provincial_wh_id = $provincial_wh[0]['warehouse_id'];
        $provincial_wh_name = $provincial_wh[0]['warehouse_name'];

        //Percentage of district stores having stock out of vaccine
        $district_stock_out_result = $consumption_summary->getPercentageOfDistrictStoresHavingStockOutOfVaccine($selected_month, $selected_year, $selected_province);

        //Percentage of UC stores having stock out of vaccine
        $uc_stock_out_result = $consumption_summary->getPercentageOfUcStoresHavingStockOutOfVaccine($selected_month, $selected_year, $selected_province);

        //Percentage of district stores understocked, adequately stocked or overstocked of vaccine
        $district_stores_stock_status_percentage_result = $consumption_summary->getPercentageOfDistrictStoresUnderstockedAdequatelyStockedOverstockedOfVaccine($selected_month, $selected_year, $selected_province);

        //Percentage of UCs understocked, adequately stocked or overstocked of a particular vaccine
        $ucs_stock_status_percentage_result = $consumption_summary->getPercentageOfUCsUnderstockedAdequatelyStockedOverstockedOfVaccine($selected_month, $selected_year, $selected_province);

        //getting Pending Vouchers
        $pending_voucher_array = $consumption_summary->getPendingVoucher($selected_month, $selected_year, $selected_province);

        //getting Percent Of vaccine Three Months Shelf Life
        $fefo_result = $consumption_summary->getPercentOfvaccineThreeMonthsShelfLife($selected_month, $selected_year, $district_wh_id);
        $fefo_array = array();
        foreach ($all_vaccines as $vaccine) {
            $fefo_array[$vaccine['item_id']]['item_id'] = $vaccine['item_id'];
            $fefo_array[$vaccine['item_id']]['item_name'] = $vaccine['item_name'];
            $fefo_array[$vaccine['item_id']]['Expire3Months'] = 0.0;

            foreach ($fefo_result as $row) {
                $fefo_array[$vaccine['item_id']]['Expire3Months'] = $row['Expire3Months'];
            }
        }

        //getting ProportionoOf Adjustments
        $proportion_of_adjustments_result = $consumption_summary->getProportionOfAdjustments($selected_month, $selected_year, $district_wh_id);

        //get Vaccine not usable due to Changed VVM
        $vaccine_not_usable_due_to_changed_VVM_result = $consumption_summary->getVaccineNotUsableDueToChangedVVM($selected_month, $selected_year, $district_wh_id);
        $changed_vvm_array = array();
        foreach ($all_vaccines as $vaccine) {
            $changed_vvm_array[$vaccine['item_id']]['item_id'] = $vaccine['item_id'];
            $changed_vvm_array[$vaccine['item_id']]['item_name'] = $vaccine['item_name'];
            $changed_vvm_array[$vaccine['item_id']]['percentage_unusable'] = 0.0;

            foreach ($vaccine_not_usable_due_to_changed_VVM_result as $row) {
                $changed_vvm_array[$vaccine['item_id']]['percentage_unusable'] = $row['percentage_unusable'];
            }
        }

        //getting Percentage Of Non Reconciled Vouchers
        $non_reconciled_vouchers_result = $consumption_summary->getPercentageOfNonReconciledVouchers($selected_month, $selected_year, $provincial_wh_id, $district_wh_id);


        $this->view->provinces = $provinces;
        $this->view->districts = $districts;
        $this->view->years = $years;
        $this->view->all_months = $all_months;
        $this->view->all_vaccines = $all_vaccines;

        $this->view->selected_province = $selected_province;
        $this->view->selected_district = $selected_district;
        $this->view->selected_year = $selected_year;
        $this->view->selected_month = $selected_month;
        $this->view->selected_product = $selected_product;
        $this->view->district_stores_stock_status_percentage_result = $district_stores_stock_status_percentage_result;
        $this->view->ucs_stock_status_percentage_result = $ucs_stock_status_percentage_result;
        $this->view->pending_voucher_array = $pending_voucher_array;
        $this->view->non_reconciled_vouchers_result = $non_reconciled_vouchers_result;
        $this->view->proportion_of_adjustments_result = $proportion_of_adjustments_result;

        //------------------------------Percentage of district stores having stock out of vaccines------------------------------
        $label_value = "";
        foreach ($district_stock_out_result as $row) {
            $label = $row['item_name'];
            $value = $row['stockout_percent'];
            $label_value .= "<set  label='" . $label . "' value='" . $value . "' />";
        }
        $xmlstore = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='25' 
                exportAction='Download' 
                caption= 'Percentage of district stores having stock out of vaccines' 
                exportFileName='Percentage of district stores having stock out of vaccines' 
                yAxisName='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                    $label_value
            </chart>";
        $this->view->xmlstore = $xmlstore;

        //------------------------------Percentage of UCs having stock out of vaccine in district------------------------------
        $label_value2 = "";
        foreach ($uc_stock_out_result as $row2) {
            $label2 = $row2['item_name'];
            $value2 = $row2['stockout_percent'];
            $label_value2 .= "<set  label='" . $label2 . "' value='" . $value2 . "' />";
        }
        $xmlstore2 = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                exportAction='Download' 
                caption= 'Percentage of UCs having stock out of vaccine in district Ghotki' 
                exportFileName='Percentage of UCs having stock out of vaccine in district Ghotki' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                    $label_value2
            </chart>";
        $this->view->xmlstore2 = $xmlstore2;


        //------------------------------vLMIS Reconciliation------------------------------
        $xmlstore5 = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= 'Percentage of " . $non_reconciled_vouchers_result[0]['from_warehouse'] . " issue and district receipt vouchers not reconciled, by " . $non_reconciled_vouchers_result[0]['to_warehouse'] . "' 
                exportFileName='Percentage of Sindh EPI issue and district receipt vouchers not reconciled, by district Ghotki' 
                yAxisName='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                <categories>
                    <category label='Issued' />
                    <category label='Received' />
                     <category label='Percentage' />
                </categories>
                <dataset>
                    <set  value='" . $non_reconciled_vouchers_result[0]['total_issue'] . "' />
                    <set  value='" . $non_reconciled_vouchers_result[0]['total_receive'] . "' />
                     <set  value='" . $non_reconciled_vouchers_result[0]['per_reconciled'] . "' />
                        </dataset>
            </chart>";
        $this->view->xmlstore5 = $xmlstore5;


        //------------------------------Proportion of adjustments------------------------------ 
        $category7 = "";
        $dataset7 = "";
        foreach ($proportion_of_adjustments_result as $adjustment) {
            $category7 .= "<category label='" . $adjustment['adjustment_type_name'] . "' />";
            $dataset7 .= "<set  value='" . $adjustment['adjustment_percent'] . "' />";
        }
        $xmlstore7 = "
                <chart 
                    exportEnabled='1' 
                    slantLabels='1' 
                    yAxisMaxValue='100' 
                    exportAction='Download' 
                    caption= 'Percent of each adjustment type out of all adjustments reported at " . $district_wh_name . "' 
                    exportFileName='Percent of each adjustment type out of all adjustments reported, at " . $district_wh_name . "' 
                    yAxisName='' 
                    showValues='1' 
                    formatNumberScale='0' 
                    theme='fint'>
                    <categories>
                        $category7
                    </categories>
                    <dataset>
                        $dataset7
                    </dataset>
                </chart>";
        $this->view->xmlstore7 = $xmlstore7;

        //------------------------------pending vs issue vouchers------------------------------
        $wh_name = $pending_voucher_array[0]['wh_name'];
        $pending_vouchers = $pending_voucher_array[0]['pending'];
        $issue_vouchers = $pending_voucher_array[0]['stockIssue'];
        if ($issue_vouchers != 0) {
            $percentage_of_voucher = ($pending_vouchers / $issue_vouchers) * 100;
        } else {
            $percentage_of_voucher = 0;
        }
        $xmlstore8 = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= 'Percentage of $wh_name pending vs issue vouchers' 
                exportFileName='Percentage of $wh_name pending vs issue vouchers' 
                yAxisName='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                <categories>
                    <category label='Issued' />
                    <category label='Pending' />
                     <category label='Percentage' />
                </categories>
                <dataset>
                    <set  value='" . $issue_vouchers . "%' />
                    <set  value='" . $pending_vouchers . "%' />
                     <set  value='" . $percentage_of_voucher . "' />
                        </dataset>
            </chart>";
        $this->view->xmlstore8 = $xmlstore8;


        //------------------------------Changed VVM------------------------------
        $category9 = "";
        $dataset9 = "";
        foreach ($changed_vvm_array as $row) {
            $category9 .= "<category label='" . $row['item_name'] . "' />";
            $dataset9 .= "<set  value='" . $row['percentage_unusable'] . "' />";
        }
        $xmlstore9 = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= 'Percentage of vaccine which is not usable due to changed VVM at Sindh EPI' 
                exportFileName='Percentage of vaccine which is not usable due to changed VVM at Sindh EPI' 
                yAxisName='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                 <categories>
                    $category9
                </categories>
                <dataset>
                    $dataset9
                </dataset>
            </chart>";

        $this->view->xmlstore9 = $xmlstore9;


        //------------------------------Percent of vaccine, by vaccine, with less than three months of shelf life at District Store------------------------------
        $category10 = "";
        $dataset10 = "";
        foreach ($fefo_array as $row) {
            $category10 .= "<category label='" . $row['item_name'] . "' />";
            $dataset10 .= "<set  value='" . $row['Expire3Months'] . "' />";
        }
        $xmlstore10 = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= 'Percent of vaccine, by vaccine, with less than three months of shelf life at $district_wh_name' 
                exportFileName='Percent of vaccine, by vaccine, with less than three months of shelf life at $district_wh_name' 
                yAxisName='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                 <categories>
                   $category10
                </categories>
                <dataset>
                    $dataset10 
                </dataset>
            </chart>";
        $this->view->xmlstore10 = $xmlstore10;
    }

    public function stocksAction() {
        $this->_helper->layout->setLayout("layout");

        //*******Default values********
        $selected_province = 2;
        $selected_district = 63;
        $selected_tehsil = 242;
//        $selected_year_from = date('Y') - 1;
//        $selected_month_from = 10;
        $selected_year_to = date('Y');
        $selected_month_to = date('m') - 1;
        $selected_product = 0;

        //Geting form data
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            if (!empty($form_values['province'])) {
                $selected_province = $form_values['province'];
            }
            if (!empty($form_values['district'])) {
                $selected_district = $form_values['district'];
            }
            if (!empty($form_values['tehsil'])) {
                $selected_tehsil = $form_values['tehsil'];
            }
//            if (!empty($form_values['year-from'])) {
//                $selected_year_from = $form_values['year-from'];
//            }
//            if (!empty($form_values['month-from'])) {
//                $selected_month_from = $form_values['month-from'];
//            }
            if (!empty($form_values['year-to'])) {
                $selected_year_to = $form_values['year-to'];
            }
            if (!empty($form_values['month-to'])) {
                $selected_month_to = $form_values['month-to'];
            }
            if (!empty($form_values['items'])) {
                $selected_product = $form_values['items'];
            }
        }

        //********Filters*********
        //VAN Provinces: currently only Sindh
        $provinces = array("2" => "Sindh");
        //VAN Districts
        $consumption_summary = new Model_ConsumptionSummary();
        $districts = Array(
            0 => Array('district_id' => 63, 'district_name' => "Ghotki"),
            1 => Array('district_id' => 109, 'district_name' => "Larkana"),
            2 => Array('district_id' => 4279, 'district_name' => "Shaheed Benazir Abad")
        );
        //Tehsils
        $tehsils = $consumption_summary->getTehsilesOfDistrict($selected_district);
        //Year
        $current_year = date('Y');
        $years = array($current_year => $current_year, $current_year - 1 => $current_year - 1, $current_year - 2 => $current_year - 2);
        //Month
        $all_months = $consumption_summary->getAllMonths();
        //Vaccines
        $all_vaccines = $consumption_summary->getAllRoutineVaccines();
        //********End Filters*********

        $end_date_str = $selected_year_to . '-' . $selected_month_to;
        $start_date_str = date("Y-m", strtotime("-5 months", strtotime($end_date_str)));

        $start_date_str_formated = date('Y-m-d', strtotime($start_date_str));
        $end_date_str_formated = date('Y-m-t', strtotime($end_date_str));

        $start_date_obj = new DateTime($start_date_str_formated);
        $end_date_obj = new DateTime($end_date_str_formated);

        $date_interval = DateInterval::createFromDateString("1 month");

        $period = new DatePeriod($start_date_obj, $date_interval, $end_date_obj);
        $this->view->period = $period;

        //geting district warehouse from district
        $district_wh = $consumption_summary->getDistrictWarehouse($selected_district);
        $district_wh_id = $district_wh[0]['pk_id'];
        $district_wh_name = $district_wh[0]['warehouse_name'];

        //geting provincial warehouse from province
        $provincial_wh = $consumption_summary->getProvincialWarehouse($selected_province);
        $provincial_wh_id = $provincial_wh[0]['warehouse_id'];
        $provincial_wh_name = $provincial_wh[0]['warehouse_name'];

        //Percentage of district stores having stock out of vaccine
        $district_store_stock_out_over_stock_result = $consumption_summary->getNumberOfTimeDistrictStoreStockOutAndOverStock($start_date_str_formated, $end_date_str_formated, $selected_district);

        //Percentage of taluka stores having stock out of vaccine
        $tehsile_array = $consumption_summary->getTehsilesOfDistrict($selected_district);
        //$taluka_store_stock_out_over_stock_result = $consumption_summary->getNumberOfTimesTalukasStoreStockOutAndOverStock($start_date_str, $end_date_str, $selected_district);

        $this->view->district_store_stock_out_over_stock_result = $district_store_stock_out_over_stock_result;
        //$this->view->taluka_store_stock_out_over_stock_result = $taluka_store_stock_out_over_stock_result;
        $this->view->tehsile_array = $tehsile_array;

        $selected_tehsil_name = $consumption_summary->getLocationNameById($selected_tehsil);

        $this->view->provinces = $provinces;
        $this->view->districts = $districts;
        $this->view->tehsils = $tehsils;
        $this->view->years = $years;
        $this->view->all_months = $all_months;
        $this->view->all_vaccines = $all_vaccines;
        $this->view->district_wh_name = $district_wh_name;
        $this->view->start_date = $start_date_str;
        $this->view->end_date = $end_date_str;

        $this->view->selected_province = $selected_province;
        $this->view->selected_district = $selected_district;
        $this->view->selected_tehsil = $selected_tehsil;
        $this->view->selected_tehsil_name = $selected_tehsil_name[0]['location_name'];

//        $this->view->selected_year_from = $selected_year_from;
//        $this->view->selected_month_from = $selected_month_from;
        $this->view->selected_year_to = $selected_year_to;
        $this->view->selected_month_to = $selected_month_to;
        $this->view->selected_product = $selected_product;
    }

    public function vaccinationAction() {
        $this->_helper->layout->setLayout("layout");

        //*******Default values********
        $selected_province = 2;
        $selected_district = 63;
//        $selected_year_from = date('Y') - 1;
//        $selected_month_from = 10;
        $selected_year_to = date('Y');
        $selected_month_to = date('m') - 1;

        $selected_product = 0;

        //********Filters*********
        //VAN Provinces: currently only Sindh
        $provinces = array("2" => "Sindh");
        //VAN Districts
        $consumption_summary = new Model_ConsumptionSummary();
        $districts = Array(
            0 => Array('district_id' => 63, 'district_name' => "Ghotki"),
            1 => Array('district_id' => 109, 'district_name' => "Larkana"),
            2 => Array('district_id' => 4279, 'district_name' => "Shaheed Benazir Abad")
        );
        //Year
        $current_year = date('Y');
        $years = array($current_year => $current_year, $current_year - 1 => $current_year - 1, $current_year - 2 => $current_year - 2);
        //Month
        $all_months = $consumption_summary->getAllMonths();
        //Vaccines
        $all_vaccines = $consumption_summary->getAllRoutineVaccines();

        //Geting form data
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            if (!empty($form_values['province'])) {
                $selected_province = $form_values['province'];
            }
            if (!empty($form_values['district'])) {
                $selected_district = $form_values['district'];
            }
            //            if (!empty($form_values['year-from'])) {
            //                $selected_year_from = $form_values['year-from'];
            //            }
            //            if (!empty($form_values['month-from'])) {
            //                $selected_month_from = $form_values['month-from'];
            //            }
            if (!empty($form_values['year-to'])) {
                $selected_year_to = $form_values['year-to'];
            }
            if (!empty($form_values['month-to'])) {
                $selected_month_to = $form_values['month-to'];
            }
            if (!empty($form_values['items'])) {
                $selected_product = $form_values['items'];
            }
        }

        $end_date_str = $selected_year_to . '-' . $selected_month_to;
        $start_date_str = date("Y-m", strtotime("-5 months", strtotime($end_date_str)));

        $start_date_str_formated = date('Y-m-d', strtotime($start_date_str));
        $end_date_str_formated = date('Y-m-t', strtotime($end_date_str));

        $start_date_obj = new DateTime($start_date_str_formated);
        $end_date_obj = new DateTime($end_date_str_formated);

        $date_interval = DateInterval::createFromDateString("1 month");

        $period = new DatePeriod($start_date_obj, $date_interval, $end_date_obj);
        $this->view->period = $period;

        $ucs_of_district = $consumption_summary->getUcsOfDistrict($selected_district);
        $num_Of_vaccinators = $consumption_summary->getNumberOfVaccinatorsInDistrict($selected_district);

        $this->view->provinces = $provinces;
        $this->view->districts = $districts;
        $this->view->years = $years;
        $this->view->all_months = $all_months;
        $this->view->all_vaccines = $all_vaccines;

        $this->view->selected_province = $selected_province;
        $this->view->selected_district = $selected_district;
        //$this->view->selected_year_from = $selected_year_from;
        //$this->view->selected_month_from = $selected_month_from;
        $this->view->selected_year_to = $selected_year_to;
        $this->view->selected_month_to = $selected_month_to;
        $this->view->selected_product = $selected_product;

        $this->view->num_Of_vaccinators = $num_Of_vaccinators;
        $this->view->ucs_of_district = $ucs_of_district;




//       
    }

    public function wastageAction() {
        $this->_helper->layout->setLayout("layout");

        //*******Default values********
        $selected_province = 2;
        $selected_district = 63;
//        $selected_year_from = date('Y') - 1;
//        $selected_month_from = 10;
        $selected_year_to = date('Y');
        $selected_month_to = date('m') - 1;

        $selected_product = 0;

        //********Filters*********
        //VAN Provinces: currently only Sindh
        $provinces = array("2" => "Sindh");
        //VAN Districts
        $consumption_summary = new Model_ConsumptionSummary();
        $districts = Array(
            0 => Array('district_id' => 63, 'district_name' => "Ghotki"),
            1 => Array('district_id' => 109, 'district_name' => "Larkana"),
            2 => Array('district_id' => 4279, 'district_name' => "Shaheed Benazir Abad")
        );
        //Year
        $current_year = date('Y');
        $years = array($current_year => $current_year, $current_year - 1 => $current_year - 1, $current_year - 2 => $current_year - 2);
        //Month
        $all_months = $consumption_summary->getAllMonths();
        //Vaccines
        $all_vaccines = $consumption_summary->getAllRoutineVaccines();

        //Geting form data
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            if (!empty($form_values['province'])) {
                $selected_province = $form_values['province'];
            }
            if (!empty($form_values['district'])) {
                $selected_district = $form_values['district'];
            }
//            if (!empty($form_values['year-from'])) {
//                $selected_year_from = $form_values['year-from'];
//            }
//            if (!empty($form_values['month-from'])) {
//                $selected_month_from = $form_values['month-from'];
//            }
            if (!empty($form_values['year-to'])) {
                $selected_year_to = $form_values['year-to'];
            }
            if (!empty($form_values['month-to'])) {
                $selected_month_to = $form_values['month-to'];
            }
            if (!empty($form_values['items'])) {
                $selected_product = $form_values['items'];
            }
        }

        $end_date_str = $selected_year_to . '-' . $selected_month_to;
        $start_date_str = date("Y-m", strtotime("-5 months", strtotime($end_date_str)));

        $start_date_str_formated = date('Y-m-d', strtotime($start_date_str));
        $end_date_str_formated = date('Y-m-t', strtotime($end_date_str));

        $start_date_obj = new DateTime($start_date_str_formated);
        $end_date_obj = new DateTime($end_date_str_formated);

        $date_interval = DateInterval::createFromDateString("1 month");

        $period = new DatePeriod($start_date_obj, $date_interval, $end_date_obj);
        $this->view->period = $period;

        $wasted_items = $consumption_summary->getWastedProducts($selected_district, $start_date_str_formated, $end_date_str_formated, $selected_product);



        $this->view->wasted_items = $wasted_items;

        $this->view->provinces = $provinces;
        $this->view->districts = $districts;
        $this->view->years = $years;
        $this->view->all_months = $all_months;
        $this->view->all_vaccines = $all_vaccines;

        $this->view->selected_province = $selected_province;
        $this->view->selected_district = $selected_district;
        //$this->view->selected_year_from = $selected_year_from;
        //$this->view->selected_month_from = $selected_month_from;
        $this->view->selected_year_to = $selected_year_to;
        $this->view->selected_month_to = $selected_month_to;
        $this->view->selected_product = $selected_product;
    }

    public function ccemAction() {
        $this->_helper->layout->setLayout("layout");

        //*******Default values********
        $selected_province = 2;
        $selected_district = 63;

        //********Filters*********
        //VAN Provinces: currently only Sindh
        $provinces = array("2" => "Sindh");
        //VAN Districts
        $consumption_summary = new Model_ConsumptionSummary();
        $districts = Array(
            0 => Array('district_id' => 63, 'district_name' => "Ghotki"),
            1 => Array('district_id' => 109, 'district_name' => "Larkana"),
            2 => Array('district_id' => 4279, 'district_name' => "Shaheed Benazir Abad")
        );

        //Geting form data
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            if (!empty($form_values['province'])) {
                $selected_province = $form_values['province'];
            }
            if (!empty($form_values['district'])) {
                $selected_district = $form_values['district'];
            }
        }

        $this->view->provinces = $provinces;
        $this->view->districts = $districts;

        $this->view->selected_province = $selected_province;
        $this->view->selected_district = $selected_district;

        $coldchain = new Model_ConsumptionSummary();
        $district_store_cold_chain_equipment_status = $coldchain->getDistrictStoreColdChainEquipmentStatus($selected_district);
        $taluka_and_ucs_cold_chain_capacity = $coldchain->getTalukaAndUCsColdChainCapacity($selected_district);

        $this->view->district_store_cold_chain_equipment_status = $district_store_cold_chain_equipment_status;
        $this->view->taluka_and_ucs_cold_chain_capacity = $taluka_and_ucs_cold_chain_capacity;
    }

    public function ajaxGetTehsilsAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->district_id) && !empty($this->_request->district_id)) {

            $consumption_summary = new Model_ConsumptionSummary();
            $selected_district = $this->_request->district_id;
            $tehsils = $consumption_summary->getTehsilesOfDistrict($selected_district);
            $this->view->result = $tehsils;
        }
    }

}
