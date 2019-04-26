<?php

/**
 * Model_CcmMakes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for CCM Makes
 * 
 * Inherits: Model_Base 
 */

class Model_CcmMakes extends Model_Base {

    /**
     * $_table
     * 
     * Private Variable
     * 
     * Table
     * 
     * @var type 
     */
    private $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('CcmMakes');
    }

    /**
     * Get All Makes
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllMakes($order = null, $sort = null) {


        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("m.pkId,m.ccmMakeName,m.status,u.userName")
                ->from('CcmMakes', 'm')
                ->LeftJOIN('m.createdBy', 'u');
        if (!empty($this->form_values['ccmMakeName'])) {
            $str_sql->where("m.ccmMakeName = '" . $this->form_values['ccmMakeName'] . "'  ");
        }

        if ($this->form_values['status'] == '1') {
            $str_sql->where("m.status = '1'  ");
        }
        if ($this->form_values['status'] == '2') {
            $str_sql->where("m.status = '0'  ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Makes By Asset Type
     * 
     * @return boolean
     */
    public function getAllMakesByAssetType() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("mm.pkId,mm.ccmMakeName")
                ->from('CcmModels', 'm')
                ->join("m.ccmMake", 'mm')
                ->where('m.ccmAssetType=' . $this->form_values['type_id']);
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Add Make
     * 
     * @return type
     */
    public function addMake() {
        $ccm_makes = new CcmMakes();
        $ccm_makes->setTransactionReference($this->form_values['transaction_reference']);
        $trans_no = '';
        $ccm_makes->setTransactionNumber($trans_no);

        $created_by = $this->_em->find('Users', $this->_user_id);
        $ccm_makes->setCreatedBy($created_by);
        $ccm_makes->setCreatedDate(App_Tools_Time::now());
        $ccm_makes->setModifiedBy($created_by);
        $ccm_makes->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($ccm_makes);
        $this->_em->flush();
        return $ccm_makes->getPkId();
    }

    /**
     * Get All Makes For Add Form
     * 
     * @return boolean
     */
    public function getAllMakesForAddForm() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("m.pkId, m.ccmMakeName")
                ->from('CcmMakes', 'm');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Make By Make Id
     * 
     * @return type
     */
    public function getMakeByMakeId() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('c.pkId', 'c.ccmMakeName')
                ->from("CcmMakes", "c")
                ->where("c.pkId = " . $this->form_values['make_id']);
        return $str_sql->getQuery()->getResult();
    }

}
