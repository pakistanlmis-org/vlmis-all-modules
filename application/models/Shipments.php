<?php

/**
 * Model_Shipment
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author    Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Shipments
 * 
 * Inherits:
 * Model Base
 */
class Model_Shipments extends Model_Base {

    /**
     * $_table
     * 
     * Table
     * 
     * @var type 
     */
    private $_table;

    /**
     * __construct
     * 
     * Constructor for Shipments
     */
    public function __construct() {
        //calling parent constructor
        parent::__construct();
        $this->_table = $this->_em->getRepository('Shipments');
    }

    /**
     * Get Min Date
     * 
     * @param type $date
     * @return string
     */
    public function getMinDate($date) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("MIN(sh.shipmentDate) sh_date")
                ->from("Shipments", "sh")
                ->where("sh.shipmentDate > '" . $date . "'");

        $result = $str_sql->getQuery()->getResult();
        if (count($result) > 0) {
            return $result[0]['sh_date'];
        } else {
            return '';
        }
    }

    /**
     * Get Max Date
     * 
     * @param type $date
     * @return string
     */
    public function getMaxDate($date) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("MAX(sh.shipmentDate) sh_date")
                ->from("Shipments", "sh")
                ->where("sh.shipmentDate > '" . $date . "'");

        $result = $str_sql->getQuery()->getResult();
        if (count($result) > 0) {
            return $result[0]['sh_date'];
        } else {
            return '';
        }
    }

    /**
     * addProcurements
     * 
     * Add Procurements
     * 
     * @return type
     */
    public function addProcurements() {

        $data = $this->form_values;
          // print_r($data);
         // exit;
        if (empty($data['hdn_master_id'])) {
            $shipments = new Shipments();

            $shipmentHistory = new ShipmentHistory();

            $shipments->setReferenceNumber($data['transaction_reference']);


            $shipments->setShipmentDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['shipment_date'])));

            $trans = $this->getTransactionNumber($data['shipment_date']);

            $shipments->setTransactionNumber($trans['trans_no']);

            $shipments->setTransactionCounter($trans['id']);
            //   $shipments->setShipmentQuantity(str_replace(",", "", $data['quantity']));
            $funding_source_id = $this->_em->getRepository('Warehouses')->find($data['from_warehouse_id']);
            $shipments->setFundingSource($funding_source_id);
            $activity_id = $this->_em->getRepository('StakeholderActivities')->find($data['activity_id']);
            $shipments->setStakeholderActivity($activity_id);
            $shipments->setDraft('1');
            $warhouse_id = $this->_em->getRepository('Warehouses')->find($this->_identity->getWarehouseId());
            $shipments->setWarehouse($warhouse_id);
            $shipments->setCreatedDate(new \DateTime(date("Y-m-d")));
            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $shipments->setCreatedBy($created_by);
            $shipments->setModifiedBy($created_by);
            $shipments->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($shipments);
            $this->_em->flush();

            $id = $shipments->getPkId();
            //  $shipment_detail = $this->_em->getRepository("ShipmentDetail")->findBy(array("shipment" => $data['hdn_master_id']));
            // $shipments_d = $this->_em->getRepository("ShipmentDetail")->find($shipment_detail[0]->getPkId());
            $shipment_ids = $this->_em->getRepository('Shipments')->find($id);
            $shipments_d = new ShipmentDetail();
            //sets Shipment Id
            $shipments_d->setShipment($shipment_ids);

            $item_id = $this->_em->getRepository('ItemPackSizes')->find($data['item_id']);
            $shipments_d->setItemPackSize($item_id);

            $shipments_d->setReceivedQuantity(str_replace(",", "", $data['quantity']));
            $shipments_d->setCreatedDate(new \DateTime(date("Y-m-d")));
            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $shipments_d->setCreatedBy($created_by);
            $shipments_d->setModifiedBy($created_by);
            $shipments_d->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($shipments_d);
            $this->_em->flush();

            $id = $shipments->getPkId();



            $shipment_id = $this->_em->getRepository('Shipments')->find($id);

            //sets Shipment Id
            $shipmentHistory->setShipment($shipment_id);

            //sets Shipment Status
            $shipmentHistory->setStatus($data['status']);

            //sets Reference Number
            $shipmentHistory->setReferenceNumber($data['transaction_reference']);

            //sets Created Date
            $shipmentHistory->setCreatedDate(new \DateTime(date("Y-m-d")));

            //sets Created By
            $shipmentHistory->setCreatedBy($created_by);

            //sets Modified By
            $shipmentHistory->setModifiedBy($created_by);

            //sets Modified Date
            $shipmentHistory->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($shipmentHistory);
            $this->_em->flush();

            //returns Pk Id of Shipment History
            return $shipmentHistory->getPkId();
        } else if (!empty($data['hdn_master_id'])) {
            //$shipment_detail = $this->_em->getRepository("ShipmentDetail")->findBy(array("shipment" => $data['hdn_master_id']));
            // $shipments_d = $this->_em->getRepository("ShipmentDetail")->find($shipment_detail[0]->getPkId());
            $shipments_d = new ShipmentDetail();
            $shipment_ids = $this->_em->getRepository('Shipments')->find($data['hdn_master_id']);

            //sets Shipment Id
            $shipments_d->setShipment($shipment_ids);

            $item_id = $this->_em->getRepository('ItemPackSizes')->find($data['item_id']);
            $shipments_d->setItemPackSize($item_id);

            $shipments_d->setReceivedQuantity(str_replace(",", "", $data['quantity']));
            $shipments_d->setCreatedDate(new \DateTime(date("Y-m-d")));
            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $shipments_d->setCreatedBy($created_by);
            $shipments_d->setModifiedBy($created_by);
            $shipments_d->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($shipments_d);
            $this->_em->flush();
        }
    }

    /**
     * getProcurements
     * 
     * Get Procurements
     * 
     * @return type
     */
    public function getProcurements() {

        if (!empty($this->form_values['from_date']) && !empty($this->form_values['to_date'])) {
            $where1 = " AND DATE_FORMAT(shipments.shipment_date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['from_date']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['to_date']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where1 = "AND DATE_FORMAT(shipments.shipment_date,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where2 = "AND shipment_detail.item_pack_size_id = '" . $this->form_values['item_pack_size_id'] . "'";
        }
        if (!empty($this->form_values['from_warehouse_id'])) {
            $where3 = "AND warehouses.pk_id = '" . $this->form_values['from_warehouse_id'] . "'";
        }
        if (!empty($this->form_values['status'])) {
            $where4 = "AND shipment_history.status = '" . $this->form_values['status'] . "'";
        }


        $str_sql = "SELECT
            shipments.pk_id,
            shipments.reference_number,
            shipments.transaction_number,
            shipments.item_pack_size_id,
            shipments.shipment_date,
            shipments.shipment_quantity,
            shipments.funding_source_id,
            shipments.stakeholder_activity_id,
            shipments.warehouse_id,
            shipments.eta,
            shipments.draft,
            shipments.created_date,
            shipments.created_by,
            shipments.modified_by,
            shipments.modified_date,
            shipment_detail.pk_id as detail_id,
            shipment_detail.received_quantity,
            item_pack_sizes.item_name,
            item_units.item_unit_name,
            shipment_history.reference_number,
            shipment_history.`status`,
            warehouses.warehouse_name,
            stakeholder_activities.activity
            FROM
            shipments
            INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
            INNER JOIN item_pack_sizes ON shipment_detail.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
            INNER JOIN shipment_history ON shipment_history.shipment_id = shipments.pk_id
            INNER JOIN warehouses ON shipments.funding_source_id = warehouses.pk_id
            INNER JOIN stakeholder_activities ON shipments.stakeholder_activity_id = stakeholder_activities.pk_id

            WHERE
            
            shipments.warehouse_id = 159
            $where1
            $where2
            $where3
            $where4";

//echo $str_sql;
//exit;
        // $str_sql->orderBy("s.pkId", "DESC");
        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        return $row->fetchAll();


//        $str_sql = $this->_em_read->createQueryBuilder()
//                ->select("DISTINCT s.referenceNumber, s.transactionNumber,s.pkId, warehouse.warehouseName, "
//                        . "s.shipmentDate,s.shipmentQuantity as quantity,sh.status,sa.activity")
//                ->from("ShipmentHistory", "sh")
//                ->join("sh.shipment", "s")
//                ->join("s.stakeholderActivity", "sa")
//                ->join("s.fundingSource", 'warehouse')
//                ->join("s.warehouse", 'w')
//                ->andWhere("w.pkId = " . $this->_identity->getWarehouseId());
//
//        if (!empty($this->form_values['from_date']) && !empty($this->form_values['to_date'])) {
//            $str_sql->andWhere("DATE_FORMAT(s.shipmentDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['from_date']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['to_date']) . "'");
//        } else {
//            $date_from = date('Y-m' . '-01');
//            $date_to = date('Y-m-d');
//            $str_sql->andWhere("DATE_FORMAT(s.shipmentDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'");
//        }
//        if (!empty($this->form_values['item_pack_size_id'])) {
//            $str_sql->andWhere("ips.pkId = '" . $this->form_values['item_pack_size_id'] . "'");
//        }
//        if (!empty($this->form_values['from_warehouse_id'])) {
//            $str_sql->andWhere("warehouse.pkId = '" . $this->form_values['from_warehouse_id'] . "'");
//        }
//        if (!empty($this->form_values['status'])) {
//            $str_sql->andWhere("sh.status = '" . $this->form_values['status'] . "'");
//        }
//
//        $str_sql->orderBy("s.pkId", "DESC");
//
//        //returns result
//        return $str_sql->getQuery()->getResult();
    }

    public function getAllShipments() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sp.pkId, sp.referenceNumber")
                ->from("Shipments", "sp")
                ->orderBy("sp.shipmentDate", "DESC");
        return $str_sql->getQuery()->getResult();
    }

    public function updateEta() {

        $data = $this->form_values;

        $shipments = $this->_em->getRepository("Shipments")->find($data['shipment_id']);
        $shipments->setShipmentDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['eta'])));
        $this->_em->persist($shipments);
        $this->_em->flush();
    }

    public function getAllPipelineShipments() {
        
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getTempShipmnetsList() {

        $str_sql = "SELECT
            shipments.pk_id,
            shipment_detail.pk_id as detail_id,
            shipments.reference_number,
            shipments.transaction_number,
            shipments.item_pack_size_id,
            shipments.shipment_date,
            shipments.shipment_quantity,
            shipments.funding_source_id,
            shipments.stakeholder_activity_id,
            shipments.warehouse_id,
            shipments.eta,
            shipments.draft,
            shipments.created_date,
            shipments.created_by,
            shipments.modified_by,
            shipments.modified_date,
            shipment_detail.received_quantity,
            item_pack_sizes.item_name,
            item_units.item_unit_name,
            shipment_history.reference_number,
            shipment_history.`status`,
            warehouses.warehouse_name
            FROM
            shipments
            INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
            INNER JOIN item_pack_sizes ON shipment_detail.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
            INNER JOIN shipment_history ON shipment_history.shipment_id = shipments.pk_id
            INNER JOIN warehouses ON shipments.funding_source_id = warehouses.pk_id

            WHERE
            shipments.draft = 1 AND
            shipments.warehouse_id = 159";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Update Procurements
     */
    public function updateProcurements() {
        $data = $this->form_values;
        $shipments = $this->_em->getRepository("Shipments")->find($data['hdn_master_id']);
        $shipments->setDraft(0);
        $this->_em->persist($shipments);
        $this->_em->flush();
    }

    /**
     * Update Procurements details
     */
    public function updateProcurementsDetail() {
        $data = $this->form_values;
        $shipments = $this->_em->getRepository("ShipmentProductBatch")->findBy(array("shipment" => $data['hdn_master_id']));
        foreach ($shipments as $ship) {
            $shipments_h = $this->_em->getRepository("ShipmentProductBatch")->find($ship->getPkId());

            $shipments_h->setDraft(0);
            $this->_em->persist($shipments_h);
        }
        $this->_em->flush();
    }

    /**
     * Delete Stock Detail
     */
    public function deleteShipment($id, $id2) {


        $shipment_detail = $this->_em->getRepository("ShipmentDetail")->find($id);
        $this->_em->remove($shipment_detail);
        $this->_em->flush();
        if (!$this->stockExists($id2)) {

            $shipment_hitory = $this->_em->getRepository("ShipmentHistory")->findBy(array("shipment" => $id2));
            $shipments_h = $this->_em->getRepository("ShipmentHistory")->find($shipment_hitory[0]->getPkId());
            $this->_em->remove($shipments_h);
            $this->_em->flush();

            $shipments = $this->_em->getRepository("Shipments")->find($id2);
            $this->_em->remove($shipments);
            $this->_em->flush();
        }

        return true;
    }

    /**
     * Stock Exists
     *
     * @param type $id
     * @return boolean
     */
    public function stockExists($id) {
        $str_sql = $this->_em->createQueryBuilder()
                ->select("sd.pkId")
                ->from("ShipmentDetail", "sd")
                ->where('sd.shipment = ' . $id);
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getShipmentDetail() {
        $data = $this->form_values;
        $master_id = $data['master_id'];
        $str_sql = "SELECT
            shipments.reference_number,
            shipments.transaction_number,
            shipment_detail.received_quantity,
            shipment_detail.item_pack_size_id,
            shipments.shipment_date,
            shipment_detail.received_quantity,
            shipments.funding_source_id,
            shipments.stakeholder_activity_id,
            shipments.warehouse_id,
            item_pack_sizes.item_name
            FROM
            shipments
            INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
            INNER JOIN item_pack_sizes ON shipment_detail.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
            shipments.pk_id = $master_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    public function addProcurementsDetail() {

        $data = $this->form_values;


        $shipment_batch = new ShipmentProductBatch();


        //  $shipment_batch->setReferenceNumber($data['transaction_reference']);

        $item_id = $this->_em->getRepository('ItemPackSizes')->find($data['item_id']);
        $shipment_batch->setItemPackSize($item_id);

        $shipment_id = $this->_em->getRepository('Shipments')->find($data['shipment_id']);
        $shipment_batch->setShipment($shipment_id);

        if (!empty($data['production_date'])) {
            $shipment_batch->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['production_date'])));
        }

        $shipment_batch->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['expiry_date'])));


        $shipment_batch->setQuantity(str_replace(",", "", $data['quantity']));
        $shipment_batch->setUnitPrice($data['unit_price']);


        if (!empty($data['vvm_type_id'])) {
            $vvm_type_id = $this->_em->getRepository('VvmTypes')->find($data['vvm_type_id']);
            $shipment_batch->setVvmType($vvm_type_id);
        }

        $shipment_batch->setNumber($data['number']);
        $stakeholder_item_pack_size = $this->_em->getRepository('StakeholderItemPackSizes')->find($data['manufacturer_id']);
        $shipment_batch->setStakeholderItemPackSize($stakeholder_item_pack_size);
        $shipment_batch->setDraft('0');

        $shipment_batch->setCreatedDate(new \DateTime(date("Y-m-d")));
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $shipment_batch->setCreatedBy($created_by);
        $shipment_batch->setModifiedBy($created_by);
        $shipment_batch->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($shipment_batch);
        $this->_em->flush();


        return $shipment_batch->getPkId();
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getTempShipmnetProcductBatchList() {

        $shipment_id = $this->form_values['shipment_id'];
        $str_sql = "SELECT
            shipment_product_batch.pk_id,
            shipment_product_batch.number,
            shipment_product_batch.expiry_date,
            shipment_product_batch.unit_price,
            shipment_product_batch.production_date,
            shipment_product_batch.vvm_type_id,
            shipment_product_batch.item_pack_size_id,
            shipment_product_batch.shipment_id,
            shipment_product_batch.draft,
            shipment_product_batch.created_by,
            shipment_product_batch.created_date,
            shipment_product_batch.modified_by,
            shipment_product_batch.modified_date,
            shipment_product_batch.quantity,
            item_pack_sizes.item_name,
            item_pack_sizes.item_unit_id,
            item_units.item_unit_name,
            stakeholders.stakeholder_name as manufacturer
            FROM
            shipment_product_batch
            INNER JOIN item_pack_sizes ON shipment_product_batch.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON shipment_product_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            WHERE
                    shipment_product_batch.shipment_id = $shipment_id
             ";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * addProcurements
     * 
     * Add Procurements
     * 
     * @return type
     */
    public function addVarReport() {

        $data = $this->form_values;



        if (!empty($data['var_id'])) {


            $shipments = $this->_em->getRepository('VarDetail')->find($data['var_id']);
        } else {
            $shipments = new VarDetail();
        }

        $shipments->setCountry($data['country']);
        $shipments->setReportNo($data['report_no']);
        $shipments->setReportDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['report_date'])));
        $shipments->setPlaceOfInspection($data['place_of_inspection']);
        $shipments->setDateOfInspection(new \DateTime(App_Controller_Functions::dateToDbFormat($data['date_of_inspection'])));
        $shipments->setColdStore($data['cold_store']);
        $shipments->setDateOfVaccinesEnteredColdStore(new \DateTime(App_Controller_Functions::dateToDbFormat($data['date_of_vaccines_entered_cold_store'])));
        $shipments->setPreAdviceDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['pre_advice_date'])));
        $shipments->setShippingNotificationDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['shipping_notification_date'])));
        if ($data['awb'] == 'yes') {
            $awb = 1;
        } else {
            $awb = 0;
        }
        $shipments->setAwb($data['awb']);
        if ($data['packing_list'] == 'yes') {
            $packing_list = 1;
        } else {
            $packing_list = 0;
        }
        $shipments->setPackingList($data['packing_list']);
        if ($data['invoice'] == 'yes') {
            $invoice = 1;
        } else {
            $invoice = 0;
        }
        $shipments->setInvoice($data['invoice']);
        if ($data['release_certificate'] == 'yes') {
            $release_certificate = 1;
        } else {
            $release_certificate = 0;
        }
        $shipments->setReleaseCertificate($data['release_certificate']);


        $shipments->setAdvanceNoteOtherDocument($data['other_document']);
        $shipments->setAwbNumber($data['awb_number']);
        $shipments->setAirportOfDestination($data['airport_of_destination']);
        $shipments->setFlightNo($data['flight_no']);

        $shipments->setEtaDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['eta_date'])));
        $shipments->setActualTimeArrival(new \DateTime(App_Controller_Functions::dateToDbFormat($data['actual_time_arrival'])));



        $shipments->setNameOfClearingAgent($data['name_of_clearing_agent']);
        $shipments->setOnBehalf($data['on_behalf']);
        if ($data['quantity_received'] == 'yes') {
            $quantity_received = 1;
        } else {
            $quantity_received = 0;
        }
        $shipments->setQuantityReceived($data['quantity_received']);
        if ($data['detail_short_shipment'] == 'yes') {
            $detail_short_shipment = 1;
        } else {
            $detail_short_shipment = 0;
        }
        $shipments->setDetailShortShipment($data['detail_short_shipment']);

        $shipments->setQuantityReceivedComments($data['quantity_received_comments']);
        $shipments->setDetailShortShipmentComments($data['detail_short_shipment_comments']);
        $shipments->setDocumentsInvoice($data['documents_invoice']);
        $shipments->setDocumentsPackinglist($data['documents_packing_list']);

        $shipments->setDocumentsReleaseCertificate($data['documents_release_certificate']);
        $shipments->setDocumentsVar($data['documents_var']);
        $shipments->setDocumentOther($data['document_other']);
        $shipments->setPart4Comments($data['part4_comments']);

        $shipments->setTotalNumberBoxesInspected($data['total_number_boxes_inspected']);
        if ($data['dry_ice'] == 'on') {
            $dry_ice = 1;
        } else {
            $dry_ice = 0;
        }

        $shipments->setDryIce($dry_ice);
        if ($data['ice_packs'] == 'on') {
            $ice_packs = 1;
        } else {
            $ice_packs = 0;
        }

        $shipments->setIcePacks($ice_packs);
        if ($data['no_coolant'] == 'on') {
            $no_coolant = 1;
        } else {
            $no_coolant = 0;
        }

        $shipments->setNoCoolant($no_coolant);
        if ($data['coolant_type_empty'] == 'on') {
            $coolant_type_empty = 1;
        } else {
            $coolant_type_empty = 0;
        }


        $shipments->setCoolantTypeEmpty($coolant_type_empty);
        if ($data['vvm'] == 'on') {
            $vvm = 1;
        } else {
            $vvm = 0;
        }

        $shipments->setVvm($vvm);
        if ($data['cold_chain_card'] == 'on') {
            $cold_chain_card = 1;
        } else {
            $cold_chain_card = 0;
        }

        $shipments->setColdChainCard($cold_chain_card);
        if ($data['electronic_device'] == 'on') {
            $electronic_device = 1;
        } else {
            $electronic_device = 0;
        }

        $shipments->setElectronicDevice($electronic_device);

        if ($data['temperature_monitors_empty'] == 'on') {
            $temperature_monitors_empty = 1;
        } else {
            $temperature_monitors_empty = 0;
        }

        $shipments->setTemperatureMonitorsEmpty($temperature_monitors_empty);
        $shipments->setConditionOfBoxesArrival($data['condition_of_boxes_arrival']);
        $shipments->setLabelsAttached($data['labels_attached']);
        $shipments->setOtherCommentsElectronicDevice($data['other_comments_electronic_device']);


        // new columns
        $shipments->setPurchaseOrderNo($data['purchase_order_no']);
        $shipments->setConsignee($data['consignee']);
        $shipments->setVaccineDescription($data['vaccine_description']);
        $shipments->setManufacturer($data['manufacturer']);
        $shipments->setCountryPart3($data['country_part3']);




        $shipment_id = $this->_em->getRepository('StockMaster')->find($data['shipment_id']);
        $shipments->setStockMaster($shipment_id);
        $shipments->setCreatedDate(new \DateTime(date("Y-m-d")));
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $shipments->setCreatedBy($created_by);
        $shipments->setModifiedBy($created_by);
        $shipments->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($shipments);
        $this->_em->flush();



        $var_id = $shipments->getPkId();
        $i = 3;

        for ($i = 0; $i < 3; $i++) {
            $box_number = $data[$i . '-box_number'];

            $lot_no = $data[$i . '-lot_no'];
            $less_45 = $data[$i . '-less_45'];
            $less_30 = $data[$i . '-less_30'];
            $less_10 = $data[$i . '-less_10'];
            $less_5 = $data[$i . '-less_5'];
            $cold_chain_a = $data[$i . '-cold_chain_a'];
            $cold_chain_b = $data[$i . '-cold_chain_b'];
            $cold_chain_c = $data[$i . '-cold_chain_c'];
            $cold_chain_d = $data[$i . '-cold_chain_d'];
            $day_of_inspection = $data[$i . '-day_of_inspection'];
            $date_of_inspection1 = $data[$i . '-date_of_inspection1'];


            if (!empty($data['var_id'])) {

                $str_qry_del = "DELETE var_electronic_device.*
                        FROM
                       var_electronic_device 
                       where
                       var_electronic_device.var_detail_id = '" . $data['var_id'] . "'";
                $this->_em = Zend_Registry::get('doctrine');
                $row_del = $this->_em->getConnection()->prepare($str_qry_del);
                $row_del->execute();
            }

            $var_electronic_device = new VarElectronicDevice();
            $var_electronic_device->setBoxNumber($box_number);
            $var_electronic_device->setLotNo($lot_no);
            $var_electronic_device->setLess45($less_45);
            $var_electronic_device->setLess30($less_30);
            $var_electronic_device->setLess10($less_10);
            $var_electronic_device->setLess5($less_5);
            $var_electronic_device->setColdChainA($cold_chain_a);
            $var_electronic_device->setColdChainB($cold_chain_b);
            $var_electronic_device->setColdChainC($cold_chain_c);

            $var_electronic_device->setColdChainD($cold_chain_d);
            $var_electronic_device->setDayOfInspection($day_of_inspection);
            $var_electronic_device->setDateOfInspection(new \DateTime(App_Controller_Functions::dateToDbFormat($date_of_inspection1)));

            $var_detail_id = $this->_em->getRepository('VarDetail')->find($var_id);
            $var_electronic_device->setVarDetail($var_detail_id);
            $var_electronic_device->setCreatedDate(new \DateTime(date("Y-m-d")));
            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $var_electronic_device->setCreatedBy($created_by);
            $var_electronic_device->setModifiedBy($created_by);
            $var_electronic_device->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($var_electronic_device);
        }

        $this->_em->flush();



        return $shipments->getPkId();
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getVarData() {
        $shipment_id = $this->shipment_id;
        if (empty($shipment_id)) {
            $shipment_id = 0;
        }

        $str_sql = "SELECT
            var_detail.pk_id,
            var_detail.country,
            var_detail.report_no,
            var_detail.report_date,
            var_detail.place_of_inspection,
            var_detail.date_of_inspection,
            var_detail.cold_store,
            var_detail.date_of_vaccines_entered_cold_store,
            var_detail.pre_advice_date,
            var_detail.shipping_notification_date,
            var_detail.awb,
            var_detail.packing_list,
            var_detail.invoice,
            var_detail.release_certificate,
            var_detail.advance_note_other_document,
            var_detail.awb_number,
            var_detail.airport_of_destination,
            var_detail.flight_no,
            var_detail.eta_date,
            var_detail.actual_time_arrival,
            var_detail.name_of_clearing_agent,
            var_detail.on_behalf,
            var_detail.quantity_received,
            var_detail.detail_short_shipment,
            var_detail.quantity_received_comments,
            var_detail.detail_short_shipment_comments,
            var_detail.documents_invoice,
            var_detail.documents_packing_list,
            var_detail.documents_release_certificate,
            var_detail.documents_var,
            var_detail.document_other,
            var_detail.part4_comments,
            var_detail.total_number_boxes_inspected,
            var_detail.dry_ice,
            var_detail.ice_packs,
            var_detail.no_coolant,
            var_detail.coolant_type_empty,
            var_detail.vvm,
            var_detail.cold_chain_card,
            var_detail.electronic_device,
            var_detail.temperature_monitors_empty,
            var_detail.condition_of_boxes_arrival,
            var_detail.labels_attached,
            var_detail.other_comments_electronic_device,
            var_detail.stock_master_id shipment_id,
            var_detail.purchase_order_no,
            var_detail.consignee,
            var_detail.vaccine_description,
            var_detail.manufacturer,
            var_detail.country_part3,
            var_detail.created_date,
            var_detail.created_by,
            var_detail.modified_by,
            var_detail.modified_date
            FROM
            var_detail
            WHERE
            var_detail.stock_master_id = $shipment_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getVarElectronicData() {
        $shipment_id = $this->shipment_id;
        if (empty($shipment_id)) {
            $shipment_id = 0;
        }

        $str_sql = "SELECT
        var_electronic_device.pk_id,
        var_electronic_device.box_number,
        var_electronic_device.lot_no,
        var_electronic_device.less_45,
        var_electronic_device.less_30,
        var_electronic_device.less_10,
        var_electronic_device.less_5,
        var_electronic_device.cold_chain_a,
        var_electronic_device.cold_chain_b,
        var_electronic_device.cold_chain_c,
        var_electronic_device.cold_chain_d,
        var_electronic_device.day_of_inspection,
        var_electronic_device.date_of_inspection,
        var_electronic_device.var_detail_id,
        var_electronic_device.created_date,
        var_electronic_device.created_by,
        var_electronic_device.modified_by,
        var_electronic_device.modified_date
        FROM
        var_detail
        INNER JOIN var_electronic_device ON var_detail.pk_id = var_electronic_device.var_detail_id
        where var_detail.stock_master_id = $shipment_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getShipmentBatchDetail() {
        $data = $this->form_values;
        $var_id = $data['master_id'];

        $str_sql = "SELECT
                shipment_product_batch.pk_id,
                shipment_product_batch.number,
                shipment_product_batch.expiry_date,
                shipment_product_batch.unit_price,
                shipment_product_batch.production_date,
                shipment_product_batch.vvm_type_id,
                shipment_product_batch.item_pack_size_id,
                shipment_product_batch.shipment_id,
                shipment_product_batch.stakeholder_item_pack_size_id,
                shipment_product_batch.draft,
                shipment_product_batch.created_by,
                shipment_product_batch.created_date,
                shipment_product_batch.modified_by,
                shipment_product_batch.modified_date,
                shipment_product_batch.quantity,
                item_pack_sizes.item_name,
                stakeholders.stakeholder_name,
                item_units.item_unit_name,
                item_pack_sizes.item_category_id
                FROM
                shipment_product_batch
                INNER JOIN item_pack_sizes ON shipment_product_batch.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON shipment_product_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                shipment_product_batch.shipment_id = $var_id
                ";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    public function getPipelineShipments($id) {
        $data = $this->form_values;



        $date = App_Controller_Functions::dateToDbFormat($data['eta']);

        $str_sql = "SELECT 
        ((A.qnty)/(A.total) * 100) as used_percentage
        from
        (SELECT
                round(
                        (
                                (
                                        SUM(
                                                shipment_detail.received_quantity
                                        ) * pack_info.volum_per_vial
                                ) / 1000
                        )
                ) as qnty,
        (
        SELECT
                ROUND(SUM(ccm_models.net_capacity_20 + ccm_models.net_capacity_4))
        FROM
                cold_chain
        INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        WHERE
                (
                        (
                                cold_chain.ccm_asset_type_id = $id
                                OR AssetMainType.pk_id = $id
                        )
                )
        GROUP BY cold_chain.ccm_asset_type_id


        ) as total



        FROM
                shipments
        INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON shipment_detail.item_pack_size_id = stakeholder_item_pack_sizes.item_pack_size_id
        INNER JOIN pack_info ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        WHERE
            DATE_FORMAT(
					shipments.shipment_date,
					'%Y-%m-%d'
				) >= '$date'
        AND pack_size_description IS NOT NULL
        AND pack_size_description != ''
        ) A";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows[0]['used_percentage'];
        } else {
            return FALSE;
        }
    }

    /**
     * Delete Stock Detail
     */
    public function deleteShipmentBactch($id) {

        $shipments = $this->_em->getRepository("ShipmentProductBatch")->find($id);
        $this->_em->remove($shipments);
        $this->_em->flush();


        return true;
    }

    /**
     * addProcurements
     * 
     * Add Procurements
     * 
     * @return type
     */
    public function addShipmentsReceive() {


        $form_values = $this->form_values;

        if ($form_values['action'] == 1) {
            $shipment_id = $form_values['shipment_id'];
            $str_qry_detail = "Delete
                shipments_receive.*
                FROM
                shipments_receive
                INNER JOIN shipment_product_batch ON shipment_product_batch.pk_id = shipments_receive.shipment_product_batch_id
                WHERE
                  shipment_product_batch.shipment_id = $shipment_id";
            $this->_em = Zend_Registry::get('doctrine');
            $row_detail = $row = $this->_em->getConnection()->prepare($str_qry_detail);
            $row_detail->execute();
        }




        $end_product = $form_values['end_product'];
// first loop
        for ($y = 0; $y < $end_product; $y++) {
            $prodouct_batch_id = $form_values[$y . '-product_batch_id'];
// second loop            
            $end = $form_values[$prodouct_batch_id . '-def_counter'];
            for ($i = 0; $i < $end; $i++) {

                $quantity = $form_values[$i . '-' . $prodouct_batch_id . '-quantity'];
                $vvm_stage = $form_values[$i . '-' . $prodouct_batch_id . '-vvm_stage'];
                $locations = $form_values[$i . '-' . $prodouct_batch_id . '-locations'];
                if ($quantity > 0) {
                    $shipment_receive = new ShipmentsReceive();

                    $ShipmentProductBatch = $this->_em->getRepository('ShipmentProductBatch')->find($prodouct_batch_id);
                    $shipment_receive->setShipmentProductBatch($ShipmentProductBatch);
                    $shipment_receive->setQuantity(str_replace(",", "", $quantity));
                    $shipment_receive->setCounter($i);
                    $locations_id = $this->_em->getRepository('PlacementLocations')->findBy(array("locationId" => $locations));

                    $loc_id = $this->_em->getRepository("PlacementLocations")->find($locations_id[0]->getPkId());
                    $shipment_receive->setPlacementLocation($loc_id);
                    if (!empty($vvm_stage)) {
                        $vvm_stage1 = $this->_em->getRepository('VvmStages')->find($vvm_stage);
                        $shipment_receive->setVvmStage($vvm_stage1);
                    }
                    $shipment_receive->setCreatedDate(new \DateTime(date("Y-m-d")));
                    $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                    $shipment_receive->setCreatedBy($created_by);
                    $shipment_receive->setModifiedBy($created_by);
                    $shipment_receive->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($shipment_receive);
                }
            }
        }




        $this->_em->flush();

//returns Pk Id of Shipment History
        return $shipment_receive->getPkId();
    }

    public function getShipmentsStockOnHand($id) {
        $data = $this->form_values;
        $date = App_Controller_Functions::dateToDbFormat($data['eta']);
        $shipment_id = $data['shipment_id'];
        //  print_r($data);

        $str_sql_item_id = "SELECT
            shipment_detail.item_pack_size_id
            FROM
            shipments
            INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
            WHERE
            shipments.pk_id = $shipment_id";

        // Get doctrine instance.
        $row1 = $this->_em_read->getConnection()->prepare($str_sql_item_id);
        // Execute and get result.
        $row1->execute();
        $res1 = $row1->fetchAll();

        $item_pack_size_id = $res1[0]['item_pack_size_id'];

        $str_sql = "SELECT
                round(
                (
                        SUM(
                                (
                                         p0_.quantity * pack_info.volum_per_vial
                                ) / 1000
                        )
                ) / (
                                                ccm_models.net_capacity_20 + ccm_models.net_capacity_4
                ) * 100
                ) AS used_percentage

            FROM
                    placements AS p0_
            INNER JOIN stock_batch_warehouses AS sbw ON p0_.stock_batch_warehouse_id = sbw.pk_id
            INNER JOIN stock_batch AS s1_ ON sbw.stock_batch_id = s1_.pk_id
            INNER JOIN pack_info ON s1_.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes AS i2_ ON stakeholder_item_pack_sizes.item_pack_size_id = i2_.pk_id
            INNER JOIN placement_locations ON p0_.placement_location_id = placement_locations.pk_id
            INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
            INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
            INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
            LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
            WHERE
                    sbw.warehouse_id = 159
            AND (
                    (
                            cold_chain.ccm_asset_type_id = $id
                            OR AssetMainType.pk_id = $id
                    )
            )
            AND i2_ .pk_id = $item_pack_size_id AND Date_Format(
	 p0_.created_date,
	'%Y-%m-%d'
       ) <= '$date'
            AND i2_.item_category_id = 1
            GROUP BY
                    sbw.warehouse_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows[0]['used_percentage'];
        } else {
            return FALSE;
        }
    }

    public function getTransactionNumber($tr_date) {

        $time_arr = explode(' ', $tr_date);

        $current_date = explode("/", $time_arr['0']);

        $current_month = $current_date[1];
        $current_year = $current_date[2];

        $from_date = $current_year . "-" . $current_month . "-01";
        $to_date = $current_year . "-" . $current_month . "-31";


        $last_id = $this->getLastID($from_date, $to_date);


        if ($last_id == NULL) {
            $last_id = 0;
        }

        $last_id += 1;



        return array(
            "id" => $last_id,
            "trans_no" => "PS" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
        );
    }

    public function getLastID($from, $to) {


        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('MAX(sm.transactionCounter) as Maxtr')
                ->from("Shipments", "sm")
                ->where("DATE_FORMAT(sm.shipmentDate,'%Y-%m-%d') between '" . $from . "' and '" . $to . "'");



        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['Maxtr'];
        } else {
            return FALSE;
        }
    }

    public function getGetIssuanceDemands($id) {
        $data = $this->form_values;



        $date = App_Controller_Functions::dateToDbFormat($data['eta']);

        $str_sql = "SELECT 
        ((A.qnty)/(A.total) * 100) as used_percentage
        from
        (SELECT
                 round(
                        (
                                (
                                        ABS(SUM(
                                                stock_detail.quantity
                                        )) * pack_info.volum_per_vial
                                ) / 1000
                        )
                ) as qnty,
        (
        SELECT
                ROUND(SUM(ccm_models.net_capacity_20 + ccm_models.net_capacity_4))
        FROM
                cold_chain
        INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        WHERE
                (
                        (
                                cold_chain.ccm_asset_type_id = $id
                                OR AssetMainType.pk_id = $id
                        )
                )
        GROUP BY cold_chain.ccm_asset_type_id


        ) as total



       FROM
        stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN demand_master ON demand_master.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        WHERE

        demand_master.to_warehouse_id = 159
        AND DATE_FORMAT(stock_master.transaction_date,'%Y-%m-%d') <= '$date') A";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows[0]['used_percentage'];
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getShipmnetsReceiveList() {
        $data = $this->form_values;
        $shipment_id = $data['shipment_id'];
        $str_sql = "SELECT
            shipments_receive.pk_id,
            shipments_receive.shipment_product_batch_id,
            shipments_receive.quantity,
            shipments_receive.counter,
            shipments_receive.placement_location_id,
            shipments_receive.vvm_stage,
            shipments_receive.created_by,
            shipments_receive.created_date,
            shipments_receive.modified_by,
            shipments_receive.modified_date,
            shipment_product_batch.number,
            placement_locations.location_id
            FROM
            shipments_receive
            INNER JOIN shipment_product_batch ON shipment_product_batch.pk_id = shipments_receive.shipment_product_batch_id
            INNER JOIN placement_locations ON shipments_receive.placement_location_id = placement_locations.pk_id
            WHERE
            shipment_product_batch.shipment_id = $shipment_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getTempShipmentsList() {
        $data = $this->form_values;
        $shipment_id = $data['pk_id'];
        $str_sql = "SELECT
            shipments.pk_id,
            shipment_detail.pk_id as detail_id,
            shipments.reference_number,
            shipments.transaction_number,
            shipments.item_pack_size_id,
            shipments.shipment_date,
            shipment_detail.received_quantity quantity,
            shipments.funding_source_id,
            shipments.stakeholder_activity_id,
            shipments.warehouse_id,
            shipments.eta,
            shipments.draft,
            shipments.created_date,
            shipments.created_by,
            shipments.modified_by,
            shipments.modified_date,
            shipment_detail.received_quantity,
            item_pack_sizes.item_name,
            item_units.item_unit_name,
            shipment_history.reference_number,
            shipment_history.`status`,
            warehouses.warehouse_name,
            wh.warehouse_name as wh_name,
            stakeholder_activities.activity
            FROM
            shipments
            INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
            INNER JOIN item_pack_sizes ON shipment_detail.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
            INNER JOIN shipment_history ON shipment_history.shipment_id = shipments.pk_id
            INNER JOIN warehouses ON shipments.funding_source_id = warehouses.pk_id
            INNER JOIN warehouses as wh ON shipments.warehouse_id = wh.pk_id
            INNER JOIN stakeholder_activities ON shipments.stakeholder_activity_id = stakeholder_activities.pk_id
            WHERE
            shipments.draft = 1 AND
            shipments.warehouse_id = 159
            AND shipments.pk_id = $shipment_id";

        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getTempShipmentsDetailList() {
        $data = $this->form_values;
        $shipment_id = $data['pk_id'];
        $str_sql = "SELECT
            shipments.pk_id,
            
            shipments.reference_number,
            shipments.transaction_number,
            shipments.item_pack_size_id,
            shipments.shipment_date,
          
            shipments.funding_source_id,
            shipments.stakeholder_activity_id,
            shipments.warehouse_id,
            shipments.eta,
            shipments.draft,
            shipments.created_date,
            shipments.created_by,
            shipments.modified_by,
            shipments.modified_date,
            
           
            item_units.item_unit_name,
           
           
            warehouses.warehouse_name,
            wh.warehouse_name as wh_name,
            stakeholder_activities.activity,            

            shipment_product_batch.pk_id,
            shipment_product_batch.number,
            shipment_product_batch.expiry_date,
            shipment_product_batch.unit_price,
            shipment_product_batch.production_date,
            shipment_product_batch.vvm_type_id,
            shipment_product_batch.item_pack_size_id,
            shipment_product_batch.shipment_id,
            shipment_product_batch.draft,
            shipment_product_batch.created_by,
            shipment_product_batch.created_date,
            shipment_product_batch.modified_by,
            shipment_product_batch.modified_date,
            shipment_product_batch.quantity,
            item_pack_sizes.item_name,
            item_pack_sizes.item_unit_id,
            item_units.item_unit_name,
            stakeholders.stakeholder_name as manufacturer
            FROM
            shipment_product_batch
            INNER JOIN shipments ON shipment_product_batch.shipment_id = shipments.pk_id
            INNER JOIN warehouses ON shipments.funding_source_id = warehouses.pk_id
            INNER JOIN warehouses as wh ON shipments.warehouse_id = wh.pk_id
            INNER JOIN stakeholder_activities ON shipments.stakeholder_activity_id = stakeholder_activities.pk_id
            INNER JOIN item_pack_sizes ON shipment_product_batch.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON shipment_product_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            WHERE
                    shipment_product_batch.shipment_id = $shipment_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getTempShipmentsReceiveList() {
        $data = $this->form_values;
        $shipment_id = $data['pk_id'];
        $str_sql_c = "SELECT
            item_pack_sizes.item_category_id
            FROM
            shipments
            INNER JOIN shipment_detail ON shipment_detail.shipment_id = shipments.pk_id
            INNER JOIN item_pack_sizes ON shipment_detail.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
            shipments.pk_id = $shipment_id";


        // Get doctrine instance.
        $row_c = $this->_em_read->getConnection()->prepare($str_sql_c);
        // Execute and get result.
        $row_c->execute();
        $rows_c = $row_c->fetchAll();

        if ($rows_c[0]['item_category_id'] == 1) {
            $col = ',cold_chain.asset_id, vvm_stages.vvm_stage_value';
            $where = ' INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id INNER JOIN vvm_stages ON shipments_receive.vvm_stage = vvm_stages.pk_id';
        } else {
            $col = ',non_ccm_locations.location_name as asset_id';
            $where = 'INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id';
        }




        $str_sql = "SELECT
        shipments.pk_id,
        shipments.reference_number,
        shipments.transaction_number,
        shipments.item_pack_size_id,
        shipments.shipment_date,
        shipments.funding_source_id,
        shipments.stakeholder_activity_id,
        shipments.warehouse_id,
        shipments.eta,
        shipments.draft,
        shipments.created_date,
        shipments.created_by,
        shipments.modified_by,
        shipments.modified_date,
        item_units.item_unit_name,
        warehouses.warehouse_name,
        wh.warehouse_name AS wh_name,
        stakeholder_activities.activity,
        shipment_product_batch.pk_id,
        shipment_product_batch.number,
        shipment_product_batch.expiry_date,
        shipment_product_batch.unit_price,
        shipment_product_batch.production_date,
        shipment_product_batch.vvm_type_id,
        shipment_product_batch.item_pack_size_id,
        shipment_product_batch.shipment_id,
        shipment_product_batch.draft,
        shipment_product_batch.created_by,
        shipment_product_batch.created_date,
        shipment_product_batch.modified_by,
        shipment_product_batch.modified_date,
        shipment_product_batch.quantity,
        item_pack_sizes.item_name,
        item_pack_sizes.item_unit_id,
        item_units.item_unit_name,
        stakeholders.stakeholder_name AS manufacturer,
        shipments_receive.placement_location_id,
        shipments_receive.quantity as placed_qty,
        shipments_receive.vvm_stage
        $col
        FROM
        shipment_product_batch
        INNER JOIN shipments ON shipment_product_batch.shipment_id = shipments.pk_id
        INNER JOIN warehouses ON shipments.funding_source_id = warehouses.pk_id
        INNER JOIN warehouses AS wh ON shipments.warehouse_id = wh.pk_id
        INNER JOIN stakeholder_activities ON shipments.stakeholder_activity_id = stakeholder_activities.pk_id
        INNER JOIN item_pack_sizes ON shipment_product_batch.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON shipment_product_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
        INNER JOIN shipments_receive ON shipment_product_batch.pk_id = shipments_receive.shipment_product_batch_id
        INNER JOIN placement_locations ON shipments_receive.placement_location_id = placement_locations.pk_id
        
        $where
        WHERE
                    shipment_product_batch.shipment_id = $shipment_id
        ";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getTempShipmnetProcductBatchListQuantity() {

        $shipment_id = $this->form_values['shipment_id'];
        $str_sql = "SELECT
            shipment_product_batch.pk_id,
            shipment_product_batch.number,
            shipment_product_batch.expiry_date,
            shipment_product_batch.unit_price,
            shipment_product_batch.production_date,
            shipment_product_batch.vvm_type_id,
            shipment_product_batch.item_pack_size_id,
            shipment_product_batch.shipment_id,
            shipment_product_batch.draft,
            shipment_product_batch.created_by,
            shipment_product_batch.created_date,
            shipment_product_batch.modified_by,
            shipment_product_batch.modified_date,
            SUM(shipment_product_batch.quantity) quantity,
            item_pack_sizes.item_name,
            item_pack_sizes.item_unit_id,
            item_units.item_unit_name,
            stakeholders.stakeholder_name as manufacturer
            FROM
            shipment_product_batch
            INNER JOIN item_pack_sizes ON shipment_product_batch.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON shipment_product_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            WHERE
                    shipment_product_batch.shipment_id = $shipment_id
             Group By shipment_product_batch.shipment_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    public function addShipmentsCreateVoucher() {

        $form_values = $this->form_values;
        $shipment_id = $form_values['shipment_id'];

        $str_sql = "SELECT
               shipments.pk_id,
                shipments.reference_number transaction_reference,
                shipments.transaction_number,
                shipments.shipment_date transaction_date,
                shipments.funding_source_id from_warehouse_id,
                shipments.stakeholder_activity_id activity_id,
                shipment_product_batch.number,
                shipment_product_batch.expiry_date,
                shipment_product_batch.unit_price,
                shipment_product_batch.production_date,
                shipment_product_batch.vvm_type_id,
                placement_locations.location_id AS cold_chain,
                shipments_receive.quantity as quantity,
                
		shipments_receive.vvm_stage,
                shipment_product_batch.item_pack_size_id item_id,
                shipment_product_batch.stakeholder_item_pack_size_id  manufacturer_id,
                item_pack_sizes.item_unit_id
                
                
                
                FROM
                shipment_product_batch
                INNER JOIN shipments ON shipment_product_batch.shipment_id = shipments.pk_id
                INNER JOIN warehouses ON shipments.funding_source_id = warehouses.pk_id
                INNER JOIN warehouses AS wh ON shipments.warehouse_id = wh.pk_id
                INNER JOIN stakeholder_activities ON shipments.stakeholder_activity_id = stakeholder_activities.pk_id
                INNER JOIN item_pack_sizes ON shipment_product_batch.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON shipment_product_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
                INNER JOIN shipments_receive ON shipment_product_batch.pk_id = shipments_receive.shipment_product_batch_id
                
                INNER JOIN placement_locations ON shipments_receive.placement_location_id = placement_locations.pk_id
                
                WHERE
                            shipment_product_batch.shipment_id = $shipment_id";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();

        $stock_master = new Model_StockMaster();
        $stock_detail = new Model_StockDetail();
        // Receive Voucer starts



        $data = $rows[0];
        $data['rcvedit'] = 'no';
        $recedit = 'no';
        $data['transaction_date'] = $rows[0]['transaction_date'] . ' 0000:00:00';
        $data['transaction_type_id'] = 1;
        $data['comments'] = 'shipment';
        $data['shipment_id'] = $rows[0]['pk_id'];


        $stock_id = $stock_master->addStockMaster($data);
        foreach ($rows as $val) {
            $data = $val;
            $data['rcvedit'] = 'no';
            $recedit = 'no';
            $data['transaction_date'] = $val['transaction_date'] . ' 0000:00:00';
            $data['transaction_type_id'] = 1;
            $data['comments'] = 'shipment';
            $data['shipment_id'] = $val['pk_id'];
            $stock_batch = new Model_StockBatch();

            $data['stock_master_id'] = $stock_id;

            $batch_id = $stock_batch->addStockBatch($data);
            $role_id = $this->_identity->getRoleId();
            if ($role_id == 3 && !empty($data['expiry_date'])) {
                $stock_batch_wh = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
                if (count($stock_batch_wh) > 0) {
                    if ($stock_batch_wh->getQuantity() == 0) {
                        $stock_batch_wh->getStockBatch()->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['expiry_date'])));
                        $stock_batch_wh->getStockBatch()->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['production_date'])));
                        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                        $stock_batch_wh->getStockBatch()->setModifiedBy($created_by);
                        $stock_batch_wh->getStockBatch()->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($stock_batch_wh);
                        $this->_em->flush();
                    }
                }
            }

            $wh_id = $this->_identity->getWarehouseId();
            $query = "SELECT AdjustQty(" . $batch_id . "," . $wh_id . ") FROM DUAL";
            // Get doctrine instance.
            $row = $this->_em->getConnection()->prepare($query);
            // Execute and get result.
            $row->execute();
            //   $str_sql =  $this->_em->getConnection()->prepare($query);
            //   $str_sql->execute();

            $detail_id = $stock_detail->addStockDetailShipmentsReceive($data);
            $data['detail_id'] = $detail_id;

            if ($detail_id) {
                $stock_batch->autoRunningLEFOBatch($va['item_id']);

                if (!empty($data['used_for'])) {
                    $stock_used_for = new Model_StockUsedFor();
                    $stock_used_for->form_values = $data;
                    $stock_used_for->addStockUsedFor();
                }
            }
            $placement = new Model_Placements();
            if (!empty($data['cold_chain'])) {
                $placement->form_values['batchId'] = $batch_id;
                $placement->form_values['quantity'] = $data['quantity'];
                $placement->form_values['placement_location_id'] = $data['cold_chain'];
                $placement->form_values['item_category_id'] = $this->_em->getRepository("ItemPackSizes")->find($data['item_id'])->getItemCategory()->getPkId();
                $placement->form_values['stock_detail_id'] = $detail_id;
                $placement->form_values['rcvedit'] = $recedit;
                $placement->form_values['vvm_stage'] = $data['vvm_stage'];
                $placement->form_values['is_placed'] = 1;
                $placement->addPlacement();
            }
        }

        // Receive Voucher Ends
        // Update TEMP
        $stock_master->updateStockMasterTemp($stock_id, 'Shipments');
        $stock_detail->updateStockDetailTemp($stock_id);
        $warehouse_data = new Model_HfDataMaster();

        //Save Data in warehouse_data table
        $warehouse_data->addReport($stock_id, 1);

        $ShipmentHistory = $this->_em->getRepository('ShipmentHistory')->findBy(array('shipment' => $rows[0]['pk_id']));
        $ShipmentHistory1 = $this->_em->getRepository('ShipmentHistory')->find($ShipmentHistory[0]->getPkId());
        $ShipmentHistory1->setStatus('Received');
        $this->_em->persist($ShipmentHistory1);
        $this->_em->flush();
        return $ShipmentHistory1->getPkId();


        //  $this->view->msg = 'Stock has been received successfully. Your voucher number is ';
        //  $this->view->voucher = $master_affacted;
        //  $this->view->master_id = $this->_request->stockid;
        // END
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getShipmnetsReceiveVoucher() {
        $data = $this->form_values;
        $shipment_id = $data['shipment_id'];
        $str_sql = "SELECT
        stock_master.pk_id,
        stock_master.transaction_date,
        stock_master.transaction_number voucher,
        stock_master.transaction_counter,
        stock_master.transaction_reference,
        stock_master.dispatch_by,
        stock_master.draft,
        stock_master.comments,
        stock_master.transaction_type_id,
        stock_master.from_warehouse_id,
        stock_master.to_warehouse_id,
        stock_master.parent_id,
        stock_master.campaign_id,
        stock_master.stakeholder_activity_id,
        stock_master.created_by,
        stock_master.created_date,
        stock_master.issue_from,
        stock_master.issue_to,
        stock_master.modified_by,
        stock_master.modified_date,
        stock_master.shipment_id
        FROM
        stock_master
        WHERE
        stock_master.shipment_id = $shipment_id
        ";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getShipmentsVaccine() {
        $shipment_id = $this->shipment_id;
        if (empty($shipment_id)) {
            $shipment_id = 0;
        }

        $str_sql = "SELECT
            stock_batch.number,
            SUM(stock_batch_warehouses.quantity) quantity,
            stock_batch.expiry_date,
            ROUND(SUM(stock_batch_warehouses.quantity) / (pack_info.quantity_per_pack)) as carton,
            item_pack_sizes.item_category_id
            FROM
            stock_master
            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
                stock_master.transaction_number = '" . $this->transaction_number . "' AND
            stock_master.to_warehouse_id = '" . $this->to_warehouse_id . "' AND
          
            item_pack_sizes.item_category_id = 1
            GROUP BY stock_batch.number"
                . "  UNION "
                . " SELECT
	    stock_batch.number,
            SUM(stock_batch_warehouses.quantity) quantity,
            stock_batch.expiry_date,
            ROUND(SUM(stock_batch_warehouses.quantity) / (pack_info.quantity_per_pack)) as carton,
            item_pack_sizes.item_category_id
            FROM 
                    stock_master
            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
                       stock_master.transaction_number = '" . $this->transaction_number . "' AND
            stock_master.to_warehouse_id = '" . $this->to_warehouse_id . "' AND
             item_pack_sizes.item_category_id <> 1
            GROUP BY
                    stock_batch.number";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Temp Shipments List
     *
     * @return boolean
     */
    public function getShipmentsNonVaccine() {
        $shipment_id = $this->shipment_id;
        if (empty($shipment_id)) {
            $shipment_id = 0;
        }

        $str_sql = "SELECT
            stock_batch.number,
            stock_batch_warehouses.quantity,
            stock_batch.expiry_date
            FROM
            stock_master
            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
            stock_master.shipment_id = $shipment_id AND
            item_pack_sizes.item_category_id = 1";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();


        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return FALSE;
        }
    }

}
