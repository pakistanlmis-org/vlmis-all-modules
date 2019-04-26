<?php

/**
 * Model_GeoIndicators
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Geo Indicators
 */
class Model_GeoIndicators extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('GeoIndicators');
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
     * Get All Geo Indicators
     * @return type
     */
    public function getAllGeoIndicators() {
        $where = "";
        if (!empty($this->form_values['geo_indicator_name'])) {
            $geo_indicator_name = $this->form_values['geo_indicator_name'];
            $where = "WHERE geo_indicators.geo_indicator_name = '$geo_indicator_name' ";
        }
        $str_sql = "
            SELECT
                geo_indicators.pk_id,
                geo_indicators.geo_indicator_name
            FROM
                geo_indicators
                $where
                ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Get Geo Indicator By Id
     * @return type
     */
    public function getGeoIndicatorById() {
        $where = "";
        if (!empty($this->form_values['geo_indicator_id'])) {
            $geo_indicator_id = $this->form_values['geo_indicator_id'];
            $where = "WHERE geo_indicators.pk_id = '$geo_indicator_id' ";
        }
        $str_sql = "
            SELECT
                geo_indicators.pk_id,
                geo_indicators.geo_indicator_name               
            FROM
                geo_indicators
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Add Geo Indicator
     * @return boolean
     */
    public function addGeoIndicator() {

        if (!empty($this->form_values['geo_indicator_name'])) {
            $geo_indicator_name = $this->form_values['geo_indicator_name'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            INSERT INTO geo_indicators 
                        (
                            geo_indicator_name, created_by, created_date, modified_by, modified_date
                        )
                        VALUES
                       (
                            '$geo_indicator_name',
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
     * Check Geo Indicator
     * @return type
     */
    public function checkGeoIndicator() {
        $where = "";
        if (!empty($this->form_values['geo_indicator_name'])) {
            $geo_indicator_name = $this->form_values['geo_indicator_name'];
            $geo_indicator_name = trim($geo_indicator_name);
            $where = "WHERE geo_indicators.geo_indicator_name = '$geo_indicator_name' ";
        }
        $str_sql = "
            SELECT
                geo_indicators.pk_id,
                geo_indicators.geo_indicator_name
            FROM
                geo_indicators
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Update Geo Indicator
     * @return boolean
     */
    public function updateGeoIndicator() {
        if (!empty($this->form_values['geo_indicator_id'])) {
            $geo_indicator_id = $this->form_values['geo_indicator_id'];
        }
        if (!empty($this->form_values['geo_indicator_name'])) {
            $geo_indicator_name = $this->form_values['geo_indicator_name'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            UPDATE geo_indicators 
                    SET
                        geo_indicators.geo_indicator_name = '$geo_indicator_name',
                        geo_indicators.created_by = $user_id,
                        geo_indicators.created_date = NOW(),
                        geo_indicators.modified_by = $user_id,
                        geo_indicators.modified_date = NOW()
                    WHERE
                        pk_id = '$geo_indicator_id'  ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

}
