<?php

/**
 * Van2_DashletController
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
 *  Controller for Van2 Dashlet
 */
class Van2_DashletController extends App_Controller_Base {

    public function vanAction() {
        $this->_helper->layout->setLayout("layout");
        //*******Default values********
        $selected_province = 2;
        $selected_district = 63;
        $selected_year = date('Y');
        $selected_month = date('m') - 3;
        $selected_product = 0;
        $current_item_id = 43;


        //********Filters*********
        //VAN Provinces: currently only Sindh
        $provinces = array("2" => "Sindh");
        $model_van = new Model_Van();


        // VAN Districts Combo
        $districts_combo = $model_van->getAllDistricts();


        //Year Combo
        $current_year = date('Y');
        $years = array($current_year => $current_year, $current_year - 1 => $current_year - 1, $current_year - 2 => $current_year - 2);
        //Month Combo
        $all_months = $model_van->getAllMonths();
       
        //Vaccines
        $all_vaccines = $model_van->getAllRoutineVaccines();

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

        //VAN Districts
        //User selected all VAN Districts
        if ($selected_district == 'all') {
            $districts = Array(
                0 => Array('district_id' => 63, 'district_name' => "Ghotki"),
                1 => Array('district_id' => 109, 'district_name' => "Larkana"),
                2 => Array('district_id' => 4279, 'district_name' => "Shaheed Benazir Abad")
            );
        }
        //User selected one VAN Distrcit
        else {
            $model_van = new Model_Van();
            $location_name = $model_van->getLocationNameById($selected_district);
            $selected_district_name = $location_name[0]['location_name'];

            $districts = Array(
                0 => Array('district_id' => $selected_district, 'district_name' => $selected_district_name)
            );
        }


        $monthly_coverage_array = array();
        $monthly_wastage_array = array();

        foreach ($all_vaccines as $item) {
            //Initialization of monthly_coverage_array
            $monthly_coverage_array[$item['item_id']][] = $item['item_name'];

            $monthly_wastage_array[$item['item_id']]['AcceptableLevel'] = $item['AcceptableLevel'];
            $monthly_wastage_array[$item['item_id']][] = $item['item_name'];

            $count = 1;
            foreach ($districts as $district) {
                $monthly_coverage_array[$item['item_id']][$count] = null;
                $monthly_wastage_array[$item['item_id']][$count] = null;
                $count++;
            }
        }

        $selected_month = 01;
        $dateObj = DateTime::createFromFormat('!m', $selected_month);
        $monthName = $dateObj->format('F');
    
        $display_date = "(" . $monthName . "-" . $selected_year . ")";
        $this->view->display_date = $display_date;

        //------------------------------ One Month Coverage for each District ------------------------------

        $count = 1;
        // Get results for selected Districts from Ghotki, Larkana and Benazir Abad
        foreach ($districts as $district) {
            $district_id = $district['district_id'];

            //Get One Month Coverage 
            //for Districts Ghotki, Larkana and Benazir Abad
            $coverage_result = $model_van->getOneMonthCoverageForDistrict($district_id, $selected_month, $selected_year);

            foreach ($coverage_result as $row) {
                $monthly_coverage_array[$row['item_id']][$count] = $row['CoveragePercentage'];
            }
            $count++;
        }

        $category_labels = "";
        $sub_caption = "";
        foreach ($districts as $district) {
            $district_name = $district['district_name'];
            $category_labels .= "<category label='" . $district_name . "'/>";
            $sub_caption .= "$district_name, ";
        }
        $data_sets = "";
        foreach ($monthly_coverage_array as $row) {
            $product_name = $row[0];
            $coverage_1 = $row[1];
            $coverage_2 = $row[2];
            $coverage_3 = $row[3];


            $data_sets .= "<dataset seriesname='" . $product_name . "'>
                        <set value='" . $coverage_1 . "' />
                        <set value='" . $coverage_2 . "' />
                        <set value='" . $coverage_3 . "' />
                    </dataset>";
        }
        $xmlstore5 = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= 'Coverage " . $display_date . "' 
                subcaption='" . $sub_caption . "'
                exportFileName='Coverage " . $display_date . "' 
                yAxisName='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                 <categories>
                 $category_labels
                </categories>
                    $data_sets
            </chart>";
        $this->view->xmlstore5 = $xmlstore5;

        //------------------------------ One Month Stock Status for each District ------------------------------
        //Default bOPV (Routine)
        $current_item = array('item_id' => 43, 'item_name' => 'bOPV (Routine)');
        $current_item_id = $current_item['item_id'];

        $category7 = "";
        $dataset7 = "";
        $stock_status = Array(
            'Low Stock' => Array('63' => 0, '109' => 0, '4279' => 0),
            'Under Stock' => Array('63' => 0, '109' => 0, '4279' => 0),
            'Adequate Stock' => Array('63' => 0, '109' => 0, '4279' => 0),
            'Over Stock' => Array('63' => 0, '109' => 0, '4279' => 0)
        );

        foreach ($districts as $district) {
            $district_id = $district['district_id'];

            //Get Percent Of Ucs Stocked out Udner stock Adequetly Stocked Over stocked
            //for Districts Ghotki, Larkana and Benazir Abad
            $monthly_stock_status_result = $model_van->getPercentOfUcsStockedoutUdnerstockAdequetlyStockedOverstocked($district_id, $current_item_id, $selected_month, $selected_year);

            $stock_status['Low Stock'][$district_id] = $monthly_stock_status_result[0]['stock_out_uc_percent'];
            $stock_status['Under Stock'][$district_id] = $monthly_stock_status_result[0]['under_stocked_uc_percent'];
            $stock_status['Adequate Stock'][$district_id] = $monthly_stock_status_result[0]['adequetly_stocked_uc_percent'];
            $stock_status['Over Stock'][$district_id] = $monthly_stock_status_result[0]['over_stocked_uc_percent'];

            $current_item['item_id'] = $monthly_stock_status_result[0]['item_id'];
            $current_item['item_name'] = $monthly_stock_status_result[0]['item_name'];

            $category7 .= "<category label='" . $district['district_name'] . "' />";
        }


        $low_stock_values = "";
        $dataset7 .= "<dataset seriesname='Low Stock: 0 ~ 0.49'>";
        foreach ($districts as $district) {
            $low_stock_values .= "<set value='" . $stock_status['Low Stock'][$district['district_id']] . "' />";
        }
        $dataset7 .= $low_stock_values;
        $dataset7 .= "</dataset>";

        $under_stock_values = "";
        $dataset7 .= "<dataset seriesname='Under Stock: 0.5 ~ 0.99'>";
        foreach ($districts as $district) {
            $under_stock_values .= "<set value='" . $stock_status['Under Stock'][$district['district_id']] . "' />";
        }
        $dataset7 .= $under_stock_values;
        $dataset7 .= "</dataset>";

        $adequate_stock_values = "";
        $dataset7 .= "<dataset seriesname='Adequate Stock: 1 ~ 1.5'>";
        foreach ($districts as $district) {
            $adequate_stock_values .= "<set value='" . $stock_status['Adequate Stock'][$district['district_id']] . "' />";
        }
        $dataset7 .= $adequate_stock_values;
        $dataset7 .= "</dataset>";

        $over_stock_values = "";
        $dataset7 .= "<dataset seriesname='Over Stock: > 1.5'>";
        foreach ($districts as $district) {
            $over_stock_values .= "<set value='" . $stock_status['Over Stock'][$district['district_id']] . "' />";
        }
        $dataset7 .= $over_stock_values;
        $dataset7 .= "</dataset>";

        $xmlstore7 = "
                <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= '% of UCs with low stock, understocked, adequately stocked or overstocked " . $display_date . "' 
                subcaption='" . $current_item['item_name'] . "'
                exportFileName='Stock Status " . $display_date . "'  
                xaxisname='' 
                yaxisname='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'    
                >
    <categories>
        " . $category7 . "
    </categories>
    " . $dataset7 . "
</chart>";
        $this->view->xmlstore7 = $xmlstore7;
        $this->view->current_item_id = $current_item['item_id'];
        //------------------------------ End One Month Stock Status for each District ------------------------------
        //------------------------------ One Month Wastage for each District ------------------------------

        $count = 1;
        // Get results for Districts Ghotki, Larkana and Benazir Abad
        foreach ($districts as $district) {
            $district_id = $district['district_id'];

            //Get One Month Wastage 
            //for Districts Ghotki, Larkana and Benazir Abad
            $wastage_result = $model_van->getOneMonthWastageForDistrict($district_id, $selected_month, $selected_year);

            foreach ($wastage_result as $row) {
                $monthly_wastage_array[$row['item_id']][$count] = $row['WastagePercentage'];
            }
            $count++;
        }

        $category_labels_8 = "";
        $sub_caption_8 = "";
        foreach ($districts as $district) {
            $district_name = $district['district_name'];
            $category_labels_8 .= "<category label='" . $district_name . "'/>";
            $sub_caption_8 .= "$district_name, ";
        }
        $data_sets_8 = "";
        foreach ($monthly_wastage_array as $row) {
            $product_name = $row[0];
            $wastage_1 = $row[1];
            $wastage_2 = $row[2];
            $wastage_3 = $row[3];
            $acceptable_level = $row['AcceptableLevel'];

            $data_sets_8 .= "<dataset seriesname='" . $product_name . ": " . $acceptable_level . "% " . "'>
                        <set value='" . $wastage_1 . "' />
                        <set value='" . $wastage_2 . "' />
                        <set value='" . $wastage_3 . "' />
                    </dataset>";
        }
        $xmlstore8 = "
            <chart 
                exportEnabled='1' 
                slantLabels='1' 
                exportAction='Download' 
                caption= 'Wastage " . $display_date . "' 
                subcaption='" . $sub_caption_8 . "'
                exportFileName='Wastage " . $display_date . "' 
                yAxisName='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'>
                 <categories>
                 $category_labels_8
                </categories>
                    $data_sets_8
            </chart>";
        $this->view->xmlstore8 = $xmlstore8;

        //------------------------------ End One Month Wastage for each District ------------------------------
        //
        ////------------------------------ % of UCs with working, working needs service or not working CCE ------------------------------
        $category10 = "";
        $dataset10 = "";

        $xmlstore10 = "
<chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= '% of UCs with working, working needs service or not working CCE " . $display_date . "' 
                exportFileName='CCE Status " . date("Y/m/d  H:i:s") . "' 
                xaxisname='' 
                yaxisname='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'    
                >
    <categories>
        <category label='Ghotki' />
        <category label='Benazir Abad' />
        <category label='Larkana' />
        <category label='Karachi' />
    </categories>
    <dataset seriesname='Working %'>
        <set value='25' />
        <set value='25' />
        <set value='25' />
        <set value='25' />
    </dataset>
    <dataset seriesname='Working Needs Service %'>
        <set value='25' />
        <set value='25' />
        <set value='25' />
        <set value='25' />
    </dataset>
    <dataset seriesname='Not Working %'>
        <set value='25' />
        <set value='25' />
        <set value='25' />
        <set value='25' />
    </dataset>
</chart>    
";
        $this->view->xmlstore10 = $xmlstore10;
        ////------------------------------ End % of UCs with working, working needs service or not working CCE ------------------------------
        //-------------------------------------- UC Reporting Rate by District ------------------------------------
        $uc_reporting_rate_array = array();
        foreach ($districts as $district) {
            $district_id = $district['district_id'];
            $uc_reporting_rate_array[$district_id]['district_name'] = $district['district_name'];
            $uc_reporting_rate_array[$district_id]['RR'] = 0;
            $uc_reporting_rate_result = $model_van->getUCReportingRateByDistrict($district_id, $selected_month, $selected_year);
            $uc_reporting_rate_array[$district_id]['RR'] = $uc_reporting_rate_result[0]['reportingPercentage'];
        }
        $this->view->uc_reporting_rate_array = $uc_reporting_rate_array;
        //-------------------------------------- End UC Reporting Rate by District ------------------------------------
        $this->view->provinces = $provinces;
        $this->view->districts = $districts_combo;
        $this->view->years = $years;
        $this->view->all_months = $all_months;
        $this->view->all_vaccines = $all_vaccines;

        $this->view->selected_province = $selected_province;
        $this->view->selected_district = $selected_district;
        $this->view->selected_year = $selected_year;
        $this->view->selected_month = $selected_month;
        $this->view->selected_product = $selected_product;
    }

