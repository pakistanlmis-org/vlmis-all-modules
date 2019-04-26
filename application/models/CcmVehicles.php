<?php

/**
 * Model_CcmMakes
 * 
 * 
 * 
 *     ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */

/**
 *  Model for CCM Vehicles
 */
class Model_CcmVehicles extends Model_Base {

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
        $this->_table = $this->_em->getRepository('CcmVehicles');
    }

    /**
     * Get Transports
     * 
     * @return type
     */
    public function getTransports() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('cma.ccmMakeName,cmo.ccmModelName,cc.manufactureYear,cv.registrationNo,cv.usedForEpi,ca.pkId,ca.assetTypeName,ld.listValue')
                ->from("CcmVehicles", "cv")
                ->join("cv.ccm", "cc")
                ->join("cc.ccmModel", "cmo")
                ->join("cc.ccmAssetType", "ca")
                ->join("cmo.ccmMake", "cma")
                ->join("cv.fuelType", "ld")
                ->where("cc.ccmAssetType = ca.pkId");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Add Transport
     * 
     */
    public function addTransport() {
        date_default_timezone_set('Asia/karachi');
        $form_values = $this->form_values;

        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);

        $cold_chain = new ColdChain();
        $cold_chain->setAssetId($form_values['asset_id']);
        $cold_chain->setAutoAssetId(App_Controller_Functions::generateCcemUniqueAssetId(Model_CcmAssetTypes::TRANSPORT));

        $stakeholder = $this->_em->getRepository('Stakeholders')->find($form_values['source_id']);
        $cold_chain->setSource($stakeholder);
        if ($form_values['ccm_asset_sub_type_id']) {
            $asset_type = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::TRANSPORT);
            $cold_chain->setCcmAssetType($asset_type);
        }
        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $cold_chain->setCcmModel($model_id);
        $cold_chain->setManufactureYear(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['manufacture_year'])));

        if (!empty($form_values['warehouse']) && $form_values['placed_at'] == 1) {
            $wh_id = $this->_em->getRepository('Warehouses')->find($form_values['warehouse']);
            $cold_chain->setWarehouse($wh_id);
        }

        $cold_chain->setCreatedBy($user_id);
        $cold_chain->setCreatedDate(App_Tools_Time::now());
        $cold_chain->setModifiedBy($user_id);
        $cold_chain->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($cold_chain);
        $this->_em->flush();

        $last_ccm_id = $cold_chain->getPkId();

        $ccm_vehicles = new CcmVehicles();
        $cold_chain_id = $this->_em->getRepository('ColdChain')->find($last_ccm_id);
        $ccm_vehicles->setCcm($cold_chain_id);
        $ccm_asset_sub_type = $this->_em->getRepository('CcmAssetTypes')->find($form_values['ccm_asset_sub_type_id']);
        $ccm_vehicles->setCcmAssetSubType($ccm_asset_sub_type);
        $ccm_vehicles->setRegistrationNo($form_values['registration_no']);
        if ($form_values['used_for_epi']) {
        $ccm_vehicles->setUsedForEpi($form_values['used_for_epi']);
        }
        if ($form_values['fuel_type_id']) {
            $fuel_type_id = $this->_em->getRepository('ListDetail')->find($form_values['fuel_type_id']);
            $ccm_vehicles->setFuelType($fuel_type_id);
        }
        $ccm_vehicles->setComments($form_values['comments']);

        $ccm_vehicles->setCreatedBy($user_id);
        $ccm_vehicles->setCreatedDate(App_Tools_Time::now());
        $ccm_vehicles->setModifiedBy($user_id);
        $ccm_vehicles->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($ccm_vehicles);

        $this->_em->flush();


        $ccm_status_history = new CcmStatusHistory();
        $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
        $cold_chian_id = $this->_em->getRepository('ColdChain')->find($last_ccm_id);
        $ccm_status_history->setCcm($cold_chian_id);
        $ccm_status_list_id = $this->_em->getRepository('CcmStatusList')->find($form_values['ccm_status_list_id']);
        $ccm_status_history->setCcmStatusList($ccm_status_list_id);
        $asset_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::TRANSPORT);
        $ccm_status_history->setCcmAssetType($asset_id);
        if (!empty($form_values['reason'])) {
            $reason = $this->_em->getRepository('CcmStatusList')->find($form_values['reason']);
            $ccm_status_history->setReason($reason);
        }
        if (!empty($form_values['reason'])) {
            $utilization = $this->_em->getRepository('CcmStatusList')->find($form_values['utilization']);
            $ccm_status_history->setUtilization($utilization);
        }

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
     * Update Transport
     * 
     */
    public function updateTransport() {

        $form_values = $this->form_values;

        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);

        $cold_chain = $this->_em->getRepository('ColdChain')->find($form_values['ccm_id']);
        $cold_chain->setAssetId($form_values['asset_id']);
        $stakeholder = $this->_em->getRepository('Stakeholders')->find($form_values['source_id']);
        $cold_chain->setSource($stakeholder);

        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::TRANSPORT);
        $cold_chain->setCcmAssetType($asset_type);

        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $cold_chain->setCcmModel($model_id);
        $cold_chain->setManufactureYear(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['manufacture_year'])));

        $cold_chain->setModifiedBy($user_id);
        $cold_chain->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($cold_chain);
        $this->_em->flush();


        $vechicles = $this->_em->getRepository('CcmVehicles')->findBy(array('ccm' => $form_values['ccm_id']));
        $ccm_vehicles = $this->_em->getRepository('CcmVehicles')->find($vechicles[0]->getPkId());

        $ccm_asset_sub_type = $this->_em->getRepository('CcmAssetTypes')->find($form_values['ccm_asset_sub_type_id']);
        $ccm_vehicles->setCcmAssetSubType($ccm_asset_sub_type);
        $ccm_vehicles->setRegistrationNo($form_values['registration_no']);
        $ccm_vehicles->setUsedForEpi($form_values['used_for_epi']);
        if ($form_values['fuel_type_id']) {
            $fuel_type_id = $this->_em->getRepository('ListDetail')->find($form_values['fuel_type_id']);
            $ccm_vehicles->setFuelType($fuel_type_id);
        }
        $ccm_vehicles->setComments($form_values['comments']);

        $ccm_vehicles->setModifiedBy($user_id);
        $ccm_vehicles->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($ccm_vehicles);

        $this->_em->flush();
    }

    /**
     * Search Vehicles
     * 
     * @return boolean
     */
    public function searchVehicles() {

        $form_values = $this->form_values;

        if (!empty($form_values['ccm_status_list_id'])) {
            $where[] = "cc.ccmStatusHistory  = '" . $form_values['ccm_status_list_id'] . "'";
        }
        if (!empty($form_values['fuel_type_id'])) {
            $where[] = "ft.pkId  = '" . $form_values['fuel_type_id'] . "'";
        }
        if (!empty($form_values['source_id'])) {
            $where[] = "stkholder.pkId  = '" . $form_values['source_id'] . "'";
        }
        if (!empty($form_values['asset_id'])) {
            $where[] = "ccm.assetId  = '" . $form_values['asset_id'] . "'";
        }
        if (!empty($form_values['ccm_asset_sub_type_id'])) {
            $where[] = "cv.ccmAssetSubType  = '" . $form_values['ccm_asset_sub_type_id'] . "'";
        }

        if (!empty($form_values['registration_no'])) {
            $where[] = "cv.registrationNo  = '" . $form_values['registration_no'] . "'";
        }
        if (!empty($form_values['ccm_make_id'])) {
            $where[] = "ccmake.pkId  = '" . $form_values['ccm_make_id'] . "'";
        }
        if (!empty($form_values['ccm_model_id'])) {
            $where[] = "ccm.pkId  = '" . $form_values['ccm_model_id'] . "'";
        }
        if (!empty($form_values['manufacture_year_from'])) {
            $where[] = "cc.manufactureYear  >= '" . $form_values['manufacture_year_from'] . "'";
        }
        if (!empty($form_values['manufacture_year_to'])) {
            $where[] = "cc.manufactureYear  <= '" . $form_values['manufacture_year_to'] . "'";
        }
        if ($form_values['placed_at'] == 1 && !empty($form_values['warehouse'])) {
            $where[] = "w.pkId  = '" . $form_values['warehouse'] . "'";
        }

        if ($form_values['placed_at'] == 0) {
            $where[] = "w.pkId  IS NULL ";
        }
        $where[] = "cat.pkId = '" . Model_CcmAssetTypes::TRANSPORT . "'";
        // $where[] = "cc.createdBy = '" . $this->_user_id . "'  ";
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em->createQueryBuilder()
                ->select("ft.listValue,cc.pkId,cc.assetId,cc.quantity,cc.manufactureYear,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,cc.serialNumber,"
                        . "ccmake.ccmMakeName,cc.createdDate,cc.workingSince,"
                        . "d.locationName as district, w.warehouseName as facility,cat.assetTypeName")
                ->from('CcmVehicles', 'cv')
                ->join('cv.fuelType', 'ft')
                ->join('cv.ccm', 'cc')
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
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
