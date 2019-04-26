<?php

/**
 * Model_Graphs
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Graphs
 */
class Model_Graphs extends Model_Base {

    /**
     * Comp Graph Option Year National
     * @return string
     */
    public function compGraphOptionYearNational() {
        /*
          Yearly Comparision - National
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $post = $this->form_values;
        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];
        $all_districts = $post['all_districts'];
        $optvals = $post['optvals'];
        $period = new Model_Period();
        $period->form_values = array('id' => $post['period']);
        $months = $period->getPeriodById();
        $rep_option = new Model_ReportOptions();
        $rep_option->form_values = array('stakeholder' => 1, 'report_id' => $post['indicators'], 'report_comp' => $optvals);
        $query = $rep_option->getReportDataSql();
        $title = $query->getReportTitleSql();
        $location = new Model_Locations();
        $loc_name = '';
        if (!empty($all_provinces)) {
            $location->form_values = array("pk_id" => $all_provinces);
            $loc_name = $location->getLocationName();
        }
        if (!empty($all_districts)) {
            $location->form_values = array("pk_id" => $all_districts);
            $loc_name = $location->getLocationName();
        }
        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();
            list($indicator, $compare_options) = explode("-", str_replace("Report", "Graph", str_replace("Province", "Provincial", $title)));

            if ($optvals == 7 || $optvals == 8) {
                $graph_caption = $indicator . " of " . $product_name . "(" . $yearcomp . ")";
            } else {
                $graph_caption = $indicator . " of " . $product_name;
            }

            if ($optvals == 1) {
                $graph_subcaption = $compare_options;
            } elseif ($optvals == 2) {
                $graph_subcaption = "Provincial " . $compare_options . " for " . $loc_name;
            } elseif ($optvals == 3) {
                $graph_subcaption = "District " . $compare_options . " for " . $loc_name;
            }
            if ($post['indicators'] == 'GMOS') {
                $y_text = "Months";
            } else {
                $y_text = "Doses";
            }
            if ($post['indicators'] == 'GCLOSINGFLD') {
                $indicator = 'Stock On Hand (UC)';
            } else if ($post['indicators'] == 'GCLOSING') {
                $indicator = 'Stock On Hand (District+Tehsil)';
            } else if ($post['indicators'] == 'GMOS') {
                $indicator = 'Month of Stock (EPI Centers)';
            }


            if ($optvals == 1) {
                $camp_options = "Year - National";
                $sub_caption = $indicator . '→' . $camp_options;
            } else if ($optvals == 2) {
                $camp_options = "Year - Provincial";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $loc_name;
            } else if ($optvals == 3) {
                $camp_options = "Year - District";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $loc_name;
            } else if ($optvals == 7) {
                $camp_options = "Geographical - Provinical";
                $sub_caption = $indicator . '→' . $camp_options;
            } else if ($optvals == 8) {
                $camp_options = "Geographical - District";
                $sub_caption = $indicator . '→' . $camp_options;
            }


            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1'  exportAction='Download' caption='" . $graph_caption . "' subCaption='" . $sub_caption . "' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $loc_name . " - " . $product_name . "' yAxisName='$y_text' numberSuffix='' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
                for ($j = sizeof($yearcomp) - 1; $j >= 0; $j--) {
                    $sql = "select " . str_replace("\$i", $i, $query->getReportDataSql()) . " as xyz  from dual ";
                    $sql = str_replace("\$yearcomp[\$j]", $yearcomp[$j], $sql);
                    $sql = str_replace("'\$products[\$k]'", "'" . $products[$k] . "'", $sql);
                    $sql = str_replace("\$seluser", 1, $sql);
                    $sql = str_replace("\$all_provinces", $all_provinces, $sql);
                    $sql = str_replace("\$all_districts", "'" . $all_districts . "'", $sql);

                    $str_sql = $this->_em_read->getConnection()->prepare($sql);

                    $str_sql->execute();
                    $row = $str_sql->fetchAll();
                    if (!empty($row)) {
                        $res = explode('*', $row[0]['xyz']);
                        $row_data = $res[$query->getReportDataPosition()] / 1;
                        $filedata1[$yearcomp[$j]][$monthval[$i - 1]] = $row_data;
                    }
                }
            }
            $xmlstore .= "</categories>";
            foreach ($filedata1 as $key1 => $value1) {
                $xmlstore .= "<dataset seriesName='$key1'>";
                foreach ($value1 as $val2) {
                    $xmlstore .= "<set value='" . Round($val2, 2) . "' />";
                }
                $xmlstore .= "</dataset>";
            }
            $xmlstore .= "</chart>";
            $xmlstore_array[] = $xmlstore;
        }
        return $xmlstore_array;
    }

    /**
     * MS Graph Option Year
     *
     * @return string
     */
    public function MSGraphOptionYear() {
        /*
          Yearly Comparision - National
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $post = $this->form_values;
        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];
        $all_districts = $post['all_districts'];
        $optvals = $post['optvals'];
        $period = new Model_Period();
        $period->form_values = array('id' => $post['period']);
        $months = $period->getPeriodById();
        $rep_option = new Model_ReportOptions();
        $rep_option->form_values = array('stakeholder' => 1, 'report_id' => 'GISSUES', 'report_comp' => $optvals);
        $query = $rep_option->getReportDataSql();
        $location = new Model_Locations();
        $location->form_values['pk_id'] = $all_provinces;
        $location_name = $location->getLocationName();
        $title = "Vaccination vs Average Monthly Consumption(" . $location_name . "-" . $yearcomp[0] . ")";
        $rep_option->form_values = array('stakeholder' => 1, 'report_id' => 'GAMC', 'report_comp' => $optvals);
        $query2 = $rep_option->getReportDataSql();
        $cache = Zend_Registry::get('cacheManager')->getCache('file');
        $vaccinationvsamc = "VACCVSAMC_$post[period]$yearcomp[0]$products[0]$all_provinces$optvals";

        $end_month = $months->getEndMonth();
        if ($yearcomp[0] == date("Y") && (int) $end_month > (int) date("m")) {
            $end_month = date("m");
        }

        if (!$xmlstore_array = $cache->load($vaccinationvsamc)) {
            for ($k = 0; $k < sizeof($products); $k++) {
                $product_obj = new Model_ItemPackSizes();
                $product_obj->form_values['pk_id'] = $products[$k];
                $product_name = $product_obj->getProductName();
                $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption='$product_name $title' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $product_name . "' yAxisName='Doses' numberSuffix='' showValues='1' formatNumberScale='0' theme='fint'>";
                $xmlstore .= "<categories>";
                for ($i = $months->getBeginMonth(); $i <= $end_month; $i++) {
                    $month_name = $monthval[$i - 1];
                    $xmlstore .= "<category label='$month_name' />";
                    for ($j = sizeof($yearcomp) - 1; $j >= 0; $j--) {
                        $sql = "select " . str_replace("\$i", $i, $query->getReportDataSql()) . " as xyz  from dual ";
                        $sql = str_replace("\$yearcomp[\$j]", $yearcomp[$j], $sql);
                        $sql = str_replace("'\$products[\$k]'", "'" . $products[$k] . "'", $sql);
                        $sql = str_replace("\$seluser", 1, $sql);
                        $sql = str_replace("\$all_provinces", $all_provinces, $sql);
                        $sql = str_replace("\$all_districts", "'" . $all_districts . "'", $sql);
                        $str_sql = $this->_em_read->getConnection()->prepare($sql);
                        $str_sql->execute();
                        $row = $str_sql->fetchAll();
                        if (!empty($row)) {
                            $res = explode('*', $row[0]['xyz']);
                            $row_data = $res[$query->getReportDataPosition()] / 1;
                            $filedata1[$yearcomp[$j]][$monthval[$i - 1]] = $row_data;
                        }
                        $sql = "select " . str_replace("\$i", $i, $query2->getReportDataSql()) . " as xyz  from dual ";
                        $sql = str_replace("\$yearcomp[\$j]", $yearcomp[$j], $sql);
                        $sql = str_replace("'\$products[\$k]'", "'" . $products[$k] . "'", $sql);
                        $sql = str_replace("\$seluser", 1, $sql);
                        $sql = str_replace("\$all_provinces", $all_provinces, $sql);
                        $sql = str_replace("\$all_districts", "'" . $all_districts . "'", $sql);
                        $str_sql = $this->_em_read->getConnection()->prepare($sql);
                        $str_sql->execute();
                        $row = $str_sql->fetchAll();
                        if (!empty($row)) {
                            $res = explode('*', $row[0]['xyz']);
                            $row_data = $res[$query2->getReportDataPosition()] / 1;
                            $filedata2[$yearcomp[$j]][$monthval[$i - 1]] = $row_data;
                        }
                    }
                }
                $xmlstore .= "</categories>";
                foreach ($filedata1 as $key1 => $value1) {
                    $xmlstore .= "<dataset seriesName='Consumption'>";
                    foreach ($value1 as $val2) {
                        $xmlstore .= "<set value='" . round($val2) . "' />";
                    }
                    $xmlstore .= "</dataset>";
                }
                foreach ($filedata2 as $key1 => $value1) {
                    $xmlstore .= "<dataset seriesName='Average Monthly Consumption*'>";
                    foreach ($value1 as $val2) {
                        $xmlstore .= "<set value='" . round($val2) . "' />";
                    }
                    $xmlstore .= "</dataset>";
                }
                $xmlstore .= "</chart>";
                $xmlstore_array[] = $xmlstore;
            }
            $cache->save($xmlstore_array, $vaccinationvsamc);
        }
        return $xmlstore_array;
    }

    /**
     * Get MS Graph Cons MOS
     * @return string
     */
    public function getMSGraphConsMOS() {
        /*
          Yearly Comparision - National
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $post = $this->form_values;
        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];
        $all_districts = $post['all_districts'];
        $optvals = $post['optvals'];

        $period = new Model_Period();
        $period->form_values = array('id' => $post['period']);
        $months = $period->getPeriodById();
        $rep_option = new Model_ReportOptions();
        $rep_option->form_values = array('stakeholder' => 1, 'report_id' => 'GISSUES', 'report_comp' => $optvals);
        $query = $rep_option->getReportDataSql();
        $location = new Model_Locations();
        $location->form_values['pk_id'] = $all_provinces;
        $location_name = $location->getLocationName();
        $title = "Vaccination vs Stock On Hand (" . $location_name . "-" . $yearcomp[0] . ")";
        $rep_option->form_values = array('stakeholder' => 1, 'report_id' => 'GCLOSING', 'report_comp' => $optvals);
        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();
            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption='$product_name $title' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $product_name . "' yAxisName='Doses' numberSuffix='' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
                for ($j = sizeof($yearcomp) - 1; $j >= 0; $j--) {
                    $sql = "select " . str_replace("\$i", $i, $query->getReportDataSql()) . " as xyz  from dual ";
                    $sql = str_replace("\$yearcomp[\$j]", $yearcomp[$j], $sql);
                    $sql = str_replace("'\$products[\$k]'", "'" . $products[$k] . "'", $sql);
                    $sql = str_replace("\$seluser", 1, $sql);
                    $sql = str_replace("\$all_provinces", $all_provinces, $sql);
                    $sql = str_replace("\$all_districts", "'" . $all_districts . "'", $sql);
                    $dbg_sql .= $sql . '<br>';
                    $str_sql = $this->_em_read->getConnection()->prepare($sql);
                    $str_sql->execute();
                    $row = $str_sql->fetchAll();
                    if (!empty($row)) {
                        $res = explode('*', $row[0]['xyz']);
                        $row_data = $res[$query->getReportDataPosition()] / 1;
                        $filedata1[$yearcomp[$j]][$monthval[$i - 1]] = $row_data;
                    }
                    $sql = "select REPgetCB('POBRA','$i','" . $yearcomp[$j] . "','" . $products[$k] . "',1,'$all_provinces','$all_provinces') as xyz  from dual ";
                    $dbg_sql .= $sql . '<br>';
                    $str_sql = $this->_em_read->getConnection()->prepare($sql);
                    $str_sql->execute();
                    $row = $str_sql->fetchAll();
                    if (!empty($row)) {
                        $filedata2[] = explode('*', $row[0]['xyz']);
                    }
                }
            }
            $xmlstore .= "</categories>";
            foreach ($filedata1 as $value1) {
                $xmlstore .= "<dataset seriesName='Consumption/Vaccination'>";
                foreach ($value1 as $val2) {
                    $xmlstore .= "<set value='" . round($val2) . "' />";
                }
                $xmlstore .= "</dataset>";
            }
            $xmlstore .= "<dataset seriesName='Stock On Hand(SOH)'>";
            foreach ($filedata2 as $val2) {
                $val = $val2[0];
                $xmlstore .= "<set value='" . round($val) . "' />";
            }
            $xmlstore .= "</dataset>";
            $xmlstore .= "</chart>";
            $xmlstore_array[] = $xmlstore;
        }
        return $xmlstore_array;
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function coldChainCapacity($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT DISTINCT cold_chain.asset_id,
        ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4 AS gross,
        ccm_models.net_capacity_20 + ccm_models.net_capacity_4 AS net_usable,
        ROUND( SUM( ( placements.quantity * pack_info.volum_per_vial ) / 1000 )
        ) AS being_used,
        placement_locations.pk_id,
        cold_chain.ccm_asset_type_id
        FROM cold_chain
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
        INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
        LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
        LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
        WHERE cold_chain.warehouse_id = $warehouse_id $where
        AND placement_locations.location_type = " . Model_PlacementLocations::LOCATIONTYPE_CCM . " AND
        ccm_status_history.ccm_status_list_id <> 3
        GROUP BY cold_chain.auto_asset_id
        ORDER BY cold_chain.asset_id, cold_chain.ccm_asset_type_id DESC";
        //  echo $str_sql . "<hr>";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if ($is_return == 2) {
            return $result;
        }
        $title = '';
        if ($type == 3) {
            $title = "+2-8C Cold Rooms (In Litres)";
        }
        if ($type == 1) {
            $title = "-20C Freezer Rooms (In Litres)";
        }
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "' yAxisName='Litres' showValues='1' formatNumberScale='0' theme='fint'>";
        $xmlstore .= "<categories>";
        foreach ($result as $row) {
            $xmlstore .= "<category label='" . $row['asset_id'] . "' />";
        }
        $xmlstore .= "</categories>";
        $xmlstore .= "<dataset seriesName='Net Usable'>";
        $base_url = Zend_Registry::get('baseurl');

        foreach ($result as $row) {
            $url = $base_url . "/stock/stock-in-bin-vaccines?id=" . $row['pk_id'];
            $xmlstore .= "<set link=\"$url\" value='" . round($row['net_usable']) . "' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesName='Being Used'>";
        foreach ($result as $row) {
            $url = $base_url . "/stock/stock-in-bin-vaccines?id=" . $row['pk_id'];
            $xmlstore .= "<set link=\"$url\" value='" . round($row['being_used']) . "' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore . "</chart>";
    }

    /**
     * MS Graph Reported Wastage
     * @return string
     */
    public function MSGraphReportedWastage() {
        /*
          Yearly Comparision - National
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $post = $this->form_values;
        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];
        $period = new Model_Period();
        $period->form_values = array('id' => $post['period']);
        $months = $period->getPeriodById();
        $location = new Model_Locations();
        $location->form_values['pk_id'] = $all_provinces;
        $location_name = $location->getLocationName();
        $title = "Reporting Rate and Wastage Comparison  (" . $location_name . "-" . $yearcomp[0] . ")";
        $cache = Zend_Registry::get('cacheManager')->getCache('file');
        $reportedwastages = "REPORTEDWASTAGES_$post[period]$yearcomp[0]$products[0]$all_provinces";

        $end_month = $months->getEndMonth();
        if ($yearcomp[0] == date("Y") && (int) $end_month > (int) date("m")) {
            $end_month = date("m");
        }

        if (!$xmlstore_array = $cache->load($reportedwastages)) {
            for ($k = 0; $k < sizeof($products); $k++) {
                $product_obj = new Model_ItemPackSizes();
                $product_obj->form_values['pk_id'] = $products[$k];
                $product_name = $product_obj->getProductName();
                $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= '$product_name $title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $product_name . "' yAxisName='Percentage' numberSuffix='%' showValues='1' rotateValues='0' formatNumberScale='0' theme='fint'>";
                $xmlstore .= "<categories>";
                for ($i = $months->getBeginMonth(); $i <= $end_month; $i++) {
                    $month_name = $monthval[$i - 1];
                    $xmlstore .= "<category label='$month_name' />";
                }
                $start_date = $yearcomp[0] . '-' . $months->getBeginMonth() . "-01";
                $end_date = $yearcomp[0] . '-' . $months->getEndMonth() . "-01";
                $sql = "select REPgetWastage('P','$start_date','$end_date',1,'$products[$k]',$all_provinces,0) as xyz  from dual ";
                $str_sql = $this->_em_read->getConnection()->prepare($sql);
                $str_sql->execute();
                $row = $str_sql->fetchAll();
                if (!empty($row)) {
                    $filedata1 = explode('*', $row[0]['xyz']);
                }
                $sql = "select REPgetRR('P','$start_date','$end_date',1,'$products[$k]',$all_provinces,0) as xyz  from dual ";
                $str_sql = $this->_em_read->getConnection()->prepare($sql);
                $str_sql->execute();
                $row = $str_sql->fetchAll();
                if (!empty($row)) {
                    $filedata2 = explode('*', $row[0]['xyz']);
                }
                $xmlstore .= "</categories>";
                $xmlstore .= "<dataset seriesName='Wastage'>";
                foreach ($filedata1 as $val2) {
                    $xmlstore .= "<set value='" . round($val2) . "' />";
                }
                $xmlstore .= "</dataset>";
                $xmlstore .= "<dataset seriesName='Reporting Rate'>";
                foreach ($filedata2 as $val2) {
$year_s = explode('-',$start_date);
$yea = $year_s[0];

if ($all_provinces == 4 && $yea == '2017') {

$val2 = 100;
} else if ($all_provinces == 5 && $yea == '2017' ) {
$val2 = 100;
} else if ($all_provinces == 6 && $yea == '2017' ) {
$val2 = 100;
}else if ($all_provinces == 7 && $yea == '2017' ) {
$val2 = 100;
}
else if ($all_provinces == 1 && $yea == '2017') {
$val2 = 100;
}
                    $xmlstore .= "<set value='" . round($val2) . "' />";
                }
                $xmlstore .= "</dataset>";
                $obj_product = new Model_ItemPackSizes();
                $prod_result = $obj_product->getProductById($products[0]);
                $xmlstore .= "<trendlines>
                <line startvalue='" . $prod_result->getWastageRateAllowed() . "' color='EE2000' displayvalue='Wastage Allowed:" . $prod_result->getWastageRateAllowed() . "%' valueonright='1' />
                </trendlines>";
                $xmlstore .= "</chart>";
                $xmlstore_array[] = $xmlstore;
            }
            $cache->save($xmlstore_array, $reportedwastages);
        }
        return $xmlstore_array;
    }

    /**
     * Simple Graph Option Year National
     * @return string
     */
    public function simpleGraphOptionYearNational() {
        /*
          Yearly Comparision - National
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $post = $this->form_values;
        $products = $post['products'];
        $yearcomp = $post['yearcomp'];
        $all_provinces = $post['all_provinces'];
        $all_districts = $post['all_districts'];
        $optvals = $post['optvals'];
        $location = new Model_Locations();
        $loc_name = array();
        if (!empty($all_provinces)) {
            $location->form_values = array("pk_id" => $all_provinces);
            $province_name = $location->getLocationName();
        }
        if (!empty($all_districts)) {
            $location->form_values = array("pk_id" => $all_districts);
            $loc_name[] = $location->getLocationName();
        }
        $period = new Model_Period();
        $period->form_values = array(
            'id' => $post['period']
        );
        $months = $period->getPeriodById();
        $rep_option = new Model_ReportOptions();
        $rep_option->form_values = array(
            'stakeholder' => 1,
            'report_id' => $post['indicators'],
            'report_comp' => $optvals
        );
        if ($post['indicators'] == 'GMOS') {
            $yaxis = "Months";
        } else {
            $yaxis = "Doses";
        }
        $query = $rep_option->getReportDataSql();
        $title = $query->getReportTitleSql();
        $location_name = implode(",", $loc_name);
        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();
            list($indicator, $compare_options) = explode("-", str_replace("Report", "Graph", str_replace("Province", "Provincial", $title)));
            $graph_caption = $indicator . " of " . $product_name;
            if ($optvals == 9) {
                $graph_subcaption = $compare_options;
            } elseif ($optvals == 10) {
                $graph_subcaption = "" . $compare_options . " for " . $province_name;
            } elseif ($optvals == 11) {
                $graph_subcaption = "" . $compare_options . " for " . $location_name;
            }
            if ($post['indicators'] == 'GCLOSINGFLD') {
                $indicator = 'Stock On Hand (UC)';
            } else if ($post['indicators'] == 'GCLOSING') {
                $indicator = 'Stock On Hand (District+Tehsil)';
            } else if ($post['indicators'] == 'GMOS') {
                $indicator = 'Month of Stock (EPI Centers)';
            }
            if ($optvals == 9) {
                $camp_options = "Geographical - National";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $yearcomp;
            } else if ($optvals == 10) {
                $camp_options = "Geographical - Provinical";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $province_name . '→' . $yearcomp;
            } else if ($optvals == 11) {
                $camp_options = "Geographical - District";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $location_name . '→' . $yearcomp;
            }

            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1'  exportAction='Download' caption='" . $graph_caption . "' subCaption='" . $sub_caption . "' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $loc_name . " - " . $product_name . "' yAxisName='$yaxis' numberSuffix='' showValues='1' theme='fint' formatNumberScale='0'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
                $sql = "select " . str_replace("\$i", $i, $query->getReportDataSql()) . " as xyz  from dual ";
                $sql = str_replace("\$year1", $yearcomp, $sql);
                $sql = str_replace("'\$products[\$k]'", "'" . $products[$k] . "'", $sql);
                $sql = str_replace("\$seluser", 1, $sql);
                $sql = str_replace("\$all_provinces", $all_provinces, $sql);
                $sql = str_replace("\$all_districts", "'" . $all_districts . "'", $sql);

                $str_sql = $this->_em_read->getConnection()->prepare($sql);
                $str_sql->execute();
                $row = $str_sql->fetchAll();
                if (!empty($row)) {
                    $res = explode('*', $row[0]['xyz']);
                    $row_data = $res[$query->getReportDataPosition()] / 1;
                    $filedata1[$yearcomp][$monthval[$i - 1]] = $row_data;
                }
            }
            $xmlstore .= "</categories>";
            foreach ($filedata1 as $key1 => $value1) {
                $xmlstore .= "<dataset seriesName='$key1'>";
                foreach ($value1 as $val2) {
                    $xmlstore .= "<set value='" . Round($val2, 2) . "' />";
                }
                $xmlstore .= "</dataset>";
            }
            $xmlstore .= "</chart>";
            $xmlstore_array[] = $xmlstore;
        }
        return $xmlstore_array;
    }

    /**
     * Comp Graph Option Geo Provincial
     *
     * @return string
     */
    public function compGraphOptionGeoProvincial() {
        /*
          Provincial
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $post = $this->form_values;
        $products = $post['products'];
        $year1 = $post['yearcomp'];
        $provinces = $post['all_provinces'];
        $optvals = $post['optvals'];
        $period = new Model_Period();
        $period->form_values = array(
            'id' => $post['period']
        );
        $months = $period->getPeriodById();
        $rep_option = new Model_ReportOptions();
        $rep_option->form_values = array(
            'stakeholder' => 1,
            'report_id' => $post['indicators'],
            'report_comp' => $optvals
        );
        $query = $rep_option->getReportDataSql();
        $title = $query->getReportTitleSql();
        $location = new Model_Locations();
        $loc_name = array();
        foreach ($provinces as $prov_id) {
            $location->form_values = array("pk_id" => $prov_id);
            $loc_name[] = $location->getLocationName();
        }
        $location_name = implode(",", $loc_name);
        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();
            list($indicator, $compare_options) = explode("-", str_replace("Report", "Graph", str_replace("Province", "Provincial", $title)));
            $graph_caption = $indicator . " of " . $product_name;
            if ($optvals == 1) {
                $graph_subcaption = $compare_options;
            } elseif ($optvals == 2) {
                $graph_subcaption = "Provincial " . $compare_options . " for " . $loc_name;
            } elseif ($optvals == 3) {
                $graph_subcaption = "District " . $compare_options . " for " . $loc_name;
            } elseif ($optvals == 7 || $optvals == 8) {
                $graph_subcaption = "" . $compare_options . " for " . $location_name . " of " . $product_name . " (" . $year1 . ")";
            }
            if ($post['indicators'] == 'GMOS') {
                $y_text = "Months";
            } else {
                $y_text = "Doses";
            }
            if ($post['indicators'] == 'GCLOSINGFLD') {
                $indicator = 'Stock On Hand (UC)';
            } else if ($post['indicators'] == 'GCLOSING') {
                $indicator = 'Stock On Hand (District+Tehsil)';
            } else if ($post['indicators'] == 'GMOS') {
                $indicator = 'Month of Stock (EPI Centers)';
            }
            if ($optvals == 1) {
                $camp_options = "Year - National";
                $sub_caption = $indicator . '→' . $camp_options;
            } else if ($optvals == 2) {
                $camp_options = "Year - Provincial";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $loc_name;
            } else if ($optvals == 3) {
                $camp_options = "Year - District";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $loc_name;
            } else if ($optvals == 7) {
                $camp_options = "Geographical - Provinical";
                $sub_caption = $indicator . '→' . $camp_options;
            } else if ($optvals == 8) {
                $camp_options = "Geographical - District";
                $sub_caption = $indicator . '→' . $camp_options;
            }
            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1'  exportAction='Download' subcaption='" . $sub_caption . "' caption='" . $graph_subcaption . "' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $location_name . " - " . $product_name . "' yAxisName='$y_text' numberSuffix='' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
                for ($j = 0; $j < sizeof($provinces); $j++) {
                    $location_obj = new Model_Locations();
                    $location_obj->form_values['product_id'] = $provinces[$j];
                    $loc_name = $location_obj->getLocationById();
                    $sql = "select " . str_replace("\$i", $i, $query->getReportDataSql()) . " as xyz  from dual ";
                    $sql = str_replace("\$year1", $year1, $sql);
                    $sql = str_replace("'\$products[\$k]'", "'" . $products[$k] . "'", $sql);
                    $sql = str_replace("\$seluser", 1, $sql);
                    $sql = str_replace("\$provinces[\$j]", $provinces[$j], $sql);

                    $str_sql = $this->_em_read->getConnection()->prepare($sql);

                    $str_sql->execute();
                    $row = $str_sql->fetchAll();
                    if (!empty($row)) {
                        $res = explode('*', $row[0]['xyz']);
                        $row_data = $res[$query->getReportDataPosition()] / 1;
                        $filedata1[$loc_name][$monthval[$i - 1]] = $row_data;
                    }
                }
            }
            $xmlstore .= "</categories>";
            foreach ($filedata1 as $key1 => $value1) {
                $xmlstore .= "<dataset seriesName='$key1'>";
                foreach ($value1 as $val2) {
                    $xmlstore .= "<set value='" . Round($val2, 2) . "' />";
                }
                $xmlstore .= "</dataset>";
            }
            $xmlstore .= "</chart>";
            $xmlstore_array[] = $xmlstore;
        }
        return $xmlstore_array;
    }

    /**
     * Comp Graph Option Geo District
     *
     * @return string
     */
    public function compGraphOptionGeoDistrict() {
        /*
          District
         */
        $monthval = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
        $post = $this->form_values;
        $products = $post['products'];
        $year1 = $post['yearcomp'];
        $provinces = $post['all_provinces'];
        $districts = $post['all_districts'];
        $optvals = $post['optvals'];
        $period = new Model_Period();
        $period->form_values = array('id' => $post['period']);
        $months = $period->getPeriodById();
        $rep_option = new Model_ReportOptions();
        $rep_option->form_values = array('stakeholder' => 1, 'report_id' => $post['indicators'], 'report_comp' => $optvals);
        $query = $rep_option->getReportDataSql();
        $title = $query->getReportTitleSql();
        $location = new Model_Locations();
        $loc_name = array();
        if (!empty($provinces)) {
            $location->form_values = array("pk_id" => $provinces);
            $province_name = $location->getLocationName();
        }
        foreach ($districts as $dist_id) {
            $location->form_values = array("pk_id" => $dist_id);
            $loc_name[] = $location->getLocationName();
        }
        $location_name = implode(",", $loc_name);
        for ($k = 0; $k < sizeof($products); $k++) {
            $product_obj = new Model_ItemPackSizes();
            $product_obj->form_values['pk_id'] = $products[$k];
            $product_name = $product_obj->getProductName();
            list($indicator, $compare_options) = explode("-", str_replace("Report", "Graph", str_replace("Province", "Provincial", $title)));
            $graph_caption = $indicator . " of " . $product_name;
            if ($optvals == 1) {
                $graph_subcaption = $compare_options;
            } elseif ($optvals == 2) {
                $graph_subcaption = "Provincial " . $compare_options . " for " . $location_name;
            } elseif ($optvals == 3) {
                $graph_subcaption = "District " . $compare_options . " for " . $location_name;
            } elseif ($optvals == 7) {
                $graph_subcaption = "" . $compare_options . " for " . $location_name . " of " . $product_name . " ( " . $year1 . ")";
            } elseif ($optvals == 8) {
                $graph_subcaption = "" . $compare_options . " for " . $province_name . "(" . $location_name . ") " . " of " . $product_name . " ( " . $year1 . ")";
            }
            if ($post['indicators'] == 'GMOS') {
                $y_text = "Months";
            } else {
                $y_text = "Doses";
            }
            if ($post['indicators'] == 'GCLOSINGFLD') {
                $indicator = 'Stock On Hand (UC)';
            } else if ($post['indicators'] == 'GCLOSING') {
                $indicator = 'Stock On Hand (District+Tehsil)';
            } else if ($post['indicators'] == 'GMOS') {
                $indicator = 'Month of Stock (EPI Centers)';
            }
            if ($optvals == 1) {
                $camp_options = "Year - National";
                $sub_caption = $indicator . '→' . $camp_options;
            } else if ($optvals == 2) {
                $camp_options = "Year - Provincial";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $loc_name;
            } else if ($optvals == 3) {
                $camp_options = "Year - District";
                $sub_caption = $indicator . '→' . $camp_options . '→' . $loc_name;
            } else if ($optvals == 7) {
                $camp_options = "Geographical - Provinical";
                $sub_caption = $indicator . '→' . $camp_options;
            } else if ($optvals == 8) {
                $camp_options = "Geographical - District";
                $sub_caption = $indicator . '→' . $camp_options;
            }

            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1'  exportAction='Download' subCaption='" . $sub_caption . "' caption='" . $graph_subcaption . "' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . " - " . $location_name . " - " . $product_name . "' yAxisName='$y_text' numberSuffix='' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";
            for ($i = $months->getBeginMonth(); $i <= $months->getEndMonth(); $i++) {
                $month_name = $monthval[$i - 1];
                $xmlstore .= "<category label='$month_name' />";
                for ($j = 0; $j < sizeof($districts); $j++) {
                    $location_obj = new Model_Locations();
                    $location_obj->form_values['product_id'] = $districts[$j];
                    $loc_name = $location_obj->getLocationById();
                    $sql = "select " . str_replace("\$i", $i, $query->getReportDataSql()) . " as xyz  from dual ";
                    $sql = str_replace("\$year1", $year1, $sql);
                    $sql = str_replace("'\$products[\$k]'", "'" . $products[$k] . "'", $sql);
                    $sql = str_replace("\$seluser", 1, $sql);
                    $sql = str_replace("\$provinces[0]", $provinces, $sql);
                    $sql = str_replace("\$dists[\$j]", $districts[$j], $sql);

                    $str_sql = $this->_em_read->getConnection()->prepare($sql);
                    $str_sql->execute();
                    $row = $str_sql->fetchAll();
                    if (!empty($row)) {
                        $res = explode('*', $row[0]['xyz']);
                        $row_data = $res[$query->getReportDataPosition()] / 1;
                        $filedata1[$loc_name][$monthval[$i - 1]] = $row_data;
                    }
                }
            }
            $xmlstore .= "</categories>";
            foreach ($filedata1 as $key1 => $value1) {
                $xmlstore .= "<dataset seriesName='$key1'>";
                foreach ($value1 as $val2) {
                    $xmlstore .= "<set value='" . Round($val2, 2) . "' />";
                }
                $xmlstore .= "</dataset>";
            }
            $xmlstore .= "</chart>";
            $xmlstore_array[] = $xmlstore;
        }
        return $xmlstore_array;
    }

    /**
     * Cold Chain Capacity Product
     * @param type $type
     * @return string
     */
    public function coldChainCapacityProduct($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                        cold_chain.asset_id,
                        i2_.item_name AS item_name,
                         stakeholder_item_pack_sizes.item_pack_size_id,
                         cold_chain.pk_id as location_id,
                        round( Sum( ( p0_.quantity * pack_info.volum_per_vial ) / 1000 ) ) AS quantity,
                        round( Sum( p0_.quantity ) ) AS quantityvials, i2_.color
                FROM
                        placements AS p0_
                INNER JOIN stock_batch_warehouses AS sbw ON p0_.stock_batch_warehouse_id = sbw.pk_id
                INNER JOIN stock_batch AS s1_ ON sbw.stock_batch_id = s1_.pk_id
                INNER JOIN pack_info ON s1_.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes AS i2_ ON stakeholder_item_pack_sizes.item_pack_size_id = i2_.pk_id
                INNER JOIN placement_locations ON p0_.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
                WHERE sbw.warehouse_id = $warehouse_id
                 $where AND i2_.item_category_id = 1
                GROUP BY cold_chain.asset_id, i2_.item_name
                HAVING quantity > 0
                ORDER BY cold_chain.asset_id,i2_.item_name ASC";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        $title = "";
        if ($is_return == 2) {
            return $result;
        }
        if ($type == 3) {
            $title = "+2-8C Cold Rooms Capacity (In Litres)";
        }
        if ($type == 1) {
            $title = "-20C Cold Rooms Capacity (In Litres)";
        }
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $title . '" labelDisplay="rotate"  numberprefix="' . $number_prefix . '" yAxisName="No.of vials" showvalues="0" exportEnabled="1" rotateValues="1" formatNumberScale="0" theme="fint">';
        $data_arr = array();
        $items = array();
        $asset_id = array();
        foreach ($result as $row) {
            if (!in_array($row['item_name'], $items)) {
                $items[] = $row['item_name'];
            }if (!in_array($row['asset_id'], $asset_id)) {
                $asset_id[] = $row['asset_id'];
            }
        }
        foreach ($items as $item) {
            foreach ($asset_id as $asset) {
                $data_arr[$item][$asset]['quantity'] = '';
            }
        }
        foreach ($result as $row) {
            $data_arr[$row['item_name']][$row['asset_id']]['quantity'] = $row['quantity'];
            $data_arr[$row['item_name']]['color'] = $row['color'];
        }
        $dataset = "";
        $categories = '<categories>';
        foreach ($asset_id as $asset) {
            $categories .= '<category label="' . $asset . '" />';
        }
        $categories .= '</categories>';
        foreach ($data_arr as $item => $sub) {
            $dataset .= '<dataset seriesname="' . $item . '" color="' . $data_arr[$item]['color'] . '" >';
            foreach ($sub as $val) {
                $dataset .= '<set color="' . $data_arr[$item]['color'] . '" value="' . ((!empty($val['quantity'])) ? $val['quantity'] : '') . '" />';
            }
            $dataset .= '</dataset>';
        }
        $xmlstore .= $categories;
        $xmlstore .= $dataset;
        return $xmlstore .= "</chart>";
    }

    /**
     * Cold Chain Capacity Vvm
     * @param type $type
     * @return string
     */
    public function coldChainCapacityVvm($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                        cold_chain.asset_id, i2_.item_name AS item_name,
                         s1_.item_pack_size_id, cold_chain.pk_id as location_id,
                         vvm_stages.pk_id as vvm_stage, vvm_stages.vvm_stage_value,
                        round( Sum( ( p0_.quantity * pack_info.volum_per_vial ) / 1000 ) ) AS quantity
                FROM placements AS p0_
                INNER JOIN stock_batch AS s1_ ON p0_.stock_batch_warehouse_id = s1_.pk_id
                INNER JOIN item_pack_sizes AS i2_ ON s1_.item_pack_size_id = i2_.pk_id
                INNER JOIN pack_info ON s1_.stakeholder_item_pack_size_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN placement_locations ON p0_.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
                INNER JOIN vvm_stages ON p0_.vvm_stage = vvm_stages.pk_id
                WHERE
                        s1_.warehouse_id = $warehouse_id
                 $where AND i2_.item_category_id = 1
                GROUP BY p0_.vvm_stage, i2_.item_name
                HAVING quantity > 0
                ORDER BY p0_.vvm_stage,i2_.item_name ASC";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if ($is_return == 2) {
            return $result;
        }
        if ($type == 3) {
            $title = "+2-8C Cold Rooms Capacity (In Litres)";
        }
        if ($type == 1) {
            $title = "-20C Cold Rooms Capacity (In Litres)";
        }
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $title . '" labelDisplay="rotate"  numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" formatNumberScale="0" theme="fint">';
        $data_arr = array();
        $vvm_stage = array();
        $item_name = array();
        foreach ($result as $row) {
            if (!in_array($row['vvm_stage_value'], $vvm_stage)) {
                $vvm_stage[] = $row['vvm_stage_value'];
            }if (!in_array($row['item_name'], $item_name)) {
                $item_name[] = $row['item_name'];
            }
        }
        foreach ($vvm_stage as $vvm) {
            foreach ($item_name as $items) {
                $data_arr[$vvm][$items]['quantity'] = '';
            }
        }
        foreach ($result as $row) {
            $data_arr[$row['vvm_stage_value']][$row['item_name']]['quantity'] = $row['quantity'];
        }
        $dataset = "";
        $categories = '<categories>';
        foreach ($item_name as $item) {

            $categories .= '<category label="' . $item . '" />';
        }
        $categories .= '</categories>';
        foreach ($data_arr as $vvm => $sub) {
            $dataset .= '<dataset seriesname="VVM ' . $vvm . '" >';
            foreach ($sub as $key => $val) {
                $dataset .= '<set value="' . $data_arr[$vvm][$key]['quantity'] . '" />';
            }
            $dataset .= '</dataset>';
        }
        $xmlstore .= $categories;
        $xmlstore .= $dataset;
        return $xmlstore .= "</chart>";
    }

    /**
     * ContributionBreakUps
     * @param type $type
     * @return string
     */
    public function contributionBreakup() {
        $year_to = $this->form_values['to_date'];
        if (empty($this->form_values['wh_id'])) {
            $wh_id = 1;
        } else {
            $wh_id = $this->form_values['wh_id'];
        }
        $str_sql = "SELECT
            Sum(stock_detail.quantity) AS qty, warehouses.warehouse_name,
            warehouses.pk_id, stock_master.stakeholder_activity_id,
            stakeholder_activities.activity
            FROM stock_master
            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN stakeholder_activities ON stock_master.stakeholder_activity_id = stakeholder_activities.pk_id
            WHERE stock_master.to_warehouse_id = 159 AND warehouses.pk_id = $wh_id
            AND stakeholders.stakeholder_type_id = 2
            AND YEAR ( stock_master.transaction_date ) = $year_to
            AND item_pack_sizes.item_category_id = 1
            GROUP BY stock_master.stakeholder_activity_id ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        $warehouse_name = $result[0]['warehouse_name'];
        $xmlstore = "<chart  exportEnabled='1' exportAction='Download' caption='$warehouse_name Contribution Breakup - Year $year_to' exportFileName='$warehouse_name Contribution Breakup - Year $year_to'  showValues='1' formatNumberScale='0' theme='fint' labelDisplay='rotate' slantLabels='1' numberSuffix=''>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[activity]' value='" . round($data['qty'], 1) . "' color='#2D9CF4' link=\"JavaScript:showSubGraphs('" . $data['pk_id'] . "');\" />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Product wise Contribution
     *
     * @param type $type
     * @return string
     */
    public function productWiseContribution() {
        $year_to = $this->form_values['to_date'];
        if (empty($this->form_values['wh_id'])) {
            $wh_id = 1;
        } else {
            $wh_id = $this->form_values['wh_id'];
        }
        $str_sql = "SELECT
        Sum(stock_detail.quantity) AS qty, warehouses.warehouse_name, warehouses.pk_id,
        stock_master.stakeholder_activity_id, item_pack_sizes.item_name
        FROM stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
        INNER JOIN locations ON  warehouses.province_id = locations.pk_id
        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN stakeholder_activities ON stock_master.stakeholder_activity_id = stakeholder_activities.pk_id
        WHERE stock_master.to_warehouse_id = 159
        AND warehouses.pk_id = $wh_id AND stakeholders.stakeholder_type_id = 2
        AND YEAR ( stock_master.transaction_date ) = $year_to
        AND item_pack_sizes.item_category_id = 1
        GROUP BY item_pack_sizes.pk_id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        $warhouse_name = $result[0]['warehouse_name'];
        $xmlstore = "<chart  exportEnabled='1' exportAction='Download' caption='$warhouse_name Product wise Contribution - Year $year_to' exportFileName='$warhouse_name Product wise Contribution - Year $year_to'  showValues='1' formatNumberScale='0' theme='fint' labelDisplay='rotate' slantLabels='1' numberSuffix=''>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[item_name]' value='" . round($data['qty'], 1) . "' color='#2D9CF4' link=\"JavaScript:showSubGraphs('" . $data['pk_id'] . "');\" />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function coldChainCapacitytest($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT DISTINCT cold_chain.asset_id,
        ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4 AS gross,
        ccm_models.net_capacity_20 + ccm_models.net_capacity_4 AS net_usable,
        ROUND( SUM( ( placements.quantity * pack_info.volum_per_vial ) / 1000 )
        ) AS being_used,
        placement_locations.pk_id,
        cold_chain.ccm_asset_type_id
        FROM cold_chain
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
        INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
        LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
        LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
        WHERE cold_chain.warehouse_id = $warehouse_id $where
        AND placement_locations.location_type = " . Model_PlacementLocations::LOCATIONTYPE_CCM . " AND
        ccm_status_history.ccm_status_list_id <> 3
        GROUP BY cold_chain.auto_asset_id
        ORDER BY cold_chain.asset_id, cold_chain.ccm_asset_type_id DESC LIMIT 5";
        //echo $str_sql."<hr>";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if ($is_return == 2) {
            return $result;
        }
        $title = '';
        if ($type == 3) {
            $title = "+2-8C Cold Rooms (In Litres)";
        }
        if ($type == 1) {
            $title = "-20C Freezer Rooms (In Litres)";
        }
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "' yAxisName='Litres' showValues='1' formatNumberScale='0' theme='fint'>";
        $xmlstore .= "<categories>";
        foreach ($result as $row) {
            $xmlstore .= "<category label='" . $row['asset_id'] . "' />";
        }
        $xmlstore .= "</categories>";
        $xmlstore .= "<dataset seriesName='Net Usable'>";
        $base_url = Zend_Registry::get('baseurl');

        foreach ($result as $row) {
            $url = $base_url . "/stock/stock-in-bin-vaccines?id=" . $row['pk_id'];
            $xmlstore .= "<set link=\"$url\" value='" . round($row['net_usable']) . "' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesName='Being Used'>";
        foreach ($result as $row) {
            $url = $base_url . "/stock/stock-in-bin-vaccines?id=" . $row['pk_id'];
            $xmlstore .= "<set link=\"$url\" value='" . round($row['being_used']) . "' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function coldStoreStock($type) {
        $is_return = $type;
        $curr_date = date("Y-m-d");
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT 
	i2_.item_name AS item_name,
	stakeholder_item_pack_sizes.item_pack_size_id,
	cold_chain.pk_id AS location_id,
	
	round(Sum(p0_.quantity)) AS quantity,
	i2_.color
        FROM
                placements AS p0_
        INNER JOIN stock_batch_warehouses AS sbw ON p0_.stock_batch_warehouse_id = sbw.pk_id
        INNER JOIN stock_batch AS s1_ ON sbw.stock_batch_id = s1_.pk_id
        INNER JOIN pack_info ON s1_.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes AS i2_ ON stakeholder_item_pack_sizes.item_pack_size_id = i2_.pk_id
        INNER JOIN placement_locations ON p0_.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        WHERE
                sbw.warehouse_id = $warehouse_id

        AND i2_.item_category_id = 1
        AND s1_.expiry_date >= '$curr_date'
        GROUP BY

                i2_.item_name
        HAVING
                quantity > 0
        ORDER BY

                i2_.item_name ASC";






        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if ($is_return == 2) {
            return $result;
        }
        $title = '';
        if ($type == 3) {
            $title = "Cold Store Stock(Vials)";
        }
        if ($type == 1) {
            $title = "Cold Store Stock(Vials)";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1' showLabels='0'  showborder='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";


        $base_url = Zend_Registry::get('baseurl');


        foreach ($result as $row) {

            $xmlstore .= "<set label='" . $row['item_name'] . "'   value='" . round($row['quantity']) . "' />";
        }


        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function dryStoreStock($type) {
        $curr_date = date("Y-m-d");
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT DISTINCT

	placement_summary.quantity AS Qty,
	placement_summary.item_name,
	stakeholder_item_pack_sizes.pk_id
        FROM
                placement_summary
        INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        WHERE
                stock_detail.`temporary` = 0
        AND non_ccm_locations.warehouse_id = $warehouse_id
        AND stock_batch.expiry_date >= '$curr_date'
        AND item_pack_sizes.item_category_id = 2
        AND item_pack_sizes.pk_id <>  39
        GROUP BY item_pack_sizes.pk_id
        ORDER BY item_name
        ";

        //echo $str_sql."<hr>";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();

        $title = '';
        if ($type == 3) {
            $title = "Dry Store Stock(Pcs)";
        }
        if ($type == 1) {
            $title = "Dry Store Stock(Pcs)";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1' showLabels='0'  showborder='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";
        foreach ($result as $row) {

            $xmlstore .= "<set label='" . $row['item_name'] . "'   value='" . round($row['Qty']) . "' />";
        }


        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function coldStoreMos($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
        i2_.item_name AS item_name,
        stakeholder_item_pack_sizes.item_pack_size_id,
        cold_chain.pk_id AS location_id,
        round(
                        Sum(
                                (
                                        p0_.quantity * pack_info.volum_per_vial
                                ) / 1000
                        )
                ) AS quantity,
        round(Sum(p0_.quantity)) AS quantityvials,
        i2_.color,
        ROUND((round(Sum(p0_.quantity))/epi_amc.amc),2) as MOS
        FROM
        placements AS p0_
        INNER JOIN stock_batch_warehouses AS sbw ON p0_.stock_batch_warehouse_id = sbw.pk_id
        INNER JOIN stock_batch AS s1_ ON sbw.stock_batch_id = s1_.pk_id
        INNER JOIN pack_info ON s1_.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes AS i2_ ON stakeholder_item_pack_sizes.item_pack_size_id = i2_.pk_id
        INNER JOIN placement_locations ON p0_.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        INNER JOIN epi_amc ON epi_amc.item_id = i2_.pk_id
        WHERE
        sbw.warehouse_id = $warehouse_id AND
        i2_.item_category_id = 1 AND
        epi_amc.warehouse_id = $warehouse_id AND
        epi_amc.amc_year = 2015
        GROUP BY
                i2_.item_name
        HAVING
                quantity > 0
        ORDER BY
                i2_.item_name ASC";
        //echo $str_sql."<hr>";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if ($is_return == 2) {
            return $result;
        }
        $title = '';
        if ($type == 3) {
            $title = "Cold Store Month of Stock";
        }
        if ($type == 1) {
            $title = "Cold Store Month of Stock";
        }
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "' yAxisName='months' showValues='1' formatNumberScale='0' theme='fint'>";

        foreach ($result as $row) {

            $xmlstore .= "<set label='" . $row['item_name'] . "' value='" . $row['MOS'] . "' />";
        }

        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function dryStoreMos($type) {

        $str_sql = "SELECT
            item_pack_sizes.pk_id,
            item_pack_sizes.item_name
            FROM
            item_pack_sizes
            WHERE
            item_pack_sizes.item_category_id = 2
            and item_pack_sizes.pk_id <> 39";







        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result_item = $rec->fetchAll();

        $title = '';
        if ($type == 3) {
            $title = "Dry Store Month of Stock";
        }
        if ($type == 1) {
            $title = "Dry Store Month of Stock";
        }
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "' yAxisName='months' showValues='1' formatNumberScale='0' theme='fint'>";

        foreach ($result_item as $row) {

            $stock_master = new Model_StockMaster();
            $mos = $stock_master->getDryStoreMos($row['pk_id']);

            $xmlstore .= "<set label='" . $row['item_name'] . "' value='" . $mos . "' />";
        }

        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function summaryCrFr($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
	SUM(A.gross) gross,
	SUM(A.net_usable) net_usable,
	SUM(A.being_used) being_used,
	A.ccm_asset_type_id
FROM
	(
		SELECT
			AssetSubtype.asset_type_name,
			ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4 AS gross,
			ccm_models.net_capacity_20 + ccm_models.net_capacity_4 AS net_usable,
			ROUND(
				SUM(
					(
						placements.quantity * pack_info.volum_per_vial
					) / 1000
				)
			) AS being_used,
			placement_locations.pk_id,
			cold_chain.ccm_asset_type_id
		FROM
			cold_chain
		INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
		LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
		LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
		INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
		LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
		LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
		LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
		WHERE
			cold_chain.warehouse_id = $warehouse_id
		
		AND placement_locations.location_type = 99
		AND ccm_status_history.ccm_status_list_id <> 3
		GROUP BY
	cold_chain.auto_asset_id
	) A
GROUP BY A.ccm_asset_type_id";



        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();

        $title = '';
        if ($type == 3) {
            $title = "Summary of CR/FR Capacity (Litres)";
        }
        if ($type == 1) {
            $title = "Summary of CR/FR Capacity (Litres)";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1'  showborder='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";

        foreach ($result as $row) {
            if ($row['ccm_asset_type_id'] == "15") {
                $lab = 'Net Capacity FR';
            } else if ($row['ccm_asset_type_id'] == "16") {
                $lab = 'Net Capacity CR';
            }

            $xmlstore .= "<set label='$lab'   value='" . round($row['net_usable']) . "' />";
        }

        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function crCapacity($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }

        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT 
SUM(A.gross) gross,

(SUM(A.being_used) / SUM(A.net_usable) )*100 used_percentage
from
(SELECT
	AssetSubtype.asset_type_name,
	
		ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4
	 AS gross,
	
		ccm_models.net_capacity_20 + ccm_models.net_capacity_4
	 AS net_usable,
	ROUND(
		SUM(
			(
				placements.quantity * pack_info.volum_per_vial
			) / 1000
		)
	) AS being_used,
	placement_locations.pk_id,
	cold_chain.ccm_asset_type_id
FROM
	cold_chain
INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
WHERE
	cold_chain.warehouse_id = $warehouse_id
AND (
	(
		cold_chain.ccm_asset_type_id = 3
		OR AssetMainType.pk_id = 3
	)
)
AND placement_locations.location_type = 99
AND ccm_status_history.ccm_status_list_id <> 3
AND cold_chain.ccm_asset_type_id = 16
GROUP BY
	cold_chain.auto_asset_id) A";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();

        $used_per = $result[0]['used_percentage'];
        $total_per = 100 - $result[0]['used_percentage'];
        if ($used_per > 100) {
            $total_per = 0;
        }
        if ($is_return == 2) {
            return $result;
        }
        $title = '';
        if ($type == 3) {
            $title = "CR Capacity";
        }
        if ($type == 1) {
            $title = "CR Capacity";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1'  showborder='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";

        $row = $result;
        $base_url = Zend_Registry::get('baseurl');
        $url = $base_url . "/reports/dashlet/cold-chain-capacity";

        $xmlstore .= "<set link=\"$url\" label='Available' value='" . round($total_per) . "' />";
        $xmlstore .= "<set link=\"$url\" label='Used' value='" . round($used_per) . "' />";


        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function frCapacity($type) {

        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT 
SUM(A.gross) gross,


(SUM(A.being_used) / SUM(A.net_usable) )*100 used_percentage
from
(SELECT
	AssetSubtype.asset_type_name,
	
		ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4
	 AS gross,
	
		ccm_models.net_capacity_20 + ccm_models.net_capacity_4
	 AS net_usable,
	ROUND(
		SUM(
			(
				placements.quantity * pack_info.volum_per_vial
			) / 1000
		)
	) AS being_used,
	placement_locations.pk_id,
	cold_chain.ccm_asset_type_id
FROM
	cold_chain
INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
WHERE
	cold_chain.warehouse_id = $warehouse_id
AND (
	(
		cold_chain.ccm_asset_type_id = 3
		OR AssetMainType.pk_id = 3
	)
)
AND placement_locations.location_type = 99
AND ccm_status_history.ccm_status_list_id <> 3
AND cold_chain.ccm_asset_type_id = 15
GROUP BY
	cold_chain.auto_asset_id) A";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        $used_per = $result[0]['used_percentage'];
        $total_per = 100 - $result[0]['used_percentage'];
        if ($used_per > 100) {
            $total_per = 0;
        }
        $title = '';
        if ($type == 3) {
            $title = "FR Capacity";
        }
        if ($type == 1) {
            $title = "FR Capacity";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1'  showborder='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";

        $row = $result;
        $base_url = Zend_Registry::get('baseurl');

        $url = $base_url . "/reports/dashlet/cold-chain-capacity/#coldchainCapacity20Div";
        $xmlstore .= "<set  link=\"$url\"  label='Available' value='" . round($total_per) . "' />";
        $xmlstore .= "<set link=\"$url\"  label='Used' value='" . round($used_per) . "' />";
        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function dryStoreCapacity($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT DISTINCT cold_chain.asset_id,
        ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4 AS gross,
        ccm_models.net_capacity_20 + ccm_models.net_capacity_4 AS net_usable,
        ROUND( SUM( ( placements.quantity * pack_info.volum_per_vial ) / 1000 )
        ) AS being_used,
        placement_locations.pk_id,
        cold_chain.ccm_asset_type_id
        FROM cold_chain
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
        INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
        LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
        LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
        WHERE cold_chain.warehouse_id = $warehouse_id $where
        AND placement_locations.location_type = " . Model_PlacementLocations::LOCATIONTYPE_CCM . " AND
        ccm_status_history.ccm_status_list_id <> 3
        GROUP BY cold_chain.auto_asset_id
        ORDER BY cold_chain.asset_id, cold_chain.ccm_asset_type_id DESC LIMIT 2";
        //echo $str_sql."<hr>";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if ($is_return == 2) {
            return $result;
        }
        $title = '';
        if ($type == 3) {
            $title = "Dry Store Capacity";
        }
        if ($type == 1) {
            $title = "Dry Store Capacity";
        }
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "' yAxisName='Litres' showValues='1' formatNumberScale='0' theme='fint'>";
        $xmlstore .= "<categories>";
        foreach ($result as $row) {
            $xmlstore .= "<category label='" . $row['asset_id'] . "' />";
        }
        $xmlstore .= "</categories>";
        $xmlstore .= "<dataset seriesName='Net Usable'>";
        $base_url = Zend_Registry::get('baseurl');

        foreach ($result as $row) {
            $url = $base_url . "/stock/stock-in-bin-vaccines?id=" . $row['pk_id'];
            $xmlstore .= "<set link=\"$url\" value='" . round($row['net_usable']) . "' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesName='Being Used'>";
        foreach ($result as $row) {
            $url = $base_url . "/stock/stock-in-bin-vaccines?id=" . $row['pk_id'];
            $xmlstore .= "<set link=\"$url\" value='" . round($row['being_used']) . "' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function getShipmentsData() {

        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
	item_pack_sizes.item_name,
	shipments.reference_number,
	warehouses.warehouse_name,
	stakeholder_activities.activity,
	shipment_detail.received_quantity,
	shipments.shipment_date,
        shipments.transaction_number,
	shipments.created_date,
	shipment_history.`status`
        FROM
                shipments

        INNER JOIN warehouses ON shipments.funding_source_id = warehouses.pk_id
        INNER JOIN stakeholder_activities ON shipments.stakeholder_activity_id = stakeholder_activities.pk_id
        INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
        INNER JOIN item_pack_sizes ON shipment_detail.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN shipment_history ON shipment_history.shipment_id = shipments.pk_id
        where shipment_history.status <> 'Received'
        AND  shipments.warehouse_id=$warehouse_id
        ORDER BY shipments.pk_id DESC";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();

        return $result;
    }

    /**
     * Cold Chain Capacity Product
     * @param type $type
     * @return string
     */
    public function ColdStoreItems($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $id = $this->form_values['id'];
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                        cold_chain.asset_id,
                        i2_.item_name AS item_name,
                         stakeholder_item_pack_sizes.item_pack_size_id,
                         cold_chain.pk_id as location_id,
                       cold_chain.asset_id,
                        round( Sum( p0_.quantity ) ) AS quantityvials, i2_.color
                FROM
                        placements AS p0_
                INNER JOIN stock_batch_warehouses AS sbw ON p0_.stock_batch_warehouse_id = sbw.pk_id
                INNER JOIN stock_batch AS s1_ ON sbw.stock_batch_id = s1_.pk_id
                INNER JOIN pack_info ON s1_.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes AS i2_ ON stakeholder_item_pack_sizes.item_pack_size_id = i2_.pk_id
                INNER JOIN placement_locations ON p0_.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
                WHERE sbw.warehouse_id = $warehouse_id
                 
                AND placement_locations.pk_id = '" . $id . "'
                GROUP BY i2_.item_name
                HAVING quantityvials > 0
                ORDER BY i2_.item_name ASC";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        $title = "";

        if ($type == 3) {
            $title = "+2-8C Cold Rooms Capacity (In Litres)";
        }
        if ($type == 1) {
            $title = "-20C Cold Rooms Capacity (In Litres)";
        }
        $number_prefix = "";
        $asset_id = $result[0]['asset_id'];
        $title = "$asset_id Vaccines Placement Status";
        $xmlstore = "<?xml version=\"1.0\"?>";
        //  $xmlstore .= '<chart caption="' . $title . '" labelDisplay="rotate"  numberprefix="' . $number_prefix . '" yAxisName="No.of vials" showvalues="0" exportEnabled="1" rotateValues="1" formatNumberScale="0" theme="fint">';

        $xmlstore .= "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' numberSuffix='%'  formatNumberScale='0' showPercentValues='1' theme='fint'>";


        $data_arr = array();
        $items = array();

        foreach ($result as $row) {
            if (!in_array($row['item_name'], $items)) {
                $items[] = $row['item_name'];
            }
        }
        foreach ($items as $item) {

            $data_arr[$item]['quantity'] = '';
        }
        foreach ($result as $row) {
            $data_arr[$row['item_name']]['quantity'] = $row['quantityvials'];
            $data_arr[$row['item_name']]['color'] = $row['color'];
        }


        foreach ($result as $row) {

            $xmlstore .= "<set label='" . $row['item_name'] . "' value='" . $row['quantityvials'] . "' />";
        }


        return $xmlstore .= "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function summaryFun($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
	SUM(A.gross) gross,
	SUM(A.net_usable) net_usable,
	SUM(A.being_used) being_used,
	A.ccm_asset_type_id
        FROM
	(
		SELECT
			AssetSubtype.asset_type_name,
			ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4 AS gross,
			ccm_models.net_capacity_20 + ccm_models.net_capacity_4 AS net_usable,
			ROUND(
				SUM(
					(
						placements.quantity * pack_info.volum_per_vial
					) / 1000
				)
			) AS being_used,
			placement_locations.pk_id,
			cold_chain.ccm_asset_type_id
		FROM
			cold_chain
		INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
		LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
		LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
		INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
		LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
		LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
		LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
		WHERE
                cold_chain.ccm_asset_type_id IN (15,16) AND
			cold_chain.warehouse_id = $warehouse_id
		
		AND placement_locations.location_type = 99
		AND ccm_status_history.ccm_status_list_id <> 3
		GROUP BY
	cold_chain.auto_asset_id
	) A
       GROUP BY A.ccm_asset_type_id";



        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();

        $title = '';
        if ($type == 3) {
            $title = "Summary of Functional/Non Functional Capacity (Litres)";
        }
        if ($type == 1) {
            $title = "Summary of Functional/Non Functional (Litres)";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1'  showborder='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";

        foreach ($result as $row) {
            if ($row['ccm_asset_type_id'] == "15") {
                $lab = 'Functional';
            } else if ($row['ccm_asset_type_id'] == "16") {
                $lab = 'Non Functional';
                $row['net_usable'] = 0;
            }

            $xmlstore .= "<set label='$lab'   value='" . round($row['net_usable']) . "' />";
        }

        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function ColdChainCapacityGraphs($type) {
        $is_return = $type;
        if ($type == 2) {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )";
        } else {
            $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        }

        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT 
SUM(A.gross) gross,

(SUM(A.being_used) / SUM(A.net_usable) )*100 used_percentage
from
(SELECT
	AssetSubtype.asset_type_name,
	
		ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4
	 AS gross,
	
		ccm_models.net_capacity_20 + ccm_models.net_capacity_4
	 AS net_usable,
	ROUND(
		SUM(
			(
				placements.quantity * pack_info.volum_per_vial
			) / 1000
		)
	) AS being_used,
	placement_locations.pk_id,
	cold_chain.ccm_asset_type_id
FROM
	cold_chain
INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
WHERE
	cold_chain.warehouse_id = $warehouse_id
AND (
	(
		cold_chain.ccm_asset_type_id = 3
		OR AssetMainType.pk_id = 3
	)
)
AND placement_locations.location_type = 99
AND ccm_status_history.ccm_status_list_id <> 3
AND cold_chain.ccm_asset_type_id = 16
GROUP BY
	cold_chain.auto_asset_id) A";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();

        $used_per = $result[0]['used_percentage'];
        $total_per = 100 - $result[0]['used_percentage'];
        if ($used_per > 100) {
            $total_per = 0;
        }
        if ($is_return == 2) {
            return $result;
        }
        $title = '';
        if ($type == 3) {
            $title = "Cold Chain Capacity";
        }
        if ($type == 1) {
            $title = "Cold Chain Capacity";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1'  showborder='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";

        $row = $result;
        $base_url = Zend_Registry::get('baseurl');
        $url = $base_url . "/reports/dashlet/cold-chain-capacity";

        $xmlstore .= "<set link=\"$url\" label='Available' value='" . round($total_per) . "' />";
        $xmlstore .= "<set link=\"$url\" label='Used' value='" . round($used_per) . "' />";


        return $xmlstore . "</chart>";
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function getCcemSummaryReport() {

        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
	ccm_asset_types.asset_type_name,
	SUM(

		IF (
			ccm_status_list.pk_id = 1,
			1,
			0
		)
	) AS working,
	SUM(

		IF (
			ccm_status_list.pk_id = 3,
			1,
			0
		)
	) AS not_working
FROM
	cold_chain
INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
LEFT JOIN list_detail ON ccm_models.gas_type = list_detail.pk_id
LEFT JOIN ccm_cold_rooms ON cold_chain.pk_id = ccm_cold_rooms.ccm_id
LEFT JOIN list_detail AS CoolingSystem ON ccm_cold_rooms.cooling_system = CoolingSystem.pk_id
INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
INNER JOIN ccm_status_list ON ccm_status_history.ccm_status_list_id = ccm_status_list.pk_id
INNER JOIN stakeholders ON cold_chain.source_id = stakeholders.pk_id
INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
WHERE
	cold_chain.warehouse_id = $warehouse_id
GROUP BY
	ccm_asset_types.pk_id ORDER BY ccm_asset_types.asset_type_name";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();

        return $result;
    }
  
     /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function wastagesCost() {


        $form_vales = $this->form_values;
        $dose_cost = $form_vales['dose_cost'];
        $year = $form_vales['year'];
        $province = $form_vales['province'];
        $product = $form_vales['product'];
        $month = $form_vales['month'];
        if ($form_vales['currency'] == 1) {
            $usd = 1;
        } else {
            $usd = $form_vales['usd'];
        }


        if ($form_vales['cost_type'] == 1) {


            $un_price = $form_vales['un_price'];
            $un_res = explode('|', $un_price);
            $unit_price = $un_res[1];
        } else if ($form_vales['cost_type'] == 2) {


            if ($product != 'all') {
                $str_sql = "SELECT
            AVG(stock_batch.unit_price) as unit_price
            FROM
            stock_batch
            INNER JOIN stock_batch_warehouses ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            WHERE
            stock_batch_warehouses.warehouse_id = '159' AND
            stakeholder_item_pack_sizes.item_pack_size_id = '$product'
            AND  unit_price > 0";


                $rec = $this->_em_read->getConnection()->prepare($str_sql);
                $rec->execute();
                $result = $rec->fetchAll();
                $unit_price = $result[0]['unit_price'] / $usd;
            }
        } else {
            $unit_price = $form_vales['manual_price'];
        }


        if ($province == 'all') {
            $wh_province = "";
        } else {
            $wh_province = "AND wastages_cost_summary.province_id = $province";
        }
        if ($year == 'all') {
            $wh_was = "";
        } else {


            if (!empty($month)) {
                if ($month < 10) {
                    $month = '0' . $month;
                }

                $wh_was = "And Date_format(wastages_cost_summary.reporting_date,'%Y-%m') = '$year-$month'";
            } else {
                $wh_was = "And Date_format(wastages_cost_summary.reporting_date,'%Y') = '$year'";
            }
        }


        if ($province == 'all' && $year != 'all') {
            $str_sql2 = "SELECT
	wastages_cost_summary.pk_id,
	wastages_cost_summary.province_id,
	wastages_cost_summary.item_name,
	wastages_cost_summary.province_name,
	SUM(wastages_cost_summary.consumption) consumption,
	SUM(wastages_cost_summary.wastages) wastages,
	wastages_cost_summary.wastages_rage_allowed,
	SUM(wastages_cost_summary.permissible_wastage) permissible_wastage,
	SUM(wastages_cost_summary.over_wastage) over_wastage,
	SUM(wastages_cost_summary.sdps_within_range_wastage) sdps_within_range_wastage,
	SUM(wastages_cost_summary.sdps_over_wastage) sdps_over_wastage,
	wastages_cost_summary.item_pack_size_id,
	wastages_cost_summary.reporting_date,
	wastages_cost_summary.created_by,
	wastages_cost_summary.created_date,
	wastages_cost_summary.modified_by,
	wastages_cost_summary.modified_date
        FROM
                wastages_cost_summary
        WHERE
                wastages_cost_summary.item_pack_size_id = $product
        $wh_was
        GROUP BY item_name";
        } else if ($year == 'all' && $province != 'all') {
            $str_sql2 = "SELECT
	wastages_cost_summary.pk_id,
	wastages_cost_summary.province_id,
	wastages_cost_summary.item_name,
	wastages_cost_summary.province_name,
	SUM(wastages_cost_summary.consumption) consumption,
	SUM(wastages_cost_summary.wastages) wastages,
	wastages_cost_summary.wastages_rage_allowed,
	SUM(wastages_cost_summary.permissible_wastage) permissible_wastage,
	SUM(wastages_cost_summary.over_wastage) over_wastage,
	SUM(wastages_cost_summary.sdps_within_range_wastage) sdps_within_range_wastage,
	SUM(wastages_cost_summary.sdps_over_wastage) sdps_over_wastage,
	wastages_cost_summary.item_pack_size_id,
	wastages_cost_summary.reporting_date,
	wastages_cost_summary.created_by,
	wastages_cost_summary.created_date,
	wastages_cost_summary.modified_by,
	wastages_cost_summary.modified_date
        FROM
                wastages_cost_summary
        WHERE
                wastages_cost_summary.item_pack_size_id = $product
        $wh_province
        $wh_was
        GROUP BY item_name";
        } else if ($province == 'all' && $year == 'all') {
            $str_sql2 = "SELECT
	wastages_cost_summary.pk_id,
	wastages_cost_summary.province_id,
	wastages_cost_summary.item_name,
	wastages_cost_summary.province_name,
	SUM(wastages_cost_summary.consumption) consumption,
	SUM(wastages_cost_summary.wastages) wastages,
	wastages_cost_summary.wastages_rage_allowed,
	SUM(wastages_cost_summary.permissible_wastage) permissible_wastage,
	SUM(wastages_cost_summary.over_wastage) over_wastage,
	SUM(wastages_cost_summary.sdps_within_range_wastage) sdps_within_range_wastage,
	SUM(wastages_cost_summary.sdps_over_wastage) sdps_over_wastage,
	wastages_cost_summary.item_pack_size_id,
	wastages_cost_summary.reporting_date,
	wastages_cost_summary.created_by,
	wastages_cost_summary.created_date,
	wastages_cost_summary.modified_by,
	wastages_cost_summary.modified_date
        FROM
                wastages_cost_summary
        WHERE
                wastages_cost_summary.item_pack_size_id = $product
        $wh_was
        GROUP BY item_name";
        } else {
            $str_sql2 = "SELECT
            wastages_cost_summary.pk_id,
            wastages_cost_summary.province_id,
            wastages_cost_summary.item_name,
            wastages_cost_summary.province_name,
            SUM(wastages_cost_summary.consumption) consumption,
            SUM(wastages_cost_summary.wastages) wastages,
            wastages_cost_summary.wastages_rage_allowed,
            SUM(wastages_cost_summary.permissible_wastage) permissible_wastage,
            SUM(wastages_cost_summary.over_wastage) over_wastage,
            wastages_cost_summary.sdps_within_range_wastage,
            wastages_cost_summary.sdps_over_wastage,
            wastages_cost_summary.item_pack_size_id,
            wastages_cost_summary.reporting_date,
            wastages_cost_summary.created_by,
            wastages_cost_summary.created_date,
            wastages_cost_summary.modified_by,
            wastages_cost_summary.modified_date
            FROM
            wastages_cost_summary
            where wastages_cost_summary.item_pack_size_id = $product"
                    . " $wh_province"
                    . " $wh_was ";
        }


        $rec1 = $this->_em_read->getConnection()->prepare($str_sql2);
        $rec1->execute();
        $result2 = $rec1->fetchAll();
        $wastages_allowed = $result2[0]['wastages_rage_allowed'];
        $item_name = $result2[0]['item_name'];
        $location_name = $result2[0]['province_name'];
        $wastages = $result2[0]['wastages'];
        $consumption = $result2[0]['consumption'];
        $was_per = $result2[0]['permissible_wastage'];
        $waste_not_per = $result2[0]['over_wastage'];
        if ($dose_cost == 2) {
            $total = Round(($wastages + $consumption));
            $u_per = Round(($wastages / $total) * 100);
            $c_per = Round(($consumption / $total) * 100);


            $w_uprice = $wastages;
            $c_uprice = $consumption;
        } else {
            $total_con = ROUND(($wastages * $unit_price) + ($consumption * $unit_price));
            $u_per = Round((($wastages * $unit_price) / $total_con) * 100);
            $c_per = Round((($consumption * $unit_price) / $total_con) * 100);

            $w_uprice = $wastages * $unit_price;
            $c_uprice = $consumption * $unit_price;
        }
        $total = $consumption + $wastages;
        $wastageP = Round(($wastages / $total) * 100);

        if ($wastageP <= $wastages_allowed) {
            $was_percentage = $wastageP;
        } else {
            $was_percentage = $wastages_allowed;
        }


        $waste_not_pert = $wastages_allowed;
        if ($wastageP == $waste_not_pert) {
            $waste_not_percentage = '';
        } else if ($wastageP < $waste_not_pert) {
            $waste_not_percentage = '';
        } else {
            $waste_not_percentage = $wastageP - $wastages_allowed;
        }

        if ($dose_cost == 2) {
            $wastage_allowed = $was_per;
            $wastage_not_allowed = $waste_not_per;
        } else {
            $wastage_allowed = Round(($unit_price * $was_per), 2);
            $wastage_not_allowed = Round(($unit_price * $waste_not_per), 2);
        }
        $u_price = Round($unit_price, 2);
        $t1 = Round($was_per * $u_price);
        $t2 = Round($waste_not_per * $u_price);
        $total_cost = Round((($t1 + $t2) / 1000000), 2) . 'M';
        //      $total_cost = $t1;
        if ($dose_cost == 2) {
            $d_c = "Doses";
        } else {
            $d_c = "Cost";
        }
        if ($province == 'all') {
            $location_name = 'Pakistan';
        } else {
            $location_name = $location_name;
        }

        if ($year == 'all') {
            $year = 'All Years';
        } else {
            $year = $year;
        }
        if (!empty($month)) {
            $m_d = date('M', mktime(0, 0, 0, $month));
            $title1 = "$item_name - Consumption & Wastage - ($d_c) ($location_name $m_d-$year)";
            //     $title = "$item_name - Wastage Cost ($location_name $m_d-$year) - Total Cost:$total_cost Unit Price: $u_price";
            $title = "$item_name - Wastage Breakup - ($d_c) ($location_name $m_d-$year) - Total Cost:$total_cost";
        } else {
            $title1 = "$item_name - Consumption & Wastage - ($d_c) ($location_name-$year) ";
            //      $title = "$item_name - Wastage Cost ($location_name $year) - Total Cost:$total_cost Unit Price: $u_price";
            $title = "$item_name - Wastage Breakup - ($d_c) ($location_name-$year) - Total Cost:$total_cost ";
        }
        $xmlstore = "<chart exportEnabled='1' howvalues='1'  showborder='0'  showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";




        $xmlstore .= "<set label='Permissible($wastages_allowed%)'   value='$wastage_allowed' color='#006600' />";
        $xmlstore .= "<set label='Over Wastage'   value='$wastage_not_allowed' color='#ff0000' />";





        $xmlstore .= "</chart>";

        $xmlstore1 = "<chart exportEnabled='1' howvalues='1'  showborder='0'  showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title1 ' exportFileName='" . $title1 . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";




        $xmlstore1 .= "<set label='Consumed($c_per%)'   value='$c_uprice' color='#21618c' />";
        $xmlstore1 .= "<set label='Wastages($u_per%)'   value='$w_uprice' color='#d35400' />";





        $xmlstore1 .= "</chart>";


        if (!empty($month)) {

            $m_d = date('M', mktime(0, 0, 0, $month));

            $title = $item_name . '- SDPs Count';
            $sub_title = "($location_name $m_d-$year)";
        } else {
            $title = $item_name . '- SDPs Count';
            $sub_title = '(' . $location_name . '-' . $year . ')';
        }

        if ($province == 'all' && $year != 'All Years') {
            $str_sql2 = "SELECT
            wastages_province_sdps_summary.province_id,
            wastages_province_sdps_summary.reporting_date,
            SUM(wastages_province_sdps_summary.within_range) within_range,
            SUM(wastages_province_sdps_summary.over_wastage) over_wastage
            FROM
            wastages_province_sdps_summary
            WHERE
            wastages_province_sdps_summary.item_pack_size_id = $product
            and year(wastages_province_sdps_summary.reporting_date) = '$year'";
            $rec1 = $this->_em_read->getConnection()->prepare($str_sql2);
            $rec1->execute();
            $result2 = $rec1->fetchAll();
            $over = $result2[0]['over_wastage'];
            $withn_range = $result2[0]['within_range'];
        } else if ($year == 'All Years' && $province != 'all') {
            $str_sql2 = "SELECT
            wastages_province_sdps_summary.province_id,
            wastages_province_sdps_summary.reporting_date,
            SUM(wastages_province_sdps_summary.within_range) within_range,
            SUM(wastages_province_sdps_summary.over_wastage) over_wastage
            FROM
            wastages_province_sdps_summary
            WHERE
            wastages_province_sdps_summary.item_pack_size_id = $product
            and wastages_province_sdps_summary.province_id = '$province'";
            $rec1 = $this->_em_read->getConnection()->prepare($str_sql2);
            $rec1->execute();
            $result2 = $rec1->fetchAll();
            $over = $result2[0]['over_wastage'];
            $withn_range = $result2[0]['within_range'];
        } else if ($year == 'All Years' && $province == 'all') {
            $curr_year = date("Y");
            $str_sql2 = "SELECT
            wastages_province_sdps_summary.province_id,
            wastages_province_sdps_summary.reporting_date,
            SUM(wastages_province_sdps_summary.within_range) within_range,
            SUM(wastages_province_sdps_summary.over_wastage) over_wastage
            FROM
            wastages_province_sdps_summary
            WHERE
            wastages_province_sdps_summary.item_pack_size_id = $product
            and year(wastages_province_sdps_summary.reporting_date) = '$curr_year'";
            $rec1 = $this->_em_read->getConnection()->prepare($str_sql2);
            $rec1->execute();
            $result2 = $rec1->fetchAll();
            $over = $result2[0]['over_wastage'];
            $withn_range = $result2[0]['within_range'];
        } else if ($month == '') {

            $str_sql2 = "SELECT
            wastages_province_sdps_summary.province_id,
            wastages_province_sdps_summary.reporting_date,
            SUM(wastages_province_sdps_summary.within_range) within_range,
            SUM(wastages_province_sdps_summary.over_wastage) over_wastage
            FROM
            wastages_province_sdps_summary
            WHERE
            wastages_province_sdps_summary.item_pack_size_id = $product
            and   wastages_province_sdps_summary.province_id = $province
            and year(wastages_province_sdps_summary.reporting_date) = '$year'";
            $rec1 = $this->_em_read->getConnection()->prepare($str_sql2);
            $rec1->execute();
            $result2 = $rec1->fetchAll();
            $over = $result2[0]['over_wastage'];
            $withn_range = $result2[0]['within_range'];
        } else {
            $over = $result2[0]['sdps_over_wastage'];
            $withn_range = $result2[0]['sdps_within_range_wastage'];
        }
        $total_fac = $over + $withn_range;
        $param = base64_encode($province . '-' . $year . '-' . $month . '-' . $product . '-1');

        $param1 = base64_encode($province . '-' . $year . '-' . $month . '-' . $product . '-0');

        $url = "javascript:functionCall('$param')";
        $url1 = "javascript:functionCall('$param1')";
        $xmlstore3 = "<chart exportEnabled='1'  howvalues='1'  showborder='0' showLabels='0' showplotborder='0' showlegend='1' defaultcenterlabel='Total: $total_fac'  legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' subCaption= '$sub_title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";
        //$xmlstore = "<chart caption='Split of Revenue by Product Categories' subcaption='Los Angeles Topanga - Last month' numberprefix='$' startingangle='310' decimals='0' defaultcenterlabel='Total revenue: $60K' centerlabel='Revenue from $label: $value' theme='fint'>";
        $xmlstore3 .= "<set link=\"$url\"  label='Wastage within range' value='$withn_range' color='#0075C2' />";
        $xmlstore3 .= "<set link=\"$url1\"  label='Over Wastage'   value='$over' color='#CDA4A5' />";





        $xmlstore3 .= "</chart>";



        $res = array("0" => "$xmlstore1", "1" => "$xmlstore", "2" => "$consumption", '3' => $wastages, '4' => $unit_price, '5' => $was_per, '6' => $waste_not_per, '7' => $was_percentage, '8' => $waste_not_percentage, '9' => $wastages_allowed, '10' => $xmlstore3);
        return $res;
    }

  

    public function getFundingSourceUnitPrice() {
        $province_id = $this->form_values['province'];
        $item = $this->form_values['product'];
        $currency = $this->form_values['currency'];
        $usd = $this->form_values['usd'];
        if ($currency == 1) {
            $usd_1 = 1;
        } else {
            $usd_1 = $usd;
        }

        $str_sql_w1 = "SELECT
        warehouses.pk_id,
        warehouses.warehouse_name,
        locations.location_name
        FROM
        warehouses
        INNER JOIN locations ON warehouses.province_id = locations.pk_id
        WHERE
        warehouses.stakeholder_office_id = 2 AND
        warehouses.province_id = '$province_id'
        LIMIT 1";

        $rec11 = $this->_em_read->getConnection()->prepare($str_sql_w1);
        $rec11->execute();
        $resultp = $rec11->fetchAll();
        $warehouse_id = $resultp[0]['pk_id'];
        $qry = "SELECT
            warehouses.pk_id,
            ROUND(AVG(stock_batch.unit_price)/$usd_1,2) as unit_price,
            warehouses.warehouse_name
            FROM
            stock_master
            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            WHERE
            stakeholders.stakeholder_type_id = 2 AND
            stock_batch_warehouses.warehouse_id = '159'
            AND stakeholder_item_pack_sizes.item_pack_size_id = '$item'
            and unit_price > 0
            GROUP BY warehouse_name";

        $rec1 = $this->_em_read->getConnection()->prepare($qry);
        $rec1->execute();
        $result2 = $rec1->fetchAll();
        return $result2;
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function facilities_count() {


        $form_vales = $this->form_values;
        $allowed = $form_vales['allowed'];
        $year = $form_vales['year'];
        $month = $form_vales['month'];
        $product = $form_vales['product'];
        $province = $form_vales['province'];

        if (!empty($month)) {
            if ($month < 10) {
                $month = '0' . $month;
            }

            $wh_was = "AND Date_format(hf_data_master.reporting_start_date,'%Y-%m') = '$year-$month'";
        } else {
            $wh_was = "AND YEAR(hf_data_master.reporting_start_date) = '$year'";
        }
        $qry = "SELECT
	*
    FROM
            (
                    SELECT
			locations.location_name,
      SUM(case when COALESCE (A.wastagePer, NULL, 0) >= $allowed then 1 else 0 end) as over,
      SUM(case when COALESCE (A.wastagePer, NULL, 0) < $allowed then 1 else 0 end) as withn_range,
			COALESCE (A.wastagePer, NULL, 0) AS wastagePer
		FROM
			(
				SELECT
					ROUND(
						IFNULL(
							(
								sum(hf_data_master.wastages) / (
									sum(
										hf_data_master.issue_balance
									) + sum(hf_data_master.wastages)
								)
							) * 100,
							0
						),
						1
					) AS wastagePer,
					warehouses.location_id
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				WHERE
					warehouses.stakeholder_id = 1
				$wh_was
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = '$product'
				AND stakeholders.pk_id = 6
				AND warehouses. STATUS = 1
				GROUP BY
					warehouses.location_id
			) A
		RIGHT JOIN locations ON locations.pk_id = A.location_id
		WHERE
			locations.geo_level_id = 6
		AND locations.province_id = '$province'
	) AS A
        ORDER BY
	wastagePer DESC";



        $rec1 = $this->_em_read->getConnection()->prepare($qry);
        $rec1->execute();
        $result2 = $rec1->fetchAll();
        $over = $result2[0]['over'];
        $withn_range = $result2[0]['withn_range'];
        $str_sql_w1 = "SELECT
        warehouses.pk_id,
        warehouses.warehouse_name,

        locations.location_name
        FROM
        warehouses
        INNER JOIN locations ON warehouses.province_id = locations.pk_id
        WHERE
        warehouses.stakeholder_office_id = 2 AND
        warehouses.province_id = '$province'
        LIMIT 1";
        $rec11 = $this->_em_read->getConnection()->prepare($str_sql_w1);
        $rec11->execute();
        $resultp = $rec11->fetchAll();


        $location_name = $resultp[0]['location_name'];

        $str_sql_w_item = "SELECT
            item_pack_sizes.item_name
            FROM
            item_pack_sizes
            WHERE
            item_pack_sizes.pk_id = $product";
        $rec112 = $this->_em_read->getConnection()->prepare($str_sql_w_item);
        $rec112->execute();
        $resulti = $rec112->fetchAll();


        $item_name = $resulti[0]['item_name'];
        if (!empty($month)) {

            $m_d = date('M', mktime(0, 0, 0, $month));

            $title = $item_name . '- SDPs Count';
            $sub_title = "($location_name $m_d-$year)";
        } else {
            $title = $item_name . '- SDPs Count';
            $sub_title = '(' . $location_name . ' ' . $year . ')';
        }
        $total_fac = $over + $withn_range;

        $xmlstore = "<chart exportEnabled='1'  howvalues='1'  showborder='0' showLabels='0' showplotborder='0' showlegend='1' defaultcenterlabel='Total: $total_fac'  legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title ' subCaption= '$sub_title ' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";
//$xmlstore = "<chart caption='Split of Revenue by Product Categories' subcaption='Los Angeles Topanga - Last month' numberprefix='$' startingangle='310' decimals='0' defaultcenterlabel='Total revenue: $60K' centerlabel='Revenue from $label: $value' theme='fint'>";
        $xmlstore .= "<set label='Wastage within range' value='$withn_range' color='#0075C2' />";
        $xmlstore .= "<set label='Over Wastage'   value='$over' color='#CDA4A5' />";





        $xmlstore .= "</chart>";




        return $xmlstore;
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function facilities_count_all() {


        $form_vales = $this->form_values;
        $allowed = $form_vales['allowed'];
        $year = $form_vales['year'];
        $month = $form_vales['month'];
        $product = $form_vales['product'];
        $province = $form_vales['province'];

        if (!empty($month)) {
            if ($month < 10) {
                $month = '0' . $month;
            }

            $wh_was = "AND Date_format(hf_data_master.reporting_start_date,'%Y-%m') = '$year-$month'";
        } else {
            $wh_was = "AND YEAR(hf_data_master.reporting_start_date) = '$year'";
        }

        $str_sql = "SELECT
	item_pack_sizes.pk_id pkId,
	item_pack_sizes.item_name itemName,
        item_pack_sizes.wastage_rate_allowed
        FROM
                item_pack_sizes
        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                item_pack_sizes.`status` = 1
        AND item_pack_sizes.item_category_id = 1
        AND item_activities.stakeholder_activity_id = 1
        ORDER BY
            item_pack_sizes.list_rank ASC";

        $sql = $this->_em_read->getConnection()->prepare($str_sql);
        $sql->execute();
        $row = $sql->fetchAll();
        $title = 'SDPs Count';
        $xmlstore = "<chart labelDisplay='rotate' exportEnabled='1' exportAction='Download' bgColor='white' caption='$title'
            exportFileName='Wastage " . date('Y-m-d H:i:s') . "' yAxisName='count' showValues='1' theme='fint'>";
        $xmlstore .= '<categories>';
        foreach ($row as $res) {
            $item_id = $res['pkId'];
            $itemName = $res['itemName'];

            $xmlstore .= "<category label='$itemName' />";
        }
        $xmlstore .= '</categories>';
        $xmlstore .= "<dataset seriesname='Wastage Within range' color='#006600'>";
        foreach ($row as $res) {
            $item_id = $res['pkId'];
            $itemName = $res['itemName'];

            $xmlstore .= "<category label='$itemName' />";

            $wastage_rate_allowed = $res['wastage_rate_allowed'];
            $qry = "SELECT
	*
      FROM
            (
                    SELECT
			locations.location_name,
      SUM(case when COALESCE (A.wastagePer, NULL, 0) >= $wastage_rate_allowed then 1 else 0 end) as over,
      SUM(case when COALESCE (A.wastagePer, NULL, 0) < $wastage_rate_allowed then 1 else 0 end) as withn_range,
			COALESCE (A.wastagePer, NULL, 0) AS wastagePer
		FROM
			(
				SELECT
					ROUND(
						IFNULL(
							(
								sum(hf_data_master.wastages) / (
									sum(
										hf_data_master.issue_balance
									) + sum(hf_data_master.wastages)
								)
							) * 100,
							0
						),
						1
					) AS wastagePer,
					warehouses.location_id
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				WHERE
					warehouses.stakeholder_id = 1
				$wh_was
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = '$item_id'
				AND stakeholders.pk_id = 6
				AND warehouses. STATUS = 1
				GROUP BY
					warehouses.location_id
			) A
		RIGHT JOIN locations ON locations.pk_id = A.location_id
		WHERE
			locations.geo_level_id = 6
		AND locations.province_id = '$province'
	) AS A
       ORDER BY
	wastagePer DESC";


            $rec1 = $this->_em_read->getConnection()->prepare($qry);
            $rec1->execute();
            $result2 = $rec1->fetchAll();



            $withn_range = $result2['0']['withn_range'];


            $xmlstore .= "<set value='$withn_range'  />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesname='Over Wastage' color='#ff0000 '>";
        foreach ($row as $res) {
            $item_id = $res['pkId'];
            $itemName = $res['itemName'];

            $xmlstore .= "<category label='$itemName' />";

            $wastage_rate_allowed = $res['wastage_rate_allowed'];
            $qry = "SELECT
                *
              FROM
                    (
                    SELECT
			locations.location_name,
                    SUM(case when COALESCE (A.wastagePer, NULL, 0) >= $wastage_rate_allowed then 1 else 0 end) as over,
                    SUM(case when COALESCE (A.wastagePer, NULL, 0) < $wastage_rate_allowed then 1 else 0 end) as withn_range,
                    COALESCE (A.wastagePer, NULL, 0) AS wastagePer
		FROM
			(
				SELECT
					ROUND(
						IFNULL(
							(
								sum(hf_data_master.wastages) / (
									sum(
										hf_data_master.issue_balance
									) + sum(hf_data_master.wastages)
								)
							) * 100,
							0
						),
						1
					) AS wastagePer,
					warehouses.location_id
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				WHERE
					warehouses.stakeholder_id = 1
				$wh_was
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = '$item_id'
				AND stakeholders.pk_id = 6
				AND warehouses. STATUS = 1
				GROUP BY
					warehouses.location_id
			) A
		RIGHT JOIN locations ON locations.pk_id = A.location_id
		WHERE
			locations.geo_level_id = 6
		AND locations.province_id = '$province'
	) AS A
       ORDER BY
	wastagePer DESC";


            $rec1 = $this->_em_read->getConnection()->prepare($qry);
            $rec1->execute();
            $result2 = $rec1->fetchAll();



            $over = $result2['0']['over'];


            $xmlstore .= "<set value='$over' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "</chart>";



        return $xmlstore;
    }

    /**
     * Cold Chain Capacity
     * @param type $type
     * @return string
     */
    public function wastagesByType() {


        $form_vales = $this->form_values;
        $allowed = $form_vales['allowed'];
        $d_c = $form_vales['dose_cost'];
        $year = $form_vales['year'];
        $month = $form_vales['month'];
        $product = $form_vales['product'];
        $province = $form_vales['province'];
        $unit_price = $form_vales['unit_price'];

        if (!empty($month)) {
            if ($month < 10) {
                $month = '0' . $month;
            }
            $date_sel = $year . '-' . $month;
            $endDate = date('Y-m-t', strtotime($date_sel));
            $wh_was = "AND 
                DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) <= '$endDate'";
        } else if ($year == 'all') {
            $date_sel = date("Y-m-d");
            // $endDate = date('Y-m-t', strtotime($date_sel));
            $wh_was = "AND 
                DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) <= '$date_sel'";
        } else {
            $date_sel = $year . '-12';
            $endDate = date('Y-m-t', strtotime($date_sel));
            $wh_was = "AND 
                DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) <= '$endDate'";
        }


        if ($province == 'all') {
            $wh_pro = '';
        } else {
            $wh_pro = "AND warehouses.province_id = $province";
        }



        $current_date = date("Y-m-d");


        $qry = "SELECT
        warehouses.stakeholder_office_id,
	warehouses.warehouse_name,
	DATE_FORMAT(
		stock_batch.expiry_date,
		'%b, %Y'
	) AS expiry_date,
	stock_batch.number,
	item_pack_sizes.item_name,
	Sum(placements.quantity) AS quantity,
	SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
	cold_chain.asset_id AS coldroom,
	placement_locations.pk_id AS coldroom_id,

        IF (
                item_pack_sizes.vvm_group_id = 1,
                IFNULL(vvm_stages.pk_id, 1),
                vvm_stages.vvm_stage_value
        ) AS vvm,
         warehouses.stakeholder_office_id
        FROM
                stock_batch_warehouses
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                item_pack_sizes.item_category_id <> 3
        AND item_activities.stakeholder_activity_id = 1
        $wh_was
        $wh_pro
        AND item_pack_sizes.pk_id = $product
        HAVING
                quantity > 0";

        $rec1 = $this->_em_read->getConnection()->prepare($qry);
        $rec1->execute();
        $result2 = $rec1->fetchAll();
        if ($d_c == 2) {
            $expired = $result2[0]['doses'];
        } else {
            $expired = $result2[0]['doses'] * $unit_price;
        }


        $qry1 = "SELECT
        warehouses.stakeholder_office_id,
	warehouses.warehouse_name,
	DATE_FORMAT(
		stock_batch.expiry_date,
		'%b, %Y'
	) AS expiry_date,
	stock_batch.number,
	item_pack_sizes.item_name,
	Sum(placements.quantity) AS quantity,
	SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
	cold_chain.asset_id AS coldroom,
	placement_locations.pk_id AS coldroom_id,

        IF (
                item_pack_sizes.vvm_group_id = 1,
                IFNULL(vvm_stages.pk_id, 1),
                vvm_stages.vvm_stage_value
        ) AS vvm,
         warehouses.stakeholder_office_id
        FROM
                stock_batch_warehouses
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                item_pack_sizes.item_category_id <> 3
        AND item_activities.stakeholder_activity_id = 1
        AND placements.vvm_stage >= 3
        $wh_pro

        AND item_pack_sizes.pk_id = $product

        HAVING
                quantity > 0";


        $rec2 = $this->_em_read->getConnection()->prepare($qry1);
        $rec2->execute();
        $result3 = $rec2->fetchAll();
        if ($d_c == 2) {
            if (count($result3) > 0) {
                $unusable = $result3[0]['doses'];
            } else {
                $unusable = 0;
            }
        } else {
            if (count($result3) > 0) {
                $unusable = $result3[0]['doses'] * $unit_price;
            } else {
                $unusable = $result3[0]['doses'] * $unit_price;
            }
        }

        $str_sql_w1 = "SELECT
        warehouses.pk_id,
        warehouses.warehouse_name,

        locations.location_name
        FROM
        warehouses
        INNER JOIN locations ON warehouses.province_id = locations.pk_id
        WHERE
        warehouses.stakeholder_office_id = 2 AND
        warehouses.province_id = '$province'
        LIMIT 1";
        $rec11 = $this->_em_read->getConnection()->prepare($str_sql_w1);
        $rec11->execute();
        $resultp = $rec11->fetchAll();


        $location_name = $resultp[0]['location_name'];

        $str_sql_w_item = "SELECT
            item_pack_sizes.item_name
            FROM
            item_pack_sizes
            WHERE
            item_pack_sizes.pk_id = $product";
        $rec112 = $this->_em_read->getConnection()->prepare($str_sql_w_item);
        $rec112->execute();
        $resulti = $rec112->fetchAll();


        $item_name = $resulti[0]['item_name'];
        if ($d_c == 2) {

            $d_c_t = "(Doses)";
        } else {

            $d_c_t = "(Cost)";
        }
        if ($province == 'all') {
            $location_name = 'Pakistan';
        } else {
            $location_name = $location_name;
        }
        if ($year == 'all') {
            $year = 'All Years';
        } else {
            $year = $year;
        }
        if (!empty($month)) {

            $m_d = date('M', mktime(0, 0, 0, $month));

            $title = $item_name . '- Expired/Unusable' . ' ' . $d_c_t;
            $sub_title = "($location_name $m_d-$year)";
        } else {
            $title = $item_name . '- Expired/Unusable' . ' ' . $d_c_t;
            $sub_title = '(' . $location_name . '-' . $year . ')';
        }
      //  $expired = Round($expired, 2);
      //  $unusable = Round($unusable, 2);

        if ($expired == 0 && $unusable == 0) {

            $xmlstore = "<chart exportEnabled='1' plotGradientColor='1' howvalues='1'  showborder='0' showLabels='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title' subCaption='$sub_title' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";



            $xmlstore .= "</chart>";
        } else {

            $xmlstore = "<chart exportEnabled='1' plotGradientColor='1' howvalues='1'  showborder='0' showLabels='0' showplotborder='0' showlegend='1' legendborder='0' legendposition='bottom'  exportAction='Download' caption= '$title' subCaption='$sub_title' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "'  theme='fint'>";

            $xmlstore .= "<set label='Expired' value='$expired' color='#94AF6D' />";
            $xmlstore .= "<set label='Unusable'   value='$unusable' color='#BFCAA8' />";

            $xmlstore .= "</chart>";
        }
        return $xmlstore;
    }

  public function getepiCentersWastages() {
        $form_vales = $this->form_values;
        $year = $form_vales['year'];

        $month = $form_vales['month'];
        $product = $form_vales['product'];
        $province = $form_vales['province'];
        $status = $form_vales['status'];

        $str_sql_wr = "SELECT
        item_pack_sizes.wastage_rate_allowed,
        item_pack_sizes.pk_id
        FROM
        item_pack_sizes
        WHERE
        item_pack_sizes.pk_id = $product
        ";
        $rec_wr = $this->_em_read->getConnection()->prepare($str_sql_wr);
        $rec_wr->execute();
        $resul_wr = $rec_wr->fetchAll();
        $wastage_rate_allowed = $resul_wr[0]['wastage_rate_allowed'];

        if ($status == 1) {
            $wh = "and   (wastages_sdps_summary.within_range = '1' OR wastages_sdps_summary.within_range IS NULL)";
            $wh_sec = "A.wastage_percentage < $wastage_rate_allowed";
        } else {
            $wh = "and   wastages_sdps_summary.over_wastage = '1'";
            $wh_sec = "A.wastage_percentage >= $wastage_rate_allowed";
        }




        if ($province != 'all') {
            $wh_province = "and wastages_sdps_summary.province_id = '$province'";
            $wh_pr = "and province_id = $province";
        } else {
            $wh_province = "";
            $wh_pr = "";
        }

        if ($year != 'All Years') {
            $wh_year = " and Date_Format(wastages_sdps_summary.reporting_date,'%Y-%m-%d') = '$year-$month-01'";
            $wh_ye = "and year(reporting_date) = '$year'";
        } else {
            $wh_year = "";
            $wh_ye = "";
        }
        if (!empty($month)) {
            $str_sql_w1 = "SELECT
        wastages_sdps_summary.pk_id,
        wastages_sdps_summary.province_id,
        wastages_sdps_summary.district_id,
        wastages_sdps_summary.warehouse_id,
        wastages_sdps_summary.warehouse_name,
        wastages_sdps_summary.consumption,
        wastages_sdps_summary.wastage,
        wastages_sdps_summary.reporting,
        wastages_sdps_summary.item_name,
        wastages_sdps_summary.reporting_date,
        wastages_sdps_summary.created_by,
        wastages_sdps_summary.created_date,
        wastages_sdps_summary.modified_by,
        wastages_sdps_summary.modified_date,
         wastages_sdps_summary.province_name,
        wastages_sdps_summary.district_name,
        wastages_sdps_summary.tehsil_name,
        wastages_sdps_summary.uc_name
        FROM
        wastages_sdps_summary
        where wastages_sdps_summary.item_pack_size_id = '$product'
        $wh
        $wh_province
        $wh_year
        ORDER BY province_name,district_name,tehsil_name,uc_name,warehouse_name";
        } else {
            if ($year == date('Y')) {
                $m_d = date('m')- 1;
            } else {
                  $m_d = 12;
            }
            $str_sql_w1 = "SELECT 
            A.province_name,
            A.district_name,
            A.tehsil_name,
            A.warehouse_name,
            A.consumption,
            A.uc_name,
            A.wastage,
            A.reporting,
            A.item_name,
            A.wastage_percentage
            from
            (SELECT

            wastages_sdps_summary.province_name,
            wastages_sdps_summary.district_name,
            wastages_sdps_summary.tehsil_name,
            wastages_sdps_summary.uc_name,
            wastages_sdps_summary.warehouse_name,
             wastages_sdps_summary.item_name,
            SUM(wastages_sdps_summary.consumption) consumption,
            SUM(wastages_sdps_summary.wastage) wastage,
            ROUND((SUM(wastages_sdps_summary.reporting)/$m_d) * 100) as reporting,
            ROUND(IFNULL((SUM(wastage)/(SUM(consumption)+SUM(wastage))*100),0)) as wastage_percentage

            FROM
            wastages_sdps_summary
            where 
            item_pack_size_id = $product
                $wh_pr
            $wh_ye

            GROUP BY warehouse_name
            ) A
            where $wh_sec
            ORDER BY province_name,district_name,tehsil_name,uc_name,warehouse_name
            ";
        }


        $rec11 = $this->_em_read->getConnection()->prepare($str_sql_w1);
        $rec11->execute();
        return $resultp = $rec11->fetchAll();
    }

}
