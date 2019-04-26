<?php

/**
 * StockBatchControlle
 *
 *
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Stock Batch
 */
class StockBatchController extends App_Controller_Base {

    /**
     * StockBatchController index
     */
    public function indexAction() {
        $form = new Form_StockBatch();
        $stock_batch = new Model_StockBatch();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $stock_batch->form_values = $data;
                $status = $this->_request->getPost('status');
            }

            if (empty($data['searchinput'])) {
                $form->searchby->setValue("");
            }
        } else {
            $status = 6;
            $stock_batch->form_values = array('warehouse_id' => $this->_identity->getWarehouseId(), 'item_pack_size_id' => 3);
            $form->status->setValue($status);
        }

        $data = $stock_batch->searchStockBatch();
        $this->view->data = $data;
        $this->view->form = $form;
        $this->view->status = $status;
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * mergeStakeholderItemPackSizeAction
     */
    public function mergeStakeholderItemPackSizeAction() {
        $item_pack_sizes = new Model_ItemPackSizes();
        $items = $item_pack_sizes->getAllManageItems();
        $this->view->items = $items;

        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * ajaxMergeStakeholderItemPackSizeAction
     */
    public function ajaxMergeStakeholderItemPackSizeAction() {

        $this->_helper->layout->disableLayout();

        $sips = new Model_StakeholderItemPackSizes();
        $sips->form_values = array(
            'item_id' => $this->_request->getParam('product')
        );
        $result = $sips->getStakeholderItemPackSizesByItem();

        $this->view->result = $result;
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * ajaxValidateMergeStakeholdersAction
     */
    public function ajaxValidateMergeStakeholdersAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $data = explode("|", substr($this->_request->data, 0, -1));
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            $sips[] = $this->_em->getRepository("StakeholderItemPackSizes")->find($data[$i]);
        }

        $counter = 0;
        $percent = 0;
        $stakeholder_name[0] = $sips[0]->getStakeholder()->getStakeholderName();
        for ($i = 1; $i < $count; $i++) {
            $stakeholder_name[$i] = $sips[$i]->getStakeholder()->getStakeholderName();
            similar_text($stakeholder_name[$i - 1], $stakeholder_name[$i], $percent);
            if ($percent < 90) {
                $counter += 1;
            }
        }

        echo $counter;
    }

    /**
     * ajaxMergeStakeholderItemPackSizeId
     */
    public function ajaxMergeStakeholderItemPackSizeIdAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        $created_by = $this->_em->find('Users', $this->_user_id);
        try {
            $data = explode("|", substr($this->_request->data, 0, -1));
            $count = count($data);
            $merge_into = $this->_request->merge;

            for ($i = 0; $i < $count; $i++) {
                $sips[] = $this->_em->getRepository("StakeholderItemPackSizes")->find($data[$i]);
            }

            $stakeholder_merge = $this->_em->getRepository("StakeholderItemPackSizes")->find($merge_into);

            for ($i = 0; $i < $count; $i++) {
                $sips_id = $sips[$i]->getPkId();
                $stk_id = $sips[$i]->getStakeholder()->getPkId();

                $stock_batch = $this->_em->getRepository("StockBatch")->findBy(array("stakeholderItemPackSize" => $sips_id));
                if (count($stock_batch) > 0) {
                    foreach ($stock_batch as $sd) {
                        $sd->setStakeholderItemPackSize($stakeholder_merge);
                        $sd->setModifiedBy($created_by);
                        $sd->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($sd);
                    }
                }

                $geo_location = $this->_em->getRepository("GeoLocations")->findBy(array("stakeholder" => $stk_id));
                if (count($geo_location) > 0) {
                    foreach ($geo_location as $gl) {
                        $gl->setStakeholder($stakeholder_merge->getStakeholder());
                        $gl->setModifiedBy($created_by);
                        $gl->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($gl);
                    }
                }

                $pipeline_consignements = $this->_em->getRepository("PipelineConsignments")->findBy(array("manufacturer" => $sips_id));
                if (count($pipeline_consignements) > 0) {
                    foreach ($pipeline_consignements as $pc) {
                        $pc->setManufacturer($stakeholder_merge);
                        $pc->setModifiedBy($created_by);
                        $pc->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($pc);
                    }
                }

                $pipeline_consignements_draft = $this->_em->getRepository("PipelineConsignmentsDraft")->findBy(array("manufacturer" => $sips_id));
                if (count($pipeline_consignements_draft) > 0) {
                    foreach ($pipeline_consignements_draft as $pcd) {
                        $pcd->setManufacturer($stakeholder_merge);
                        $pcd->setModifiedBy($created_by);
                        $pcd->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($pcd);
                    }
                }

                $users = $this->_em->getRepository("Users")->findBy(array("stakeholder" => $stk_id));
                if (count($users) > 0) {
                    foreach ($users as $usr) {
                        $usr->setStakeholder($stakeholder_merge->getStakeholder());
                        $usr->setModifiedBy($created_by);
                        $usr->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($usr);
                    }
                }

                $warehouses = $this->_em->getRepository("Warehouses")->findBy(array("stakeholder" => $stk_id));
                if (count($warehouses) > 0) {
                    foreach ($warehouses as $wh) {
                        $wh->setStakeholder($stakeholder_merge->getStakeholder());
                        $wh->setModifiedBy($created_by);
                        $wh->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($wh);
                    }
                }

                if ($sips[$i]->getStakeholder()->getPkId() != $stakeholder_merge->getStakeholder()->getPkId()) {
                    $this->_em->remove($sips[$i]->getStakeholder());
                }

                if ($merge_into != $sips_id) {
                    $this->_em->remove($sips[$i]);
                }

                $stk_item_pack = $this->_em->getRepository("StakeholderItemPackSizes")->findBy(array("stakeholder" => $stk_id));
                if (count($stk_item_pack) > 0) {
                    foreach ($stk_item_pack as $stkips) {
                        $stkips->setStakeholder($stakeholder_merge->getStakeholder());
                        $stkips->setModifiedBy($created_by);
                        $stkips->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($stkips);
                    }
                }

                $this->_em->flush();
            }

            $em->getConnection()->commit();
            echo 1;
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            App_FileLogger::info($e);
            echo 0;
        }
    }

    /**
     * Merge Manufacturers
     */
    public function mergeManufacturersAction() {
        $stakeholders = new Model_Stakeholders();
        $data = $stakeholders->getAllManufacturers();
        $this->view->result = $data;
    }

    /**
     * ajaxProductBatches
     */
    public function ajaxProductBatchesAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            $item_units = new Model_ItemUnits();
            $item_units->form_values['item_pack_size_id'] = $this->_request->item_id;
            $array = $item_units->getUnitByItemId();
            if ($array) {
                $this->view->type = $array['type'];
                $this->view->id = $array['id'];
            }
        }
    }

    /**
     * ajaxGetBatchColdchain
     */
    public function ajaxGetBatchColdchainAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->batch) && !empty($this->_request->batch)) {
            $pick_from = new Model_ColdChain();
            $pick_from->form_values = array(
                'batch' => $this->_request->batch
            );
            $this->view->combo = $pick_from->getColdchainByBatch();
        }
    }

    /**
     * ajaxProductCategory
     */
    public function ajaxProductCategoryAction() {
        $this->_helper->layout->disableLayout();
        $item_pack_sizes = new Model_ItemPackSizes();
        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->item_id;
            $this->view->category_name = $item_pack_sizes->getProductCategory();
        }

        if (isset($this->_request->quantity) && !empty($this->_request->quantity) && !empty($this->_request->item_id)) {
            $qty = $this->_request->quantity;
            $item_pack_sizes->form_values['pk_id'] = $this->_request->item_id;
            $doses = $item_pack_sizes->getProductDoses();
            $this->view->doses = $doses * $qty . ' Doses';
        }
    }

    /**
     * ajaxBatchDetail
     */
    public function ajaxBatchDetailAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $stock_batch = new Model_StockBatch();
            $stock_batch->form_values['item_pack_size_id'] = $this->_request->id;

            $items = $this->_em->getRepository("ItemPackSizes")->find($this->_request->id);

            if ($items->getItemCategory()->getPkId() == 1 || $items->getItemCategory()->getPkId() == 4) {
                $result = $stock_batch->getBatchDetail();
            } else {
                $result = $stock_batch->getNonVaccineBatchDetail();
            }
            $this->view->result = $result;
        }
    }

    /**
     * ajaxChangeStatus
     */
    public function ajaxChangeStatusAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost()) {
            $batch_id = $this->_request->getPost('batch_id');
            $status = $this->_request->getPost('status');

            if ($status == Model_StockBatch::RUNNING || $status == Model_StockBatch::FINISHED) {
                $button = Model_StockBatch::STACKED;
            } else {
                $button = Model_StockBatch::RUNNING;
            }

            $stock_batch = new Model_StockBatch();
            $stock_batch->form_values['status'] = $button;
            $stock_batch->form_values['pk_id'] = $batch_id;
            $result = $stock_batch->changeStatus();

            if ($result) {
                $array = array(
                    'status' => $button,
                    'button' => $status
                );
            } else {
                $array = array(
                    'status' => $status,
                    'button' => $button
                );
            }

            $this->_helper->json($array);
        }
    }

    /**
     * Batch Summary
     */
    public function batchSummaryAction() {
        $this->_helper->layout->setLayout('print');
        $stock_batch = new Model_StockBatch();

        if (!empty($this->_request->type)) {
            $stock_batch->form_values = $this->_request->getParams();
            $data = $stock_batch->searchStockBatch();
            $this->view->data = $data;
            $this->view->print_title = "Batch Management";
            $this->view->status = $this->_request->getParam('status');
        } else {
            $stock_batch->form_values['adjustment_no'] = $this->_request->adjustment_no;
            $stock_batch->form_values['adjustment_type'] = $this->_request->adjustment_type;
            $stock_batch->form_values['product'] = $this->_request->product;
            $stock_batch->form_values['date_from'] = $this->_request->date_from;
            $stock_batch->form_values['date_to'] = $this->_request->date_to;
            $summary = $stock_batch->batchSummary();
            $expired_summary = $stock_batch->expiredBatchSummary();
            $this->view->summary = $summary;
            $this->view->expired_summary = $expired_summary;
            $this->view->print_title = "Batch Management Summary";
        }
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
    }

    /**
     * Non Vaccine Batch Summary
     */
    public function nonVaccineBatchSummaryAction() {
        $this->_helper->layout->setLayout('print');
        $stock_batch = new Model_StockBatch();

        if (!empty($this->_request->type)) {
            $stock_batch->form_values = $this->_request->getParams();
            $data = $stock_batch->searchStockBatch();
            $this->view->data = $data;
            $this->view->print_title = "Batch Management";
            $this->view->status = $this->_request->getParam('status');
        } else {
            $stock_batch->form_values['adjustment_no'] = $this->_request->adjustment_no;
            $stock_batch->form_values['adjustment_type'] = $this->_request->adjustment_type;
            $stock_batch->form_values['product'] = $this->_request->product;
            $stock_batch->form_values['date_from'] = $this->_request->date_from;
            $stock_batch->form_values['date_to'] = $this->_request->date_to;
            $summary = $stock_batch->nonVaccineBatchSummary();
            $expired_summary = $stock_batch->nonVaccineExpiredBatchSummary();
            $this->view->summary = $summary;
            $this->view->expired_summary = $expired_summary;
            $this->view->print_title = "Batch Management Summary";
        }
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
    }

    /**
     * Stock Placement Comparison Report
     */
    public function stockPlacementComparisonAction() {

        if ($this->_request->isPost()) {
            $wh_id = $this->_request->getParam('warehouse');
        } else {
            // Federal Vaccine Store
            $wh_id = 159;
        }

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['wh_id'] = $wh_id;

        $batch_total = $stock_batch->batchProductTotal();
        $this->view->batch_total = $batch_total;

        $stakeholder_total = $stock_batch->stakeholderProductTotal();
        $this->view->stakeholder_total = $stakeholder_total;

        $priority_total = $stock_batch->priorityProductTotal();
        $this->view->priority_total = $priority_total;

        $warehouse = new Model_Warehouses;
        $wh_name = $warehouse->getWarehouseNameByWarehouseId($wh_id);
        $this->view->warehousename = $wh_name;
        $this->view->warehouseid = $wh_id;

        $params = array(
            "office" => $this->_request->getParam('office', 0),
            "province" => $this->_request->getParam('combo1', $this->_identity->getProvinceId()),
            "district" => $this->_request->getParam('combo2', $this->_identity->getDistrictId()),
            "warehouse" => $this->_request->getParam('warehouse', 0)
        );

        $this->view->params = $params;

        $base_url = Zend_Registry::get('baseurl');

        $this->view->headScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * Stock Placement Comparison Report Print
     */
    public function stockPlacementComparisonPrintAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Placement Comparison");
        $this->view->print_title = "Stock Placement Comparison";

        $wh_id = $this->_request->getParam('id');
        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['wh_id'] = $wh_id;

        $batch_total = $stock_batch->batchProductTotal();
        $this->view->batch_total = $batch_total;
        $stakeholder_total = $stock_batch->stakeholderProductTotal();
        $this->view->stakeholder_total = $stakeholder_total;
        $priority_total = $stock_batch->priorityProductTotal();
        $this->view->priority_total = $priority_total;

        $warehouse = new Model_Warehouses;
        $wh_name = $warehouse->getWarehouseNameByWarehouseId($wh_id);
        $this->view->warehousename = $wh_name;
        $this->view->warehouseid = $wh_id;
    }

    /**
     * Dry Store Stock Placement Comparison Report
     */
    public function dsStockPlacementComparisonAction() {

        if ($this->_request->isPost()) {
            $wh_id = $this->_request->getParam('warehouse');
        } else {
            // Federal Vaccine Store
            $wh_id = 159;
        }

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['wh_id'] = $wh_id;

        $batch_total = $stock_batch->dsBatchProductTotal();
        $this->view->batch_total = $batch_total;
        $stakeholder_total = $stock_batch->dsStakeholderProductTotal();
        $this->view->stakeholder_total = $stakeholder_total;
        $priority_total = $stock_batch->dsPlacementProductTotal();
        $this->view->priority_total = $priority_total;

        $warehouse = new Model_Warehouses;
        $wh_name = $warehouse->getWarehouseNameByWarehouseId($wh_id);
        $this->view->warehousename = $wh_name;
        $this->view->warehouseid = $wh_id;

        $params = array(
            "office" => $this->_request->getParam('office', 0),
            "province" => $this->_request->getParam('combo1', $this->_identity->getProvinceId()),
            "district" => $this->_request->getParam('combo2', $this->_identity->getDistrictId()),
            "warehouse" => $this->_request->getParam('warehouse', 0)
        );

        $this->view->params = $params;

        $base_url = Zend_Registry::get('baseurl');

        $this->view->headScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * Dry Store Stock Placement Comparison Report Print
     */
    public function dsStockPlacementComparisonPrintAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Dry Store Stock Placement Comparison");
        $this->view->print_title = "Dry Store Stock Placement Comparison";

        $wh_id = $this->_request->getParam('id');
        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['wh_id'] = $wh_id;

        $batch_total = $stock_batch->dsBatchProductTotal();
        $this->view->batch_total = $batch_total;
        $stakeholder_total = $stock_batch->dsStakeholderProductTotal();
        $this->view->stakeholder_total = $stakeholder_total;
        $priority_total = $stock_batch->dsPlacementProductTotal();
        $this->view->priority_total = $priority_total;

        $warehouse = new Model_Warehouses;
        $wh_name = $warehouse->getWarehouseNameByWarehouseId($wh_id);
        $this->view->warehousename = $wh_name;
        $this->view->warehouseid = $wh_id;
    }

    /**
     * Batch Summary 2
     */
    public function batchSummary2Action() {
        $stock_batch = new Model_StockBatch();
        $summary = $stock_batch->batchSummary2();
        $summarybefore = $stock_batch->batchSummaryBefore();
        $stock_batch->form_values = array(
            'from_date' => '2015-03-11',
            'to_date' => date("Y-m-d")
        );
        $transactions = $stock_batch->getIssueReceiveByDate();

        $this->view->summary = $summary;
        $this->view->summarybefore = $summarybefore;
        $this->view->transactions = $transactions;

        $this->view->headTitle("Batch Summary");
    }

    /**
     * Batch Summary 2 Print
     */
    public function batchSummary2PrintAction() {
        $this->_helper->layout->setLayout('print');

        $stock_batch = new Model_StockBatch();
        $summary = $stock_batch->batchSummary2();
        $summarybefore = $stock_batch->batchSummaryBefore();
        $stock_batch->form_values = array(
            'from_date' => '2015-03-11',
            'to_date' => date("Y-m-d")
        );
        $transactions = $stock_batch->getIssueReceiveByDate();

        $this->view->summary = $summary;
        $this->view->summarybefore = $summarybefore;
        $this->view->transactions = $transactions;
        $this->view->headTitle("Batch Summary");
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    /**
     * Stakeholder Summary
     */
    public function stakeholderSummaryAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Manufacturer wise stock summary");
        $stock_batch = new Model_StockBatch();

        $stock_batch->form_values['adjustment_no'] = $this->_request->adjustment_no;
        $stock_batch->form_values['adjustment_type'] = $this->_request->adjustment_type;
        $stock_batch->form_values['product'] = $this->_request->product;
        $stock_batch->form_values['date_from'] = $this->_request->date_from;
        $stock_batch->form_values['date_to'] = $this->_request->date_to;
        $summary = $stock_batch->stakeholderProductSummary();
        $expired_summary = $stock_batch->stakeholderExpiredProductSummary();
        $this->view->summary = $summary;
        $this->view->expired_summary = $expired_summary;
        $this->view->print_title = "Batch Management Summary";

        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
    }

    /**
     * ajaxRunningBatches
     */
    public function ajaxRunningBatchesAction() {
        $this->_helper->layout->disableLayout();
        $stock_batch = new Model_StockBatch();
        $page = $this->_request->getParam("page", '');
        $adjustment_type = $this->_request->getParam("adjustment_type", '');

        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            if ($page == 'adjustment') {
                $stock_batch->form_values['item_id'] = $this->_request->item_id;
                $stock_batch->form_values['adj_type'] = $adjustment_type;
                $itm = $this->_em->getRepository("ItemPackSizes")->find($this->_request->item_id);
                $this->view->all_running_batches = $stock_batch->getAllBatchesByItemId();
            } else if ($page == 'vvm-management') {
                $stock_batch->form_values['item_pack_size_id'] = $this->_request->item_id;
                $stock_batch->form_values['transaction_date'] = $this->_request->transaction_date;
                $this->view->all_running_batches = $stock_batch->getAllRunningBatches();
            } else {
                $stock_batch->form_values['item_pack_size_id'] = $this->_request->item_id;
                $stock_batch->form_values['transaction_date'] = $this->_request->transaction_date;

                $itm = $this->_em->getRepository("ItemPackSizes")->find($this->_request->item_id);

                if ($itm->getItemCategory()->getPkId() == 1 || $itm->getItemCategory()->getPkId() == 4) {
                    $this->view->all_running_batches = $stock_batch->getAllPriorityBatches();
                } else {
                    $this->view->all_running_batches = $stock_batch->getDryStoreAllPriorityBatches();
                }
            }
        }

        if (isset($this->_request->batch) && !empty($this->_request->batch)) {
            $stock_batch->form_values['pk_id'] = $this->_request->batch;
            $this->view->batch_expiry = $stock_batch->getBatchExpiry();
        }
        if (isset($this->_request->number) && !empty($this->_request->number)) {
            $stock_batch->form_values['pk_id'] = $this->_request->number;
            $this->view->available_quantity = $stock_batch->getBatchExpiry();
        }
        $this->view->page = $page;
        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            $itm = $this->_em->getRepository("ItemPackSizes")->find($this->_request->item_id);
            $this->view->item_category = $itm->getItemCategory()->getPkId();
        }
    }

    /**
     * ajaxAvailableBatchQuantity
     */
    public function ajaxAvailableBatchQuantityAction() {
        $this->_helper->layout->disableLayout();

        $page = $this->_request->page;
        $batch_id = $this->_request->batch;
        if (!empty($page) && $page == 'issue') {
            list($batch_id) = explode("|", $this->_request->batch);
        }

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['pk_id'] = $batch_id;
        $stock_batch->form_values['trans_date'] = App_Controller_Functions::dateToDbFormat($this->_request->tr_date);
        $batch_expiry = $stock_batch->getBatchAvailableBalanceExpiry();

        $type = $this->_request->getParam('type', 0);

        if ($type === 'json') {
            $this->_helper->viewRenderer->setNoRender(TRUE);
            echo json_encode($batch_expiry);
        } else {
            $this->view->batch_expiry = $batch_expiry;
        }
    }

    /**
     * ajaxCheckAdjustmentType
     */
    public function ajaxCheckAdjustmentTypeAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $type = $this->_request->type;
        $adj_type = $this->_em->getRepository("TransactionTypes")->find($type);
        $type_sign = $adj_type->getNature();

        if ($type_sign == "+") {
            echo 'positive';
        } else {
            echo 'negative';
        }
    }

    /**
     * Detail Batch
     */
    public function detailBatchAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->batch_detail_id) && !empty($this->_request->batch_detail_id)) {
            $placement = new Model_Placements();
            $stock_batch = $this->_request->batch_detail_id;
            $result = $placement->getPlacementByBatch($stock_batch);
            $this->view->result = $result;
        }
    }

    /**
     * ajaxExpiryEditAction
     */
    public function ajaxExpiryEditAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $param['id'] = $this->_request->id;
        $param['date'] = $this->_request->data;
        $batch = new Model_StockBatch();
        $batch->form_values = $param;
        $batch->editBatchExpiry();
        echo "done";
    }

    /**
     * ajaxGetPlacementHistory
     */
    public function ajaxGetPlacementHistoryAction() {
        $this->_helper->layout->disableLayout();
        $stock_batch = new Model_StockBatch();
        $arr = explode('|', $this->_request->id);
        $this->view->array_data = $arr;
        $stock_batch->form_values['batch_id'] = $arr[0];
        $this->view->data = $stock_batch->getPlacementHistory();
        $this->view->data1 = $stock_batch->getPlacementVvmStage();
    }

    /**
     * ajaxGetBatchLocations
     */
    public function ajaxGetBatchLocationsAction() {
        $this->_helper->layout->disableLayout();

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['batch_id'] = $this->_request->getParam('batch_id');
        $stock_batch->form_values['item_id'] = $this->_request->getParam('item_id');
        $stock_batch->form_values['type'] = $this->_request->getParam('type');
        $item_pack_size = $this->_em->getRepository('ItemPackSizes')->find($this->_request->getParam('item_id'));
        if ($item_pack_size) {
            //if vaccine
            if ($item_pack_size->getItemCategory() != null && ($item_pack_size->getItemCategory()->getPkId() == 1 || $item_pack_size->getItemCategory()->getPkId() == 4)) {
                $this->view->locations = $stock_batch->getBatchLocations();
            } else {
                $this->view->locations = $stock_batch->getBactchLocationDryStore();
            }
        }
    }

    /**
     * ajaxAvailableVvmStages
     */
    public function ajaxAvailableVvmStagesAction() {
        $this->_helper->layout->disableLayout();
        $placements = new Model_Placements();
        $batch_id = $this->_request->getParam('batch');
        $page = $this->_request->getParam('page');
        if (!empty($page) && $page == 'issue') {
            list($batch_id, $priority) = explode("|", $batch_id);
        }

        $batch = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
        $item_cat = $batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId();

        $placements->form_values['priority'] = $priority;
        $this->view->vvmstages = $placements->getAvailableVvmStages($batch_id, $item_cat);
        $this->view->role = $this->_identity->getRoleId();
        $this->view->item_cat = $item_cat;
        $this->view->is_placement_enable = 1;
    }

    /**
     * ajaxGetExistingBatches
     */
    public function ajaxGetExistingBatchesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $product = $this->_request->getParam('product');
        $batch = new Model_StockBatch();
        $batches = $batch->getExistingBatches($product);

        echo $batches;
    }

    /**
     * ajaxProductVvmStages
     */
    public function ajaxProductVvmStagesAction() {
        $this->_helper->layout->disableLayout();
        $product = $this->_request->getParam('product');
        $item = $this->_em->getRepository("ItemPackSizes")->find($product);
        $group = $item->getVvmGroup()->getPkId();
        $this->view->group = $group;
        $vvm_stages = $this->_em->getRepository("VvmGroups")->findBy(array("vvmGroupId" => $group));
        $this->view->item_vvm = $vvm_stages;
    }

    /**
     * ajaxPlacementLocations
     */
    public function ajaxPlacementLocationsAction() {
        $this->_helper->layout->disableLayout();
        $product = $this->_request->getParam('product');
        $item = $this->_em->getRepository("ItemPackSizes")->find($product);

        $result = '';
        if ($item->getItemCategory()->getPkId() == 1) {
            //Generate Asset Sub Type Combo
            $cold_chain = new Model_ColdChain();
            $cold_chain->form_values = array('type_id' => '1,3');
            $result = $cold_chain->getColdchainByAssetType();
            $this->view->placement_locations = $result;
        } else {
            $non_ccm_loc = new Model_NonCcmLocations();
            $result = $non_ccm_loc->getDryStoreLocationsName();
        }

        $this->view->placement_locations = $result;
    }

    /**
     * ajaxPlacementLocations
     */
    public function ajaxShipmentsPlacementLocationsAction() {
        $this->_helper->layout->disableLayout();
        //Generate Asset Sub Type Combo
        $cold_chain = new Model_ColdChain();
        $cold_chain->form_values = array('type_id' => '1,3');
        $result = $cold_chain->getColdchainByAssetType();
        $this->view->placement_locations = $result;
    }

    /**
     * ajaxAvailableBatchQuantityExpiry
     */
    public function ajaxAvailableBatchQuantityExpiryAction() {
        $this->_helper->layout->disableLayout();

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['pk_id'] = $this->_request->batch;
        $stock_batch->form_values['trans_date'] = App_Controller_Functions::dateToDbFormat($this->_request->tr_date);
        $batch_expiry = $stock_batch->getBatchAvailableBalanceExpiry();

        $type = $this->_request->getParam('type', 0);

        if ($type === 'json') {
            $this->_helper->viewRenderer->setNoRender(TRUE);
            echo json_encode($batch_expiry);
        } else {
            $this->view->batch_expiry = $batch_expiry;
        }
    }

    /**
     * ajaxAvailableIssueBatchQuantity
     */
    public function ajaxAvailableIssueBatchQuantityAction() {
        $this->_helper->layout->disableLayout();

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['pk_id'] = $this->_request->batch;
        $stock_batch->form_values['trans_date'] = App_Controller_Functions::dateToDbFormat($this->_request->tr_date);
        $batch_expiry = $stock_batch->getBatchAvailableBalanceExpiry();

        $type = $this->_request->getParam('type', 0);

        if ($type === 'json') {
            $this->_helper->viewRenderer->setNoRender(TRUE);
            echo json_encode($batch_expiry);
        } else {
            $this->view->batch_expiry = $batch_expiry;
        }
    }

    /**
     * ajaxIssueRunningBatches
     */
    public function ajaxIssueRunningBatchesAction() {
        $this->_helper->layout->disableLayout();
        $stock_batch = new Model_StockBatch();
        $page = $this->_request->getParam("page", '');
        $adjustment_type = $this->_request->getParam("adjustment_type", '');

        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            if ($page == 'adjustment') {
                $stock_batch->form_values['item_id'] = $this->_request->item_id;
                $stock_batch->form_values['adj_type'] = $adjustment_type;
                $this->view->all_running_batches = $stock_batch->getAllBatchesByItemId();
            } else {
                $stock_batch->form_values['item_pack_size_id'] = $this->_request->item_id;
                $stock_batch->form_values['transaction_date'] = $this->_request->transaction_date;
                $stock_batch->form_values['batch_no'] = $this->_request->batch_no;

                $itm = $this->_em->getRepository("ItemPackSizes")->find($this->_request->item_id);

                if ($itm->getItemCategory()->getPkId() == 1 || $itm->getItemCategory()->getPkId() == 4) {
                    $this->view->all_running_batches = $stock_batch->getAllIssuePriorityBatches();
                } else {
                    $this->view->all_running_batches = $stock_batch->getAllIssueRunningBatches();
                }
            }
        }

        if (isset($this->_request->batch) && !empty($this->_request->batch)) {
            $stock_batch->form_values['pk_id'] = $this->_request->batch;
            $this->view->batch_expiry = $stock_batch->getBatchExpiry();
        }
        if (isset($this->_request->number) && !empty($this->_request->number)) {
            $stock_batch->form_values['pk_id'] = $this->_request->number;
            $this->view->available_quantity = $stock_batch->getBatchExpiry();
        }
    }

    /**
     * ajaxIssueAvailableVvmStages
     */
    public function ajaxIssueAvailableVvmStagesAction() {
        $this->_helper->layout->disableLayout();

        $placements = new Model_Placements();
        $batch_id = $this->_request->getParam('batch');
        $priority = $this->_request->getParam('priority');

        $batch = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
        $item_cat = $batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId();

        $placements->form_values['priority'] = $priority;
        $this->view->vvmstages = $placements->getAvailableVvmStages($batch_id, $item_cat);
        $this->view->role = $this->_identity->getRoleId();
        $this->view->item_cat = $item_cat;
        $this->view->is_placement_enable = 1;
    }

    /**
     * Batch Summary
     */
    public function batchSummaryAlertsAction() {
        $this->_helper->layout->setLayout('print');
        $stock_batch = new Model_StockBatch();

        if (!empty($this->_request->type)) {
            $stock_batch->form_values = $this->_request->getParams();
            $data = $stock_batch->searchStockBatch();
            $this->view->data = $data;
            $this->view->print_title = "Batch Management";
            $this->view->status = $this->_request->getParam('status');
        } else {
            $stock_batch->form_values['adjustment_no'] = $this->_request->adjustment_no;
            $stock_batch->form_values['adjustment_type'] = $this->_request->adjustment_type;
            $stock_batch->form_values['product'] = $this->_request->product;
            $stock_batch->form_values['date_from'] = $this->_request->date_from;
            $stock_batch->form_values['date_to'] = $this->_request->date_to;
            $summary = $stock_batch->batchSummaryAlert();
            $expired_summary = $stock_batch->expiredBatchSummary();
            $this->view->summary = $summary;
            $this->view->expired_summary = $expired_summary;
            $this->view->print_title = "Batch Management Summary";
        }
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
    }

    /**
     * ajaxRunningBatches
     */
    public function ajaxRunningBatchesAefiAction() {
        $this->_helper->layout->disableLayout();
        $stock_batch = new Model_StockBatch();


        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {

            $stock_batch->form_values['item_id'] = $this->_request->item_id;


            $this->view->all_running_batches = $stock_batch->getAllBatchesByItemId();
        }
    }

}
