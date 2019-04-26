<?php

/**
 * Model_HfDataMaster
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Summary Tables
 */

class Model_SummaryTables extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('HfDataMaster');
    }

    /**
     * Get Reported Wastages
     * 
     * @return string
     */
    public function getReportedWastages() {
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");

        $post = $this->form_values;

        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];

        $period = new Model_Period();
        $period->form_values = array(
            'id' => $post['period']
        );
        $months = $period->getPeriodById();

        $location = new Model_Locations();
        $location->form_values['pk_id'] = $all_provinces;
        $location_name = $location->getLocationName();

        $title = "Reporting Rate and Wastage Comparison  (" . $location_name . "-" . $yearcomp[0] . ")";

        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();

            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= '$product_name $title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $product_name . "' yAxisName='Percentage' numberSuffix='%' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
            }

            $xmlstore .= "</categories>";

            $start_date = $yearcomp[0] . '-' . str_pad($months->getBeginMonth(), 2, "0", STR_PAD_LEFT) . "-01";
            $end_date = $yearcomp[0] . '-' . str_pad($months->getEndMonth(), 2, "0", STR_PAD_LEFT) . "-01";

            $sql = "SELECT
                    summary_provincial.wastages_percentage
                    FROM
                    summary_provincial
                    WHERE
                    summary_provincial.item_id = '$products[$k]' AND
                    summary_provincial.province_id = $all_provinces AND
                    summary_provincial.stakeholder_id = 1 AND
                    DATE_FORMAT(summary_provincial.reporting_date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
            $str_sql = $this->_em_read->getConnection()->prepare($sql);
            $str_sql->execute();
            $rowWastage = $str_sql->fetchAll();

            $xmlstore .= "<dataset seriesName='Wastage'>";
            foreach ($rowWastage as $val2) {
                $xmlstore .= "<set value='" . round($val2['wastages_percentage']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $sql = "SELECT
                summary_provincial.reporting_rate
                FROM
                summary_provincial
                WHERE
                summary_provincial.item_id = '$products[$k]' AND
                summary_provincial.province_id = $all_provinces AND
                summary_provincial.stakeholder_id = 1 AND
                DATE_FORMAT(summary_provincial.reporting_date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
            $str_sql = $this->_em_read->getConnection()->prepare($sql);
            $str_sql->execute();
            $rowRR = $str_sql->fetchAll();

            $xmlstore .= "<dataset seriesName='Reporting Rate'>";
            foreach ($rowRR as $val2) {
                $xmlstore .= "<set value='" . round($val2['reporting_rate']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $obj_product = new Model_ItemPackSizes();
            $prod_result = $obj_product->getProductById($products[0]);

            $xmlstore .="<trendlines>
                <line startvalue='" . $prod_result->getWastageRateAllowed() . "' color='EE2000' displayvalue='Wastage Allowed:" . $prod_result->getWastageRateAllowed() . "%' valueonright='1' />
                </trendlines>";

            $xmlstore .="</chart>";

            $xmlstore_array[] = $xmlstore;
        }

        return $xmlstore_array;
    }

    /**
     * Get Consumption Amc
     * 
     * @return string
     */
    public function getConsumptionAmc() {
        /*
          Yearly Comparision - National
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");

        $post = $this->form_values;

        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];
        $period = new Model_Period();
        $period->form_values = array(
            'id' => $post['period']
        );
        $months = $period->getPeriodById();

        $location = new Model_Locations();
        $location->form_values['pk_id'] = $all_provinces;
        $location_name = $location->getLocationName();

        $title = "Vaccination vs Average Monthly Consumption (" . $location_name . "-" . $yearcomp[0] . ")";

        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();

            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption='$product_name $title' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $product_name . "' yAxisName='Doses' numberSuffix='' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
            }

            $xmlstore .= "</categories>";

            $start_date = $yearcomp[0] . '-' . str_pad($months->getBeginMonth(), 2, "0", STR_PAD_LEFT) . "-01";
            $end_date = $yearcomp[0] . '-' . str_pad($months->getEndMonth(), 2, "0", STR_PAD_LEFT) . "-01";

            $sql = "SELECT
                    summary_provincial.consumption,
                    summary_provincial.average_consumption
                    FROM
                    summary_provincial
                    WHERE
                    summary_provincial.item_id = '$products[$k]' AND
                    summary_provincial.province_id = $all_provinces AND
                    summary_provincial.stakeholder_id = 1 AND
                    DATE_FORMAT(summary_provincial.reporting_date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
            $str_sql = $this->_em_read->getConnection()->prepare($sql);
            $str_sql->execute();
            $rowData = $str_sql->fetchAll();

            $xmlstore .= "<dataset seriesName='Consumption'>";
            foreach ($rowData as $val2) {
                $xmlstore .= "<set value='" . round($val2['consumption']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $xmlstore .= "<dataset seriesName='Average Monthly Consumption*'>";
            foreach ($rowData as $val2) {
                $xmlstore .= "<set value='" . round($val2['average_consumption']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $xmlstore .="</chart>";

            $xmlstore_array[] = $xmlstore;
        }

        return $xmlstore_array;
    }

    /**
     * Get Consumption Mos
     * 
     * @return string
     */
    public function getConsumptionMos() {
        /*
          Yearly Comparision - National
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");

        $post = $this->form_values;

        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];

        $period = new Model_Period();
        $period->form_values = array(
            'id' => $post['period']
        );
        $months = $period->getPeriodById();

        $location = new Model_Locations();
        $location->form_values['pk_id'] = $all_provinces;
        $location_name = $location->getLocationName();

        $title = "Vaccination vs Stock On Hand (" . $location_name . "-" . $yearcomp[0] . ")";

        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();

            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption='$product_name $title' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $product_name . "' yAxisName='Doses' numberSuffix='' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
            }

            $xmlstore .= "</categories>";

            $start_date = $yearcomp[0] . '-' . str_pad($months->getBeginMonth(), 2, "0", STR_PAD_LEFT) . "-01";
            $end_date = $yearcomp[0] . '-' . str_pad($months->getEndMonth(), 2, "0", STR_PAD_LEFT) . "-01";

            $sql = "SELECT
                    summary_provincial.consumption,
                    summary_provincial.soh_store
                    FROM
                    summary_provincial
                    WHERE
                    summary_provincial.item_id = '$products[$k]' AND
                    summary_provincial.province_id = $all_provinces AND
                    summary_provincial.stakeholder_id = 1 AND
                    DATE_FORMAT(summary_provincial.reporting_date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
            $str_sql = $this->_em_read->getConnection()->prepare($sql);
            $str_sql->execute();
            $rowData = $str_sql->fetchAll();

            $xmlstore .= "<dataset seriesName='Consumption/Vaccination'>";
            foreach ($rowData as $val2) {
                $xmlstore .= "<set value='" . round($val2['consumption']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $xmlstore .= "<dataset seriesName='Stock On Hand(SOH)'>";
            foreach ($rowData as $val2) {
                $xmlstore .= "<set value='" . round($val2['soh_store']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $xmlstore .="</chart>";

            $xmlstore_array[] = $xmlstore;
        }

        return $xmlstore_array;
    }

}
