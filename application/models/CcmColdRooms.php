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
 *  Model for CCM Cold Rooms
 */
class Model_CcmColdRooms extends Model_Base {

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
        $this->_table = $this->_em->getRepository('CcmColdRooms');
    }

    /**
     * Add Cold Room
     * 
     */
    public function addColdRoom() {
        $form_values = $this->form_values;
        $ccm_model = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);

        $ccm_model->setAssetDimensionLength($form_values['asset_dimension_length']);
        $ccm_model->setAssetDimensionWidth($form_values['asset_dimension_width']);
        $ccm_model->setAssetDimensionHeight($form_values['asset_dimension_height']);

        if ($form_values['ccm_asset_sub_type_id'] == Model_CcmAssetTypes::SUBFREEZERROOM) {

//for -20'C Freezer room
            $ccm_model->setTemperatureType(1);
            $ccm_model->setNetCapacity20($form_values['net_capacity']);
            $ccm_model->setGrossCapacity20($form_values['gross_capacity']);
        } elseif ($form_values['ccm_asset_sub_type_id'] == Model_CcmAssetTypes::SUBCOLDROOM) {

//for +4'C Cold room
            $ccm_model->setTemperatureType(0);
            $ccm_model->setNetCapacity4($form_values['net_capacity']);
            $ccm_model->setGrossCapacity4($form_values['gross_capacity']);
        }
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $ccm_model->setCreatedBy($user_id);
        $ccm_model->setCreatedDate(App_Tools_Time::now());
        $ccm_model->setModifiedBy($user_id);
        $ccm_model->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($ccm_model);
        $this->_em->flush();

        $cold_chain = new ColdChain();
        $cold_chain->setAssetId($form_values['asset_id']);
        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $cold_chain->setCcmModel($model_id);
        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find($form_values['ccm_asset_sub_type_id']);
        $cold_chain->setCcmAssetType($asset_type);
        $cold_chain->setWorkingSince(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['working_since'])));
        $auto_gen_id = App_Controller_Functions::generateCcemUniqueAssetId(Model_CcmAssetTypes::COLDROOM);
        $cold_chain->setAutoAssetId($auto_gen_id);


        $source_id = $this->_em->getRepository('Stakeholders')->find($form_values['source_id']);
        $cold_chain->setSource($source_id);

        if (!empty($form_values['warehouse']) && $form_values['placed_at'] == 1) {
            $wh_id = $this->_em->getRepository('Warehouses')->find($form_values['warehouse']);
            $cold_chain->setWarehouse($wh_id);
        }

        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $cold_chain->setCreatedBy($created_by);
        $cold_chain->setCreatedDate(App_Tools_Time::now());
        $cold_chain->setModifiedBy($created_by);
        $cold_chain->setModifiedDate(App_Tools_Time::now());


        $this->_em->persist($cold_chain);
        $this->_em->flush();

        $last_ccm_id = $cold_chain->getPkId();
        $cold_rooms = new CcmColdRooms();

        $backup_generator = $this->_em->getRepository('ListDetail')->find($form_values['backup_generator']);

        $cold_rooms->setBackupGenerator($backup_generator);
        $ccm_id = $this->_em->getRepository('ColdChain')->find($last_ccm_id);
        $cold_rooms->setCcm($ccm_id);
        $asset_sub_type_id = $this->_em->getRepository('CcmAssetTypes')->find($form_values['ccm_asset_sub_type_id']);
        $cold_rooms->setCcmAssetSubType($asset_sub_type_id);
        $cold_rooms->setCoolingSystem($form_values['cooling_system']);
        $cold_rooms->setCreatedBy($user_id);
        $cold_rooms->setCreatedDate(App_Tools_Time::now());
        $cold_rooms->setHasVoltage($form_values['has_voltage']);
        $cold_rooms->setModifiedBy($user_id);
        $cold_rooms->setModifiedDate(App_Tools_Time::now());
        if (!empty($form_values['refrigerator_gas_type'])) {
            $refrigerator_gas_type = $this->_em->getRepository('ListDetail')->find($form_values['refrigerator_gas_type']);
            $cold_rooms->setRefrigeratorGasType($refrigerator_gas_type);
        }
        if (!empty($form_values['temperature_recording_system'])) {
            $temperature_recording_system = $this->_em->getRepository('ListDetail')->find($form_values['temperature_recording_system']);
            $cold_rooms->setTemperatureRecordingSystem($temperature_recording_system);
        }
        if (!empty($form_values['type_recording_system'])) {
            $type_recording_system = $this->_em->getRepository('ListDetail')->find($form_values['type_recording_system']);
            $cold_rooms->setTypeRecordingSystem($type_recording_system);
        }


        $cold_rooms->setCreatedBy($user_id);
        $cold_rooms->setCreatedDate(App_Tools_Time::now());
        $cold_rooms->setModifiedBy($user_id);
        $cold_rooms->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($cold_rooms);
        $this->_em->flush();

        $ccm_status_history = new CcmStatusHistory();
        $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
        $cold_chian_id = $this->_em->getRepository('ColdChain')->find($last_ccm_id);
        $ccm_status_history->setCcm($cold_chian_id);
        $ccm_status_list_id = $this->_em->getRepository('CcmStatusList')->find($form_values['ccm_status_list_id']);
        $ccm_status_history->setCcmStatusList($ccm_status_list_id);
        $asset_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::COLDROOM);
        $ccm_status_history->setCcmAssetType($asset_id);
        if (!empty($form_values['reason'])) {
            $reason = $this->_em->getRepository('CcmStatusList')->find($form_values['reason']);
            $ccm_status_history->setReason($reason);
        }
        if (!empty($form_values['utilization'])) {
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

        $placements_locations = new PlacementLocations();
        $location_type = $this->_em->getRepository('ListDetail')->find(Model_PlacementLocations::LOCATIONTYPE_CCM);
        $placements_locations->setLocationType($location_type);
        $placements_locations->setLocationBarcode($auto_gen_id);
        $placements_locations->setLocationId($last_ccm_id);

        $placements_locations->setCreatedBy($user_id);
        $placements_locations->setCreatedDate(App_Tools_Time::now());
        $placements_locations->setModifiedBy($user_id);
        $placements_locations->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($placements_locations);
        $this->_em->flush();
        $ware_house = $form_values['warehouse'];
        if (!empty($ware_house)) {
               $date_time = date("Y/m/d");
            $qry = "SELECT REPUpdateCapacity($ware_house,'$date_time') from DUAL";
            $row = $this->_em->getConnection()->prepare($qry);
            $row->execute();

            $qry1 = "SELECT REPUpdateRequirement($ware_house,'$date_time') from DUAL";
            $row1 = $this->_em->getConnection()->prepare($qry1);
            $row1->execute();
        }
    }

    /**
     * Update Cold Room
     * 
     */
    public function updateColdRoom() {
        $form_values = $this->form_values;
        $ccm_model = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $ccm_model->setAssetDimensionLength($form_values['asset_dimension_length']);
        $ccm_model->setAssetDimensionWidth($form_values['asset_dimension_width']);
        $ccm_model->setAssetDimensionHeight($form_values['asset_dimension_height']);
        if ($form_values['ccm_asset_sub_type_id'] == Model_CcmAssetTypes::SUBFREEZERROOM) {
//for -20'C Freezer room
            $ccm_model->setTemperatureType(1);
            $ccm_model->setNetCapacity20($form_values['net_capacity']);
            $ccm_model->setGrossCapacity20($form_values['gross_capacity']);
        } elseif ($form_values['ccm_asset_sub_type_id'] == Model_CcmAssetTypes::SUBCOLDROOM) {
//for +4'C Cold room
            $ccm_model->setTemperatureType(0);
            $ccm_model->setNetCapacity4($form_values['net_capacity']);
            $ccm_model->setGrossCapacity4($form_values['gross_capacity']);
        }
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $ccm_model->setModifiedBy($user_id);
        $ccm_model->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_model);
        $this->_em->flush();

        $cold_chain = $this->_em->getRepository('ColdChain')->find($form_values['ccm_id']);
        $cold_chain->setAssetId($form_values['asset_id']);
        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['ccm_model_id']);
        $cold_chain->setCcmModel($model_id);
        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::COLDROOM);
        $cold_chain->setCcmAssetType($asset_type);
        $cold_chain->setWorkingSince(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['working_since'])));

        $cold_chain->setModifiedBy($user_id);
        $cold_chain->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($cold_chain);
        $this->_em->flush();

        $c_room = $this->_em->getRepository('CcmColdRooms')->findBy(array('ccm' => $form_values['ccm_id']));
        $cold_rooms = $this->_em->getRepository('CcmColdRooms')->find($c_room[0]->getPkId());
        $backup_generator = $this->_em->getRepository('ListDetail')->find($form_values['backup_generator']);
        $cold_rooms->setBackupGenerator($backup_generator);

        $asset_sub_type_id = $this->_em->getRepository('CcmAssetTypes')->find($form_values['ccm_asset_sub_type_id']);
        $cold_rooms->setCcmAssetSubType($asset_sub_type_id);
        $cold_rooms->setCoolingSystem($form_values['cooling_system']);

        $cold_rooms->setHasVoltage($form_values['has_voltage']);
        $cold_rooms->setModifiedBy($user_id);
        $cold_rooms->setModifiedDate(App_Tools_Time::now());
        if (!empty($form_values['refrigerator_gas_type'])) {
            $refrigerator_gas_type = $this->_em->getRepository('ListDetail')->find($form_values['refrigerator_gas_type']);
            $cold_rooms->setRefrigeratorGasType($refrigerator_gas_type);
        }
        if (!empty($form_values['temperature_recording_system'])) {
            $temperature_recording_system = $this->_em->getRepository('ListDetail')->find($form_values['temperature_recording_system']);
            $cold_rooms->setTemperatureRecordingSystem($temperature_recording_system);
        }
        if (!empty($form_values['type_recording_system'])) {
            $type_recording_system = $this->_em->getRepository('ListDetail')->find($form_values['type_recording_system']);
            $cold_rooms->setTypeRecordingSystem($type_recording_system);
        }
        $ware_house = $form_values['warehouse'];
        if (!empty($ware_house)) {
              $date_time = date("Y/m/d");
            $qry = "SELECT REPUpdateCapacity($ware_house,'$date_time') from DUAL";
            $row = $this->_em->getConnection()->prepare($qry);
            $row->execute();

            $qry1 = "SELECT REPUpdateRequirement($ware_house,'$date_time') from DUAL";
            $row1 = $this->_em->getConnection()->prepare($qry1);
            $row1->execute();
        }
    }

    /**
     * Search Cold Rooms
     * 
     * @return boolean
     */
    public function searchColdRooms() {


        if (!empty($this->form_values['ccm_asset_sub_type_id'])) {
            $where[] = "ccr.ccm_asset_sub-type  = '" . $this->form_values['ccm_asset_sub_type_id'] . "'";
        }
        if (!empty($this->form_values['ccm_asset_sub_type_id']) && $this->form_values['ccm_asset_sub_type_id'] == Model_CcmAssetTypes::SUBCOLDROOM && !empty($this->form_values['capacity_from']) && !empty($this->form_values['capacity_to'])) {
            $where[] = "ccm.gross_capacity4  Between '" . $this->form_values['capacity_from'] . "'   AND '" . $this->form_values['capacity_to'] . "'";
        }

        if (!empty($this->form_values['ccm_asset_sub_type_id']) && $this->form_values['ccm_asset_sub_type_id'] == Model_CcmAssetTypes::SUBFREEZERROOM && !empty($this->form_values['capacity_from']) && !empty($this->form_values['capacity_to'])) {
            $where[] = "ccm.gross_capacity20  Between '" . $this->form_values['capacity_from'] . "'   AND '" . $this->form_values['capacity_to'] . "'";
        }
        //Working status
        if (!empty($this->form_values['ccm_status_list_id'])) {
            $where[] = "csh.ccm_status_list  = '" . $this->form_values['ccm_status_list_id'] . "'";
        }
        //Source of Supply
        if (!empty($this->form_values['source_id'])) {
            $where[] = "cc.source  = '" . $this->form_values['source_id'] . "'";
        }
        // Number of Cooling System
        if (!empty($this->form_values['cooling_system'])) {
            $where[] = "ccr.cooling_system  = '" . $this->form_values['cooling_system'] . "'";
        }
        //Asset Equipment Id
        if (!empty($this->form_values['asset_id'])) {
            $where[] = "cc.asset_id  = '" . $this->form_values['asset_id'] . "'";
        }

        // year of supply from and to
        if (!empty($this->form_values['working_since_from']) && !empty($this->form_values['working_since_to'])) {
            $where[] = "cc.working_since Between   '" . App_Controller_Functions::dateToDbFormat($this->form_values['working_since_from']) . "' AND  '" . App_Controller_Functions::dateToDbFormat($this->form_values['working_since_to']) . "' ";
        }

        if (!empty($this->form_values['make'])) {
            $where[] = "ccmake.pk_id  = '" . $this->form_values['make'] . "'";
        }
        if (!empty($this->form_values['ccm_model_id'])) {
            $where[] = "ccm.pk_id  = '" . $this->form_values['ccm_model_id'] . "'";
        }

        if ($this->form_values['placed_at'] == 1 && !empty($this->form_values['warehouse'])) {
            $where[] = "w.pk_id  = '" . $this->form_values['warehouse'] . "'";
        }
        if ($this->form_values['placed_at'] == 0) {
            $where[] = "w.pk_id  IS NULL ";
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = "SELECT
	cc.pk_id AS pkId,
	cc.asset_id AS assetId,
	cat.asset_type_name AS assetTypeName,
	ccm.ccm_model_name AS ccmModelName,
	ccmake.ccm_make_name AS ccmMakeName,
	cc.created_date AS createdDate,
	csl.ccm_status_list_name AS ccmStatusListName,
	w.warehouse_name AS warehouseName,
	d.location_name AS locationName,
	ccm.gross_capacity_20 AS grossCapacity20
        FROM
                ccm_cold_rooms AS ccr
        INNER JOIN cold_chain AS cc ON ccr.ccm_id = cc.pk_id
        INNER JOIN ccm_models AS ccm ON cc.ccm_model_id = ccm.pk_id
        INNER JOIN ccm_asset_types AS cat ON cc.ccm_asset_type_id = cat.pk_id
        INNER JOIN ccm_asset_types AS cp ON cat.parent_id = cp.pk_id
        INNER JOIN ccm_status_history AS csh ON cc.ccm_status_history_id = csh.pk_id
        INNER JOIN ccm_status_list AS csl ON csh.ccm_status_list_id = csl.pk_id
        INNER JOIN ccm_makes AS ccmake ON ccm.ccm_make_id = ccmake.pk_id";

        if ($this->form_values['placed_at'] == 1) {
            $str_sql = $str_sql . " INNER JOIN warehouses AS w ON cc.warehouse_id = w.pk_id"
                    . " INNER JOIN locations AS d ON w.district_id = d.pk_id";
        } else {
            $str_sql = $str_sql . " LEFT JOIN warehouses AS w ON cc.warehouse_id = w.pk_id"
                    . " LEFT JOIN locations AS d ON w.district_id = d.pk_id";
        }

        $str_sql = $str_sql . " WHERE " . $where_s;

        $sql = $this->_em_read->getConnection()->prepare($str_sql);
        $sql->execute();
        $row = $sql->fetchAll();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
