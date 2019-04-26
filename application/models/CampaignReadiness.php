<?php

/**
 * Model_CampaignReadiness
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Campaign Readiness
 * 
 * Inherits: Zend Form
 */

class Model_CampaignReadiness extends Model_Base {
    
    /**
     * Get Campaign Readiness
     * 
     * Public Function
     * 
     * @return type
     */
    public function getCampaignReadiness() {
        
        //to create query builder
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cr.pkId,cr.numTallySheets,cr.numFingerMarkers,cr.arrivalDateMobilizationMaterial,l.locationName as districtName,c.campaignName")
                ->from("CampaignReadiness", "cr")
                ->join("cr.district", "l")
                ->join("cr.campaign", "c")
                ->where("l.pkId=" . $this->_identity->getDistrictId($this->_identity->getIdentity()));
        if (!empty($this->form_values['campaignId'])) {
            $str_sql->andWhere("c.pkId=" . $this->form_values['campaignId']);
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Latest Campaign By District
     * 
     * Public function
     * To get Latest Campaign
     * By District
     * 
     * @return type
     */
    public function getLatestCampaignByDistrict() {

        $distirct_id = $this->_identity->getDistrictId($this->_identity->getIdentity());
        $str_sql = "SELECT
                                Max(campaigns.pk_id) as pkId
                                FROM
                                campaign_districts
                                INNER JOIN campaign_targets ON campaign_targets.campaign_id = campaign_districts.campaign_id
                                INNER JOIN campaigns ON campaigns.pk_id = campaign_targets.campaign_id
                                where campaign_districts.district_id = $distirct_id
                                ";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $rows = $row->fetchAll();
        return $rows['0']['pkId'];
    }

    /**
     * Get Campaign Readiness Uc
     * 
     * @return type
     */
    public function getCampaignReadinessUc() {

        $distirct_id = $this->_identity->getDistrictId($this->_identity->getIdentity());

        if (!empty($this->form_values['ucId']) && $this->form_values['ucId'] != "All") {

            $where[] = " warehouses.pk_id = '" . $this->form_values['ucId'] . "'";
        }
        if (!empty($this->form_values['campaignId'])) {

            $where[] = "campaign_targets.campaign_id  = '" . $this->form_values['campaignId'] . "'";
        }
        $where[] = " warehouses.district_id = '" . $distirct_id . "'";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = "SELECT
                            warehouses.pk_id as warehouse_id,
                            warehouses.warehouse_name AS ucName,
                            campaign_readiness_union_council.inaccessible_children AS inaccessibleChildren,
                            campaign_readiness_union_council.inaccessible_area AS inaccessibleArea,
                            campaign_readiness_union_council.number_mobile_teams AS numberMobileTeams,
                            campaign_readiness_union_council.number_fixed_teams AS numberFixedTeams,
                            campaign_readiness_union_council.number_transit_points AS numberTransitPoints,
                            campaign_readiness_union_council.aic_trained AS aicTrained,
                            campaign_readiness_union_council.number_teams_trained AS numberTeamsTrained,
                            campaign_readiness_union_council.mobile_population_areas AS mobilePopulationAreas,
                            campaign_readiness_union_council.upec_meeting_date AS upecMeetingDate,
                            campaign_readiness_union_council.pk_id as  pkId,
                            campaign_targets.campaign_id
                            FROM
                            warehouses
                            INNER JOIN campaign_targets ON campaign_targets.warehouse_id = warehouses.pk_id
                            LEFT JOIN campaign_readiness_union_council ON campaign_readiness_union_council.campaign_id = campaign_targets.campaign_id
                            AND campaign_readiness_union_council.union_council_id = campaign_targets.warehouse_id
                            WHERE
                            warehouses.stakeholder_id = 40 AND
                            $where_s";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

}
