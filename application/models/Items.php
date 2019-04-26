<?php

/**
 * Model_Items
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Bushra Saeed <bushrajsi1@gmail.com>
 * @version    2.5.1
 */

/**
 *  Model for Items
 */
class Model_Items extends Model_Base {

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
        $this->_table = $this->_em->getRepository('Items');
    }

    /**
     * Get Items
     * 
     * @return boolean
     */
    public function getItems() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("i.pkId,i.description")
                ->from('Items', 'i');

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Items
     * 
     * @return type
     */
    public function getAllItems() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("i.pkId, i.description")
                ->from("Items", "i");
        if (!empty($this->form_values['item_description'])) {
            $str_sql->where("i.description = '" . $this->form_values['item_description'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Item
     * 
     * @return type
     */
    public function checkItem() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("i.pkId")
                ->from('Items', 'i')
                ->where("i.description= '" . $form_values . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Item Categories
     * 
     * @uses api Barcode
     * @return type
     */
    public function getItemCategories() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
        item_categories.pk_id,
        item_categories.item_category_name,
        item_categories.`status`
        FROM
        item_categories");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Item Pack Sizes
     * 
     * @uses api Barcode
     * @return type
     */
    public function getItemPackSizes() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
        * FROM
        item_pack_sizes");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Item Units
     * 
     * @uses api Barcode
     * @return type
     */
    public function getItemUnits() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
        item_units.item_unit_name,
        item_units.pk_id
        FROM
        item_units");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Stakeholder Items
     * 
     * @uses api Barcode
     * @return type
     */
    public function getStakeholderItems() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
        * FROM
        stakeholder_item_pack_sizes");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Stakeholder Item Pack Sizes
     * 
     * @uses api Barcode
     * @return type
     */
    public function getStakeholderItemPackSizes() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT                
                                                    stakeholder_item_pack_sizes.stakeholder_id,
                                                    stakeholder_item_pack_sizes.item_pack_size_id,
                                                    stakeholder_item_pack_sizes.pk_id,
                                                    pack_info.length,
                                                    pack_info.width,
                                                    pack_info.height,
                                                    pack_info.quantity_per_pack,
                                                    pack_info.volum_per_vial,
                                                    pack_info.item_gtin,
                                                    pack_info.packaging_level,
                                                    pack_info.pack_size_description
                                                    FROM
                                                    stakeholder_item_pack_sizes
                                                    INNER JOIN pack_info ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id;");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

}
