<?php

/**
 * Model_Item
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author    Hannan mehmood <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Item
 */
class Model_Survey extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Survey');
    }

    /**
     * Get All Items
     * 
     * @return type
     */
    public function getSurveyList() {
        $user_id = $this->_user_id;
        $current_date = date("Y-m-d");

        $str_qry = "SELECT
                survey.pk_id,
                survey.`name`,
                survey.email,
                survey.department,
                survey.office,
                survey.cell_number,
                survey.q1_data_difficulty,
                survey.q2_report,
                survey.`comment`,
                survey.q1_y_n,
                survey.q2_y_n,
                survey.user_id,
                survey.created_by,
                survey.created_date,
                survey.modified_by,
                survey.modified_date
                FROM
                survey
                where survey.user_id = '$user_id'
                and DATE_FORMAT(survey.created_date,'%Y-%m-%d') = '$current_date'";

        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

}
