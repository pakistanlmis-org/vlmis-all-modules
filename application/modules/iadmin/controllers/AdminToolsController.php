<?php

/**
 * Iadmin_AdminToolsController
 *
 * 
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller provides Admin Tools
 */
class Iadmin_AdminToolsController extends App_Controller_Base {

    /**
     * This method fixes the Placement Difference
     */
    public function fixPlacementDifferenceAction() {
        $model = new Model_AdminTools();
        $data = $model->getDifferentStockPlacementByProduct();

        $this->view->result = $data;
    }

    /**
     * This method fixes Stock Placement Quntity
     */
    public function fixStockPlacementQtyAction() {
        $this->_helper->layout->disableLayout();
        $wh_id = $this->_request->getParam('wh_id');
        $item_id = $this->_request->getParam('item_id');

        $items = $this->_em->getRepository("ItemPackSizes")->find($item_id);
        $item_cat = $items->getItemCategory()->getPkId();

        $transactions = array();
        $batch = array();
        $data = array();

        $model = new Model_AdminTools();
        $data = $model->getDifferentStockPlacement($wh_id, $item_id);

        if (count($data) > 0) {
            $batch = array();
            foreach ($data as $row) {
                $batch[$row['BatchId']] = $row['BatchNumber'];
            }

            if ($this->_request->isPost()) {
                $batch_id = $this->_request->getPost('batch_id');
                $transactions = $model->getBatchTransactions(array($batch_id), $item_cat);
            } else {
                $transactions = $model->getBatchTransactions(array_keys($batch), $item_cat);
            }
        }

        if ($item_cat == 1) {
            //Generate Asset Sub Type Combo
            $cold_chain = new Model_ColdChain();
            $cold_chain->form_values = array('type_id' => '1,3', 'wh_id' => $wh_id);
            $locations = $cold_chain->getColdchainByAssetType();
        } else {
            $non_ccm_loc = new Model_NonCcmLocations();
            $non_ccm_loc->form_values = array('wh_id' => $wh_id);
            $locations = $non_ccm_loc->getDryStoreLocationsName();
        }

        $this->view->placement_locations = $locations;

        $this->view->result = $data;
        $this->view->transactions = $transactions;
        $this->view->batches = $batch;
        $this->view->wh_id = $wh_id;
        $this->view->item_id = $item_id;
        $this->view->item_cat = $item_cat;
        $this->view->batch_id = $batch_id;
    }

    /**
     * This method adds Pick Placement
     */
    public function addPickPlaceAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $wh_id = $this->_request->getPost('wh_id');
        $item_id = $this->_request->getPost('item_id');

        $trans_type = $this->_request->getPost('trans_type');

        if ($trans_type == 114) {
            $is_placed = 1;
        } else {
            $is_placed = 0;
        }

        $updateplc = $this->_request->getPost('updateplc');

        if (isset($updateplc)) {
            $plc_id = $this->_request->getPost('plc_id');
            $loc_id = $this->_request->getPost('location_id');
            $placements = $this->_em->getRepository("Placements")->find($plc_id);
            $placement_loc = $this->_em->getRepository("PlacementLocations")->find($loc_id);
            $placements->setPlacementLocation($placement_loc);
            $placements->setQuantity($this->_request->getPost('trans_qty'));
            $this->_em->persist($placements);
            $this->_em->flush();
        } else {
            $placements = new Model_Placements();
            $placements->form_values = array(
                'quantity' => $this->_request->getPost('trans_qty'),
                'placement_loc_id' => $this->_request->getPost('location_id'),
                'batch_id' => $this->_request->getPost('batch_id'),
                'detail_id' => $this->_request->getPost('detail_id'),
                'placement_loc_type_id' => $this->_request->getPost('trans_type'),
                'user_id' => $this->_userid,
                'created_date' => date("Y-m-d"),
                'vvmstage' => $this->_request->getPost('vvm_stage'),
                'is_placed' => $is_placed
            );
            $placements->add();
        }

