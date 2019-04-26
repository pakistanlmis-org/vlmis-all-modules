<?php

/**
 * Model_GeoLevels
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Geo Levels
 */
class Model_GeoLevels extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('GeoLevels');
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
    public function getAllGeoLevels() {
        $where = "";
        if (!empty($this->form_values['geo_level_name'])) {
            $geo_level_name = $this->form_values['geo_level_name'];
            $where = "WHERE geo_levels.geo_level_name = '$geo_level_name' ";
        }
        $str_sql = "
            SELECT
                geo_levels.pk_id,
                geo_levels.geo_level_name,
                geo_levels.description,
                geo_levels.`status`
            FROM
                geo_levels
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
    public function getGeoLevelById() {
        $where = "";
        if (!empty($this->form_values['geo_level_id'])) {
            $geo_level_id = $this->form_values['geo_level_id'];
            $where = "WHERE geo_levels.pk_id = '$geo_level_id' ";
        }
        $str_sql = "
            SELECT
                geo_levels.pk_id,
                geo_levels.geo_level_name,
                geo_levels.description,
                geo_levels.`status`
            FROM
                geo_levels
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
    public function addGeoLevel() {
        if (!empty($this->form_values['geo_level_name'])) {
            $geo_level_name = $this->form_values['geo_level_name'];
            $geo_level_description = $this->form_values['geo_level_description'];
            $status = $this->form_values['status'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            INSERT INTO geo_levels 
                        (
                            geo_level_name, description, `status`, created_by, created_date, modified_by, modified_date
                        )
                        VALUES
                       (
                            '$geo_level_name',
                            '$geo_level_description',
                             '$status',
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
    public function checkGeoLevel() {
        $where = "";
        if (!empty($this->form_values['geo_level_name'])) {
            $geo_level_name = $this->form_values['geo_level_name'];
            $geo_level_name = trim($geo_level_name);
            $where = "WHERE geo_levels.geo_level_name = '$geo_level_name' ";
        }
        $str_sql = "
            SELECT
                geo_levels.pk_id,
                geo_levels.geo_level_name
            FROM
                geo_levels
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
    public function updateGeoLevel() {
        if (!empty($this->form_values['geo_level_id'])) {
            $geo_level_id = $this->form_values['geo_level_id'];
        }
        if (!empty($this->form_values['geo_level_name'])) {
            $geo_level_name = $this->form_values['geo_level_name'];
        }
        if (!empty($this->form_values['geo_level_description'])) {
            $description = $this->form_values['geo_level_description'];
        }
        $status = $this->form_values['status'];
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            UPDATE geo_levels 
                    SET
                        geo_levels.geo_level_name = '$geo_level_name',
                        geo_levels.description = '$description',
                        geo_levels.`status` = $status,
                        geo_levels.created_by = $user_id,
                        geo_levels.created_date = NOW(),
                        geo_levels.modified_by = $user_id,
                        geo_levels.modified_date = NOW()
                    WHERE
                        pk_id = '$geo_level_id'  ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Delete Geo Level
     * @return boolean
     */
    public function deleteGeoLevel() {
        if (!empty($this->form_values['country_id'])) {
            $country_id = $this->form_values['country_id'];
        }
        $str_qry = " 
            DELETE FROM countries 
                    WHERE
                        id = '$country_id'  ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

}
