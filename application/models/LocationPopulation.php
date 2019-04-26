<?php

/**
 * Model_LocationPopulation
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for locations
 */
class Model_LocationPopulation extends Model_Base {

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
        $this->_table = $this->_em->getRepository('LocationPopulations');
    }

    /**
     * Gets province population.
     * @return boolean
     */
    public function getProvincePopulation($province_id, $year) {
        $querypro = "SELECT
                    SUM(location_populations.population) as population
                    FROM
                    locations
                    INNER JOIN location_populations ON location_populations.location_id = locations.pk_id
                    WHERE
                    YEAR(location_populations.estimation_date) = $year AND
                    locations.geo_level_id = 6
                    AND locations.province_id = $province_id
                    GROUP BY locations.province_id";
        //echo $querypro;exit;
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            return $rs;
        } else {
            return false;
        }
    }

}
