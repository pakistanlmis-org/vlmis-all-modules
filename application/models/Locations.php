<?php

/**
 * Model_Locations
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for locations
 */
class Model_Locations extends Model_Base {

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
        $this->_table = $this->_em->getRepository('Locations');
    }

    /**
     * Get All UC By User Id
     * @return boolean
     */
    public function getAllUCByUserId() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT l.locationName AS location_name,
                        l.pkId AS pk_id,
                        wu.pkId AS wu_pk_id,
                        ws.pkId AS ws_pk_id')
                ->from("WarehouseUsers", "wu")
                ->innerJoin("wu.warehouse", "ws")
                ->innerJoin("ws.location", "l")
                ->where("l.geoLevel = 6")
                ->andWhere("wu.user = " . $this->_identity->getIdentity());
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Locations By Level
     * @return boolean
     */
    public function getLocationsByLevel() {

        if ($this->_identity->getRoleId() == 38 || $this->_identity->getRoleId() == 13) {
            $warehouse_id = $this->_identity->getWarehouseId();
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('l.locationName,l.pkId')
                    ->from("Warehouses", "w")
                    ->Join("w.province", "l")
                    ->andWhere("w.pkId = '$warehouse_id'");
        } else if ($this->form_values['office'] == 3 || $this->_identity->getRoleId() == 36) {

            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('l.locationName,l.pkId')
                    ->from("Locations", "l")
                    ->where("l.geoLevel = '" . $this->form_values['geo_level_id'] . "' ")
                    ->andWhere("l.parent =  '" . $this->form_values['parent_id'] . "'  ")
                    // Skip national.
                    ->andWhere("l.pkId <> 10")
                    ->andWhere("l.locationName = 'Sindh'");
        } else {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('l.locationName,l.pkId')
                    ->from("Locations", "l")
                    ->where("l.geoLevel = '" . $this->form_values['geo_level_id'] . "' ")
                    ->andWhere("l.parent =  '" . $this->form_values['parent_id'] . "'  ")
                    // Skip national.
                    ->andWhere("l.pkId <> 10");
        }
        
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['locationName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse By Level
     * @return type
     */
    public function getWarehouseByLevel() {
        $level = $this->form_values['level'];
        $prov_id = $this->form_values['prov_id'];
        $loc_id = $this->form_values['loc_id'];
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT w.pkId')
                ->from("Warehouses", "w")
                ->join("w.stakeholderOffice", "s")
                ->where("s.stakeholderType = 1")
                ->andWhere("w.status = 1");
        switch ($level) {
            case 1:
                $str_sql->andWhere("s.geoLevel = 1");
                break;
            case 2:
                $str_sql->andWhere("s.geoLevel = 2");
                $str_sql->andWhere("w.province = " . $prov_id);
                break;
            case 6:
                $str_sql->andWhere("s.geoLevel = 4");
                $str_sql->andWhere("w.district = " . $loc_id);
                break;
            default :
                break;
        }
        $rs = $str_sql->getQuery()->getResult();
        if (!empty($rs[0]['pkId'])) {
            return $rs[0]['pkId'];
        } else {
            return $this->_identity->getWarehouseId();
        }
    }

    /**
     * Get Location By Id
     * @return boolean
     */
    public function getLocationById() {
        $id = $this->form_values['product_id'];
        $row = $this->_table->find($id);
        if (count($row) > 0) {
            return $row->getLocationName();
        } else {
            return false;
        }
    }

    /**
     * Get Locations By Level By Province
     * @return boolean
     */
    public function getLocationsByLevelByProvince() {
        $geo_level_id = $this->form_values['geo_level_id'];
        $province_id = $this->form_values['province_id'];
        $querypro = "SELECT
                        l.pk_id as pkId,l.location_name as locationName
                        FROM
                        locations l
                        INNER JOIN pilot_districts ON pilot_districts.district_id = l.district_id
                        WHERE
                        (l.geo_level_id = $geo_level_id "
                . "AND l.province_id=  $province_id )"
                . "ORDER BY locationName";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['locationName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Divisions By Province
     * @return boolean
     */
    public function getDivisionsByProvince() {
        $province_id = $this->form_values['parent_id'];

        $query = "SELECT
                    locations.pk_id AS pkId,
                    locations.location_name AS locationName
                FROM
                    locations
                WHERE
                    locations.geo_level_id = 3 AND
                    locations.parent_id = $province_id";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['locationName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Districts by Division
     * @return boolean
     */
    public function getDistrictsByDivision() {
        $div_id = $this->form_values['div_id'];
        $query = "SELECT
                    locations.pk_id AS pkId,
                    locations.location_name AS locationName
                FROM
                    locations
                WHERE
                    locations.geo_level_id = 4 AND
                    locations.parent_id = $div_id";
        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['locationName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Locations By Level By Province Consumption
     * @return boolean
     */
    public function getLocationsByLevelByProvinceConsumption() {
        $geo_level_id = $this->form_values['geo_level_id'];
        $province_id = $this->form_values['province_id'];
        $querypro = "SELECT
                        l.pk_id as pkId,l.location_name as locationName
                        FROM
                        locations l
                        WHERE
                        (l.geo_level_id = $geo_level_id "
                . "AND l.province_id=  $province_id)"
                . "ORDER BY locationName";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['locationName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Locations By Level By Province Consumption
     * @return boolean
     */
    public function getLocationsByLevelByProvinceConsumption1() {
        $geo_level_id = $this->form_values['geo_level_id'];
        $province_id = $this->form_values['province_id'];
        $querypro = "SELECT
            locations.pk_id,
            locations.location_name
            FROM
            locations
            WHERE
            locations.province_id = 2 AND
            locations.geo_level_id = 4
            UNION
            SELECT
            locations.pk_id,
            locations.location_name
            FROM
            locations
            WHERE
            locations.district_id = 87 AND
            locations.geo_level_id = 5";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pk_id'], 'value' => $row['location_name']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * National Report
     * @return type
     */
    public function nationalReport() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT l.locationName AS location_name,
                        l.pkId AS pk_id')
                ->from("Locations", "l")
                ->where("l.geoLevel = 2")
                ->andWhere("l.province IS NOT NULL");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Stock Availability Report
     * @return type
     */
    public function stockAvailabilityReport() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT l.locationName AS location_name,
                        l.pkId AS pk_id')
                ->from("Locations", "l")
                ->where("l.geoLevel = 2 ")
                ->andWhere("l.province IS NOT NULL ");
        return $str_sql->fetchArray();
    }

    /**
     * Devisional Report
     * @return type
     */
    public function devisionalReport() {
        $querypro = "SELECT DISTINCT
                    l0_.pk_id,
                    l0_.location_name
                    FROM
                    locations AS l0_
                    INNER JOIN locations AS dist ON dist.province_id = l0_.pk_id
                    INNER JOIN pilot_districts ON pilot_districts.district_id = dist.pk_id
                    WHERE
                    l0_.geo_level_id = 2 AND
                    l0_.province_id IS NOT NULL
                    ORDER BY l0_.pk_id";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Province For Dropout Report
     * @return type
     */
    public function getProvinceForDropoutReport() {
        $querypro = "SELECT DISTINCT
                    l0_.pk_id,
                    l0_.location_name
                    FROM
                    locations AS l0_
                    INNER JOIN locations AS dist ON dist.province_id = l0_.pk_id
                    INNER JOIN pilot_districts ON pilot_districts.district_id = dist.pk_id
                    WHERE
                    l0_.geo_level_id = 2 AND
                    l0_.province_id = 2
                    ORDER BY l0_.pk_id";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Conusmption Report Locations
     * @return type
     */
    public function conusmptionReportLocations() {
        $querypro = "SELECT DISTINCT
                    l0_.pk_id,
                    l0_.location_name
                    FROM
                    locations AS l0_
                    INNER JOIN locations AS dist ON dist.province_id = l0_.pk_id
                    INNER JOIN pilot_districts ON pilot_districts.district_id = dist.pk_id
                    WHERE
                    l0_.geo_level_id = 2 AND
                    l0_.pk_id = 2 AND
                    l0_.province_id IS NOT NULL
                    ORDER BY l0_.pk_id";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Devisional Locations
     * @return type
     */
    public function devisionalLocations() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId as pk_id,l.locationName as location_name')
                ->from("Locations", "l")
                ->where("l.geoLevel = 3 ")
                ->andWhere("l.province=$this->province_id");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * District Locations
     * @return type
     */
    public function districtLocations() {

        if ($this->form_values['office'] == '3') {

            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('l.pkId as pk_id,l.locationName as location_name')
                    ->from("Locations", "l")
                    ->where("l.geoLevel = '3' ")
                    ->andWhere("l.province= '" . $this->form_values['province_id'] . "' ");
        } else {
            if ($this->_identity->getRoleId() == 39) {
                $district_id = $this->_identity->getDistrictId();
                $str_sql = $this->_em_read->createQueryBuilder()
                        ->select('l.pkId as pk_id,l.locationName as location_name')
                        ->from("PilotDistricts", "pd")
                        ->join("pd.district", "l")
                        ->where("l.geoLevel = '4' ")
                        ->andWhere("l.district = '" . $district_id . "' ");
            } else {

                $str_sql = $this->_em_read->createQueryBuilder()
                        ->select('l.pkId as pk_id,l.locationName as location_name')
                        ->from("PilotDistricts", "pd")
                        ->join("pd.district", "l")
                        ->where("l.geoLevel = '4' ")
                        ->andWhere("l.province= '" . $this->form_values['province_id'] . "' ");
            }
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Locations By Level By District
     * @return boolean
     */
    public function getLocationsByLevelByDistrict() {
        if (!empty($this->form_values['district_id'])) {
            $district_id = $this->form_values['district_id'];
        } else {
            $district_id = '0';
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId as pk_id,l.locationName as location_name')
                ->from("Locations", "l")
                ->where("l.geoLevel = " . $this->form_values['geo_level_id'])
                ->andWhere("l.district = " . $district_id)
                ->orderBy("l.locationName", "ASC");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pk_id'], 'value' => $row['location_name']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Locations By Level By Tehsil
     * @return boolean
     */
    public function getLocationsByLevelByTehsil() {
        if (!empty($this->form_values['parent_id'])) {
            $district_id = $this->form_values['parent_id'];
        } else {
            $district_id = '0';
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId as pk_id,l.locationName as location_name')
                ->from("Locations", "l")
                ->where("l.geoLevel = '" . $this->form_values['geo_level_id'] . "' ")
                ->andWhere("l.parent = '" . $district_id . "' ")
                ->orderBy("l.locationName", "ASC");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pk_id'], 'value' => $row['location_name']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Locations By Level By Uc
     * @return boolean
     */
    public function getLocationsByLevelByUc() {
        if (!empty($this->form_values['parent_id'])) {
            $tehsil_id = $this->form_values['parent_id'];
        } else {
            $tehsil_id = '0';
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId as pk_id,l.locationName as location_name')
                ->from("Locations", "l")
                ->where("l.geoLevel = " . $this->form_values['geo_level_id'])
                ->andWhere("l.parent = " . $tehsil_id)
                ->orderBy("l.locationName", "ASC");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pk_id'], 'value' => $row['location_name']);
            }

            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Location Name
     * @return boolean
     */
    public function getLocationName() {
        if (!empty($this->form_values['pk_id'])) {
            $pk_id = $this->form_values['pk_id'];
        } else {
            $pk_id = '0';
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.locationName as location_name')
                ->from('Locations', 'l')
                ->where("l.pkId = " . $pk_id);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['location_name'];
        } else {
            return FALSE;
        }
    }

    /**
     * Tehsil Locations
     * @return type
     */
    public function tehsilLocations() {
        if (!empty($this->form_values['province_id'])) {
            $where[] = "l.province = '" . $this->form_values['province_id'] . "'";
        }
        if (!empty($this->form_values['district_id'])) {
            $where[] = "l.district = '" . $this->form_values['district_id'] . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId as pk_id,l.locationName as location_name')
                ->from("Locations", "l")
                ->where("l.geoLevel = 5 ")
                ->andWhere($where_s)
                ->orderBy("l.locationName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Tehsil Locations District
     * @return type
     */
    public function tehsilLocationsDistrict() {
        if (!empty($this->form_values['province_id'])) {
            $where[] = "l.province = '" . $this->form_values['province_id'] . "'";
        }
        if (!empty($this->form_values['district_id'])) {
            $where[] = "l.district = '" . $this->form_values['district_id'] . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId,l.locationName')
                ->from("Locations", "l")
                ->where("l.geoLevel = 5 ")
                ->andWhere($where_s)
                ->orderBy("l.locationName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Uc Locations
     * @return type
     */
    public function ucLocations() {
        if (!empty($this->form_values['province_id']) && $this->form_values['province_id'] != '0') {
            $where[] = "w.province = '" . $this->form_values['province_id'] . "'";
        }
        if (!empty($this->form_values['district_id']) && $this->form_values['district_id'] != '0') {
            $where[] = "w.district = '" . $this->form_values['district_id'] . "'";
        }
        if (!empty($this->form_values['tehsil_id']) && $this->form_values['tehsil_id'] != '0') {
            $where[] = "l.parent = '" . $this->form_values['tehsil_id'] . "'";
        }
        if (!empty($this->form_values['uc_id']) && $this->form_values['uc_id'] != '') {
            $where[] = "l.pkId = '" . $this->form_values['uc_id'] . "'";
        }
        $where[] = "l.geoLevel = 6";
        $where[] = "s.pkId = 1";
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        if (!empty($this->form_values['uc_id']) && $this->form_values['uc_id'] > 0) {
            $wh_name = "w.pkId as pk_id,w.warehouseName as location_name";
        } else {
            $wh_name = "l.pkId as pk_id,l.locationName AS location_name";
        }
        if (!empty($this->form_values['province_id'])) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("DISTINCT $wh_name,p.pkId as province")
                    ->from("Warehouses", "w")
                    ->join("w.location", "l")
                    ->join("l.parent", "p")
                    ->join("w.stakeholder", "s")
                    ->where($where_s)
                    ->andWhere("w.status = 1")
                    ->orderBy("s.pkId", "ASC");
            return $str_sql->getQuery()->getResult();
        }
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getAllLocations() {
        $form_values = $this->form_values;

        if (!empty($form_values['location_level'])) {
            $where[] = "gl.pk_id = '" . $form_values['location_level'] . "'";
        } else {
            $where[] = "gl.pk_id  = '3' ";
        }
        if (!empty($form_values['combo1'])) {
            $where[] = "l.province_id = '" . $form_values['combo1'] . "'";
        }
        if (!empty($form_values['combo2'])) {
            $where[] = "l.district_id  = '" . $form_values['combo2'] . "'";
        }
        if (!empty($form_values['combo3'])) {
            $where[] = "l.parent_id = '" . $form_values['combo3'] . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $sub_sql = "
SELECT l0_.pk_id AS pk_id0 FROM warehouses w1_ INNER JOIN locations l0_ ON w1_.location_id = l0_.pk_id WHERE w1_.status = 1
";


        if (!empty($form_values['not_used'])) {
            $su_sql = "And l.pk_id NOT IN ($sub_sql)";
        }
        if (!empty($form_values['location_name_hdn'])) {
            $location_name = $form_values['location_name_hdn'];
            $str_sql_order = "Order By l.location_name='$location_name' DESC,l.location_name";
        } else {
            $str_sql_order = "Order By l.location_name";
        }

        $str_sql = "SELECT
	l.pk_id AS pkId,
	l.location_name AS locationName,
	l.ccm_location_id AS ccmLocationId,
	gl.geo_level_name AS geoLevelName,
	lt.location_type_name AS locationTypeName,
	p.location_name AS parent,
        l.status
        FROM
                locations l
        INNER JOIN geo_levels gl ON l.geo_level_id = gl.pk_id
        INNER JOIN location_types lt ON l.location_type_id = lt.pk_id
        INNER JOIN locations p ON l.parent_id = p.pk_id
        WHERE  $where_s  $su_sql $str_sql_order";



        $rec = $this->_em_read->getConnection()->prepare($str_sql);




        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get All Locations Population
     * @return type
     */
    public function getAllLocationsPopulation() {

        $form_values = $this->form_values;

        if (!empty($form_values['location_level2'])) {
            $where[] = "gl.pkId = '" . $form_values['location_level2'] . "'";
        }
        if (!empty($form_values['year1'])) {
            $where[] = "YEAR(lp.estimationDate) = '" . $form_values['year1'] . "'";
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);

            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("lp.pkId AS locPopId,
                        lp.estimationDate,
                        l1.locationName,
                        l1.pkId AS LocId,
                        lp.population,
                        gl.geoLevelName,
                        l1.pkId AS locId,
                        pro.locationName as ProvName,
                        pro.pkId as ProvId")
                    ->from("LocationPopulations", 'lp')
                    ->join("lp.location", "l1")
                    ->join("l1.province", "pro")
                    ->join("l1.geoLevel", "gl")
                    ->where($where_s);
        } else {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("lp.pkId AS locPopId,
                        lp.estimationDate,
                        l1.locationName,
                        l1.pkId AS LocId,
                        lp.population,
                        gl.geoLevelName,
                        l1.pkId AS locId,
                        pro.locationName as ProvName,
                        pro.pkId as ProvId")
                    ->from("LocationPopulations", 'lp')
                    ->join("lp.location", "l1")
                    ->join("l1.province", "pro")
                    ->join("l1.geoLevel", "gl");
        }
//        print($str_sql->getQuery()->getSql());
//        exit;
//        print_r($str_sql->getQuery()->getResult());
//        exit;
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Provinces
     * @return type
     */
    public function getAllProvinces() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->where("l.parent = 10")
                ->andWhere("l.pkId != 10");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Province
     * 
     * @return type
     */
    public function getProvince() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->where("l.parent = 10")
                ->andWhere("l.pkId = 2");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Province
     * 
     * @return type
     */
    public function getProvinces() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->where("l.parent = 10");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Location
     * @return type
     */
    public function checkLocation() {
        $form_values = $this->form_values;
        if ($form_values['locLvl'] == 3) {
            $where = "l.province='" . $form_values['province'] . "' and l.geoLevel='3' ";
        }
        if ($form_values['locLvl'] == 4) {
            $where = "l.province='" . $form_values['province'] . "' and l.geoLevel='4' ";
        }
        if ($form_values['locLvl'] == 5) {
            $where = "l.district='" . $form_values['district'] . "' and l.geoLevel='5' ";
        }
        if ($form_values['locLvl'] == 6) {
            $where = "l.parent='" . $form_values['locid'] . "' and l.geoLevel='6' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.locationName")
                ->from('Locations', 'l')
                ->where("l.locationName= '" . $form_values['location_name_add'] . "' ")
                ->AndWhere($where);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Location Update
     * @return type
     */
    public function checkLocationUpdate() {
        $form_values = $this->form_values;
        $loc_Id = $form_values['loc_Id'];
        $where1 = "l.pkId NOT IN ($loc_Id)";

        if ($form_values['locLvl'] == 3) {
            $where = "l.province='" . $form_values['province'] . "' and l.geoLevel='3'   ";
        }
        if ($form_values['locLvl'] == 4) {
            $where = "l.province='" . $form_values['province'] . "' and l.geoLevel='4'   ";
        }
        if ($form_values['locLvl'] == 5) {
            $where = "l.district='" . $form_values['district'] . "' and l.geoLevel='5'   ";
        }
        if ($form_values['locLvl'] == 6) {
            $where = "l.parent='" . $form_values['locid'] . "' and l.geoLevel='6'  ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.locationName")
                ->from('Locations', 'l')
                ->where("l.locationName= '" . $form_values['location_name_update'] . "' ")
                ->AndWhere($where)
                ->Andwhere($where1);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Location Type
     * @return type
     */
    public function getLocationType() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationTypeName")
                ->from('LocationTypes', 'l')
                ->join('l.geoLevel', 'gl')
                ->where("gl.pkId= '" . $form_values . "' ");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Ccm Location Id
     * @return type
     */
    public function checkCcmLocationId() {
        $form_values = $this->form_values;
        if ($form_values['locLvl'] == 3) {
            $where = "l.province ='" . $form_values['province'] . "' and gl.pkId='3' and l.ccmLocationId='" . $form_values['ccm_location_id'] . "' ";
        }
        if ($form_values['locLvl'] == 4) {
            $where = "l.province='" . $form_values['province'] . "' and gl.pkId='4' and l.ccmLocationId='" . $form_values['ccm_location_id'] . "' ";
        }
        if ($form_values['locLvl'] == 5) {
            $where = "l.district='" . $form_values['district'] . "' and gl.pkId='5' and l.ccmLocationId='" . $form_values['ccm_location_id'] . "' ";
        }
        if ($form_values['locLvl'] == 6) {
            $where = "l.parent='" . $form_values['locid'] . "' and gl.pkId='6' and l.ccmLocationId='" . $form_values['ccm_location_id'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->join('l.geoLevel', 'gl')
                ->where($where);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Ccm Location Id Update
     * @return type
     */
    public function checkCcmLocationIdUpdate() {
        $form_values = $this->form_values;
        $loc_Id = $form_values['loc_Id'];
        $where1 = "l.pkId NOT IN ($loc_Id)";

        if ($form_values['locLvl'] == 3) {
            $where = "l.province ='" . $form_values['province'] . "' and gl.pkId='3' and l.ccmLocationId='" . $form_values['ccm_location_id_update'] . "' and l.locationName='" . $form_values['location_name_update'] . "' ";
        }
        if ($form_values['locLvl'] == 4) {
            $where = "l.province='" . $form_values['province'] . "' and gl.pkId='4' and l.ccmLocationId='" . $form_values['ccm_location_id_update'] . "'  and l.locationName='" . $form_values['location_name_update'] . "' ";
        }
        if ($form_values['locLvl'] == 5) {
            $where = "l.district='" . $form_values['district'] . "' and gl.pkId='5' and l.ccmLocationId='" . $form_values['ccm_location_id_update'] . "' and l.locationName='" . $form_values['location_name_update'] . "' ";
        }
        if ($form_values['locLvl'] == 6) {
            $where = "l.parent='" . $form_values['locid'] . "' and gl.pkId='6' and l.ccmLocationId='" . $form_values['ccm_location_id_update'] . "' and l.locationName='" . $form_values['location_name_update'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->join('l.geoLevel', 'gl')
                ->where($where)
                ->AndWhere($where1);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Stakeholder Geo Level
     * @return type
     */
    public function getStakeholderGeoLevel() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("gl.pkId,gl.geoLevelName")
                ->from('GeoLevels', 'gl')
                ->where('gl.pkId=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Office Geo Levels
     * @return type
     */
    public function getOfficeGeoLevels() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("gl.pkId,gl.geoLevelName")
                ->from('GeoLevels', 'gl')
                ->where('gl.pkId <> 1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Placement Locations
     * @uses api Barcode
     * @return type
     */
    public function getPlacementLocations($wh_id, $type) {
        if ($type == Model_Placements::LOCATIONTYPE_CCM) {
            $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                placement_locations.pk_id,
                placement_locations.location_type,
                placement_locations.location_barcode,
                placement_locations.location_id
                FROM
                placement_locations
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                WHERE
                cold_chain.warehouse_id = $wh_id AND
                placement_locations.location_type = $type");
        } else {
            $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                placement_locations.pk_id,
                placement_locations.location_type,
                placement_locations.location_barcode,
                placement_locations.location_id
                FROM
                placement_locations
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                WHERE
                non_ccm_locations.warehouse_id = $wh_id AND
                placement_locations.location_type = $type");
        }
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Rack Information
     * @uses api Barcode
     * @return type
     */
    public function getRackInformation() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
            rack_information.pk_id,
            rack_information.rack_type,
            rack_information.bin_net_capacity,
            rack_information.no_of_bins,
            rack_information.gross_capacity,
            rack_information.capacity_unit
            FROM
            rack_information");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get All Ucs By Campaign Id
     * @return type
     */
    public function getAllUcsByCampaignId() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName")
                ->from('Warehouses', 'w')
                ->join('w.district', 'd')
                ->join('w.stakeholder', 's')
                ->join('s.geoLevel', 'gl')
                ->where("s.pkId='" . Model_Stakeholders::CAMPAIGN . "' ")
                ->AndWhere('w.status=1')
                ->AndWhere('d.pkId=' . $this->form_values['district_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Locations
     * @return type
     */
    public function getLocations() {
        if (isset($this->form_values['dist_id'])) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("l.pkId,l.locationName")
                    ->from("PilotDistricts", "pd")
                    ->join("pd.district", "l")
                    ->join('l.province', 'p')
                    ->join('l.geoLevel', 'gl')
                    ->AndWhere('gl.pkId=5')
                    ->AndWhere('l.pkId=' . $this->form_values['dist_id'])
                    ->orderBy("l.locationName");
        } else {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("l.pkId,l.locationName")
                    ->from("PilotDistricts", "pd")
                    ->join("pd.district", "l")
                    ->join('l.province', 'p')
                    ->join('l.geoLevel', 'gl')
                    ->AndWhere('gl.pkId=4')
                    ->AndWhere('p.pkId=' . $this->form_values['province_id'])
                    ->orderBy("l.locationName");
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Location Wastages
     * @return type
     */
    public function getLocationWastages() {
        $prov = $this->form_values['province_id'];
        $district = $this->form_values['district_id'];
        if ($this->form_values['level_id'] == '2') {
            $str_sql = "SELECT
                                                        District.pk_id AS districtId,
                                                        District.location_name AS districtName,
                                                        COUNT(DISTINCT UC.pk_id) AS totalWH
                                                FROM
                                                        locations AS District
                                                INNER JOIN locations AS UC ON District.pk_Id = UC.district_id
                                                INNER JOIN warehouses ON UC.pk_id =  warehouses.location_id
                                                INNER JOIN pilot_districts ON District.pk_Id = pilot_districts.district_id
                                                WHERE
                                                         District.geo_level_id = 4
                                                        and warehouses.status = 1
                                                        AND UC.province_id = " . $prov . "

                                                GROUP BY
                                                        District.pk_id
                                                ORDER BY
                                                        districtId ASC";
        } else {
            $str_sql = "SELECT DISTINCT
        District.pk_id AS districtId,
        District.location_name AS districtName
        FROM
                locations AS District
        INNER JOIN locations AS UC ON District.pk_Id = UC.parent_id
        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
        WHERE
                District.geo_level_id = 5
        AND warehouses. STATUS = 1
        AND  UC.district_id = " . $district . "
        GROUP BY
                UC.pk_id
        ORDER BY
                districtId ASC";
        }

        $row = $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Provinces Name
     * @return type
     */
    public function getProvincesName() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                                        Province.pk_id,
                                        Province.location_name
                                FROM
                                        locations AS Province
                                WHERE
                                        Province.geo_level_id = 2
                                AND Province.parent_id IS NOT NULL
                                ORDER BY
                                        Province.pk_id ASC");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Province To Warehouse
     * @return type
     */
    public function getProvinceToWarehouse() {
        $prov_id = isset($this->form_values['combo']) ? $this->form_values['combo'] : '';
        $and = '';
        if (!empty($prov_id)) {
            $and = " AND warehouses.province_id = $prov_id";
        }
        if ($this->form_values['SkOfcLvl'] == 1) {
            $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                                        pk_id,
                                        warehouse_name,
                                        stakeholder_office_id
                                FROM
                                        warehouses
                                        WHERE stakeholder_office_id=" . $this->form_values['SkOfcLvl'] . "
                                        and warehouses.status = 1  
                                ORDER BY
                                        warehouse_name");
        } else if ($this->form_values['SkOfcLvl'] == 2) {
            $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                                        pk_id,
                                        warehouse_name,
                                        stakeholder_office_id
                                FROM
                                        warehouses
                                WHERE
                                         stakeholder_office_id=" . $this->form_values['SkOfcLvl'] . "
                                AND warehouses.status = 1  
                                AND warehouses.province_id IN (
                                        SELECT DISTINCT
                                                locations.province_id
                                        FROM
                                                locations

                                )
                                ORDER BY
                                        warehouse_name");
        } else if ($this->form_values['SkOfcLvl'] == 3) {
            $sql = "SELECT
                            warehouses.pk_id,
                            warehouses.warehouse_name,
                            warehouses.stakeholder_office_id
                    FROM
                            warehouses
               
                    WHERE
                            warehouses. STATUS = 1
                    AND stakeholder_office_id = " . $this->form_values['SkOfcLvl'] . " $and
                    ORDER BY
                            warehouse_name";
            $str_sql = $this->_em_read->getConnection()->prepare($sql);
        } else if ($this->form_values['SkOfcLvl'] > 2) {
            $sql = "SELECT
                            warehouses.pk_id,
                            warehouses.warehouse_name,
                            warehouses.stakeholder_office_id
                    FROM
                            warehouses
                    INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                    WHERE
                            warehouses. STATUS = 1
                    AND stakeholder_office_id = " . $this->form_values['SkOfcLvl'] . " $and
                    ORDER BY
                            warehouse_name";
            $str_sql = $this->_em_read->getConnection()->prepare($sql);
        }

        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Province Name
     * @return type
     */
    public function getProvinceName() {
        $str_sql = "SELECT DISTINCT
                        p.pk_id,
                        p.location_name
                FROM
                        locations
                INNER JOIN pilot_districts ON locations.district_id = pilot_districts.district_id
                INNER JOIN locations AS p ON p.pk_id = locations.province_id
                ORDER BY
                        p.pk_id ASC";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Get Ccm Locations Status
     * @param type $wh_id
     * @return boolean
     */
    public function getCcmLocationsStatus($wh_id) {

        $str_sql = "SELECT
            placement_locations.location_id,
            cold_chain.asset_id AS location_name,
           IF (
    ROUND(
                    abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
            ) = 0,
            NULL,
            item_pack_sizes.item_name
    ) AS item_name,

    IF (
            ROUND(
                    abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
            ) = 0,
            NULL,
            item_pack_sizes.pk_id
    ) AS item_id,
            ROUND(abs(Sum(placements.quantity)) / pack_info.quantity_per_pack) AS pack_quantity,
            abs(Sum(placements.quantity)) AS quantity
            FROM
            placement_locations
            LEFT JOIN placements ON placement_locations.pk_id = placements.placement_location_id
            INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
            LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id            
            WHERE
                    cold_chain.warehouse_id =" . $wh_id . "
            GROUP BY
                    cold_chain.pk_id,
            item_pack_sizes.pk_id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Non Ccm Locations Status
     * @param type $wh_id
     * @return boolean
     */
    public function getNonCcmLocationsStatus($wh_id) {

        $str_sql = "SELECT
                placement_locations.location_id,
                non_ccm_locations.location_name,
        IF (
                ROUND(
                        abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
                ) = 0,
                NULL,
                item_pack_sizes.item_name
        ) AS item_name,

        IF (
                ROUND(
                        abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
                ) = 0,
                NULL,
                item_pack_sizes.pk_id
        ) AS item_id,
         ROUND(
                abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
        ) AS pack_quantity,
         abs(Sum(placements.quantity)) AS quantity
        FROM
                placement_locations
        LEFT JOIN placements ON placement_locations.pk_id = placements.placement_location_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id 
        WHERE
                non_ccm_locations.warehouse_id = " . $wh_id . "
        GROUP BY
                non_ccm_locations.pk_id,
                item_pack_sizes.pk_id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Pilot Provinces
     * @return boolean
     */
    public function getPilotProvinces() {
        $str_sql = "SELECT DISTINCT
                        p.pk_id,
                        p.location_name
                FROM
                        locations
                INNER JOIN pilot_districts ON locations.district_id = pilot_districts.district_id
                INNER JOIN locations AS p ON p.pk_id = locations.province_id
                ORDER BY
                        p.pk_id ASC";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get District Name
     * @return type
     */
    public function getDistrictName() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->Where('l.pkId =' . $this->form_values['district_id']);

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Districts By Province
     * @return type
     */
    public function getDistrictsByProvince() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->where('l.geoLevel =4')
                ->andWhere('l.province =' . $this->form_values['province_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Tehsils By District
     * @return type
     */
    public function getTehsilsByDistrict() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->where('l.geoLevel =5')
                ->andWhere('l.district =' . $this->form_values['district_id'])
                ->orderBy("l.locationName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Ucs By Tehsil
     * @return type
     */
    public function getUcsByTehsil() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->where('l.geoLevel =6')
                ->andWhere('l.parent =' . $this->form_values['tehsil_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Sindh Districts
     * @return type
     */
    public function getSindhDistricts() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->where('l.geoLevel =4')
                ->andWhere('l.province =2');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Ucs By District
     * @return type
     */
    public function getUcsByDistrict() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->Where('l.district =' . $this->form_values['district_id'])
                ->andWhere('l.geoLevel =6')
                ->orderBy("l.locationName");

        return $str_sql->getQuery()->getResult();
    }
public function getUcsByDistrictSurvAdmin() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId,l.locationName")
                ->from('Locations', 'l')
                ->Where('l.province =2')
                ->andWhere('l.geoLevel =6')
                ->orderBy("l.locationName");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Ucs By District
     * @return type
     */
    public function getWarehousesByDistrict() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName")
                ->from('Warehouses', 'w')
                ->Where('w.district =' . $this->form_values['district_id'])
                ->andWhere('w.stakeholderOffice =6')
                ->orderBy("w.warehouseName");

        return $str_sql->getQuery()->getResult();
    }
 public function getWarehousesByDistrictSurvAdmin() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName")
                ->from('Warehouses', 'w')
                ->Where('w.province =2')
                ->andWhere('w.stakeholderOffice =6')
                ->orderBy("w.warehouseName");

        return $str_sql->getQuery()->getResult();
    }
    /**
     * Get Ucs By District
     * @return type
     */
    public function getSurSitesByDistrict() {
        $district_id = $this->form_values['district_id'];
        $str_sql = "SELECT
            sentinel_sites.pk_id,
            sentinel_sites.sentinel_site_name
            FROM
            sentinel_sites
            INNER JOIN locations ON sentinel_sites.location_id = locations.pk_id
            WHERE
            locations.district_id = '$district_id'";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }
public function getSurSitesByDistrictSurvAdmin() {
        $district_id = $this->form_values['district_id'];
        $str_sql = "SELECT
            sentinel_sites.pk_id,
            sentinel_sites.sentinel_site_name
            FROM
            sentinel_sites
            INNER JOIN locations ON sentinel_sites.location_id = locations.pk_id
            WHERE
            locations.province_id = 2";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }
    /**
     * Get Locations For Consumption Report
     * @return boolean
     */
    public function getLocationsForConsumptionReport() {
        $dist_id = $this->form_values['dist_id'];
        $year = $this->form_values['year'];
        if (!empty($this->form_values['dist_id'])) {
            $where = "  AND
                    locations.pk_id = $dist_id ";
        } else {
            $where = "";
        }
        $str_sql = "SELECT
                    ucs.pk_id as pk_id,
                    locations.location_name AS district,
                    tehsils.location_name AS tehsil,
                    ucs.location_name AS ucs,
                    ROUND(COALESCE(ROUND((((location_populations.population*1)/100*3.5)))/12,null,0)) AS target
                    FROM
                    locations
                    INNER JOIN locations AS tehsils ON locations.pk_id = tehsils.parent_id
                    INNER JOIN locations AS ucs ON tehsils.pk_id = ucs.parent_id
                    INNER JOIN warehouses ON ucs.pk_id = warehouses.location_id
                    INNER JOIN location_populations ON ucs.pk_id = location_populations.location_id
                    WHERE
                    locations.geo_level_id = 4 AND
                    YEAR(location_populations.estimation_date) = '$year' AND
                    locations.province_id = 2 $where
                    GROUP BY ucs.pk_id
                    ORDER BY tehsil,ucs";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Locations For Consumption Report 2
     * @return boolean
     */
    public function getLocationsForConsumptionReport2() {
        $str_sql = "SELECT
                    ucs.pk_id as pk_id,
                    locations.location_name AS district,
                    tehsils.location_name AS tehsil,
                    ucs.location_name AS ucs,
                    warehouse_population.facility_total_pouplation
                    FROM
                    locations
                    INNER JOIN locations AS tehsils ON locations.pk_id = tehsils.parent_id
                    INNER JOIN locations AS ucs ON tehsils.pk_id = ucs.parent_id
                    INNER JOIN warehouses ON ucs.pk_id = warehouses.location_id
                    LEFT  JOIN warehouse_population ON warehouses.pk_id = warehouse_population.warehouse_id
                    WHERE
                    locations.geo_level_id = 4 AND
                    locations.province_id = 2 
                    ORDER BY tehsil,ucs";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Location For Report
     * @return boolean
     */
    public function getLocationForReport() {
        $id = $this->form_values['dist_id'];
        $row = $this->_table->find($id);
        if (count($row) > 0) {
            return $row->getLocationName();
        } else {
            return false;
        }
    }

    /**
     * Get Batch Vvm Locations
     * @param type $batch_id
     * @return boolean
     */
    public function getBatchVvmLocations($batch_id) {
        $str_sql = "SELECT
                            cold_chain.asset_id AS location,
                            Sum(placements.quantity) AS placed_qty,
                            vvm_stages.vvm_stage_value,
                            vvm_stages.pk_id AS vvm_stage,
                            item_pack_sizes.vvm_group_id,
                            stock_batch_warehouses.pk_id as batch_id,
                            placement_locations.pk_id as location_id
                    FROM
                            placements
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                    INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id    
                    WHERE
                            placements.stock_batch_warehouse_id = $batch_id
                    AND placement_locations.location_type = " . Model_Placements::LOCATIONTYPE_CCM . "
                    GROUP BY
                            placements.vvm_stage,
                            placements.placement_location_id,
                            placements.stock_batch_warehouse_id";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Country List
     * @return boolean
     */
    public function getCountryList() {
        $str_sql = "SELECT
                        countries.id AS country_id,
                        countries.countryName AS country_name
                    FROM
                        countries";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Uc Wise Target List
     * @return type
     */
    public function getUcWiseTargetList() {
        if (!empty($this->form_values['province'])) {
            $where[] = "locations.province_id = '" . $this->form_values['province'] . "'";
        } else {
            $where[] = "locations.province_id = '" . 1 . "'";
        }
        if (!empty($this->form_values['district'])) {
            $where[] = "locations.district_id = '" . $this->form_values['district'] . "'";
        }
        if (!empty($this->form_values['year'])) {
            $y = $this->form_values['year'];
        } else {
            $y = '2016';
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_qry = "SELECT
                            district.location_name AS district_name,
                            locations.parent_id AS tehsil_id,
                            locations.location_name AS uc_name,
                            locations.province_id,
                            A.population,
                            A.target,
                            tehsil.location_name AS tehsil_name
                         FROM
                            (   SELECT
                                    locations.pk_id AS uc_id,
                                    locations.location_name AS uc_name,
                                    location_populations.population,
                                    locations.province_id,
                                    location_populations.estimation_date,
                                    ROUND( COALESCE ( ROUND( ( ( location_populations.population * 1 ) / 100 * 3.5 ) ), NULL, 0 ) ) AS target
                                FROM
                                    location_populations
                                    INNER JOIN locations ON location_populations.location_id = locations.pk_id
                                WHERE
                                    locations.geo_level_id = 6
                                    AND DATE_FORMAT( location_populations.estimation_date, '%Y' ) = '$y'
                                    AND $where_s
                             ) AS A
                                    RIGHT JOIN locations ON locations.pk_id = A.uc_id
                                    INNER JOIN locations AS district ON locations.district_id = district.pk_id
                                    INNER JOIN pilot_districts ON district.pk_id = pilot_districts.district_id
                                    INNER JOIN locations AS tehsil ON tehsil.pk_id = locations.parent_id
                                    WHERE
                                        locations.geo_level_id = 6
                                        AND $where_s ";

        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Warehouse Id By Location Id
     * @return boolean
     */
    public function getWarehouseIdByLocationId() {
        $location_id = $this->form_values['location_id'];
        $str_sql = "SELECT
                        warehouses.pk_id
                        
                    FROM
                        warehouses
                        where warehouses.location_id = $location_id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Sindh Towns Distrits
     * @return type
     */
    public function getSindhTownsDistrits() {
        $str_sql = "SELECT
	locations.location_name
FROM
	locations
INNER JOIN pilot_districts ON pilot_districts.district_id = locations.pk_id
WHERE
	locations.province_id = 2
AND pilot_districts.district_id <> 87
UNION
	SELECT
		locations.location_name
	FROM
		locations
	WHERE
		locations.province_id = 2
	AND locations.district_id = 87
	AND locations.geo_level_id = 5";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Get All Countries
     * @return type
     */
    public function getAllCountries() {
        $where = "";
        if (!empty($this->form_values['country_name'])) {
            $country_name = $this->form_values['country_name'];
            $where = "WHERE countries.countryName = '$country_name' ";
        }
        $str_sql = "
            SELECT
                countries.id,
                countries.countryName
            FROM
                countries
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Get Country By Id
     * @return type
     */
    public function getCountryById() {
        $where = "";
        if (!empty($this->form_values['country_id'])) {
            $country_id = $this->form_values['country_id'];
            $where = "WHERE countries.id = '$country_id' ";
        }
        $str_sql = "
            SELECT
                countries.id,
                countries.countryName
            FROM
                countries
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Add Country
     * @return boolean
     */
    public function addCountry() {
        if (!empty($this->form_values['country_name'])) {
            $country_name = $this->form_values['country_name'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            INSERT INTO countries 
                        (
                            countryName, created_by, created_date, modified_by, modified_date
                        )
                        VALUES
                       (
                            '$country_name',
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
     * Check Country
     * @return type
     */
    public function checkCountry() {
        $where = "";
        if (!empty($this->form_values['country_name'])) {
            $country_name = $this->form_values['country_name'];
            $country_name = trim($country_name);
            $where = "WHERE countries.countryName = '$country_name' ";
        }
        $this->form_values['country_name'];
        $str_sql = "
            SELECT
                countries.id,
                countries.countryName
            FROM
                countries
                $where
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    /**
     * Update Country
     * @return boolean
     */
    public function updateCountry() {
        if (!empty($this->form_values['country_id'])) {
            $country_id = $this->form_values['country_id'];
            $country_name = $this->form_values['country_name'];
        }
        $user_id = $this->_identity->getIdentity();
        $str_qry = "
            UPDATE countries 
                    SET
                        countryName = '$country_name',
                        created_by = $user_id,
                        created_date = NOW(),
                        modified_by = $user_id,
                        modified_date = NOW()

                    WHERE
                        id = '$country_id'  ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Delete Country
     * @return boolean
     */
    public function deleteCountry() {
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

    /**
     * National Report
     * @return type
     */
    public function locationsVaccineDistributionReport() {
        $form_values = $this->form_values;
        $warehouse = $form_values ['warehouse_id'];
        if ($form_values['wh_type'] == '1') {

            $geo_level_id = 2;
        }
        if ($form_values['wh_type'] == '2') {

            $geo_level_id = 4;
            $str_sql = "SELECT
                    warehouses.province_id
                    FROM
                    warehouses
                    WHERE
                    warehouses.pk_id = $warehouse";
            $rec = $this->_em_read->getConnection()->prepare($str_sql);
            $rec->execute();
            $res = $rec->fetchAll();

            $province_id = $res[0]['province_id'];
            $where = "l.province = $province_id";
        } else if ($form_values['wh_type'] == '4') {
            $geo_level_id = 5;
            $str_sql = "SELECT
                    warehouses.district_id
                    FROM
                    warehouses
                    WHERE
                    warehouses.pk_id = $warehouse";

            $rec = $this->_em_read->getConnection()->prepare($str_sql);
            $rec->execute();
            $res = $rec->fetchAll();
            $district_id = $res[0]['district_id'];
            $where = "l.district = $district_id";
        } else if ($form_values['wh_type'] == '5') {
            $geo_level_id = 6;
            $str_sql = "SELECT
                    warehouses.location_id
                    FROM
                    warehouses
                    WHERE
                    warehouses.pk_id = $warehouse";

            $rec = $this->_em_read->getConnection()->prepare($str_sql);
            $rec->execute();
            $res = $rec->fetchAll();
            $parent_id = $res[0]['location_id'];
            $where = "l.parent = $parent_id";
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT l.locationName AS location_name,
                        l.pkId AS pk_id')
                ->from("Locations", "l")
                ->where("l.geoLevel = $geo_level_id");
        if ($form_values['wh_type'] != '1') {
            $str_sql->andWhere("$where");
        }
        return $str_sql->getQuery()->getResult();
    }

}
