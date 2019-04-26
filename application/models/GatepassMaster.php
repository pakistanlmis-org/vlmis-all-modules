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
 *  Model for Gatepass Master
 */
class Model_GatepassMaster extends Model_Base {

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
        // Initialize class instance.
        $this->_table = $this->_em->getRepository('GatepassMaster');
    }

    /**
     * Add Gatepass
     * 
     */
    public function addGatepass() {
        // Set form values.
        $form_values = $this->form_values;

        // get gatepassmater instance.
        $gp_master = new GatepassMaster();
        // Check vehicle option.
        if (!empty($form_values['vehicle_other'])) {
            // Find vehicle
            $gp_vehicle = $this->_em->getRepository("GatepassVehicles")->findOneBy(array("vehicleType" => $form_values['vehicle_type_id'], "number" => $form_values['vehicle_other']));
            // Check if vehicle found.
            if (count($gp_vehicle) == 0) {
                $gp_vehicle = new GatepassVehicles();
                $gp_vehicle->setNumber($form_values['vehicle_other']);
                $gvehicle = $this->_em->getRepository("ListDetail")->find($form_values['vehicle_type_id']);
                $gp_vehicle->setVehicleType($gvehicle);

                $modified_by = $this->_em->getRepository('Users')->find($this->_user_id);
                $gp_vehicle->setModifiedBy($modified_by);
                $gp_vehicle->setCreatedBy($modified_by);
                $gp_vehicle->setCreatedDate(App_Tools_Time::now());


                $this->_em->persist($gp_vehicle);
                $this->_em->flush();
            }
            // Set result.
            $form_values['gatepass_vehicle_id'] = $gp_vehicle->getPkId();
        }

        // Check gatepass vehicle id.
        if (!empty($form_values['gatepass_vehicle_id'])) {
            $gatepass_vehicle_id = $this->_em->find("GatepassVehicles", $form_values['gatepass_vehicle_id']);
            $gp_master->setGatepassVehicle($gatepass_vehicle_id);
        }

        // Check transaction date.
        if (!empty($form_values['transaction_date'])) {
            $gp_master->setTransactionDate(new \DateTime(date("Y-m-d h:i")));
        }

        // Set number format.
        $number = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
        $gp_master->setNumber($number);

        // get warehouse id.
        $warehouse_id = $this->_identity->getWarehouseId();
        // Find warehouse and get its instance.
        $warehouse_id = $this->_em->find("Warehouses", $warehouse_id);
        $gp_master->setWarehouse($warehouse_id);


        // comments
        if (!empty($form_values['comments'])) {
            $gp_master->setComments($form_values['comments']);
        }

       


        // get logged in users.
        $created_by = $this->_em->find('Users', $this->_user_id);
        $gp_master->setCreatedBy($created_by);
        // Set created date.
        $gp_master->setCreatedDate(App_Tools_Time::now());
        $gp_master->setModifiedBy($created_by);
        // Set modified date.
        $gp_master->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($gp_master);
        $this->_em->flush();
        $gp_master_id = $gp_master->getPkId();
        $stock_detail = new Model_StockDetail();

        $detailId = array();
        foreach ($form_values['quantity'] as $key => $value) {
            $value1 = $value;
            if (!empty($value1)) {
                list($stm, $stcmaster) = explode('_', $key);

                $res_rec = $stock_detail->quantityDataByStcBatch($stm, $stcmaster);
                $dataArr = array();
                foreach ($res_rec as $res) {
                    $detail_pkid = $res['pkId'];
                    $detail_qty = abs($res['quantity']);
                    $dataArr[$detail_pkid] = $detail_qty;
                }

                while ($value1 > 0) {

                    $arr = $stock_detail->getClosest($dataArr, $value);

                    $qty = $value1;
                    $value1 = $value1 - $arr[1];
                    if ($value1 > 0) {
                        $detailId[$arr[0]] = (int) $arr[1];
                    } else {

                        $detailId[$arr[0]] = (int) $qty;
                    }
                }
            }
        }

        // Get gatepassmater instance.
        $gp_m_id = $this->_em->getRepository('GatepassMaster')->find($gp_master_id);

        // Loop through all quantities.
        foreach ($detailId as $detId => $quantity) {

            $gp_detail = new GatepassDetail();

            $stock_d_id = $this->_em->getRepository('StockDetail')->find($detId);

            $gp_detail->setStockDetail($stock_d_id);

            $gp_detail->setQuantity($quantity);

            $gp_detail->setGatepassMaster($gp_m_id);

            // Get logged in user id.
            $created_by = $this->_em->find('Users', $this->_user_id);
            $gp_detail->setCreatedBy($created_by);
            // Set created date.
            $gp_detail->setCreatedDate(App_Tools_Time::now());
            $gp_detail->setModifiedBy($created_by);
            // Set modified date.
            $gp_detail->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($gp_detail);
            $this->_em->flush();
        }
    }

}
