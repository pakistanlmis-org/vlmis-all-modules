<?php

/**
 * Zend_View_Helper_ExplorerReport
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Explorer Report
 */
class Zend_View_Helper_ExplorerReport extends Zend_View_Helper_Abstract {

    protected $_em_read;

    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Explorer Report
     * @return \Zend_View_Helper_ExplorerReport
     */
    public function explorerReport() {
        return $this;
    }

    /**
     * ajaxExplorerReport
     * @param type $stkid
     * @param type $wh_id
     * @param type $yy
     * @param type $mm
     * @return boolean
     */
    public function ajaxExplorerReport($stkid, $wh_id, $yy, $mm) {
        $stakeholder_item_ids = "";
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.pkId")
                ->from('StakeholderItemPackSizes', 'si')
                ->innerJoin("si.itemPackSize", "ips")
                ->where("si.stakeholder = " . $stkid);
        $row = $str_sql->getQuery()->getResult();
        foreach ($row as $val) {
            $stakeholder_items[] = $val['pkId'];
        }
        $stakeholder_item_ids = implode(",", $stakeholder_items);

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT ips.itemName as item_name, ips.pkId as pk_id, ips.numberOfDoses as description')
                ->from("ItemPackSizes", "ips")
                ->innerJoin("ips.itemCategory", "ic")
                ->where("ips.status = 1")
                ->andWhere("ic.pkId <> 3")
                ->andWhere("ips.pkId IN (" . $stakeholder_item_ids . ")")
                ->orderBy("ips.listRank", "ASC");
        $rows = $str_sql->getQuery()->getResult();
        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return false;
        }
    }

    /**
     * Get Monthly Receive Quantity Warehouse
     * @param type $mm
     * @param type $yy
     * @param type $item_id
     * @param type $wh_id
     * @return type
     */
    public function getMonthlyReceiveQuantityWarehouse($mm, $yy, $item_id, $wh_id) {
        $row = $this->_em_read->getConnection()->prepare("SELECT getMonthlyRcvQtyWH($mm,$yy,$item_id,$wh_id) as rcv from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Warehouse Data By Item And Warehouse Id
     * @param type $wh_id
     * @param type $item_id
     * @param type $previous_selected
     * @return boolean
     */
    public function getWarehouseDataByItemAndWarehouseId($wh_id, $item_id, $previous_selected) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wd.pkId as pk_id, wd.openingBalance as opening_balance, wd.receivedBalance as received_balance, wd.issueBalance as issue_balance,'
                        . 'wd.closingBalance as closing_balance, wd.vialsUsed as vials_used, wd.adjustments,'
                        . 'wd.reportingStartDate as reporting_start_date, wd.nearestExpiry as nearest_expiry')
                ->from('HfDataMaster', "wd")
                ->where("wd.warehouse = '" . $wh_id . "'")
                ->andWhere("wd.itemPackSize = '" . $item_id . "' ")
                ->andWhere("wd.reportingStartDate = '" . $previous_selected . "' ");
        $rows = $str_sql->getQuery()->getResult();
        if (!empty($rows) && count($rows) > 0) {
            return $rows[0];
        } else {
            return false;
        }
    }

}

?>