<?php

/**
 * Model_EpiAmc
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Campaigns
 */
class Model_EpiAmc extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * Model_Campaigns __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('EpiAmc');
    }

    /**
     * Add EPI AMC
     * @return boolean
     */
    public function addRecord() {
        $data = $this->form_values;
        if ($data['type'] == 'Update') {
            $epi = $this->_em->getRepository("EpiAmc")->find($data['pk_id']);
        } else {
            $epi = new EpiAmc();
        }
        $amc = str_replace(",", "", $data['amc']);

        $epi->setAmc($amc);
        $epi->setAmcYear($data['amc_year']);
        $item_id = $this->_em->getRepository("ItemPackSizes")->find($data['item_id']);
        $epi->setItem($item_id);
        $epi->setRemarks($item_id->getItemName());
        $warehouse = $this->_em->getRepository("Warehouses")->find($data['wh_id']);
        $epi->setWarehouse($warehouse);
        $epi->setCreatedBy($this->_user_id);
        $epi->setModifiedBy($this->_user_id);
        $epi->setCreatedDate(App_Tools_Time::now());
        $epi->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($epi);
        $this->_em->flush();

        return true;
    }

    /**
     * Get EPI AMC By Year
     * @param type $year
     * @param type $wh_id
     * @return type
     */
    public function getByYear($year, $wh_id) {
        return $this->_table->findBy(array("amcYear" => $year, "warehouse" => $wh_id));
    }

    /**
     * Get Stores
     * @return type
     */
    public function getStores() {
        $str_sql2 = $this->_em_read->createQueryBuilder()
                ->select("wh")
                ->from("Warehouses", "wh")
                ->where("wh.stakeholderOffice IN (1,2)")
                ->andWhere("wh.pkId NOT IN (3683,3684)")
                ->orderBy("wh.pkId");
        return $str_sql2->getQuery()->getResult();
    }

}
