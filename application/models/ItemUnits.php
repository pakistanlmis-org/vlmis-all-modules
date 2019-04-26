<?php

/**
 * Model_ItemUnits
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Item Units
 */

class Model_ItemUnits extends Model_Base {

    /**
     * $item_pack_size_id
     * @var type 
     */
    public $item_pack_size_id;

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
        $this->_table = $this->_em->getRepository('ItemUnits');
    }

    /**
     * Get Unit By Item Id
     * 
     * @return boolean
     */
    public function getUnitByItemId() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("iu.pkId, iu.itemUnitName, ips.itemName")
                ->from("ItemPackSizes", "ips")
                ->join("ips.itemUnit", "iu")
                ->where("ips.pkId = " . $this->form_values['item_pack_size_id']);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return array(
                'id' => $row[0]['pkId'],
                'type' => $row[0]['itemUnitName']
            );
        } else {
            return false;
        }
    }

    /**
     * Get Item Unit By Id
     * 
     * @return boolean
     */
    public function GetItemUnitById() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("item_units.item_unit_name")
                ->from('Model_ItemUnits item_units')
                ->where("pk_id=" . $this->pk_id);
        $row = $str_sql->fetchOne();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Get All Item Units
     * 
     * @return type
     */
    public function getAllItemUnits() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("iu.pkId, iu.itemUnitName")
                ->from("ItemUnits", "iu");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Item Units
     * 
     * @return type
     */
    public function getItemUnits() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("iu.pkId, iu.itemUnitName")
                ->from("ItemUnits", "iu");
        if (!empty($this->form_values['item_unit_name'])) {
            $str_sql->where("iu.itemUnitName= '" . $this->form_values['item_unit_name'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Item Unit
     * 
     * @return type
     */
    public function checkItemUnit() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("i.pkId")
                ->from('ItemUnits', 'i')
                ->where("i.itemUnitName = '" . $form_values . "' ");

        return $str_sql->getQuery()->getResult();
    }

}
