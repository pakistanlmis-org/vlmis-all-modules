<?php

/**
 * Model_CampaignData
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Campaign Data
 */
class Model_CampaignData extends Model_Base {

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
        $this->_table = $this->_em->getRepository('CampaignData');
    }

    /**
     * Get Coverage Report
     * Used to get coverage report.
     * @return type
     */
    public function getCoverageReport() {
        // Create query.
        $str_qry = "SELECT
                    locations.location_name,
                    SUM(campaign_data.daily_target) AS daily_target,
                    SUM(campaign_data.teams_reported) AS teams_reported,
                    SUM(campaign_data.target_age_six_months) AS target_age_six_months,
                    SUM(campaign_data.target_age_sixty_months) AS target_age_sixty_months,
                    SUM(campaign_data.record_not_accessible) AS record_not_accessible,
                    SUM(campaign_data.record_refusal) AS record_refusal,
                    SUM(campaign_data.coverage_not_accessible) AS coverage_not_accessible,
                    SUM(campaign_data.refusal_covered) AS refusal_covered,
                    SUM(campaign_data.coverage_mobile_children) AS coverage_mobile_children,
                    SUM(campaign_data.total_coverage) AS total_coverage
            FROM
                campaign_data
            INNER JOIN locations ON campaign_data.district_id = locations.pk_id
            INNER JOIN campaigns ON campaign_data.campaign_id = campaigns.pk_id";

        // Check combo and year.
        if (!empty($this->form_values['combo1_add']) || !empty($this->form_values['year'])) {
            if (!empty($this->form_values['combo1_add'])) {
                $str_qry .= " AND locations.province_id = " . $this->form_values['combo1_add'];
            }
            if (!empty($this->form_values['year'])) {
                $str_qry .= " AND YEAR(campaigns.date_from) = '" . $this->form_values['year'] . "'";
            }
        }

        // Set group by.
        $str_qry .= "
            GROUP BY
                campaign_data.district_id
                    ";

        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Campaign Detail
     * Used to get campaign details.
     *
     * @return type
     */
    public function getCampaignDetail() {
        // Create query.
        $str_qry = "SELECT
                        Province.location_name AS Province,
                        District.location_name AS District,
                        Tehsil.location_name AS Tehsil,
                        UC.location_name AS UC,
                        SUM(campaign_data.daily_target) AS daily_target,
                        SUM(campaign_data.teams_reported) AS teams_reported,
                        SUM(campaign_data.target_age_six_months) AS target_age_six_months,
                        SUM(campaign_data.target_age_sixty_months) AS target_age_sixty_months,
                        SUM(campaign_data.record_not_accessible) AS record_not_accessible,
                        SUM(campaign_data.record_refusal) AS record_refusal,
                        SUM(campaign_data.coverage_not_accessible) AS coverage_not_accessible,
                        SUM(campaign_data.refusal_covered) AS refusal_covered,
                        SUM(campaign_data.coverage_mobile_children) AS coverage_mobile_children,
                        SUM(campaign_data.total_coverage) AS total_coverage
                FROM
                    locations AS Province
                INNER JOIN locations AS District ON Province.pk_id = District.province_id
                INNER JOIN locations AS Tehsil ON District.pk_id = Tehsil.parent_id
                INNER JOIN locations AS UC ON Tehsil.pk_id = UC.parent_id
                INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                INNER JOIN campaign_data ON warehouses.pk_id = campaign_data.warehouse_id
                INNER JOIN campaigns ON campaign_data.campaign_id = campaigns.pk_id
                WHERE
                    warehouses.stakeholder_id = '" . Model_Stakeholders::CAMPAIGN . "'
                    and warehouses.status = 1 ";
        if (!empty($this->form_values['combo1_add'])) {
            $str_qry .= " AND Province.pk_id = " . $this->form_values['combo1_add'];
        }
        if (!empty($this->form_values['combo2_add'])) {
            $str_qry .= " AND District.pk_id = " . $this->form_values['combo2_add'];
        }
        if (!empty($this->form_values['year'])) {
            $str_qry .= " AND YEAR(campaigns.date_from) = '" . $this->form_values['year'] . "'";
        }
        if (!empty($this->form_values['campaign_id'])) {
            $str_qry .= " AND campaigns.pk_id = " . $this->form_values['campaign_id'];
        }

        // Set group by.
        $str_qry .= "
                GROUP BY
                    UC.pk_id";
        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Coverage Catch Up
     * Used to get coverage catch up data.
     * @return type
     */
    public function getCoverageCatchUp() {
        // Create query.
        $str_qry = "SELECT
                    Province.location_name AS Province,
                    District.location_name AS District,
                    Tehsil.location_name AS Tehsil,
                    UC.location_name AS UC,
                    SUM(campaign_data.daily_target) - SUM(campaign_data.total_coverage) AS stillMissedChilds,
                    SUM(campaign_data.record_not_accessible)  - SUM(campaign_data.coverage_not_accessible) AS stillNA,
                    SUM(campaign_data.record_refusal) - SUM(campaign_data.refusal_covered) AS stillRefusal,
                    Sum(campaign_data.coverage_not_accessible) AS coverage_not_accessible,
                    Sum(campaign_data.refusal_covered) AS refusal_covered,
                    SUM(IF(campaign_data.campaign_day >= (DATEDIFF(campaigns.date_to, campaigns.date_from) + 1), campaign_data.total_coverage, 0)) AS catchUPCoverage
                FROM
                    locations AS Province
                INNER JOIN locations AS District ON Province.pk_id = District.province_id
                INNER JOIN locations AS Tehsil ON District.pk_id = Tehsil.parent_id
                INNER JOIN locations AS UC ON Tehsil.pk_id = UC.parent_id
                INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                INNER JOIN campaign_data ON warehouses.pk_id = campaign_data.warehouse_id
                INNER JOIN campaigns ON campaign_data.campaign_id = campaigns.pk_id
                WHERE
                    warehouses.stakeholder_id = '" . Model_Stakeholders::CAMPAIGN . "' and warehouses.status = 1";

        if (!empty($this->form_values['combo1_add'])) {
            $str_qry .= " AND Province.pk_id = " . $this->form_values['combo1_add'];
        }
        if (!empty($this->form_values['combo2_add'])) {
            $str_qry .= " AND District.pk_id = " . $this->form_values['combo2_add'];
        }
        if (!empty($this->form_values['year'])) {
            $str_qry .= " AND YEAR(campaigns.date_from) = '" . $this->form_values['year'] . "'";
        }

        // Set group by.
        $str_qry .= "
                GROUP BY
                    UC.pk_id
                    ";

        // Execute and get result data.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get LQAS Report
     * Used to get LQAS report.
     * @return type
     */
    public function getLQASReport() {
        $form_values = $this->form_values;

        // Get level.
        $level = $form_values['level'];
        $str_qry = '';

        // Check levels.
        switch ($level) {
            case 1:
                $where = '';
                if (!empty($form_values['campaign_id'])) {
                    $where = "WHERE
                      campaign_lqas_data.campaign_id = " . $form_values['campaign_id'] . "";
                }
                $str_qry = "SELECT
                    B.pk_id,
                    B.location_name,
                    SUM(IF(A.unvaccinated <= 3,1,0)) as passed,
                    SUM(IF(A.unvaccinated >= 4 AND A.unvaccinated <= 8,1,0)) as intermediate,
                    SUM(IF(A.unvaccinated >= 9,1,0)) as failed,
                    ROUND(A.checked/60) as lots_assessed,
                    ROUND(((A.checked-A.unvaccinated)/A.checked)*100) as vaccinatedPer,
                    ROUND((A.total_coverage/A.daily_target)*100) as rptDataVaccinatedPer
                   FROM
                    (
                     SELECT
                     Province.pk_id,
                     Sum(campaign_lqas_data.checked) AS checked,
                     SUM(campaign_lqas_data.unvaccinated) AS unvaccinated,
                     campaign_data.daily_target,
                     campaign_data.total_coverage
                     FROM
                     campaign_lqas_data
                     INNER JOIN campaign_data ON campaign_lqas_data.campaign_id = campaign_data.pk_id
                     INNER JOIN locations AS District ON campaign_lqas_data.district_id = District.pk_id
                     INNER JOIN locations AS Province ON District.parent_id = Province.pk_id
                     $where
                     GROUP BY
                      Province.pk_id
                    ) A
                   RIGHT JOIN (
                   SELECT
                    locations.pk_id,
                    locations.location_name
                   FROM
                    locations
                   WHERE
                    locations.geo_level_id = 2
                   ) B
                   ON A.pk_id = B.pk_id
                   GROUP BY
                   B.pk_id";
                break;
            case 2:
                $where = '';
                if (!empty($form_values['campaign_id'])) {
                    $arr_where[] = "campaign_lqas_data.campaign_id = " . $form_values['campaign_id'] . "";
                }
                if (!empty($form_values['province_id'])) {
                    $arr_where[] = "Province.pk_id = " . $form_values['province_id'] . "";
                    $prov_where = " AND locations.province_id = " . $form_values['province_id'] . "";
                }
                if (is_array($arr_where)) {
                    $where = " WHERE " . implode(" AND ", $arr_where);
                }
                $str_qry = "SELECT
                    B.pk_id,
                    B.location_name,
                    SUM(IF(A.unvaccinated <= 3,1,0)) as passed,
                    SUM(IF(A.unvaccinated >= 4 AND A.unvaccinated <= 8,1,0)) as intermediate,
                    SUM(IF(A.unvaccinated >= 9,1,0)) as failed,
                    ROUND(A.checked/60) as lots_assessed,
                    ROUND(((A.checked-A.unvaccinated)/A.checked)*100) as vaccinatedPer,
                    ROUND((A.total_coverage/A.daily_target)*100) as rptDataVaccinatedPer
                   FROM
                    (
                     SELECT
                      District.pk_id,
                      Sum(campaign_lqas_data.checked) AS checked,
                      SUM(campaign_lqas_data.unvaccinated) AS unvaccinated,
                      campaign_data.daily_target,
                      campaign_data.total_coverage
                     FROM
                      campaign_lqas_data
                     INNER JOIN campaign_data ON campaign_lqas_data.campaign_id = campaign_data.pk_id
                     INNER JOIN locations AS District ON campaign_lqas_data.district_id = District.pk_id
                     INNER JOIN locations AS Province ON District.parent_id = Province.pk_id
                     " . $where . "
                     GROUP BY
                      District.pk_id
                    ) A
                   RIGHT JOIN (
                   SELECT
                    locations.pk_id,
                    locations.location_name
                   FROM
                    locations
                   WHERE
                    locations.geo_level_id = 4

                    $prov_where
                   ) B
                   ON A.pk_id = B.pk_id
                   GROUP BY
                   B.pk_id";
                break;
            default :
                break;
        }

        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Coverage Missed Children
     * Used to get coverage missed childrens.
     * @return type
     */
    public function getCoverageMissedChildren() {
        $select = $joins = $where = $order = $group = "";
        if (!empty($this->form_values['office'])) {
            $office = $this->form_values['office'];
        } else {
            return;
        }

        // Check if campaign.
        if (!empty($this->form_values['campaign'])) {
            $where .= " AND campaign_data.campaign_id = " . $this->form_values['campaign'] . "";
        }
        if ($office == 1) {
            $select = "campaign_data.district_id,Province.pk_id,Province.location_name,";
            $joins = "INNER JOIN locations AS District ON campaign_data.district_id = District.pk_id
            INNER JOIN locations AS Province ON District.province_id = Province.pk_id";
            $group = "GROUP BY Province.pk_id";
            $order = "ORDER BY Province.pk_id";
        } elseif ($office == 2) {
            $select = "campaign_data.district_id AS pk_id, District.location_name,";
            if (!empty($this->form_values['combo1'])) {
                $where = " AND District.province_id = " . $this->form_values['combo1'];
            }
            $joins = "INNER JOIN locations AS District ON campaign_data.district_id = District.pk_id";
            $group = "GROUP BY campaign_data.district_id";
            $order = "ORDER BY District.location_name";
        } elseif ($office == 6) {
            $select = "warehouses.pk_id,warehouses.warehouse_name as location_name, campaign_data.warehouse_id,";
            if (!empty($this->form_values['combo2'])) {
                $where = " AND campaign_data.district_id = " . $this->form_values['combo2'];
            }
            $joins = "INNER JOIN warehouses ON campaign_data.warehouse_id = warehouses.pk_id";
            $group = "GROUP BY campaign_data.warehouse_id";
            $order = "ORDER BY warehouses.warehouse_name";
        }

        // Create query.
        $str_qry = "SELECT
                    A.pk_id,
                    A.location_name,
                    A.totalTarget,
                    A.totalCoverage,
                    ROUND((A.totalCoverage / A.totalTarget) * 100, 1) AS coveragePer,
                    A.NA,
                    ROUND((A.NA/ A.totalTarget) * 100, 1)  AS NAPer,
                    A.refusal,
                    ROUND((A.refusal/ A.totalTarget) * 100, 1)  AS refusalPer,
                    A.NA + A.refusal AS total,
                    ROUND(((A.NA + A.refusal) / A.totalTarget) * 100, 1) AS totalPer
                FROM
                    (
                    SELECT
                        $select
                        Sum(campaign_data.daily_target) AS totalTarget,
                        Sum(campaign_data.total_coverage) AS totalCoverage,
                        Sum(campaign_data.record_not_accessible) AS NA,
                        Sum(campaign_data.record_refusal) AS refusal
                    FROM
                        campaign_data
                        $joins
                    WHERE 1=1
                    $where
                    $group
                    $order
                    ) A ";

        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Status Nids Report
     * Used to get NIDs status report.
     *
     * @return type
     */
    public function getStatusNidsReport() {
        $office = $this->form_values['office'];
        if (!empty($this->form_values['campaign'])) {
            $where = "WHERE
                campaign_data.campaign_id = '" . $this->form_values['campaign'] . "'";
        } else {
            $where = "";
        }

        // Check office level.
        if (!empty($office)) {
            $office = $this->form_values['office'];
        } else {
            $office = 1;
        }
        if ($office == 1) {
            // Create query.
            $str_qry = "SELECT
            B.location_name,
            SUM(A.totalTarget) AS totalTarget,
            SUM(A.totalCoverage) AS totalCoverage,
            ROUND((SUM(A.totalCoverage) / SUM(A.totalTarget) * 100), 1) AS coveragePer,
            COUNT(A.union_council_id) as noOfUc,
            SUM(IF(A.coveragePer < 30, 1, 0)) AS thiryPer,
            SUM(IF(A.coveragePer >= 30 AND A.coveragePer < 50, 1, 0)) AS fityPer,
            SUM(IF(A.coveragePer >= 50 AND A.coveragePer < 70, 1, 0)) AS seventyPer,
            SUM(IF(A.coveragePer >= 70 AND A.coveragePer < 90, 1, 0)) AS nintyPer
        FROM
        (
                SELECT
                        campaign_data.union_council_id,
                        campaign_data.district_id,
                        SUM(campaign_data.daily_target) AS totalTarget,
                        SUM(campaign_data.total_coverage) AS totalCoverage,
                        ROUND((SUM(campaign_data.total_coverage) / SUM(campaign_data.daily_target)) * 100, 1) AS coveragePer
                FROM
                        campaign_data
                $where
                GROUP BY
                        campaign_data.union_council_id
        ) A
        JOIN (
                SELECT
                        Province.location_name,
                        locations.pk_id,
                        Province.pk_id AS ProvinceId
                FROM
                        locations
                INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
                WHERE
                        locations.geo_level_id = 4
        )B
        ON A.district_id = B.pk_id
        GROUP BY B.ProvinceId ";
        } else if ($office == 2) {
            $str_qry = "SELECT
                B.location_name,
                SUM(A.totalTarget) AS totalTarget,
                SUM(A.totalCoverage) AS totalCoverage,
                ROUND((SUM(A.totalCoverage) / SUM(A.totalTarget) * 100), 1) AS coveragePer,
                COUNT(A.union_council_id) as noOfUc,
                SUM(IF(A.coveragePer < 30, 1, 0)) AS thiryPer,
                SUM(IF(A.coveragePer >= 30 AND A.coveragePer < 50, 1, 0)) AS fityPer,
                SUM(IF(A.coveragePer >= 50 AND A.coveragePer < 70, 1, 0)) AS seventyPer,
                SUM(IF(A.coveragePer >= 70 AND A.coveragePer < 90, 1, 0)) AS nintyPer
        FROM
        (
                SELECT
                        campaign_data.union_council_id,
                        campaign_data.district_id,
                        SUM(campaign_data.daily_target) AS totalTarget,
                        SUM(campaign_data.total_coverage) AS totalCoverage,
                        ROUND((SUM(campaign_data.total_coverage) / SUM(campaign_data.daily_target)) * 100, 1) AS coveragePer
                FROM
                        campaign_data
                $where
                GROUP BY
                        campaign_data.district_id
        ) A
        JOIN (
        SELECT
                locations.pk_id,
                locations.location_name
        FROM
                locations
        WHERE
                locations.geo_level_id = 4
                and
          locations.province_id = '" . $this->form_values['combo1'] . "'
        )B
        ON A.district_id = B.pk_id
        GROUP BY A.district_id";
        } else {
            $str_qry = "";
        }
        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Under Performing Districts
     * Used to get all those districts list
     * which are under performing.
     * @return type
     */
    public function getUnderPerformingDistricts() {
        $form_values = $this->form_values;

        if (!empty($form_values['campaign_id'])) {
            $arr_where[] = "campaign_data.campaign_id = " . $form_values['campaign_id'] . "";
        }
        if (!empty($form_values['combo1'])) {

            $arr_where[] = "locations.province_id = " . $form_values['combo1'] . "";
        }

        if (is_array($arr_where)) {
            $where = " WHERE " . implode(" AND ", $arr_where);
        }

        // Create query.
        $str_qry = "SELECT
                    locations.pk_id,
                    locations.location_name,
                    Sum(campaign_data.daily_target) AS totalTarget,
                    Sum(campaign_data.total_coverage) AS vaccinated,
                    ROUND((SUM(campaign_data.total_coverage) / SUM(campaign_data.daily_target)) * 100) AS vaccinatedPer,
                    campaign_data.campaign_id
                    FROM
                            campaign_data
                    INNER JOIN locations ON campaign_data.district_id = locations.pk_id

                    " . $where . "
                    GROUP BY
                            locations.pk_id";


        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Under Performing Districts Summary
     * Used to get all those districts summary list
     * which are under performing.
     * @return type
     */
    public function getUnderPerformingDistrictsSummary() {
        $form_values = $this->form_values;

        if (!empty($form_values['campaign_id'])) {
            $arr_where = "where campaign_data.campaign_id = " . $form_values['campaign_id'] . "";
        }
        if (!empty($form_values['campaign_id'])) {
            $arr_where_d = "where campaign_districts.campaign_id = " . $form_values['campaign_id'] . "";
        }

        // Create query.
        $str_qry = "SELECT
        B.location_name,
        SUM(A.reported) AS reported,
        SUM(IF(A.coveragePer < 90, 1, 0)) AS less90PerCoverage
        FROM
        (
                SELECT
                        campaign_data.district_id,
                        locations.province_id,
                        COUNT(DISTINCT campaign_data.district_id) AS reported,
                        ROUND((SUM(campaign_data.total_coverage) / SUM(campaign_data.daily_target)) * 100) AS coveragePer
                FROM
                        campaign_data
                INNER JOIN locations ON campaign_data.district_id = locations.pk_id

                        " . $arr_where . "
                GROUP BY
                        locations.pk_id
        ) A
        RIGHT JOIN (
        SELECT DISTINCT
                Province.pk_id,
                Province.location_name
        FROM
                campaign_districts
        INNER JOIN locations AS District ON campaign_districts.district_id = District.pk_id
        INNER JOIN locations AS Province ON District.province_id = Province.pk_id
        " . $arr_where_d . "
        ) B
        ON A.province_id = B.pk_id
        GROUP BY
                A.province_id";


        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Under Performing Ucs
     * Used to get Union Councils list
     * which are under performing.
     * @return type
     */
    public function getUnderPerformingUcs() {
        // Set forms values.
        $form_values = $this->form_values;
        if (!empty($form_values['campaign_id'])) {
            $arr_where[] = "campaign_data.campaign_id = " . $form_values['campaign_id'] . "";
        }
        if (!empty($form_values['combo1'])) {

            $arr_where[] = "districts.province_id = " . $form_values['combo1'] . "";
        }
        if (!empty($form_values['combo2'])) {
            $arr_where[] = "campaign_data.district_id = " . $form_values['combo2'] . "";
        }

        $arr_where[] = "warehouses.status = 1";
        if (is_array($arr_where)) {
            $where = " WHERE " . implode(" AND ", $arr_where);
        }

        // Create query.
        $str_qry = "SELECT
         districts.location_name,
                warehouses.warehouse_name,
                SUM(campaign_data.daily_target) AS totalTarget,
                SUM(campaign_data.total_coverage) AS vaccinated,
                ROUND((SUM(campaign_data.total_coverage) / SUM(campaign_data.daily_target)) * 100) AS vaccinatedPer
        FROM
                campaign_data
        INNER JOIN warehouses ON campaign_data.warehouse_id = warehouses.pk_id
        INNER JOIN locations as districts ON campaign_data.district_id = districts.pk_id
        " . $where . "
        GROUP BY
                campaign_data.warehouse_id";

        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Vaccine Utilization And Wastage
     * Used to get vaccines utilitization/comsumption
     * and wastage data.
     *
     * @return type
     */
    public function getVaccineUtilizationAndWastage() {

        // Set forms values.
        $form_values = $this->form_values;

        // Check campaing id.
        if (!empty($form_values['campaign_id'])) {
            $arr_where = "where campaign_data.campaign_id = " . $form_values['campaign_id'] . "";
        }

        // Create query.
        $str_qry = "SELECT
        Province.pk_id,
        Province.location_name,
        SUM(campaign_data.vials_given) AS vials_given,
        SUM(campaign_data.vials_used) AS vials_used,
        SUM(campaign_data.vials_returned) AS vials_returned,
        SUM(campaign_data.vials_expired) AS vials_expired
        FROM
        campaign_data
        INNER JOIN locations AS District ON campaign_data.district_id = District.pk_id
        INNER JOIN locations AS Province ON District.province_id = Province.pk_id
        $arr_where
        GROUP BY
        Province.pk_id";

        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

}
