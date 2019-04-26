<?php

/**
 * Model_Dashlets
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Dashboards
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @author     Muhammad Waqas Azeem <waqaszaeemcs06@gmail.com>
 * @version    1
 */

/**
 *  Model for Dashlets
 */
class Model_Dashlets extends Model_Base {

    /**
     * Stock Status
     * 
     * @return type
     */
    public function stockStatus() {
        switch ($this->form_values['level']) {
            case 1:
                $where = ' stakeholders.geo_level_id = 1 ';
                break;
            case 2:
                $where = ' stakeholders.geo_level_id = 2 ';
                $where .= " AND warehouses.location_id = '" . $this->form_values['prov_id'] . "'";
                break;
            case 6:
                $where = ' stakeholders.geo_level_id = 4 ';
                $where .= " AND warehouses.location_id = '" . $this->form_values['loc_id'] . "'";
                break;
            default :
                break;
        }

        $startDate = date('Y-m-01', strtotime($this->form_values['date']));
        $endDate = date('Y-m-t', strtotime($this->form_values['date']));

        $str_sql = "SELECT
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') < '$startDate', stock_detail.quantity, 0)) AS OB,
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id = 1, stock_detail.quantity, 0)) AS Rcv,
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id = 2, ABS(stock_detail.quantity), 0)) AS Issue,
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id > 2 AND transaction_types.nature = '+', stock_detail.quantity, 0)) AS vials_used,
            ABS(SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id > 2 AND transaction_types.nature = '-', stock_detail.quantity, 0))) AS adjustments,
            SUM(stock_detail.quantity) AS CB,
            item_pack_sizes.item_name
           FROM
            stock_master
           INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
           INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
           INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
           INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
           INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
           INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
           INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
           INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
           INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
           WHERE
           $where AND DATE_FORMAT(
             stock_master.transaction_date,
             '%Y-%m-%d'
            ) <= '$endDate' GROUP BY item_pack_sizes.pk_id order by item_pack_sizes.list_rank";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Reporting Rate
     * 
     * @return string
     */
    public function reportingRate() {
        $months = $this->form_values;
        $data_arr = array();
        $sub_sql = "SELECT
                            warehouses.pk_id,
                            warehouses.warehouse_name 
                            FROM
                                    warehouse_users 
                            INNER JOIN warehouses  ON warehouse_users.warehouse_id = warehouses.pk_id
                            INNER JOIN users  ON warehouse_users.user_id = users.pk_id
                            WHERE
                            users.pk_id = " . $this->_user_id . " and warehouses.status = 1";
        $row_sub = $this->_em_read->getConnection()->prepare($sub_sql);
        $row_sub->execute();
        $sub_sub = $row_sub->fetchAll();
        foreach ($sub_sub as $sub_rs) {

            foreach ($months as $months_rs) {
                $str_sql_sub = "SELECT
                       hf_data_master.warehouse_id
                       FROM
                       hf_data_master 
                       WHERE
                       DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m') = '$months_rs'
                       AND hf_data_master.warehouse_id  = " . $sub_rs['pk_id'] . "
                       GROUP BY
                       hf_data_master.warehouse_id
                       UNION 
                       SELECT
                       hf_data_master.warehouse_id
                       FROM
                       hf_data_master 
                       WHERE
                       DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m') = '$months_rs'
                       AND hf_data_master.warehouse_id  = " . $sub_rs['pk_id'] . "
                       GROUP BY
                       hf_data_master.warehouse_id";

                $row_r = $this->_em_read->getConnection()->prepare($str_sql_sub);
                $row_r->execute();
                if (count($row_r->fetchAll()) > 0) {
                    $data_arr[$sub_rs['pk_id']."|".$sub_rs['warehouse_name']][$sub_rs['pk_id']][$months_rs] = 'R';
                } else {
                    $data_arr[$sub_rs['pk_id']."|".$sub_rs['warehouse_name']][$sub_rs['pk_id']][$months_rs] = 'NR';
                }
            }
        }
        return $data_arr;
    }

    /**
     * Campaign Vaccines
     * 
     * @return boolean
     */
    public function campaignVaccines() {
        $loc_id = $this->form_values['loc_id'];
        $prov_id = $this->form_values['prov_id'];
        $camp_id = $this->form_values['camp'];
        $level = $this->form_values['level'];

        if (empty($camp_id)) {
            return false;
        }

        switch ($level) {
            case 1:
                $where = "";
                break;
            case 2:
                $where = "and locations.province_id=" . $prov_id;
                break;
            case 6:
                $where = "and locations.pk_id=" . $loc_id;
                break;
            default :
                break;
        }

        $str_sql = "SELECT
            locations.location_name,item_pack_sizes.item_name,
            round(ifnull(Sum(campaign_targets.daily_target),0)/item_pack_sizes.number_of_doses) as vials_required,
            round(ifnull(Sum(campaign_data.vials_used), 0)) AS vials_used
            FROM
            campaign_targets
            INNER JOIN warehouses ON campaign_targets.warehouse_id = warehouses.pk_id
            INNER JOIN locations ON locations.pk_id = warehouses.district_id
            INNER JOIN campaign_item_pack_sizes ON campaign_targets.campaign_id = campaign_item_pack_sizes.campaign_id
            INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = campaign_item_pack_sizes.item_pack_size_id
            INNER JOIN campaign_data ON campaign_targets.pk_id = campaign_data.campaign_target_id            
            where campaign_targets.campaign_id=$camp_id and warehouses.status = 1 "
                . "$where
            GROUP BY warehouses.district_id,campaign_targets.campaign_id,item_pack_sizes.pk_id";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

}
