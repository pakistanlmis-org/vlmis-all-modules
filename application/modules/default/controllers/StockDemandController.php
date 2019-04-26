<?php

/**
 * StockDemandController
 *
 *
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Click Paths
 */
class StockDemandController extends App_Controller_Base {

    /**
     *
     */
    public function requisitionAction() {
        $demand_master = new Model_DemandMaster();
        $demand_detail = new Model_DemandDetail();
        $form = new Form_StockRequisition();
        $form_values = array();
        $temp = array();
        $form_values['transaction_type_id'] = 2;
        $form_values['adjustment_type'] = 2;
        $stock_id = "";
        $master_id = "";

        $created_by = $this->_em->find('Users', $this->_userid);
        if (!empty($this->_request->hdn_stock_id)) {
            $stock_id = $this->_request->hdn_stock_id;
        }
        if (!empty($this->_request->hdn_master_id)) {
            $master_id = $this->_request->hdn_master_id;
        }

        $warehouse_id = '';
        if (!empty($this->_request->warehouse)) {
            $warehouse_id = $this->_request->warehouse;
        }

        $warehouse_id = $this->_request->warehouse_name;
        $form->remaining_balance->setAttrib("disabled", "true");

        if ($this->_request->isPost()) {

            if (!empty($master_id)) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    //Start update issue period
                    $array = $this->_request->getParams();
                    //End update issue period
                    $requisition_number = $demand_master->updateDemandMasterTemp($master_id, $this->_request->comments);
                    $demand_detail->updateDemandDetailTemp($master_id);

                    if (isset($_FILES['attachment'])) {
                        $errors = array();
                        $file_name = $_FILES['attachment']['name'];
                        $file_size = $_FILES['attachment']['size'];
                        $file_tmp = $_FILES['attachment']['tmp_name'];
                        $file_type = $_FILES['attachment']['type'];
                        $file_ext = strtolower(end(explode('.', $_FILES['attachment']['name'])));

                        if ($file_size > 2097152) {
                            $errors[] = 'File size must be excately 2 MB';
                        }

                        $base_url = Zend_Registry::get('baseurl');

                        // create directory as req_masterid e.g req_12.
                        if (!empty($file_name)) {
                            mkdir("tmp/requisition_attachments/req-" . $master_id);
                            $file_path = "tmp/requisition_attachments/req-" . $master_id . '/' . $file_name;
                            if (empty($errors) == true) {
                                move_uploaded_file($file_tmp, $file_path);
                                $demand_master->saveRequisitionAttachments($master_id, $file_path);
                            }
                        }
                    }

                    $this->view->msg = 'Requisition saved and submitted successfully. Your requisition number is ';
                    $this->view->voucher = $requisition_number;
                    $this->view->master_id = $master_id;
                    $em->getConnection()->commit();
                    $this->redirect("/stock-demand/requisition");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            } elseif ($form->isValid($this->_request->getPost())) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    $editissue = $this->_request->getPost('issueedit');
                    if ($editissue == "Yes") {
                        $d_id = $this->_request->getPost('detailid');
                        $obj_stock_detail = $em->getRepository("DemandDetail")->find($d_id);
                        $old_batch_id = $obj_stock_detail->getStockBatch()->getPkId();

                        $data = $form->getValues();

                        $obj_stock_master = $obj_stock_detail->getStockMaster();
                        $master_update = false;

                        $arr_date = explode("/", $data['transaction_date']);
                        if (intval($arr_date[1]) . "-" . intval($arr_date[2]) != intval($obj_stock_detail->getStockMaster()->getTransactionDate()->format("m")) . "-" . intval($obj_stock_detail->getStockMaster()->getTransactionDate()->format("Y"))) {
                            $trans = $demand_master->getTransactionNumber(2, $data['transaction_date']);
                            $obj_stock_master->setTransactionNumber($trans['trans_no']);
                            $obj_stock_master->setTransactionCounter($trans['id']);
                            $master_update = true;
                        }

                        $obj_stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['transaction_date'])));

                        if (!empty($warehouse_id)) {
                            $to_wh = $em->getRepository("Warehouses")->find($warehouse_id);
                            $obj_stock_master->setToWarehouse($to_wh);
                            $master_update = true;
                        }

                        if ($master_update) {
                            $obj_stock_master->setModifiedBy($created_by);
                            $obj_stock_master->setModifiedDate(App_Tools_Time::now());
                            $em->persist($obj_stock_master);
                            $em->flush();
                        }

                        $qunty = str_replace(",", "", $data['quantity']);
                        $obj_stock_detail->setQuantity("-" . $qunty);
                        $vvms = $this->_em->getRepository("VvmStages")->find($data['vvm_stage']);
                        $obj_stock_detail->setVvmStage($vvms);
                        $stock_b = $em->getRepository("StockBatch")->find($data['number']);
                        $obj_stock_detail->setStockBatch($stock_b);
                        if (!empty($data['item_unit_id'])) {
                            $item_unit = $em->getRepository("ItemUnits")->find("2");
                            $obj_stock_detail->setItemUnit($item_unit);
                        }
                        $obj_stock_detail->setModifiedBy($created_by);
                        $obj_stock_detail->setModifiedDate(App_Tools_Time::now());
                        $em->persist($obj_stock_detail);
                        $em->flush();

                        $current_date = explode("/", $data['transaction_date']);
                    } else {
                        $temp = $form->getValues();
                        $data = array_merge($temp, $form_values);
                        $data['warehouse'] = $warehouse_id;
                        if (empty($this->_request->requisition_number)) {
                            $stock_id = $demand_master->addDemandMaster($data);
                        }
                        if (isset($data['quantity']) && !empty($data['quantity'])) {
                            $qty = str_replace(",", "", $data['quantity']);
                        }
                        if (isset($data['available_quantity']) && !empty($data['available_quantity'])) {
                            $ava_qty = str_replace(",", "", $data['available_quantity']);
                        }
                        if ((int) $qty > (int) $ava_qty || (int) $qty == (int) $ava_qty) {
                            $data['quantity'] = $ava_qty;
                            $autorun = true;
                        }

                        list($batch_id) = explode("|", $this->_request->number);
                        $form_values['item_unit_id'] = $this->_request->item_unit_id;
                        $form_values['stock_master_id'] = $stock_id;
                        $form_values['stock_batch_id'] = $batch_id;
                        $data = array_merge($temp, $form_values);
                        $detail_id = $demand_detail->addDemandDetail($data);
                    }
                    $em->getConnection()->commit();
                    $this->redirect("stock-demand/requisition?id=$stock_id");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                    if ($e->getMessage() == 'PLCD_QTY_GREATER_THAN_ISSUE_QTY') {
                        $this->view->status = false;
                        $this->view->msg = "Issue quantity should not greater than placed quantity!";
                        $this->redirect("stock-demand/requisition");
                    }
                }
            }

            if ($this->_request->type == 's') {
                $this->redirect("/stock-demand/");
            }
        }

        $temp_stock = "";
        // Edit Issue Start
        if (!empty($this->_request->id) && !$this->_request->isPost()) {
            $demand_master->form_values = $form_values;

            $varid = $this->_request->id;
            $form->requisition_number->setValue($varid);
            $temp_stock = $demand_master->getDemandRequisitionDetails($varid);
            $demand_master->form_values['stock_master_id'] = $this->_request->id;
            if (!empty($temp_stock['stock_id'])) {
                $form->hdn_stock_id->setValue($temp_stock['pk_id']);
                $form->hdn_master_id->setValue($temp_stock['pk_id']);
            } elseif (!empty($stockid)) {
                $form->hdn_stock_id->setValue($stock_id);
                $form->hdn_master_id->setValue($stock_id);
            }
            if ($temp_stock) {
                $form->requisition_number->setValue($temp_stock[0]['demand_master_id']);
                $form->warehouse_name->setValue($temp_stock[0]['to_warehouse_id']);
                $form->requisition_reference->setValue($temp_stock[0]['requisition_reference']);
                $form->suggested_date->setValue(date("d/m/Y", strtotime($temp_stock[0]['suggested_date'])));
                $form->item_id->setValue($temp_stock[0]['requisition_reference']);
                $form->activity_id->setValue($temp_stock[0]['stakeholder_activity_id']);
                $form->hdn_to_warehouse_id->setValue($temp_stock[0]['to_warehouse_id']);
                $form->hdn_stock_id->setValue($temp_stock[0]['demand_master_id']);
                $form->hdn_master_id->setValue($temp_stock[0]['demand_master_id']);

                $qtr = date("d/m/Y", strtotime($temp_stock[0]['from_date']));

                $d = new DateTime($qtr);
                $month = $d->format("d");
                $y = $d->format("Y");

                $to_qtr = date("d/m/Y", strtotime($temp_stock[0]['to_date']));

                $d_to = new DateTime($to_qtr);
                $month_to = $d_to->format("d");
                $y_to = $d_to->format("Y");
                //$month_to = 1;
                $period = '';
                // start months
                $month_end = date('m');
                $curr_year = date('Y');
                for ($i = 1; $i <= $month_end; $i++) {
                    $key_val = "01" . "/" . str_pad($i, 2, '0', STR_PAD_LEFT) . "/$curr_year-31" . "/" . str_pad($i, 2, '0', STR_PAD_LEFT) . "/$curr_year";
                    $period = $key_val;
                }
                // end months
                if ($month == 1 && $month_to == 1) {
                    $period = "01/01/$y-01/01/$y";
                } else if ($month == 2 && $month_to == 2) {
                    $period = "01/02/$y-01/02/$y";
                } else if ($month == 3 && $month_to == 3) {
                    $period = "01/03/$y-01/03/$y";
                } else if ($month == 4 && $month_to == 4) {
                    $period = "01/04/$y-01/04/$y";
                } else if ($month == 5 && $month_to == 5) {
                    $period = "01/05/$y-01/05/$y";
                } else if ($month == 6 && $month_to == 6) {
                    $period = "01/06/$y-01/06/$y";
                } else if ($month == 7 && $month_to == 7) {
                    $period = "01/07/$y-01/07/$y";
                } else if ($month == 8 && $month_to == 8) {
                    $period = "01/08/$y-01/08/$y";
                } else if ($month == 9 && $month_to == 9) {
                    $period = "01/09/$y-01/09/$y";
                } else if ($month == 10 && $month_to == 10) {
                    $period = "01/10/$y-01/10/$y";
                } else if ($month == 11 && $month_to == 11) {
                    $period = "01/11/$y-01/11/$y";
                } else if ($month == 12 && $month_to == 12) {
                    $period = "01/12/$y-01/12/$y";
                } else if ($month == 01 && $month_to == 03) {
                    $period = "01/01/$y-01/03/$y";
                } else if ($month == 04 && $month_to == 06) {
                    $period = "01/04/$y-01/06/$y";
                } else if ($month == 07 && $month_to == 09) {
                    $period = "01/07/$y-01/09/$y";
                } else if ($month == 10 && $month_to == 12) {
                    $period = "01/10/$y-01/12/$y";
                }

                $form->period->setValue($period);
                $form->period->setAttrib("disabled", "true");
                $form->activity_id->setAttrib("disabled", "true");
                $form->requisition_reference->setAttrib("disabled", "true");
                $form->warehouse_name->setAttrib("disabled", "true");
                $form->suggested_date->setAttrib("disabled", "true");
            }
        }
        // Edit Issue End
        $this->view->form = $form;

        $base_url = Zend_Registry::get('baseurl');
        switch ($this->_user_level) {
            case 1:
            case 2:
            case 3:
                $this->view->menu_type = 1;
                $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
                break;
            case 4:
            case 5:
            case 6:
                $this->view->menu_type = 2;
                $this->view->inlineScript()->appendFile($base_url . '/js/level_combos.js');
                break;
            default:
                break;
        }


        $this->view->arr_data = $temp_stock;
        $this->view->type = $this->_request->getParam("t", "issue");
        $this->view->wh_id = $this->_identity->getWarehouseId();

        $this->view->params = array("province" => $this->_identity->getProvinceId(), "district" => $this->_identity->getDistrictId());
        $this->view->role = $this->_identity->getRoleId();
    }

    /**
     *
     */
    public function requisitionActionTemp() {
        $form = new Form_StockRequisition();
        $this->view->form = $form;

        $demand_master = new Model_DemandMaster();
        $demand_detail = new Model_DemandDetail();

        $temp_req_list = null;
        $form_values = array();

        if (!empty($this->_request->hdn_stock_id)) {
            $stock_id = $this->_request->hdn_stock_id;
        }
        if (!empty($this->_request->hdn_master_id)) {
            $master_id = $this->_request->hdn_master_id;
        }

        if (!empty($this->_request->period)) {
            $requisition_period = $this->_request->period;
        }
        $created_by = $this->_em->find('Users', $this->_userid);

        if ($this->_request->isPost()) {

            $form_values = $form->getValues();

            if (!empty($master_id)) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    $transaction_number = $demand_master->updateDemandMasterTemp($master_id, $this->_request->comments);
                    $demand_detail->updateDemandDetailTemp($master_id);
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            } elseif ($form->isValid($this->_request->getPost())) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    //Add DemandMaster
                    if (empty($this->_request->transaction_number)) {
                        //$stock_id = $demand_master->addDemandMaster($data);
                    }
                    $detail_id = $demand_detail->addDemandDetail($data);
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            }

            $demand_master->form_values = $form_values;
            $temp_req = $demand_master->getTempRequisition();
            if ($temp_req) {
                $arr_data = array_merge($arr_data, $temp_req);
            }
            $temp_req_list = $demand_master->getTempRequisitionList();
            if ($temp_req_list) {
                $form->transaction_number->setValue($temp_req_list[0]['transaction_number']);
                $form->transaction_date->setValue(date("d/m/Y h:i A", strtotime($temp_req_list[0]['transaction_date'])));
                $form->warehouse_name->setValue($temp_req_list[0]['to_warehouse']);
                $form->transaction_reference->setValue($temp_req_list[0]['transaction_reference']);
                $form->hdn_to_warehouse_id->setValue($temp_req_list[0]['to_warehouse_id']);
                $arr_data['warehouse_name'] = $temp_req_list[0]['to_warehouse'];
                $form->activity_id->setValue($temp_req_list[0]['activity_id']);

                $arr_data['tempstocks'] = $temp_req_list;
                $form->makeFieldReadonly();
            } else {
                $form->transaction_date->setValue(date("d/m/Y h:i A"));
            }

            if (!empty($temp_req['stock_id'])) {
                $form->hdn_stock_id->setValue($temp_req['stock_id']);
                $form->hdn_master_id->setValue($temp_req['stock_id']);
            } elseif (!empty($stockid)) {
                $form->hdn_stock_id->setValue($stock_id);
                $form->hdn_master_id->setValue($stock_id);
            }

            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $period_arr = explode('-', $requisition_period);
                $from_date = $period_arr['0'];
                $to_date = $period_arr['1'];
                $form_data = $this->_request->getParams();
                $demand_master->form_values = $form_data;

                $requisition_number = $demand_master->getRequisitionNumber($from_date, $to_date);

                // Add Demand Master
                $obj_demand_master = new DemandMaster();

                $obj_demand_master->setFromDate(new \DateTime(App_Controller_Functions::dateToDbFormat($from_date)));
                $obj_demand_master->setToDate(new \DateTime(App_Controller_Functions::dateToDbFormat($to_date)));
                $obj_demand_master->setSuggestedDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_data['suggested_date'])));

                $obj_demand_master->setRequisitionNumber($requisition_number['req_no']);
                $obj_demand_master->setRequisitionCounter($requisition_number['id']);
                $obj_demand_master->setRequisitionReference($form_data['requisition_reference']);
                $trans_type = $this->_em->getRepository("TransactionTypes")->find(18);
                $obj_demand_master->setTransactionType($trans_type);
                $obj_demand_master->setDraft(1);
                $obj_demand_master->setStatus(0);
                $from_wh = $this->_em->getRepository("Warehouses")->find($this->_identity->getWarehouseId());
                $obj_demand_master->setFromWarehouse($from_wh);
                $to_wh = $this->_em->getRepository("Warehouses")->find(159);
                $obj_demand_master->setToWarehouse($to_wh);

                $obj_demand_master->setCreatedBy($created_by);
                $obj_demand_master->setCreatedDate(App_Tools_Time::now());
                $obj_demand_master->setModifiedBy($created_by);
                $obj_demand_master->setModifiedDate(App_Tools_Time::now());
                $em->persist($obj_demand_master);
                $em->flush();

                // Add Demand Detail
                $obj_demand_detail = new DemandDetail();

                $obj_demand_detail->setDemandQuantity($form_data['quantity']);
                $obj_demand_detail->setDemandMaster($obj_demand_master);
                $product = $this->_em->getRepository("ItemPackSizes")->find($form_data['item_id']);
                $obj_demand_detail->setProduct($product);
                $obj_demand_detail->setPairProductId($form_data['usage']);
                $obj_demand_detail->setDraft(1);
                $obj_demand_detail->setCreatedBy($created_by);
                $obj_demand_detail->setCreatedDate(App_Tools_Time::now());
                $obj_demand_detail->setModifiedBy($created_by);
                $obj_demand_detail->setModifiedDate(App_Tools_Time::now());
                $em->persist($obj_demand_detail);
                $em->flush();


                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }
        }

        $this->view->temp_list = $temp_req_list;
        $form->warehouse_name->setValue("Federal Vaccine Store");
    }

    /**
     *
     */
    public function ajaxGetProductPairByProductAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {

            $item_pairs_logic = new Model_ItemPairsLogic();
            $item_pairs_logic->form_values['item_id'] = $this->_request->item_id;

            $item_pairs = $item_pairs_logic->getProductPairByProduct();
            $this->view->result = $item_pairs;
        }
    }

    /**
     *
     */
    public function ajaxGetProductAllocatedQtyAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            $demand_master = new Model_DemandMaster();
            $demand_master->form_values['item_id'] = $this->_request->item_id;
            $demand_master->form_values['period'] = $this->_request->period;
            $allocated_qty = $demand_master->getProductAllocatedQty();
            $remaining_qty = $demand_master->getProductRemainingBalance();
            $data = array();
            if ($allocated_qty != false) {
                $data['allocated'] = number_format($allocated_qty[0]['allocation']);
            }
            if ($allocated_qty != false) {
                $data['remaining'] = number_format($allocated_qty[0]['allocation'] - $remaining_qty[0]['demanded_qty']);
            }

            echo Zend_Json::encode($data);
        }
    }

    /**
     *
     */
    public function approvalAction() {
        $form = new Form_Approval();
        $demand_master = new Model_DemandMaster();
        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $demand_master->form_values = $res;
            $form->from_warehouse_id->setValue($res['from_warehouse_id']);
            $form->from_date->setValue($res['from_date']);
            $form->to_date->setValue($res['to_date']);
            $form->status->setValue($res['status']);
        }

        $arr_data = $demand_master->getAllRequisitions();
        $this->view->arr_data = $arr_data;
        $this->view->form = $form;
        $this->view->voucher = $this->_request->getParam("voucher", "");
    }

    /**
     * Issue
     */
    public function issueAction() {
        $stock_master = new Model_DemandMaster();
        $stock_detail = new Model_DemandDetail();
        $form = new Form_StockRequisition();
        $form_values = array();
        $temp = array();
        $arr_data = array('transaction_number' => "",
            'stock_id' => "",
            'transaction_date' => date("d/m/Y"),
            'warehouse_name' => "",
            'success' => $this->_request->success
        );
        $form_values['transaction_type_id'] = 2;
        $form_values['adjustment_type'] = 2;
        $stock_id = "";
        $master_id = "";

        $created_by = $this->_em->find('Users', $this->_userid);
        if (!empty($this->_request->hdn_stock_id)) {
            $stock_id = $this->_request->hdn_stock_id;
        }
        if (!empty($this->_request->hdn_master_id)) {
            $master_id = $this->_request->hdn_master_id;
        }

        if (!empty($this->_request->warehouse)) {
            $warehouse_id = $this->_request->warehouse;
        }

        if ($this->_request->isPost()) {

            if (!empty($master_id)) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {

                    // Start update issue period
                    $array = $this->_request->getParams();
                    $stock_master->updateStockPeriod($master_id, $array);
                    //End update issue period

                    $transaction_number = $stock_master->updateStockMasterTemp($master_id, $this->_request->comments);
                    $stock_detail->updateStockDetailTemp($master_id);
                    // Save Data in warehouse_data table

                    $this->view->msg = 'Stock has been issued successfully. Your voucher number is ';
                    $this->view->voucher = $transaction_number;
                    $this->view->master_id = $master_id;
                    $em->getConnection()->commit();
                    $this->redirect("/stock/issue");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            } elseif ($form->isValid($this->_request->getPost())) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    $editissue = $this->_request->getPost('issueedit');
                    if ($editissue == "Yes") {
                        $d_id = $this->_request->getPost('detailid');
                        $obj_stock_detail = $em->getRepository("StockDetail")->find($d_id);
                        $old_batch_id = $obj_stock_detail->getStockBatch()->getPkId();

                        $data = $form->getValues();

                        $obj_stock_master = $obj_stock_detail->getStockMaster();
                        $master_update = false;

                        $arr_date = explode("/", $data['transaction_date']);
                        if (intval($arr_date[1]) . "-" . intval($arr_date[2]) != intval($obj_stock_detail->getStockMaster()->getTransactionDate()->format("m")) . "-" . intval($obj_stock_detail->getStockMaster()->getTransactionDate()->format("Y"))) {
                            $trans = $stock_master->getTransactionNumber(2, $data['transaction_date']);
                            $obj_stock_master->setTransactionNumber($trans['trans_no']);
                            $obj_stock_master->setTransactionCounter($trans['id']);
                            $master_update = true;
                        }

                        $obj_stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['transaction_date'])));

                        if (!empty($warehouse_id)) {
                            $to_wh = $em->getRepository("Warehouses")->find($warehouse_id);
                            $obj_stock_master->setToWarehouse($to_wh);
                            $master_update = true;
                        }

                        if ($master_update) {
                            $obj_stock_master->setModifiedBy($created_by);
                            $obj_stock_master->setModifiedDate(App_Tools_Time::now());
                            $em->persist($obj_stock_master);
                            $em->flush();
                        }

                        $qunty = str_replace(",", "", $data['quantity']);
                        $obj_stock_detail->setQuantity("-" . $qunty);
                        $vvms = $this->_em->getRepository("VvmStages")->find($data['vvm_stage']);
                        $obj_stock_detail->setVvmStage($vvms);
                        $stock_b = $em->getRepository("StockBatch")->find($data['number']);
                        $obj_stock_detail->setStockBatch($stock_b);
                        if (!empty($data['item_unit_id'])) {
                            $item_unit = $em->getRepository("ItemUnits")->find("2");
                            $obj_stock_detail->setItemUnit($item_unit);
                        }
                        $obj_stock_detail->setModifiedBy($created_by);
                        $obj_stock_detail->setModifiedDate(App_Tools_Time::now());
                        $em->persist($obj_stock_detail);
                        $em->flush();

                        $current_date = explode("/", $data['transaction_date']);

                        $mm = $current_date[1];
                        $yy = $current_date[2];

                        $item_id = $data['item_id'];

                        $wh_id = $this->_identity->getWarehouseId();
                        $user_id = $this->_userid;

                        $query = "SELECT AdjustQty(" . $data['number'] . "," . $wh_id . ") FROM DUAL";
                        $str_sql = $em->getConnection()->prepare($query);
                        $str_sql->execute();

                        $query = "SELECT AdjustQty(" . $old_batch_id . "," . $wh_id . ") FROM DUAL";
                        $str_sql = $em->getConnection()->prepare($query);
                        $str_sql->execute();

                        $query = "SELECT REPUpdateData($mm,$yy,$item_id,$wh_id,$user_id) FROM DUAL";
                        $str_sql = $em->getConnection()->prepare($query);
                        $str_sql->execute();
                    } else {
                        $temp = $form->getValues();
                        $data = array_merge($temp, $form_values);
                        $data['warehouse'] = $warehouse_id;
                        if (empty($this->_request->transaction_number)) {
                            $stock_id = $stock_master->addStockMaster($data);
                        }
                        if (isset($data['quantity']) && !empty($data['quantity'])) {
                            $qty = str_replace(",", "", $data['quantity']);
                        }
                        if (isset($data['available_quantity']) && !empty($data['available_quantity'])) {
                            $ava_qty = str_replace(",", "", $data['available_quantity']);
                        }
                        if ((int) $qty > (int) $ava_qty || (int) $qty == (int) $ava_qty) {
                            $data['quantity'] = $ava_qty;
                            $autorun = true;
                        }

                        list($batch_id) = explode("|", $this->_request->number);
                        $form_values['item_unit_id'] = $this->_request->item_unit_id;
                        $form_values['stock_master_id'] = $stock_id;
                        $form_values['stock_batch_id'] = $batch_id;
                        $data = array_merge($temp, $form_values);
                        $detail_id = $stock_detail->addStockDetail($data);

                        $stock_batch->adjustQuantityByWarehouse($batch_id);
                        if ($autorun) {
                            $stock_batch->autoRunningLEFOBatch($form->getValue('item_id'));
                            $stock_batch->form_values['pk_id'] = $batch_id;
                            $stock_batch->form_values['status'] = Model_StockBatch::FINISHED;
                            $stock_batch->changeStatus();
                        }
                    }
                    $em->getConnection()->commit();
                    $this->redirect("/stock/issue?id=$stock_id");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                    if ($e->getMessage() == 'PLCD_QTY_GREATER_THAN_ISSUE_QTY') {
                        $this->view->status = false;
                        $this->view->msg = "Issue quantity should not greater than placed quantity!";
                        $this->redirect("/stock/issue");
                    }
                }
            }

            $this->view->form = $form;
            $this->view->arr_data = $arr_data;

            if ($this->_request->type == 's') {
                $this->redirect("/stock-demand/");
            }
        }



