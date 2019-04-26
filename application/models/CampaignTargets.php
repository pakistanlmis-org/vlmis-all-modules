<?php

/**
 * Model_CampaignTargets
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Campaign Targets
 */
class Model_CampaignTargets extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * Model_CampaignTargets __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('CampaignTargets');
    }

    /**
     * Get All Campaign Target Ucs
     * 
     * @return type
     */
    public function getAllCampaignTargetUcs() {
        $row = $this->_em_read->getConnection()->prepare("SELECT
                    A.warehouse_id,
                    A.warehouse_name,
                    B.pk_id,
                    B.daily_target
                FROM
                    (
                            SELECT
                                    warehouses.pk_id as warehouse_id,
                                    warehouses.warehouse_name
                            FROM
                                    warehouses
                            WHERE                              
                             warehouses.district_id = " . $this->form_values['district_id'] . " 
                            AND warehouses.stakeholder_office_id = " . Model_Stakeholders::CAMPAIGN_TEAMS . "
                             AND   warehouses.status = 1
                            ORDER BY
                                    warehouses.warehouse_name ASC
                    ) A
                LEFT JOIN (
                    SELECT
                            warehouses.pk_id as warehouse_id,
                            campaign_targets.pk_id ,
                            campaign_targets.daily_target
                    FROM
                            warehouses
                    LEFT JOIN campaign_targets ON warehouses.pk_id = campaign_targets.warehouse_id
                    WHERE
                  
                    warehouses.district_id = " . $this->form_values['district_id'] . " 
                    AND warehouses.stakeholder_office_id = " . Model_Stakeholders::CAMPAIGN_TEAMS . "
                    AND campaign_targets.campaign_id = " . $this->form_values['campaign_id'] . " 
   AND   warehouses.status = 1                    
ORDER BY
                            warehouses.warehouse_name ASC
                ) B ON A.warehouse_id = B.warehouse_id ");

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Get Campaign Name
     * 
     * @return type
     */
    public function getCampaignName() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName,c.campaignName,ct.dailyTarget,p.locationName as provinceName,d.locationName as districtName")
                ->from('CampaignTargets', 'ct')
                ->join('ct.campaign', 'c')
                ->join('ct.warehouse', 'w')
                ->join('w.province', 'p')
                ->join('w.district', 'd')
                ->join('w.stakeholderOffice', 'so')
                ->where('so.pkId=10')
                ->AndWhere('p.pkId=' . $form_values['province_id'])
                ->AndWhere('d.pkId=' . $form_values['district_id'])
                ->AndWhere('c.pkId=' . $form_values['campaign_id']);

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign Name For Label
     * 
     * @return type
     */
    public function getCampaignNameForLabel() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("c.campaignName")
                ->from('Campaigns', 'c')
                ->where('c.pkId=' . $form_values['campaign_id']);

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Campaign Targets
     * 
     * @return type
     */
    public function getAllCampaignTargets() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName,ct.dailyTarget,p.locationName as provinceName,d.locationName as districtName,c.isClosed")
                ->from('CampaignTargets', 'ct')
                ->join('ct.campaign', 'c')
                ->join('ct.warehouse', 'w')
                ->join('w.province', 'p')
                ->join('w.district', 'd')
                ->join('w.stakeholderOffice', 'so')
                ->where('so.pkId=' . Model_Stakeholders::CAMPAIGN_TEAMS)
                ->AndWhere('p.pkId=' . $form_values['province_id'])
                ->AndWhere('d.pkId=' . $form_values['district_id'])
                ->AndWhere('c.pkId=' . $form_values['campaign_id'])
                ->AndWhere('ct.itemPackSize=' . $form_values['item_id']);

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign Targets
     * 
     * @return type
     */
    public function getCampaignTargets() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.pkId as itemId,w.pkId as warehouseId,w.warehouseName,ct.dailyTarget,p.locationName as provinceName,d.locationName as districtName,c.isClosed")
                ->from('CampaignTargets', 'ct')
                ->join('ct.itemPackSize', 'ips')
                ->join('ct.campaign', 'c')
                ->join('ct.warehouse', 'w')
                ->join('w.province', 'p')
                ->join('w.district', 'd')
                ->join('w.stakeholderOffice', 'so')
                ->where('so.pkId=' . Model_Stakeholders::CAMPAIGN_TEAMS)
                ->AndWhere('p.pkId=' . $form_values['province_import_hidden'])
                ->AndWhere('d.pkId=' . $form_values['district_import_hidden'])
                ->AndWhere('c.pkId=' . $form_values['campaign_import_id'])
                ->AndWhere('ips.pkId=' . $form_values['item_import_hidden']);


        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Campaign Closed
     * 
     * @return int
     */
    public function getAllCampaignClosed() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("c.isClosed")
                ->from('Campaigns', 'c')
                ->where('c.pkId=' . $form_values['campaign_id']);

        $rs = $str_sql->getQuery()->getResult();
        if (count($rs) > 0) {
            return $rs['0']['isClosed'];
        } else {
            return 1;
        }
    }

    /**
     * Delete Campaign Data
     * 
     * @return type
     */
    public function deleteCampaignData() {

        $em = Zend_Registry::get('doctrine');

        $row = $em->getConnection()->prepare("DELETE
          campaign_targets.*
          FROM
          campaign_targets
          INNER JOIN warehouses ON campaign_targets.warehouse_id = warehouses.pk_id
          WHERE
          campaign_targets.campaign_id = " . $this->form_values['campaign_import_hidden'] . "
          AND
          campaign_targets.item_pack_size_id = " . $this->form_values['item_import_hidden'] . "
          AND    
          warehouses.district_id = " . $this->form_values['district_import_hidden'] . " ");

        return $row->execute();
    }

    /**
     * Get Old Campaign Target
     * 
     * @return type
     */
    public function getOldCampaignTarget() {

        $row = $this->_em_read->getConnection()->prepare("SELECT
                                        B.warehouse_id,
                                        B.warehouse_name,
                                        A.pk_id
                                        FROM
                                        (
                                                SELECT
                                                        campaign_targets.pk_id,
                                                        campaign_targets.warehouse_id
                                                FROM
                                                        campaign_targets
                                                WHERE
                                                        campaign_targets.campaign_id = '" . $this->form_values['campaign_import_hidden'] . "'
                                        ) A
                                        RIGHT JOIN (
                                        SELECT
                                                warehouses.pk_id as warehouse_id,
                                                warehouses.warehouse_name
                                        FROM
                                                warehouses
                                        WHERE
                                                TRIM(warehouses.warehouse_name) =  '" . $this->form_values['warehouse_name'] . "'
                                                AND warehouses.stakeholder_office_id = 45
                                                and warehouses.status = 1    
                                        ) B ON A.warehouse_id = B.warehouse_id");

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Get Target By Campaign Id Uc Id
     * 
     * @return type
     */
    public function getTargetByCampaignIdUcId() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ct.pkId,ct.dailyTarget")
                ->from('CampaignTargets', 'ct')
                ->join('ct.campaign', 'c')
                ->join('ct.warehouse', 'w')
                ->where("w.pkId= '" . $form_values['uc_id'] . "' ")
                ->AndWhere("c.pkId='" . $form_values['campaign_id'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Previous Campaigns
     * 
     * @return type
     */
    public function getAllPreviousCampaigns() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT c.pkId,c.campaignName")
                ->from('CampaignTargets', 'ct')
                ->join('ct.campaign', 'c')
                ->join('ct.warehouse', 'w')
                ->join('w.province', 'p')
                ->join('w.district', 'd')
                ->join('w.stakeholderOffice', 'so')
                ->where('so.pkId=' . Model_Stakeholders::CAMPAIGN_TEAMS)
                ->AndWhere('p.pkId=' . $form_values['province_id'])
                ->AndWhere('d.pkId=' . $form_values['district_id'])
                ->AndWhere('c.pkId <> ' . $form_values['campaign_id'])
                ->AndWhere('ct.itemPackSize=' . $form_values['item_id']);

        return $str_sql->getQuery()->getResult();
    }

}
