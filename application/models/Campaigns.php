<?php

/**
 * Model_Campaigns
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Campaigns
 */
class Model_Campaigns extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * Model_Campaigns __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Campaigns');
    }

    /**
     * Get All Campaigns
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllCampaigns($order = null, $sort = null) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT c.pkId, c.isClosed,'
                        . 'c.campaignName,c.dateFrom,'
                        . 'c.dateTo,c.catchUpDays,ct.camapignTypeName'
                )
                ->from("CampaignItemPackSizes", "cips")
                ->join("cips.campaign", "c")
                ->join("c.campaignType", "ct")
                ->where("c.campaignName IS NOT NULL");

        if (!empty($this->form_values['campaign_name'])) {
            $str_sql->andWhere("c.campaignName LIKE '%" . $this->form_values['campaign_name'] . "%'");
        }
        if (!empty($this->form_values['campaign_type_id'])) {
            $str_sql->andWhere("ct.pkId = " . $this->form_values['campaign_type_id']);
        }
        if (!empty($this->form_values['item_id'])) {
            $str_sql->andWhere("cips.itemPackSize = " . $this->form_values['item_id']);
        }
        if ($order == "pk_id") {
            $str_sql->orderBy("c.pkId", $sort);
        } elseif ($order == "campaign_name") {
            $str_sql->orderBy("c.campaignName", $sort);
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaigns By District
     */
    public function getCampaignsByDistrict() {
        if (!empty($this->form_values['districts'])) {
            $where = "  AND campaign_districts.district_id IN ( " . trim($this->form_values['districts'], ',') . ") ";
        } else {
            $where = "";
        }
        $str_qry = "SELECT DISTINCT
                            c0_.pk_id AS pkId,
                            c0_.campaign_name AS campaignName,
                            c0_.date_from AS dateFrom,
                            c0_.date_to AS dateTo
                    FROM
                            campaign_item_pack_sizes AS c3_
                    INNER JOIN campaigns AS c0_ ON c3_.campaign_id = c0_.pk_id
                    INNER JOIN item_pack_sizes AS i2_ ON c3_.item_pack_size_id = i2_.pk_id
                    INNER JOIN campaign_types AS c1_ ON c0_.campaign_type_id = c1_.pk_id
                    INNER JOIN campaign_districts ON c0_.pk_id = campaign_districts.campaign_id
                    WHERE
                    c0_.campaign_name IS NOT NULL $where
                   ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Campaigns By District Reports
     * @return type
     */
    public function getCampaignsByDistrictReports() {
        $str_qry = "SELECT 
                            c0_.pk_id AS pkId,
                            c0_.campaign_name AS campaignName
                    FROM
                     campaigns  c0_ 
                    INNER JOIN campaign_districts ON c0_.pk_id = campaign_districts.campaign_id
                    WHERE
                            c0_.campaign_name IS NOT NULL
                    AND campaign_districts.district_id IN ( " . $this->form_values['districts'] . ")";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Add Campaign
     */
    public function addCampaign() {
        $campaign = new Campaigns();
        $campaign->setCampaignName($this->form_values['campaign_name']);
        $campaign->setDateFrom(App_Tools_Time::now());
        $campaign->setDateTo(App_Tools_Time::now());
        $campaign->setCatchUpDays($this->form_values['catch_up_days']);
        $campaign_type_id = $this->_em->find('CampaignTypes', $this->form_values['campaign_type_id']);
        $campaign->setCampaignType($campaign_type_id);
        $campaign->setIsClosed('0');
        $created_by = $this->_em->find('Users', $this->_user_id);
        $campaign->setCreatedBy($created_by);
        $campaign->setCreatedDate(App_Tools_Time::now());
        $campaign->setModifiedBy($created_by);
        $campaign->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($campaign);
        $this->_em->flush();
        $last_id = $campaign->getPkId();
        $campaign_id = $this->_em->find('Campaigns', $last_id);

        foreach ($this->form_values['item_id'] as $item_id):
            $campaign_ips = new CampaignItemPackSizes();
            $campaign_ips->setCampaign($campaign_id);
            $item_id_ips = $this->_em->find('ItemPackSizes', $item_id);
            $campaign_ips->setItemPackSize($item_id_ips);
            $campaign_ips->setCreatedBy($created_by);
            $campaign_ips->setCreatedDate(App_Tools_Time::now());
            $campaign_ips->setModifiedBy($created_by);
            $campaign_ips->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($campaign_ips);
        endforeach;
        $this->_em->flush();
        foreach ($this->form_values['districts'] as $district_id):
            $campaign_d = new CampaignDistricts();
            $campaign_d->setCampaign($campaign_id);
            $district_location_id = $this->_em->find('Locations', $district_id);
            $campaign_d->setDistrict($district_location_id);
            $campaign_d->setCreatedBy($created_by);
            $campaign_d->setCreatedDate(App_Tools_Time::now());
            $campaign_d->setModifiedBy($created_by);
            $campaign_d->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($campaign_d);
        endforeach;
        $this->_em->flush();
        return $last_id;
    }

    /**
     * Get Campaign Vaccine Names
     * @return type
     */
    public function getCampaignVccNames() {
        $em = Zend_Registry::get('doctrine');
        $row = $this->_em_read->getConnection()->prepare("SELECT "
                . "GROUP_CONCAT(item_pack_sizes.item_name SEPARATOR '-') AS itemNames "
                . "FROM item_pack_sizes "
                . "WHERE item_pack_sizes.pk_id IN (" . $this->form_values['item_IDs'] . ")");
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Compaign Type Name
     * @return type
     */
    public function getCompaignTypeName() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ct.camapignTypeName')
                ->from("CampaignTypes", "ct")
                ->where("ct.pkId = " . $this->form_values['campaign_type_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign Districts
     * @return type
     */
    public function getCampaignDistricts() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('d.pkId, d.locationName,c.campaignName')
                ->from("CampaignDistricts", "cd")
                ->join("cd.district", "d")
                ->join("cd.campaign", "c")
                ->where("d.geoLevel = 4")
                ->andWhere("d.province = " . $this->form_values['province_id'] . "")
                ->andWhere("c.pkId = " . $this->form_values['campaign_id'] . "")
                ->orderBy("d.locationName", "ASC");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaigns By Province
     * @return type
     */
    public function getCampaignsByProvince() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT c.pkId,c.campaignName')
                ->from("CampaignDistricts", "cd")
                ->join("cd.district", "d")
                ->join("cd.campaign", "c")
                ->where("d.geoLevel = 4");
        if (!empty($this->form_values['province_id'])) {
            $str_sql->andWhere("d.province = " . $this->form_values['province_id'] . "");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaigns By District Report
     * @return type
     */
    public function getCampaignsByDistrictReport() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT c.pkId,c.campaignName')
                ->from("CampaignDistricts", "cd")
                ->join("cd.district", "d")
                ->join("cd.campaign", "c")
                ->where("d.geoLevel = 4");
        if (!empty($this->form_values['district_id'])) {
            $str_sql->andWhere("d.district = " . $this->form_values['district_id'] . "");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Campaigns National
     * @return type
     */
    public function getAllCampaignsNational() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT c.pkId,c.campaignName')
                ->from("Campaigns", "c");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign UCs For Data Entry
     * @return type
     */
    public function getCampaignUCsForDataEntry() {
        $sub_sql = $this->_em_read->createQueryBuilder()
                ->select('wh.pkId')
                ->from("CampaignData", "cd")
                ->innerJoin("cd.warehouse", 'wh')
                ->where("cd.campaign = " . $this->form_values['campaign_id'])
                ->andWhere("cd.campaignDay = " . $this->form_values['campaign_day']);
        if (!empty($this->form_values['district_id'])) {
            $sub_sql->andWhere("cd.district = " . $this->_identity->getDistrictId($this->_user_id));
        }
        $sub1_sql = $this->_em_read->createQueryBuilder()
                ->select('waa.pkId')
                ->from("CampaignTargets", "ct")
                ->join("ct.warehouse", "waa")
                ->where("ct.campaign = " . $this->form_values['campaign_id']);
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId, w.warehouseName, loc.pkId as location_id')
                ->from("Warehouses", "w")
                ->join("w.stakeholderOffice", "s")
                ->join("s.geoLevel", "gl")
                ->join("w.location", "loc")
                ->where("s.pkId =" . Model_Stakeholders::CAMPAIGN_TEAMS)
                ->andWhere("w.pkId NOT IN (" . $sub_sql->getQuery()->getDql() . ")")
                ->andWhere("w.pkId IN (" . $sub1_sql->getQuery()->getDql() . ")")
                ->andWhere("w.district = " . $this->_identity->getDistrictId($this->_user_id))
                ->andWhere("w.status=1")
                ->OrderBy("w.warehouseName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign UCs For Readiness
     * @return type
     */
    public function getCampaignUCsForReadiness() {
        if (!empty($this->form_values['campaign_id'])) {
            $sub1_sql = $this->_em_read->createQueryBuilder()
                    ->select('waa.pkId')
                    ->from("CampaignTargets", "ct")
                    ->join("ct.warehouse", "waa")
                    ->where("ct.campaign = " . $this->form_values['campaign_id']);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId, w.warehouseName, loc.pkId as location_id')
                ->from("Warehouses", "w")
                ->join("w.stakeholderOffice", "s")
                ->join("s.geoLevel", "gl")
                ->join("w.location", "loc")
                ->where("s.pkId =" . Model_Stakeholders::CAMPAIGN_TEAMS)
                ->andWhere("w.district = " . $this->_identity->getDistrictId($this->_user_id))
                ->andWhere("w.status=1");
        if (!empty($this->form_values['campaign_id'])) {
            $str_sql->andWhere("w.pkId IN (" . $sub1_sql->getQuery()->getDql() . ")");
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Campaign Items
     * @return type
     */
    public function campaignItems() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ips.pkId, ips.itemName')
                ->from("CampaignItemPackSizes", "cips")
                ->join("cips.itemPackSize", "ips")
                ->where("cips.campaign = " . $this->form_values['campaign_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign Days
     * @return type
     */
    public function getCampaignDays() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('c.dateFrom, c.dateTo,c.catchUpDays')
                ->from("Campaigns", "c")
                ->where("c.pkId = " . $this->form_values['campaign_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Districts
     */
    public function allDistricts() {
        $querypro = "SELECT
                        l.pk_id as pkId,l.location_name as locationName
                        FROM
                        locations l
                        WHERE
                        l.geo_level_id = 4 "
                . "ORDER BY locationName";
        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Districts Data
     * @return type
     */
    public function allDistrictsData() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('d.pkId')
                ->from("CampaignDistricts", "cd")
                ->join("cd.district", "d")
                ->where("cd.campaign =" . $this->form_values['campaign_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign Vaccines
     * @return type
     */
    public function campaignVaccines() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ips.pkId, ips.itemName')
                ->from("StakeholderItemPackSizes", 'si')
                ->join("si.itemPackSize", "ips")
                ->where("si.stakeholder = '" . Model_Stakeholders::CAMPAIGN . "' ")
                ->andWhere("ips.pkId <> 22")
                ->orderBy("ips.listRank", 'ASC');

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Campaigns
     * @return type
     */
    public function allCampaigns() {

        if ($this->_identity->getProvinceId() == 10) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('c.pkId, c.campaignName')
                    ->from("Campaigns", 'c')
                    ->orderBy("c.campaignName", 'ASC');

            return $str_sql->getQuery()->getResult();
        } else {
            $str_sql = "
                SELECT
                    campaigns.campaign_name campaignName,
                    campaigns.pk_id AS pkId
                FROM
                    locations
                INNER JOIN campaign_districts ON campaign_districts.district_id = locations.pk_id
                INNER JOIN campaigns ON campaign_districts.campaign_id = campaigns.pk_id
                
                ORDER BY campaign_name ASC
                ";

            $row = $this->_em_read->getConnection()->prepare($str_sql);

            $row->execute();
            return $row->fetchAll();
        }
    }

    /**
     * Get Campaign Entered Data
     * @return type
     */
    public function getCampaignEnteredData() {
        if ($this->form_values['campaign_day'] == 'all') {
            $str_sql = "SELECT campaign_data.pk_id,
                        campaign_data.campaign_id,
                        campaign_data.campaign_day,
                        SUM(campaign_data.daily_target) AS daily_target,
                        SUM(campaign_data.teams_reported) AS teams_reported,
                        SUM(campaign_data.target_age_six_months) AS target_age_six_months,
                        SUM(campaign_data.target_age_sixty_months) AS target_age_sixty_months,
                        SUM(campaign_data.total_coverage) AS total_coverage,
                        campaign_data.campaign_day,
                        campaign_data.district_id,
                        campaign_data.item_pack_size_id as item_id,
                        warehouses.pk_id as warehouses_id,
                        warehouses.warehouse_name
                FROM
                        campaign_data
                INNER JOIN warehouses ON campaign_data.warehouse_id = warehouses.pk_id
                WHERE
                        campaign_data.district_id = " . $this->form_values['district_id'] . "
                        AND campaign_data.campaign_id = " . $this->form_values['campaign_id'] . "
                        AND warehouses.status = 1    
                GROUP BY
                        campaign_data.district_id,
                        campaign_data.warehouse_id
                ORDER BY
                        warehouses.warehouse_name ASC
                        ";
        } else {
            $str_sql = "SELECT
                        campaign_data.pk_id,
                        campaign_data.campaign_id,
                        campaign_data.campaign_day,
                        campaign_data.daily_target,
                        campaign_data.teams_reported,
                        campaign_data.target_age_six_months,
                        campaign_data.target_age_sixty_months,
                        campaign_data.total_coverage,
                        campaign_data.campaign_day,
                        campaign_data.item_pack_size_id as item_id,
                        campaign_data.district_id,
                        warehouses.pk_id as warehouses_id,
                        warehouses.warehouse_name
                    FROM
                        campaign_data
                    INNER JOIN warehouses ON campaign_data.warehouse_id = warehouses.pk_id
                    WHERE
                        campaign_data.district_id = " . $this->form_values['district_id'] . "
                        AND campaign_data.campaign_day = " . $this->form_values['campaign_day'] . "
                        AND campaign_data.campaign_id = " . $this->form_values['campaign_id'] . "
                        AND warehouses.status = 1    
                    ORDER BY
                        warehouses.warehouse_name ASC";
        }

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Provinces
     * @return type
     */
    public function getProvinces() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId, l.locationName')
                ->from("Locations", 'l')
                ->where("l.geoLevel = 2")
                ->andWhere("l.parent IS NOT NULL");
        if ($this->_identity->getProvinceId() != 10 && !empty($this->form_values['province_id'])) {
            $str_sql->andWhere("l.pkId = " . $this->form_values['province_id']);
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get District Campaigns
     * @return type
     */
    public function districtCampaigns() {

        if (!empty($this->form_values['district_id'])) {
            $str_where = "where campaign_districts.district_id =" . $this->form_values['district_id'];
        }

        $str_sql = "SELECT
                                DISTINCT campaigns.campaign_name as campaignName,
                                campaigns.pk_id as pkId
                                FROM
                                campaign_districts
                                INNER JOIN campaign_targets ON campaign_targets.campaign_id = campaign_districts.campaign_id
                                INNER JOIN campaigns ON campaigns.pk_id = campaign_targets.campaign_id
                                $str_where                               
                                ORDER BY
                                pkId DESC";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Campaign Data Entry
     * @return type
     */
    public function getCampaignDataEntry() {

        if ($this->form_values['campaign_day'] == 'all') {
            $str_sql = "SELECT
                        warehouses.pk_id as warehouse_id,
                        warehouses.warehouse_name,
                        campaign_data.pk_id,
                        campaign_data.campaign_id,
                        campaign_data.item_pack_size_id,
                        campaign_data.campaign_day,
                        SUM(campaign_data.daily_target) AS daily_target,
                        SUM(campaign_data.target_age_six_months) AS target_age_six_months,
                        SUM(campaign_data.target_age_sixty_months) AS target_age_sixty_months,
                        SUM(campaign_data.household_visited) AS household_visited,
                        SUM(campaign_data.multiple_family_household) AS multiple_family_household,
                        SUM(campaign_data.total_coverage) AS total_coverage,
                        SUM(campaign_data.refusal_covered) AS refusal_covered,
                        SUM(campaign_data.record_reference) AS record_reference,
                        SUM(campaign_data.coverage_not_accessible) AS coverage_not_accessible,
                        SUM(campaign_data.record_not_accessible) AS record_not_accessible,
                        SUM(campaign_data.record_refusal) AS record_refusal,
                        SUM(campaign_data.coverage_mobile_children) AS coverage_mobile_children,
                        SUM(campaign_data.reported_with_weakness) AS reported_with_weakness,
                        SUM(campaign_data.zero_doses) AS zero_dose,
                        SUM(campaign_data.coverage_reference) AS coverage_reference,
                        SUM(campaign_data.inaccessible_coverage) AS inaccessible_coverage,
                        SUM(campaign_data.teams_reported) AS teams_reported,
                        SUM(campaign_data.vials_given) AS vials_given,
                        SUM(campaign_data.vials_used) AS vials_used,
                        SUM(campaign_data.vials_returned) AS vials_returned,
                        SUM(campaign_data.vials_expired) AS vials_expired,
                        SUM(campaign_data.recon_syr_wasted) AS recon_syr_wasted,
                        SUM(campaign_data.ad_syr_wasted) AS ad_syr_wasted,
                        SUM(campaign_data.district_id) AS district_id,
                        SUM(campaign_data.union_council_id) AS union_council_id,
                        SUM(campaign_data.campaign_target_id) AS campaign_target_id
                FROM campaign_data 
                INNER JOIN warehouses ON campaign_data.warehouse_id = warehouses.pk_id
                WHERE campaign_data.campaign_id = '" . $this->form_values['campaign_id'] . "'  
                and warehouses.status = 1";
            if (!empty($this->form_values['wh_id'])) {
                $str_sql .= " AND campaign_data.warehouse_id = " . $this->form_values['wh_id'];
            }
        } else {
            $str_sql = "SELECT
                        warehouses.pk_id as warehouse_id, warehouses.warehouse_name, campaign_data.pk_id campaign_data_pkId,campaign_data.campaign_id,
                        campaign_data.item_pack_size_id, campaign_data.campaign_day, campaign_data.daily_target,
                        campaign_data.target_age_six_months, campaign_data.target_age_sixty_months,
                        campaign_data.household_visited, campaign_data.multiple_family_household,
                        campaign_data.total_coverage, campaign_data.refusal_covered, campaign_data.record_reference,
                        campaign_data.coverage_not_accessible, campaign_data.record_not_accessible,campaign_data.record_refusal,
                        campaign_data.coverage_mobile_children, campaign_data.reported_with_weakness, campaign_data.zero_doses,
                        campaign_data.coverage_reference, campaign_data.inaccessible_coverage, campaign_data.teams_reported,
                        campaign_data.vials_given, campaign_data.vials_used, campaign_data.vials_returned,
                        campaign_data.vials_expired, campaign_data.recon_syr_wasted, campaign_data.ad_syr_wasted,
                        campaign_data.district_id, campaign_data.union_council_id, campaign_data.campaign_target_id,
                        campaign_data.created_by, campaign_data.created_date, campaign_data.modified_by, campaign_data.modified_date
                FROM campaign_data 
                INNER JOIN warehouses ON campaign_data.warehouse_id = warehouses.pk_id
                WHERE campaign_data.campaign_id = '" . $this->form_values['campaign_id'] . "'  
                   and warehouses.status = 1 ";
            if (!empty($this->form_values['campaign_day'])) {
                $str_sql.=" AND campaign_data.campaign_day = " . $this->form_values['campaign_day'];
            }
            if (!empty($this->form_values['district_id'])) {
                $str_sql.=" AND campaign_data.district_id = " . $this->form_values['district_id'];
            }
            if (!empty($this->form_values['wh_id'])) {
                $str_sql .=" AND campaign_data.warehouse_id = " . $this->form_values['wh_id'];
            }
        }

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Add Campaign Data
     */
    public function addCampaignData() {
        $form_values = $this->form_values;
        $campaign_data = new CampaignData();
        $campaign_id = $this->_em->find('Campaigns', $form_values['campaign_id']);
        $campaign_data->setCampaign($campaign_id);
        $item_id = $this->_em->find('ItemPackSizes', $form_values['item_id']);
        $campaign_data->setItemPackSize($item_id);
        list($warehouse_id, $location_id) = explode("_", $form_values['wh_id']);
        $wh_id = $this->_em->find('Warehouses', $warehouse_id);
        $campaign_data->setWarehouse($wh_id);
        $loc_id = $this->_em->find('Locations', $location_id);
        $campaign_data->setUnionCouncil($loc_id);
        $campaign_data->setCampaignDay($form_values['campaign_day']);
        $campaign_data->setDailyTarget($form_values['daily_target']);
        $campaign_data->setHouseholdVisited($form_values['household_visited']);
        $campaign_data->setMultipleFamilyHousehold($form_values['multiple_family_household']);
        $campaign_data->setTargetAgeSixMonths($form_values['target_age_six_months']);
        $campaign_data->setTargetAgeSixtyMonths($form_values['target_age_sixty_months']);
        $campaign_data->setTotalCoverage($form_values['total_coverage']);
        $campaign_data->setRefusalCovered($form_values['refusal_covered']);
        $campaign_data->setCoverageMobileChildren($form_values['coverage_mobile_children']);
        $campaign_data->setCoverageNotAccessible($form_values['coverage_not_accessible']);
        $campaign_data->setRecordNotAccessible($form_values['record_not_accessible']);
        $campaign_data->setRecordRefusal($form_values['record_refusal']);
        $campaign_data->setReportedWithWeakness($form_values['reported_with_weakness']);
        $campaign_data->setZeroDoses($form_values['zero_dose']);
        $campaign_data->setTeamsReported($form_values['teams_reported']);
        $campaign_data->setInaccessibleCoverage($form_values['inaccessible_coverage']);
        $campaign_data->setVialsGiven($form_values['vials_given']);
        $campaign_data->setVialsUsed($form_values['vials_used']);
        $campaign_data->setVialsReturned($form_values['vials_returned']);
        $campaign_data->setVialsExpired($form_values['vials_expired']);
        $campaign_data->setReconSyrWasted($form_values['recon_syr_wasted']);
        $campaign_data->setAdSyrWasted($form_values['ad_syr_wasted']);

        $user_id = $this->_em->find('Users', $this->_identity->getIdentity());
        $campaign_data->setCreatedDate(App_Tools_Time::now());
        $campaign_data->setCreatedBy($user_id);
        $campaign_data->setModifiedBy($user_id);
        $campaign_data->setModifiedDate(App_Tools_Time::now());

        $dist_id = $this->_em->find('Locations', $form_values['district_id']);
        $campaign_data->setDistrict($dist_id);

        $this->_em->persist($campaign_data);
        $this->_em->flush();
    }

    /**
     * Update Campaign Data
     */
    public function updateCampaignData() {

        $form_values = $this->form_values;

        $campaign_data = $this->_em->find('CampaignData', $form_values['campaign_pk_id']);


        $item_id = $this->_em->find('ItemPackSizes', $form_values['item_id']);


        $campaign_data->setItemPackSize($item_id);

        $wh_id = $this->_em->find('Warehouses', $form_values['wh_id']);
        $campaign_data->setWarehouse($wh_id);

        $campaign_data->setCampaignDay($form_values['campaign_day']);
        $campaign_data->setDailyTarget($form_values['daily_target']);

        $campaign_data->setHouseholdVisited($form_values['household_visited']);
        $campaign_data->setMultipleFamilyHousehold($form_values['multiple_family_household']);
        $campaign_data->setTargetAgeSixMonths($form_values['target_age_six_months']);
        $campaign_data->setTargetAgeSixtyMonths($form_values['target_age_sixty_months']);
        $campaign_data->setTotalCoverage($form_values['total_coverage']);
        $campaign_data->setRefusalCovered($form_values['refusal_covered']);
        $campaign_data->setCoverageMobileChildren($form_values['coverage_mobile_children']);
        $campaign_data->setCoverageNotAccessible($form_values['coverage_not_accessible']);
        $campaign_data->setRecordNotAccessible($form_values['record_not_accessible']);
        $campaign_data->setRecordRefusal($form_values['record_refusal']);
        $campaign_data->setReportedWithWeakness($form_values['reported_with_weakness']);
        $campaign_data->setZeroDoses($form_values['zero_dose']);

        $campaign_data->setTeamsReported($form_values['teams_reported']);
        $campaign_data->setInaccessibleCoverage($form_values['inaccessible_coverage']);
        $campaign_data->setVialsGiven($form_values['vials_given']);
        $campaign_data->setVialsUsed($form_values['vials_used']);
        $campaign_data->setVialsReturned($form_values['vials_returned']);
        $campaign_data->setVialsExpired($form_values['vials_expired']);
        $campaign_data->setReconSyrWasted($form_values['recon_syr_wasted']);
        $campaign_data->setAdSyrWasted($form_values['ad_syr_wasted']);

        $user_id = $this->_em->find('Users', $this->_identity->getIdentity());
        $campaign_data->setModifiedDate(App_Tools_Time::now());
        $campaign_data->setModifiedBy($user_id);

        $dist_id = $this->_em->find('Locations', $form_values['district_id']);
        $campaign_data->setDistrict($dist_id);

        $this->_em->persist($campaign_data);
        $this->_em->flush();
    }

    /**
     * Get Campaign Data WH
     * @return type
     */
    public function getCampaignDataWH() {
        $qry = "SELECT
                    campaign_data.warehouse_id,
                    campaign_data.campaign_id,
                    campaign_data.item_pack_size_id,
                    campaign_data.campaign_day,
                    MONTH(campaigns.date_from) AS `month`,
                    YEAR(campaigns.date_from) AS `year`,
                    hf_data_master.created_date
            FROM
                    campaign_data
            INNER JOIN campaigns ON campaign_data.campaign_id = campaigns.pk_id
            INNER JOIN hf_data_master ON campaign_data.warehouse_id = hf_data_master.warehouse_id
            WHERE
                    campaign_data.pk_id = " . $this->form_values['campaign_id'] . "
            LIMIT 1";

        $row = $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Delete Campaign Data
     * @return type
     */
    public function deleteCampaignData() {

        $campaign_data = $this->_em->getRepository("CampaignData")->find($this->form_values['campaign_id']);
        $this->_em->remove($campaign_data);

        return $this->_em->flush();
    }

    /**
     * Get Campaign Provinces
     * @return type
     */
    public function getCampaignProvinces() {
        $str_sql = "SELECT
                    Province.pk_id,
                    Province.location_name
                FROM
                    campaign_districts
                    INNER JOIN locations AS Districts ON campaign_districts.district_id = Districts.district_id
                    INNER JOIN locations AS Province ON Districts.province_id = Province.pk_id
                WHERE
                    campaign_districts.campaign_id = " . $this->form_values['campaign_id'] . "
                GROUP BY
                    Districts.province_id
                ORDER BY
                    Province.pk_id ASC";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Campaigns Lqas Data
     * @return type
     */
    public function getAllCampaignsLqasData() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('cld.pkId, cld.surveyor,cld.checked,cld.unvaccinated,cld.remarks,uc.warehouseName as locationName')
                ->from("CampaignLqasData", "cld")
                ->join("cld.campaign", "c")
                ->join("cld.unionCouncil", "uc")
                ->join("cld.district", "d");
        if (!empty($this->form_values['campaignId'])) {
            $str_sql->andWhere("c.pkId = " . $this->form_values['campaignId']);
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Reported Districts
     * @return type
     */
    public function getAllReportedDistricts() {
        $str_sql = "SELECT
                                    B.DistrictName,
                                    B.TotalUC,
                                    A.ReportedUC
                            FROM
                                    (
                                            SELECT
                                                    locations.pk_id AS DistrictId,
                                                    locations.location_name AS DistrictName,
                                                    COUNT(
                                                            DISTINCT campaign_data.warehouse_id
                                                    ) AS ReportedUC
                                            FROM
                                                    locations
                                            INNER JOIN warehouses ON locations.pk_id = warehouses.district_id
                                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                            LEFT JOIN campaign_data ON warehouses.pk_id = campaign_data.warehouse_id
                                            WHERE
                                                    stakeholders.geo_level_id = 6
                                            AND warehouses.stakeholder_id = " . Model_Stakeholders::CAMPAIGN . "
                                            AND campaign_data.campaign_id = " . $this->form_values['campaignId'] . "
                                            AND warehouses.status = 1
                                            GROUP BY
                                                    warehouses.district_id
                                            ORDER BY
                                                    locations.location_name ASC
                                    ) A
                            RIGHT JOIN (
                                    SELECT
                                            locations.pk_id AS DistrictId,
                                            locations.location_name AS DistrictName,
                                            COUNT(
                                                    DISTINCT warehouses.pk_id
                                            ) AS TotalUC
                                    FROM
                                            locations
                                    INNER JOIN warehouses ON locations.pk_id = warehouses.district_id
                                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                    WHERE
                                            stakeholders.geo_level_id = 6
                                    AND   warehouses.stakeholder_id = " . Model_Stakeholders::CAMPAIGN . "
                                    AND warehouses.status = 1    
                                    GROUP BY
                                            warehouses.district_id
                                    ORDER BY
                                            locations.location_name ASC
                            ) AS B ON A.DistrictId = B.DistrictId
                            ORDER BY
                                    B.DistrictName";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Reported Ucs
     * @return type
     */
    public function getAllReportedUcs() {
        $str_sql = " SELECT DISTINCT
                                        campaign_data.warehouse_id
                                FROM
                                        campaign_data
                                WHERE
                                        campaign_data.district_id = " . $this->form_values['district_id'];
        if (!empty($this->form_values['campaignId'])) {
            $str_sql .=" AND campaign_data.campaign_id = " . $this->form_values['campaignId'];
        }

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All District Ucs
     * @return type
     */
    public function getAllDistrictUcs() {
        $str_sql = "SELECT
                                        warehouses.pk_id,
                                        warehouses.warehouse_name
                                FROM
                                        warehouses
                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                INNER JOIN campaign_targets ON warehouses.pk_id = campaign_targets.warehouse_id 
                                WHERE
                                         warehouses.district_id = " . $this->form_values['district_id'] . "
                                AND warehouses.stakeholder_id = " . Model_Stakeholders::CAMPAIGN . "
                                AND campaign_targets.campaign_id = " . $this->form_values['campaign_id'] . "
                                 AND warehouses.status = 1   
                                ORDER BY
                                        warehouses.warehouse_name ASC";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Vaccince By Campaign Id
     * @return type
     */
    public function getVaccinceByCampaignId() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('cips.pkId,ips.itemName')
                ->from("CampaignItemPackSizes", 'cips')
                ->join("cips.itemPackSize", "ips")
                ->where("cips.campaign = '" . $form_values['campaign_id'] . "'");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Uc Target
     * @return type
     */
    public function getUcTarget() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ct.pkId,ct.dailyTarget')
                ->from("CampaignTargets", 'ct')
                ->where("ct.warehouse = '" . $form_values['warehouse_id'] . "'");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Vials Require By Campaign Id
     * @return type
     */
    public function getVialsRequireByCampaignId() {
        $form_values = $this->form_values;

        $querypro = "SELECT
        CEIL((IFNULL(Sum(campaign_targets.daily_target)/
        item_pack_sizes.number_of_doses,0))) as dailyTarget
        FROM
        campaign_targets
        INNER JOIN warehouses ON campaign_targets.warehouse_id = warehouses.pk_id
        INNER JOIN campaign_item_pack_sizes ON campaign_targets.campaign_id = campaign_item_pack_sizes.campaign_id
        INNER JOIN item_pack_sizes ON campaign_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
        campaign_targets.campaign_id = '" . $form_values['campaign_id'] . "' AND
        warehouses.district_id = '" . $this->_identity->getDistrictId($this->_identity->getIdentity()) . "' 
            AND warehouses.status = 1";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Vials Available By Campaign Id
     * @return type
     */
    public function getVialsAvailableByCampaignId() {

        $form_values = $this->form_values;
        $querypro_1 = "SELECT
        campaign_item_pack_sizes.item_pack_size_id
        FROM
        campaign_item_pack_sizes
        Where
        campaign_item_pack_sizes.campaign_id = '" . $form_values['campaign_id'] . "' 
        ";
        $row_1 = $this->_em_read->getConnection()->prepare($querypro_1);
        $row_1->execute();
        $result = $row_1->fetchAll();
        $item_pack_size_id = $result['0']['item_pack_size_id'];

        $querypro = "SELECT
        stock_batch_warehouses.quantity
        FROM
        warehouses
        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id       
        INNER JOIN stock_batch_warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.master_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        WHERE
        warehouses.district_id = '" . $this->_identity->getDistrictId($this->_identity->getIdentity()) . "' AND
        stakeholders.geo_level_id = 4 AND
        stakeholder_item_pack_sizes.item_pack_size_id = '" . $item_pack_size_id . "' 
        AND warehouses.status = 1";
        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Number Of Doses
     * @return type
     */
    public function getNumberOfDoses() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ips.numberOfDoses')
                ->from("ItemPackSizes", 'ips')
                ->where("ips.pkId = '" . $form_values['item_id'] . "'");
        return $str_sql->getQuery()->getResult();
    }
}