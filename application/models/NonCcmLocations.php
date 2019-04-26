<?php

/**
 * Model_ItemPackSizes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Non CCM Locations
 */
class Model_NonCcmLocations extends Model_Base {

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
        $this->_table = $this->_em->getRepository('NonCcmLocations');
    }

    /**
     * Placement
     * 
     * @return int
     */
    public function placement() {
        $warehouse_id = $this->_identity->getWarehouseId();
        $form_values = $this->form_values;

        $created_by = $this->_em->find('Users', $this->_user_id);

        $non_ccm_location = new NonCcmLocations();
        $area = $this->_em->find("ListDetail", $form_values['area']);
        $row = $this->_em->find("ListDetail", $form_values['row']);
        $rack = $this->_em->find("ListDetail", $form_values['rack']);
        $pallet = $this->_em->find("ListDetail", $form_values['pallet']);
        $level = $this->_em->find("ListDetail", $form_values['level']);

        $non_ccm_location->setArea($area);
        $non_ccm_location->setRow($row);
        $non_ccm_location->setRack($rack);
        $non_ccm_location->setPallet($pallet);
        $non_ccm_location->setLevel($level);

        $locationName = $area->getListValue() . $row->getListValue() . $rack->getListValue() . $level->getListValue() . $pallet->getListValue();

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ncl.locationName')
                ->from("NonCcmLocations", "ncl")
                ->where("ncl.locationName = '$locationName'")
                ->andwhere("ncl.warehouse = '$warehouse_id'");
        $result = $str_sql->getQuery()->getResult();
        if (count($result) > 0) {
            return 0;
        } else {
            $non_ccm_location->setLocationName($locationName);
            $warehouse_id = $this->_identity->getWarehouseId();
            $warehouse = $this->_em->find("Warehouses", $warehouse_id);
            $non_ccm_location->setWarehouse($warehouse);


            $non_ccm_location->setCreatedBy($created_by);
            $non_ccm_location->setCreatedDate(App_Tools_Time::now());
            $non_ccm_location->setModifiedBy($created_by);
            $non_ccm_location->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($non_ccm_location);
            $this->_em->flush();
            $location_id = $non_ccm_location->getPkId();
            $placement_location = new PlacementLocations();
            $placement_location->setLocationId($location_id);
            $placement_location->setLocationBarcode($locationName);
            $loctype_id = $this->_em->find("ListDetail", Model_PlacementLocations::LOCATIONTYPE_NONCCM);
            $placement_location->setLocationType($loctype_id);

            $placement_location->setCreatedBy($created_by);
            $placement_location->setCreatedDate(App_Tools_Time::now());
            $placement_location->setModifiedBy($created_by);
            $placement_location->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($placement_location);
            $this->_em->flush();
            return 1;
        }
    }

    /**
     * Check Location
     * 
     * @param type $locationName
     * @return int
     */
    public function checkLocation($locationName) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ncl.locationName')
                ->from("NonCcmLocations", "ncl")
                ->where("ncl.locationName = '$locationName'");

        $result = $str_sql->getQuery()->getResult();
        if (count($result) <= 0) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * Get Locations Name
     * 
     * @return boolean
     */
    public function getLocationsName() {
        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
	non_ccm_locations.location_name AS locationName,
	non_ccm_locations.pk_id AS pkId,
	non_ccm_locations.warehouse_id
FROM
	placement_locations
INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
WHERE
	placement_locations.location_type = 100
AND non_ccm_locations.warehouse_id = $warehouse_id
ORDER BY
	locationName ASC";

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
     * Get dry store locations names.
     * 
     * @return boolean
     */
    public function getDryStoreLocationsName() {
        if (isset($this->form_values['wh_id']) && !empty($this->form_values['wh_id'])) {
            $warehouse_id = $this->form_values['wh_id'];
        } else {
            $warehouse_id = $this->_identity->getWarehouseId();
        }

        $str_sql = "SELECT
	non_ccm_locations.location_name AS asset_name,
	non_ccm_locations.pk_id,
	non_ccm_locations.warehouse_id,
	'\b' AS make_name,
	placement_locations.pk_id as plc_loc_id
        FROM
                non_ccm_locations
        INNER JOIN placement_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        WHERE
                non_ccm_locations.warehouse_id = $warehouse_id
        AND placement_locations.location_type = 100 ORDER BY non_ccm_locations.location_name ASC";

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
     * Get Bins
     * 
     * @return type
     */
    public function getBins() {
        return $this->_table->findBy($this->form_values);
    }

    /**
     * Get Non Ccm Locations
     * 
     * @return type
     */
    public function getNonCcmLocations() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ncl.pkId, ncl.locationName')
                ->from("NonCcmLocations", "ncl");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Update Placement
     * 
     * @return int
     */
    public function updatePlacement() {

        $form_values = $this->form_values;
        $non_ccm_location = $this->_table->find($form_values['placement_id']);

        $area = $this->_em->find("ListDetail", $form_values['area']);
        $row = $this->_em->find("ListDetail", $form_values['row']);
        $rack = $this->_em->find("ListDetail", $form_values['rack']);
        $rack_information_id = $this->_em->find("RackInformation", $form_values['rack_information_id']);
        $pallet = $this->_em->find("ListDetail", $form_values['pallet']);
        $level = $this->_em->find("ListDetail", $form_values['level']);

        $non_ccm_location->setArea($area);
        $non_ccm_location->setRow($row);
        $non_ccm_location->setRack($rack);
        $non_ccm_location->setRackInformation($rack_information_id);
        $non_ccm_location->setPallet($pallet);
        $non_ccm_location->setLevel($level);

        $locationName = $area->getListValue() . $row->getListValue() . $rack->getListValue() . $pallet->getListValue() . $level->getListValue();
        $non_ccm_location->setLocationName($locationName);

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ncl.locationName')
                ->from("NonCcmLocations", "ncl")
                ->where("ncl.locationName = '$locationName'");
        $result = $str_sql->getQuery()->getResult();
        $created_by = $this->_em->find('Users', $this->_user_id);
        if (count($result) > 0) {
            return 0;
        } else {
            $non_ccm_location->setLocationName($locationName);
            $warehouse_id = $this->_identity->getWarehouseId();
            $warehouse = $this->_em->find("Warehouses", $warehouse_id);
            $non_ccm_location->setWarehouse($warehouse);

            $non_ccm_location->setModifiedBy($created_by);
            $non_ccm_location->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($non_ccm_location);
            $this->_em->flush();
            $location_id = $non_ccm_location->getPkId();
            $placement_location = new PlacementLocations();
            $placement_location->setLocationId($location_id);
            $placement_location->setLocationBarcode($locationName);
            $loctype_id = $this->_em->find("ListDetail", Model_PlacementLocations::LOCATIONTYPE_NONCCM);
            $placement_location->setLocationType($loctype_id);

            $placement_location->setModifiedBy($created_by);
            $placement_location->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($placement_location);
            $this->_em->flush();
            return 1;
        }
    }

    /**
     * Location Status
     * 
     * @param type $area
     * @param type $lvl
     * @return boolean
     */
    public function locationStatus($area, $lvl = "") {
        $warehouse_id = $this->_identity->getWarehouseId();

        $str_sql1 = "SELECT
                        non_ccm_locations.location_name,
                        placement_locations.pk_id AS placement_locationsid,
                        placement_locations.location_type,
                        placement_locations.pk_id,
                        non_ccm_locations.pk_id,
                        rows.rank AS myrow,
                        rows.list_value AS myrowvalue,
                        Pallets.list_value AS mypallet,
                        racks.list_value AS myrack
                    FROM
                        non_ccm_locations
                    INNER JOIN placement_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                    INNER JOIN list_detail AS rows ON non_ccm_locations.`level` = rows.pk_id
                    INNER JOIN list_detail AS racks ON non_ccm_locations.rack = racks.pk_id
                    INNER JOIN list_detail AS Pallets ON non_ccm_locations.pallet = Pallets.pk_id
                    WHERE
                        placement_locations.location_type =" . Model_ListDetail::NON_CCM . "
                    AND non_ccm_locations.area = " . $area . "
                    AND non_ccm_locations.`row` = " . $lvl . "
                    AND non_ccm_locations.warehouse_id = " . $warehouse_id . "
                    ORDER BY
                        myrow,
                        myrack,
                        mypallet";
        $rec = $this->_em_read->getConnection()->prepare($str_sql1);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get All Dry Stores
     * 
     * @return boolean
     */
    public function getAllDryStores() {
        $warehouse_id = $this->_identity->getWarehouseId();

        $str_sql1 = "SELECT
                        non_ccm_locations.location_name as asset_id,
                        placement_locations.pk_id AS placement_location_id,
                        0 vvm_stage
                    FROM
                        non_ccm_locations
                    INNER JOIN placement_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                    INNER JOIN list_detail AS rows ON non_ccm_locations.`level` = rows.pk_id
                    INNER JOIN list_detail AS racks ON non_ccm_locations.rack = racks.pk_id
                    INNER JOIN list_detail AS Pallets ON non_ccm_locations.pallet = Pallets.pk_id
                    WHERE
                        placement_locations.location_type =" . Model_ListDetail::NON_CCM . "
                    AND non_ccm_locations.warehouse_id = " . $warehouse_id;

        $rec = $this->_em->getConnection()->prepare($str_sql1);

        $rec->execute();
        $result = $rec->fetchAll();

        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Max Row
     * 
     * @param type $area
     * @param type $lvl
     * @return boolean
     */
    public function getMaxRow($area, $lvl = "") {
        $warehouse_id = $this->_identity->getWarehouseId();

        $SQL = "SELECT ifnull(max(rows.rank),0) AS rows
                                FROM
                                non_ccm_locations
                                INNER JOIN list_detail AS rows ON non_ccm_locations.`row` = rows.pk_id
                                WHERE
                                        area=" . $area . " AND level=" . $lvl . " AND warehouse_id =" . $warehouse_id . "
                                GROUP BY
                                        non_ccm_locations.warehouse_id";

        $str_sql1 = $this->_em_read->getConnection()->prepare($SQL);
        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {
            return $result[0]['rows'];
        } else {
            return false;
        }
    }

    /**
     * Get Min Row
     * 
     * @return boolean
     */
    public function getMinRow() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
        min(list_detail.list_value)
        FROM
        non_ccm_locations
        INNER JOIN list_detail ON non_ccm_locations.`row` = list_detail.pk_id
        WHERE
        list_detail.list_master_id =" . Model_ListMaster::ROW);

        $str_sql->execute();
        $result = $str_sql->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Max Shelf
     * 
     * @param type $area
     * @param type $lvl
     * @return boolean
     */
    public function getMaxShelf($area, $lvl = "") {
        $warehouse_id = $this->_identity->getWarehouseId();

        $sql = "SELECT IFNULL(COUNT(DISTINCT rack.list_value),0) AS shelf
                                FROM
                                non_ccm_locations
                                INNER JOIN list_detail AS rack ON non_ccm_locations.`level` = rack.pk_id
                                WHERE
                                        area=" . $area . " AND `row` = " . $lvl . " AND warehouse_id =" . $warehouse_id . "
                                GROUP BY
                                        non_ccm_locations.warehouse_id";

        $str_sql1 = $this->_em_read->getConnection()->prepare($sql);

        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {

            return $result[0]['shelf'];
        } else {
            return false;
        }
    }

    /**
     * Get Max Rack
     * 
     * @param type $area
     * @param type $lvl
     * @return boolean
     */
    public function getMaxRack($area, $lvl = "") {
        $warehouse_id = $this->_identity->getWarehouseId();

        $sql = "SELECT IFNULL(COUNT(DISTINCT rack.list_value),0) AS racks
                                FROM
                                non_ccm_locations
                                INNER JOIN list_detail AS rack ON non_ccm_locations.`rack` = rack.pk_id
                                WHERE
                                        area=" . $area . " AND `row` = " . $lvl . " AND warehouse_id =" . $warehouse_id . "
                                GROUP BY
                                        non_ccm_locations.warehouse_id";
        $str_sql1 = $this->_em_read->getConnection()->prepare($sql);

        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {

            return $result[0]['racks'];
        } else {
            return false;
        }
    }

    /**
     * Get Min Rack
     * 
     * @return boolean
     */
    public function getMinRack() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
        min(list_detail.list_value)
        FROM
        non_ccm_locations
        INNER JOIN list_detail ON non_ccm_locations.`rack` = list_detail.pk_id
        WHERE
        list_detail.list_master_id =" . Model_ListMaster::RACK);

        $str_sql->execute();
        $result = $str_sql->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Max Pallet
     * 
     * @return boolean
     */
    public function getMaxPallet() {

        $str_sql1 = $this->_em_read->getConnection()->prepare("SELECT
        max(list_detail.list_value) as maxVal
        FROM
        non_ccm_locations
        INNER JOIN list_detail ON non_ccm_locations.`pallet` = list_detail.pk_id
        WHERE
        list_detail.list_master_id =" . Model_ListMaster::PALLET);
        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {
            return $result[0]['maxVal'];
        } else {
            return false;
        }
    }

    /**
     * Get Min Pallet
     * 
     * @return boolean
     */
    public function getMinPallet() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
        min(list_detail.list_value)
        FROM
        non_ccm_locations
        INNER JOIN list_detail ON non_ccm_locations.`pallet` = list_detail.pk_id
        WHERE
        list_detail.list_master_id =" . Model_ListMaster::PALLET);

        $str_sql->execute();
        $result = $str_sql->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Non Ccm Location Id
     * 
     * @param type $bin_id
     * @return boolean
     */
    public function getNonCcmLocationId($bin_id) {
        $str_sql = "SELECT
        placement_locations.location_id
        FROM
        placement_locations
        WHERE
        placement_locations.pk_id =" . $bin_id;

        $str_sql1 = $this->_em_read->getConnection()->prepare($str_sql);

        $str_sql1->execute();
        $result = $str_sql1->fetchAll();
        if (count($result) > 0) {

            return $result[0]['location_id'];
        } else {
            return false;
        }
    }
    /*
      * Get dry store locations names.
     * 
     * @return boolean
     */
    public function getDryStoreLocationsNameByShipments() {
        if (isset($this->form_values['wh_id']) && !empty($this->form_values['wh_id'])) {
            $warehouse_id = $this->form_values['wh_id'];
        } else {
            $warehouse_id = $this->_identity->getWarehouseId();
        }

        $str_sql = "SELECT
	non_ccm_locations.location_name AS asset_name,
	non_ccm_locations.pk_id,
	non_ccm_locations.warehouse_id,
	'\b' AS make_name,
	placement_locations.pk_id as plc_loc_id
        FROM
                non_ccm_locations
        INNER JOIN placement_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        WHERE
                non_ccm_locations.warehouse_id = $warehouse_id
        AND placement_locations.location_type = 100 ORDER BY non_ccm_locations.location_name ASC";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return json_encode($result);
        } else {
            return false;
        }
    }

}
