<?php

/**
 * Zend_View_Helper_Reports
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Reports
 */
class Zend_View_Helper_WastageReports extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Reports
     */
    public function wastageReports() {
        return $this;
    }

    /**
     * BCG Coverage Report
     * @param type $wh_type
     * @param type $report_year
     * @param type $report_month
     * @param type $from_sel_month
     * @param type $from_sel_year
     * @param type $district
     * @param type $sel_tehsil
     * @return type
     */
    public function antigenWiseCoverageReport($sel_item, $dose_no, $wh_type, $report_year, $report_month, $from_sel_month, $from_sel_year, $province, $district, $sel_tehsil, $report_type, $province_sel) {


        $report_date1 = $from_sel_year . "-" . $report_month;
        $report_date = date('Y-m', strtotime($report_date1));

        $from_report_date = $from_sel_year . "-" . $from_sel_month;
        $from_report_date = date('Y-m', strtotime($from_report_date));
        $diff = (($report_month - $from_sel_month) + 1);
        if ($sel_item != 12) {
            $sel_i = "and age_group_id = '160'";
        }
        if ($sel_item == 12) {
            $t_p = '3.57';
        } else {
            $t_p = '3.5';
        }
        if ($wh_type == 2 && $sel_item == 12 && $dose_no == 2) {
            $dose_sel = "and dose_no IN (2,3,4,5)";
        } else if ($wh_type == 4 && $sel_item == 12 && $dose_no == 21) {
            $dose_sel = "and dose_no IN (2,3,4,5)";
        } else {
            $dose_sel = "and dose_no = '$dose_no'";
        }




        $str_qry = "SELECT 
        A.consumption,
	A.target,
	B.location_name location_name,
	B.pk_id location_id,
	A.wastage_rate_allowed
        from
       (SELECT
	wastage_report.consumption,
	wastage_report.wastage target,
	wastage_report.district_name location_name,
	wastage_report.district_id location_id,
	wastage_report.wastage_rate_allowed
        FROM
	wastage_report
        WHERE
	DATE_FORMAT(
		wastage_report.reporting_date,
		'%Y-%m'
	) BETWEEN '$from_report_date'
        AND '$report_date'
        AND wastage_report.item_id = $sel_item
        AND wastage_report.province_id = $province_sel
        GROUP BY
        wastage_report.district_name ) A
        RIGHT JOIN (
        SELECT locations.pk_id,
        locations.location_name,
        locations.province_id
        from locations
        where locations.geo_level_id = 4
        and locations.province_id = $province_sel
        and locations.pk_id <> 5019    

        ) B ON A.location_id = B.pk_id";


        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function wTotal($sel_item, $dose_no, $wh_type, $report_year, $report_month, $from_sel_month, $from_sel_year, $province, $district, $sel_tehsil, $report_type, $province_sel) {


        $report_date1 = $from_sel_year . "-" . $report_month;
        $report_date = date('Y-m', strtotime($report_date1));

        $from_report_date = $from_sel_year . "-" . $from_sel_month;
        $from_report_date = date('Y-m', strtotime($from_report_date));
        $diff = (($report_month - $from_sel_month) + 1);
        if ($sel_item != 12) {
            $sel_i = "and age_group_id = '160'";
        }
        if ($sel_item == 12) {
            $t_p = '3.57';
        } else {
            $t_p = '3.5';
        }
        if ($wh_type == 2 && $sel_item == 12 && $dose_no == 2) {
            $dose_sel = "and dose_no IN (2,3,4,5)";
        } else if ($wh_type == 4 && $sel_item == 12 && $dose_no == 21) {
            $dose_sel = "and dose_no IN (2,3,4,5)";
        } else {
            $dose_sel = "and dose_no = '$dose_no'";
        }

        $str_qry = "SELECT
			SUM(wastage_report.consumption) consumption,
			SUM(wastage_report.wastage) target,
		       
			wastage_report.wastage_rate_allowed,
                        wastage_report.item_id location_id
		FROM
			wastage_report
		WHERE
			DATE_FORMAT(
				wastage_report.reporting_date,
				'%Y-%m'
			) BETWEEN '$from_report_date'
		AND '$report_date'
		AND wastage_report.item_id = $sel_item
		AND wastage_report.province_id = $province_sel
		GROUP BY
			wastage_report.province_id";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function dropoutReport($sel_item, $dose_no, $wh_type, $report_year, $report_month, $to_date, $from_date, $province, $district, $sel_tehsil, $report_type, $province_sel) {



        $date = explode(" ", $to_date);

        list($dd, $mm, $yy ) = explode("/", $date[0]);
        $to_date = $yy . "-" . $mm;


        $date1 = explode(" ", $from_date);
        list($dd1, $mm1, $yy1 ) = explode("/", $date1[0]);
        $from_date = $yy1 . "-" . $mm1;


        $diff = (($mm - $mm1) + 1);
        if ($sel_item == 43) {
            $dose_sel1 = "and vaccine_schedule_id = '$dose_no'";
        } else if ($sel_item == 7) {
            $dose_sel1 = "and vaccine_schedule_id = '$dose_no'";
        } else if ($sel_item == 8) {
            $dose_sel1 = "and vaccine_schedule_id = '$dose_no'";
        } else if ($sel_item == 9) {
            $dose_sel1 = "and vaccine_schedule_id = '$dose_no'";
        } else if ($sel_item == 12) {
            $dose_sel1 = "and vaccine_schedule_id = '$dose_no'";
        } else {
            $dose_sel1 = "";
        }






        $str_qry1 = "SELECT 
B.District,
B.Tehsil,
B.UC,
B.pk_id location_id,
IFNULL(A.con,0) consumption,
B.target
from
(SELECT
locations.location_name,
locations.pk_id,
SUM(IFNULL(hf_data_detail.fixed_inside_uc_male,0) +
IFNULL(hf_data_detail.fixed_inside_uc_female,0) +
IFNULL(hf_data_detail.fixed_outside_uc_male,0) +
IFNULL(hf_data_detail.fixed_outside_uc_female,0) +
IFNULL(hf_data_detail.referal_female,0) +
IFNULL(hf_data_detail.referal_male,0) +
IFNULL(hf_data_detail.pregnant_women,0) +
IFNULL(hf_data_detail.outreach_female,0) +
IFNULL(hf_data_detail.outreach_male,0) +
IFNULL(hf_data_detail.non_pregnant_women,0)  ) as con

FROM
hf_data_master
INNER JOIN hf_data_detail ON hf_data_detail.hf_data_master_id = hf_data_master.pk_id
INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
INNER JOIN locations ON warehouses.location_id = locations.pk_id
WHERE
hf_data_master.item_pack_size_id = $sel_item
    $dose_sel1 
AND
Date_format(hf_data_master.reporting_start_date,'%Y-%m') BETWEEN '$to_date' AND  '$from_date' AND
locations.district_id = $district
GROUP BY locations.location_name) A
RIGHT JOIN
(SELECT 
A.District,
A.Tehsil,
A.UC,
A.pk_id,
IFNULL(B.target,0) target
from
(
SELECT
dis.location_name AS District,
teh.location_name AS Tehsil,
locations.location_name AS UC,
locations.pk_id
FROM
locations
INNER JOIN locations AS teh ON locations.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id
WHERE
locations.geo_level_id = 6 AND
locations.district_id = $district AND
dis.province_id = $province_sel
ORDER BY
		District,
		Tehsil,
		UC
) A
LEFT JOIN
(
SELECT
ROUND(
						COALESCE (
							ROUND(
								(
									(
										(
											(
												SUM(location_populations.population) * 1
											) / 100 * 3.5
										)
									) * $diff
								)
							) / 12,
							NULL,
							0
						)
					) AS target,
location_populations.location_id as pk_id
FROM
location_populations
INNER JOIN locations ON location_populations.location_id = locations.pk_id
WHERE
YEAR(location_populations.estimation_date) = '$yy1' AND
locations.district_id = $district AND
locations.geo_level_id = 6
GROUP BY
	location_populations.location_id
) B ON A.pk_id = B.pk_id) B ON A.pk_id = B.pk_id
ORDER BY B.District,B.Tehsil,B.UC
";


        $row = $this->_em_read->getConnection()->prepare($str_qry1);
        $row->execute();
        return $row->fetchAll();
    }

}

?>