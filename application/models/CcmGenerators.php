<?php

/**
 * Model_CcmMakes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for CCM Generators
 */
class Model_CcmGenerators extends Model_Base {

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
        $this->_table = $this->_em->getRepository('CcmMakes');
    }

    /**
     * Add Generator
     * 
     */
    public function addGenerator() {

        $form_values = $this->form_values;
        $ccm_model = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        //var_dump($ccm_model);
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        //var_dump($user_id);
        $ccm_model->setCreatedBy($user_id);
        $ccm_model->setCreatedDate(App_Tools_Time::now());

        $ccm_model->setModifiedBy($user_id);
        $ccm_model->setModifiedDate(App_Tools_Time::now());

        $ccm_model->setNoOfPhases($form_values['no_of_phases']);
        $this->_em->persist($ccm_model);
        $this->_em->flush();

        $cold_chain = new ColdChain();
        $cold_chain->setAssetId($form_values['asset_id']);
        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $cold_chain->setCcmModel($model_id);
        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::GENERATOR);
        $cold_chain->setCcmAssetType($asset_type);
        $cold_chain->setSerialNumber($form_values['serial_number']);
        $cold_chain->setWorkingSince(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['working_since'])));
        $cold_chain->setCreatedBy($user_id);
        $source_id = $this->_em->getRepository('Stakeholders')->find($form_values['source_id']);
        $cold_chain->setSource($source_id);
        $auto_gen_id = App_Controller_Functions::generateCcemUniqueAssetId(Model_CcmAssetTypes::GENERATOR);
        $cold_chain->setAutoAssetId($auto_gen_id);
        $cold_chain->setCreatedDate(App_Tools_Time::now());
        if (!empty($form_values['warehouse']) && $form_values['placed_at'] == 1) {
            $wh_id = $this->_em->getRepository('Warehouses')->find($form_values['warehouse']);
            $cold_chain->setWarehouse($wh_id);
        }

        //$user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $cold_chain->setCreatedDate(App_Tools_Time::now());

        $cold_chain->setModifiedBy($user_id);
        $cold_chain->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($cold_chain);
        $this->_em->flush();
        $last_ccm_id = $cold_chain->getPkId();

        $generators = new CcmGenerators();
        $power_source = $this->_em->getRepository('ListDetail')->find($form_values['power_source']);
        $generators->setPowerSource($power_source);
        $generators->setPowerRating($form_values['power_rating']);
        $generators->setUseFor($form_values['use_for']);
        if ($form_values['use_for'] == "90") {
            $cold_room_id = $this->_em->getRepository('ColdChain')->find($form_values['cold_room']);

            $generators->setColdRoom($cold_room_id);
        }
        $generators->setAutomaticStartMechanism($form_values['automatic_start']);
        $ccm_id = $this->_em->getRepository('ColdChain')->find($last_ccm_id);
        $generators->setCcm($ccm_id);

        $generators->setCreatedBy($user_id);
        $generators->setCreatedDate(App_Tools_Time::now());
        $generators->setModifiedBy($user_id);
        $generators->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($generators);
        $this->_em->flush();

        $ccm_status_history = new CcmStatusHistory();
        $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
        $cold_chian_id = $this->_em->getRepository('ColdChain')->find($last_ccm_id);
        $ccm_status_history->setCcm($cold_chian_id);

        $ccm_status_list_id = $this->_em->getRepository('CcmStatusList')->find($form_values['ccm_status_list_id']);
        $ccm_status_history->setCcmStatusList($ccm_status_list_id);
        $asset_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::GENERATOR);
        $ccm_status_history->setCcmAssetType($asset_id);
        if (!empty($form_values['reasons'])) {
            $reason = $this->_em->getRepository('CcmStatusList')->find($form_values['reasons']);
            $ccm_status_history->setReason($reason);
        }
        if (!empty($form_values['utilizations'])) {
            $utilization = $this->_em->getRepository('CcmStatusList')->find($form_values['utilizations']);
            $ccm_status_history->setUtilization($utilization);
        }

        //$user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $ccm_status_history->setCreatedBy($user_id);
        $ccm_status_history->setCreatedDate(App_Tools_Time::now());
        $ccm_status_history->setModifiedBy($user_id);
        $ccm_status_history->setModifiedDate(App_Tools_Time::now());


        $this->_em->persist($ccm_status_history);
        $this->_em->flush();

        $ccm_history_id = $ccm_status_history->getPkId();
        $cold_chain_model = new Model_ColdChain();
        $cold_chain_model->updateCcmStatusHistory($last_ccm_id, $ccm_history_id);
    }

    /**
     * Update Generator
     * 
     */
    public function updateGenerator() {
        $form_values = $this->form_values;
        $ccm_model = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $ccm_model->setModifiedBy($user_id);
        $ccm_model->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_model);
        $this->_em->flush();

        $cold_chain = $this->_em->getRepository('ColdChain')->find($form_values['ccm_id']);
        $cold_chain->setAssetId($form_values['asset_id']);

        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $cold_chain->setCcmModel($model_id);
        $cold_chain->setSerialNumber($form_values['serial_number']);
        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::GENERATOR);
        $cold_chain->setCcmAssetType($asset_type);
        $cold_chain->setWorkingSince(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['working_since'])));
        $cold_chain->setCreatedBy($user_id);

        $cold_chain->setCreatedDate(App_Tools_Time::now());

        $gen = $this->_em->getRepository('CcmGenerators')->findBy(array('ccm' => $form_values['ccm_id']));

        $generators = $this->_em->getRepository('CcmGenerators')->find($gen[0]->getPkId());
        $power_source = $this->_em->getRepository('ListDetail')->find($form_values['power_source']);
        $generators->setPowerSource($power_source);
        $generators->setPowerRating($form_values['power_rating']);
        $generators->setUseFor($form_values['use_for']);
        $generators->setAutomaticStartMechanism($form_values['automatic_start']);
        if ($form_values['use_for'] == "90") {

           $cold_room_id = $this->_em->getRepository('ColdChain')->find($form_values['cold_room']);

            $generators->setColdRoom($cold_room_id);
        }
        $generators->setCreatedBy($user_id);
        $generators->setCreatedDate(App_Tools_Time::now());

        $generators->setModifiedBy($user_id);
        $generators->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($generators);
        $this->_em->flush();
    }

    /**
     * Search Generators
     * 
     * @return boolean
     */
    public function searchGenerators() {
        $form_values = $this->form_values;

        if (!empty($form_values['ccm_status_list_id'])) {
            $where[] = "csl.pkId  = '" . $form_values['ccm_status_list_id'] . "'";
        }
        if (!empty($form_values['serial_number'])) {
            $where[] = "cc.serialNumber  = '" . $form_values['serial_number'] . "'";
        }
        if (!empty($form_values['source_id'])) {
            $where[] = "stkholder.pkId  = '" . $form_values['source_id'] . "'";
        }
        if (!empty($form_values['asset_id'])) {
            $where[] = "cc.assetId  = '" . $form_values['asset_id'] . "'";
        }
        if (!empty($form_values['ccm_make_id'])) {
            $where[] = "ccmake.pkId  = '" . $form_values['ccm_make_id'] . "'";
        }
        if (!empty($form_values['ccm_model_id'])) {
            $where[] = "ccm.pkId  = '" . $form_values['ccm_model_id'] . "'";
        }
        if (!empty($form_values['working_since_from'])) {
            $where[] = "cc.workingSince  >= '" . $form_values['working_since_from'] . "'";
        }
        if (!empty($form_values['working_since_to'])) {
            $where[] = "cc.workingSince  <= '" . $form_values['working_since_to'] . "'";
        }
        if ($form_values['placed_at'] == 1 && !empty($form_values['warehouse'])) {
            $where[] = "w.pkId  = '" . $form_values['warehouse'] . "'";
        }

        if ($form_values['placed_at'] == 0) {
            $where[] = "w.pkId  IS NULL ";
        }
        $where[] = "cat.pkId = '" . Model_CcmAssetTypes::GENERATOR . "'";
        // $where[] = "cc.createdBy = '" . $this->_user_id . "'  ";
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,cat.assetTypeName,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,cc.serialNumber,"
                        . "ccmake.ccmMakeName,cc.createdDate,cc.workingSince,"
                        . "d.locationName as district, w.warehouseName as facility")
                ->from('ColdChain', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('cc.ccmAssetType', 'cat')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->leftJoin('cc.source', 'stkholder');
        if ($this->form_values['placed_at'] == 1) {
            $str_sql->join('cc.warehouse', 'w');
            $str_sql->join('w.district', 'd');
        }

        if ($this->form_values['placed_at'] == 0) {
            $str_sql->leftjoin('cc.warehouse', 'w');
            $str_sql->leftjoin('w.district', 'd');
        }
        $str_sql->where($where_s);
        if ($this->form_values['report_type'] == "summary") {
            $str_sql->groupBy('ccm.ccmModelName');
        }

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