// Edit Issue Start
        if (!empty($this->_request->id) && !$this->_request->isPost()) {
            $stock_master->form_values = $form_values;
            $temp_stock = $stock_master->getTempStock();

            if ($temp_stock) {
                $arr_data = array_merge($arr_data, $temp_stock);
            }

            $stock_master->form_values['stock_master_id'] = $this->_request->id;
            $temp_stock_list = $stock_master->getTempStocksListStockMasterId();
            if (!empty($temp_stock['stock_id'])) {
                $form->hdn_stock_id->setValue($temp_stock['stock_id']);
                $form->hdn_master_id->setValue($temp_stock['stock_id']);
            } elseif (!empty($stockid)) {
                $form->hdn_stock_id->setValue($stock_id);
                $form->hdn_master_id->setValue($stock_id);
            }
            if ($temp_stock_list) {

                $form->transaction_number->setValue($temp_stock_list[0]['stock_master_id']);
                $form->transaction_date->setValue(date("d/m/Y h:i A", strtotime($temp_stock_list[0]['transaction_date'])));
                $form->warehouse_name->setValue($temp_stock_list[0]['to_warehouse']);
                $form->transaction_reference->setValue($temp_stock_list[0]['transaction_reference']);
                $form->hdn_to_warehouse_id->setValue($temp_stock_list[0]['to_warehouse_id']);
                $arr_data['warehouse_name'] = $temp_stock_list[0]['to_warehouse'];
                $form->activity_id->setValue($temp_stock_list[0]['activity_id']);

                $form->hdn_stock_id->setValue($temp_stock_list[0]['stock_master_id']);
                $form->hdn_master_id->setValue($temp_stock_list[0]['stock_master_id']);
                $arr_data['tempstocks'] = $temp_stock_list;
                //$form->makeFieldReadonly();
            } else {
                $form->transaction_date->setValue(date("d/m/Y h:i A"));
            }
        }
