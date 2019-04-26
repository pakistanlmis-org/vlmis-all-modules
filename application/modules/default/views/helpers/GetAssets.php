<?php

/**
 * Zend_View_Helper_GetAssets
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper GetAssets
 */
class Zend_View_Helper_GetAssets extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Assets
     * 
     * Public Function
     * 
     * To Get Assets
     * 
     * @return \Zend_View_Helper_GetAssets
     */
    public function getAssets() {
        //returns result
        return $this;
    }

    /**
     * Get Assets Non Quanity
     * 
     * Public Function
     * 
     * To Get Assets Non Quantity
     * 
     * @param type $warehouse_id
     * @return type
     */
    public function getAssetsNonQuanity($warehouse_id, $date_in) {

        $querypro = "SELECT
                    csh.status_date as statusDate,
                    cat.pk_id AS ccmAssetId,
                    cc.asset_id AS generateAssetId,
                    cat.asset_type_name as assetTypeName,
                    csh.freeze_alarm as freezeAlarm,
                    csh.heat_alarm as heatAlarm,
                    csl.pk_id AS ccmStatusListId,
                    csl.reason_type AS reason_type,
                    cc.pk_id as pkId,
                    r.pk_id AS reasonId,
                    w.warehouse_name,
                    u.pk_id AS utilizationId,
                    cat.parent_id 
            FROM
                    cold_chain cc
            INNER JOIN ccm_status_history csh ON cc.ccm_status_history_id = csh.pk_id
            INNER JOIN ccm_status_list csl  ON csh.ccm_status_list_id = csl.pk_id
            LEFT JOIN ccm_status_list r ON csh.reason_id = r.pk_id
            LEFT JOIN ccm_status_list u ON csh.utilization_id = u.pk_id
            INNER JOIN ccm_asset_types cat ON cc.ccm_asset_type_id = cat.pk_id
            INNER JOIN warehouses w ON cc.warehouse_id = w.pk_id
            INNER JOIN warehouse_users wu ON w.pk_id = wu.warehouse_id
            WHERE
                    wu.user_id = $warehouse_id
                    and Date_Format(csh.status_date,'%Y-%m-%d') = '$date_in'   
            AND (
                    cat.pk_id IN (1, 3, 6, 7)
                    OR cat.parent_id IN (1, 3, 6, 7)
            )";



        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();

        $result = $row->fetchAll();

        if (count($result) > 0) {
            $querypro = "SELECT
                    csh.status_date as statusDate,
                    cat.pk_id AS ccmAssetId,
                    cc.asset_id AS generateAssetId,
                    cat.asset_type_name as assetTypeName,
                    csh.heat_alarm as heatAlarm,
                    csh.freeze_alarm as freezeAlarm,
                    csl.pk_id AS ccmStatusListId,
                    csl.reason_type AS reason_type,
                    cc.pk_id as pkId,
                    r.pk_id AS reasonId,
                    w.warehouse_name,
                    u.pk_id AS utilizationId,
                    cat.parent_id,
                    IFNULL(SUM(placement_summary.quantity),0) as qty
            FROM
                    cold_chain cc
            INNER JOIN ccm_status_history csh ON cc.ccm_status_history_id = csh.pk_id
            INNER JOIN ccm_status_list csl  ON csh.ccm_status_list_id = csl.pk_id
            LEFT JOIN ccm_status_list r ON csh.reason_id = r.pk_id
            LEFT JOIN ccm_status_list u ON csh.utilization_id = u.pk_id
            INNER JOIN ccm_asset_types cat ON cc.ccm_asset_type_id = cat.pk_id
            INNER JOIN warehouses w ON cc.warehouse_id = w.pk_id
            INNER JOIN warehouse_users wu ON w.pk_id = wu.warehouse_id
            LEFT JOIN placement_locations ON cc.pk_id = placement_locations.location_id
            LEFT JOIN placement_summary ON placement_locations.pk_id = placement_summary.placement_location_id
            WHERE
                    wu.user_id = $warehouse_id
                    and Date_Format(csh.status_date,'%Y-%m-%d') = '$date_in'   
            AND (
                    cat.pk_id IN (1, 3, 6, 7)
                    OR cat.parent_id IN (1, 3, 6, 7)
            ) GROUP BY cc.pk_id";

       


            $row = $this->_em_read->getConnection()->prepare($querypro);

            $row->execute();

            return $row->fetchAll();
        } else {
            $querypro = "SELECT
                    csh.status_date as statusDate,
                    cat.pk_id AS ccmAssetId,
                    cc.asset_id AS generateAssetId,
                    cat.asset_type_name as assetTypeName,
                    csh.heat_alarm as heatAlarm,
                    csh.freeze_alarm as freezeAlarm,
                    csl.pk_id AS ccmStatusListId,
                    csl.reason_type AS reason_type,
                    cc.pk_id as pkId,
                    r.pk_id AS reasonId,
                    w.warehouse_name,
                    u.pk_id AS utilizationId,
                    cat.parent_id,
                    IFNULL(SUM(placement_summary.quantity),0) as qty
            FROM
                    cold_chain cc
            INNER JOIN ccm_status_history csh ON cc.ccm_status_history_id = csh.pk_id
            INNER JOIN ccm_status_list csl  ON csh.ccm_status_list_id = csl.pk_id
            LEFT JOIN ccm_status_list r ON csh.reason_id = r.pk_id
            LEFT JOIN ccm_status_list u ON csh.utilization_id = u.pk_id
            INNER JOIN ccm_asset_types cat ON cc.ccm_asset_type_id = cat.pk_id
            INNER JOIN warehouses w ON cc.warehouse_id = w.pk_id
            INNER JOIN warehouse_users wu ON w.pk_id = wu.warehouse_id
            LEFT JOIN placement_locations ON cc.pk_id = placement_locations.location_id
LEFT JOIN placement_summary ON placement_locations.pk_id = placement_summary.placement_location_id
            WHERE
                    wu.user_id = $warehouse_id
                     
            AND (
                    cat.pk_id IN (1, 3, 6, 7)
                    OR cat.parent_id IN (1, 3, 6, 7)
            ) GROUP BY cc.pk_id";


           
            $row = $this->_em_read->getConnection()->prepare($querypro);

            $row->execute();
            return $row->fetchAll();
        }

        //returns result
    }

    /**
     * Get Assets Quanity
     * @param type $warehouse_id
     * @return type
     */
    public function getAssetsQuanity($warehouse_id) {

        $querypro = "SELECT DISTINCT
        cc.pk_id as pkId,
        cc.asset_id as assetId,
        cat.pk_id AS ccmAssetId,
        cat.asset_type_name As assetTypeName,
        SUM(csh.working_quantity) AS quantity,
        cm.ccm_model_name AS ccmModelName,
        csh.comments
        FROM
                cold_chain cc
        INNER JOIN ccm_models cm ON cc.ccm_model_id = cm.pk_id
        INNER JOIN ccm_asset_types cat ON cc.ccm_asset_type_id = cat.pk_id
        INNER JOIN ccm_status_history csh ON cc.ccm_status_history_id = csh.pk_id
        INNER JOIN warehouses w ON cc.warehouse_id = w.pk_id
        WHERE
                w.pk_id = $warehouse_id
        AND cat.pk_id IN (2, 4, 5)
        GROUP BY cm.ccm_model_name"
                . " HAVING quantity>0";


        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();

        //returns result
        return $row->fetchAll();
    }

    /**
     * Get Assets Quanity Update
     * @param type $warehouse_id
     * @return boolean
     */
    public function getAssetsQuanityUpdate($warehouse_id) {

        $s_sql = $this->_em_read->createQueryBuilder()
                ->select("MAX(cs.pkId)")
                ->from("CcmStatusHistory", "cs");
        $max_id = $s_sql->getQuery()->getResult();
        $sub_sql = $this->_em_read->createQueryBuilder()
                ->select("MAX(cs.statusDate)")
                ->from("CcmStatusHistory", "cs")
                ->where("cs.pkId='" . $max_id[0][1] . "'");
        $max_date = $sub_sql->getQuery()->getResult();

        //database query
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT cc.pkId,cc.assetId,at.pkId as ccmAssetId,at.assetTypeName,"
                        . "csh.workingQuantity as quantity,cm.ccmModelName")
                ->from('CcmStatusHistory', 'csh')
                ->join('csh.ccm', 'cc')
                ->join('cc.ccmModel', 'cm')
                ->join('cc.ccmAssetType', 'at')
                ->join('cc.warehouse', 'w')
                ->where("w.pkId = $warehouse_id ")
                ->andWhere('at.pkId IN (2,4,5)')
                ->andWhere("csh.statusDate='" . $max_date[0][1] . "'")
                ->groupBy('at.assetTypeName');
        //gets result
        $row = $str_sql->getQuery()->getResult();
        //returns result
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
