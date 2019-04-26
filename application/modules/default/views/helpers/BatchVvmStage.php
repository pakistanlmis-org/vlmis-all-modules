<?php

/**
 * Zend_View_Helper_BatchVvmStage
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */



/**
 *  Zend View Helper Batch Vvm Stage
 */


class Zend_View_Helper_BatchVvmStage extends Zend_View_Helper_Abstract {

    protected $_em_read;
    
    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }
    /**
     * Batch Vvm Stage
     * sets batch vvm stage.
     * @param type $batch_id
     */
    public function batchVvmStage($batch_id) {

        // Create query.
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("IF(ips.vvmGroup = 1, MAX(vvm.pkId), vvm.vvmStageValue) as stage, COUNT(DISTINCT vvm.pkId) as total")
                ->from('PlacementSummary', 'ps')
                ->join('ps.stockBatchWarehouse', 'sbw')
                ->join('sbw.stockBatch','sb')
                ->join('sb.packInfo','pi')
                ->join('pi.stakeholderItemPackSize','sip')   
                ->join('sip.itemPackSize','ips')
                ->join('ps.vvmStage', 'vvm')               
                ->where("ps.stockBatchWarehouse = $batch_id");
        // Get query result.
        $row = $str_sql->getQuery()->getResult();

        if (isset($row) && count($row) > 0) {
            if ($row[0]['total'] > 1) {
                echo 'Multiple';
            } else {
                if (!empty($row[0]['stage'])) {
                    echo $row[0]['stage'];
                } else {
                    echo 'NA';
                }
            }
        } else {
            echo '1';
        }
    }

}
