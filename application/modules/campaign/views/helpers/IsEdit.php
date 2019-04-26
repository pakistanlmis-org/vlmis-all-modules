<?php

/**
 * Zend_View_Helper_IsEdit
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage campaign
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */



/**
 *  Zend View Helper Is Edit
 */

class Zend_View_Helper_IsEdit extends Zend_View_Helper_Abstract {

    protected $_em_read;
    
    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }
    
    /**
     * isEdit
     * @param type $campaign_id
     * @param type $campaign_readiness_uc_id
     * @return boolean
     */
    public function isEdit($campaign_id, $campaign_readiness_uc_id) {

        $result = 0;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cru.pkId")
                ->from('CampaignReadinessUnionCouncil', 'cru')
                ->join('cru.unionCouncil', 'uc')
                ->join('cru.campaign', 'c')
                ->where("c.pkId = $campaign_id")
                ->Andwhere("uc.pkId = $campaign_readiness_uc_id");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            $result = 1;
        }

        return $result == 1;
    }

}