    public function van2Action() {
        $this->_helper->layout->setLayout("layout");
        //*******Default values********
        $selected_province = 2;
        $selected_district = 63;
        $selected_district_name = "Ghotki";
        $selected_year_to = date('Y');
        $selected_month_to = date('m') - 3;
        $selected_product = 0;

        //********Filters*********
        //VAN Provinces: currently only Sindh
        $provinces = array("2" => "Sindh");
        //VAN Districts
        $model_van = new Model_Van();
        $districts = $model_van->getAllDistricts();
        //Year
        $current_year = date('Y');
        $years = array($current_year => $current_year, $current_year - 1 => $current_year - 1, $current_year - 2 => $current_year - 2);
        //Month
        $all_months = $model_van->getAllMonths();
        //Vaccines
        $all_vaccines = $model_van->getAllRoutineVaccines();

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
                $selected_year_to = $form_values['year'];
            }
            if (!empty($form_values['month'])) {
                $selected_month_to = $form_values['month'];
            }
            if (!empty($form_values['items'])) {
                $selected_product = $form_values['items'];
            }
        }

        //Date to dislay
        $to_date_Obj = DateTime::createFromFormat('!m', $selected_month_to);
        $to_month_name = $to_date_Obj->format('F');
        $display_date_to = $to_month_name . "-" . $selected_year_to;
        $display_date_from = date("M-Y", strtotime("-11 months", strtotime($display_date_to)));
        $display_date = "(" . $display_date_from . " to " . $display_date_to . ")";

        // Making date period of 12 Months
        $end_date_str = $selected_year_to . '-' . $selected_month_to;
        $start_date_str = date("Y-m", strtotime("-11 months", strtotime($end_date_str)));

        $start_date_str_formated = date('Y-m-d', strtotime($start_date_str));
        $end_date_str_formated = date('Y-m-t', strtotime($end_date_str));

        $start_date_obj = new DateTime($start_date_str_formated);
        $end_date_obj = new DateTime($end_date_str_formated);

        $date_interval = DateInterval::createFromDateString("1 month");

        $period = new DatePeriod($start_date_obj, $date_interval, $end_date_obj);

        //Tranfer data to the view
        $this->view->period = $period;
        $this->view->provinces = $provinces;
        $this->view->districts = $districts;
        $this->view->years = $years;
        $this->view->all_months = $all_months;
        $this->view->all_vaccines = $all_vaccines;
        $this->view->selected_province = $selected_province;
        $this->view->selected_district = $selected_district;
        $this->view->selected_year = $selected_year_to;
        $this->view->selected_month = $selected_month_to;
        $this->view->selected_product = $selected_product;
        $this->view->display_date = $display_date;

        $location_name = $model_van->getLocationNameById($selected_district);
        $selected_district_name = $location_name[0]['location_name'];

        $month_array = array();
        $monthly_coverage_array = array();
        $monthly_wastage_array = array();
        $stock_outs_array = array();
        $reporting_rate_array = array();

        foreach ($all_vaccines as $item) {
            //Initialization of monthly_coverage_array
            $monthly_coverage_array[$item['item_id']][] = $item['item_name'];

            //Initialization of monthly_wastage_array
            $monthly_wastage_array[$item['item_id']]['AcceptableLevel'] = $item['AcceptableLevel'];
            $monthly_wastage_array[$item['item_id']][] = $item['item_name'];

            //Initialization of stock_outs_array
            $stock_outs_array[$item['item_id']][] = $item['item_name'];

            //Initialization of reporting_rate_array
            $reporting_rate_array[$item['item_id']][] = $item['item_name'];

            $count = 1;
            foreach ($period as $date) {
                $monthly_coverage_array[$item['item_id']][$count] = null;
                $monthly_wastage_array[$item['item_id']][$count] = null;
                $stock_outs_array[$item['item_id']][$count] = null;
                $reporting_rate_array[$item['item_id']][$count] = null;
                $count++;
            }
        }

        //------------------------------ Coverage by Month for Selected District ------------------------------

        $count = 1;
        // Get results for District
        foreach ($period as $date) {

            $m = $date->format("M");
            $y = $date->format("y");
            $month_array[] = "$m-" . "$y";

            //Get One Month Coverage 
            //for District
            $month = $date->format("m");
            $year = $date->format("Y");
            $coverage_result = $model_van->getOneMonthCoverageForDistrict($selected_district, $month, $year);
            foreach ($coverage_result as $row) {
                $monthly_coverage_array[$row['item_id']][$count] = $row['CoveragePercentage'];
            }
            $count++;
        }

        $category_5 = "";
        $dataset_5 = "";
        foreach ($month_array as $month) {
            $category_5 .= "<category label='" . $month . "' stepskipped='false' labeltooltext='' />";
        }
        $total_coverage_array = array();
        foreach ($monthly_coverage_array as $key => $value) {
            $total_coverage = $value[1] + $value[2] + $value[3] + $value[4] + $value[5] + $value[6] + $value[7] + $value[8] + $value[9] + $value[10] + $value[11] + $value[12];
            $total_coverage_array[$value[0]] = $total_coverage / 12;
            $dataset_5 .= "<dataset seriesname='" . $value[0] . "'>
                    <set value='" . $value[1] . "' />
                    <set value='" . $value[2] . "' />
                    <set value='" . $value[3] . "' />
                    <set value='" . $value[4] . "' />
                    <set value='" . $value[5] . "' />
                    <set value='" . $value[6] . "' />
                    <set value='" . $value[7] . "' />
                    <set value='" . $value[8] . "' />
                    <set value='" . $value[9] . "' />
                    <set value='" . $value[10] . "' />
                    <set value='" . $value[11] . "' />
                    <set value='" . $value[12] . "' />
                </dataset>";
        }
        $xmlstore5 = "
            <chart 
                    exportEnabled='1' 
                    slantLabels='1' 
                    yAxisMaxValue='100' 
                    exportAction='Download' 
                    caption= 'Coverage by Month " . $display_date . "' 
                    subcaption='" . $selected_district_name . "'
                    exportFileName='Coverage by Month " . $display_date . "' 
                    xaxisname='' 
                    yaxisname='' 
                    showValues='0'
                    numberprefix='%'
                    formatNumberScale='0' 
                    linethickness='2' 
                    bgcolor='FFFFFF'
                    showborder='0'
                    showalternatehgridcolor='0' 
                    divlinecolor='CCCCCC' 
                    legendshadow='0' 
                    legendborderalpha='0' 
                    showcanvasborder='0' 
                    canvasborderalpha='0' 
                    canvasbordercolor='CCCCCC' 
                    canvasborderthickness='0'
                    palettecolors='#f8bd19,#008ee4,#33bdda,#e44a00,#6baa01,#583e78,#ff69b4'
            >
                <categories>
                    $category_5
                </categories>
                $dataset_5
            </chart>      
            ";


        $this->view->total_coverage_array = $total_coverage_array;
        $this->view->xmlstore5 = $xmlstore5;

