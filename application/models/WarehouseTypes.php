<?php

/**
 * Model_WarehouseTypes
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
class Model_WarehouseTypes extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('WarehouseTypes');
    }

    /**
     * Get Geos All
     * 
     * @return type
     */
    public function getGeosAll() {
        return $this->_table->findAll();
    }

    public function getAllGeoLevels() {
        $str_sql = "
            SELECT
                geo_levels.pk_id,
                geo_levels.geo_level_name
            FROM
                geo_levels
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Get All Warehouse Types
     * @return type
     */
    public function getAllWarehouseTypes() {
        $where = "";
        if (!empty($this->form_values['warehouse_type_name'])) {
            $warehouse_type_name = $this->form_values['warehouse_type_name'];
            $where = "WHERE warehouse_types.warehouse_type_name = '$warehouse_type_name' ";
        }
        $str_sql = "
            SELECT
                warehouse_types.pk_id,
                warehouse_types.warehouse_type_name,
                warehouse_types.resupply_interval,
                warehouse_types.reserved_stock,
                warehouse_types.usage_percentage,
                warehouse_types.list_rank,
                warehouse_types.created_by,
                warehouse_types.created_date,
                warehouse_types.modified_by,
                warehouse_types.modified_date,
                geo_levels.pk_id,
                geo_levels.geo_level_name,
                warehouse_type_categories.category_name
                
            FROM
                warehouse_types
                INNER JOIN geo_levels ON warehouse_types.geo_level_id = geo_levels.pk_id
                INNER JOIN warehouse_type_categories ON warehouse_types.warehouse_type_category_id = warehouse_type_categories.pk_id
                $where
            ORDER BY
                geo_levels.pk_id ASC
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Get Warehouse Type By Id
     * @return type
     */
    public function getWarehouseTypeById() {
        $where = "";
        if (!empty($this->form_values['warehouse_type_id'])) {
            $warehouse_type_id = $this->form_values['warehouse_type_id'];
            $where = "WHERE warehouse_types.pk_id = '$warehouse_type_id' ";
        }
        $str_sql = "
            SELECT
                warehouse_types.pk_id,
                warehouse_types.warehouse_type_name,
                warehouse_types.resupply_interval,
                warehouse_types.reserved_stock,
                warehouse_types.usage_percentage,
                warehouse_types.list_rank,
                warehouse_types.created_by,
                warehouse_types.created_date,
                warehouse_types.modified_by,
                warehouse_types.modified_date,
                geo_levels.pk_id AS geo_level_id,
                warehouse_type_categories.pk_id AS category_id
            FROM
                warehouse_types
                INNER JOIN geo_levels ON warehouse_types.geo_level_id = geo_levels.pk_id
                INNER JOIN warehouse_type_categories ON warehouse_types.warehouse_type_category_id = warehouse_type_categories.pk_id
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Add Warehouse Type
     * @return boolean
     */
    public function addWarehouseType() {

        if (!empty($this->form_values['warehouse_type_name'])) {
            $warehouse_type_name = $this->form_values['warehouse_type_name'];
        }
        if (!empty($this->form_values['warehouse_type_category'])) {
            $warehouse_type_category = $this->form_values['warehouse_type_category'];
        }
        if (!empty($this->form_values['geo_level'])) {
            $geo_level = $this->form_values['geo_level'];
        }
        if (!empty($this->form_values['resupply_interval'])) {
            $resupply_interval = $this->form_values['resupply_interval'];
        }
        if (!empty($this->form_values['reserved_stock'])) {
            $reserved_stock = $this->form_values['reserved_stock'];
        }
        if (!empty($this->form_values['usage_percentage'])) {
            $usage_percentage = $this->form_values['usage_percentage'];
        }
        if (!empty($this->form_values['list_rank'])) {
            $list_rank = $this->form_values['list_rank'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            INSERT INTO warehouse_types 
                        (
                            pk_id, warehouse_type_name, resupply_interval, reserved_stock, usage_percentage, geo_level_id, warehouse_type_category_id, list_rank, created_by, created_date, modified_by, modified_date
                        )
                            SELECT
                                MAX(warehouse_types.pk_id) + 1 AS counter,              
                            '$warehouse_type_name',
                            '$resupply_interval',
                            '$reserved_stock',
                            '$usage_percentage',
                            '$geo_level',
                            '$warehouse_type_category',
                            '$list_rank',
                            '$user_id',
                            NOW(),
                            '$user_id',
                            NOW()
                            FROM
                            warehouse_types
                        ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Check Warehouse Type
     * @return type
     */
    public function checkWarehouseType() {
        $where = "";
        if (!empty($this->form_values['warehouse_type_name'])) {
            $warehouse_type_name = $this->form_values['warehouse_type_name'];
            $warehouse_type_name = trim($warehouse_type_name);
            $where = "WHERE warehouse_types.warehouse_type_name = '$warehouse_type_name' ";
        }
        $str_sql = "
            SELECT
                warehouse_types.pk_id,
                warehouse_types.warehouse_type_name
            FROM
                warehouse_types
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Update Warehouse Type
     * @return boolean
     */
    public function updateWarehouseType() {
        if (!empty($this->form_values['warehouse_type_id'])) {
            $warehouse_type_id = $this->form_values['warehouse_type_id'];
        }
        if (!empty($this->form_values['warehouse_type_name'])) {
            $warehouse_type_name = $this->form_values['warehouse_type_name'];
        }
        if (!empty($this->form_values['warehouse_type_category'])) {
            $warehouse_type_category = $this->form_values['warehouse_type_category'];
        }
        if (!empty($this->form_values['geo_level'])) {
            $geo_level = $this->form_values['geo_level'];
        }
        if (!empty($this->form_values['resupply_interval'])) {
            $resupply_interval = $this->form_values['resupply_interval'];
        }
        if (!empty($this->form_values['reserved_stock'])) {
            $reserved_stock = $this->form_values['reserved_stock'];
        }
        if (!empty($this->form_values['usage_percentage'])) {
            $usage_percentage = $this->form_values['usage_percentage'];
        }
        if (!empty($this->form_values['list_rank'])) {
            $list_rank = $this->form_values['list_rank'];
        }

        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            UPDATE warehouse_types 
                    SET
                        warehouse_types.warehouse_type_name = '$warehouse_type_name',
                        warehouse_types.resupply_interval = '$resupply_interval',
                        warehouse_types.reserved_stock = '$reserved_stock',
                        warehouse_types.usage_percentage = '$usage_percentage',
                        warehouse_types.geo_level_id = '$geo_level',
                        warehouse_types.warehouse_type_category_id = '$warehouse_type_category',
                        warehouse_types.list_rank = '$list_rank',                           
                        warehouse_types.created_by = $user_id,
                        warehouse_types.created_date = NOW(),
                        warehouse_types.modified_by = $user_id,
                        warehouse_types.modified_date = NOW()
                    WHERE
                        pk_id = '$warehouse_type_id'  ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Get Max Rank
     * @return type
     */
    public function getMaxRank() {
        $str_sql = "
            SELECT
                MAX(warehouse_types.list_rank) AS max_rank
            FROM
                warehouse_types
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

}
