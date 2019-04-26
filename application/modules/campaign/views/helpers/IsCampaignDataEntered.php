<?php

/**
 * Zend_View_Helper_IsCampaignDataEntered
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage campaign
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Is Campaign Data Entered
 */

class Zend_View_Helper_IsCampaignDataEntered extends Zend_View_Helper_Abstract {

    protected $_em_read;
    
    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }
    
    /**
     * Is Campaign Data Entered
     * @param type $campaign_id
     * @param type $campaign_uc_id
     * @return boolean
     */
    public function isCampaignDataEntered($campaign_id, $campaign_uc_id) {

        $result = 0;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cd.pkId")
                ->from('CampaignData', 'cd')
                ->join('cd.warehouse', 'uc')
                ->join('cd.campaign', 'c')
                ->where("c.pkId = $campaign_id")
                ->Andwhere("uc.pkId = $campaign_uc_id");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            $result = 1;
        }

        if ($result == 1){
            return false;
        }
        
        return true;
    }

}
