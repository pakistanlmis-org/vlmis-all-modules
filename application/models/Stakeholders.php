<?php

/**
 * Model_Stakeholders
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stakeholders
 */
class Model_Stakeholders extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;

    const TYPE_REPORTING = 1;
    const TYPE_SUPPLIER = 2;
    const TYPE_MANUFACTURER = 3;
    const CAMPAIGN = 40;
    const CAMPAIGN_DISTRICT = 43;
    const CAMPAIGN_TEAMS = 45;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Stakeholders');
    }

    /**
     * Nation Report
     * 
     * @return type
     */
    public function nationReport() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName")
                ->from("Stakeholders", "s")
                ->where("s.parent IS NULL")
                ->orderBy("s.listRank", "ASC");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Stock Availability Report
     * 
     * @return type
     */
    public function stockAvailabilityReport() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId as pk_id,s.stakeholderName as stakeholder_name")
                ->from("Stakeholders", "s")
                ->where("s.parent IS NULL")
                ->orderBy("s.listRank");
        return $str_sql->fetchArray();
    }

    /**
     * Get Stakholder
     * 
     * @return type
     */
    public function getStakholder() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId as pk_id,s.stakeholderName as stakeholder_name")
                ->from("Stakeholders", "s")
                ->where("s.stakeholderType = 1")
                ->orderBy("s.listRank");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Stakeholder Name
     * 
     * @return boolean
     */
    public function getStakeholderName() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId as pk_id,s.stakeholderName as stakeholder_name")
                ->from("Stakeholders", "s")
                ->where("s.pkId =" . $this->form_values['pk_id']);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['stakeholder_name'];
        } else {
            return FALSE;
        }
    }

    /**
     * Get All Stakeholders
     * 
     * @return boolean
     */
    public function getAllStakeholders() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName")
                ->from('Stakeholders', 's')
                ->join('s.geoLevel', 'gl')
                ->join('s.stakeholderType', 'st')
                ->where('gl.pkId=1')
                ->andWhere('st.pkId=1');
        if ($form_values['type'] == 1) {
            $str_sql->andWhere("s.pkId <> 40");
        }
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Manufacturer By Product
     * 
     * @return boolean
     */
    public function getManufacturerByProduct() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sip.pkId,s.stakeholderName")
                ->from('PackInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.stakeholder', 's')
                ->where('s.stakeholderType = ' . Model_Stakeholders::TYPE_MANUFACTURER)
                ->andWhere('pi.packagingLevel = 140')
                ->andWhere('sip.itemPackSize = ' . $this->form_values['item_id'])
                ->orderBy("s.listRank", "ASC");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Unaccociated Manufacturer
     * 
     * @param type $associated_array
     * @return boolean
     */
    public function getUnaccociatedManufacturer($associated_array) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sip.pkId,s.stakeholderName")
                ->from('PackInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.stakeholder', 's')
                ->where('s.stakeholderType = ' . Model_Stakeholders::TYPE_MANUFACTURER)
                ->andWhere('pi.packagingLevel = 140');

        if (is_array($associated_array)) {
            $in_sips = "'" . implode("','", $associated_array) . "'";
            $str_sql->andWhere("s.stakeholderName NOT IN ($in_sips)");
        }
        $str_sql->orderBy("s.listRank", "ASC")
                ->groupBy("s.stakeholderName");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Manufacturer By Product Set Up Barcode
     * 
     * @return boolean
     */
    public function getManufacturerByProductSetUpBarcode() {
        $item_id = $this->form_values['item_id'];

        $str_sql = "Select * from (
                (SELECT DISTINCT
                        s0_.pk_id AS pkId,
                        UPPER(s0_.stakeholder_name) AS stakeholderName,
                        s0_.list_rank
                FROM
                        stakeholder_item_pack_sizes s1_
                INNER JOIN stakeholders s0_ ON s1_.stakeholder_id = s0_.pk_id
                WHERE
                        s0_.stakeholder_type_id =  '" . Model_Stakeholders::TYPE_MANUFACTURER . "'
                AND s1_.item_pack_size_id = $item_id

                )
                UNION
                (SELECT DISTINCT
                        s0_.pk_id AS pkId,
                        s0_.stakeholder_name AS stakeholderName,
                        s0_.list_rank 
                FROM
                        stakeholder_item_pack_sizes s1_
                INNER JOIN stakeholders s0_ ON s1_.stakeholder_id = s0_.pk_id
                AND s1_.item_pack_size_id <> $item_id
                WHERE
                        s0_.stakeholder_type_id = '" . Model_Stakeholders::TYPE_MANUFACTURER . "')

) a 
";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $row = $rec->fetchAll();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Manufacturer
     * 
     * @return boolean
     */
    public function getManufacturer() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName")
                ->from('Stakeholders', 's')
                ->where('s.stakeholderType = ' . Model_Stakeholders::TYPE_MANUFACTURER);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Manufacturers
     * 
     * @return boolean
     */
    public function getAllManufacturers() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s")
                ->from('Stakeholders', 's')
                ->where('s.stakeholderType = ' . Model_Stakeholders::TYPE_MANUFACTURER)
                ->orderBy("s.stakeholderName");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Sectors
     * 
     * @return boolean
     */
    public function getAllSectors() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderSectorName")
                ->from('StakeholderSectors', 's');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Activities
     * 
     * @return boolean
     */
    public function getAllActivities() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.activity")
                ->from('StakeholderActivities', 's');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Stakeholders
     * 
     * @return type
     */
    public function getStakeholders() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName,gl.geoLevelName,ss.stakeholderSectorName,sa.activity")
                ->from('Stakeholders', 's')
                ->join('s.stakeholderType', 'st')
                ->join('s.stakeholderSector', 'ss')
                ->join('s.stakeholderActivity', 'sa')
                ->join('s.geoLevel', 'gl')
                ->where('st.pkId=1');
        if (!empty($this->form_values['stakeholderName'])) {
            $str_sql->AndWhere("s.stakeholderName = '" . $this->form_values['stakeholderName'] . "' ");
        }
        if (!empty($this->form_values['geo_level'])) {
            $str_sql->AndWhere("gl.pkId = '" . $this->form_values['geo_level'] . "' ");
        } else {
            $str_sql->AndWhere("gl.pkId = '1' ");
        }
        if (!empty($this->form_values['sector'])) {
            $str_sql->AndWhere("ss.pkId = '" . $this->form_values['sector'] . "' ");
        }
        if (!empty($this->form_values['activity'])) {
            $str_sql->AndWhere("sa.pkId = '" . $this->form_values['activity'] . "' ");
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Offices
     * 
     * @return type
     */
    public function getOffices() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName,gl.geoLevelName,ss.stakeholderSectorName,sa.activity")
                ->from('Stakeholders', 's')
                ->join('s.stakeholderType', 'st')
                ->join('s.stakeholderSector', 'ss')
                ->join('s.stakeholderActivity', 'sa')
                ->join('s.geoLevel', 'gl')
                ->join('s.mainStakeholder', 'ms');
        if (!empty($this->form_values['office'])) {
            $str_sql->AndWhere("s.stakeholderName = '" . $this->form_values['office'] . "' ");
        }
        if (!empty($this->form_values['geo_level'])) {
            $str_sql->AndWhere("gl.pkId = '" . $this->form_values['geo_level'] . "' ");
        } else {
            $str_sql->AndWhere("gl.pkId <> '1' ");
        }
        if (!empty($this->form_values['stakeholder'])) {
            $str_sql->AndWhere("ms.pkId = '" . $this->form_values['stakeholder'] . "' ");
        }


        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Manufacturers
     * 
     * @return type
     */
    public function getManufacturers() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName,gl.geoLevelName,ss.stakeholderSectorName,sa.activity")
                ->from('Stakeholders', 's')
                ->join('s.stakeholderType', 'st')
                ->join('s.stakeholderSector', 'ss')
                ->join('s.stakeholderActivity', 'sa')
                ->join('s.geoLevel', 'gl')
                ->join('s.mainStakeholder', 'ms')
                ->where('st.pkId=2')
                ->AndWhere("gl.pkId=1");
        if (!empty($this->form_values['manufacturer'])) {
            $str_sql->AndWhere("s.stakeholderName = '" . $this->form_values['manufacturer'] . "' ");
        }

        if (!empty($this->form_values['sector'])) {
            $str_sql->AndWhere("ss.pkId = '" . $this->form_values['sector'] . "' ");
        }


        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Stakeholders
     * 
     * @return type
     */
    public function checkStakeholders() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId")
                ->from('Stakeholders', 's')
                ->join('s.geoLevel', 'gl')
                ->join('s.stakeholderActivity', 'a')
                ->join('s.stakeholderSector', 'ss')
                ->where("s.stakeholderName= '" . $form_values['stakeholder_name'] . "' ")
                ->AndWhere("gl.pkId= '" . $form_values['geo_level'] . "' ")
                ->AndWhere("ss.pkId= '" . $form_values['sector'] . "' ")
                ->AndWhere("a.pkId= '" . $form_values['activity'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Office
     * 
     * @return type
     */
    public function checkOffice() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId")
                ->from('Stakeholders', 's')
                ->join('s.geoLevel', 'gl')
                ->join('s.stakeholderActivity', 'a')
                ->join('s.stakeholderSector', 'ss')
                ->join('s.mainStakeholder', 'ms')
                ->where("s.stakeholderName= '" . $form_values['office'] . "' ")
                ->AndWhere("gl.pkId= '" . $form_values['geo_level'] . "' ")
                ->AndWhere("ms.pkId= '" . $form_values['stakeholder'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Manufacturer Admin
     * 
     * @return type
     */
    public function checkManufacturerAdmin() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId")
                ->from('Stakeholders', 's')
                ->join('s.stakeholderSector', 'ss')
                ->where("s.stakeholderName= '" . $form_values['manufacturer'] . "' ")
                ->AndWhere("ss.pkId= '" . $form_values['sector'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Manufacturer
     * 
     * @return boolean
     */
    public function checkManufacturer() {
        $form_values = $this->form_values;
        $result = $this->_table->findOneBy(array("stakeholderType" => 3, "stakeholderName" => $form_values['name']));

        if (count($result) > 0) {
            return $result->getPkId();
        } else {
            return false;
        }
    }

    /**
     * Get Stakeholder Activities
     * 
     * @return type
     */
    public function getStakeholderActivities() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.activity")
                ->from('StakeholderActivities', 's');
        if (!empty($form_values['stakeholder_activity'])) {
            $str_sql->where("s.activity= '" . $form_values['stakeholder_activity'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Stakeholder Activity
     * 
     * @return type
     */
    public function checkStakeholderActivity() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId")
                ->from('StakeholderActivities', 's')
                ->where("s.activity= '" . $form_values . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Stakeholder Types
     * 
     * @return type
     */
    public function getStakeholderTypes() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderTypeName")
                ->from('StakeholderTypes', 's');
        if (!empty($form_values['stakeholder_type'])) {
            $str_sql->where("s.stakeholderTypeName= '" . $form_values['stakeholder_type'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Stakeholder Sectors
     * 
     * @return type
     */
    public function getStakeholderSectors() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderSectorName")
                ->from('StakeholderSectors', 's');
        if (!empty($form_values['stakeholder_sector'])) {
            $str_sql->where("s.stakeholderSectorName= '" . $form_values['stakeholder_sector'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Stakeholder Type
     * 
     * @return type
     */
    public function checkStakeholderType() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId")
                ->from('StakeholderTypes', 's')
                ->where("s.stakeholderTypeName= '" . $form_values . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Stakeholder Sector
     * 
     * @return type
     */
    public function checkStakeholderSector() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId")
                ->from('StakeholderSectors', 's')
                ->where("s.stakeholderSectorName= '" . $form_values . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Stakeholders Items
     * 
     * @return boolean
     */
    public function getAllStakeholdersItems() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName")
                ->from('Stakeholders', 's')
                ->where('s.pkId <> 1');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Add Stakeholder
     * 
     * @return boolean
     */
    public function addStakeholder() {
        $data = $this->form_values;
        $id = $this->checkManufacturer();
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);

        if (!$id) {
            $stakeholder = new Stakeholders();
            $stakeholder->setStakeholderName($data['name']);
            $type = $this->_em->getRepository("StakeholderTypes")->findOneBy(array("stakeholderTypeName" => 'Manufacturer'));
            $stakeholder->setStakeholderType($type);
            $main_stakeholder = $this->_em->getRepository("Stakeholders")->findOneBy(array("stakeholderName" => 'EPI Program'));
            $stakeholder->setMainStakeholder($main_stakeholder);
            $stakeholder_activity = $this->_em->getRepository("StakeholderActivities")->findOneBy(array("activity" => 'Routine'));
            $stakeholder->setStakeholderActivity($stakeholder_activity);
            $geo_level = $this->_em->getRepository("GeoLevels")->find("1");
            $stakeholder->setGeoLevel($geo_level);
            $stakeholder->setParent($main_stakeholder);
            $sector = $this->_em->find("StakeholderSectors", 1);
            $stakeholder->setStakeholderSector($sector);
            $stakeholder->setModifiedBy($created_by);
            $stakeholder->setModifiedDate(App_Tools_Time::now());
            $stakeholder->setCreatedBy($created_by);
            $stakeholder->setCreatedDate(App_Tools_Time::now());
            $this->_em->persist($stakeholder);
            $this->_em->flush();

            $id = $stakeholder->getPkId();
        }

        $check_stak_items = $this->_em->getRepository("StakeholderItemPackSizes")->findOneBy(array("stakeholder" => $id, "itemPackSize" => $data['item_id']));
        if (count($check_stak_items) == 0) {
            $stakeholder_items = new StakeholderItemPackSizes();
            $stakeholder_id = $this->_em->getRepository("Stakeholders")->find($id);
            $stakeholder_items->setStakeholder($stakeholder_id);
            $stakeholder_items->setQuantityPerPack($data['quantity']);
            $item_id = $this->_em->getRepository("ItemPackSizes")->find($data['item_id']);
            $stakeholder_items->setItemPackSize($item_id);
            $pck_id = $this->_em->getRepository("ListDetail")->find('140');
            $stakeholder_items->setPackagingLevel($pck_id);
            $stakeholder_items->setModifiedBy($created_by);
            $stakeholder_items->setModifiedDate(App_Tools_Time::now());
            $stakeholder_items->setCreatedBy($created_by);
            $stakeholder_items->setCreatedDate(App_Tools_Time::now());
            $this->_em->persist($stakeholder_items);
            $this->_em->flush();
        }

        return true;
    }

    /**
     * Get All Stakeholders
     * 
     * @return boolean
     */
    public function getAllStakeholdersUsers() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId,s.stakeholderName")
                ->from('Stakeholders', 's')
                ->join('s.geoLevel', 'gl')
                ->join('s.stakeholderType', 'st')
                ->where('gl.pkId=1')
                ->andWhere('st.pkId=1');

        $str_sql->andWhere("s.pkId <> 40");
        $str_sql->andWhere("s.pkId <> 0");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
