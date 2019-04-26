<?php

/**
 * Model_CcmStatusHistory
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1.5.1
 */

/**
 *  Model for CCM Status History
 */
class Model_CcmStatusHistory extends Model_Base {

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
        $this->_table = $this->_em->getRepository('CcmStatusHistory');
    }

    /**
     * Update Cold Chain Status
     * 
     */
    public function updateColdChainStatus() {

        // Set Time zone.
        date_default_timezone_set('Asia/karachi');
        // Set cold chain id.
        $cc_id = $this->form_values['ccm_id'];

        // Get logged in user id.
        $created_by = $this->_em->find('Users', $this->_user_id);

        // Loop through all cold chain items ids.
        foreach ($cc_id as $index => $ccm_id) {
            // Set cold chain properties.
            $working_status = $this->form_values['working_status'];
            $freeze = $this->form_values['freeze'];
            $heat = $this->form_values['heat'];
            $ccmAssetId = $this->form_values['asset_id'];
            $reason = $this->form_values['reason'];
            $utilization = $this->form_values['utilization'];
            $status_date = $this->form_values['status_date'];
            // Set warehouse id
            $wh_id = $this->form_values['wh_id'];

            // Init CcmStatusHistory object.
            $ccm_status_history = new CcmStatusHistory();
            if (!empty($freeze[$index])) {
                $ccm_status_history->setFreezeAlarm($freeze[$index]);
            }
            if (!empty($heat[$index])) {
                $ccm_status_history->setHeatAlarm($heat[$index]);
            }
            $cold_chain_id = $this->_em->getRepository('ColdChain')->find($ccm_id);
            $ccm_status_history->setCcm($cold_chain_id);

            // Set status date.
            $ccm_status_history->setStatusDate(new \DateTime(App_Controller_Functions::dateToDbFormat($status_date)));
            $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
            $work_status = $this->_em->getRepository('CcmStatusList')->find($working_status[$index]);
            $ccm_status_history->setCcmStatusList($work_status);
            $ccm_asset_type = $this->_em->getRepository('CcmAssetTypes')->find($ccmAssetId[$index]);
            $ccm_status_history->setCcmAssetType($ccm_asset_type);
            // Set reason id.
            if (empty($reason[$index])) {
                $reason_id = $this->_em->getRepository('CcmStatusList')->find(1);
                $ccm_status_history->setReason($reason_id);
            } else {
                $reason_id = $this->_em->getRepository('CcmStatusList')->find($reason[$index]);
                $ccm_status_history->setReason($reason_id);
            }
            //  Set utilization id.
            if (empty($utilization[$index])) {
                $utilization_id = $this->_em->getRepository('CcmStatusList')->find(1);
                $ccm_status_history->setUtilization($utilization_id);
            } else {
                $utilization_id = $this->_em->getRepository('CcmStatusList')->find($utilization[$index]);
                $ccm_status_history->setUtilization($utilization_id);
            }
            // Set created by user id.
            $ccm_status_history->setCreatedBy($created_by);

            // Set modified by user id.
            $ccm_status_history->setModifiedBy($created_by);

            // Set modified date.
            $ccm_status_history->setModifiedDate(App_Tools_Time::now());

            // Set created date.
            $ccm_status_history->setCreatedDate(App_Tools_Time::now());

            // Save record.
            $this->_em->persist($ccm_status_history);
            $this->_em->flush();

            // Get last saved record id.
            $ccm_history_id = $ccm_status_history->getPkId();
            $cold_chain = new Model_ColdChain();

            // Update ccm status history.
            $cold_chain->updateCcmStatusHistory($ccm_id, $ccm_history_id);
        }

        // Set Cold chain id.
        $ccm_id_q = $this->form_values['ccm_id_q'];

        // Loop through all quantities.
        foreach ($ccm_id_q as $index => $ccm_id_quantity) {

            $work_quantity = $this->form_values['work_quantity'];
            $comments = $this->form_values['comments'];
            $asset_id_q = $this->form_values['asset_id_q'];

            // Set warehouse id.
            $wh_id = $this->form_values['wh_id'];
            $ccm_status = new CcmStatusHistory();
            $cold_chain_id_q = $this->_em->getRepository('ColdChain')->find($ccm_id_quantity);
            $ccm_status->setCcm($cold_chain_id_q);
            $ccm_status->setWorkingQuantity($work_quantity[$index]);
            $ccm_status->setComments($comments[$index]);
            $ccm_status->setStatusDate(new \DateTime(date("Y-m-d h:i")));
            $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);

            $ccm_asset_type_q = $this->_em->getRepository('CcmAssetTypes')->find($asset_id_q[$index]);
            $ccm_status->setCcmAssetType($ccm_asset_type_q);
            // Set created by user id.
            $ccm_status->setCreatedBy($created_by);

            // Set Modified by user id.
            $ccm_status->setModifiedBy($created_by);

            // Set modified date.
            $ccm_status->setModifiedDate(App_Tools_Time::now());
            // Set created date.
            $ccm_status->setCreatedDate(App_Tools_Time::now());

            // Save record.
            $this->_em->persist($ccm_status);
            $this->_em->flush();
            $history_id = $ccm_status->getPkId();
            $cold_chain = new Model_ColdChain();
            // Update cold chain 
            $cold_chain->updateCcmStatusHistory($ccm_id_quantity, $history_id);
        }

        $ccm_id_histroy = $this->form_values['ccm_id_q'];

        // Loop through cold chain quantities.
        foreach ($ccm_id_histroy as $index => $ccm_id_quantity) {

            $work_quantity = $this->form_values['work_quantity'];
            $total_quantity = $this->form_values['total_quantity'];

            // Set warehouse id.
            $wh_id = $this->form_values['wh_id'];
            $ccm_history = new CcmHistory();
            $quantity = $total_quantity[$index] - $work_quantity[$index];
            $ccm_history->setQuantity($quantity);
            $ccm_history->setCreatedDate(new \DateTime(date("Y-m-d")));
            $action_id = $this->_em->getRepository('ListDetail')->find(26);
            $ccm_history->setAction($action_id);
            $ccm_id = $this->_em->getRepository('ColdChain')->find($ccm_id_quantity);
            $ccm_history->setCcm($ccm_id);

            // Get logged in user id.
            $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
            // Set created by user id
            $ccm_history->setCreatedBy($user_id);

            $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
            $ccm_history->setWarehouse($warehouse_id);
            // Set modified user id.
            $ccm_history->setModifiedBy($created_by);

            // Set created date.
            $ccm_history->setCreatedDate(App_Tools_Time::now());

            // Set modified date.
            $ccm_history->setModifiedDate(App_Tools_Time::now());
            // save record.
            $this->_em->persist($ccm_history);
        }

        $this->_em->flush();
    }

}