// Edit Issue End


        $this->view->form = $form;

        $base_url = Zend_Registry::get('baseurl');
        switch ($this->_user_level) {
            case 1:
            case 2:
            case 3:
                $this->view->menu_type = 1;
                $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
                break;
            case 4:
            case 5:
            case 6:
                $this->view->menu_type = 2;
                $this->view->inlineScript()->appendFile($base_url . '/js/level_combos.js');
                break;
            default:
                break;
        }


        $this->view->arr_data = $arr_data;
        $this->view->type = $this->_request->getParam("t", "issue");
        $this->view->wh_id = $this->_identity->getWarehouseId();

        $this->view->params = array("province" => $this->_identity->getProvinceId(), "district" => $this->_identity->getDistrictId());
        $this->view->role = $this->_identity->getRoleId();
    }

    /**
     * search requisition
     */
    public function searchRequisitionAction() {
        $form = new Form_StockRequisitionSearch();
        $this->view->form = $form;
        $stock_master = new Model_DemandMaster();
        $from_edit = new Form_StockRequisitionSearch();
        $em = Zend_Registry::get('doctrine');

// Edit Issue Start
        if (!empty($this->_request->id)) {
            $detail_id = $this->_request->id;
            $issue = $this->_em->getRepository("StockDetail")->find($detail_id);
            $from_edit->item_id->setValue($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $from_edit->fillBatchCombo($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $from_edit->number->setValue($issue->getStockBatch()->getPkId());
            $from_edit->vvm_stage->setValue($issue->getVvmStage());
            $from_edit->quantity->setValue(abs($issue->getQuantity()));
            $from_edit->available_quantity->setValue($issue->getStockBatch()->getQuantity());
            $from_edit->expiry_date->setValue($issue->getStockBatch()->getExpiryDate()->format("d/m/Y"));

            $from_edit->transaction_number->setValue($issue->getStockMaster()->getTransactionNumber());
            $from_edit->transaction_date->setValue(date("d/m/Y", strtotime($issue->getStockMaster()->getTransactionDate()->format("Y-m-d"))));
            $from_edit->hdn_transaction_date->setValue(date("d/m/Y", strtotime($issue->getStockMaster()->getTransactionDate()->format("Y-m-d"))));

            $from_edit->warehouse_name->setValue($issue->getStockMaster()->getToWarehouse()->getWarehouseName());
            $from_edit->transaction_reference->setValue($issue->getStockMaster()->getTransactionReference());
            $from_edit->makeFieldReadonly();

            $this->view->issueedit = true;
            $this->view->detail_id = $this->_request->id;

            $base_url = Zend_Registry::get('baseurl');
            switch ($this->_user_level) {
                case 1:
                case 2:
                case 3:
                    $this->view->menu_type = 1;
                    $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
                    break;
                case 4:
                case 5:
                case 6:
                    $this->view->menu_type = 2;
                    $this->view->inlineScript()->appendFile($base_url . '/js/level_combos.js');
                    break;
                default:
                    break;
            }
        }
// Edit Issue End

        if (!empty($this->_request->warehouse)) {
            $warehouse_id = $this->_request->warehouse;
        }

        $created_by = $this->_em->find('Users', $this->_userid);
        if ($this->_request->isPost()) {
            if ($from_edit->isValid($this->_request->getPost())) {
                $editissue = $this->_request->getPost('issueedit');
                if ($editissue == "Yes") {
                    $d_id = $this->_request->getPost('detailid');
                    $obj_stock_detail = $em->getRepository("StockDetail")->find($d_id);
                    $data = $from_edit->getValues();

                    $obj_stock_master = $obj_stock_detail->getStockMaster();
                    $master_update = false;
                    $obj_stock_master = $obj_stock_detail->getStockMaster();
                    if (!empty($data['transaction_date'])) {
                        $trans = $stock_master->getTransactionNumber(2, $data['transaction_date']);
                        $obj_stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['transaction_date'])));

                        $current_date = explode("/", $data['hdn_transaction_date']);

                        $h_mm = $current_date[1];
                        $h_yy = $current_date[2];

                        list($dd, $mm, $yy) = explode("/", $data['transaction_date']);

                        if ($h_mm != $mm || $h_yy != $yy) {
                            $obj_stock_master->setTransactionNumber($trans['trans_no']);
                            $obj_stock_master->setTransactionCounter($trans['id']);
                        }


                        $master_update = true;
                    }

                    $obj_stock_master->setTransactionReference($data['transaction_reference']);

                    if (!empty($warehouse_id)) {
                        $to_wh = $em->getRepository("Warehouses")->find($warehouse_id);
                        $obj_stock_master->setToWarehouse($to_wh);
                        $master_update = true;
                    }

                    if ($master_update) {
                        $obj_stock_master->setModifiedBy($created_by);
                        $obj_stock_master->setModifiedDate(App_Tools_Time::now());
                        $em->persist($obj_stock_master);
                    }

                    $qunty = str_replace(",", "", $data['quantity']);
                    $obj_stock_detail->setQuantity("-" . $qunty);
                    $vvms = $this->_em->getRepository("VvmStages")->find($data['vvm_stage']);
                    $obj_stock_detail->setVvmStage($vvms);
                    $stock_b = $em->getRepository("StockBatch")->find($data['number']);
                    $obj_stock_detail->setStockBatch($stock_b);
                    if (!empty($data['item_unit_id'])) {
                        $item_unit = $em->getRepository("ItemUnits")->find($data['item_unit_id']);
                        $obj_stock_detail->setItemUnit($item_unit);
                    }
                    $obj_stock_detail->setModifiedBy($created_by);
                    $obj_stock_detail->setModifiedDate(App_Tools_Time::now());
                    $em->persist($obj_stock_detail);
                    $em->flush();

                    list($dd, $mm, $yy) = explode("/", $data['transaction_date']);
                    $item_id = $data['item_id'];

                    $wh_id = $this->_identity->getWarehouseId();
                    $user_id = $this->_userid;

                    $query = "SELECT AdjustQty(" . $data['number'] . "," . $wh_id . ") FROM DUAL";
                    $str_sql = $em->getConnection()->prepare($query);
                    $str_sql->execute();
                    $query = "SELECT REPUpdateData($mm,$yy,$item_id,$wh_id,$user_id) FROM DUAL";
                    $str_sql = $em->getConnection()->prepare($query);
                    $str_sql->execute();
                }
            }

            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $stock_master->form_values = $data;
            }
        }

        $dataset = $stock_master->stockRequisitionSearch();
        $this->view->result = $dataset;
        $this->view->from_edit = $from_edit;
        $this->view->vouchertype = $this->_request->getParam('voucher_type', 1);
    }

    /**
     *
     */
    public function printRequisitionAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Requsition Details");
        $stock_master = new Model_DemandMaster();
        $stock_master->form_values['pk_id'] = $this->_request->id;
        $result = $stock_master->getRequisitionDetails();
        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Requisition";
        $this->view->department = $this->_identity->getUserDepartment();
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     *
     */
    public function requisitionPrintAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Requsition Details");
        $stock_master = new Model_DemandMaster();
        $stock_master->form_values['pk_id'] = $this->_request->id;
        $result = $stock_master->getRequisitionDetails();
        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Requisition";
        $this->view->department = $this->_identity->getUserDepartment();
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     * Vaccine Placement Issue
     */
    public function printRequisitionSearchAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Requisitions List");
        $var = $this->_request->grpBy;
        $this->view->val = $var;
        $stock_master = new Model_DemandMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $stock_master->form_values['activity_id'] = $this->_request->activity_id;
        $data = $stock_master->stockRequisitionSearch();
        $this->view->result = $data;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->params = $this->_request->getParams();
        $this->view->print_title = "Stock Requistions List";
    }

    /**
     * Export Excel
     */
    public function exportExcelAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $export_array = array();

        $stock_master = new Model_DemandMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $data = $stock_master->stockRequisitionSearch();

        array_push($export_array, array("Sr.No", "Date", "Req. No", "Period", "To Store", "Ref.No", "Product.", "Demanded Qty", "Approved Qty", "Delivery Date", "Status"));
        $s = 1;
        foreach ($data as $row) {
            $month = date("m", strtotime($row['fromDate']));
            $year = date("Y", strtotime($row['fromDate']));
            if ($month <= 3) {
                $requsitionPeriod = "1st Quarter of " . $year;
            } else if ($month <= 6) {
                $requsitionPeriod = "2nd Quarter of " . $year;
            } else if ($month <= 9) {
                $requsitionPeriod = "3rd Quarter of " . $year;
            } else if ($month <= 12) {
                $requsitionPeriod = "4th Quarter of " . $year;
            }

            $status = '';
            if ($row['draft'] == 0) {
                $status = 'Draft';
            } else if ($row['draft'] == 1) {
                $status = 'Submitted';
            } else if ($row['draft'] == 2) {
                $status = 'Approved';
            } else if ($row['draft'] == 3) {
                $status = 'UnApproved';
            }

            array_push($export_array, array($s, $row['createdDate'], $row['requisitionNumber'], $requsitionPeriod, $row['warehouseName'], $row['requisitionReference'], $row['itemName'], $row['quantity'], $row['approvedQuantity'], $row['suggestedDate'], $status));
            $s++;
        }

        // generate file (constructor parameters are optional)
        $xls = new App_PhpExcel('UTF-8', false, 'Requsitions List');
        $xls->addArray($export_array);
        $xls->generateXML('requisition-list');
    }

    /**
     * Requisition details.
     */
    public function ajaxRequisitionsDetailsAction() {
        $this->_helper->layout->disableLayout();
        $form = new Form_Approval();
        $id = $this->_request->getParam('id', '');
        $demand_master = new Model_DemandMaster();
        if (!empty($id)) {
            $demand_master->form_values['pk_id'] = $id;
            $arr_data = $demand_master->getRequisitionDetails();
            $this->view->voucher_number = $id;

            $this->view->arr_data = $arr_data;
            $attachments = $demand_master->getRequisitionAttachments();
            $this->view->attachments = $attachments;
        }
        $this->view->form = $form;
    }

    /**
     * Requisition details.
     */
    public function ajaxStoreRequisitionsDetailsAction() {
        $this->_helper->layout->disableLayout();
        $id = $this->_request->getParam('id', '');

        $demand_master = new Model_DemandMaster();
        if (!empty($id)) {
            $demand_master->form_values['pk_id'] = $id;
            $arr_data = $demand_master->getRequisitionDetails();
            $this->view->voucher_number = $id;
            $this->view->arr_data = $arr_data;
            $attachments = $demand_master->getRequisitionAttachments();
            $this->view->attachments = $attachments;
        }
    }

    /**
     *
     */
    public function updateRequisitionAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost()) {


            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            $masterIds = $this->_request->getPost('masterId');
            $detailIds = $this->_request->getPost('detailId');
            $approved_qty = $this->_request->getPost('approvedQty');
            $comments = $this->_request->getPost('comments');
            $new_suggested_date = $this->_request->getPost('new_suggested_date');

            $submit_button = $this->_request->getPost('app');


            $demand_master_id = $masterIds[0];

            $demand_master = $this->_em->find('DemandMaster', $demand_master_id);

            // if approved button clicked.
            if (isset($submit_button)) {

                $i = 0;
                if (count($approved_qty) > 0) {

                    foreach ($detailIds as $row) {

                        if (ABS($approved_qty[$row]) <= 0) {
                            continue;
                        }

                        // get demand detail id.
                        $demand_detail_id = $row;
                        $demand_detail = $this->_em->find('DemandDetail', $demand_detail_id);
                        $demand_detail->setApprovedQuantity($approved_qty[$row]);
                        if (!empty($comments[$row])) {
                            $demand_detail->setComments($comments[$row]);
                        }
                        $demand_detail->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($demand_detail);
                        $this->_em->flush();
                        $i++;
                    }

                    // this means no approved qty entered so make it unapproved.
                    if ($i == 0) {
                        $demand_master->setDraft('4');
                    } else {
                        $demand_master->setDraft('2');
                    }
                    $demand_master->setApprovedDate(App_Tools_Time::now());
                    $demand_master->setModifiedDate(App_Tools_Time::now());

                    list($dd, $mm, $yy) = explode("/", $new_suggested_date);

                    $demand_master->setSuggestedDate((new \DateTime($yy . "-" . $mm . "-" . $dd)));

                    foreach ($detailIds as $row) {



                        // get demand detail id.
                        $demand_detail_id = $row;
                        $demand_detail = $this->_em->find('DemandDetail', $demand_detail_id);

                        if (!empty($comments[$row])) {
                            $demand_detail->setComments($comments[$row]);
                        }
                        $demand_detail->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($demand_detail);
                        $this->_em->flush();
                        $i++;
                    }
                }
            } else {
                $demand_master->setDraft('4'); // unapproved.
                foreach ($detailIds as $row) {



                    // get demand detail id.
                    $demand_detail_id = $row;
                    $demand_detail = $this->_em->find('DemandDetail', $demand_detail_id);

                    if (!empty($comments[$row])) {
                        $demand_detail->setComments($comments[$row]);
                    }
                    $demand_detail->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($demand_detail);
                    $this->_em->flush();
                    $i++;
                }
            }

            $this->_em->persist($demand_master);
            $this->_em->flush();

            $em->getConnection()->commit();
        }
        $this->_redirect("stock-demand/approval/voucher/$demand_master_id");
    }

    /**
     *
     */
    public function requisitionSearchAction() {
        $form = new Form_RequisitionSearch();
        $demand_master = new Model_DemandMaster();
        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $demand_master->form_values = $res;
            $form->from_warehouse_id->setValue($res['from_warehouse_id']);
            $form->from_date->setValue($res['from_date']);
            $form->to_date->setValue($res['to_date']);
            $form->status->setValue($res['status']);
        }

        $arr_data = $demand_master->getStoreRequisitions();
        $this->view->arr_data = $arr_data;
        $this->view->form = $form;
        $this->view->voucher = $this->_request->getParam("voucher", "");
        $this->view->msg = $this->_request->getParam('msg', '');
    }

    /**
     * Delete requisition.
     */
    public function deleteRequisitionAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;

            $em = Zend_Registry::get("doctrine");
            $em->getConnection()->beginTransaction();
            try {
                $stock_detail = new Model_DemandDetail();
                $stock_detail->deleteRequisition($id);
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

            if (!empty($this->_request->p) && $this->_request->p == 'stock') {
                echo 1;
                exit;
            } elseif (!empty($this->_request->p) && $this->_request->p == 'issue') {
                $this->redirect("/stock-demand/requisition");
            }
            $this->redirect("/stock-demand/requisition");
        }
    }

    /**
     * Delete requisition master.
     */
    public function deleteRequisitionMasterAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;

            $em = Zend_Registry::get("doctrine");
            $em->getConnection()->beginTransaction();
            try {
                $demand_master = new Model_DemandMaster();
                $demand_master->deleteRequisitionMaster($id);
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

//            if (!empty($this->_request->p) && $this->_request->p == 'stock') {
//            } elseif (!empty($this->_request->p) && $this->_request->p == 'requisition') {
//                $this->redirect("/stock-demand/requisition-search");
//            }
            $this->redirect("/stock-demand/requisition-search/msg/ok");
        }
    }

}
