<?php

/**
 * Api_IndexController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Api
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Controller for Api Index Controller
 */
class Api_IndexController extends App_Controller_Base {

    /**
     * Api_IndexController init
     */
    public function init() {
        parent::init();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $auth = $this->_request->getParam('auth', '');
        $page = $this->_request->getParam('action');

        /**
         * Access these pages without authentication
         */
        $pages_without_auth = array(
            'get-warehouses-by-level',
            'register-user'
        );

        if (!in_array($page, $pages_without_auth) && (empty($auth) || !$this->authenticateUser($auth))) {
            $return = array(array("error" => 'Please provide authentication'));
            echo Zend_Json::encode($return);
            exit;
        }
    }

    /**
     * Authenticate User
     * @param type $auth
     * @return type boolean
     */
    private function authenticateUser($auth) {
        $user = $this->_em->getRepository("Users")->findOneBy(array('auth' => $auth));
        return count($user) > 0;
    }

    /**
     * Authenticate User
     */
    public function authenticateUserAction() {
        if ($this->_request->getParam('auth')) {
            if ($this->_identity->loginAuth($this->_request->getParam('auth'))) {
                $return = array(array("userId" => $this->_identity->getIdentity(), "userName" => $this->_identity->getUserName(), "WHID" => $this->_identity->getWarehouseId(), "WHName" => $this->_identity->getWarehouseName()));
            }
        } else {
            $return = array(array("error" => 'Please provide authentication'));
        }
        echo Zend_Json::encode($return);
    }

    /**
     * Get Item All Batches
     * @author Gul Muhammad <gul@deliver-pk.org>
     * Used by Barcode services
     */
    public function getItemAllBatchesAction() {
        $wh_id = $this->_request->getParam('wh_id');
        if ($wh_id) {
            $stock_batch = new Model_StockBatch();
            $result = $stock_batch->getItemAllBatches($wh_id);
        } else {
            $result = array("error" => "Please provide whid param. e.g. (?whid=159)");
        }
        echo Zend_Json::encode($result);
    }

