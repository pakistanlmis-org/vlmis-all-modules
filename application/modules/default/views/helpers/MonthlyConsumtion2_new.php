<?php

/**
 * Zend_View_Helper_MonthlyConsumtion2
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Monthly Consumtion 2
 */
class Zend_View_Helper_MonthlyConsumtion2 extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Monthly Consumtion 2
     * @return \Zend_View_Helper_MonthlyConsumtion2
     */
    public function monthlyConsumtion2() {
        return $this;
    }

    /**
     * Monthly Consumtion 2 Vaccines
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @param type $age_group_id
     * @return type
     */
    public function monthlyConsumtion2Vaccines($wh_id, $prev_month_date, $pk_id, $age_group_id) {

        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) > 0) {
            $querypro = " SELECT
                        w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate ,
                        w0_.created_date AS createdDate,
                        hf_data_detail_draft.pk_id,
                        hf_data_detail_draft.fixed_inside_uc_male,
                        hf_data_detail_draft.fixed_inside_uc_female,
                        hf_data_detail_draft.fixed_outside_uc_male,
                        hf_data_detail_draft.fixed_outside_uc_female,
                        hf_data_detail_draft.outreach_male,
                        hf_data_detail_draft.outreach_female,
                        IFNULL(hf_data_detail_draft.outreach_outside_male,0) as outreach_outside_male,
                        IFNULL(hf_data_detail_draft.outreach_outside_female,0) as outreach_outside_female,
                        hf_data_detail_draft.age_group_id,
                        hf_data_detail_draft.vaccine_schedule_id

                       FROM
                        hf_data_master_draft w0_
                        INNER JOIN hf_data_detail_draft ON w0_.pk_id = hf_data_detail_draft.hf_data_master_id

                       WHERE
                               w0_.warehouse_id = $wh_id
                                   AND hf_data_detail_draft.age_group_id = '$age_group_id'
           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = "SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.created_date AS createdDate,
                        hf_data_detail.pk_id,
                        hf_data_detail.fixed_inside_uc_male,
                        hf_data_detail.fixed_inside_uc_female,
                        hf_data_detail.fixed_outside_uc_male,
                        hf_data_detail.fixed_outside_uc_female,
                        hf_data_detail.mobile_inside_male,
                        hf_data_detail.mobile_inside_female,
                        hf_data_detail.lhw_inside_male,
                        hf_data_detail.lhw_inside_female,
                        hf_data_detail.outreach_male,
                        hf_data_detail.outreach_female,
                        IFNULL(hf_data_detail.outreach_outside_male,0) as outreach_outside_male,
                        IFNULL(hf_data_detail.outreach_outside_female,0) as outreach_outside_female,
                        hf_data_detail.age_group_id,
                        hf_data_detail.vaccine_schedule_id
                        FROM
                        hf_data_master AS w0_
                        INNER JOIN hf_data_detail ON w0_.pk_id = hf_data_detail.hf_data_master_id
                        WHERE
                        w0_.warehouse_id = '$wh_id'
                        AND hf_data_detail.age_group_id = '$age_group_id'
                        AND DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
                        AND w0_.item_pack_size_id = '$pk_id' ";
        }

        //  echo $querypro . "<br>";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Monthly Consumtion 2 Vaccines Tt
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @return type
     */
    public function monthlyConsumtion2VaccinesTt($wh_id, $prev_month_date, $pk_id) {

        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) > 0) {
            $querypro = " SELECT
                        w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        hf_data_detail_draft.pk_id,
                        hf_data_detail_draft.pregnant_women,
                        hf_data_detail_draft.non_pregnant_women,
                          hf_data_detail_draft.outside_pregnant_women,
                        hf_data_detail_draft.outside_non_pregnant_women,
                        hf_data_detail_draft.age_group_id,
                        hf_data_detail_draft.vaccine_schedule_id

                       FROM
                               hf_data_master_draft w0_
                        INNER JOIN hf_data_detail_draft ON w0_.pk_id = hf_data_detail_draft.hf_data_master_id
                       WHERE
                               w0_.warehouse_id = $wh_id
           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.created_date AS createdDate,
                        hf_data_detail.pk_id,
                        hf_data_detail.pregnant_women,
                        hf_data_detail.non_pregnant_women,
                        hf_data_detail.outside_pregnant_women,
                        hf_data_detail.outside_non_pregnant_women,
                        hf_data_detail.age_group_id,
                        hf_data_detail.vaccine_schedule_id
                        FROM
                        hf_data_master AS w0_
                        INNER JOIN hf_data_detail ON w0_.pk_id = hf_data_detail.hf_data_master_id
                        WHERE
                               w0_.warehouse_id = '$wh_id'

           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = '$pk_id' ";
        }


        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Monthly Consumption Non Vaccinces
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @return type
     */
    public function monthlyConsumptionNonVaccinces($wh_id, $prev_month_date, $pk_id) {

        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) > 0) {
            $querypro = "SELECT
                        w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate
                    FROM
                            hf_data_master_draft w0_
                     WHERE
                               w0_.warehouse_id = $wh_id
           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.created_date AS createdDate
                        FROM
                        hf_data_master AS w0_

                        WHERE
                            w0_.warehouse_id = '$wh_id'

           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = '$pk_id' ";
        }

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();

        return $result[0];
    }

    /**
     * Monthly Consumtion 2 Targets
     * @param type $wh_id
     * @param type $prev_month_date
     * @return type
     */
    public function monthlyConsumtion2Targets($wh_id, $prev_month_date) {

        $pov = explode('-', $prev_month_date);

        $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.facility_total_pouplation,
                        w0_.live_births_per_year as children_live_birth,
                        w0_.surviving_children_0_11 as surviving_children_0_11,
                        w0_.children_aged_12_23 as children_aged_12_23,
                        w0_.pregnant_women_per_year as pregnant_women,
                        w0_.women_of_child_bearing_age as cbas,
                        w0_.above_2_year

                        FROM
                        warehouse_population AS w0_

                        WHERE
                        w0_.warehouse_id = '$wh_id'
                        AND
                       DATE_FORMAT(w0_.estimation_year,'%Y') = '$pov[0]' LIMIT 1 ";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();

        return $result[0];
    }

    /**
     * Monthly Consumtion 2 Hf Sessions
     * @param type $wh_id
     * @param type $prev_month_date
     * @return type
     */
    public function monthlyConsumtion2HfSessions($wh_id, $prev_month_date) {

        $querypro = "SELECT
        hf_sessions.fixed_planned_sessions,
        hf_sessions.fixed_actually_held_sessions,
        hf_sessions.outreach_planned_sessions,
        hf_sessions.outreach_actually_held_sessions
        FROM
        hf_sessions
        WHERE
        DATE_FORMAT(hf_sessions.reporting_start_date,'%Y-%m-%d') = '$prev_month_date' AND
        hf_sessions.warehouse_id = '$wh_id'";


        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();

        return $result[0];
    }

    /**
     * Log Book
     * @param type $data_id
     * @return type
     */
    public function logBook($data_id) {

        $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.doses
                      FROM
                        log_book_item_doses AS w0_

                        WHERE
                         w0_.log_book_id =$data_id ";
        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Log Book Edit
     * @param type $wh_id
     * @param type $rpt_date
     * @return boolean
     */
    public function logBookEdit($wh_id, $rpt_date) {

        $querypro = "SELECT
                    log_book.pk_id,
                    log_book.`name`,
                    log_book.father_name,
                    log_book.age,
                    log_book.contact,
                    log_book.address,
                    log_book.district_id,
                    log_book.union_council_id,
                    log_book.vaccination_date,
                    log_book.reffer_to,
                    log_book.remarks,
                    log_book.warehouse_id,
                    log_book.created_by,
                    log_book.created_date,
                    log_book.modified_date,
                    log_book.reporting_start_date
                    FROM
                    log_book
                    WHERE
                    log_book.warehouse_id='$wh_id' AND
                     DATE_FORMAT(log_book.vaccination_date,'%Y-%m')= '$rpt_date' ";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Log Book Item Doses Edit
     * @param type $log_book_data_id
     * @param type $item_id
     * @return type
     */
    public function logBookItemDosesEdit($log_book_data_id, $item_id) {

        $querypro = "SELECT
                log_book_item_doses.item_pack_size_id,
                log_book_item_doses.log_book_id,
                log_book_item_doses.doses,
                log_book_item_doses.pk_id
                FROM
                log_book_item_doses
                WHERE
                log_book_item_doses.log_book_id = '$log_book_data_id' AND
                log_book_item_doses.item_pack_size_id = '$item_id' ";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Log Book Item Ucs
     * @param type $district_id
     * @return type
     */
    public function logBookItemUcs($district_id) {

        $querypro = "SELECT
                locations.pk_id,
                locations.location_name,
                locations.geo_level_id,
                locations.parent_id,
                locations.location_type_id,
                locations.province_id,
                locations.district_id,
                locations.ccm_location_id,
                locations.sdms_name
                FROM
                locations
                WHERE
                locations.district_id = $district_id AND
                locations.geo_level_id = 6";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Location Name
     * @param type $location_id
     * @return type
     */
    public function getLocationName($location_id) {

        $querypro = "SELECT
                locations.pk_id,
                locations.location_name

                FROM
                locations
                WHERE
                locations.pk_id = '$location_id' ";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Monthly Consumtion Refferal
     * @param type $wh_id
     * @param type $date_in
     * @param type $item_id
     * @param type $gender
     * @return type
     */
    public function monthlyConsumtionRefferal($wh_id, $date_in, $item_id, $gender) {


        $querypro = "SELECT
              count(log_book.pk_id) as total
              FROM
                 log_book
              INNER JOIN log_book_item_doses ON log_book.pk_id = log_book_item_doses.log_book_id
              WHERE
              log_book_item_doses.item_pack_size_id = '$item_id' AND
              log_book.warehouse_id = '$wh_id' AND
              DATE_FORMAT(log_book.vaccination_date,'%Y-%m') = '$date_in'
              AND log_book.gender = '$gender'";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Item Name
     * @param type $item_id
     * @return type
     */
    public function getItemName($item_id) {

        $querypro = "SELECT
            item_pack_sizes.item_name
            FROM
            item_pack_sizes
            WHERE
            item_pack_sizes.pk_id = '$item_id'";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();

        return $result[0]['item_name'];
    }

    /**
     * Get Issue Voucher
     * @param type $warehouse_id
     * @param type $str_date
     * @return type
     */
    public function getIssueVoucher($warehouse_id, $str_date) {

        $pov = explode('-', $str_date);
//        if ($warehouse_id == "9369") {
//            return 1;
//        } else {
        $querypro = "SELECT DISTINCT stock_master.pk_id
            FROM
            stock_detail
            INNER JOIN stock_master ON stock_master.pk_id = stock_detail.stock_master_id
            WHERE
            stock_master.to_warehouse_id = '$warehouse_id'
            AND  DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '$pov[0]-$pov[1]'";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
        // }
    }

    /**
     * Get Stock On Hand
     * @param type $warehouse_id
     * @param type $str_date
     * @return int
     */
    public function getStockOnHand($warehouse_id, $str_date) {

        $pov = explode('-', $str_date);
        $rows = "Select warehouses.location_id from warehouses where warehouses.pk_id = '$warehouse_id'";
        $row_result = $this->_em_read->getConnection()->prepare($rows);
        $row_result->execute();
        $result_row = $row_result->fetchAll();
        $location_id = $result_row[0]['location_id'];
        $item_id = array(6, 9);
        foreach ($item_id as $val) {
            $querypro = "SELECT
        A.pk_id,
        A.location_name,
        A.MonthlyTarget,
        IFNULL(B.consumption, 0) AS closing_balance,
        ROUND(IFNULL(
                (
                        IFNULL(B.consumption, 0) / A.MonthlyTarget
                ) * 100,0)
        ) AS reportingPercentage
        FROM
        (
                SELECT
         A.pk_id,

        A.location_name,

        ROUND(
                (
                        (
                                (
                                        (A.population / 100) * B.population_percent_increase_per_year
                                ) / 100 * B.child_surviving_percent_per_year
                        ) * B.doses_per_year
                ) / 12
        ) AS MonthlyTarget
        FROM
        (
        SELECT DISTINCT
        locations.pk_id,
        locations.location_name,
        (
                SELECT
                        IFNULL(
                                location_populations.population,
                                0
                        )
                FROM
                        location_populations
                WHERE
                        location_populations.location_id = locations.pk_id
                AND DATE_FORMAT(
                        location_populations.estimation_date,
                        '%Y'
                ) = '$pov[0]' LIMIT 1
        ) AS population
        FROM
        locations

        WHERE
        locations.geo_level_id = 6
        AND locations.pk_id = '$location_id'
        ) A,
       (
        SELECT
                item_pack_sizes.pk_id,
                item_pack_sizes.item_name,
                items.population_percent_increase_per_year,
                items.child_surviving_percent_per_year,
                items.doses_per_year
        FROM
                item_pack_sizes
        INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
        WHERE
                item_pack_sizes.pk_id = '$val'
       ) B
       ) A
       LEFT JOIN (
        SELECT
        sum(
                hf_data_master.closing_balance
        ) AS consumption,
        warehouses.location_id AS pk_id
        FROM
        warehouses
        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
        WHERE
        hf_data_master.item_pack_size_id = '$val'
        AND MONTH (
        hf_data_master.reporting_start_date
        ) = '$pov[1]'
        AND YEAR (
        hf_data_master.reporting_start_date
        ) = '$pov[0]'
        AND warehouses.pk_id = hf_data_master.warehouse_id
        AND warehouses.location_id = '$location_id'
        AND stakeholders.geo_level_id = 6

        ) B ON A.pk_id = B.pk_id";

            $row = $this->_em_read->getConnection()->prepare($querypro);

            $row->execute();
            $result = $row->fetchAll();

            if ($result[0]['reportingPercentage'] > 0) {
                $total[] = 1;
            }
        }


        return $total;
    }

    /**
     * Monthly Consumtion 2 Vaccines
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @param type $age_group_id
     * @return type
     */
    public function monthlyConsumtion2VaccinesTotal($wh_id, $prev_month_date, $pk_id, $age_group_id) {


        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) > 0) {
            $querypro = " SELECT
                        w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate ,
                        w0_.created_date AS createdDate,
                        hf_data_detail_draft.pk_id,
                        SUM(hf_data_detail_draft.fixed_inside_uc_male) as fixed_inside_uc_male,
                        SUM(hf_data_detail_draft.fixed_inside_uc_female) as fixed_inside_uc_female,
                        SUM(hf_data_detail_draft.fixed_outside_uc_male) as fixed_outside_uc_male,
                        SUM(hf_data_detail_draft.fixed_outside_uc_female) as fixed_outside_uc_female,
                        SUM(hf_data_detail_draft.outreach_male) as outreach_male,
                        SUM(hf_data_detail_draft.outreach_female) as outreach_female,
                        SUM(IFNULL(hf_data_detail_draft.outreach_outside_male,0)) as outreach_outside_male,
                        SUM(IFNULL(hf_data_detail_draft.outreach_outside_female,0)) as outreach_outside_female,
                        hf_data_detail_draft.age_group_id,
                        hf_data_detail_draft.vaccine_schedule_id

                       FROM
                               hf_data_master_draft w0_

                        INNER JOIN hf_data_detail_draft ON w0_.pk_id = hf_data_detail_draft.hf_data_master_id

                       WHERE
                               w0_.warehouse_id = $wh_id
                                 AND hf_data_detail_draft.age_group_id = '$age_group_id'
           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.created_date AS createdDate,
                        hf_data_detail.pk_id,
                        SUM(hf_data_detail.fixed_inside_uc_male) as fixed_inside_uc_male,
                        SUM(hf_data_detail.fixed_inside_uc_female) as fixed_inside_uc_female,
                        SUM(hf_data_detail.fixed_outside_uc_male) as fixed_outside_uc_male,
                        SUM(hf_data_detail.fixed_outside_uc_female) as fixed_outside_uc_female,

                        SUM(hf_data_detail.outreach_male) as outreach_male,
                        SUM(hf_data_detail.outreach_female) as outreach_female,
                        SUM(IFNULL(hf_data_detail.outreach_outside_male,0)) as outreach_outside_male,
                        SUM(IFNULL(hf_data_detail.outreach_outside_female,0)) as outreach_outside_female,
                        SUM(IFNULL(hf_data_detail.mobile_inside_male,0)) as mobile_inside_male,
                        SUM(IFNULL(hf_data_detail.mobile_inside_female,0)) as mobile_inside_female,
                        SUM(IFNULL(hf_data_detail.lhw_inside_male,0)) as lhw_inside_male,
                        SUM(IFNULL(hf_data_detail.lhw_inside_female,0)) as lhw_inside_female,
                        hf_data_detail.age_group_id,
                        hf_data_detail.vaccine_schedule_id
                        FROM
                        hf_data_master AS w0_
                        INNER JOIN hf_data_detail ON w0_.pk_id = hf_data_detail.hf_data_master_id
                        WHERE
                               w0_.warehouse_id = '$wh_id'
                                   AND hf_data_detail.age_group_id = '$age_group_id'
           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = '$pk_id' ";
        }
        $row = $this->_em_read->getConnection()->prepare($querypro);
        // echo $querypro . "<br><br>";

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Monthly Consumtion 2 Vaccines Tt
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @return type
     */
    public function monthlyConsumtion2VaccinesTtTotal($wh_id, $prev_month_date, $pk_id) {

        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));

        if (count($rows) > 0) {
            $querypro = " SELECT
                        hf_data_detail_draft.pk_id,
                        SUM(hf_data_detail_draft.pregnant_women) as pregnant_women,
                        SUM(hf_data_detail_draft.non_pregnant_women) as non_pregnant_women,
                        hf_data_detail_draft.age_group_id,
                        hf_data_detail_draft.vaccine_schedule_id

                       FROM
                               hf_data_master_draft w0_
                        INNER JOIN hf_data_detail_draft ON w0_.pk_id = hf_data_detail_draft.hf_data_master_id
                       WHERE
                               w0_.warehouse_id = $wh_id
           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT
                        hf_data_detail.pk_id,
                        SUM(hf_data_detail.pregnant_women) as pregnant_women,
                        SUM(hf_data_detail.non_pregnant_women) as non_pregnant_women,
                        hf_data_detail.age_group_id,
                        hf_data_detail.vaccine_schedule_id
                        FROM
                        hf_data_master AS w0_
                        INNER JOIN hf_data_detail ON w0_.pk_id = hf_data_detail.hf_data_master_id
                        WHERE
                               w0_.warehouse_id = '$wh_id'

           AND
           DATE_FORMAT(w0_.reporting_start_date,'%Y-%m-%d') = '$prev_month_date'
           AND w0_.item_pack_size_id = '$pk_id' ";
        }


        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Monthly Consumtion Refferal
     * @param type $wh_id
     * @param type $date_in
     * @param type $item_id
     * @param type $gender
     * @return type
     */
    public function monthlyConsumtionRefferalTotal($wh_id, $date_in, $item_id, $gender) {

        $querypro = "SELECT
              count(log_book.pk_id) as total
              FROM
                 log_book
              INNER JOIN log_book_item_doses ON log_book.pk_id = log_book_item_doses.log_book_id
              WHERE
              log_book_item_doses.item_pack_size_id = '$item_id' AND
              log_book.warehouse_id = '$wh_id' AND
              DATE_FORMAT(log_book.vaccination_date,'%Y-%m') = '$date_in'
              AND log_book.gender = '$gender'";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    public function getConfigurations() {
        $querypro = "SELECT
	consumption_form_config.vaccination_by,
	consumption_form_config.vaccination_given,
	consumption_form_config.gender,
	consumption_form_config.opening,
	consumption_form_config.received,
	consumption_form_config.closing,
	consumption_form_config.item_id,
	consumption_form_config.no_of_doses,
	consumption_form_config.start_dose,
	item_pack_sizes.item_name,
        item_pack_sizes.item_category_id
FROM
	consumption_form_config
INNER JOIN item_pack_sizes ON consumption_form_config.item_id = item_pack_sizes.pk_id";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

}

?>