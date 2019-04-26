<?php

/**
 * Model_WarehouseTypeCategories
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Muhammad Imran 
 * @version    2.5.1
 */

/**
 *  Model for Geo Levels
 */
class Model_WarehouseTypeCategories extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('WarehouseTypeCategories');
    }

    /**
     * Get Geos All
     * 
     * @return type
     */
    public function getGeosAll() {
        return $this->_table->findAll();
    }

    /**
     * Get All Geo Levels
     * @return type
     */
    public function getAllWarehouseTypeCategories() {
        $where = "";
        if (!empty($this->form_values['wh_category_name'])) {
            $wh_category_name = $this->form_values['wh_category_name'];
            $where = "WHERE warehouse_type_categories.category_name = '$wh_category_name' ";
        }
        $str_sql = "
            SELECT
                warehouse_type_categories.pk_id,
                warehouse_type_categories.category_name
            FROM
                warehouse_type_categories
                $where
                ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Get Geo Level By Id
     * @return type
     */
    public function getWarehouseTypeCategoryById() {
        $where = "";
        if (!empty($this->form_values['warehouse_type_category_id'])) {
            $warehouse_type_category_id = $this->form_values['warehouse_type_category_id'];
            $where = "WHERE warehouse_type_categories.pk_id = '$warehouse_type_category_id' ";
        }
       $str_sql = "
            SELECT
                warehouse_type_categories.pk_id,
                warehouse_type_categories.category_name               
            FROM
                warehouse_type_categories
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Add Geo Level
     * @return boolean
     */
    public function addWarehouseTypeCategory() {

        if (!empty($this->form_values['wh_category_name'])) {
            $wh_category_name = $this->form_values['wh_category_name'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            INSERT INTO warehouse_type_categories 
                        (
                            category_name, created_by, created_date, modified_by, modified_date
                        )
                        VALUES
                       (
                            '$wh_category_name',
                            '$user_id',
                            NOW(),
                            '$user_id',
                            NOW()
                        )";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Check Geo Level
     * @return type
     */
    public function checkWarehouseTypeCategory() {
        $where = "";
        if (!empty($this->form_values['wh_category_name'])) {
            $wh_category_name = $this->form_values['wh_category_name'];
            $wh_category_name = trim($wh_category_name);
            $where = "WHERE warehouse_type_categories.category_name = '$wh_category_name' ";
        }
        $str_sql = "
            SELECT
                warehouse_type_categories.pk_id,
                warehouse_type_categories.category_name
            FROM
                warehouse_type_categories
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Update Geo Level
     * @return boolean
     */
    public function updateWarehouseTypeCategory() {
        if (!empty($this->form_values['wh_category_id'])) {
            $wh_category_id = $this->form_values['wh_category_id'];
        }
        if (!empty($this->form_values['wh_category_name'])) {
            $wh_category_name = $this->form_values['wh_category_name'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            UPDATE warehouse_type_categories 
                    SET
                        warehouse_type_categories.category_name = '$wh_category_name',
                        warehouse_type_categories.created_by = $user_id,
                        warehouse_type_categories.created_date = NOW(),
                        warehouse_type_categories.modified_by = $user_id,
                        warehouse_type_categories.modified_date = NOW()
                    WHERE
                        pk_id = '$wh_category_id'  ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

}
