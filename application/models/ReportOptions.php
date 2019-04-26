<?php

/**
 * Model_ReportOptions
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Report Options
 */

class Model_ReportOptions extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('ReportOptions');
    }

    /**
     * Get Report Data Sql
     * 
     * @return type
     */
    public function getReportDataSql() {
    
        return $this->_table->findOneBy(
                        array(
                            'reportStakeholder' => $this->form_values['stakeholder'],
                            'reportId' => $this->form_values['report_id'],
                            'reportComparision' => $this->form_values['report_comp']
                        )
        );
    }

}
