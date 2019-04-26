<?php

/**
 * Model_HelpMessages
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Help Messages
 */

class Model_HelpMessages extends Model_Base {

    /**
     *$_table
     * 
     * private Variable
     * 
     * Table
     * 
     * @var type 
     */
    private $_table;

    const DEACTIVE = 0;
    const ACTIVE = 1;
    const DELETED = 2;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('HelpMessages');
    }

    /**
     * Get By Search
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getBySearch($order = null, $sort = null) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("hp.pkId, hp.description, hp.status, r.resourceName")
                ->from("HelpMessages", "hp")
                ->leftJoin('hp.resource', 'r');
        if (!empty($this->form_values['deleted'])) {
            $str_sql->where("hp.status = '" . Model_HelpMessages::DELETED . "' ");
        } else {
            $str_sql->where("hp.status <> '" . Model_HelpMessages::DELETED . "' ");
        }
        if (!empty($this->form_values['pkId'])) {
            $str_sql->andWhere("hp.pkId = '" . $this->form_values['pkId'] . "' ");
        }
        if (!empty($this->form_values['search_text'])) {
            $str_sql->andWhere("hp.description LIKE '%" . $this->form_values['search_text'] . "%'  ");
        }
        if (!empty($this->form_values['page_name'])) {
            $str_sql->andWhere("hp.resource = " . $this->form_values['page_name'] . "");
        }
        if ($order == 'sr_no') {
            $str_sql->orderBy("hp.pkId", $sort);
        }
        if ($order == 'title') {
            $str_sql->orderBy("hp.description", $sort);
        }
        if ($order == 'status') {
            $str_sql->orderBy("hp.status", $sort);
        }

        return $str_sql->getQuery()->getResult();
    }

}
