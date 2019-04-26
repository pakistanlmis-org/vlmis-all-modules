<?php

/**
 * Zend_View_Helper_TableHeading
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */



/**
 *  Zend View Helper Monthly Consumtion
 */

class Zend_View_Helper_MonthlyConsumtion extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Monthly Consumtion
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @return string
     */
    public function monthlyConsumtion($wh_id, $prev_month_date, $pk_id) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wd.pkId,
                    wd.openingBalance,
                    wd.receivedBalance,
                    wd.issueBalance,
                    wd.closingBalance,
                    wd.wastages,
                    wd.vialsUsed,
                    wd.adjustments,
                    wd.reportingStartDate,
                    wd.nearestExpiry,
                    wd.createdDate')
                ->from("HfDataMaster", "wd")
                ->where("wd.warehouse = $wh_id")
                ->andWhere("wd.reportingStartDate IN ('$prev_month_date')")
                ->andWhere("wd.itemPackSize=$pk_id");

        $rs = $str_sql->getQuery()->getResult();

        if (!empty($rs) && count($rs) > 0) {
            return $rs[0];
        } else {
            return '0';
        }
    }

}

?>