<?php

/**
 * Model_CampaignReadinessUnionCouncil
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */


/**
 *  Model for Campaign Item Groups
 */

class Model_CampaignItemGroups extends Model_Base {

     /**
     * Get Campaign Item Groups
     * 
     * @return type
     */
    public function getCampaignItemGroups() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("c.pkId,ips.itemName,c.ageGroup1Min,c.ageGroup1Max,c.ageGroup2Min,c.ageGroup2Max")
                ->from('CampaignItemGroups', 'c')
                ->join('c.itemPackSize', 'ips');
        if (!empty($form_values['item_id'])) {
            $str_sql->where("ips.pkId= '" . $form_values['item_id'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

}