        $this->_redirect("iadmin/admin-tools/fix-stock-placement-qty?action=$is_placed&wh_id=$wh_id&item_id=$item_id");
    }

    /**
     * This method merges Stock Batch
     */
    public function mergeStockBatchAction() {
        $model_stock_batch = new Model_StockBatch();

        if ($this->_request->isPost()) {
            $batches = $this->_request->getPost('checkbatches');
            $merge_b_id = $this->_request->getPost('mergeinto');

            foreach ($batches as $update_id) {
                if ($update_id != $merge_b_id) {
                    $stock_batch_warehouse = $this->_em->getRepository("StockBatchWarehouses")->findBy(array("stockBatch" => $update_id));
                    if (count($stock_batch_warehouse) > 0) {
                        foreach ($stock_batch_warehouse as $stock_batch_wh) {
                            $stock_batch = $this->_em->getRepository("StockBatch")->find($merge_b_id);
                            $stock_batch_wh->setStockBatch($stock_batch);
                            $this->_em->persist($stock_batch_wh);
                        }
                        $this->_em->flush();
                    }

                    $del_stock_batch = $this->_em->getRepository("StockBatch")->find($update_id);
                    $this->_em->remove($del_stock_batch);
                    $this->_em->flush();
                }
            }
        }

        $result = $model_stock_batch->getDuplicateBatches();
        $this->view->result = $result;
    }

    /**
     * This method fixes Stock Batches with negative quantity
     */
    public function stockBatchWithNegativeQtyAction() {
        $model_stock_batch = new Model_StockBatch();

        $batch_id = $this->_request->getParam('batch');
        $trans_date = $this->_request->getParam('date');
        list($dd, $mm, $yy) = explode("-", $trans_date);

        $updatedqty = $this->_request->getParam('qty');

        if (!empty($batch_id)) {
            $batch_detail = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
            $activity_id = $this->_em->getRepository("ItemActivities")->findOneBy(array("itemPackSize" => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId()));
            $item_category = $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId();

            $model_stock_batch->form_values['batch_id'] = $batch_id;
            $model_stock_batch->form_values['type'] = 12;
            $model_stock_batch->form_values['wh_id'] = $batch_detail->getWarehouse()->getPkId();

            if ($item_category == 1 || $item_category == 4) {
                $locations = $model_stock_batch->getBatchLocations();
            } else {
                $locations = $model_stock_batch->getBactchLocationDryStore();
            }

            /* $placements = $this->_em->getRepository("Placements")->findBy(array("stockBatchWarehouse" => $batch_id));
              if (count($placements) > 0) {
              foreach ($placements as $plc) {
              $this->_em->remove($plc);
              }
              $this->_em->flush();
              } */

            $stock_master = new Model_StockMaster();
            $data = array(
                'adjustment_date' => $dd . "/" . $mm . "/" . $yy,
                'ref_no' => '',
                'product' => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId(),
                'batch_no' => $batch_id,
                'adjustment_type' => 12,
                'quantity' => $updatedqty,
                'comments' => 'Negative Balance Adjustments',
                'item_unit_id' => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemUnit()->getPkId(),
                'vvm_stage' => 1,
                'location_id' => $locations[0]['placement_location_id'],
                'purpose' => $activity_id->getStakeholderActivity()->getPkId(),
                'is_received' => 1,
                'wh_id' => $batch_detail->getWarehouse()->getPkId()
            );
            $stock_master->form_values = $data;
            $stock_master->addAdjustment();

            $plc_qty = $model_stock_batch->getPlacementsByBatch($batch_id);
            $stock_plc = new Model_Placements();
            if ($plc_qty > 0) {
                $data = array(
                    'quantity' => "-" . $plc_qty,
                    'placement_loc_id' => $locations[0]['placement_location_id'],
                    'batch_id' => $batch_id,
                    'detail_id' => '',
                    'placement_loc_type_id' => 115,
                    'user_id' => $this->_userid,
                    'vvmstage' => 1,
                    'is_placed' => 0
                );
            } else {
                $data = array(
                    'quantity' => ABS($plc_qty),
                    'placement_loc_id' => $locations[0]['placement_location_id'],
                    'batch_id' => $batch_id,
                    'detail_id' => '',
                    'placement_loc_type_id' => 114,
                    'user_id' => $this->_userid,
                    'vvmstage' => 1,
                    'is_placed' => 1
                );
            }

            $stock_plc->form_values = $data;
            $stock_plc->add();
        }

        $this->view->result = $model_stock_batch->getAllNegativeBatches();
    }

    /**
     * This method updates Yearly AMC  
     */
    public function epiAmcAction() {

        if ($this->_request->getPost('go')) {
            $year = $this->_request->getPost("year");
            $wh_id = $this->_request->getPost("store");
        } else {
            $year = date("Y");
            $wh_id = 159;
        }

        if ($this->_request->getPost('submit')) {
            $this->view->msg = 'Added successfully!';
            $amc_data = $this->_request->getPost("amc");
            foreach ($amc_data as $data => $amc) {

                list($type, $pk_id, $wh_id, $year, $item_id) = explode("|", $data);
                $epi = new Model_EpiAmc();
                $epi->form_values = array(
                    'amc' => $amc,
                    'amc_year' => $year,
                    'item_id' => $item_id,
                    'wh_id' => $wh_id,
                    'pk_id' => $pk_id,
                    'type' => $type
                );
                $epi->addRecord();
            }

            if ($type == 'Update') {
                $this->view->msg = 'Updated successfully!';
            }
        }

        $epi = new Model_EpiAmc();
        $this->view->addcheck = 'No';
        $result = $epi->getByYear($year, $wh_id);
        if (count($result) == 0) {
            $result = $epi->getByYear($year - 1, $wh_id);
            $this->view->addcheck = 'Yes';
        }
        $stores = $epi->getStores();

        $this->view->result = $result;
        $this->view->year = $year;
        $this->view->store = $wh_id;
        $this->view->stores = $stores;
    }

}