//------------------------------ Wastage Rate by Month for Selected District ------------------------------

        $count = 1;
        // Get results for District
        foreach ($period as $date) {

            //Get One Month Wastage 
            //for District
            $month = $date->format("m");
            $year = $date->format("Y");
            $wastage_result = $model_van->getOneMonthWastageForDistrict($selected_district, $month, $year);
            foreach ($wastage_result as $row) {
                $monthly_wastage_array[$row['item_id']][$count] = $row['WastagePercentage'];
            }
            $count++;
        }

        $category_8 = "";
        $dataset_8 = "";
        foreach ($month_array as $month) {
            $category_8 .= "<category label='" . $month . "' stepskipped='false' labeltooltext='' />";
        }

        foreach ($monthly_wastage_array as $key => $value) {
            $dataset_8 .= "<dataset seriesname='" . $value[0] . ": " . $value['AcceptableLevel'] . "% " . "'>
                    <set value='" . $value[1] . "' />
                    <set value='" . $value[2] . "' />
                    <set value='" . $value[3] . "' />
                    <set value='" . $value[4] . "' />
                    <set value='" . $value[5] . "' />
                    <set value='" . $value[6] . "' />
                    <set value='" . $value[7] . "' />
                    <set value='" . $value[8] . "' />
                    <set value='" . $value[9] . "' />
                    <set value='" . $value[10] . "' />
                    <set value='" . $value[11] . "' />
                    <set value='" . $value[12] . "' />
                </dataset>";
        }
        $xmlstore8 = "
            <chart 
                    exportEnabled='1' 
                    slantLabels='1' 
                    yAxisMaxValue='100' 
                    exportAction='Download' 
                    caption= 'Wastage Rate " . $display_date . "' 
                    subcaption='" . $selected_district_name . "'
                    exportFileName='Wasatge Rate " . $display_date . "' 
                    xaxisname='' 
                    yaxisname='' 
                    showValues='0'
                    numberprefix='%'
                    formatNumberScale='0' 
                    linethickness='2' 
                    bgcolor='FFFFFF'
                    showborder='0'
                    showalternatehgridcolor='0' 
                    divlinecolor='CCCCCC' 
                    legendshadow='0' 
                    legendborderalpha='0' 
                    showcanvasborder='0' 
                    canvasborderalpha='0' 
                    canvasbordercolor='CCCCCC' 
                    canvasborderthickness='0'
                    palettecolors='#f8bd19,#008ee4,#33bdda,#e44a00,#6baa01,#583e78,#ff69b4'
            >
                <categories>
                    $category_8
                </categories>
                $dataset_8
            </chart>      
            ";

        $this->view->xmlstore8 = $xmlstore8;



        //------------------------------Stock outs over time------------------------------ 
        $count = 1;
        // Get results for District
        foreach ($period as $date) {

            //Get One Month Wastage 
            //for District
            $month = $date->format("m");
            $year = $date->format("Y");
            $stock_outs_result = $model_van->getPercentOfUcsStockedout($selected_district, $month, $year);
            foreach ($stock_outs_result as $row) {
                $stock_outs_array[$row['item_id']][$count] = $row['stock_out_uc_percent'];
            }
            $count++;
        }

        $category_7 = "";
        $dataset_7 = "";
        foreach ($month_array as $month) {
            $category_7 .= "<category label='" . $month . "' stepskipped='false' labeltooltext='' />";
        }
        foreach ($stock_outs_array as $key => $value) {
            $dataset_7 .= "<dataset seriesname='" . $value[0] . "'>
                    <set value='" . $value[1] . "' />
                    <set value='" . $value[2] . "' />
                    <set value='" . $value[3] . "' />
                    <set value='" . $value[4] . "' />
                    <set value='" . $value[5] . "' />
                    <set value='" . $value[6] . "' />
                    <set value='" . $value[7] . "' />
                    <set value='" . $value[8] . "' />
                    <set value='" . $value[9] . "' />
                    <set value='" . $value[10] . "' />
                    <set value='" . $value[11] . "' />
                    <set value='" . $value[12] . "' />
                </dataset>";
        }
        $xmlstore7 = "
            <chart 
                    exportEnabled='1' 
                    slantLabels='1' 
                    yAxisMaxValue='100' 
                    exportAction='Download' 
                    caption= 'Stock outs over time " . $display_date . "' 
                    subcaption='" . $selected_district_name . "'
                    exportFileName='Stock outs over time " . $display_date . "' 
                    xaxisname='' 
                    yaxisname='' 
                    showValues='0'
                    numberprefix='%'
                    formatNumberScale='0' 
                    linethickness='2' 
                    bgcolor='FFFFFF'
                    showborder='0'
                    showalternatehgridcolor='0' 
                    divlinecolor='CCCCCC' 
                    legendshadow='0' 
                    legendborderalpha='0' 
                    showcanvasborder='0' 
                    canvasborderalpha='0' 
                    canvasbordercolor='CCCCCC' 
                    canvasborderthickness='0'
                    palettecolors='#f8bd19,#008ee4,#33bdda,#e44a00,#6baa01,#583e78,#ff69b4'
            >
                <categories>
                    $category_7
                </categories>
                $dataset_7
            </chart>      
            ";

        $this->view->xmlstore7 = $xmlstore7;


        //------------------------------ Reporting Rate ------------------------------
        $count = 1;
        // Get results for District
        foreach ($period as $date) {

            //Get One Month Reporting Rate 
            //for District
            $month = $date->format("m");
            $year = $date->format("Y");
            $reporting_rate_result = $model_van->getOneMonthReportingRateForDistrict($selected_district, $month, $year);
            foreach ($reporting_rate_result as $row) {
                $reporting_rate_array[$row['item_id']][$count] = $row['ReportingRate'];
            }
            $count++;
        }

        $category_10 = "";
        $dataset_10 = "";
        foreach ($month_array as $month) {
            $category_10 .= "<category label='" . $month . "' stepskipped='false' labeltooltext='' />";
        }
        foreach ($reporting_rate_array as $key => $value) {
            $dataset_10 .= "<dataset seriesname='" . $value[0] . "'>
                    <set value='" . $value[1] . "' />
                    <set value='" . $value[2] . "' />
                    <set value='" . $value[3] . "' />
                    <set value='" . $value[4] . "' />
                    <set value='" . $value[5] . "' />
                    <set value='" . $value[6] . "' />
                    <set value='" . $value[7] . "' />
                    <set value='" . $value[8] . "' />
                    <set value='" . $value[9] . "' />
                    <set value='" . $value[10] . "' />
                    <set value='" . $value[11] . "' />
                    <set value='" . $value[12] . "' />
                </dataset>";
        }
        $xmlstore10 = "
            <chart 
                    exportEnabled='1' 
                    slantLabels='1' 
                    yAxisMaxValue='100' 
                    exportAction='Download' 
                    caption= 'Reporting Rate " . $display_date . "' 
                    subcaption='" . $selected_district_name . "'
                    exportFileName='Reporting Rate " . $display_date . "' 
                    xaxisname='' 
                    yaxisname='' 
                    showValues='0'
                    numberprefix='%'
                    formatNumberScale='0' 
                    linethickness='2' 
                    bgcolor='FFFFFF'
                    showborder='0'
                    showalternatehgridcolor='0' 
                    divlinecolor='CCCCCC' 
                    legendshadow='0' 
                    legendborderalpha='0' 
                    showcanvasborder='0' 
                    canvasborderalpha='0' 
                    canvasbordercolor='CCCCCC' 
                    canvasborderthickness='0'
                    palettecolors='#f8bd19,#008ee4,#33bdda,#e44a00,#6baa01,#583e78,#ff69b4'
            >
                <categories>
                    $category_10
                </categories>
                $dataset_10
            </chart>      
            ";

        $this->view->xmlstore10 = $xmlstore10;
    }

    /**
     * % of UCs with low stock, understocked, adequately stocked or overstocked
     * @param type $param
     */
    public function ajaxGetStockStatusAction() {
        $this->_helper->layout->disableLayout();
        //Default bOPV (Routine)
        $current_item = array('item_id' => 43, 'item_name' => 'bOPV (Routine)');
        $current_item_id = $current_item['item_id'];
        $selected_district = 63;
        $selected_district_name = "Ghotki";

        $model_van = new Model_Van();

        if (isset($this->_request->item) && !empty($this->_request->item)) {
            $current_item_id = $this->_request->item;
            $selected_month = $this->_request->month;
            $selected_year = $this->_request->year;
            $selected_district = $this->_request->district;
            //if month is single digit prepend 0
            if ($selected_month < 10) {
                $selected_month = "0" . $selected_month;
            }

            //VAN Districts
            //User selected all VAN Districts
            if ($selected_district == 'all') {
                $districts = Array(
                    0 => Array('district_id' => 63, 'district_name' => "Ghotki"),
                    1 => Array('district_id' => 109, 'district_name' => "Larkana"),
                    2 => Array('district_id' => 4279, 'district_name' => "Shaheed Benazir Abad")
                );
            }
            //User selected one VAN Distrcit
            else {
                $location_name = $model_van->getLocationNameById($selected_district);
                $selected_district_name = $location_name[0]['location_name'];

                $districts = Array(
                    0 => Array('district_id' => $selected_district, 'district_name' => $selected_district_name)
                );
            }

            //Date to dislay
            $dateObj = DateTime::createFromFormat('!m', $selected_month);
            $monthName = $dateObj->format('F');
            $display_date = "(" . $monthName . "-" . $selected_year . ")";

            //------------------------------ One Month Stock Status for each District ------------------------------
            $category7 = "";
            $dataset7 = "";
            $stock_status = Array(
                'Low Stock' => Array('63' => 0, '109' => 0, '4279' => 0),
                'Under Stock' => Array('63' => 0, '109' => 0, '4279' => 0),
                'Adequate Stock' => Array('63' => 0, '109' => 0, '4279' => 0),
                'Over Stock' => Array('63' => 0, '109' => 0, '4279' => 0)
            );

            foreach ($districts as $district) {
                $district_id = $district['district_id'];

                //Get Percent Of Ucs Stocked out Udner stock Adequetly Stocked Over stocked
                //for Districts Ghotki, Larkana and Benazir Abad
                $monthly_stock_status_result = $model_van->getPercentOfUcsStockedoutUdnerstockAdequetlyStockedOverstocked($district_id, $current_item_id, $selected_month, $selected_year);

                $stock_status['Low Stock'][$district_id] = $monthly_stock_status_result[0]['stock_out_uc_percent'];
                $stock_status['Under Stock'][$district_id] = $monthly_stock_status_result[0]['under_stocked_uc_percent'];
                $stock_status['Adequate Stock'][$district_id] = $monthly_stock_status_result[0]['adequetly_stocked_uc_percent'];
                $stock_status['Over Stock'][$district_id] = $monthly_stock_status_result[0]['over_stocked_uc_percent'];

                $current_item['item_id'] = $monthly_stock_status_result[0]['item_id'];
                $current_item['item_name'] = $monthly_stock_status_result[0]['item_name'];

                $category7 .= "<category label='" . $district['district_name'] . "' />";
            }

            $low_stock_values = "";
            $dataset7 .= "<dataset seriesname='Low Stock: 0 ~ 0.49'>";
            foreach ($districts as $district) {
                $low_stock_values .= "<set value='" . $stock_status['Low Stock'][$district['district_id']] . "' />";
            }
            $dataset7 .= $low_stock_values;
            $dataset7 .= "</dataset>";

            $under_stock_values = "";
            $dataset7 .= "<dataset seriesname='Under Stock: 0.5 ~ 0.99'>";
            foreach ($districts as $district) {
                $under_stock_values .= "<set value='" . $stock_status['Under Stock'][$district['district_id']] . "' />";
            }
            $dataset7 .= $under_stock_values;
            $dataset7 .= "</dataset>";

            $adequate_stock_values = "";
            $dataset7 .= "<dataset seriesname='Adequate Stock: 1 ~ 1.5'>";
            foreach ($districts as $district) {
                $adequate_stock_values .= "<set value='" . $stock_status['Adequate Stock'][$district['district_id']] . "' />";
            }
            $dataset7 .= $adequate_stock_values;
            $dataset7 .= "</dataset>";

            $over_stock_values = "";
            $dataset7 .= "<dataset seriesname='Over Stock: > 1.5'>";
            foreach ($districts as $district) {
                $over_stock_values .= "<set value='" . $stock_status['Over Stock'][$district['district_id']] . "' />";
            }
            $dataset7 .= $over_stock_values;
            $dataset7 .= "</dataset>";


            $xmlstore7 = "
                <chart 
                exportEnabled='1' 
                slantLabels='1' 
                yAxisMaxValue='100' 
                exportAction='Download' 
                caption= '% of UCs with low stock, understocked, adequately stocked or overstocked " . $display_date . "' 
                subcaption='" . $current_item['item_name'] . "'
                exportFileName='Stock Status " . $display_date . "' 
                xaxisname='' 
                yaxisname='' 
                showValues='1' 
                formatNumberScale='0' 
                theme='fint'    
                >
    <categories>
        " . $category7 . "
    </categories>
    " . $dataset7 . "
</chart>";
            $this->view->xmlstore7 = $xmlstore7;
            $this->view->current_item_id = $current_item['item_id'];
            //------------------------------ End One Month Stock Status for each District ------------------------------
        }
    }

}
