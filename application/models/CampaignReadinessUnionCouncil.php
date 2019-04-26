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
 *  Model for Campaign Readiness Union Council
 */

class Model_CampaignsReadinessUnionCouncil extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * Model_CampaignsReadinessUnionCouncil __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('CampaignReadinessUnionCouncil');
    }

}
