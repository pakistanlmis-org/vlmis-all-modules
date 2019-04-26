<?php

/**
 * Model_CampaignTypes
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Campaign Types
 */
class Model_CampaignTypes extends Model_Base {

    /**
     * Get All Campaign Types
     * 
     * @return type
     */
    public function getAllCampaignTypes() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ct.pkId,ct.camapignTypeName")
                ->from('CampaignTypes', 'ct')
                ->orderBy("ct.listRank", "DESC");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Campaign Types
     * 
     * @return type
     */
    public function getCampaignTypes() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ct.pkId,ct.camapignTypeName")
                ->from('CampaignTypes', 'ct');
        if (!empty($form_values['campaign_type_name'])) {
            $str_sql->where("ct.camapignTypeName= '" . $form_values['campaign_type_name'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Campaign Type
     * 
     * @return type
     */
    public function checkCampaignType() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ct.pkId")
                ->from('CampaignTypes', 'ct')
                ->where("ct.camapignTypeName= '" . $form_values . "' ");

        return $str_sql->getQuery()->getResult();
    }

}