    /**
     * Get Location By Wh Id
     */
    public function getLocationByWhIdAction() {

        if ($this->_request->getParam('wh_id')) {
            $wh_id = $this->_request->getParam('wh_id');
            $type_id = $this->_request->getParam('type_id');
            $temp = explode("-", $type_id);
            $type = implode(",", $temp);
            $coldchain = new Model_ColdChain();
            $result = $coldchain->getLocationByWhId($wh_id, $type);
        } else {
            $result = array("error" => "Please provide wh_id param. e.g. (?wh_id=1)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * @name $getWarehousesByLevel
     * @param int $level
     * 
     * This service will return warehouses by level
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function getWarehousesByLevelAction() {

        $level = $this->_request->getParam('level', '');
        if (!empty($level)) {
            $wh = new Model_Warehouses();
            $result = $wh->getWarehousesByLevel($level);
        } else {
            $result = array("error" => "Please provide level param. e.g. (?level=2)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * @name $registerUser
     * @param int $wh_id
     * @param varchar $hash
     * 
     * This service will update auth token for user
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function registerUserAction() {

        $wh_id = $this->_request->getParam('wh_id', '');
        $hash = $this->_request->getParam('hash', '');

        if (!empty($wh_id)) {
            $users = new Model_Users();
            $result = $users->updateUserToken($hash, $wh_id);
        } else {
            $result = array(array("error" => "Please provide wh_id param. e.g. (?wh_id=&hash=)"));
        }

        echo Zend_Json::encode($result);
    }

    /**
     * @name $getPlacementsSummary
     * @param int $wh_id Warehouse ID
     * @param int $type Location Type ID i.e. 99 - CCEM, 100 - Non CCEM
     * 
     * This service will return placement summary for both ccem and non ccem
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function getPlacementsSummaryAction() {

        $wh_id = $this->_request->getParam('wh_id', '');
        $type = $this->_request->getParam('type', '');

        if (!empty($wh_id)) {
            $placement_location = new Model_PlacementLocations();
            $result = $placement_location->getPlacementsSummary($wh_id, $type);
        } else {
            $result = array(array("error" => "Please provide wh_id param. e.g. (?wh_id=&type=)"));
        }

        echo Zend_Json::encode($result);
    }

    /**
     * @name $getPipelineShipment
     * @param int $wh_id
     * @param int $type
     * 
     * This service will return all Pipeline Consignments which are either 
     * completely not received or partially received sorted by arrival date.
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function getPipelineConsignmentsAction() {

        $type = $this->_request->getParam('type');
        if ($this->_request->getParam('wh_id')) {
            $wh_id = $this->_request->getParam('wh_id');
            $pipeline_consignments = new Model_PipelineConsignments();
            if ($type == 1) {
                $result = $pipeline_consignments->getPipelineConsignmentsByWh($wh_id);
            } else {
                $result = $pipeline_consignments->getPlannedIssueByWh($wh_id);
            }
        } else {
            $result = array(array("error" => "Please provide wh_id param. e.g. (?wh_id=1)"));
        }

        echo Zend_Json::encode($result);
    }

    /**
     * @name $uploadPipelineConsignments
     * @param int $wh_id Warehouse ID
     * @param int $rec_id Record ID (PK id shipment table)
     * @param varchar $voucher Temprary Voucher Number
     * @param varchar $batch_no Batch no
     * @param float $qty Batch received quantity
     * @param int $location_id Placement Location id where the stock is placed
     * 
     * This service will upload future shipment transactions, for first call with master id zero, 
     * service will process Stock Master and Stock Detail & Stock Batch Trans with specified batch 
     * and Qty and rest of the required information from shipment table and returns master id, 
     * in next call with master id service will process stock detail with specified batch and qty.
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function uploadPipelineConsignmentsAction() {

        $type = $this->_request->getParam('type', 0);

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            $params = $this->_request->getParams();


            if ($type == 3) {
                $stock_master = new Model_StockMaster();
                $stock_master->form_values = $params;
                $result = $stock_master->uploadReceivedQuantityViaScanner();
            } else {
                $rec_id = $this->_request->getParam('rec_id', 0);
                $pipeline_consignments = $this->_em->getRepository("PipelineConsignments")->find($rec_id);

                if (count($pipeline_consignments) > 0 && $pipeline_consignments->getStatus() == 'Received') {
                    $result = array("message" => 104);
                    echo Zend_Json::encode(array($result));
                    return false;
                }

                if ($rec_id > 0) {
                    $params = $this->_request->getParams();
                    $pipeline_consignments = new Model_PipelineConsignments();
                    $pipeline_consignments->form_values = $params;
                    $result = $pipeline_consignments->updatePipelineConsignmentsReceivedQty();
                } else {
                    $params = $this->_request->getParams();
                    $pipeline_consignments = new Model_PipelineConsignments();
                    $pipeline_consignments->form_values = $params;
                    $result = $pipeline_consignments->insertPipelineConsignments();
                }
            }
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            App_FileLogger::info($e);
            $result = array("error" => "An error occur");
        }

        echo Zend_Json::encode(array($result));
    }

    /**
     * @name $getIssueVoucherList
     * @param int $wh_id
     * 
     * This service will return all Issue voucher which are 
     * not received or partially received sorted by issue date.
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function getIssueVoucherListNotReceivedAction() {

        if ($this->_request->getParam('wh_id')) {
            $wh_id = $this->_request->getParam('wh_id');
            $stock_master = new Model_StockMaster();
            $result = $stock_master->getIssueVoucherListNotReceived($wh_id);
        } else {
            $result = array(array("error" => "Please provide wh_id param. e.g. (?wh_id=1)"));
        }

        echo Zend_Json::encode($result);
    }

    /**
     * @name $uploadReceiveVouchers
     * @param int $rec_id Record ID (PK id stock_detail table)
     * @param float $qty Batch received quantity
     * @param int $location_id Placement Location id where the stock is placed
     * @param int $vvmstage VvmStage
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function uploadReceiveVouchersAction() {

        if ($this->_request->getParam('rec_id')) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $params = $this->_request->getParams();

                $stock_master = new Model_StockMaster();
                $stock_master->form_values = $params;
                $result = $stock_master->uploadReceivedQuantityViaScanner();
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
                $result = array("error" => "An error occur");
            }
        } else {
            $result = array("error" => "Please provide rec_id param. e.g. (?rec_id=1)");
        }

        echo Zend_Json::encode(array($result));
    }

    /**
     * Get Table Data
     */
    public function getTableDataAction() {
        $query = $this->_request->getParam('query');

        $table = new Model_Base();
        $result = $table->getTableData($query);
        echo Zend_Json::encode($result);
    }

    /**
     * Get Issue Voucher Items
     */
    public function getIssueVoucherItemsAction() {

        $voucher = $this->_request->getParam('voucher');
        $wh_id = $this->_request->getParam('wh_id');

        if (!empty($voucher)) {
            $pipeline_consignments = new Model_PipelineConsignments();
            $result = $pipeline_consignments->getIssueVoucherItemsList($voucher, $wh_id);
        } else {
            $result = array("error" => "Please provide transaction number. e.g. (?voucher=12)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Get Issue Voucher List
     */
    public function getIssueVoucherListAction() {

        $wh_id = $this->_request->getParam('wh_id', '');
        $pipeline_consignments = new Model_PipelineConsignments();
        $result = $pipeline_consignments->getPlannedIssueByWh($wh_id);
        echo Zend_Json::encode($result);
    }

    /**
     * Search Batch By Number
     */
    public function searchBatchByNumberAction() {


        $wh_id = $this->_request->getParam('wh_id', '');
        $batch_no = $this->_request->getParam('batch_no', '');

        if (!empty($wh_id) || !empty($batch_no)) {
            $coldchain = new Model_ColdChain();
            $result = $coldchain->searchBatchByBatchNo($wh_id, $batch_no);
        } else {
            $result = array("error" => "Please provide these params. e.g. (?wh_id=1&batch_no=I1010)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Get Item Batch List
     */
    public function getItemBatchListAction() {

        if ($this->_request->getParam('item_id')) {
            $item_id = $this->_request->getParam('item_id');
            $stock_batch = new Model_StockBatch();
            $stock_batch->form_values = array("item_id" => $item_id);
            $result = $stock_batch->getAllBatchesByItemId();
        } else {
            $result = array("error" => "Please provide item_id param. e.g. (?item_id=1)");
        }
        echo Zend_Json::encode($result);
    }

    /**
     * Get Item List
     */
    public function getItemListAction() {


        $items = new Model_ItemPackSizes();
        $items_list = $items->getAllItems();

        echo Zend_Json::encode($items_list);
    }

    /**
     * Get Vvm Stage List
     */
    public function getVvmStageListAction() {
        echo "Hello";
    }

    /**
     * Get Receive Voucher Items
     */
    public function getReceiveVoucherItemsAction() {


        $voucher_id = $this->_request->getParam('voucher_id');
        if (!empty($voucher_id)) {
            $stock_master = new Model_StockMaster();
            $result = $stock_master->detailDataReceiveno($voucher_id);
        } else {
            $result = array("error" => "Please provide transaction number. e.g. (?voucher_id =15)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Get Receive Voucher List
     */
    public function getReceiveVoucherListAction() {

        $wh_id = $this->_request->getParam('wh_id', '');
        $stock_master = new Model_StockMaster();
        $result = $stock_master->getUnplacedVoucherNo($wh_id);

        echo Zend_Json::encode($result);
    }

    /**
     * Search Item By Location Id
     */
    public function searchItemByLocationIdAction() {


        $asset_id = $this->_request->getParam('asset_id', '');
        if (!empty($asset_id)) {
            $coldchain = new Model_ColdChain();
            $result = $coldchain->searchItemByLocationId($asset_id);
        } else {
            $result = array("error" => "Please provide coldchain asset id. e.g. (?asset_id=1)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Get Stakeholder List
     */
    public function getStakeholderListAction() {

        $stakeholders = new Model_Stakeholders();
        $result = $stakeholders->getStakholder();

        echo Zend_Json::encode($result);
    }

    /**
     * Adds placement.
     */
    public function addPlacementAction() {

        $array = array(
            'stock_batch_id' => $this->_request->getParam('batch-id'),
            'quantity' => $this->_request->getParam('quanty'),
            'placement_location_id' => $this->_request->getParam('plac-loc-id'),
            'stock_detail_id' => $this->_request->getParam('detail-id')
        );

        $placement = new Model_Placements();
        $placement->form_values = $array;
        $placement->addPlacement();

        $result = array(array('message' => "Placement added successfully!"));
        echo Zend_Json::encode($result);
    }

    /**
     * Get Ccm Locations
     */
    public function getCcmLocationsAction() {

        $wh_id = $this->_request->getParam('wh_id');
        if ($wh_id) {
            $coldchain = new Model_ColdChain();
            $result = $coldchain->getCCMLocations($wh_id);
        } else {
            $result = array("error" => "Please provide wh_id param. e.g. (?wh_id=1)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Get Item Categories
     */
    public function getItemCategoriesAction() {


        $items = new Model_Items();
        $result = $items->getItemCategories();

        echo Zend_Json::encode($result);
    }

    /**
     * Get Item Pack Sizes
     */
    public function getItemPackSizesAction() {


        $items = new Model_Items();
        $result = $items->getItemPackSizes();

        echo Zend_Json::encode($result);
    }

    /**
     * Get Items
     */
    public function getItemsAction() {

        $items = new Model_Items();
        $result = $items->getItems();

        echo Zend_Json::encode($result);
    }

    /**
     * Get Item Units
     */
    public function getItemUnitsAction() {


        $items = new Model_Items();
        $result = $items->getItemUnits();

        echo Zend_Json::encode($result);
    }

    /**
     * Get Non Ccm Locations
     */
    public function getNonCcmLocationsAction() {

        $wh_id = $this->_request->getParam('wh_id');
        if ($wh_id) {
            $nonccm = new Model_ColdChain();
            $result = $nonccm->getNonCCMLocations($wh_id);
        }
        echo Zend_Json::encode($result);
    }

    /**
     * Get Placement Locations
     */
    public function getPlacementLocationsAction() {

        $wh_id = $this->_request->getParam('wh_id');
        $type = $this->_request->getParam('loctype');

        $locations = new Model_Locations();
        $result = $locations->getPlacementLocations($wh_id, $type);

        echo Zend_Json::encode($result);
    }

    /**
     * Get Rack Information
     */
    public function getRackInformationAction() {


        $locations = new Model_Locations();
        $result = $locations->getRackInformation();

        echo Zend_Json::encode($result);
    }

    /**
     * Get Stakeholder Items
     */
    public function getStakeholderItemsAction() {


        $items = new Model_Items();
        $result = $items->getStakeholderItems();

        echo Zend_Json::encode($result);
    }

    /**
     * Get Item Batches
     */
    public function getItemBatchesAction() {


        if ($this->_request->getParam('wh_id')) {
            $coldchain = new Model_StockBatch();
            $coldchain->form_values['wh_id'] = $this->_request->getParam('wh_id');
            $result = $coldchain->getItemBatches();
        } else {
            $result = array("error" => "Please provide wh_id param. e.g. (?wh_id=1)");
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Upload Transactions
     * 14 July 2014 - Modify - Startu
     */
    public function uploadTransactionsAction() {
        try {
            $data = array();
            $data['quantity'] = $this->_request->getParam('qty');
            $data['placement_loc_id'] = $this->_request->getParam('placement_loc_id');
            $data['placement_loc_type_id'] = $this->_request->getParam('placement_loc_type_id');
            $data['batch_id'] = $this->_request->getParam('batch_id');
            $data['detail_id'] = $this->_request->getParam('detail_id');
            $data['created_date'] = $this->_request->getParam('created_date');
            $data['user_id'] = $this->_request->getParam('user_id');
            $data['vvmstage'] = $this->_request->getParam('vvmstage');
            $data['is_placed'] = $this->_request->getParam('is_placed');

            $placement = new Model_Placements();
            $placement->form_values = $data;
            $result[]['id'] = $placement->add();
        } catch (OptimisticLockException $e) {
            App_FileLogger::info($e);
            $result[]['message'] = "Failed";
        }

        echo Zend_Json::encode($result);
    }

    /**
     * Search Batch Locations
     * 14 July 2014 - Modify - Start
     * 08 July 2014 - Start
     */
    public function searchBatchLocationsAction() {

        $data = array();
        $data['batch_id'] = $this->_request->getParam('batch_id');
        $data['wh_id'] = $this->_request->getParam('wh_id');
        $data['loc_type'] = $this->_request->getParam('loc_type');

        $placements = new Model_Placements();
        $placements->form_values = $data;
        $result = $placements->searchBatchLocations();

        echo Zend_Json::encode($result);
    }

    /**
     * Search Locations Products
     */
    public function searchLocationsProductsAction() {

        $data = array();
        $data['p_loc_id'] = $this->_request->getParam('p_loc_id');
        $data['wh_id'] = $this->_request->getParam('wh_id');
        $data['loc_type'] = $this->_request->getParam('loc_type');

        $placements = new Model_Placements();
        $placements->form_values = $data;
        $result = $placements->searchLocationsProducts();

        echo Zend_Json::encode($result);
    }

    /**
     * Update Vvm Stage
     */
    public function updateVvmStageAction() {
        try {
            $data = array();
            $data['batch_id'] = $this->_request->getParam('batch_id');
            $data['placement_id'] = $this->_request->getParam('placement_id');
            $data['vvm_stage'] = $this->_request->getParam('vvm_stage');
            $data['qty'] = $this->_request->getParam('qty');
            $data['old_vvm_stage'] = $this->_request->getParam('old_vvm_stage');

            $placements = new Model_Placements();
            $placements->form_values = $data;
            $result = $placements->updateVvmStage();

            echo Zend_Json::encode(array($result));
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            App_FileLogger::info($e);
            $result = array("error" => "An error occur");
        }
    }

    /**
     * Get Stakeholder Item Pack Sizes
     */
    public function getStakeholderItemPackSizesAction() {


        $items = new Model_Items();
        $result = $items->getStakeholderItemPackSizes();

        echo Zend_Json::encode($result);
    }

    /**
     * Get Ccm Locations Status
     */
    public function getCcmLocationsStatusAction() {
        $wh_id = $this->_request->getParam('wh_id');
        $locations = new Model_Locations();
        $result = $locations->getCcmLocationsStatus($wh_id);

        echo Zend_Json::encode($result);
    }

    /**
     * Get Non Ccm Locations Status
     */
    public function getNonCcmLocationsStatusAction() {
        $wh_id = $this->_request->getParam('wh_id');
        $locations = new Model_Locations();
        $result = $locations->getNonCcmLocationsStatus($wh_id);

        echo Zend_Json::encode($result);
    }

    /**
     * Upload Stock Ticking
     */
    public function uploadStockTickingAction() {
        $params = $this->_request->getParams();
        $stock_ticking = new Model_StockTicking();
        $stock_ticking->form_values = $params;
        $result = $stock_ticking->uploadStockTicking();

        echo Zend_Json::encode($result);
    }

    /**
     * Gets list detail items.
     * 
     * 
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     */
    public function getListDetailAction() {

        if ($this->_request->getParam('master_id')) {
            $master_id = $this->_request->getParam('master_id');
            $list_detail = new Model_ListDetail();
            $list_detail->form_values['master_id'] = $master_id;
            $result = $list_detail->getListDetailByMasterId();
        } else {
            $result = array(array("error" => "Please provide detail master id param. e.g. (?master_id=33)"));
        }

        echo Zend_Json::encode($result);
    }

}
