<?php

/**
 * Model_Placements
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Period
 */
class Model_Period extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    const QUARTER = 3;
    const HALFYEAR = 6;
    const ANNUAL = 12;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Period');
    }

    /**
     * Get Period By Id
     * 
     * @return type
     */
    public function getPeriodById() {
        return $this->_table->find($this->form_values['id']);
    }

    /**
     * Get Time Intervals
     * 
     * @return boolean
     */
    public function getTimeIntervals() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("p.periodName,p.pkId,p.monthCount")
                ->from("Period", "p")
                ->where("p.monthCount > 1");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Periods
     * @param type $order
     * @param type $sort
     * @return boolean
     */
    public function getPeriods($order = null, $sort = null) {

        $str_sql = "SELECT
                        period.pk_id,
                        period.period_name,
                        period.is_month
                    FROM
                        period";

        if (!empty($this->form_values['period_name'])) {
            $str_sql = $str_sql . " WHERE period.period_name = '" . $this->form_values['period_name']."'" ;
        }
        
        $sql = $this->_em_read->getConnection()->prepare($str_sql);
        $sql->execute();
        $row = $sql->fetchAll();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Check Period Code
     * @return type
     */
    public function checkPeriodCode() {
        $form_values = $this->form_values;
        
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("p.pkId")
                ->from('Period', 'p')
                ->where("p.periodCode = '" . $form_values['period_code'] . "' ");
        return $str_sql->getQuery()->getResult();
    }

}
