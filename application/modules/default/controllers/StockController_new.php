<?php

/**
 * StockController
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Stock
 */
class StockController extends App_Controller_Base {

    /**
     * StockController index 
     */
    public function indexAction() {
        
    }

    /**
     * Receive Supplier
     */
    public function receiveSupplierAction() {

        $role_id = $this->_identity->getRoleId();

        if (!empty($this->_request->stock_id)) {
            $stock_id = $this->_request->stock_id;
        } else {
            $stock_id = "";
        }

        $red_type = '';
        if (!empty($this->_request->t)) {
            $red_type = $this->_request->t;
        }
        $this->view->type = $red_type;

        $form_values = array();
        $temp = array();
        $arr_data = array('transaction_number' => "",
            'stock_id' => "",
            'transaction_date' => date("d/m/Y"),
            'success' => $this->_request->success
        );

        $stock_master = new Model_StockMaster();
        $stock_batch = new Model_StockBatch();
        $stock_detail = new Model_StockDetail();
        $warehouse_data = new Model_HfDataMaster();
        $placement = new Model_Placements();
        $form = new Form_ReceiveSupplier();
        $form_manufacturer = new Form_AddManufacturer();

        $form_values['transaction_type_id'] = 1;
        $form_values['adjustment_type'] = 1;

        if ($this->_request->isPost()) {
            if (isset($this->_request->stockid) && !empty($this->_request->stockid)) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    $master_affacted = $stock_master->updateStockMasterTemp($this->_request->stockid, $this->_request->comments);
                    $detail_affacted = $stock_detail->updateStockDetailTemp($this->_request->stockid);

                    //Save Data in warehouse_data table
                    $warehouse_data->addReport($this->_request->stockid, 1);

                    $this->view->msg = 'Stock has been received successfully. Your voucher number is ';
                    $this->view->voucher = $master_affacted;
                    $this->view->master_id = $this->_request->stockid;

                    $em->getConnection()->commit();

                    $alerts = new Model_Alerts();
                    //$alerts->sendEmail($this->_request->stockid, "1");
                    //$alerts->sendSms($this->_request->stockid, "1");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            } elseif ($form->isValid($this->_request->getPost())) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    $recedit = $this->_request->getPost('rcvedit', '');
                    if ($recedit == "Yes") {
                        $d_id = $this->_request->getPost('detailid');
                        $obj_stock_detail = $em->getRepository("StockDetail")->find($d_id);
                        $form_values['stock_master_id'] = $obj_stock_detail->getStockMaster()->getPkId();
                        $form_values['stock_detail_id'] = $d_id;
                        $form_values['stock_batch_id'] = $obj_stock_detail->getStockBatch()->getPkId();
                        $form_values['rcvedit'] = "Yes";
                    } else {
                        $form_values['item_unit_id'] = $this->_request->item_unit_id;
                        $form_values['stock_master_id'] = $stock_id;
                    }

                    $temp = $form->getValues();

                    $data = array_merge($temp, $form_values);
                    $data['edit_type'] = $red_type;

                    if (empty($this->_request->transaction_number) || (!empty($recedit))) {
                        $stock_id = $stock_master->addStockMaster($data);
                    }

                    $data['stock_master_id'] = $stock_id;
                    $data['type'] = $red_type;
                    $batch_id = $stock_batch->addStockBatch($data);

                    if ($role_id == 3 && !empty($data['expiry_date'])) {
                        $stock_batch_wh = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
                        if (count($stock_batch_wh) > 0) {
                            if ($stock_batch_wh->getQuantity() == 0) {
                                $stock_batch_wh->getStockBatch()->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['expiry_date'])));
                                $stock_batch_wh->getStockBatch()->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['production_date'])));
                                $created_by = $this->_em->getRepository('Users')->find($this->_userid);
                                $stock_batch_wh->getStockBatch()->setModifiedBy($created_by);
                                $stock_batch_wh->getStockBatch()->setModifiedDate(App_Tools_Time::now());
                                $this->_em->persist($stock_batch_wh);
                                $this->_em->flush();
                            }
                        }
                    }

                    $wh_id = $this->_identity->getWarehouseId();
                    $query = "SELECT AdjustQty(" . $batch_id . "," . $wh_id . ") FROM DUAL";

                    $str_sql = $em->getConnection()->prepare($query);
                    $str_sql->execute();

                    $detail_id = $stock_detail->addStockDetail($data);
                    $data['detail_id'] = $detail_id;

                    if ($detail_id) {
                        $stock_batch->autoRunningLEFOBatch($form->getValue('item_id'));

                        if (!empty($data['used_for'])) {
                            $stock_used_for = new Model_StockUsedFor();
                            $stock_used_for->form_values = $data;
                            $stock_used_for->addStockUsedFor();
                        }
                    }

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

                    if ($recedit == "Yes") {
                        $date_arr = explode(' ', $data['transaction_date']);
                        $current_date = explode("/", $date_arr[0]);
                        $mm = $current_date[1];
                        $yy = $current_date[2];
                        $item_id = $data['item_id'];

                        $wh_id = $this->_identity->getWarehouseId();
                        $user_id = $this->_userid;

                        $query = "SELECT AdjustQty(" . $batch_id . "," . $wh_id . ") FROM DUAL";
                        $str_sql = $em->getConnection()->prepare($query);
                        $str_sql->execute();

                        $query = "SELECT REPUpdateData($mm,$yy,$item_id,$wh_id,$user_id) FROM DUAL";
                        $str_sql = $em->getConnection()->prepare($query);
                        $str_sql->execute();
                    }

                    $em->getConnection()->commit();

                    $this->redirect("/stock/receive-supplier");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            }


            $arr_data['stock_id'] = (!empty($stock_id) ? $stock_id : "");
            if (!empty($stock_id) || !empty($batch_id) || !empty($master_affacted) || !empty($detail_affacted)) {
                $arr_data['success'] = 1;
            }

            $this->view->form = $form;
            $this->view->arr_data = $arr_data;

            if ($red_type == 's') {
                $this->redirect("/stock/receive-search?success=1");
            }
        }

        if (!empty($this->form_values['transaction_type_id'])) {
            $type = $this->form_values['transaction_type_id'];
        } else {
            $type = $this->_request->type;
        }

        $stock_master->form_values = $form_values;
        $temp_stock = $stock_master->getTempStock();
        if ($temp_stock) {
            $arr_data = $temp_stock;
        }
        $temp_stock_list = $stock_master->getTempStocksList();

        if ($temp_stock_list) {

            $time_arr = explode(' ', $temp_stock_list[0]['transaction_date']);
            $time = date('H:i A', strtotime($time_arr[1]));

            $form->transaction_number->setValue($temp_stock_list[0]['transaction_number']);
            $form->transaction_date->setValue(App_Controller_Functions::dateToUserFormat($time_arr[0]) . ' ' . $time);
            $form->hdn_transaction_date->setValue(App_Controller_Functions::dateToUserFormat($time_arr[0]) . ' ' . $time);
            $form->transaction_reference->setValue($temp_stock_list[0]['transaction_reference']);
            $form->from_warehouse_id->setValue($temp_stock_list[0]['from_warehouse_id']);
            $form->getProductsByActivity($temp_stock_list[0]['activity_id']);
            $form->activity_id->setValue($temp_stock_list[0]['activity_id']);
            $form->vvm_type_id->setValue($temp_stock_list[0]['vvm_type_id']);
            $form->activity_id->setAttribs(array('disable' => 'disable'));
            $form->from_warehouse_id->setAttribs(array('disable' => 'disable'));
            $form->transaction_reference->setAttribs(array('disable' => 'disable'));
            $arr_data['tempstocks'] = $temp_stock_list;
        } else {
            $form->transaction_date->setValue(date("d/m/Y h:i A"));
        }
        $this->view->form = $form;
        $this->view->form_manufacturer = $form_manufacturer;
        $this->view->arr_data = $arr_data;

        // Edit Receive Start
        if (!empty($this->_request->id)) {
            $detail_id = $this->_request->id;
            $obj_rec = $this->_em->getRepository("StockDetail")->find($detail_id);
            if ($red_type == 's') {
                $form->transaction_number->setValue($obj_rec->getStockMaster()->getTransactionNumber());
                $form->transaction_date->setValue($obj_rec->getStockMaster()->getTransactionDate()->format("d/m/Y h:i A"));
                $form->hdn_transaction_date->setValue($obj_rec->getStockMaster()->getTransactionDate()->format("d/m/Y h:i A"));
                $form->transaction_reference->setValue($obj_rec->getStockMaster()->getTransactionReference());
                $form->from_warehouse_id->setValue($obj_rec->getStockMaster()->getFromWarehouse()->getPkId());

                $stakeholder_act = $obj_rec->getStockMaster()->getStakeholderActivity();
                if (count($stakeholder_act) > 0) {
                    $form->getProductsByActivity($stakeholder_act->getPkId());
                    $form->activity_id->setValue($stakeholder_act->getPkId());
                }

                if ($obj_rec->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId() == 1 || $obj_rec->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId() == 4) {
                    $form->vvm_type_id->setValue($obj_rec->getStockBatch()->getVvmType()->getPkId());
                }
            }
            $form->item_id->setValue($obj_rec->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $form->getManufacturerByProductId($obj_rec->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $stakeholder_item_pack_size = $obj_rec->getStockBatch()->getStakeholderItemPackSize();
            if (count($stakeholder_item_pack_size) > 0) {
                $form->manufacturer_id->setValue($stakeholder_item_pack_size->getPkId());
            }
            $form->number->setValue($obj_rec->getStockBatch()->getNumber());
            $form->production_date->setValue($obj_rec->getStockBatch()->getProductionDate()->format("d/m/Y"));
            $form->expiry_date->setValue($obj_rec->getStockBatch()->getExpiryDate()->format("d/m/Y"));
            $form->unit_price->setValue($obj_rec->getStockBatch()->getUnitPrice());
            $form->quantity->setValue($obj_rec->getStockBatch()->getQuantity());
            $form->vvm_stage->setValue($obj_rec->getVvmStage());
            $form->comments->setValue($obj_rec->getStockMaster()->getComments());
            $form->from_warehouse_id->setAttrib('disable', false);
            $form->transaction_reference->setAttrib('disable', false);
            $form->activity_id->setAttrib('disable', false);

            $obj_placements = $this->_em->getRepository("Placements")->findOneBy(array("stockDetail" => $detail_id));
            if (count($obj_placements->getPlacementLocation()) > 0) {
                $form->cold_chain->setValue($obj_placements->getPlacementLocation()->getLocationId());
            }

            $this->view->rcvedit = true;
            $this->view->prod_cat = $obj_rec->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId();
            $this->view->detail_id = $this->_request->id;
        }

        $this->view->temp_vouchers = $stock_master->getTempVouchersReceive();
        // Edit Receive End
        $this->view->success = $this->_request->getParam('success', 0);
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * Receive Search
     */
    public function receiveSearchAction() {
        $form = new Form_StockReceiveSearch();
        $stock_master = new Model_StockMaster();
        $this->view->form = $form;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();
            $stock_master->form_values = $data;
        }

        $dataset_search = $stock_master->getAllItemStock();
        $this->view->result = $dataset_search;
        $this->view->wh_id = $this->_identity->getWarehouseId();

        $auth = App_Auth::getInstance();
        $this->view->role_id = $auth->getRoleId();
    }

    /**
     * New Gatepass
     */
    public function newGatepassAction() {
        $form = new Form_NewGatepass();
        $this->view->ispost = "false";

        if ($this->_request->isPost()) {
            $datefrom = $this->_request->date_from;
            $dateto = $this->_request->date_to;
            $stock_master = new Model_StockMaster();
            $data = $stock_master->stockGatepassSearch($datefrom, $dateto);
            $this->view->data = $data;
            $this->view->ispost = "true";

            $form->date_from->setValue($datefrom);
            $form->date_to->setValue($dateto);

            if (!$data) {
                $form->readFields();
            }
        }

        $this->view->form = $form;
    }

    /**
     * ajaxNewGatepass1
     */
    public function ajaxNewGatepass1Action() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $gp_master = new Model_GatepassMaster();
        $gp_master->form_values = $this->_request->getParams();
        $gp_master->addGatepass();
    }

    /**
     * ajaxGetIssueNo
     */
    public function ajaxGetIssueNoAction() {
        $this->_helper->layout->disableLayout();
        $form = new Form_NewGatepass();
        $this->view->form = $form;

        if (isset($this->_request->datefrom) && !empty($this->_request->datefrom) && isset($this->_request->dateto) && !empty($this->_request->dateto)) {
            $datefrom = $this->_request->datefrom;
            $dateto = $this->_request->dateto;
            $stock_master = new Model_StockMaster();
            $result = $stock_master->stockGatepassSearch($datefrom, $dateto);
            $this->view->result = $result;
        }
    }

    /**
     * ajaxNewGatepass
     */
    public function ajaxNewGatepassAction() {
        $this->_helper->layout->disableLayout();
        $form = new Form_NewGatepass();
        $form->readFields();
        $this->view->form = $form;

        $datefrom = $this->_request->datefrom;
        $dateto = $this->_request->dateto;
        $stock_master = new Model_StockMaster();

        $data = $stock_master->stockGatepassSearch($datefrom, $dateto);
        $this->view->data = $data;
    }

    /**
     * ajaxQuantityDataIssueno
     */
    public function ajaxQuantityDataIssuenoAction() {
        $this->_helper->layout->disableLayout();
        $form = new Form_NewGatepass();
        $this->view->form = $form;

        $stock_master_id = $this->_request->stock_master_id;
        $stock_detail = new Model_StockDetail();
        $result = $stock_detail->quantityDataIssueno($stock_master_id);
        $this->view->data = $result;
    }

    /**
     * Gatepass List
     */
    public function gatepassListAction() {
        $form = new Form_GatepassList();
        $stock_batch = new Model_StockBatch();
        $this->view->form = $form;
        //$params = array();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();
            $stock_batch->form_values = $data;
        }
        //  $sort = $this->_getParam("sort", "asc");
        //  $order = $this->_getParam("order", "gpnumber");
        $result = $stock_batch->stockGatepassSearch();
        $this->view->result = $result;
    }

    /**
     * Stock Bin Placement
     */
    public function stockBinPlacementAction() {
        $stock_master = new Model_StockMaster();
        $id = $this->_request->getParam('id', '');
        $area = $this->_request->getParam('area', '');
        $level = $this->_request->getParam('level', '');
        $placement_location_id = $this->_em->getRepository("PlacementLocations")->find($id);
        $location_id = $placement_location_id->getLocationId();
        $non_ccm = $this->_em->find("NonCcmLocations", $location_id);
        $location_name = $non_ccm->getLocationName();

        $stock_master->form_values['id'] = $id;
        $data_record = $stock_master->getAllItem();
        $this->view->id = $id;
        $this->view->area = $area;
        $this->view->level = $level;
        $this->view->title = $location_name;
        $this->view->result = $data_record;
    }

    /**
     * Stock Bin Placement Vaccines
     */
    public function stockBinPlacementVaccinesAction() {
        $stock_master = new Model_StockMaster();
        $id = $this->_request->getParam('id', '');

        $placement_location_id = $this->_em->getRepository("PlacementLocations")->find($id);
        $location_id = $placement_location_id->getLocationId();
        $cold_chain = $this->_em->find("ColdChain", $location_id);
        $location_name = $cold_chain->getAssetId();

        $stock_master->form_values['id'] = $id;
        $data_record = $stock_master->getAllItemVaccines();
        $this->view->id = $id;
        $this->view->title = $location_name;
        $this->view->result = $data_record;
    }

    /**
     * Stock In Bin Vaccines Print
     */
    public function stockInBinVaccinesPrintAction() {
        $this->_helper->layout->setLayout("print");

        $cold_chain = new Model_ColdChain();
        $placement = new Model_Placements();
        $id = $this->_request->getParam('id', '');
        $asset = $cold_chain->getAssetByLocation($id);

        $placement->form_values['id'] = $id;
        $result = $placement->getStockInBinVaccines("product", "ASC");

        $this->view->id = $id;
        $this->view->result = $result;
        $this->view->bin_id = $id;
        $this->view->title = $asset;
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    /**
     * Monthly Consumption 2
     */
    public function monthlyConsumptionAction() {
        
        $form = new Form_MonthlyConsumption();

        $warehouse = new Model_Warehouses();
        $warehouses = $warehouse->getWarehouseNames();
        $this->view->warehouses = $warehouses;

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->monthlyConsumtionTemp();
            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $items = $item_pack_sizes->monthlyConsumtion2();
            $consumption_var = $item_pack_sizes->monthlyConsumtionColTypes();
           
            $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
            $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
            $warehouse_data->form_values = array('warehouse_id' => $arr_temp['wh_id']);
            $result = $warehouse_data->getMonthYearByWarehouseId2();

            if ($result) {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
                foreach ($result as $row) {
                    $loc_id = $row['location_id'];
                    $do = 'Z' . base64_encode($arr_temp['wh_id'] . '|' . $row['report_year'] . '-' . str_pad($row['report_month'], 2, "0", STR_PAD_LEFT) . '-01' . '|2');
                    $arr_combo[] = array(
                        "key" => $do,
                        "value" => $row['report_month'] . '-' . $row['report_year']
                    );
                }
            } else {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
            }
            $warehouse = $this->_em->getRepository("Warehouses")->find($arr_temp['wh_id']);
            $warehouse_name = $warehouse->getWarehouseName();

            $this->view->monthly_report = $temp;
            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];

            $this->view->warehouse_name = $warehouse_name;
            $this->view->district_name = $warehouse->getDistrict()->getLocationName();
            $tehsil = $this->_em->getRepository("Locations")->find($warehouse->getLocation()->getPkId());
            $tehsil_name = $tehsil->getParent()->getLocationName();
            $this->view->tehsil_name = $tehsil_name;
            $this->view->uc_name = $warehouse->getLocation()->getLocationName();
            $this->view->locid = $loc_id;
            $this->view->items = $items;
            $this->view->items_non_vaccinces = $items_non_vaccines;
            $this->view->items_tt = $items_tt;
            $this->view->consumption_var = $consumption_var;
           
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $hf_data_master = new Model_HfDataMaster();
            $hf_data_master->form_values = $data;
            //$result = $hf_data_master->addMonthlyConsumptionValidation();
            $result = $hf_data_master->addMonthlyConsumption();

            if (empty($result)) {
                $l3m_dt = new DateTime($data['rpt_date']);
                $string = "Z" . base64_encode($data['wh_id'] . '|' . $l3m_dt->format('Y-m-') . '01|2');
                $this->redirect("/stock/monthly-consumption?do=" . $string);
            } else {
                // Open report in edit form after save
                $this->view->error = "P";
                $this->view->msg = $result;
            }
        }
        $this->view->user_id = $user_id = $this->_userid;
        $warehouse_user = $this->_em->getRepository('WarehouseUsers')->findBy(array('user' => $user_id));
        $warehouse_id = $warehouse_user[0]->getWarehouse()->getPkId();
        $this->view->cold_chain_warehouse_id = $warehouse_id;
        $this->view->province_id = $this->_identity->getProvinceId();

        $this->view->user_level = $this->_identity->getUserLevel($this->_userid);
        $this->view->form = $form;

        $this->view->do = $this->_request->do;
    }
    /**
     * Monthly Consumption 2
     */
    public function monthlyConsumptionsAction() {

        $form = new Form_MonthlyConsumption();

        $warehouse = new Model_Warehouses();
        $warehouses = $warehouse->getWarehouseNames();
        $this->view->warehouses = $warehouses;

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->monthlyConsumtionTemp();
            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $items = $item_pack_sizes->monthlyConsumtion2();
            $consumption_var = $item_pack_sizes->monthlyConsumtionColTypes();
           
            $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
            $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
            $warehouse_data->form_values = array('warehouse_id' => $arr_temp['wh_id']);
            $result = $warehouse_data->getMonthYearByWarehouseId2();

            if ($result) {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
                foreach ($result as $row) {
                    $loc_id = $row['location_id'];
                    $do = 'Z' . base64_encode($arr_temp['wh_id'] . '|' . $row['report_year'] . '-' . str_pad($row['report_month'], 2, "0", STR_PAD_LEFT) . '-01' . '|2');
                    $arr_combo[] = array(
                        "key" => $do,
                        "value" => $row['report_month'] . '-' . $row['report_year']
                    );
                }
            } else {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
            }
            $warehouse = $this->_em->getRepository("Warehouses")->find($arr_temp['wh_id']);
            $warehouse_name = $warehouse->getWarehouseName();

            $this->view->monthly_report = $temp;
            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];

            $this->view->warehouse_name = $warehouse_name;
            $this->view->district_name = $warehouse->getDistrict()->getLocationName();
            $tehsil = $this->_em->getRepository("Locations")->find($warehouse->getLocation()->getPkId());
            $tehsil_name = $tehsil->getParent()->getLocationName();
            $this->view->tehsil_name = $tehsil_name;
            $this->view->uc_name = $warehouse->getLocation()->getLocationName();
            $this->view->locid = $loc_id;
            $this->view->items = $items;
            $this->view->items_non_vaccinces = $items_non_vaccines;
            $this->view->items_tt = $items_tt;
            $this->view->consumption_var = $consumption_var;
           
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $hf_data_master = new Model_HfDataMaster();
            $hf_data_master->form_values = $data;
            $result = $hf_data_master->addMonthlyConsumptionValidation();

            if (empty($result)) {
                $l3m_dt = new DateTime($data['rpt_date']);
                $string = "Z" . base64_encode($data['wh_id'] . '|' . $l3m_dt->format('Y-m-') . '01|2');
                $this->redirect("/stock/monthly-consumption?do=" . $string);
            } else {
                // Open report in edit form after save
                $this->view->error = "P";
                $this->view->msg = $result;
            }
        }
        $this->view->user_id = $user_id = $this->_userid;
        $warehouse_user = $this->_em->getRepository('WarehouseUsers')->findBy(array('user' => $user_id));
        $warehouse_id = $warehouse_user[0]->getWarehouse()->getPkId();
        $this->view->cold_chain_warehouse_id = $warehouse_id;
        $this->view->province_id = $this->_identity->getProvinceId();

        $this->view->user_level = $this->_identity->getUserLevel($this->_userid);
        $this->view->form = $form;

        $this->view->do = $this->_request->do;
    }

    /**
     * Monthly Consumption 3
     */
    public function monthlyConsumption3Action() {

        $form = new Form_MonthlyConsumption();

        $warehouse = new Model_Warehouses();
        $warehouses = $warehouse->getWarehouseNames();
        $this->view->warehouses = $warehouses;

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->monthlyConsumtionTemp();
            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $items = $item_pack_sizes->monthlyConsumtion2();
            $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
            $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
            $warehouse_data->form_values = array('warehouse_id' => $arr_temp['wh_id']);
            $result = $warehouse_data->getMonthYearByWarehouseId2();

            if ($result) {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
                foreach ($result as $row) {
                    $loc_id = $row['location_id'];
                    $do = 'Z' . base64_encode($arr_temp['wh_id'] . '|' . $row['report_year'] . '-' . str_pad($row['report_month'], 2, "0", STR_PAD_LEFT) . '-01' . '|2');
                    $arr_combo[] = array(
                        "key" => $do,
                        "value" => $row['report_month'] . '-' . $row['report_year']
                    );
                }
            } else {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
            }
            $warehouse = $this->_em->getRepository("Warehouses")->find($arr_temp['wh_id']);
            $warehouse_name = $warehouse->getWarehouseName();

            $this->view->monthly_report = $temp;
            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];

            $this->view->warehouse_name = $warehouse_name;
            $this->view->district_name = $warehouse->getDistrict()->getLocationName();
            $tehsil = $this->_em->getRepository("Locations")->find($warehouse->getLocation()->getPkId());
            $tehsil_name = $tehsil->getParent()->getLocationName();
            $this->view->tehsil_name = $tehsil_name;
            $this->view->uc_name = $warehouse->getLocation()->getLocationName();
            $this->view->locid = $loc_id;
            $this->view->items = $items;
            $this->view->items_non_vaccinces = $items_non_vaccines;
            $this->view->items_tt = $items_tt;
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $hf_data_master = new Model_HfDataMaster();
            $hf_data_master->form_values = $data;
            $result = $hf_data_master->addMonthlyConsumption2Validation();

            if (empty($result)) {
                $l3m_dt = new DateTime($data['rpt_date']);
                $string = "Z" . base64_encode($data['wh_id'] . '|' . $l3m_dt->format('Y-m-') . '01|2');
                $this->redirect("/stock/monthly-consumption2?do=" . $string);
            } else {
                // Open report in edit form after save
                $this->view->error = "P";
                $this->view->msg = $result;
            }
        }
        $this->view->user_id = $user_id = $this->_userid;
        $warehouse_user = $this->_em->getRepository('WarehouseUsers')->findBy(array('user' => $user_id));
        $warehouse_id = $warehouse_user[0]->getWarehouse()->getPkId();
        $this->view->cold_chain_warehouse_id = $warehouse_id;
        $this->view->province_id = $this->_identity->getProvinceId();

        $this->view->user_level = $this->_identity->getUserLevel($this->_userid);
        $this->view->form = $form;

        $this->view->do = $this->_request->do;
    }
    
    /**
     * Monthly Consumption 2
     */
    public function monthlyConsumption2Action() {

        $form = new Form_MonthlyConsumption();

        $warehouse = new Model_Warehouses();
        $warehouses = $warehouse->getWarehouseNames();
        $this->view->warehouses = $warehouses;

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->monthlyConsumtionTemp();
            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $items = $item_pack_sizes->monthlyConsumtion2();
            $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
            $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
            $warehouse_data->form_values = array('warehouse_id' => $arr_temp['wh_id']);
            $result = $warehouse_data->getMonthYearByWarehouseId2();

            if ($result) {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
                foreach ($result as $row) {
                    $loc_id = $row['location_id'];
                    $do = 'Z' . base64_encode($arr_temp['wh_id'] . '|' . $row['report_year'] . '-' . str_pad($row['report_month'], 2, "0", STR_PAD_LEFT) . '-01' . '|2');
                    $arr_combo[] = array(
                        "key" => $do,
                        "value" => $row['report_month'] . '-' . $row['report_year']
                    );
                }
            } else {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
            }
            $warehouse = $this->_em->getRepository("Warehouses")->find($arr_temp['wh_id']);
            $warehouse_name = $warehouse->getWarehouseName();

            $this->view->monthly_report = $temp;
            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];

            $this->view->warehouse_name = $warehouse_name;
            $this->view->district_name = $warehouse->getDistrict()->getLocationName();
            $tehsil = $this->_em->getRepository("Locations")->find($warehouse->getLocation()->getPkId());
            $tehsil_name = $tehsil->getParent()->getLocationName();
            $this->view->tehsil_name = $tehsil_name;
            $this->view->uc_name = $warehouse->getLocation()->getLocationName();
            $this->view->locid = $loc_id;
            $this->view->items = $items;
            $this->view->items_non_vaccinces = $items_non_vaccines;
            $this->view->items_tt = $items_tt;
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $hf_data_master = new Model_HfDataMaster();
            $hf_data_master->form_values = $data;
            $result = $hf_data_master->addMonthlyConsumption2Validation();

            if (empty($result)) {
                $l3m_dt = new DateTime($data['rpt_date']);
                $string = "Z" . base64_encode($data['wh_id'] . '|' . $l3m_dt->format('Y-m-') . '01|2');
                $this->redirect("/stock/monthly-consumption2?do=" . $string);
            } else {
                // Open report in edit form after save
                $this->view->error = "P";
                $this->view->msg = $result;
            }
        }
        
        $this->view->user_id = $user_id = $this->_userid;
        $warehouse_user = $this->_em->getRepository('WarehouseUsers')->findBy(array('user' => $user_id));
        $warehouse_id = $warehouse_user[0]->getWarehouse()->getPkId();
        $this->view->cold_chain_warehouse_id = $warehouse_id;
        $this->view->province_id = $this->_identity->getProvinceId();

        $this->view->user_level = $this->_identity->getUserLevel($this->_userid);
        $this->view->form = $form;

        $this->view->do = $this->_request->do;
    }

    /**
     * Monthly Consumption Draft
     */
    public function monthlyConsumptionDraftAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $warehouse_data_draft = new Model_HfDataMaster();
            $warehouse_data_draft->form_values = $data;
            $warehouse_data_draft->addMonthlyConsumptionDraft();
        }
    }

    /**
     * Monthly Consumption 2 Draft
     */
    public function monthlyConsumption2DraftAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $this->_em->getConnection()->beginTransaction();

        try {
            if ($this->_request->isPost()) {
                $data = $this->_request->getPost();

                $hf_data_master = new Model_HfDataMaster();
                $hf_data_master->form_values = $data;
                $hf_data_master->addMonthlyConsumption2Draft();
                $this->_em->getConnection()->commit();
            }
        } catch (Exception $e) {
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            App_FileLogger::info($e);
        }
    }

    /**
     * Receive Warehouse
     */
    public function receiveWarehouseAction() {
        $stock_master = new Model_StockMaster();
        $form = new Form_ReceiveWarehouse();
        $save = false;

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();

        try {
            if ($this->_request->isPost()) {
                $data = $this->_request->getPost();
                $issue_no = $data['issue_no'];
                $stock_master = new Model_StockMaster();
                $stock_master->form_values = $data;

                $stkid = $stock_master->addStockWarehouseByIssue();
                Zend_Registry::set('stkid', $stkid);

                $em->getConnection()->commit();
                $save = true;
            } else {
                if (isset($this->_request->search) && !empty($this->_request->search)) {
                    if (isset($this->_request->issue_no) && !empty($this->_request->issue_no)) {
                        $issue_no = $this->_request->issue_no;

                        $stock_master->transaction_number = $issue_no;
                        $stock_master->to_warehouse_id = $this->_identity->getWarehouseId();

                        $stock_batch = new Model_StockBatch();
                        $placement_locations = $stock_batch->getAllColdStores();

                        $non_ccm_loc = new Model_NonCcmLocations();
                        $non_ccm_locations = $non_ccm_loc->getAllDryStores();

                        $stockReceive = $stock_master->getwarehouseStockByIssueNo();

                        $transaction_type = new Model_TransactionTypes();
                        $trans_type = $transaction_type->findAll();
                        $count_o = count($stockReceive);
                        $this->view->count = $count_o;
                        $this->view->form = $form;
                        $this->view->result = $stockReceive;
                        $this->view->issue_no = $issue_no;
                        $this->view->trans_type = $trans_type;
                        $this->view->locations = $placement_locations;
                        $this->view->non_ccm_locations = $non_ccm_locations;

                        $form->issue_no->setValue($issue_no);
                    }
                    $em->getConnection()->commit();
                }
            }
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            App_FileLogger::info($e);

            $this->_redirect("stock/receive-warehouse?msg=Voucher Already Received!");
        }

        if ($save) {
            $stkid = Zend_Registry::get('stkid');
            $alerts = new Model_Alerts();
            $alerts->sendEmail($stkid, "1");
            $alerts->sendSms($stkid, "1");

            $this->_redirect("stock/receive-warehouse?msg=Received sucessfully!");
        }

        $this->view->form = $form;
        $is_scanner_enable = $this->_identity->getIsScannerEnable();

        if ($is_scanner_enable == 'yes') {
            $this->render("receive-warehouse-scanner");
        }
    }

    /**
     * Receive Warehouse Via Scanner
     */
    public function receiveWarehouseViaScannerAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();

        if ($this->_request->isPost()) {
            try {
                $stock_master = new Model_StockMaster();
                $stock_master->form_values = $this->_request->getPost();
                $stock_master->addStockWarehouseViaScanner();

                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }
        }

        $this->_redirect("stock/receive-warehouse?msg=Received sucessfully!");
    }

    /**
     * Issue Search
     */
    public function issueSearchAction() {
        $form = new Form_StockIssueSearch();
        $this->view->form = $form;
        $stock_master = new Model_StockMaster();
        $from_edit = new Form_StockIssue();
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

        $dataset = $stock_master->stockIssueSearch();
        $this->view->result = $dataset;
        $this->view->from_edit = $from_edit;
        $this->view->vouchertype = $this->_request->getParam('voucher_type', 1);
    }

    /**
     * Issue
     */
    public function issueAction() {
        $stock_master = new Model_StockMaster();
        $stock_batch = new Model_StockBatch();
        $stock_detail = new Model_StockDetail();
        $warehouse_data = new Model_HfDataMaster();
        $form = new Form_StockIssue();
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
                    //Start update issue period
                    $array = $this->_request->getParams();
                    $stock_master->updateStockPeriod($master_id, $array);
                    //End update issue period

                    $transaction_number = $stock_master->updateStockMasterTemp($master_id, $this->_request->comments);
                    $stock_detail->updateStockDetailTemp($master_id);
                    //Save Data in warehouse_data table
                    //$warehouse_data->addReport($master_id, 2);
                    $this->view->msg = 'Stock has been issued successfully. Your voucher number is ';
                    $this->view->voucher = $transaction_number;
                    $this->view->master_id = $master_id;

                    // Temp solution for deleting orphan records
                    //  $stock_detail->deleteOrphonRecord();

                    $em->getConnection()->commit();
                    $this->form_values['stock_master_id'] = $master_id;

                    $alerts = new Model_Alerts();
                    $alerts->sendEmail($master_id, "2");
                    $alerts->sendSms($master_id, "2");
                    //exit("Hellsdfsdo");
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
                            $item_unit = $em->getRepository("ItemUnits")->find($data['item_unit_id']);
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
                        if ((int) $qty >= (int) $ava_qty) {
                            $data['quantity'] = $ava_qty;
                            $autorun = true;
                        }

                        list($batch_id) = explode("|", $this->_request->number);
                        $form_values['item_unit_id'] = $this->_request->item_unit_id;
                        $form_values['stock_master_id'] = $stock_id;
                        $form_values['stock_batch_id'] = $batch_id;
                        $data = array_merge($temp, $form_values);
                        $detail_id = $stock_detail->addStockDetail($data);

                        $data['detail_id'] = $detail_id;
                        if (!empty($data['used_for'])) {
                            $stock_used_for = new Model_StockUsedFor();
                            $stock_used_for->form_values = $data;
                            $stock_used_for->addStockUsedFor();
                        }

                        $stock_batch->adjustQuantityByWarehouse($batch_id);
                        if ($autorun) {
                            $stock_batch->autoRunningLEFOBatch($form->getValue('item_id'));
                            $stock_batch->form_values['pk_id'] = $batch_id;
                            $stock_batch->form_values['status'] = Model_StockBatch::FINISHED;
                            $stock_batch->changeStatus();
                        }
                    }
                    $em->getConnection()->commit();

                    // email sms code strat
                    // email sms code end


                    $this->redirect("/stock/issue");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                    if ($e->getMessage() == 'PLCD_QTY_GREATER_THAN_ISSUE_QTY') {
                        $this->view->status = false;
                        $this->view->msg = "Issue quantity should not greater than placed quantity!";
                    }
                }
            }

            $this->view->form = $form;
            $this->view->arr_data = $arr_data;

            if ($this->_request->type == 's') {
                $this->redirect("/stock/");
            }
        }

        $stock_master->form_values = $form_values;
        $temp_stock = $stock_master->getTempStock();
        if ($temp_stock) {
            $arr_data = array_merge($arr_data, $temp_stock);
        }
        $temp_stock_list = $stock_master->getTempStocksList();
        if ($temp_stock_list) {
            $form->transaction_number->setValue($temp_stock_list[0]['transaction_number']);
            $form->transaction_date->setValue(date("d/m/Y h:i A", strtotime($temp_stock_list[0]['transaction_date'])));
            $form->warehouse_name->setValue($temp_stock_list[0]['to_warehouse']);
            $form->transaction_reference->setValue($temp_stock_list[0]['transaction_reference']);
            $form->hdn_to_warehouse_id->setValue($temp_stock_list[0]['to_warehouse_id']);
            $arr_data['warehouse_name'] = $temp_stock_list[0]['to_warehouse'];
            $form->activity_id->setValue($temp_stock_list[0]['activity_id']);

            $arr_data['tempstocks'] = $temp_stock_list;
            $form->makeFieldReadonly();
        } else {
            $form->transaction_date->setValue(date("d/m/Y h:i A"));
        }

        if (!empty($temp_stock['stock_id'])) {
            $form->hdn_stock_id->setValue($temp_stock['stock_id']);
            $form->hdn_master_id->setValue($temp_stock['stock_id']);
        } elseif (!empty($stockid)) {
            $form->hdn_stock_id->setValue($stock_id);
            $form->hdn_master_id->setValue($stock_id);
        }

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

        // Edit Issue Start
        if (!empty($this->_request->id)) {
            $detail_id = $this->_request->id;
            $issue = $this->_em->getRepository("StockDetail")->find($detail_id);
            $form->transaction_number->setValue($issue->getStockMaster()->getTransactionNumber());
            $form->transaction_date->setValue($issue->getStockMaster()->getTransactionDate()->format("d/m/Y h:i A"));
            $form->warehouse_name->setValue($issue->getStockMaster()->getToWarehouse()->getWarehouseName());
            $form->transaction_reference->setValue($issue->getStockMaster()->getTransactionReference());
            $arr_data['warehouse_name'] = $issue->getStockMaster()->getToWarehouse()->getWarehouseName();
            $form->item_id->setValue($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $form->fillBatchCombo($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $form->number->setValue($issue->getStockBatch()->getPkId());
            $form->vvm_stage->setValue($issue->getVvmStage());
            $form->quantity->setValue($issue->getQuantity());
            $av_qty = $issue->getStockBatch()->getQuantity() + ABS($issue->getQuantity());
            $form->available_quantity->setValue($av_qty);
            $form->expiry_date->setValue($issue->getStockBatch()->getExpiryDate()->format("d/m/Y"));
            $this->view->issueedit = true;
            $this->view->detail_id = $this->_request->id;
        }
        // Edit Issue End
        $this->view->arr_data = $arr_data;
        $this->view->type = $this->_request->getParam("t", "issue");
        $this->view->wh_id = $this->_identity->getWarehouseId();

        $this->view->params = array("province" => $this->_identity->getProvinceId(), "district" => $this->_identity->getDistrictId());
        $this->view->role = $this->_identity->getRoleId();
        $this->view->temp_vouchers = $stock_master->getTempVouchersIssue();
    }

    /**
     * Adjustment Search
     */
    public function adjustmentSearchAction() {

        $form = new Form_AdjustmentSearch();
        $this->view->form = $form;
        $stock_master = new Model_StockMaster();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();
            $form->hdn_batch_no->setValue($data['batch_no']);
            $stock_master->form_values = $data;
        }

        $stock_adjustment_search = $stock_master->stockAdjustmentSearch();
        $this->view->result = $stock_adjustment_search;
        $this->view->role = $this->_identity->getRoleId();
    }

    /**
     * Purpose Transfer Search
     */
    public function stockTransferSearchAction() {

        $form = new Form_StockTransferSearch();
        $this->view->form = $form;
        $stock_master = new Model_StockMaster();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();
            $form->hdn_batch_no->setValue($data['batch_no']);
            $stock_master->form_values = $data;
        }
        $stock_adjustment_search = $stock_master->stockTransferSearch();
        $this->view->result = $stock_adjustment_search;
        $this->view->role = $this->_identity->getRoleId();
    }

    /**
     * ajaxAdjustedBatches
     */
    public function ajaxAdjustedBatchesAction() {
        $this->_helper->layout->disableLayout();

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values['item_id'] = $this->_request->item_id;
        $this->view->adjusted_batches = $stock_batch->getAdjustedBatches();
    }

    /**
     * Add Adjustment
     */
    public function addAdjustmentAction() {
        $form = new Form_Adjustment();
        $batch_form = new Form_AddBatch();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $data = $form->getValues();
                $stock_master = new Model_StockMaster();
                $stock_master->form_values = $data;
                $result = $stock_master->addAdjustment();
                $this->view->status = $result;
                $form->reset();
                $form->adjustment_date->setValue(date("d/m/Y"));

                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e->getMessage());
                $this->view->status = false;
                switch ($e->getMessage()) {
                    case 'NEGATIVE_OR_ZERO_QTY':
                        $this->view->msg = 'Quantity should not be less than zero for negative adjustments!';
                        break;
                    case 'ADJ_QTY_GREATER_BATCH_QTY':
                        $this->view->msg = 'Adjustment Quantity should not be greater than batch Quantity!';
                        break;
                    case 'PLCD_QTY_LESS_EQUAL_ZERO':
                        $this->view->msg = 'Placed quantity should not be less than or equal to zero!';
                        break;
                    case 'PICK_ERROR':
                        $this->view->msg = 'Adjustment Quantity should not be greater than placed quantity.';
                        break;
                    case 'ADJ_QTY_LESS_EQUAL_PLCD_QTY':
                        $this->view->msg = 'Adjustment quantity should be less than or equal to placed quantity!';
                        break;
                    default:
                        break;
                }

                $form->product->setValue("");
                $form->quantity->setValue("");
            }
        }
        $this->view->province = $this->_identity->getProvinceId();
        $this->view->userid = $this->_userid;
        $this->view->role = $this->_identity->getRoleId();
        $this->view->form = $form;
        $this->view->batch_form = $batch_form;
    }

    /**
     * Delete Adjustment
     */
    public function deleteAdjustmentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;

            $this->_em->getConnection()->beginTransaction();
            try {
                $stock_master = new Model_StockMaster();
                $stock_master->deleteAdjustment($id);

                $this->_em->getConnection()->commit();
            } catch (Exception $e) {
                $this->_em->getConnection()->rollback();
                $this->_em->close();
                App_FileLogger::info($e);
            }

            echo 1;
            exit;
        }
    }

    /**
     * Delete
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;

            $em = Zend_Registry::get("doctrine");
            $em->getConnection()->beginTransaction();
            try {
                $stock_master = new Model_StockMaster();
                $stock_master->deleteReceive($id);
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

            if (!empty($this->_request->p) && $this->_request->p == 'stock') {
                $this->redirect("/stock/receive-search?s=t");
                //exit;
            }

            $this->redirect("/stock/receive-supplier?s=t");
            //exit;
        }
    }

    /**
     * Delete Issue
     */
    public function deleteIssueAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;

            $em = Zend_Registry::get("doctrine");
            $em->getConnection()->beginTransaction();
            try {
                if ($this->isIssueDelete($id) == 'DIRECT_DELETE') {
                    $stock_detail = new Model_StockDetail();
                    $stock_detail->deleteIssue($id);
                    $em->getConnection()->commit();
                }
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

            if (!empty($this->_request->p) && $this->_request->p == 'stock') {
                echo 1;
                exit;
            } elseif (!empty($this->_request->p) && $this->_request->p == 'requisition') {
                $this->redirect("/stock/requisition-issue/demandMasterId/" . $this->_request->demandMasterId);
            } elseif (!empty($this->_request->p) && $this->_request->p == 'issue') {
                $this->redirect("/stock/issue");
            }

            $this->redirect("/stock/issue-supplier");
        }
    }

    public function isIssueDelete($detail_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.quantity")
                ->from('StockDetail', 'sd')
                ->where("sd.isReceived = 0")
                ->andWhere("sd.pkId = $detail_id");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            $sql_plc = $this->_em_read->createQueryBuilder()
                    ->select("SUM(p.quantity) qty")
                    ->from('Placements', 'p')
                    ->where("p.stockDetail = $detail_id")
                    ->groupBy("p.stockDetail")
                    ->having("qty = '" . $row[0]['quantity'] . "'");

            $row_plc = $sql_plc->getQuery()->getResult();

            if (count($row_plc) > 0) {
                return 'DIRECT_DELETE';
            } else {
                return 'CUSTOM_DELETE';
            }
        } else {
            return 'VOUCHER_RECEIVED';
        }
    }

    /**
     * Check Cc Capacity
     */
    public function checkCcCapacityAction() {
        $cold_chain = $this->_request->cold_chain;
        $quantity = $this->_request->quantity;

        echo $quantity . "<br>";
        echo $cold_chain;
        //exit;
    }

    /**
     * cc Qty Add
     */
    public function ccQtyAddAction() {

        $form = new Form_ColdChainQtyAdd();

        $this->view->form = $form;

        $var['stockDetailId'] = base64_decode($this->_request->stockDetailId);
        $var['qty'] = base64_decode($this->_request->qty);
        $var['batchID'] = base64_decode($this->_request->batchID);
        $var['product'] = base64_decode($this->_request->product);
        $var['id'] = base64_decode($this->_request->id);
        $stock_batch = new Model_StockBatch();
        $placement = new Model_Placements();
        $placement_qty = new Model_PlacementQuantity();
        $placement->form_values['stock_detail'] = $var['stockDetailId'];
        $place_lis = $placement->getListing();
        $count = $placement->sumPlace($var['stockDetailId']);
        $batch_name = $stock_batch->pickBatch($var['batchID']);
        $sum_place = $placement->sumPlace($var['stockDetailId']);
        $count_remaing = $placement->countRemaining($var['qty'], $var['stockDetailId']);

        $this->view->count = $count;
        $this->view->countRemaing = $count_remaing;
        $this->view->place_lis = $place_lis;
        $this->view->placement = $sum_place;
        $this->view->batch = $batch_name;
        $this->view->result = $var;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();
            $other = '&batchID=' . base64_encode($var['batchID']) . '&qty=' . base64_encode($var['qty']) . '&stockDetailId=' . base64_encode($var['stockDetailId']) . '&product=' . base64_encode($var['product']);


            if ($data['quantity'] > $data['rem_quantity']) {
                $err_msg = 'Quantity is greater than available Quantity ' . $data['rem_quantity'];
                $this->redirect("/stock/cc-qty-add?err_msg=" . base64_encode($err_msg) . $other);
                exit;
            } else if ($data['quantity'] < 1) {
                $err_msg = 'Please insert some quantity';
                $this->redirect("/stock/cc-qty-add?err_msg=" . base64_encode($err_msg) . $other);
                exit;
            } else {
                $net_quantity = $placement_qty->find_by_asset_batch($data['coldchain'], $var['batchID']);

                $data['pk_id'] = $net_quantity['0']['pk_id'];

                $data['batchID'] = $var['batchID'];
                $data['stockDetailId'] = $var['stockDetailId'];

                $placement_qty->add($data);
                $placement->add($data);
                $placement_qty->update($data);
            }
        }
    }

    /**
     * cc Qty Pick
     */
    public function ccQtyPickAction() {
        $form = new Form_ColdChainQtyAdd();
        $this->view->form = $form;
    }

    /**
     * print Receive
     */
    public function printReceiveAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Receive Voucher");
        $stock_id = $this->_request->id;
        $type = $this->_request->getParam('type', '');

        $stock_master = new Model_StockMaster;
        $stock_master->form_values['pk_id'] = $stock_id;

        $result = $stock_master->getStocksReceiveList($type);
        $this->view->result = $result;

        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->wh_id = $this->_identity->getWarehouseId();
        $this->view->print_title = "Stock Receive Voucher";
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     * print Receive Shipment
     */
    public function printReceiveShipmentAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Receive List");
        $stock_id = $this->_request->id;
        $warehouse_id = $this->_request->warehouse_id;
        $stock_master = new Model_StockMaster;
        $stock_master->form_values['pk_id'] = $stock_id;
        $stock_master->form_values['warehouse_id'] = $warehouse_id;
        $result = $stock_master->getStocksReceiveListShipment();

        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $result['0']['warehouseName'];
        $this->view->print_title = "Stock Receive from Warehouse Voucher";
    }

    /**
     * stock Receive List
     */
    public function stockReceiveListAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Receive List");
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $dataset = $stock_master->getAllItemStock();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Receive List";
        $this->view->result = $dataset;
    }

    /**
     * Vaccine Placement Issue
     */
    public function vaccinePlacementIssueAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Vaccine Placement List");
        $var = $this->_request->grpBy;
        $this->view->val = $var;
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $stock_master->form_values['activity_id'] = $this->_request->activity_id;
        $data = $stock_master->getTempStockIssue();
        $this->view->result = $data;
        $this->view->result = $data;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();

        $this->view->params = $this->_request->getParams();

        if ($var == "prod") {
            $this->view->print_title = "Product wise Stock Issue  List";
        } else if ($var == "loc") {
            $this->view->print_title = "Location wise Stock Issue  List";
        } else {
            $this->view->print_title = "Stock Issue List";
        }
    }

    /**
     * Vaccine Placement Receive
     */
    public function vaccinePlacementReceiveAction() {
        $this->_helper->layout->setLayout('print');
        $var = $this->_request->grpBy;
        $this->view->val = $var;
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $stock_master->form_values['activity_id'] = $this->_request->activity_id;
        $data = $stock_master->getTempStockReceive();
        $this->view->result = $data;
        $this->view->result = $data;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();

        $this->view->params = $this->_request->getParams();

        if ($var == "prod") {
            $this->view->headTitle("Product wise Stock Receive Detail");
            $this->view->print_title = "Product wise Stock Receive Detail";
        } else if ($var == "loc") {
            $this->view->headTitle("Location wise Stock Receive Detail");
            $this->view->print_title = "Location wise Stock Receive Detail";
        } else {
            $this->view->headTitle("Stock Receive Detail");
            $this->view->print_title = "Stock Receive Detail";
        }
    }

    /**
     * Export Excel
     */
    public function exportExcelAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $export_array = array();

        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $data = $stock_master->getTempStockIssue();

        array_push($export_array, array("Item Name", "Transaction Date", "Transaction Number", "Transaction Reference", "Supplier/Store", "Batch no.", "Expiry date", "Quantity", "Item Unit", "Doses per vial", "Total doses", "VVM Stage"));
        foreach ($data as $row) {
            array_push($export_array, array($row['itemName'], $row['transactionDate'], $row['transactionNumber'], $row['transactionReference'], $row['warehouseName'], $row['number'], $row['expiryDate'], $row['quantity'], $row['itemUnitName'], $row['description'], $row['quantity'] * $row['description'], $row['vvmStage']));
        }

// generate file (constructor parameters are optional)
        $xls = new App_PhpExcel('UTF-8', false, 'Issue List');
        $xls->addArray($export_array);
        $xls->generateXML('issue-list');
    }

    /**
     * Export Excel Receive
     */
    public function exportExcelReceiveAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $export_array = array();

        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $data = $stock_master->getTempStockReceive();

        array_push($export_array, array("Item Name", "Transaction Date", "Transaction Number", "Transaction Reference", "Supplier/Store", "Batch no.", "Expiry date", "Quantity", "Item Unit", "Doses per vial", "Total doses", "VVM Stage"));
        foreach ($data as $row) {
            array_push($export_array, array($row['itemName'], $row['transactionDate'], $row['transactionNumber'], $row['transactionReference'], $row['warehouseName'], $row['number'], $row['expiryDate'], $row['quantity'], $row['itemUnitName'], $row['description'], $row['quantity'] * $row['description'], $row['vvmStage']));
        }

// generate file (constructor parameters are optional)
        $xls = new App_PhpExcel('UTF-8', false, 'Receive List');
        $xls->addArray($export_array);
        $xls->generateXML('receive-list');
    }

    /**
     * Vaccine Placement Issue Summary
     */
    public function vaccinePlacementIssueSummaryAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Vaccine Placement List");
        $var = $this->_request->type;
        $this->view->val = $var;
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $stock_master->form_values['activity_id'] = $this->_request->activity_id;
        $data = $stock_master->getTempStockIssueSummary($var);
        $this->view->result = $data;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        if ($var == "prod") {
            $this->view->print_title = "Product wise Stock Issue Summary List";
        } else {
            $this->view->print_title = "Location wise Stock Issue Summary List";
        }
    }

    /**
     * Vaccine Placement Receive Summary
     */
    public function vaccinePlacementReceiveSummaryAction() {
        $this->_helper->layout->setLayout('print');

        $var = $this->_request->type;
        $this->view->val = $var;
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $stock_master->form_values['activity_id'] = $this->_request->activity_id;
        $data = $stock_master->getTempStockReceiveSummary($var);
        $this->view->result = $data;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        if ($var == "prod") {
            $this->view->headTitle("Product wise Stock Receive Summary");
            $this->view->print_title = "Product wise Stock Receive Summary";
        } else {
            $this->view->headTitle("Location wise Stock Receive Summary");
            $this->view->print_title = "Location wise Stock Receive Summary";
        }
    }

    /**
     *
     */
    public function printIssueAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Issue/Dispatch Voucher");
        $stock_master = new Model_StockMaster;
        $stock_master->form_values['pk_id'] = $this->_request->id;
        $result = $stock_master->getStocksIssueList();
        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Issue/Dispatch Voucher";
        $this->view->department = $this->_identity->getUserDepartment();
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     * Print Issue
     */
    public function printIssueCancelAction() {

        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Issue Cancel List");
        $stock_master = new Model_StockMaster;

        $stock_master->form_values['pk_id'] = $this->_request->id;


        $result = $stock_master->getStocksIssueCancelList();
        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Issue/Canceled Voucher";
        $this->view->department = $this->_identity->getUserDepartment();
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     * Print Issue Shipment
     */
    public function printIssueShipmentAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Issue List");
        $stock_master = new Model_StockMaster;
        $stock_master->form_values['pk_id'] = $this->_request->id;
        $stock_master->form_values['warehouse_id'] = $this->_request->warehouse_id;
        $result = $stock_master->getStocksIssueListShipment();
        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $result[0]['warehouse_name'];
        $this->view->print_title = "Stock Issue/Dispatch Voucher";
        $this->view->department = $this->_identity->getUserDepartment();
    }

    /**
     * Print Pending Shipment
     */
    public function printPendingShipmentAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Issue List");
        $stock_master = new Model_StockMaster;
        $stock_master->form_values['pk_id'] = $this->_request->id;
        $stock_master->form_values['warehouse_id'] = $this->_request->warehouse_id;
        $result = $stock_master->getStocksPendingListShipment();
        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $result[0]['warehouse_name'];
        $this->view->print_title = "Stock Issue/Dispatch Voucher";
        $this->view->department = $this->_identity->getUserDepartment();
    }

    /**
     * Stock Issue Print
     */
    public function stockIssuePrintAction() {
        $this->_helper->layout->setLayout('print');

        $this->view->headTitle("Stock Issue List");
        $temp_stock_list = array();
        $stock_master = new Model_StockMaster;
        $stock_master->form_values['pk_id'] = $this->_request->id;

        $temp_stock_list = $stock_master->getStocksIssueList();
        $this->view->arr_data = $temp_stock_list;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Issue Voucher";
    }

    /**
     * Stock Adjustment Print
     */
    public function stockAdjustmentPrintAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Adjustments List");
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['adjustment_no'] = $this->_request->adjustment_no;
        $stock_master->form_values['adjustment_type'] = $this->_request->adjustment_type;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['batch_no'] = $this->_request->batch_no;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $stock_adjustment = $stock_master->stockAdjustment();
        $this->view->result = $stock_adjustment;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Adjustment";
    }

    /**
     * ajaxProductCatAndDoses
     */
    public function ajaxProductCatAndDosesAction() {
        $this->_helper->layout->disableLayout();
        $item_pack_size = new Model_ItemPackSizes();
        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            $item_pack_size->form_values['pk_id'] = $this->_request->item_id;
            $this->view->category = $item_pack_size->getProductCategory();
        }

        if (isset($this->_request->quantity) && !empty($this->_request->quantity) && !empty($this->_request->itemId)) {
            $quantity = $this->_request->quantity;
            $item_pack_size->form_values['pk_id'] = $this->_request->itemId;
            $doses = $item_pack_size->getProductDoses();
            $this->view->doses = $doses * str_replace(',', '', $quantity) . ' Doses';
        }
    }

    /**
     * ajaxCheckProductCategory
     */
    public function ajaxCheckProductCategoryAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $item_id = $this->_request->item_id;
        $item = $this->_em->getRepository("ItemPackSizes")->find($item_id);

        echo $item->getItemCategory()->getPkId();
    }

    /**
     * ajaxInlineEdit
     */
    public function ajaxInlineEditAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $stock_batch = new Model_StockBatch();
        $stock_detail = new Model_StockDetail();

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $type = $this->_request->type;
            $stock_detail->form_values['adjustment_type'] = $this->_request->adjustment_type;

            if ($type == 'quantity') {
                $stock_detail->form_values['pk_id'] = $this->_request->id;
                $stock_detail->form_values['quantity'] = str_replace(",", "", $this->_request->data);
                $stock_detail->editQuantity();
            } else if ($type == 'batch') {
                $stock_batch->form_values['pk_id'] = $this->_request->id;
                $stock_batch->form_values['number'] = $this->_request->data;
                $str_result = $stock_batch->editBatchNo();
                if ($str_result) {
                    echo "true";
                } else {
                    echo "false";
                }
            }
        }
    }

    /**
     * ajaxUcCenters
     */
    public function ajaxUcCentersAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->uc) && !empty($this->_request->uc)) {
            $id = $this->_request->uc;
            $warehouse = new Model_Warehouses();
            $warehouse->form_values = array('location_id' => $id);
            $result = $warehouse->getAllUCCenters();
            $this->view->result = $result;
        }
    }

    /**
     * Load Last 3 months
     */
    public function loadLast3monthsAction() {
        $this->_helper->layout->disableLayout();

        $wh_id = $this->_request->warehouse_id;
        $loc_id = $this->_request->location_id;

        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id, 'loc_id' => $loc_id);

        if (isset($this->_request->update) && !empty($this->_request->update)) {
            $result = $reports->last3monthsUpdate();
        } else {
            $result = $reports->last3months();
        }
        $this->view->result = $result;
    }

    /**
     * Stock Receive Print
     */
    public function stockReceivePrintAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Receive Voucher");
        $arr_data = array();
        $stock_master = new Model_StockMaster();
        $form_values['transaction_type_id'] = 1;
        $stock_master->form_values = $form_values;

        $temp_stock = $stock_master->getTempStock();
        if ($temp_stock) {
            $arr_data = array_merge($arr_data, $temp_stock);
        }
        $temp_stock_list = $stock_master->getTempStocksList();

        if ($temp_stock_list) {
            $arr_data['tempstocks'] = $temp_stock_list;
        }

        $this->view->arr_data = $arr_data;
        $this->view->print_title = "Stock Receive from Supplier Voucher";
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    /**
     * Explorer
     */
    public function explorerAction() {
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * ajaxExplorer
     */
    public function ajaxExplorerAction() {
        $this->_helper->layout->setLayout('ajax');
        $warehouse_data = new Model_HfDataMaster();

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $base_url = Zend_Registry::get('baseurl');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/media/css/jquery.dataTables.css');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables_1.js');
            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/demo/tables.js');

            $temp = $this->_request->do;
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->getExplorerReport();

            $this->view->month = $arr_temp['month'];
            $this->view->wh_id = $arr_temp['wh_id'];
            $this->view->loc_id = $arr_temp['loc_id'];
            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->yy = $arr_temp['yy'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->dd = $arr_temp['dd'];
            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->stakeholder_id = $arr_temp['stakeholder_id'];
            $this->view->warehouse_level = $arr_temp['warehouse_level'];
            $warehouse_data->warehouse_id = $arr_temp['wh_id'];
            $this->view->province_id = $warehouse_data->getProvinceId();
            $reports = new Model_Reports();
            $reports->form_values = array("wh_id" => $arr_temp['wh_id']);
            $max_date = $reports->getLastCreatedDateIm();

            $this->view->created_date = date("d/m/Y", strtotime($max_date));
        }
    }

    /**
     * ajaxExplorer2
     */
    public function ajaxExplorer2Action() {
        $this->_helper->layout->setLayout('ajax');
        $warehouse_data = new Model_HfDataMaster();

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $base_url = Zend_Registry::get('baseurl');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/media/css/jquery.dataTables.css');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables_1.js');
            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/demo/tables.js');

            $temp = $this->_request->do;
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->getExplorerReport();

            $this->view->month = $arr_temp['month'];
            $this->view->wh_id = $arr_temp['wh_id'];
            $this->view->loc_id = $arr_temp['loc_id'];
            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->yy = $arr_temp['yy'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->dd = $arr_temp['dd'];
            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->stakeholder_id = $arr_temp['stakeholder_id'];
            $this->view->warehouse_level = $arr_temp['warehouse_level'];

            $reports = new Model_Reports();
            $reports->form_values = array("wh_id" => $arr_temp['wh_id']);
            $max_date = $reports->getLastCreatedDate();

            $this->view->created_date = date("d/m/Y", strtotime($max_date));
        }
    }

    /**
     * ajaxConsumption
     */
    public function ajaxConsumptionAction() {
        $this->_helper->layout->setLayout('ajax');

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $base_url = Zend_Registry::get('baseurl');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/media/css/jquery.dataTables.css');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables_1.js');
            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/demo/tables.js');

            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;

            $arr_temp = $warehouse_data->monthlyConsumtionTemp();

            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $warehouse_data->warehouse_id = $arr_temp['wh_id'];
            $res = $warehouse_data->getProvinceId();
            $this->view->province_id = $res['province_id'];
            $this->view->warehouse_name = $res['warehouse_name'];

            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];
            $province_id = $res['province_id'];

            if (strtotime($arr_temp['yy'] . '-' . $arr_temp['mm']) >= strtotime('2015-05') && $province_id == 2 || $province_id == 5 || $province_id == 7) {

                $item_pack_sizes = new Model_ItemPackSizes();
                $item_pack_sizes->form_values = array(
                    'month' => $arr_temp['mm'],
                    'year' => $arr_temp['yy'],
                    'wh_id' => $arr_temp['wh_id']
                );
                $items = $item_pack_sizes->monthlyConsumtion2();
                $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
                $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
                $this->view->items = $items;
                $this->view->items_non_vaccinces = $items_non_vaccines;
                $this->view->items_tt = $items_tt;
            } else if (strtotime($arr_temp['yy'] . '-' . $arr_temp['mm']) >= strtotime('2018-01') && $province_id == 6) {

                $item_pack_sizes = new Model_ItemPackSizes();
                $item_pack_sizes->form_values = array(
                    'month' => $arr_temp['mm'],
                    'year' => $arr_temp['yy'],
                    'wh_id' => $arr_temp['wh_id']
                );
                $items = $item_pack_sizes->monthlyConsumtion2();
                $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
                $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
                $this->view->items = $items;
                $this->view->items_non_vaccinces = $items_non_vaccines;
                $this->view->items_tt = $items_tt;
            } else if (strtotime($arr_temp['yy'] . '-' . $arr_temp['mm']) >= strtotime('2018-01') && $province_id == 1) {

                $item_pack_sizes = new Model_ItemPackSizes();
                $item_pack_sizes->form_values = array(
                    'month' => $arr_temp['mm'],
                    'year' => $arr_temp['yy'],
                    'wh_id' => $arr_temp['wh_id']
                );
                $items = $item_pack_sizes->monthlyConsumtion2();
                $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
                $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
                $this->view->items = $items;
                $this->view->items_non_vaccinces = $items_non_vaccines;
                $this->view->items_tt = $items_tt;
            } else if (strtotime($arr_temp['yy'] . '-' . $arr_temp['mm']) >= strtotime('2018-01') && $province_id == 8) {

                $item_pack_sizes = new Model_ItemPackSizes();
                $item_pack_sizes->form_values = array(
                    'month' => $arr_temp['mm'],
                    'year' => $arr_temp['yy'],
                    'wh_id' => $arr_temp['wh_id']
                );
                $items = $item_pack_sizes->monthlyConsumtion2();
                $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
                $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();
                $this->view->items = $items;
                $this->view->items_non_vaccinces = $items_non_vaccines;
                $this->view->items_tt = $items_tt;
            } else {
                $item_pack_sizes = new Model_ItemPackSizes();
                $item_pack_sizes->form_values = array(
                    'month' => $arr_temp['mm'],
                    'year' => $arr_temp['yy'],
                    'wh_id' => $arr_temp['wh_id']
                );
                $items = $item_pack_sizes->monthlyConsumtion();
                $this->view->items = $items;
            }
            $reports = new Model_Reports();
            $reports->form_values = array("wh_id" => $arr_temp['wh_id']);
            $max_date = $reports->getLastModifiedDate();
            if (!empty($max_date)) {
                $this->view->modified_date = date("d/m/Y", strtotime($max_date));
            }
        }
    }

    /**
     * ajaxConsumption2
     */
    public function ajaxConsumption2Action() {
        $this->_helper->layout->setLayout('ajax');

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $base_url = Zend_Registry::get('baseurl');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css');
            $this->view->headLink()->appendStylesheet($base_url . '/common/assets/global/plugins/datatables/media/css/jquery.dataTables.css');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables_1.js');
            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js');

            $this->view->inlineScript()->appendFile($base_url . '/common/theme/scripts/demo/tables.js');

            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;

            $arr_temp = $warehouse_data->monthlyConsumtionTemp();

            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $items = $item_pack_sizes->monthlyConsumtion2();
            $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
            $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();

            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];
            $this->view->items = $items;
            $this->view->items_non_vaccinces = $items_non_vaccines;
            $this->view->items_tt = $items_tt;
            $this->view->province_id = $this->_identity->getProvinceId();

            $reports = new Model_Reports();
            $reports->form_values = array("wh_id" => $arr_temp['wh_id']);
            $max_date = $reports->getLastModifiedDate();
            if (!empty($max_date)) {
                $this->view->modified_date = date("d/m/Y", strtotime($max_date));
            }
        }
    }

    /**
     * Print Explorer
     */
    public function printExplorerAction() {
        $this->_helper->layout->setLayout('print');
        $warehouse_data = new Model_HfDataMaster();

        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $temp = $this->_request->do;

            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->getExplorerReport();

            $this->view->month = $arr_temp['month'];
            $this->view->wh_id = $arr_temp['wh_id'];
            $this->view->loc_id = $arr_temp['loc_id'];
            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->yy = $arr_temp['yy'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->dd = $arr_temp['dd'];
            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->stakeholder_id = $arr_temp['stakeholder_id'];
            $this->view->warehouse_level = $arr_temp['warehouse_level'];
            $this->view->username = $this->_identity->getUserName();
            $wh = $this->_em->getRepository("Warehouses")->find($arr_temp['wh_id']);
            $this->view->warehousename = $wh->getWarehouseName();
            $this->view->print_title = "LMIS Explorer";
        }
    }

    /**
     * ajaxReportCombo
     */
    public function ajaxReportComboAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $warehouse_data = new Model_HfDataMaster();
            $level = $this->_request->level;
            $warehouse_id = $this->_request->warehouse_id;
            $warehouse_data->form_values = array('warehouse_id' => $warehouse_id, 'level' => $level);
            $result = $warehouse_data->getMonthYearByWarehouseId();
            $this->view->warehouse_id = $warehouse_id;
            $this->view->result = $result;
            $this->view->level = $this->_request->level;
        }
    }

    /**
     * Update Placement
     */
    public function updatePlacementAction() {
        $this->_helper->layout->disableLayout();
        $placement_id = $this->_request->getParam('placement_id', '');
        $non_ccm_location = $this->_em->find('NonCcmLocations', $placement_id);
        $form = new Form_Placement();
        $form->placement_id->setValue($placement_id);
        $form->area->setValue($non_ccm_location->getArea()->getPkId());
        $form->row->setValue($non_ccm_location->getRow()->getPkId());
        $form->rack->setValue($non_ccm_location->getRack()->getPkId());
        $form->pallet->setValue($non_ccm_location->getPallet()->getPkId());
        $form->level->setValue($non_ccm_location->getLevel()->getPkId());
        $form->rack_information_id->setValue($non_ccm_location->getRackInformation()->getPkId());
        $this->view->form = $form;
    }

    /**
     * Update Placement 1
     */
    public function updatePlacement1Action() {
        $form = new Form_Placement();
        $non_ccm_location = new Model_NonCcmLocations();
        $non_ccm_location->form_values = $this->_request->getPost();
        $var = $non_ccm_location->updatePlacement();
        if ($var == 0) {
            $this->redirect("/stock/placement?error=1");
        } else {
            $this->redirect("/stock/placement?success=1");
        }
        $this->view->form = $form;
        $result = $non_ccm_location->getLocationsName();
        $this->view->result = $result;
    }

    /**
     * Placement
     */
    public function placementAction() {
        $form = new Form_Placement();
        $non_ccm_location = new Model_NonCcmLocations();
        if ($this->_request->getPost()) {
            $non_ccm_location->form_values = $this->_request->getPost();
            $var = $non_ccm_location->placement();
            if ($var == 0) {
                $this->redirect("/stock/placement?error=1");
            } else {
                $this->redirect("/stock/placement?success=1");
            }
        }
        $this->view->form = $form;
        $result = $non_ccm_location->getLocationsName();
        $this->view->result = $result;
    }

    /**
     * Delete Placement
     */
    public function deletePlacementAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $placements = array();

        $placement_id = $this->_request->getParam("placement_id");
        $nonccmloc = $this->_em->getRepository("PlacementLocations")->findOneBy(array("locationId" => $placement_id, "locationType" => Model_PlacementLocations::LOCATIONTYPE_NONCCM));
        if (count($nonccmloc) > 0) {
            $placements = $this->_em->getRepository("Placements")->findBy(array("placementLocation" => $nonccmloc->getPkId()));
        }

        if (count($placements) > 0) {
            echo "exists";
        } else {
            $this->_em->remove($nonccmloc);
            $user = $this->_em->getRepository("NonCcmLocations")->find($placement_id);
            $this->_em->remove($user);
            $this->_em->flush();
            echo "true";
        }
    }

    /**
     * Placement Area
     */
    public function placementAreaAction() {
        $form = new Form_PlacementArea();
        $non_ccm_location = new Model_NonCcmLocations();
        if ($this->_request->getPost()) {

            $non_ccm_location->form_values = $this->_request->getPost();
            $non_ccm_location->placement();

            $this->redirect("/stock/placement?success=1");
        }

        $this->view->form = $form;
        $result = $non_ccm_location->getLocationsName();
        $this->view->result = $result;
    }

    /**
     * Location Status 1
     */
    public function locationStatus1Action() {
        $form = new Form_LocationStatus();

        $non_ccm_location = new Model_NonCcmLocations();
        if ($this->_request->getPost()) {
            $non_ccm_location->form_values = $this->_request->getPost();
            $non_ccm_location->locationStatus();

            $this->redirect("/stock/location-status?success=1");
        }

        $this->view->form = $form;
        $result = $non_ccm_location->getLocationsName();
        $this->view->result = $result;
    }

    /**
     * Location Status
     */
    public function locationStatusAction() {
        $form = new Form_LocationStatus();
        $params = array();
        $result = null;
        $non_ccm_location = new Model_NonCcmLocations();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $area = $form->getValue('area');
                $level = $form->getValue('level');
                if (!empty($area)) {
                    $params['area'] = $area;
                }
                if (!empty($level)) {
                    $params['level'] = $level;

                    $levl = $this->_em->getRepository("ListDetail")->find($level);
                    $level_val = $levl->getListValue();
                    $this->view->level = $level_val;
                }

                $non_ccm_location->form_values = $params;
                $result = $non_ccm_location->locationStatus($area, $level);
                $this->view->max_value_shelf = $non_ccm_location->getMaxShelf($area, $level);
                $this->view->max_value_rack = $non_ccm_location->getMaxRack($area, $level);
                $this->view->max_value_pallet = $non_ccm_location->getMaxPallet();
            }
        } else {
            $level = $this->getParam('level');
            $area = $this->getParam('area');
            if (!empty($level) && !empty($area)) {

                $levl = $this->_em->getRepository("ListDetail")->find($level);
                $level_val = $levl->getListValue();
                $this->view->level = $level_val;
                $params['level'] = $level;
                $params['area'] = $area;
                $non_ccm_location->form_values = $params;
                $result = $non_ccm_location->locationStatus($area, $level);
                $this->view->getarea = $area;
                $this->view->getlevel = $level;
                $this->view->max_value_shelf = $non_ccm_location->getMaxShelf($area, $level);
                $this->view->max_value_rack = $non_ccm_location->getMaxRack($area, $level);
                $this->view->max_value_pallet = $non_ccm_location->getMaxPallet();

                $form->area->setValue($area);
                $form->level->setValue($level);
            }
        }

        $this->view->getarea = $area;
        $this->view->getlevel = $level;
        $this->view->form = $form;
        $this->view->result = $result;
    }

    /**
     * Location Status Vaccines
     */
    public function locationStatusVaccinesAction() {
        $placement_location = new Model_PlacementLocations();
        $result = $placement_location->locationStatusVaccines();
        $this->view->result = $result;
    }

    /**
     * Product Location
     */
    public function productLocationAction() {
        $form = new Form_ProductLocation();
        $form->addHidden();
        $form->readFields();
        $placement = new Model_Placements();
        $stock_detail = new Model_StockDetail();
        $id = $this->_request->getParam("itemid");
        $locid = $this->_request->getParam("locid");
        $stock_detail_id = $this->_em->getRepository("StockDetail")->find($id);
        $stock_batch_id = $stock_detail_id->getStockBatch()->getPkId();
        $stockbatch = $this->_em->find('StockBatch', $stock_batch_id);
        $batchnumer = $stockbatch->getPkId();
        $batchname = $stockbatch->getNumber();
        $item_pack_size = $stockbatch->getItemPackSize()->getPkId();
        $item_pack_size_id = $this->_em->find('ItemPackSizes', $item_pack_size);
        $item_p_id = $item_pack_size_id->getPkId();
        $itemname = $item_pack_size_id->getItemName();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $placement->form_values = $form->getValues();
            $placement->form_values['stock_detail_id'] = $id;
            $form->form_values['batchId'] = $batchnumer;
            $form->form_values['itemId'] = $item_p_id;

            $placement->addPlacement();
            $this->redirect("/stock/product-location?success=1&itemid=$id&locid=$locid");
        }

        $this->view->form = $form;
        $placement->form_values['id'] = $id;
        $placement->form_values['locid'] = $locid;
        $form->item_pack_size_id->setValue($itemname);
        $form->stock_batch_id->setValue($batchname);

        $stock_detail->form_values['batchId'] = $stockbatch->getPkId();
        $result = $stock_detail->getUnTotalQuantityByBatch($id);
        $form->unallocated_quantity->setValue($result['unallocated_qty']);
        $form->total_quantity->setValue($result['product_qty']);
        $rec = $this->_em->getRepository("PlacementLocations")->findBy(array("locationId" => $locid));
        $plc_id = $rec[0]->getPkId();
        $result = $placement->getProductPlacements($plc_id, $id);
        $form->placement_location_id->setValue($plc_id);
        $form->batchId->setValue($batchnumer);
        $form->itemId->setValue($item_p_id);

        $this->view->result = $result;
    }

    /**
     * ajaxGetBatchNumber
     */
    public function ajaxGetBatchNumberAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->item_pack_size_id) && !empty($this->_request->item_pack_size_id)) {

            $stock_batch = new Model_StockBatch();
            $stock_batch->form_values['item_pack_size_id'] = $this->_request->item_pack_size_id;

            $array = $stock_batch->getBatchNumberByProducts();

            foreach ($array as $key => $row) {
                $batch_id = $row['pkId'];

                $stock_detail = new Model_StockDetail();
                $stock_detail->form_values['stock_batch_id'] = $batch_id;
                $data = $stock_detail->getTotalQuantityByBatch();

                if (!empty($data) && $data['product_qty'] <= 0) {
                    unset($array[$key]);
                }
            }

            $this->view->stock_batch_id = $this->_request->stock_batch_id;
            $this->view->data = $array;
        }
    }

    /**
     * ajaxBatchNumber
     */
    public function ajaxBatchNumberAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->item_pack_size_id) && !empty($this->_request->item_pack_size_id)) {

            $stock_batch = new Model_StockBatch();
            $stock_batch->form_values['item_pack_size_id'] = $this->_request->item_pack_size_id;

            $array = $stock_batch->getBatchNumberByProducts();

            $this->view->data = $array;
        }
    }

    /**
     * ajaxVehicleNumber
     */
    public function ajaxVehicleNumberAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->vehicle_type_id) && !empty($this->_request->vehicle_type_id)) {

            $gatepass_vehicle = new Model_GatepassVehicles();
            $gatepass_vehicle->form_values['vehicle_type_id'] = $this->_request->vehicle_type_id;

            $array = $gatepass_vehicle->getVehicleNumberByType();

            $this->view->data = $array;
        }
    }

    /**
     * ajaxStockPlacement
     */
    public function ajaxStockPlacementAction() {
        $this->_helper->layout->setLayout('ajax');
        $id = $this->_request->getParam('id', '');
        $stock_batch = new Model_StockBatch();
        $form = new Form_StockPlacement();
        $form->addHidden();
        $stock_batch->form_values['stock_detail'] = $id;
        $data = $stock_batch->getStockBatchAndDetailById();

        $this->view->data = $data;
        $data['placed_quantity'] = 0;
        $this->view->data['placed_quantity'] = $data['placed_quantity'];
        $this->view->data['remaining_quantity'] = $data['quantity'] - $data['placed_quantity'];
        $this->view->form = $form;
    }

    /**
     * ajaxStockBinPlacement
     */
    public function ajaxStockBinPlacementAction() {
        $this->_helper->layout->setLayout('ajax');
        $id = $this->_request->getParam('id', '');
        $stock_batch = new Model_StockBatch();
        $form = new Form_StockPlacement();
        $form->addHidden();
        $stock_batch->form_values['stock_detail'] = $id;
        $data = $stock_batch->getStockBatchAndDetailById();

        $this->view->data = $data;
        $data['placed_quantity'] = 0;
        $this->view->data['placed_quantity'] = $data['placed_quantity'];
        $this->view->data['remaining_quantity'] = $data['quantity'] - $data['placed_quantity'];
        $this->view->form = $form;
    }

    /**
     * ajaxGetTotalQuantityByBatch
     */
    public function ajaxGetTotalQuantityByBatchAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $stock_detail = new Model_StockDetail();
            $stock_detail->form_values['stock_batch_id'] = $this->_request->id;
            $result = $stock_detail->getTotalQuantityByBatch();

            return $this->_helper->json($result);
        }
    }

    /**
     * ajaxCheckQuantity
     */
    public function ajaxCheckQuantityAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $quantity = $this->_request->getPost('quantity');
        $batchId = $this->_request->getPost('batchId');
        $item_id = $this->_request->item_id;

        if (!empty($quantity) && !empty($batchId)) {
            $stock_detail = new Model_StockDetail();
            $stock_detail->form_values['batchId'] = $batchId;
            $result = $stock_detail->getTotalQuantityByBatch($item_id);
            if ($quantity <= $result['unallocated_qty'] && $quantity > 0) {
                echo 'true';
            } else {
                echo "false";
            }
        } else {
            echo "false";
        }
    }

    /**
     * ajaxGetColdChainsWrtStorage
     */
    public function ajaxGetColdChainsWrtStorageAction() {
        $this->_helper->layout->disableLayout();
        $item_id = $this->_request->getParam('item_id', '');
        $this->_em->getRepository("ItemPackSizes")->find($item_id);
    }

    /**
     * ajaxGetCampaignsByProduct
     */
    public function ajaxGetCampaignsByProductAction() {
        $this->_helper->layout->disableLayout();
        $item_id = $this->_request->getParam('item_id', '');
        $warehouse = $this->_request->getParam('warehouse_id', '');
        if (empty($item_id)) {
            $this->view->data = false;
            return;
        }

        if (!empty($warehouse)) {
            $sub_sql = $this->_em->getConnection()->prepare("SELECT district_id from warehouses where pk_id=$warehouse ");

            $sub_sql->execute();
            $district = $sub_sql->fetchAll();
            $dist_id = $district[0]['district_id'];

            $qry = "and campaign_districts.district_id= $dist_id ";
        }
        $str_sql = $this->_em->getConnection()->prepare("SELECT DISTINCT
                campaigns.campaign_name,
                campaigns.pk_id
                FROM
                campaign_item_pack_sizes
                INNER JOIN campaigns ON campaign_item_pack_sizes.campaign_id = campaigns.pk_id
                INNER JOIN campaign_districts ON campaign_districts.campaign_id = campaigns.pk_id
                WHERE
                campaign_item_pack_sizes.item_pack_size_id = $item_id
                $qry ");


        $str_sql->execute();
        $campaigns = $str_sql->fetchAll();

        if (count($campaigns) > 0) {
            $this->view->data = $campaigns;
        } else {
            $this->view->data = false;
        }
    }

    /**
     * ajaxGetCampaignsByProductReceive
     */
    public function ajaxGetCampaignsByProductReceiveAction() {
        $this->_helper->layout->disableLayout();
        $item_id = $this->_request->getParam('item_id', '');

        $str_sql = $this->_em->getConnection()->prepare("SELECT DISTINCT
                campaigns.campaign_name,
                campaigns.pk_id
                FROM
                campaign_item_pack_sizes
                INNER JOIN campaigns ON campaign_item_pack_sizes.campaign_id = campaigns.pk_id
                INNER JOIN campaign_districts ON campaign_districts.campaign_id = campaigns.pk_id
                WHERE
                campaign_item_pack_sizes.item_pack_size_id = $item_id
                ");

        $str_sql->execute();
        $campaigns = $str_sql->fetchAll();

        if (count($campaigns) > 0) {
            $this->view->data = $campaigns;
        } else {
            $this->view->data = false;
        }
    }

    /**
     * ajaxGetManufacturerByProduct
     */
    public function ajaxGetManufacturerByProductAction() {
        $this->_helper->layout->disableLayout();
        $item_id = $this->_request->getParam('item_id', '');
        $stakeholder_items = new Model_Stakeholders();
        $stakeholder_items->form_values['item_id'] = $item_id;
        $associated = $stakeholder_items->getManufacturerByProduct();
        $this->view->associated = $associated;
        $associated_array = array();
        if (count($associated) > 0) {
            foreach ($associated as $row) {
                $associated_array[] = $row['stakeholderName'];
            }
        }
        //$not_associated = $stakeholder_items->getUnaccociatedManufacturer($associated_array);
        //$this->view->not_associated = $not_associated;
    }

    /**
     * ajaxGetManufacturerByProduct
     */
    public function ajaxGetQuantityByShipmentAction() {
        $this->_helper->layout->disableLayout();

        $shipmnets = new Model_Shipments();
        $shipmnets->form_values['master_id'] = $this->_request->getParam('shipment_id');

        $stock_list = $shipmnets->getShipmentDetail();
        $shipmnets->form_values['shipment_id'] = $this->_request->getParam('shipment_id');
        $temp_stock_list1 = $shipmnets->getTempShipmnetProcductBatchListQuantity();
        if ($temp_stock_list1) {
            $this->view->avl_qty = ABS($stock_list[0]['received_quantity'] - $temp_stock_list1[0]['quantity']);
        } else {
            $this->view->avl_qty = $stock_list[0]['received_quantity'];
        }

        //$not_associated = $stakeholder_items->getUnaccociatedManufacturer($associated_array);
        //$this->view->not_associated = $not_associated;
    }

    /**
     * Add New Manufacturer
     */
    public function addNewManufacturerAction() {
        $this->_helper->layout->disableLayout();

        $data = array();
        $data['name'] = $this->_request->getParam('name');
        $data['item_id'] = $this->_request->getParam('item_id');
        $data['quantity'] = $this->_request->getParam('quantity');
        $stakeholer = new Model_Stakeholders();
        $stakeholer->form_values = $data;
        $stakeholer->addStakeholder();

        $stakeholder_items = new Model_Stakeholders();
        $stakeholder_items->form_values['item_id'] = $data['item_id'];
        $this->view->associated = $stakeholder_items->getManufacturerByProduct();
    }

    /**
     * Stock In Bin
     */
    public function stockInBinAction() {
        $placement = new Model_Placements();
        $id = $this->_request->getParam('id', '');
        $area = $this->_request->getParam('area', '');
        $level = $this->_request->getParam('level', '');

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "product");
        $placement->form_values['id'] = $id;
        $bin_name = $placement->getStockBinName($id);
        $result = $placement->getStockInBin($order, $sort);

        $this->view->id = $id;
        $this->view->area = $area;
        $this->view->level = $level;

        $this->view->result = $result;
        $this->view->title = $bin_name[0]['location_name'];
        $this->view->bin_id = $id;
        $this->view->order = $order;
        $this->view->sort = $sort;
        $auth = App_Auth::getInstance();
        $this->view->role_id = $auth->getRoleId();
        $this->view->pagination_params = array("id" => $id, "area" => $area, "level" => $level);
    }

    /**
     * Stock In Bin Vaccines
     */
    public function stockInBinVaccinesAction() {

        $cold_chain = new Model_ColdChain();
        $placement = new Model_Placements();
        $id = $this->_request->getParam('id', '');
        $asset = $cold_chain->getAssetByLocation($id);

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "product");
        $placement->form_values['id'] = $id;
        $result = $placement->getStockInBinVaccines($order, $sort);
        $result_xml = $placement->getStockInBinVaccinesGraph();

//Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 25);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->id = $id;
        $this->view->result = $paginator;
        $this->view->bin_id = $id;
        $this->view->counter = $counter;
        $this->view->order = $order;
        $this->view->sort = $sort;
        $this->view->title = $asset;
        $this->view->pagination_params = array("id" => $id, "asset" => $asset);

        $auth = App_Auth::getInstance();
        $this->view->role_id = $auth->getRoleId();
        $this->view->xmlstore = $result_xml;
        $graphs = new Model_Graphs();
        $graphs->form_values['id'] = $id;
        $xmlstore1 = $graphs->ColdStoreItems(15);
        $this->view->xmlstore1 = $xmlstore1;
    }

    /**
     * ajaxBatchDetailBody
     */
    public function ajaxBatchDetailBodyAction() {
        $this->_helper->layout->disableLayout();
        $batch_id = $this->_request->getParam("id");
        $placment = new Model_Placements();
        $this->view->result = $placment->getBatchPlacmentDetail($batch_id);
    }

    /**
     * ajaxNonVaccineBatchDetailBody
     */
    public function ajaxNonVaccineBatchDetailBodyAction() {
        $this->_helper->layout->disableLayout();
        $batch_id = $this->_request->getParam("id");
        $placment = new Model_Placements();
        $this->view->result = $placment->getNonVaccineBatchPlacmentDetail($batch_id);
    }

    /**
     * Product Placement Detail
     */
    public function productPlacementDetailAction() {
        $form = new Form_ProductLedger();
        $this->view->form = $form;
        $form->detail_summary->setValue(2);
        $this->view->headTitle("Product Placement Detail");
    }

    /**
     * ajaxProductPlacementDetail
     */
    public function ajaxProductPlacementDetailAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getParams();

        $placment = new Model_Placements();
        if ($form_values['detail_summary'] == 1) {
            $this->view->result = $placment->getProductPlacmentSummary($form_values['product']);
        } else {
            $this->view->result = $placment->getProductPlacmentDetail($form_values['product']);
        }

        $product_obj = new Model_ItemPackSizes();
        $product_obj->form_values['pk_id'] = $form_values['product'];
        $product_name = $product_obj->getProductName();

        $this->view->detail_summary = $form_values['detail_summary'];
        $this->view->product = $form_values['product'];
        $this->view->product_name = $product_name;
    }

    /**
     * Transfer Stock
     */
    public function transferStockAction() {
        $this->_helper->layout->setLayout("ajax");
        $form = new Form_TransferStock();
        $form->addHidden();
        $form->readFields();
        $non_ccm_loc = new Model_NonCcmLocations();


        $placement_id = $this->_request->getParam('placement_id');
        $bin_id = $this->_request->getParam('bin_id');
        $quantity_per_pack = $this->_request->getParam('quantity_per_pack');
        $totqty = $this->_request->getParam('totqty');
        $area = $this->_request->getParam('area');
        $level = $this->_request->getParam('level');
        $placement = $this->_em->find("PlacementSummary", $placement_id);

        $batch_numer = $placement->getStockBatchWarehouse()->getStockBatch()->getNumber();
        $item_name = $placement->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemName();
        $non_ccm_id = $non_ccm_loc->getNonCcmLocationId($bin_id);
        $form->item_pack_size_id->setValue($item_name);
        $form->stock_batch_id->setValue($batch_numer);
        $form->id->setValue($placement_id);
        $form->totqty->setValue($totqty);
        $this->view->form = $form;
        $this->view->bin_id = $bin_id;
        $this->view->non_ccm_id = $non_ccm_id;
        $this->view->quantity_per_pack = $quantity_per_pack;
        $this->view->totqty = $totqty;
        $this->view->area = $area;
        $this->view->level = $level;
    }

    /**
     * Save Transfer Stock
     */
    public function saveTransferStockAction() {
        $this->_helper->layout->disableLayout();

        $form = new Form_TransferStock();
        $form->addHidden();
        $form->readFields();
        $placement = new Model_Placements();

        $id = $this->_request->getParam("bin_id");
        $quantity_per_pack = $this->_request->getPost("quantity_per_pack");

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $quantity_per_pack = $this->_request->getPost("quantity_per_pack");
            $area = $this->_request->getPost("area");
            $level = $this->_request->getPost("level");
            $placement->form_values = $form->getValues();
            $placement->form_values['quantity_per_pack'] = $quantity_per_pack;
            $placement->addTransferStock();
            $this->_redirect("/stock/stock-in-bin?success=1&id=$id&area=$area&level=$level");
        }
    }

    /**
     * Transfer Stock Vaccines
     */
    public function transferStockVaccinesAction() {
        $this->_helper->layout->setLayout("ajax");
        $form = new Form_TransferStockVaccines();
        $form->addHidden();
        $form->readFields();
        $loc_name = new Model_ColdChain();

        $placement_id = $this->_request->getParam('placement_id');

        $placement = $this->_em->find("PlacementSummary", $placement_id);
        if (is_null($placement)) {
            echo "<h4>Please refresh the page. Press Ctrl+R simultaneously. <h4>";
            exit;
        }
        $batch_numer = $placement->getStockBatchWarehouse()->getStockBatch()->getNumber();
        $item_name = $placement->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemName();
        $asset_name = $loc_name->getAssetByLocation($placement->getPlacementLocation()->getPkId());

        $form->item_pack_size_id->setValue($item_name);
        $form->stock_batch_id->setValue($batch_numer);
        $form->id->setValue($placement_id);
        $this->view->form = $form;
        $this->view->available_qty = $placement->getQuantity();
        $this->view->vvm = $placement->getVvmStage()->getPkId();
        $this->view->asset_name = $asset_name;
        $this->view->bin_id = $placement->getPlacementLocation()->getPkId();
        $this->view->bin_location_id = $placement->getPlacementLocation()->getLocationId();
    }

    /**
     * Save Transfer Stock Vaccines
     */
    public function saveTransferStockVaccinesAction() {
        $this->_helper->layout->disableLayout();

        $form = new Form_TransferStockVaccines();
        $form->addHidden();
        $form->readFields();
        $placement = new Model_Placements();

        $bin_id = $this->_request->getParam("bin_id");

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $placement->form_values = $form->getValues();
            $placement->addTransferStockVaccines();
            $this->_redirect("/stock/stock-in-bin-vaccines?success=1&id=$bin_id");
        }
    }

    /**
     * ajaxAddStockPlacement
     */
    public function ajaxAddStockPlacementAction() {
        $placement = new Model_Placements();

        $placement->form_values = $this->_request->getParams();

        $id = $placement->form_values['placement_loc_id'];
        $placement->addPlaceStock();
        $this->redirect("/stock/stock-bin-placement?id=$id");
    }

    /**
     * ajaxAddStockPlacementVaccines
     */
    public function ajaxAddStockPlacementVaccinesAction() {
        $placement = new Model_Placements();
        $placement->form_values = $this->_request->getParams();

        $id = $placement->form_values['placement_loc_id'];

        $placement->addPlaceStockVaccines();

        $placement_location = $placement->locationType($id);

        if ($placement_location == 99) {
            $this->redirect("/stock/stock-bin-placement-vaccines?id=$id");
        } else {
            $this->redirect("/stock/stock-bin-placement?id=$id");
        }
    }

    /**
     * Pick Stock
     */
    public function pickStockAction() {
        $form = new Form_PickStock();
        $this->view->form = $form;
    }

    /**
     * ajaxDetailDataIssueno
     */
    public function ajaxDetailDataIssuenoAction() {
        $this->_helper->layout->disableLayout();
        $form = new Form_PickStock();
        $this->view->form = $form;

        $stock_master_id = $this->_request->stock_master_id;
        $stock_master = new Model_StockMaster();
        $result = $stock_master->detailDataIssueno($stock_master_id);
        $this->view->data = $result;
    }

    /**
     * Stock Pick Data
     */
    public function stockPickDataAction() {
        $placement = new Model_Placements();
        $qry_string = base64_decode(str_replace("Zr2", "", $this->_request->getParam('qry')));
        $params = explode("|", $qry_string);

        $detail_id = $params[0];
        $batch_id = $params[1];
        $item_cat = $params[2];
        $uqty = $params[3];

        $wh_id = $this->_identity->getWarehouseId();
        if ($item_cat == Model_ItemCategories::NONVACCINES || $item_cat == Model_ItemCategories::DILUENT) {
            $result = $placement->stockPickDetail($detail_id, $batch_id, $wh_id, $item_cat);
        } if ($item_cat == Model_ItemCategories::VACCINES) {
            $result = $placement->stockPickDetailVaccines($detail_id, $batch_id, $wh_id, $item_cat);
        }

        $this->view->plc_loc_id = $result[0]["plc_loc_id"];
        $this->view->batch_id = $batch_id;
        $this->view->detail_id = $detail_id;
        $this->view->uqty = $uqty;
        $this->view->result = $result;
    }

    /**
     * Pick Stock Quantity
     */
    public function pickStockQuantityAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $placement = new Model_Placements();
        $placement->form_values = $this->_request->getParams();
        $result = $placement->addStockQuantity();
        $this->view->result = $result;
    }

    /**
     * ajaxGetProductsByPurpose
     */
    public function ajaxGetProductsByPurposeAction() {
        $this->_helper->layout->disableLayout();
        $sips = new Model_StakeholderItemPackSizes();
        $params = $this->_request->getParam('params', '');
        $purpose = $this->_request->getParam('purpose', '');

        list($batch_id) = explode("_", $params);

        $stock_batch = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
        $item_id = $stock_batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItem()->getPkId();

        $sips->form_values = array(
            'item_id' => $item_id,
            'purpose' => $purpose
        );

        $item_category_id = $stock_batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId();

        $items = $sips->getProductByItemPurpose($item_category_id);

        $this->view->items = $items;
    }

    /**
     * ajaxGetProductsByStakeholderActivity
     */
    public function ajaxGetProductsByStakeholderActivityAction() {
        $this->_helper->layout->disableLayout();
        $sips = new Model_StakeholderItemPackSizes();
        $type = $this->_request->getParam('type', '');
        $sips->form_values = array(
            'stakeholder_id' => $this->_request->getParam('activity_id', ''),
            'trans_date' => $this->_request->getParam('tran_date', date("d/m/Y h:i:s A"))
        );

        if (!empty($type) && $type == 2) {
            $result = $sips->getAllIssueProductsByStakeholder();
        } else {
            $result = $sips->getAllProductsByStakeholderType();
        }

        $this->view->result = $result;
    }

    /**
     * ajaxGetProductsByWhTransactions
     */
    public function ajaxGetProductsByWhTransactionsAction() {
        $this->_helper->layout->disableLayout();
        $ips = new Model_ItemPackSizes();
        $ips->form_values['wh_id'] = $this->_request->getParam('wh_id', '');
        $this->view->result = $ips->getProductsByWhTransactions();
    }

    /**
     * ajaxGetAllStakeholderActivities
     */
    public function ajaxGetAllStakeholderActivitiesAction() {
        $this->_helper->layout->disableLayout();
        $sips = new Model_StakeholderActivities();
        $sips->form_values['warehouse'] = $this->_request->getParam('warehouse', '');
        $result = $sips->getAllStakeholderActivitiesIssues();
        $this->view->result = $result;
    }

    /**
     * ajaxGetStakeholderActivities
     */
    public function ajaxGetStakeholderActivitiesAction() {
        $this->_helper->layout->disableLayout();
        $sips = new Model_StakeholderActivities();
        $sips->form_values['warehouse'] = $this->_request->getParam('warehouse', '');
        $result = $sips->getAllStakeholderActivities();
        $this->view->result = $result;
    }

    /**
     * printMonthlyConsumption
     */
    public function printMonthlyConsumptionAction() {
        $this->_helper->layout->setLayout('print-consumption');
        $form = new Form_MonthlyConsumption();

        $warehouse = new Model_Warehouses();
        $warehouses = $warehouse->getWarehouseNames();
        $this->view->warehouses = $warehouses;

        if (isset($this->_request->do) && !empty($this->_request->do)) {

            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->monthlyConsumtionTemp();

            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $items = $item_pack_sizes->monthlyConsumtion();

            $warehouse_data->form_values = array('warehouse_id' => $arr_temp['wh_id']);
            $warehouse_data->form_values['level'] = 6;
            $result = $warehouse_data->getMonthYearByWarehouseId();

            if ($result) {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
                foreach ($result as $row) {
                    $loc_id = $row['location_id'];
                    $do = 'Z' . base64_encode($arr_temp['wh_id'] . '|' . $row['report_year'] . '-' . str_pad($row['report_month'], 2, "0", STR_PAD_LEFT) . '-01' . '|2');
                    $arr_combo[] = array(
                        "key" => $do,
                        "value" => $row['report_month'] . '-' . $row['report_year']
                    );
                }
            }

            $warehouse_id = $arr_temp['wh_id'];
            $wh = $this->_em->getRepository("Warehouses")->find($warehouse_id);
            $warehouse_name = $wh->getWarehouseName();

            $form->monthly_report->setMultiOptions($arr_combo);
            $form->monthly_report->setValue($temp);

            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];
            $this->view->warehouse_name = $warehouse_name;
            $this->view->locid = $loc_id;
            $this->view->items = $items;

            if (!empty($warehouse_id)) {
                $uc = $wh->getLocation()->getPkId();
                $district = $wh->getDistrict()->getPkId();
                $province = $wh->getProvince()->getPkId();
                $uc_tbl = $this->_em->getRepository("Locations")->find($uc);
                $uc_name = $uc_tbl->getLocationName();
                $this->view->uc_name = $uc_name;
                $tehsil = $uc_tbl->getParent()->getPkId();
                $tehsil_tbl = $this->_em->getRepository("Locations")->find($tehsil);
                $tehsil_name = $tehsil_tbl->getLocationName();
                $this->view->tehsil_name = $tehsil_name;
                $district_tbl = $this->_em->getRepository("Locations")->find($district);
                $district_name = $district_tbl->getLocationName();
                $this->view->district_name = $district_name;
                $province_tbl = $this->_em->getRepository("Locations")->find($province);
                $provinc_name = $province_tbl->getLocationName();
                $this->view->provinc_name = $provinc_name;
            }
        }
        $this->view->form = $form;
        $this->view->do = $this->_request->do;
        $this->view->username = $this->_identity->getUserName();
        $this->view->print_title = "Monthly Consumption Reporting Form (EPI Center)";
    }

    /**
     * printMonthlyConsumption2
     */
    public function printMonthlyConsumption2Action() {
        $this->_helper->layout->setLayout('print-consumption2');

        $warehouse = new Model_Warehouses();
        $warehouses = $warehouse->getWarehouseNames();
        $this->view->warehouses = $warehouses;

        if (isset($this->_request->do) && !empty($this->_request->do)) {

            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->monthlyConsumtionTemp();

            $this->view->month = $arr_temp['month'];
            $this->view->mm = $arr_temp['mm'];
            $this->view->year = $arr_temp['yy'];

            $this->view->is_new_report = $arr_temp['is_new_rpt'];
            $this->view->prev_month_date = $arr_temp['prev_month_date'];
            $this->view->check_date = $arr_temp['check_date'];
            $this->view->first_month = Zend_Registry::get('first_month');

            $item_pack_sizes = new Model_ItemPackSizes();
            $item_pack_sizes->form_values = array(
                'month' => $arr_temp['mm'],
                'year' => $arr_temp['yy'],
                'wh_id' => $arr_temp['wh_id']
            );

            $items = $item_pack_sizes->monthlyConsumtion2();
            $items_non_vaccines = $item_pack_sizes->monthlyConsumtion2_non_vaccinces();
            $items_tt = $item_pack_sizes->monthlyConsumtion2_tt();

            $warehouse_data->form_values = array('warehouse_id' => $arr_temp['wh_id']);
            $result = $warehouse_data->getMonthYearByWarehouseId();

            if ($result) {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
                foreach ($result as $row) {
                    $loc_id = $row['location_id'];
                    $do = 'Z' . base64_encode($arr_temp['wh_id'] . '|' . $row['report_year'] . '-' . str_pad($row['report_month'], 2, "0", STR_PAD_LEFT) . '-01' . '|2');
                    $arr_combo[] = array(
                        "key" => $do,
                        "value" => $row['report_month'] . '-' . $row['report_year']
                    );
                }
            }

            $warehouse_id = $arr_temp['wh_id'];

            $wh = $this->_em->getRepository("Warehouses")->find($warehouse_id);
            $warehouse_name = $wh->getWarehouseName();

            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];
            $this->view->warehouse_name = $warehouse_name;
            $this->view->locid = $loc_id;
            $this->view->items = $items;

            $this->view->items_non_vaccinces = $items_non_vaccines;
            $this->view->items_tt = $items_tt;

            if (!empty($warehouse_id)) {
                $uc = $wh->getLocation()->getPkId();
                $district = $wh->getDistrict()->getPkId();
                $province = $wh->getProvince()->getPkId();
                $uc_tbl = $this->_em->getRepository("Locations")->find($uc);
                $uc_name = $uc_tbl->getLocationName();
                $this->view->uc_name = $uc_name;
                $tehsil = $uc_tbl->getParent()->getPkId();
                $tehsil_tbl = $this->_em->getRepository("Locations")->find($tehsil);
                $tehsil_name = $tehsil_tbl->getLocationName();
                $this->view->tehsil_name = $tehsil_name;
                $district_tbl = $this->_em->getRepository("Locations")->find($district);
                $district_name = $district_tbl->getLocationName();
                $this->view->district_name = $district_name;
                $province_tbl = $this->_em->getRepository("Locations")->find($province);
                $provinc_name = $province_tbl->getLocationName();
                $this->view->provinc_name = $provinc_name;
            }
        }
        $this->view->province_id = $this->_identity->getProvinceId();
        $this->view->do = $this->_request->do;
        $this->view->username = $this->_identity->getUserName();
        $this->view->print_title = "Monthly Consumption Reporting Form (EPI Center)";
    }

    /**
     * ajaxGetPreMonthCb
     */
    public function ajaxGetPreMonthCbAction() {
        $this->_helper->layout->disableLayout();
        $this->view->data = $this->_request->getParams();
    }

    /**
     * ajaxGetPreMonthCb2
     */
    public function ajaxGetPreMonthCb2Action() {
        $this->_helper->layout->disableLayout();
        $this->view->data = $this->_request->getParams();
    }

    /**
     * ajaxGetPreMonthReceive
     */
    public function ajaxGetPreMonthReceiveAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $params = $this->_request->getParams();

        $obj_item = new Model_ItemPackSizes();
        $items = $obj_item->getAllItems();

        $wh_data = new Model_HfDataMaster();
        $wh_data->form_values = array(
            'reporting_start_date' => $params['year'] . "-" . $params['month'] . "-01",
            'warehouse_id' => $params['wh_id']
        );
        $data_rs = $wh_data->isReportExists();

        $result = array();
        if (!$data_rs) {
            foreach ($items as $item) {
                $ips = $this->_em->getRepository("ItemPackSizes")->find($item['pkId']);
                $str_sql = "SELECT REPgetTransWHData(4,'" . $params['month'] . "','" . $params['year'] . "','" . $item['pkId'] . "','" . $params['wh_id'] . "') from DUAL";
                $row = $this->_em->getConnection()->executeQuery($str_sql);
                $rs = $row->fetch();
                $result[$item['pkId']] = ($rs[0] == NULL) ? 0 : $rs[0] * $ips->getNumberOfDoses();
            }
        }

        echo json_encode($result);
    }

    /**
     * pipelineConsignments
     */
    public function pipelineConsignmentsAction() {
        $start = 0;
        $end = $this->_request->getParam('counter', 10);

        $form = new Form_PipelineConsignments();
        $form->addRows($start, $end);

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            if ($this->_request->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $pipeline_consignments = new Model_PipelineConsignments();
                    $pipeline_consignments->form_values = $form->getValues();
                    $pipeline_consignments->form_values['counter'] = $end;
                    $result = $pipeline_consignments->addPipelineConsignments();
                    if ($result) {
                        $this->view->success = "Pipeline Consignments has been received successfully.";
                        $form->reset();
                    } else {
                        $this->view->error = "Please enter atleast one row data.";
                    }
                }
            } else {
                $draft = new Model_PipelineConsignments();
                $saved_draft = $draft->getPipelineConsignmentsDraft();
                if ($saved_draft) {
                    $sum = count($saved_draft);
                    $end = $sum + 10;
                    $form->addRows($start, $end);

                    $form->from_warehouse_id->setValue($saved_draft[0]->getFromWarehouse()->getPkId());
                    $form->expected_arrival_date->setValue($saved_draft[0]->getExpectedArrivalDate()->format("d/m/Y"));
                    $form->reference_number->setValue($saved_draft[0]->getReferenceNumber());
                    $form->stakeholder_activity_id->setValue($saved_draft[0]->getStakeholderActivity()->getPkId());
                    $form->description->setValue($saved_draft[0]->getDescription());

                    for ($i = 0; $i < $sum; $i++) {
                        $rows = "rows" . $i;
                        $form->$rows->item_pack_size_id->setValue($saved_draft[$i]->getItemPackSize()->getPkId());
                        $form->populateManufacturer($saved_draft[$i]->getItemPackSize()->getPkId(), $rows);
                        $form->$rows->manufacturer_id->setValue($saved_draft[$i]->getManufacturer()->getPkId());
                        $form->$rows->vvm_type_id->setValue($saved_draft[$i]->getVvmType()->getPkId());
                        $form->$rows->batch_number->setValue($saved_draft[$i]->getBatchNumber());
                        $form->$rows->production_date->setValue($saved_draft[$i]->getProductionDate()->format("d/m/Y"));
                        $form->$rows->expiry_date->setValue($saved_draft[$i]->getExpiryDate()->format("d/m/Y"));
                        $form->$rows->unit_price->setValue($saved_draft[$i]->getUnitPrice());
                        $form->$rows->quantity->setValue($saved_draft[$i]->getQuantity());
                    }
                }
            }
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }

        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * autoAdjustment
     */
    public function autoAdjustmentAction() {
        $start = 0;
        $end = 10;
        $batch_form = new Form_AddBatch();
        $form = new Form_AutoAdjustment();
        $form->addRows($start, $end);
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $data = $form->getValues();
                $stock_master = new Model_StockMaster();
                $stock_master->form_values = $data;
                $result = $stock_master->addAdjustment();
                $this->view->status = $result;
                $form->reset();
                $form->adjustment_date->setValue(date("d/m/Y"));

                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e->getMessage());
                $this->view->status = false;
                switch ($e->getMessage()) {
                    case 'NEGATIVE_OR_ZERO_QTY':
                        $this->view->msg = 'Quantity should not be less than zero for negative adjustments!';
                        break;
                    case 'ADJ_QTY_GREATER_BATCH_QTY':
                        $this->view->msg = 'Adjustment Quantity should not be greater than batch Quantity!';
                        break;
                    case 'PLCD_QTY_LESS_EQUAL_ZERO':
                        $this->view->msg = 'Placed quantity should not be less than or equal to zero!';
                        break;
                    case 'PICK_ERROR':
                        $this->view->msg = 'Adjustment Quantity should not be greater than placed quantity.';
                        break;
                    case 'ADJ_QTY_LESS_EQUAL_PLCD_QTY':
                        $this->view->msg = 'Adjustment quantity should be less than or equal to placed quantity!';
                        break;
                    default :
                        break;
                }

                $form->product->setValue("");
                $form->quantity->setValue("");
            }
        }
        $this->view->province = $this->_identity->getProvinceId();
        $this->view->userid = $this->_userid;
        $this->view->role = $this->_identity->getRoleId();
        $this->view->form = $form;
        $this->view->batch_form = $batch_form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * plannedIssue
     */
    public function plannedIssueAction() {
        $start = 0;
        $end = $this->_request->getParam('counter', 10);

        $form = new Form_PlannedIssue();
        $form->addRows($start, $end);

        if ($this->_request->isPost()) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                if ($form->isValid($this->_request->getPost())) {
                    $pipeline_consignments = new Model_PipelineConsignments();
                    $pipeline_consignments->form_values = $form->getValues();
                    $pipeline_consignments->form_values['warehouse'] = $this->_request->warehouse;
                    $pipeline_consignments->form_values['counter'] = $end;
                    $pipeline_consignments->form_values['voucher'] = $this->_request->voucher;
                    $pipeline_consignments->form_values['trans_id'] = $this->_request->trans_id;
                    $result = $pipeline_consignments->addPlannedIssue();
                    if ($result) {
                        $this->view->success = "Planned stock has been issued successfully. Your temprary voucher number is " . $result;
                        $form->reset();
                    } else {
                        $this->view->error = "Please enter atleast one row data.";
                    }
                }
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
            }
        }

        $params = array(
            "office" => $this->_request->getParam('office', 0),
            "province" => $this->_request->getParam('combo1', $this->_identity->getProvinceId()),
            "district" => $this->_request->getParam('combo2', $this->_identity->getDistrictId()),
            "warehouse" => $this->_request->getParam('warehouse', 0)
        );

        $this->view->params = $params;
        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;

        $voucher = $this->_request->getParam('voucher', '');
        if (!empty($voucher)) {
            $planned_issue = $this->_em->getRepository("PipelineConsignments")->findOneBy(array("voucherNumber" => $voucher));
            if (count($planned_issue) > 0) {
                $this->view->voucherdetail = $planned_issue;
            }
        }

        $base_url = Zend_Registry::get('baseurl');
        switch ($this->_user_level) {
            case 1:
            case 2:
            case 3:
                $this->view->menu_type = 1;
                $this->view->headScript()->appendFile($base_url . '/js/all_level_combos.js');
                break;
            case 4:
            case 5:
            case 6:
                $this->view->menu_type = 2;
                $this->view->headScript()->appendFile($base_url . '/js/level_combos.js');
                break;
            default:
                break;
        }
    }

    /**
     * ajaxAddMoreIssueRows
     */
    public function ajaxAddMoreIssueRowsAction() {
        $this->_helper->layout->disableLayout();

        $start = $this->_request->getParam('start');
        $end = $this->_request->getParam('end');

        $form = new Form_PlannedIssue();
        $form->addRows($start, $end);

        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * ajaxPipelineConsignmentsDraft
     */
    public function ajaxPipelineConsignmentsDraftAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $start = 0;
        $end = $this->_request->getParam('counter', 10);

        $form = new Form_PipelineConsignments();
        $form->addRows($start, $end);

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $pipeline_consignments = new Model_PipelineConsignments();
            $pipeline_consignments->form_values = $form->getValues();
            $pipeline_consignments->form_values['counter'] = $end;
            $result = $pipeline_consignments->addPipelineConsignmentsDraft();
            if ($result) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /**
     * ajaxAddMoreRows
     */
    public function ajaxAddMoreRowsAction() {
        $this->_helper->layout->disableLayout();

        $start = $this->_request->getParam('start');
        $end = $this->_request->getParam('end');

        $form = new Form_PipelineConsignments();
        $form->addRows($start, $end);

        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * ajaxAddPipelineConsignments
     */
    public function ajaxAddPipelineConsignmentsAction() {
        $this->_helper->layout->disableLayout();

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $id = $this->_request->getParam('', '');
            $fa = $this->_em->getRepository("PipelineConsignments")->find($id);
            $fa->setPageName($form->page_name->getValue());
            $fa->setDescription($form->description->getValue());
            $fa->setStatus($form->status->getValue());
            $created_by = $this->_em->find('Users', $this->_user_id);
            $fa->setCreatedBy($created_by);
            $fa->setCreatedDate(App_Tools_Time::now());
            $fa->setModifiedBy($created_by);
            $fa->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($fa);
            $this->_em->flush();
        }
    }

    /**
     * ajaxUpdatePipelineConsignments
     */
    public function ajaxUpdatePipelineConsignmentsAction() {
        $this->_helper->layout->disableLayout();
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $id = $this->_request->getParam('', '');
            $fa = $this->_em->getRepository("PipelineConsignments")->find($id);
            $fa->setPageName($form->page_name->getValue());
            $fa->setDescription($form->description->getValue());
            $fa->setStatus($form->status->getValue());
            $created_by = $this->_em->find('Users', $this->_user_id);
            $fa->setModifiedBy($created_by);
            $fa->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($fa);
            $this->_em->flush();
        }
    }

    /**
     * searchPipelineConsignments
     */
    public function searchPipelineConsignmentsAction() {
        $form = new Form_PipelineConsignmentsFilters();
        $pipeline_consignments = new Model_PipelineConsignments();
        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $pipeline_consignments->form_values = $res;
            $form->from_warehouse_id->setValue($res['from_warehouse_id']);
            $form->from_date->setValue($res['from_date']);
            $form->to_date->setValue($res['to_date']);
            $form->item_pack_size_id->setValue($res['item_pack_size_id']);
            $form->status->setValue($res['status']);
        }
        $arr_data = $pipeline_consignments->getDistinctByVoucherNumber();
        $this->view->arr_data = $arr_data;
        $this->view->form = $form;
        $data = $this->_request->getParam("voucher", "");
        if (!empty($data)) {
            $string = substr($data, 1);
            list($voucher, $master_id) = explode("|", base64_decode($string));
            $this->view->voucher = $voucher;
            $this->view->master_id = $master_id;
        }
    }

    /**
     * searchPlannedIssue
     */
    public function searchPlannedIssueAction() {
        $form = new Form_PlannedIssueFilters();
        $pipeline_consignments = new Model_PipelineConsignments();
        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $pipeline_consignments->form_values = $res;
            $form->to_warehouse_id->setValue($res['to_warehouse_id']);
            $form->from_date->setValue($res['from_date']);
            $form->to_date->setValue($res['to_date']);
            $form->item_pack_size_id->setValue($res['item_pack_size_id']);
            $form->status->setValue($res['status']);
        }
        $arr_data = $pipeline_consignments->getDistinctIssueByVoucherNumber();
        $this->view->arr_data = $arr_data;
        $this->view->form = $form;
        $data = $this->_request->getParam("voucher", "");
        if (!empty($data)) {
            $string = substr($data, 1);
            list($voucher, $master_id) = explode("|", base64_decode($string));
            $this->view->voucher = $voucher;
            $this->view->master_id = $master_id;
        }
    }

    /**
     *
     */
    public function uploadPipelineConsignmentsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $type = $this->_request->getPost('type');

        if ($this->_request->isPost()) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            $pipelines = $this->_request->getPost('pipeline');
            $receivedqty = $this->_request->getPost('receivedqty');
            $vvmstage = $this->_request->getPost('vvmstage');

            if (count($pipelines) > 0) {
                $i = 0;
                foreach ($pipelines as $row) {

                    if (ABS($receivedqty[$i]) <= 0) {
                        continue;
                    }

                    $str_sql = $this->_em->createQueryBuilder()
                            ->select('pc')
                            ->from("PipelineConsignments", "pc")
                            ->where("pc.pkId = $row")
                            ->andWhere("pc.status != 'Received'");
                    $pc = $str_sql->getQuery()->getResult();
                    $pipeline_consignments = $pc[0];

                    if (count($pc) > 0) {

                        $type = $pipeline_consignments->getTransactionType()->getPkId();

                        $params = array(
                            'rec_id' => $pipeline_consignments->getPkId(),
                            'wh_id' => $pipeline_consignments->getToWarehouse()->getPkId(),
                            'from_wh_id' => $pipeline_consignments->getFromWarehouse()->getPkId(),
                            'voucher' => $pipeline_consignments->getVoucherNumber(),
                            'batch_no' => $pipeline_consignments->getBatchNumber(),
                            'qty' => $receivedqty[$i],
                            'trans_type' => $type,
                            'vvmstage' => $vvmstage[$i]
                        );
                        $p_consignments = new Model_PipelineConsignments();
                        $p_consignments->form_values = $params;
                        $voucher_number = $p_consignments->uploadPipelineConsignments();

                        $pipeline_consignments->setReceivedQuantity($receivedqty[$i]);
                        $pipeline_consignments->setStatus("Received");
                        $pipeline_consignments->setCreatedBy($pipeline_consignments->getCreatedBy());
                        $pipeline_consignments->setCreatedDate(App_Tools_Time::now());
                        $pipeline_consignments->setModifiedBy($pipeline_consignments->getCreatedBy());
                        $pipeline_consignments->setModifiedDate(App_Tools_Time::now());
                        // $this->_em->persist($p_consignments);
                        $this->_em->persist($pipeline_consignments);
                        $this->_em->flush();
                    }
                    $i++;
                }
            }
            $em->getConnection()->commit();
        }

        if ($type == Model_TransactionTypes::TRANSACTION_RECIEVE) {
            $this->_redirect("stock/search-pipeline-consignments/voucher/$voucher_number");
        }
        if ($type == Model_TransactionTypes::TRANSACTION_ISSUE) {
            $this->_redirect("stock/search-planned-issue/voucher/$voucher_number");
        }
    }

    /**
     * uploadPipelineConsignments
     */
    public function ajaxPipelineConsignmentDetailsAction() {
        $this->_helper->layout->disableLayout();
        $id = $this->_request->getParam('id', '');

        $pipeline_consignments = new Model_PipelineConsignments();
        if (!empty($id)) {
            $pipeline_consignments->form_values['voucher_number'] = $id;
            $arr_data = $pipeline_consignments->getBySearch();
            $this->view->voucher_number = $id;
            $this->view->arr_data = $arr_data;
        }
    }

    /**
     * ajaxPlannedIssueDetails
     */
    public function ajaxPlannedIssueDetailsAction() {
        $this->_helper->layout->disableLayout();
        $id = $this->_request->getParam('id', '');

        $pipeline_consignments = new Model_PipelineConsignments();
        if (!empty($id)) {
            $pipeline_consignments->form_values = array(
                'voucher_number' => $id,
                'source' => $this->_identity->getWarehouseId(),
                'type' => 2
            );
            $arr_data = $pipeline_consignments->getBySearch();
            $this->view->voucher_number = $id;
            $this->view->arr_data = $arr_data;
        }
    }

    /**
     * ajaxDeletePipelineConsignment
     */
    public function ajaxDeletePipelineConsignmentAction() {
        $id = $this->_request->getParam('id', '');
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if (!empty($id)) {
            $id = $this->_em->find('PipelineConsignments', $id);
            $this->_em->remove($id);
            $this->_em->flush();
        }
    }

    /**
     * Product Ledger
     */
    public function productLedgerAction() {
        $form = new Form_ProductLedger();
        $this->view->form = $form;
        if ($this->_identity->getRoleId() != 34 || $this->_identity->getRoleId() != 35 || $this->_identity->getRoleId() != 36) {
            $base_url = Zend_Registry::get('baseurl');
            $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        }
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * ajaxProductLedger
     */
    public function ajaxProductLedgerAction() {
        $this->_helper->layout->disableLayout();
        $query = array();
        parse_str($this->_request->getParam('data'), $query);
        $from_date = $query['from_date'];
        $to_date = $query['to_date'];

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $query;
        $result = $stock_master->getProductLedger();
        $ob = $stock_master->getProductOB();
        $stock_master->form_values['type'] = 'OB';
        $batch_ob = $stock_master->getBatchOB();
        $stock_master->form_values['from_date'] = $query['to_date'];
        $stock_master->form_values['type'] = 'CB';
        $batch_cb = $stock_master->getBatchOB();

        $this->view->result = $result;
        $this->view->ob = $ob;
        $this->view->cb_batch = $batch_cb;
        $this->view->ob_batch = $batch_ob;
        $this->view->from_date = $from_date;
        $this->view->to_date = $to_date;
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * Batch Summary Comparison
     */
    public function batchSummaryComparisonAction() {
        $form = new Form_ProductLedger();

        $this->view->form = $form;
    }

    /**
     * ajaxBatchSummaryComparison
     */
    public function ajaxBatchSummaryComparisonAction() {
        $this->_helper->layout->disableLayout();
        $product_id = $this->_request->getParam('product_id');
        $from_date = '11/03/2015';
        $to_date = date("d/m/Y");

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = array(
            'from_date' => $from_date,
            'to_date' => $to_date,
            'product' => $product_id
        );
        $result = $stock_master->getProductLedger();
        $quantity = $stock_master->getProductOBBeforeAdjust();

        $this->view->result = $result;
        $this->view->ob = $quantity['vials'];
        $this->view->doses = $quantity['doses'];
        $this->view->from_date = $from_date;
        $this->view->to_date = $to_date;
    }

    /**
     * Merge Batch
     */
    public function mergeBatchAction() {

        $stock_batch_model = new Model_StockBatch();
        $result = array();

        if ($this->_request->isPost()) {
            $em = $this->_em;
            //$em->getConnection()->beginTransaction();
            //try {
            $created_by = $em->find('Users', $this->_userid);
            $data = $this->_request->getPost("check_batches");
            $count = count($data);
            $merge_into_batch = $this->_request->getPost("mergeinto");

            for ($i = 0; $i < $count; $i++) {
                $stock_batch[] = $em->getRepository("StockBatchWarehouses")->find($data[$i]);
            }

            $stock_batch_merge = $em->getRepository("StockBatchWarehouses")->find($merge_into_batch);

            $quantity = 0;
            for ($i = 0; $i < $count; $i++) {
                $quantity = $quantity + $stock_batch[$i]->getQuantity();
                $batch_id = $stock_batch[$i]->getPkId();

                $placement_sum = $em->getRepository("PlacementSummary")->findBy(array("stockBatchWarehouse" => $batch_id));
                if (count($placement_sum) > 0) {
                    foreach ($placement_sum as $plc_sum) {
                        $plc_sum->setStockBatchWarehouse($stock_batch_merge);
                        $plc_sum->setModifiedBy($created_by);
                        $plc_sum->setModifiedDate(App_Tools_Time::now());
                        $em->persist($plc_sum);
                    }
                    $em->flush();
                }

                $stock_detail = $em->getRepository("StockDetail")->findBy(array("stockBatchWarehouse" => $batch_id));
                if (count($stock_detail) > 0) {
                    foreach ($stock_detail as $sd) {
                        $sd->setStockBatchWarehouse($stock_batch_merge);
                        $sd->setModifiedBy($created_by);
                        $sd->setModifiedDate(App_Tools_Time::now());
                        $em->persist($sd);
                    }
                    $em->flush();
                }

                $placements = $em->getRepository("Placements")->findBy(array("stockBatchWarehouse" => $batch_id));
                if (count($placements) > 0) {
                    foreach ($placements as $plc) {
                        $plc->setStockBatchWarehouse($stock_batch_merge);
                        $plc->setModifiedBy($created_by);
                        $plc->setModifiedDate(App_Tools_Time::now());
                        $em->persist($plc);
                    }
                    $em->flush();
                }

                $pth_from = $em->getRepository("PurposeTransferHistory")->findBy(array("fromStockBatchWarehouse" => $batch_id));
                if (count($pth_from) > 0) {
                    foreach ($pth_from as $pth_from_row) {
                        $pth_from_row->setFromStockBatchWarehouse($stock_batch_merge);
                        $pth_from_row->setModifiedBy($created_by);
                        $pth_from_row->setModifiedDate(App_Tools_Time::now());
                        $em->persist($pth_from_row);
                    }
                    $em->flush();
                }

                $pth_to = $em->getRepository("PurposeTransferHistory")->findBy(array("toStockBatchWarehouse" => $batch_id));
                if (count($pth_to) > 0) {
                    foreach ($pth_to as $pth_to_row) {
                        $pth_to_row->setToStockBatchWarehouse($stock_batch_merge);
                        $pth_to_row->setModifiedBy($created_by);
                        $pth_to_row->setModifiedDate(App_Tools_Time::now());
                        $em->persist($pth_to_row);
                    }
                    $em->flush();
                }

                if ($merge_into_batch != $batch_id) {
                    $em->remove($stock_batch[$i]);
                }
            }

            //$stock_batch_merge->setQuantity($quantity);
            $stock_batch_merge->setModifiedBy($created_by);
            $stock_batch_merge->setModifiedDate(App_Tools_Time::now());
            $em->persist($stock_batch_merge);
            $em->flush();

            //$stock_master = new Model_StockMaster();
            //$stock_master->adjustQuantityByWarehouse($merge_into_batch, $stock_batch_merge->getWarehouse()->getPkId());
            //  $em->getConnection()->commit();
            //} catch (Exception $e) {
            // $em->getConnection()->rollback();
            // $em->close();
            //  App_FileLogger::info($e->getMessage());
            //}
        }

        $result = $stock_batch_model->getBatchesByItem();
        $this->view->result = $result;
    }

    /**
     * ajaxMergeBatch
     */
    public function ajaxMergeBatchAction() {

        $this->_helper->layout->disableLayout();

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values = array(
            'item_id' => $this->_request->getParam('product'),
            'wh_id' => $this->_request->getParam('warehouse')
        );
        $result = $stock_batch->getBatchesByItem();

        $this->view->result = $result;
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * ajaxUpdateBatchMaster
     */
    public function ajaxUpdateBatchMasterAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $data = explode("|", $this->_request->id);
        $stock_batch = $this->_em->getRepository("StockBatch")->find($data[0]);
        $stock_batch->setBatchMasterId($data[1]);
        $created_by = $this->_em->find('Users', $this->_user_id);
        $stock_batch->setModifiedBy($created_by);
        $stock_batch->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock_batch);
        $this->_em->flush();
        echo $data[1];
    }

    /**
     * ajaxValidateMergeBatches
     */
    public function ajaxValidateMergeBatchesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $data = explode("|", substr($this->_request->data, 0, -1));
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            $stock_batch[] = $this->_em->getRepository("StockBatch")->find($data[$i]);
        }

        $counter = 0;
        $percent = 0;
        $batch_number[0] = $stock_batch[0]->getNumber();
        for ($i = 1; $i < $count; $i++) {
            $batch_number[$i] = $stock_batch[$i]->getNumber();
            similar_text($batch_number[$i - 1], $batch_number[$i], $percent);
            if ($percent < 90) {
                $counter += 1;
            }
        }

        echo $counter;
    }

    /**
     * ajaxMergeBatchQuantity
     */
    public function ajaxMergeBatchQuantityAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            $created_by = $this->_em->find('Users', $this->_user_id);
            $data = explode("|", substr($this->_request->data, 0, -1));
            $count = count($data);
            $merge_into_batch = $this->_request->merge;

            for ($i = 0; $i < $count; $i++) {
                $stock_batch[] = $this->_em->getRepository("StockBatch")->find($data[$i]);
            }

            $stock_batch_merge = $this->_em->getRepository("StockBatch")->find($merge_into_batch);

            $quantity = 0;
            for ($i = 0; $i < $count; $i++) {
                $quantity = $quantity + $stock_batch[$i]->getQuantity();
                $batch_id = $stock_batch[$i]->getPkId();

                $stock_detail = $this->_em->getRepository("StockDetail")->findBy(array("stockBatch" => $batch_id));
                if (count($stock_detail) > 0) {
                    foreach ($stock_detail as $sd) {
                        $sd->setStockBatch($stock_batch_merge);
                        $sd->setModifiedBy($created_by);
                        $sd->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($sd);
                    }
                    $this->_em->flush();
                }

                if ($merge_into_batch != $batch_id) {
                    $this->_em->remove($stock_batch[$i]);
                }
            }

            $stock_batch_merge->setQuantity($quantity);
            $stock_batch_merge->setModifiedBy($created_by);
            $stock_batch_merge->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock_batch_merge);
            $this->_em->flush();

            $stock_master = new Model_StockMaster();
            $stock_master->adjustQuantityByWarehouse($merge_into_batch, $stock_batch_merge->getWarehouse()->getPkId());

            $em->getConnection()->commit();
            echo 1;
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            echo 0;
        }
    }

    /**
     * Product Ledger Date Edit
     */
    public function productLedgerDateEditAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            $param['id'] = $this->_request->id;
            $param['date'] = $this->_request->data;
            $stock_master = new Model_StockMaster();
            $stock_master->form_values = $param;
            $stock_master->editLedgerTranscationDate();
            $em->getConnection()->commit();
            echo "done";
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            echo "error";
        }
    }

    /**
     * ajaxCheckReference
     */
    public function ajaxCheckReferenceAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $wh_id = $this->_identity->getWarehouseId();
        $reference = $this->_request->getParam('ref');

        $result = $this->_em->getRepository("StockMaster")->findOneBy(array("toWarehouse" => $wh_id, "transactionReference" => $reference));

        if (count($result) > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     *
     */
    public function deleteStockPlacementAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        return true;
    }

    /**
     * Delete Stock Placement
     */
    public function physicalStockTakingAction() {
        $form = new Form_ProductLedger();

        $this->view->form = $form;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * ajaxPhysicalStockTaking
     */
    public function ajaxPhysicalStockTakingAction() {
        $this->_helper->layout->disableLayout();
        $query = array();
        parse_str($this->_request->getParam('data'), $query);

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $query;
        $result = $stock_master->getProductPhysicalQuantity();
        $this->view->result = $result;

        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * ajaxUpdatePhysicalCurrentQuantity
     */
    public function ajaxUpdatePhysicalCurrentQuantityAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $wh_id = $this->_identity->getWarehouseId();

        $data = explode("|", substr($this->_request->data, 0, -1));
        $count = count($data);

        $physical_quantity = 0;
        $current_quantity = 0;

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            $created_by = $this->_em->find('Users', $this->_user_id);
            for ($i = 0; $i < $count; $i++) {

                $flag = 0;
                $placement_physical_summary = $this->_em->getRepository("PhysicalStockTakingDetail")->find($data[$i]);

                if ($placement_physical_summary->getStockBatch() != "") {

                    $current_quantity = $placement_physical_summary->getStockBatch()->getQuantity();
                    $batch_id = $placement_physical_summary->getStockBatch()->getPkId();

                    $stk_master = new Model_StockMaster();
                    $physical_quantity = $stk_master->getPhysicalBatchQuantity($batch_id);

                    if ($current_quantity > $physical_quantity) {
                        $quantity = $current_quantity - $physical_quantity;

                        $type = 13;
                        $placement_physical_summary->setCurrentQuantity($current_quantity);
                        $placement_physical_summary->setModifiedBy($created_by);
                        $placement_physical_summary->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($placement_physical_summary);
                        $this->_em->flush();
                    } else if ($current_quantity < $physical_quantity) {
                        $quantity = $physical_quantity - $current_quantity;

                        $type = 12;
                        $placement_physical_summary->setCurrentQuantity($current_quantity);
                        $placement_physical_summary->setModifiedBy($created_by);
                        $placement_physical_summary->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($placement_physical_summary);
                        $this->_em->flush();
                    } else if ($current_quantity == $physical_quantity) {
                        $flag = 1;
                        $stock_detail = null;
                    }
                } else {
                    $stock_batch_model = new Model_StockBatch();
                    $batch_number = $placement_physical_summary->getBatchNumber();
                    $item_pack_size_id = $placement_physical_summary->getItemPackSize()->getPkId();
                    $expiry_date = $placement_physical_summary->getExpiryDate()->format("d/m/Y");

                    $vvm_type_id = 2;
                    $array = array(
                        'number' => $batch_number,
                        'quantity' => $placement_physical_summary->getQuantity(),
                        'item_id' => $item_pack_size_id,
                        'production_date' => '',
                        'expiry_date' => $expiry_date,
                        'unit_price' => '0',
                        'vvm_type_id' => $vvm_type_id,
                        'manufacturer_id' => $placement_physical_summary->getStakeholderItemPackSize()
                    );
                    $batch_id = $stock_batch_model->createBatch($array);

                    $type = 12;
                    $quantity = 0;
                    $multi_batches = $this->_em->getRepository("PhysicalStockTakingDetail")->findBy(array("batchNumber" => $batch_number, "warehouse" => $this->_identity->getWarehouseId(), "physicalStockTaking" => Model_PhysicalStockTakingDetail::STOCKID));
                    if (count($multi_batches) > 0) {
                        foreach ($multi_batches as $add_batch_id) {
                            $add_batch_id->setCurrentQuantity($current_quantity);
                            $ba_id01 = $this->_em->getRepository('StockBatch')->find($batch_id);
                            $add_batch_id->setStockBatch($ba_id01);
                            $add_batch_id->setModifiedBy($created_by);
                            $add_batch_id->setModifiedDate(App_Tools_Time::now());
                            $this->_em->persist($add_batch_id);
                            $this->_em->flush();
                            $quantity += $add_batch_id->getQuantity();
                        }
                    }
                }

                if ($flag == 0) {
                    $tranaction_type = new Model_TransactionTypes();
                    $type_nature = $tranaction_type->findById($type);

                    $stock_batch_vvm = new StockBatchVvm();

                    $stock_batch_vvm->setQuantity($type_nature['0']['nature'] . $quantity);
                    $stock_batch_vvm->setVvmStage($placement_physical_summary->getVvmStage());
                    $ba_id1 = $this->_em->getRepository('StockBatch')->find($batch_id);
                    $stock_batch_vvm->setStockBatch($ba_id1);
                    $stock_batch_vvm->setModifiedBy($created_by);
                    $stock_batch_vvm->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($stock_batch_vvm);
                    $this->_em->flush();

                    $stock_batch_vvm_id = $stock_batch_vvm->getPkId();

                    $stock_batch_vvm_history = new StockBatchVvmHistory();
                    $stock_batch_vvm_ID = $this->_em->getRepository('StockBatchVvm')->find($stock_batch_vvm_id);
                    $stock_batch_vvm_history->setStockBatchVvm($stock_batch_vvm_ID);
                    $stock_batch_vvm_history->setQuantity($type_nature['0']['nature'] . $quantity);
                    $stock_batch_vvm_history->setVvmStage($placement_physical_summary->getVvmStage());
                    $ba_id2 = $this->_em->getRepository('StockBatch')->find($batch_id);
                    $stock_batch_vvm_history->setStockBatch($ba_id2);
                    $stock_batch_vvm_history->setModifiedBy($created_by);
                    $stock_batch_vvm_history->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($stock_batch_vvm_history);
                    $this->_em->flush();

                    $stock_master = new StockMaster();
                    $stock_master->setTransactionDate(new \DateTime(date('Y-m-d')));
                    $tran_type = $this->_em->getRepository('TransactionTypes')->find($type);
                    $stock_master->setTransactionType($tran_type);

                    $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
                    $stock_master->setFromWarehouse($warehouse_id);
                    $stock_master->setToWarehouse($warehouse_id);
                    $created_by = $this->_em->getRepository('Users')->find($this->_userid);
                    $stock_master->setCreatedBy($created_by);
                    $stock_master->setCreatedDate(new \DateTime(date("Y-m-d")));
                    $stock_master->setModifiedBy($created_by);
                    $stock_master->setModifiedDate(App_Tools_Time::now());
                    $adjustment_date = date('d/m/Y');
                    $sm = new Model_StockMaster();
                    $trans = $sm->getTransactionNumber($type, $adjustment_date);
                    $stock_master->setTransactionNumber($trans['trans_no']);
                    $stock_master->setDraft(0);
                    $stock_master->setTransactionCounter($trans['id']);
                    $stock_master->setParentId(0);
                    $this->_em->persist($stock_master);
                    $this->_em->flush();

                    $stock_detail = new StockDetail();
                    $stock_detail->setStockMaster($stock_master);
                    $b_id = $this->_em->getRepository('StockBatch')->find($batch_id);
                    $stock_detail->setStockBatch($b_id);
                    $stock_detail->setQuantity($type_nature[0]['nature'] . $quantity);
                    $stock_detail->setAdjustmentType($type);
                    $stock_detail->setVvmStage($placement_physical_summary->getVvmStage());
                    $stock_detail->setTemporary(0);
                    $stock_detail->setItemUnit($placement_physical_summary->getItemPackSize()->getItemUnit());
                    $stock_detail->setCreatedBy($created_by);
                    $stock_detail->setCreatedDate(App_Tools_Time::now());
                    $stock_detail->setModifiedBy($created_by);
                    $stock_detail->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($stock_detail);
                    $this->_em->flush();

                    $stock_ID = $stock_master->getPkId();
                    $stock_batch = new Model_StockBatch();
                    $warehouse_data = new Model_HfDataMaster();
                    $stock_batch->adjustQuantityBywarehouse($batch_id);
                    $warehouse_data->addReport($stock_ID, $type);

                    $placement_physical_summary->setStockMaster($stock_master);
                    $placement_physical_summary->setCreatedBy($created_by);
                    $placement_physical_summary->setCreatedDate(App_Tools_Time::now());
                    $placement_physical_summary->setModifiedBy($created_by);
                    $placement_physical_summary->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($placement_physical_summary);
                    $this->_em->flush();
                }

                $already_exists = $this->_em->getRepository("PhysicalStockTakingDetail")->findBy(array("stockBatch" => $batch_id, "warehouse" => $this->_identity->getWarehouseId(), "physicalStockTaking" => Model_PhysicalStockTakingDetail::STOCKID));
                if (count($already_exists) > 0) {
                    foreach ($already_exists as $physical_stk) {
                        if ($physical_stk->getPlacementLocation() != null && $physical_stk->getQuantity() > 0) {
                            $placement = new Placements();
                            $placement->setQuantity($physical_stk->getQuantity());
                            $placement->setPlacementLocation($physical_stk->getPlacementLocation());
                            $placement->setStockBatch($physical_stk->getStockBatch());
                            $type_id = $this->_em->getRepository("ListDetail")->find(114);
                            $placement->setPlacementTransactionType($type_id);
                            $created_by = $this->_em->getRepository("Users")->find($this->_userid);
                            $placement->setCreatedBy($created_by);
                            $placement->setModifiedBy($created_by);
                            $placement->setModifiedDate(App_Tools_Time::now());
                            $placement->setIsPlaced(1);
                            $placement->setCreatedDate(new \DateTime(date("Y-m-d")));
                            $vvm = $physical_stk->getVvmStage();
                            $placement->setVvmStage($vvm);
                            $this->_em->persist($placement);
                            $this->_em->flush();
                        }
                    }
                }
            }

            $em->getConnection()->commit();
            echo 1;
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            echo 0;
        }
    }

    /**
     * Physical Stock Taking Report
     */
    public function physicalStockTakingReportAction() {
        $form = new Form_PhysicalStockTaking();

        $this->view->form = $form;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * ajaxPhysicalStockTakingReport
     */
    public function ajaxPhysicalStockTakingReportAction() {
        $this->_helper->layout->disableLayout();
        $query = array();
        parse_str($this->_request->getParam('data'), $query);

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $query;
        $result = $stock_master->getProductPhysicalStockTakingQuantity();
        $this->view->result = $result;

        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * Priority Vaccines Distribution
     */
    public function priorityVaccinesDistributionAction() {
        $form = new Form_ProductLedger();
        $this->view->form = $form;
        $form->detail_summary->setValue(2);
        $ips = new Model_ItemPackSizes();
        $this->view->products = $ips->getAllItemsNonDil();
    }

    /**
     * ajaxPriorityVaccineDistribution
     */
    public function ajaxPriorityVaccineDistributionAction() {
        $this->_helper->layout->disableLayout();
        $detail_summary = $this->_request->getParam('detail_summary');

        $ips = new Model_ItemPackSizes();
        if ($detail_summary == 1) {
            $this->view->products = $ips->getAllItemsNonDilSummary();
        } else {
            $this->view->products = $ips->getAllItemsNonDil();
        }
        $this->view->detail_summary = $detail_summary;
    }

    /**
     * Priority Vaccines Distribution Print
     */
    public function priorityVaccinesDistributionPrintAction() {
        $this->_helper->layout->setLayout('print');
        $form = new Form_ProductLedger();
        $this->view->form = $form;
        $form->detail_summary->setValue(2);
        $ips = new Model_ItemPackSizes();
        $this->view->products = $ips->getAllItemsNonDil();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
    }

    /**
     * Priority Vaccines Distribution Detail Print
     */
    public function priorityVaccinesDistributionDetailPrintAction() {
        $this->_helper->layout->setLayout('print');
        $ips = new Model_ItemPackSizes();
        $this->view->products = $ips->getAllItemsNonDil();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->headTitle("Priority Vaccines Distribution");
    }

    /**
     * Priority Vaccines Distribution Detail Print
     */
    public function priorityNonVaccinesDistributionDetailPrintAction() {
        $this->_helper->layout->setLayout('print');
        $ips = new Model_ItemPackSizes();
        $this->view->products = $ips->getAllNonVaccineItems();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->headTitle("Priority Non Vaccines Distribution");
    }

    /**
     * Expired Stock Report
     */
    public function expiredStockReportAction() {
        $expired = '';
        $ccbreak = '';
        $default_adj_type = 9;
        $form = new Form_AdjustmentSearch();
        $form->adjustment_type->setMultiOptions(array("all" => "All", "9" => "Expired", "6" => "Cold Chain Break"));
        $form->adjustment_type->setValue($default_adj_type);
        $this->view->form = $form;
        $stock_master = new Model_StockMaster();
        $stock_master->form_values = array(
            'date_from' => date("01/m/Y"),
            'date_to' => date("d/m/Y"),
            'adjustment_type' => $default_adj_type
        );

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $stock_master->form_values = $data;

                switch ($data['adjustment_type']) {
                    case 9:
                        $expired = $stock_master->expiredStockReport();
                        break;
                    case 6:
                        $ccbreak = $stock_master->expiredStockReport();
                        break;
                    case 'all':
                        $stock_master->form_values['adjustment_type'] = 9;
                        $expired = $stock_master->expiredStockReport();
                        $stock_master->form_values['adjustment_type'] = 6;
                        $ccbreak = $stock_master->expiredStockReport();
                        break;
                    default:
                        break;
                }
            }
        } else {
            $expired = $stock_master->expiredStockReport();
        }

        $this->view->expired = $expired;
        $this->view->ccbreak = $ccbreak;
        $this->view->date_from = App_Controller_Functions::dateToMonthYear(App_Controller_Functions::dateToDbFormat($stock_master->form_values['date_from']), "M d, Y");
        $this->view->date_to = App_Controller_Functions::dateToMonthYear(App_Controller_Functions::dateToDbFormat($stock_master->form_values['date_to']), "M d, Y");
    }

    /**
     * Expired Stock Print
     */
    public function expiredStockPrintAction() {
        $this->_helper->layout->setLayout('print');
        $expired = '';
        $ccbreak = '';
        $adjustment_type = $this->_request->getParam('adjustment_type', 9);
        $stock_master = new Model_StockMaster();
        $stock_master->form_values = array(
            'date_from' => $this->_request->getParam('date_from', date("d/m/Y")),
            'date_to' => $this->_request->getParam('date_to', date("d/m/Y")),
            'adjustment_type' => $adjustment_type
        );

        switch ($adjustment_type) {
            case 9:
                $expired = $stock_master->expiredStockReport();
                break;
            case 6:
                $ccbreak = $stock_master->expiredStockReport();
                break;
            case 'all':
                $stock_master->form_values['adjustment_type'] = 9;
                $expired = $stock_master->expiredStockReport();
                $stock_master->form_values['adjustment_type'] = 6;
                $ccbreak = $stock_master->expiredStockReport();
                break;
            default:
                break;
        }

        $this->view->expired = $expired;
        $this->view->ccbreak = $ccbreak;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
        $this->view->date_from = App_Controller_Functions::dateToMonthYear(App_Controller_Functions::dateToDbFormat($stock_master->form_values['date_from']), "M d, Y");
        $this->view->date_to = App_Controller_Functions::dateToMonthYear(App_Controller_Functions::dateToDbFormat($stock_master->form_values['date_to']), "M d, Y");
    }

    /**
     * Add New Batch
     */
    public function addNewBatchAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $params = $this->_request->getParams();

        $stock_batch = new Model_StockBatch();
        $array = array(
            'number' => $params['batch'],
            'item_id' => $params['product_id'],
            'quantity' => 0,
            'expiry_date' => $params['expiry_date'],
            'production_date' => $params['production_date'],
            'vvm_type_id' => $params['vvm_type_id'],
            'unit_price' => $params['unit_price'],
            'manufacturer_id' => $params['manufacturer']
        );
        if (!$stock_batch->checkBatch($array)) {
            $batch_id = $stock_batch->addStockBatch($array);
            echo $batch_id;
        } else {
            echo 0;
        }
    }

    /**
     * Log Book
     */
    public function logBookAction() {
        $form = new Form_MonthlyConsumption();

        $start = 0;
        $end = $this->_request->getParam('counter', 10);

        $temp = $this->_request->do;

        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));

        $temp = explode("|", $temp);

        $warehouse_id = $temp[0];
        $is_new_rpt = $temp[2];
        $rpt_date = $temp[1];
        $tt = explode("-", $rpt_date);
        $yy = $tt[0];
        $mm = $tt[1];
        if (isset($this->_request->do) && !empty($this->_request->do)) {
            $temp1 = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->form_values = array('warehouse_id' => $warehouse_id);
            $result = $warehouse_data->getMonthYearByWarehouseIdLogBook();

            if ($result) {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
                foreach ($result as $row) {
                    $loc_id = $row['location_id'];
                    $do = 'Z' . base64_encode($warehouse_id . '|' . $row['report_year'] . '-' . str_pad($row['report_month'], 2, "0", STR_PAD_LEFT) . '-01' . '|2');
                    $arr_combo[] = array(
                        "key" => $do,
                        "value" => $row['report_month'] . '-' . $row['report_year']
                    );
                }
            } else {
                $arr_combo = array();
                $arr_combo[] = array(
                    "key" => "",
                    "value" => "Select"
                );
            }



            $form->monthly_report->setMultiOptions($arr_combo);
            $form->monthly_report->setValue($temp1);
        }


        if ($this->_request->isPost()) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {

                if ($this->_request->getPost()) {

                    $temp = $this->_request->do;

                    $log_book = new Model_HfDataMaster();
                    $log_book->form_values = $this->_request->getPost();
                    $log_book->form_values['temp'] = $temp;

                    $result = $log_book->addLog();
                    $em->getConnection()->commit();
                    if ($result) {
// Open report in edit form after save
                        $l3m_dt = new DateTime($rpt_date);
                        $string = "Z" . base64_encode($warehouse_id . '|' . $l3m_dt->format('Y-m-') . '01|2');
                        $this->redirect("/stock/log-book?do=" . $string);
                    }
                }
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
            }
        }

        $item_pack_sizes = new Model_ItemPackSizes();

        $items = $item_pack_sizes->logBookItemPackSize();
        $this->view->is_new_report = $is_new_rpt;
        $this->view->wh_id = $warehouse_id;
        $this->view->prev_month_date = $rpt_date;
        $this->view->items = $items;
        $this->view->mm = $mm;
        $this->view->yy = $yy;
        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
        $this->view->do = $this->_request->do;
        $this->view->locid = $loc_id;
        $this->view->rpt_date = $rpt_date;
        $locations = new Model_Locations();
        $result = $locations->getSindhDistricts();
        $this->view->locations = $result;
    }

    /**
     * Log Book Add
     * For new Log Book Form
     */
    public function logBookAddAction() {
        $form = new Form_LogBookAdd();
        $this->view->form = $form;

        $temp = $this->_request->do;

        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));

        $temp = explode("|", $temp);

        $warehouse_id = $temp[0];
        $rpt_date = $temp[1];
        $tt = explode("-", $rpt_date);
        $yy = $tt[0];
        $mm = $tt[1];

        $item_pack_sizes = new Model_ItemPackSizes();
        $items = $item_pack_sizes->logBookItemPackSize();

        $warehouses = new Model_Warehouses();
        $warehouse_name = $warehouses->getWarehouseNameByWarehouseId($warehouse_id);

        $this->view->items = $items;
        $this->view->mm = $mm;
        $this->view->yy = $yy;
        $this->view->wh_id = $warehouse_id;
        $this->view->warehouse_name = $warehouse_name;
        $this->view->do = $this->_request->do;

        $log_book_month = new Model_LogBook();
        $log_book_month->form_values['month'] = $mm;
        $log_book_month->form_values['year'] = $yy;
        $log_book_month->form_values['warehouse_id'] = $warehouse_id;
        $query = $log_book_month->getLogBookByMonth();
        $this->view->query = $query;
    }

    /**
     * Log Book Add Print
     * For new Log Book Form
     */
    public function logBookAddPrintAction() {
        $this->_helper->layout->setLayout('print-landscape');
        $this->view->headTitle("Log Book");
        $this->view->print_title = "Log Book";

        $item_pack_sizes = new Model_ItemPackSizes();
        $items = $item_pack_sizes->logBookItemPackSize();

        $this->view->items = $items;

        $mm = $this->_request->month;
        $yy = $this->_request->year;
        $warehouse_id = $this->_request->wh_id;

        $warehouses = new Model_Warehouses();

        $log_book_month = new Model_LogBook();
        $log_book_month->form_values['month'] = $mm;
        $log_book_month->form_values['year'] = $yy;
        $log_book_month->form_values['warehouse_id'] = $warehouse_id;
        $query = $log_book_month->getLogBookByMonth();
        $this->view->query = $query;
        $this->view->warehousename = $warehouses->getWarehouseNameByWarehouseId($warehouse_id);


        $this->view->username = $this->_identity->getUserName();
    }

    /**
     * ajaxDisplayLogBookResult
     */
    public function ajaxDisplayLogBookResultAction() {
        $this->_helper->layout->disableLayout();

        $temp = $this->_request->do;
        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));
        $temp = explode("|", $temp);

        $warehouse_id = $temp[0];
        $rpt_date = $temp[1];
        $tt = explode("-", $rpt_date);
        $yy = $tt[0];
        $mm = $tt[1];

        $item_pack_sizes = new Model_ItemPackSizes();
        $items = $item_pack_sizes->logBookItemPackSize();

        $this->view->items = $items;
        $this->view->mm = $mm;
        $this->view->yy = $yy;
        $this->view->wh_id = $warehouse_id;
        $this->view->do = $this->_request->do;

        if ($this->_request->isPost()) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();

            try {
                if ($this->_request->getPost()) {

                    $temp = $this->_request->do;
                    $log_book = new Model_LogBook();
                    $log_book->form_values = $this->_request->getPost();
                    $log_book->form_values['temp'] = $temp;
                    $log_book->addLogBook();
                    $em->getConnection()->commit();
                }
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
            }
        }

        $log_book_month = new Model_LogBook();
        $log_book_month->form_values['month'] = $mm;
        $log_book_month->form_values['year'] = $yy;
        $log_book_month->form_values['warehouse_id'] = $warehouse_id;
        $query = $log_book_month->getLogBookByMonth();
        $this->view->query = $query;
    }

    /**
     * Delete Log Book
     */
    public function deleteLogBookAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;

            $this->_em->getConnection()->beginTransaction();
            try {
                $log_book = new Model_LogBook();
                $log_book->deleteLogBook($id);
                $this->_em->getConnection()->commit();
            } catch (Exception $e) {
                $this->_em->getConnection()->rollback();
                $this->_em->close();
                App_FileLogger::info($e);
            }

            echo 1;
            exit;
        }
    }

    /**
     * ajaxGetDistricts
     */
    public function ajaxGetDistrictsAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->province_id) && !empty($this->_request->province_id)) {

            $locations = new Model_Locations();
            $locations->form_values['province_id'] = $this->_request->province_id;

            $districts = $locations->getDistrictsByProvince();

            $this->view->result = $districts;
        }
    }

    /**
     * ajaxGetTehsils
     */
    public function ajaxGetTehsilsAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->district_id) && !empty($this->_request->district_id)) {

            $locations = new Model_Locations();
            $locations->form_values['district_id'] = $this->_request->district_id;

            $tehsils = $locations->getTehsilsByDistrict();

            $this->view->result = $tehsils;
        }
    }

    /**
     * ajaxGetUcsByTehsil
     */
    public function ajaxGetUcsByTehsilAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->tehsil_id) && !empty($this->_request->tehsil_id)) {

            $locations = new Model_Locations();
            $locations->form_values['tehsil_id'] = $this->_request->tehsil_id;

            $ucs = $locations->getUcsByTehsil();

            $this->view->result = $ucs;
        }
    }

    /**
     * ajaxGetWarehousesByTehsil
     */
    public function ajaxGetWarehousesByTehsilAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->tehsil_id) && !empty($this->_request->tehsil_id)) {
            $warehouses = new Model_Warehouses();
            $warehouses->form_values['parent_id'] = $this->_request->tehsil_id;
            $th_warehouses = $warehouses->getUcWarehousesofTehsil();
            $this->view->result = $th_warehouses;
        }
    }

    /**
     * ajaxGetUcsAction
     */
    public function ajaxGetUcsAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->district_id) && !empty($this->_request->district_id)) {

            $locations = new Model_Locations();
            $locations->form_values['district_id'] = $this->_request->district_id;

            $array = $locations->getUcsByDistrict();



            $this->view->result = $array;
        }
    }

    /**
     * Search Log Book
     */
    public function searchLogBookAction() {
        $form = new Form_LogBook();

        $log_book = new Model_LogBook();
        $locations = new Model_Locations();

        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $log_book->form_values = $res;

            if (!empty($res['entry_type'])) {
                $form->entry_type->setValue($res['entry_type']);
            }

            if (!empty($res['province'])) {
                $form->province->setValue($res['province']);
            }



            $locations->form_values['province_id'] = $res['province'];

            if (!empty($res['province'])) {
                $districts = $locations->getDistrictsByProvince();
                $districtarray[] = 'Select';
                foreach ($districts as $dist) {
                    $districtarray[$dist['pkId']] = $dist['locationName'];
                }
                $form->district->setMultiOptions($districtarray);
                $form->district->setValue($res['district']);
            }


            $locations->form_values['district_id'] = $res['district'];
            if (!empty($res['district'])) {
                $tehsils = $locations->getTehsilsByDistrict();
                $tehsilarray[] = 'Select';
                foreach ($tehsils as $teh) {
                    $tehsilarray[$teh['pkId']] = $teh['locationName'];
                }
                $form->tehsil->setMultiOptions($tehsilarray);
                $form->tehsil->setValue($res['tehsil']);
            }

            $locations->form_values['tehsil_id'] = $res['tehsil'];
            if (!empty($res['tehsil'])) {
                $ucs = $locations->getUcsByTehsil();
                $ucarray[] = 'Select';
                foreach ($ucs as $uc) {
                    $ucarray[$uc['pkId']] = $uc['locationName'];
                }
                if (!empty($ucarray)) {
                    $form->uc->setMultiOptions($ucarray);
                    $form->uc->setValue($res['uc']);
                }
            }

            $form->vaccination_date_from->setValue($res['vaccination_date_from']);
            $form->vaccination_date_to->setValue($res['vaccination_date_to']);
        }
        $arr_data = $log_book->getSearchLogBook();
        $item_pack_sizes = new Model_ItemPackSizes();

        $items = $item_pack_sizes->logBookItemPackSize();
        $this->view->items = $items;
        $this->view->arr_data = $arr_data;
        $this->view->form = $form;
        $data = $this->_request->getParam("voucher", "");
        if (!empty($data)) {
            $string = substr($data, 1);
            list($voucher, $master_id) = explode("|", base64_decode($string));
            $this->view->voucher = $voucher;
            $this->view->master_id = $master_id;
        }
    }

    /**
     * ajaxAddMoreLogRows
     */
    public function ajaxAddMoreLogRowsAction() {
        $this->_helper->layout->disableLayout();

        $start = $this->_request->getParam('start');
        $end = $this->_request->getParam('end');

        $item_pack_sizes = new Model_ItemPackSizes();
        $items = $item_pack_sizes->logBookItemPackSize();
        $this->view->items = $items;
        $locations = new Model_Locations();
        $result = $locations->getSindhDistricts();
        $this->view->locations = $result;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * ajaxReportCombo2
     */
    public function ajaxReportCombo2Action() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_id = $this->_request->warehouse_id;
            $warehouse_data->form_values = array('warehouse_id' => $warehouse_id);
            $result = $warehouse_data->getMonthYearByWarehouseId2();
            $this->view->warehouse_id = $warehouse_id;
            $this->view->result = $result;
            $this->view->level = $this->_request->level;
        }
    }

    /**
     * ajaxCheckLogbookName
     */
    public function ajaxCheckLogbookNameAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $name = $this->_request->name;
        $fname = $this->_request->fname;
        $do = $this->_request->do;

        $log_book = new Model_LogBook();
        $log_book->form_values = array(
            'name' => $name,
            'fname' => $fname,
            'do' => $do
        );
        return $log_book->checkLogbookName();
    }

    /**
     * This function generates report for
     * "Requirement", "Issuance" and "Remaining Balance" at Provincial Stores
     *
     */
    public function targetIssuanceAction() {
        $form = new Form_TargetIssuanceSearch();
        $this->view->form = $form;
        $stock_master = new Model_StockMaster();

        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();

            $form->month->setValue($res['month']);
            $form->year->setValue($res['year']);
            $form->province_region->setValue($res['province_region']);

            $stock_master->form_values = $res;

            // Getting "Warehouse" from "Location"
            $warehouses = new Model_Warehouses();
            $warehouses->form_values['stakeholder_id'] = 1;
            $warehouses->form_values['province_id'] = $res['province_region'];
            $warehouse = $warehouses->getAmcWarehouses();
            $stock_master->form_values['warehouse_id'] = $warehouse[0]['key'];

            $this->view->w_name = $warehouse[0]['value'];
            $this->view->w_id = $warehouse[0]['key'];
            $month = $res['month'];
            $year = $res['year'];
            $this->view->m = $month;
            $this->view->y = $year;

            $dataset = $stock_master->targetIssuanceSearch();
            $this->view->result = $dataset;
            $monthName = date("F", mktime(0, 0, 0, $month, 10));

            // Generating XML for chart
            $title = "EPI - Provincial Requirement Vs Issuance ";
            $sub_title = $warehouse[0]['value'] . " (January, $year to $monthName,  $year)";
            $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= '$title ' subcaption= ' $sub_title' exportFileName='" . $title . " - " . date('Y-m-d H:i:s') . "' yAxisName='Doses' showValues='0' formatNumberScale='0' theme='fint'>";
            $xmlstore .= "<categories>";

            foreach ($dataset as $row) {
                $xmlstore .= "<category label='" . $row['product'] . "' />";
            }
            $xmlstore .= "</categories>";

            $xmlstore .= "<dataset seriesName='Requirement' color='5B9BD5'>";
            foreach ($dataset as $row) {
                $xmlstore .= "<set value='" . round($row['n_month_requirement']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $xmlstore .= "<dataset seriesName='Issued' color='A5C838'>";
            foreach ($dataset as $row) {
                $xmlstore .= "<set value='" . round($row['issuance']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $xmlstore .= "<dataset seriesName='Balance' color='ED7D31'>";
            foreach ($dataset as $row) {
                $xmlstore .= "<set value='" . round($row['n_month_balance']) . "' />";
            }
            $xmlstore .= "</dataset>";

            $xmlstore .= "</chart>";

            $this->view->xmlstore = $xmlstore;
        }
    }

    /**
     * Target Issuance Print
     */
    public function targetIssuancePrintAction() {
        $this->_helper->layout->setLayout("print");
        $this->view->headTitle("Vaccine Balance");

        $stock_master = new Model_StockMaster();
        $stock_master->form_values['month'] = $this->_request->m;
        $stock_master->form_values['year'] = $this->_request->y;
        $stock_master->form_values['warehouse_id'] = $this->_request->w;

        $result = $stock_master->targetIssuanceSearch();

        $warehouses = new Model_Warehouses();
        $issue_warehouse_name = $warehouses->getWarehouseNameByWarehouseId($this->_request->w);
        $this->view->i_warehousename = $issue_warehouse_name;
        $this->view->m = $this->_request->m;
        $this->view->y = $this->_request->y;

        // Logged in Warehouse
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->result = $result;
    }

    /**
     * vvm Management
     */
    public function vvmManagementAction() {
        $form = new Form_VvmManagement();
        $msg = '';

        if ($this->_request->isPost()) {
            $product_id = $this->_request->getPost('product');
            $batch_id = $this->_request->getPost('batch');

            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $quantity = $this->_request->quantity;
                $newvvm = $this->_request->newvvm;
                if (!is_null($quantity)) {
                    foreach ($quantity as $key => $value) {
                        if (!empty($value) && $value > 0) {
                            $updatedqty = $value;
                            list($batchid, $vvmid, $locationid) = explode("_", $key);
                            $batch_detail = $this->_em->getRepository("StockBatchWarehouses")->find($batchid);

                            $placement = new Model_Placements();
                            $placement->form_values = array(
                                'quantity' => "-" . $updatedqty,
                                'placement_loc_id' => $locationid,
                                'batch_id' => $batchid,
                                'placement_loc_type_id' => 116,
                                'user_id' => $this->_userid,
                                'created_date' => date("Y-m-d"),
                                'vvmstage' => $vvmid,
                                'is_placed' => 0
                            );
                            $placement->add();

                            $placement->form_values = array(
                                'quantity' => $updatedqty,
                                'placement_loc_id' => $locationid,
                                'batch_id' => $batchid,
                                'placement_loc_type_id' => 116,
                                'user_id' => $this->_userid,
                                'created_date' => date("Y-m-d"),
                                'vvmstage' => $newvvm[$key],
                                'is_placed' => 1
                            );
                            $placement->add();

                            //Log Entry START
                            $history = new VvmTransferHistory();
                            $from_to_batch = $this->_em->getRepository("StockBatchWarehouses")->find($batchid);
                            $history->setStockBatchWarehouse($from_to_batch);
                            $from_vvmstages = $this->_em->getRepository("VvmStages")->find($vvmid);
                            $history->setFromVvmStage($from_vvmstages);
                            $to_vvmstages = $this->_em->getRepository("VvmStages")->find($newvvm[$key]);
                            $history->setToVvmStage($to_vvmstages);
                            $created_by = $this->_em->getRepository("Users")->find($this->_userid);
                            $history->setCreatedBy($created_by);
                            $history->setCreatedDate(App_Tools_Time::now());
                            $history->setModifiedBy($created_by);
                            $history->setModifiedDate(App_Tools_Time::now());
                            $history->setQuantity(ABS($updatedqty));
                            $this->_em->persist($history);
                            $this->_em->flush();
                            //Log Entry END

                            $msg = 'VVM has been changed successfully!';
                            $product_id = $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId();
                            $batch_id = $batchid;
                        }
                    }
                }
                $location = new Model_Locations();

                $this->view->result = $location->getBatchVvmLocations($batch_id);

                $form->product->setValue($product_id);
                $this->view->batch = $batch_id;

                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }
            $stock_detail = new Model_StockDetail();
            $this->view->h_result = $stock_detail->getVvmTransferHistory($product_id);
        }

        $this->view->form = $form;
        $this->view->msg = $msg;
    }

    /**
     * Purpose Transfer Management
     */
    public function purposeTransferManagementAction() {
        $form = new Form_PurposeTransfer();
        $stock_detail = new Model_StockDetail();
        $msg = '';
        $created_by = $this->_em->getRepository("Users")->find($this->_userid);

        if ($this->_request->isPost()) {
            $product_id = $this->_request->getPost('product');
            $batch_id = $this->_request->getPost('batch');
            $transfer_date = $this->_request->getPost('transfer_date');

            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $quantity = $this->_request->quantity;
                $newpurpose = $this->_request->newpurpose;
                $comments = $this->_request->comments;
                $toproducts = $this->_request->toproducts;
                $transfer_date1 = $this->_request->transfer_d;
                if (!is_null($quantity)) {
                    foreach ($quantity as $key => $value) {
                        if (!empty($value) && $value > 0) {
                            $updatedqty = $value;
                            list($batchid, $vvmid, $locationid, $activity_id) = explode("_", $key);

                            $batch_detail = $this->_em->getRepository("StockBatchWarehouses")->find($batchid);
                            $microtime = strtotime(date("Y-m-d H:i:s"));

                            $stock_master = new Model_StockMaster();
                            $data = array(
                                'adjustment_date' => $transfer_date1,
                                'ref_no' => '',
                                'product' => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId(),
                                'batch_no' => $batchid,
                                'adjustment_type' => 17,
                                'quantity' => $updatedqty,
                                'comments' => $comments,
                                'item_unit_id' => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemUnit()->getPkId(),
                                'vvm_stage' => $vvmid,
                                'location_id' => $locationid . "|" . $vvmid,
                                'purpose' => $activity_id,
                                'is_received' => $microtime,
                                'transfer_date' => App_Controller_Functions::dateToDbFormat($transfer_date1)
                            );
                            $stock_master->form_values = $data;
                            $stock_master->addAdjustment();

                            $manufacturer_to_id = $this->_em->getRepository("StakeholderItemPackSizes")->findOneBy(array("stakeholder" => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getStakeholder()->getPkId(), "itemPackSize" => $toproducts[$key]));
                            if (count($manufacturer_to_id) > 0) {
                                $manufacturer_id = $manufacturer_to_id->getPkId();
                            } else {
                                $manufacturer_from_id = $this->_em->getRepository("StakeholderItemPackSizes")->findOneBy(array("stakeholder" => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getStakeholder()->getPkId(), "itemPackSize" => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId()));
                                if (count($manufacturer_from_id) > 0) {
                                    $manufacturer_id = $manufacturer_from_id->getPkId();
                                }
                            }

                            $stock_batch = new Model_StockBatch();
                            $array = array(
                                'wh_id' => $batch_detail->getWarehouse()->getPkId(),
                                'number' => $batch_detail->getStockBatch()->getNumber(),
                                'item_id' => $toproducts[$key],
                                'expiry_date' => $batch_detail->getStockBatch()->getExpiryDate()->format("d/m/Y"),
                                'quantity' => $updatedqty,
                                'production_date' => ($batch_detail->getStockBatch()->getProductionDate() != null ? $batch_detail->getStockBatch()->getProductionDate()->format("d/m/Y") : '' ),
                                'vvm_type_id' => ($batch_detail->getStockBatch()->getVvmType() != null ? $batch_detail->getStockBatch()->getVvmType()->getPkId() : '' ),
                                'unit_price' => $batch_detail->getStockBatch()->getUnitPrice(),
                                'manufacturer_id' => $manufacturer_id
                            );


                            $transbatchid = $stock_batch->createBatch($array);

                            $data2 = array(
                                'adjustment_type' => 16,
                                'batch_no' => $transbatchid,
                                'adjustment_date' => $transfer_date1,
                                'ref_no' => '',
                                'product' => $toproducts[$key],
                                'quantity' => $updatedqty,
                                'comments' => $comments,
                                'item_unit_id' => $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemUnit()->getPkId(),
                                'vvm_stage' => $vvmid,
                                'location_id' => $locationid . "|" . $vvmid,
                                'purpose' => $newpurpose[$key],
                                'is_received' => $microtime
                            );
                            $stock_master->form_values = $data2;
                            $stock_master->addAdjustment();

                            //Log Entry START
                            $history = new PurposeTransferHistory();
                            $from_batch = $this->_em->getRepository("StockBatchWarehouses")->find($batchid);
                            $history->setFromStockBatchWarehouse($from_batch);
                            $to_batch = $this->_em->getRepository("StockBatchWarehouses")->find($transbatchid);
                            $history->setToStockBatchWarehouse($to_batch);
                            $from_activity = $this->_em->getRepository("StakeholderActivities")->find($activity_id);
                            $history->setFromActivity($from_activity);
                            $to_activity = $this->_em->getRepository("StakeholderActivities")->find($newpurpose[$key]);
                            $history->setToActivity($to_activity);
                            $history->setCreatedBy($created_by);
                            $history->setModifiedBy($created_by);
                            $cre_date = App_Controller_Functions::dateToDbFormat($transfer_date1);

                            $history->setCreatedDate(new \DateTime($cre_date));

                            $history->setModifiedDate(App_Tools_Time::now());
                            $history->setQuantity(ABS($updatedqty));
                            $transaction_type = $this->_em->getRepository("TransactionTypes")->find(Model_TransactionTypes::CHANGE_PURPOSE_POSITIVE);
                            $history->setTransactionType($transaction_type);

                            $this->_em->persist($history);
                            $this->_em->flush();
                            //Log Entry END

                            $stock_batch->adjustQuantityByWarehouse($transbatchid);

                            $msg = 'Purpose has been changed successfully!';
                            $product_id = $batch_detail->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId();
                            $batch_id = $batchid;
                            $transfer_date = $transfer_date1;
                        }
                    }
                }

                $this->view->result = $stock_detail->purposeTransferManagement($batch_id, $transfer_date);
                $form->product->setValue($product_id);
                // echo $transfer_date;
                //exit;
                $form->transfer_date->setValue($transfer_date);
                $this->view->batch = $batch_id;
                $this->view->transfer_d = $transfer_date;
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

            $this->view->opening_balance_result = $stock_detail->getOpeningBalancePurpose($product_id, $batch_id);
            $this->view->closing_balance_result = $stock_detail->getClosingBalancePurpose($product_id, $batch_id);
            $this->view->h_result = $stock_detail->getPurposeTransferHistory($product_id);
            $this->view->product = $product_id;
        }

        $this->view->form = $form;
        $this->view->msg = $msg;
    }

    /**
     * Stock Issue
     */
    public function stockIssueAction() {

        $form = new Form_StockIssueDispatch();
        $stock_master = new Model_StockMaster();
        $stakeholder_item_pack_sizes = new Model_StakeholderItemPackSizes();
        $this->view->form = $form;

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $data = $form->getValues();
                $stock_master->form_values = $data;
                $form->month->setValue($data['month']);
                $form->year->setValue($data['year']);

                $form->activity_id->setValue($data['activity_id']);
                $stakeholder_item_pack_sizes->form_values['stakeholder_id'] = $data['activity_id'];
                $this->view->activity_id = $data['activity_id'];
                $this->view->month = $data['month'];
                $this->view->year = $data['year'];
            }
        } else {
            if ($this->_request->month) {
                $form->month->setValue($this->_request->month);
                $form->year->setValue($this->_request->year);
                $stock_master->form_values['month'] = $this->_request->month;
                $stock_master->form_values['year'] = $this->_request->year;
                $stakeholder_item_pack_sizes->form_values['stakeholder_id'] = '1';
                $this->view->activity_id = 1;
                $this->view->month = $this->_request->month;
                $this->view->year = $this->_request->year;
            } else {
                $form->month->setValue(date('m'));
                $form->year->setValue(date('Y'));
                $stock_master->form_values['month'] = date('m');
                $stock_master->form_values['year'] = date('Y');
                $stakeholder_item_pack_sizes->form_values['stakeholder_id'] = '1';
                $this->view->activity_id = 1;
                $this->view->month = date('m');
                $this->view->year = date('Y');
            }
        }

        $items = $stakeholder_item_pack_sizes->getAllProductsByStakeholderTypeVaccines();

        $dataset_search = $stock_master->getStockIssueVoucherList();

        $this->view->result = $dataset_search;
        $this->view->wh_id = $this->_identity->getWarehouseId();
        $this->view->items = $items;
        $auth = App_Auth::getInstance();
        $this->view->role_id = $auth->getRoleId();
    }

    /**
     * Add Stock Issue
     */
    public function addStockIssueAction() {
        $stock_master = new Model_StockMaster();
        $stock_batch = new Model_StockBatch();
        $placements = new Model_Placements();
        $stock_detail = new Model_StockDetail();
        $form_values = array();
        $temp = array();
        $batch = array();

        $date_em = '01' . "-" . $this->_request->month . "-" . $this->_request->year;

        $date_in = date('d/m/Y', strtotime($date_em));
        $this->view->month = $this->_request->month;
        $this->view->year = $this->_request->year;

        $this->view->data_in = $date_in;
        $form_values['transaction_type_id'] = 2;
        $form_values['adjustment_type'] = 2;
        //need to be dynamic
        $form_values['transaction_reference'] = $this->_request->transaction_reference;
        $form_values['activity_id'] = $this->_request->activity_id;
        $warehouse_id = $this->_request->r_wh_id;
        $form_values['comments'] = $this->_request->comments;
        // end
        $stock_id = "";
        $master_id = "";
        $start = 0;
        $end = $this->_request->getParam('counter', 10);
        $activity_id = 1;
        $type_d = $this->_request->type_d;
        $r_wh_id = $this->_request->r_wh_id;
        $form_values['type_d'] = $type_d;
        $form = new Form_AddStockIssue();
        $form->addRows($start, $end);
        $form->transaction_date->setValue(date("$date_in h:i A"));
        if (!empty($this->_request->stock_master_id)) {
            $master_id = $this->_request->stock_master_id;
        }
        $form->hdn_activity_id->setValue($activity_id);
        $form->hdn_receive_warehouse_id->setValue($r_wh_id);
        $form->hdn_stock_master_id->setValue($master_id);
        $rows = $this->_em->getRepository('Warehouses')->find($warehouse_id);
        $this->view->warehouse_name = $rows->getWarehouseName();
        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            if ($type_d == 'd' && !$this->_request->isPost()) {

                $s_master = new Model_StockMaster();
                $s_master->form_values['sender_warehouse_id'] = $this->_identity->getWarehouseId();
                $s_master->form_values['receive_warehouse_id'] = $r_wh_id;

                $result = $s_master->getIssueTemp();
                $form->transaction_date->setValue($result[0]['transaction_date']);
                $form->transaction_reference->setValue($result[0]['transaction_reference']);
                $form->comments->setValue($result[0]['comments']);
                $i = 0;
                foreach ($result as $row) {
                    $rows = "rows" . $i;
                    $form->$rows->item_pack_size_id->setValue($row['item_pack_size_id']);
                    $stock_batch->form_values['item_pack_size_id'] = $row['item_pack_size_id'];
                    $stock_batch->form_values['transaction_date'] = $row['transaction_date'];
                    $associated = $stock_batch->getRunningBatches();

                    if ($associated) {
                        foreach ($associated as $row_batch) {
                            $batch[$row_batch['pkId']] = $row_batch['number'] . '|' . number_format($row_batch['quantity']) . '|' . $row_batch['priority'];
                        }
                    }
                    $stock_batch->form_values['pk_id'] = $row['stock_batch_id'];
                    $stock_batch->form_values['trans_date'] = App_Controller_Functions::dateToDbFormat($row['transaction_date']);
                    $placements->form_values['batch_id'] = $row['stock_batch_id'];
                    $placements->form_values['trans_date'] = App_Controller_Functions::dateToDbFormat($row['transaction_date']);
                    $vvmstages = $placements->getIssueAvailableVvmStages();

                    if (!empty($batch)) {
                        $form->$rows->number->setMultiOptions($batch);
                        if (!empty($vvmstages[0]['qty'])) {

                            $form->$rows->hdn_available_quantity->setValue($vvmstages[0]['qty']);
                            $form->$rows->hdn_vvm_stage->setValue($vvmstages[0]['placement_location_id'] . "|" . $vvmstages[0]['vvm_stage_id'] . "|" . $vvmstages[0]['qty']);
                        }
                    }
                    unset($batch);

                    $form->$rows->number->setValue($row['stock_batch_id']);


                    $form->$rows->expiry_date->setValue($row['expiry_date']);
                    $form->$rows->quantity->setValue(abs($row['quantity']));

                    $i++;
                }
            } else if ($this->_request->isPost()) {


                $temp = $this->_request->getPost();
                $data = array_merge($temp, $form_values);
                $data['warehouse'] = $warehouse_id;
                // Stock Master
                $stock_id = $stock_master->addStockMaster1($data);

                $form_values['type'] = 'd';

                // end
                $form_values['stock_master_id'] = $stock_id;

                $form_values['counter'] = $end;

                $form_values['transaction_type_id'] = 2;

                $data = array_merge($temp, $form_values);

                $detail_id = $stock_detail->addStockDetail1Validation($data);
                if (empty($detail_id)) {
                    for ($i = 0; $i < $end; $i++) {
                        $form_values = $data;
                        $row = $form_values["rows" . $i];
                        if ($row['quantity'] > 0) {

                            $stock_batch->adjustQuantityByWarehouse($row['number']);

                            $stock_batch->autoRunningLEFOBatch($row['item_pack_size_id']);
                            $stock_batch->form_values['pk_id'] = $row['number'];
                            $stock_batch->form_values['status'] = Model_StockBatch::FINISHED;
                            $stock_batch->changeStatus();
                        }
                    }
                    $stock_m = $this->_em->getRepository('StockMaster')->find($stock_id);

                    $this->view->msg = 'Stock has been issued successfully. Your voucher number is ';
                    $this->view->voucher = $stock_m->getTransactionNumber();
                    $this->view->master_id = $stock_id;
                    $this->view->error = "B";
                } else {
// Open report in edit form after save
                    $this->view->error = "P";
                    $this->view->msg = $detail_id;
                }
            }

            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }
        if ($type_d == 'n' && !$this->_request->isPost()) {
            $sips = new Model_StakeholderItemPackSizes();
            $sips->form_values['stakeholder_id'] = '1';

            $result = $sips->getAllProductsByStakeholderTypeVaccines();
            $i = 0;
            $batch11 = array();

            foreach ($result as $row) {

                $rows = "rows" . $i;

                //  $form->$rows->item_pack_size_id->setValue($row['item_pack_size_id']);

                $stock_batch->form_values['item_pack_size_id'] = $row['item_pack_size_id'];
                $stock_batch->form_values['transaction_date'] = date("$date_in h:i A");
                $associated = $stock_batch->getRunningBatches();
                $batch11[''] = "Select";
                if ($associated) {
                    foreach ($associated as $row_batch) {
                        $batch11[$row_batch['pkId']] = $row_batch['number'] . '|' . number_format($row_batch['quantity']) . '|' . $row_batch['priority'];
                    }
                }
                if (!empty($batch11)) {
                    //    $form->$rows->number->setMultiOptions($batch11);
                }
                unset($batch11);
                $i++;
            }
        }
        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * ajaxAddIssueMoreRows
     */
    public function ajaxAddIssueMoreRowsAction() {
        $this->_helper->layout->disableLayout();

        $start = $this->_request->getParam('start');
        $end = $this->_request->getParam('end');

        $form = new Form_AddStockIssue();
        $form->addRows($start, $end);

        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * ajaxGetProductsIssueByStakeholderActivity
     */
    public function ajaxGetProductsIssueByStakeholderActivityAction() {
        $this->_helper->layout->disableLayout();
        $sips = new Model_StakeholderItemPackSizes();
        $type = $this->_request->getParam('type', '');
        $sips->form_values = array(
            'stakeholder_id' => $this->_request->getParam('activity_id', ''),
            'trans_date' => $this->_request->getParam('tran_date', date("d/m/Y h:i:s A"))
        );
        if (!empty($type) && $type == 2) {
            $result = $sips->getAllIssueProductsByStakeholder();
        } else {
            $result = $sips->getAllProductsByStakeholderType();
        }

        $this->view->result = $result;
    }

    /**
     * ajaxStockIssueTempAction
     */
    public function ajaxStockIssueTempAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $stock_detail = new Model_StockDetail();
        $form_values = array();
        $start = 0;
        $end = $this->_request->getParam('counter', 10);
        $form = new Form_AddStockIssue();
        $form->addRows($start, $end);


        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $stock_master = new Model_StockMaster();
            $temp = $form->getValues();
            $form_values['counter'] = $end;
            $form_values['transaction_type_id'] = 2;
            $form_values['adjustment_type'] = 2;
            $data = array_merge($temp, $form_values);
            $stock_master->deleteIssueDraft($data);
        }

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

                $stock_master = new Model_StockMaster();

                $temp = $form->getValues();
                $form_values['counter'] = $end;
                $form_values['transaction_type_id'] = 2;
                $form_values['adjustment_type'] = 2;
                $data = array_merge($temp, $form_values);
                $stock_id = $stock_master->addStockMasterTemp($data);

                // need to dynamic
                $form_values['type'] = 'd';

                $form_values['stock_master_id'] = $stock_id;
                $form_values['counter'] = $end;
                $form_values['transaction_type_id'] = 2;
                $form_values['counter'] = $end;
                $data = array_merge($temp, $form_values);

                $detail_id = $stock_detail->addStockDetailTempValidation($data);
            }
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }

        if ($detail_id) {
            echo '1';
        } else {
            return FALSE;
        }
    }

    /**
     * Activity Log Action
     */
    public function activityLogAction() {
        $form = new Form_StockIssueSearch();
        $this->view->form = $form;
        $stock_master = new Model_StockMaster();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();
            $stock_master->form_values = $data;
        }
        $dataset = $stock_master->activityLogSearch();
        $this->view->result = $dataset;
    }

    /**
     * Procurements
     */
    public function procurementsAction() {

        $arr_data = array('transaction_number' => "",
            'stock_id' => "",
            'shipment_date' => date("d/m/Y"),
            'success' => $this->_request->success
        );

        $form = new Form_Procurements();
        $form_manufacturer = new Form_AddManufacturer();
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($data['temp'] == 0) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    if ($this->_request->isPost()) {
                        $shipment = new Model_Shipments();
                        $data = $this->_request->getPost();
                        $shipment->form_values = $data;
                        $shipment->addProcurements();
                    }

                    $em->getConnection()->commit();
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            } else if ($data['temp'] == 1) {
                $shipment = new Model_Shipments();
                $data = $this->_request->getPost();
                $shipment->form_values = $data;
                $shipment->updateProcurements();
                $this->view->msg = 'Procurement has been added successfully';
            }
        }

        sleep(3);
        $form_values = $this->_request->getPost();
        $shipmnets = new Model_Shipments();
        $shipmnets->form_values = $form_values;

        $temp_stock_list = $shipmnets->getTempShipmnetsList();

        if ($temp_stock_list) {
            $arr_data['tempstocks'] = $temp_stock_list;
            $form->shipment_date->setValue(date("d/m/Y h:i A", strtotime($temp_stock_list[0]['shipment_date'])));
            $form->transaction_reference->setValue($temp_stock_list[0]['reference_number']);
            $form->from_warehouse_id->setValue($temp_stock_list[0]['funding_source_id']);
            $form->activity_id->setValue($temp_stock_list[0]['stakeholder_activity_id']);
            $form->status->setValue($temp_stock_list[0]['status']);

            $form->shipment_date->setAttrib("disabled", "true");
            $form->transaction_reference->setAttrib("disabled", "true");
            $form->from_warehouse_id->setAttrib("disabled", "true");
            $form->activity_id->setAttrib("disabled", "true");
            $form->status->setAttrib("disabled", "true");
            $form->hdn_master_id->setValue($temp_stock_list[0]['pk_id']);
        } else {
            $form->shipment_date->setValue(date("d/m/Y h:i A"));
        }



        $this->view->form = $form;
        $this->view->form_manufacturer = $form_manufacturer;
        $this->view->arr_data = $arr_data;


        $this->view->success = $this->_request->getParam('success', 0);
    }

    /**
     * Search Procurements
     */
    public function searchProcurementsAction() {
        $form = new Form_ProcurementFilters();
        $shipments = new Model_Shipments();
        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $shipments->form_values = $res;
            $form->from_warehouse_id->setValue($res['from_warehouse_id']);
            $form->from_date->setValue($res['from_date']);
            $form->to_date->setValue($res['to_date']);
            $form->item_pack_size_id->setValue($res['item_pack_size_id']);
            $form->status->setValue($res['status']);
        }
        $arr_data = $shipments->getProcurements();
        $this->view->arr_data = $arr_data;
        $this->view->form = $form;
    }

    /**
     * ajaxAddMoreAdjustmentRows
     */
    public function ajaxAddMoreAdjustmentRowsAction() {
        $this->_helper->layout->disableLayout();

        $start = $this->_request->getParam('start');
        $end = $this->_request->getParam('end');

        $form = new Form_PlannedIssue();
        $form->addRows($start, $end);

        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     * Multiple Adjustment
     */
    public function multipleAdjustmentAction() {
        $stock_master = new Model_StockMaster();
        $stock_batch = new Model_StockBatch();
        $stock_detail = new Model_StockDetail();
        $warehouse_data = new Model_HfDataMaster();
        $form = new Form_MultipleAdjustment();
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
            $created_by = $this->_em->find('Users', $this->_user_id);
            if (!empty($master_id)) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();

                try {
                    //Start update issue period
                    $array = $this->_request->getParams();
                    $stock_master->updateStockPeriod($master_id, $array);
                    //End update issue period

                    $transaction_number = $stock_master->updateStockMasterTemp($master_id, $this->_request->comments);
                    $stock_detail->updateStockDetailTemp($master_id);
                    //Save Data in warehouse_data table
                    $warehouse_data->addReport($master_id, 2);

                    $this->view->msg = 'Stock has been issued successfully. Your voucher number is ';
                    $this->view->voucher = $transaction_number;
                    $this->view->master_id = $master_id;
                    $em->getConnection()->commit();
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
                            $item_unit = $em->getRepository("ItemUnits")->find($data['item_unit_id']);
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
                    $this->redirect("/stock/multiple-adjustment");
                    // For Data Show
                    $temp_stock_list = $stock_master->getTempStocksList();
                    if ($temp_stock_list) {
                        $form->transaction_date->setValue(date("d/m/Y h:i A", strtotime($temp_stock_list[0]['transaction_date'])));
                        $form->warehouse_name->setValue($temp_stock_list[0]['to_warehouse']);
                        $form->transaction_reference->setValue($temp_stock_list[0]['transaction_reference']);
                        $form->hdn_to_warehouse_id->setValue($temp_stock_list[0]['to_warehouse_id']);
                        $form->product->setValue($temp_stock_list[0]['to_warehouse_id']);
                        $arr_data['warehouse_name'] = $temp_stock_list[0]['to_warehouse'];

                        $form->activity_id->setValue($temp_stock_list[0]['activity_id']);

                        $arr_data['tempstocks'] = $temp_stock_list;
                        $form->makeFieldReadonly();
                    } else {
                        $form->transaction_date->setValue(date("d/m/Y h:i A"));
                    }
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                    if ($e->getMessage() == 'PLCD_QTY_GREATER_THAN_ISSUE_QTY') {
                        $this->view->status = false;
                        $this->view->msg = "Issue quantity should not greater than placed quantity!";
                    }
                }
            }

            $this->view->form = $form;
            $this->view->arr_data = $arr_data;

            if ($this->_request->type == 's') {
                $this->redirect("/stock/issue-search");
            }
        }

        $stock_master->form_values = $form_values;
        $temp_stock = $stock_master->getTempStock();
        if ($temp_stock) {
            $arr_data = array_merge($arr_data, $temp_stock);
        }


        if (!empty($temp_stock['stock_id'])) {
            $form->hdn_stock_id->setValue($temp_stock['stock_id']);
            $form->hdn_master_id->setValue($temp_stock['stock_id']);
        } elseif (!empty($stockid)) {
            $form->hdn_stock_id->setValue($stock_id);
            $form->hdn_master_id->setValue($stock_id);
        }

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

// Edit Issue Start
        if (!empty($this->_request->id)) {
            $detail_id = $this->_request->id;
            $issue = $this->_em->getRepository("StockDetail")->find($detail_id);
            $form->transaction_number->setValue($issue->getStockMaster()->getTransactionNumber());
            $form->transaction_date->setValue($issue->getStockMaster()->getTransactionDate()->format("d/m/Y h:i A"));
            $form->warehouse_name->setValue($issue->getStockMaster()->getToWarehouse()->getWarehouseName());
            $form->transaction_reference->setValue($issue->getStockMaster()->getTransactionReference());

            $arr_data['warehouse_name'] = $issue->getStockMaster()->getToWarehouse()->getWarehouseName();

            $form->item_id->setValue($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $form->fillBatchCombo($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $form->number->setValue($issue->getStockBatch()->getPkId());
            $form->vvm_stage->setValue($issue->getVvmStage());
            $form->quantity->setValue($issue->getQuantity());
            $av_qty = $issue->getStockBatch()->getQuantity() + ABS($issue->getQuantity());
            $form->available_quantity->setValue($av_qty);
            $form->expiry_date->setValue($issue->getStockBatch()->getExpiryDate()->format("d/m/Y"));
            $this->view->issueedit = true;
            $this->view->detail_id = $this->_request->id;
        }
// Edit Issue End
        $this->view->arr_data = $arr_data;
        $this->view->type = $this->_request->getParam("t", "issue");
        $this->view->wh_id = $this->_identity->getWarehouseId();

        $this->view->params = array("province" => $this->_identity->getProvinceId(), "district" => $this->_identity->getDistrictId());
        $this->view->role = $this->_identity->getRoleId();
    }

    /**
     * ajaxGetPlacementDetail
     */
    public function ajaxGetPlacementDetailAction() {
        $this->_helper->layout->disableLayout();
        $detail_id = $this->_request->detail_id;
        $stock_batch = new Model_StockBatch();
        $arr = explode('|', $this->_request->id);
        $this->view->array_data = $arr;
        $stock_batch->form_values['batch_id'] = $arr[0];
        $this->view->data = $stock_batch->getPlacementHistory();
        $this->view->id = $detail_id;
    }

    /**
     * Delete Batch Placement
     */
    public function deleteBatchPlacementAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost()) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $rowinfo = $this->_request->rowinfo;
                $qtydel = $this->_request->qtydel;
                $detail_id = $this->_request->id;

                $data = '';
                foreach ($qtydel as $key => $value) {
                    if (!empty($value)) {
                        $data .= $rowinfo[$key] . "|$value,";
                    }
                }

                $data = rtrim($data, ",");
                $placement_type = Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_PICK;
                $placement = new Model_Placements();
                $flag = $placement->updateStockPlacement($data, $placement_type);

                $stock_master = new Model_StockMaster();
                $stock_master->deleteReceive($detail_id);

                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

            if ($flag) {
                $this->redirect("/stock/receive-search");
                exit;
            }
        }
    }

    /**
     * Delete Stock Issue
     */
    public function deleteStockIssueAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost()) {
            $em = Zend_Registry::get('doctrine');
            $em->getConnection()->beginTransaction();
            try {
                $batch_id = $this->_request->b_id;
                $loc_id = $this->_request->loc_id;
                $vvm_stage_id = $this->_request->vvm_id;
                $qty_issued = $this->_request->qty_issued;
                $item_cat_id = $this->_request->item_cat_id;
                $detail_id = $this->_request->id;

                if ($item_cat_id == 1) {
                    $loc_type = Model_PlacementLocations::LOCATIONTYPE_CCM;
                } else {
                    $loc_type = Model_PlacementLocations::LOCATIONTYPE_NONCCM;
                }
                $placement_locations = $this->_em->getRepository("PlacementLocations")->findBy(array("locationId" => $loc_id, "locationType" => $loc_type));
                $placement_loc_id = $placement_locations[0]->getPkId();
                $placement_details = $batch_id . "|" . $placement_loc_id . "|" . $vvm_stage_id . "|" . $qty_issued;
                $placement_type = Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_P;
                $placement = new Model_Placements();
                $flag = $placement->updateStockPlacement($placement_details, $placement_type);

                $stock_detail = new Model_StockDetail();
                $stock_detail->deleteIssue($detail_id);

                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

            if ($flag) {
                $this->redirect("/stock/issue-search");
                exit;
            }
        }
    }

    /**
     * ajaxGetPlacementLocations
     */
    public function ajaxGetPlacementLocationsAction() {

        $this->_helper->layout->disableLayout();
        $detail_id = $this->_request->id;
        $p = $this->_request->p;

        $stock_master = new Model_StockMaster();
        $stock_master->form_values['$detail_id'] = $detail_id;
        $result = $stock_master->getIssueDetail();

        $item_category_id = $result['item_category_id'];
        if ($item_category_id == 1) {
            $cold_chain_loc = new Model_ColdChain();
            $ccm_locations = $cold_chain_loc->getLocationsName();
            $this->view->locations = $ccm_locations;
        } else {
            $non_ccm_loc = new Model_NonCcmLocations();
            $non_ccm_locations = $non_ccm_loc->getLocationsName();
            $this->view->locations = $non_ccm_locations;
        }
        $this->view->item_cat_id = $item_category_id;
        $this->view->result = $result;
        $this->view->p = $p;
        $this->view->detail_id = $detail_id;
    }

    /**
     * Print dry store locations(bins) list.
     */
    public function printLocationsAction() {
        $this->_helper->layout->setLayout('print');
        $non_ccm_location = new Model_NonCcmLocations();
        $this->view->result = $non_ccm_location->getLocationsName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
    }

    /**
     * UC wise Target Report
     */
    public function ucWiseTargetAction() {
        $form = new Form_UcWiseTarget();
        $locations = new Model_Locations();

        $this->view->province = "Punjab";
        $this->view->province_id = 1;
        $this->view->district = "";
        $this->view->year = "2016";

        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $locations->form_values = $res;

            if (!empty($res['province'])) {
                $form->province->setValue($res['province']);
                $locations->form_values['pk_id'] = $res['province'];
                $province_name = $locations->getLocationName();
                $this->view->province = $province_name;
                $this->view->province_id = $res['province'];
            }
            if (!empty($res['district'])) {
                $form->district->setValue($res['district']);
                $this->view->district = $res['district'];
            }
            if (!empty($res['year'])) {
                $form->year->setValue($res['year']);
                $this->view->year = $res['year'];
            }

            $locations->form_values['province_id'] = $res['province'];

            if (!empty($res['province'])) {
                $districts = $locations->getDistrictsByProvince();
                $districtarray[] = 'Select';
                foreach ($districts as $dist) {
                    $districtarray[$dist['pkId']] = $dist['locationName'];
                }
                $form->district->setMultiOptions($districtarray);
                $form->district->setValue($res['district']);
            }
        }

        $arr_data = $locations->getUcWiseTargetList();

        $this->view->arr_data = $arr_data;
        $this->view->form = $form;
    }

    public function ucWiseTargetPrintAction() {
        $this->_helper->layout->setLayout("print");
        $this->view->headTitle("UC wise Target List");

        $province = $this->_request->p;
        $district = $this->_request->d;
        $year = $this->_request->y;

        $locations = new Model_Locations();
        $locations->form_values['province'] = $province;
        $locations->form_values['district'] = $district;
        $locations->form_values['year'] = $year;

        $locations->form_values['pk_id'] = $province;
        $province_name = $locations->getLocationName();

        $arr_data = $locations->getUcWiseTargetList();
        $this->view->result = $arr_data;
        $this->view->province = $province;
        $this->view->province_name = $province_name;
        $this->view->district = $district;
        $this->view->year = $year;
        // Logged in Warehouse
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
    }

    public function ajaxGetDistrictsByProvinceAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->province_id) && !empty($this->_request->province_id)) {

            $locations = new Model_Locations();
            $locations->form_values['province_id'] = $this->_request->province_id;

            $districts = $locations->getDistrictsByProvince();

            $this->view->result = $districts;
        }
    }

    public function productPlacementDetailPrintAction() {
        $this->_helper->layout->setLayout("print");
        $this->view->headTitle("Product Placement");

        $detail_summary = $this->_request->param;
        $product_id = $this->_request->product;

        $placment = new Model_Placements();
        if ($detail_summary == 1) {
            $this->view->result = $placment->getProductPlacmentSummary($product_id);
        } else {
            $this->view->result = $placment->getProductPlacmentDetail($product_id);
        }

        $product_obj = new Model_ItemPackSizes();
        $product_obj->form_values['pk_id'] = $product_id;
        $product_name = $product_obj->getProductName();

        $this->view->detail_summary = $detail_summary;
        $this->view->product = $product_name;

        // Logged in Warehouse
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->username = $this->_identity->getUserName();
    }

    public function reverseLogisticsAction() {
        $form = new Form_ReverseLogistics();

        $stock_master = new Model_StockMaster();

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();

        try {
            if ($this->_request->isPost()) {
                $postdata = $this->_request->getPost();

                if (!empty($postdata['batch_id'])) {
                    $stock_master = new Model_StockMaster();
                    $stock_batch_warehouse = $this->_em->getRepository("StockBatchWarehouses")->find($postdata['batch_id']);
                    //echo $postdata['batch_id'];
                    //$stock_batch_placements = $this->_em->getRepository("Placements")->findBy(array('stockBatchWarehouse' => $postdata['batch_id']),array('vvmStage' => 'DESC'));
                    //echo $stock_batch_placements[0]->getVvmStage()->getPkId();
                    //exit;
                    //if (count($stock_batch_placements) > 0) {
                    //$vvm_stage = $stock_batch_placements[0]->getVvmStage()->getPkId();
                    //} else {
                    $vvm_stage = 1;
                    //}

                    $stock_batch = new Model_StockBatch();
                    $placement_locations = $stock_batch->getAllColdStores();

                    $data = array(
                        'adjustment_date' => date("d/m/Y"),
                        'ref_no' => 'Reverse Logistics',
                        'product' => $postdata['product'],
                        'batch_no' => $postdata['batch_id'],
                        'adjustment_type' => 19,
                        'quantity' => $postdata['quantity'],
                        'comments' => 'Reverse Logistics',
                        'item_unit_id' => $stock_batch_warehouse->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemUnit()->getPkId(),
                        'vvm_stage' => $vvm_stage,
                        'location_id' => $placement_locations[0]['placement_location_id'],
                        'purpose' => 1,
                        'is_received' => 1,
                        'from_wh_id' => $postdata['healthfacilities']
                    );
                    $stock_master->form_values = $data;
                    $stock_master->addAdjustment();

                    $em->getConnection()->commit();
                    $msg = "Received successfully!";
                    $msg_class = "alert-success";
                    $form->reset();
                } else {
                    $msg = "Batch number does not exists! Please contact us at support@lmis.gov.pk";
                    $msg_class = "alert-warning";
                }
            }
        } catch (Exception $ex) {
            $em->getConnection()->rollback();
            $em->close();
            App_FileLogger::info($ex);
        }

        $this->view->form = $form;
        $this->view->msg_class = $msg_class;
        $this->view->msg = $msg;
    }

    public function changeStoreAction() {
        $record = $this->_identity->getUserRecordId();

        if ($record != 'SUPERUSER') {
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(TRUE);
            echo "You don't have permission to access this page.";
        }
    }

    /**
     * Requisition Issue
     */
    public function requisitionIssueAction() {
        $stock_master = new Model_StockMaster();
        $stock_batch = new Model_StockBatch();
        $stock_detail = new Model_StockDetail();
        $warehouse_data = new Model_HfDataMaster();
        $form = new Form_StockRequisitionIssue();
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

        $demand_master_id = $this->_request->demandMasterId;
        $form_values['demand_master_id'] = $demand_master_id;
        $this->view->demand_master_id = $demand_master_id;
        $demandMaster = $this->_em->find('DemandMaster', $demand_master_id);
        $form->activity_id->setValue($demandMaster->getStakeholderActivity()->getPkId());
        $form->activity_id->setAttrib("disabled", "true");

        $form->warehouse_name->setValue($demandMaster->getFromWarehouse()->getWarehouseName());
        $form->hdn_to_date->setValue($demandMaster->getFromDate()->format("Y/m/d"));
        $form->warehouse_name->setAttrib("disabled", "true");
        $form->requisition_number->setValue($demandMaster->getRequisitionNumber());

        $form->requisition_number->setAttrib("disabled", "true");

        $month = $demandMaster->getFromDate()->format("m");
        $y = $demandMaster->getFromDate()->format("Y");
        if ($month <= 3) {
            $requsitionPeriod = "01/01/$y-01/03/$y";
        } else if ($month <= 6) {
            $requsitionPeriod = "01/04/$y-01/06/$y";
        } else if ($month <= 9) {
            $requsitionPeriod = "01/07/$y-01/09/$y";
        } else if ($month <= 12) {
            $requsitionPeriod = "01/10/$y-01/12/$y";
        }

        $form->issue_period->setValue($requsitionPeriod);
        $form->issue_period->setAttrib("disabled", "true");

        $warehouse_id = $demandMaster->getFromWarehouse()->getPkId();

        $demand = new Model_DemandMaster();
        $requisition_items = $demand->getRequisitionProducts($demand_master_id);

        $items[''] = "Select";
        if ($requisition_items) {
            foreach ($requisition_items as $item) {

                if ($demandMaster->getStockMaster() == NULL) {
                    $items[$item['itemPackSizeId']] = $item['itemName'] . '|' . number_format($item['approvedQuantity']) . '|0';
                } else {
                    $issued_qty = $demand->getRequisitionProductIssuedQty($demandMaster->getStockMaster()->getPkId(), $item['itemPackSizeId']);
                    $qty = '0';
                    if ($issued_qty['0']['1'] != NULL) {
                        $qty = $issued_qty['0']['1'];
                    }

                    $items[$item['itemPackSizeId']] = $item['itemName'] . '|' . number_format($item['approvedQuantity']) . '|' . $qty;
                }
            }
        }

        $form->item_id->setMultiOptions($items);

        $created_by = $this->_em->find('Users', $this->_userid);
        if (!empty($this->_request->hdn_stock_id)) {
            $stock_id = $this->_request->hdn_stock_id;
        }
        if (!empty($this->_request->hdn_master_id)) {
            $master_id = $this->_request->hdn_master_id;
        }


        if ($this->_request->isPost()) {

            if (!empty($master_id)) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    //Start update issue period
                    $array = $this->_request->getParams();
                    $stock_master->updateStockPeriod($master_id, $array);
                    //End update issue period

                    $transaction_number = $stock_master->updateStockMasterTemp($master_id, $this->_request->comments);
                    $stock_detail->updateStockDetailTemp($master_id);
                    //Save Data in warehouse_data table
                    $warehouse_data->addReport($master_id, 2);

                    $this->view->msg = 'Stock has been issued successfully. Your voucher number is ';
                    $this->view->voucher = $transaction_number;
                    $this->view->master_id = $master_id;

                    // Temp solution for deleting orphan records
                    $stock_detail->deleteOrphonRecord();

                    $em->getConnection()->commit();
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
                            $item_unit = $em->getRepository("ItemUnits")->find($data['item_unit_id']);
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
                        $data['activity_id'] = $demandMaster->getStakeholderActivity()->getPkId();
                        $data['demand_id'] = $demand_master_id;
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
                        // echo var_dump($batch_id);exit;
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
                    $this->redirect("/stock/requisition-issue?demandMasterId=" . $demand_master_id);
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                    if ($e->getMessage() == 'PLCD_QTY_GREATER_THAN_ISSUE_QTY') {
                        $this->view->status = false;
                        $this->view->msg = "Issue quantity should not greater than placed quantity!";
                    }
                }
            }

            $this->view->form = $form;
            $this->view->arr_data = $arr_data;

            if ($this->_request->type == 's') {
                $this->redirect("/stock/");
            }
        }

        $stock_master->form_values = $form_values;
        $temp_stock = $stock_master->getTempStock();
        if ($temp_stock) {
            $arr_data = array_merge($arr_data, $temp_stock);
        }
        $temp_stock_list = $stock_master->getTempStocksList();
        //var_dump($temp_stock_list);exit;

        if ($temp_stock_list) {
            $form->transaction_number->setValue($temp_stock_list[0]['transaction_number']);
            $form->transaction_date->setValue(date("d/m/Y h:i A", strtotime($temp_stock_list[0]['transaction_date'])));
            $form->warehouse_name->setValue($temp_stock_list[0]['to_warehouse']);
            $form->transaction_reference->setValue($temp_stock_list[0]['transaction_reference']);
            $form->hdn_to_warehouse_id->setValue($temp_stock_list[0]['to_warehouse_id']);
            $arr_data['warehouse_name'] = $temp_stock_list[0]['to_warehouse'];
            $form->activity_id->setValue($temp_stock_list[0]['activity_id']);

            $arr_data['tempstocks'] = $temp_stock_list;
            $form->makeFieldReadonly();
        } else {
            $form->transaction_date->setValue(date("d/m/Y h:i A"));
        }

        if (!empty($temp_stock['stock_id'])) {
            $form->hdn_stock_id->setValue($temp_stock['stock_id']);
            $form->hdn_master_id->setValue($temp_stock['stock_id']);
        } elseif (!empty($stockid)) {
            $form->hdn_stock_id->setValue($stock_id);
            $form->hdn_master_id->setValue($stock_id);
        }

        $this->view->form = $form;


        // Edit Issue Start
        if (!empty($this->_request->id)) {
            $detail_id = $this->_request->id;
            $issue = $this->_em->getRepository("StockDetail")->find($detail_id);
            $form->transaction_number->setValue($issue->getStockMaster()->getTransactionNumber());
            $form->transaction_date->setValue($issue->getStockMaster()->getTransactionDate()->format("d/m/Y h:i A"));
            $form->warehouse_name->setValue($issue->getStockMaster()->getToWarehouse()->getWarehouseName());
            $form->transaction_reference->setValue($issue->getStockMaster()->getTransactionReference());
            $arr_data['warehouse_name'] = $issue->getStockMaster()->getToWarehouse()->getWarehouseName();
            $form->item_id->setValue($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $form->fillBatchCombo($issue->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $form->number->setValue($issue->getStockBatch()->getPkId());
            $form->vvm_stage->setValue($issue->getVvmStage());
            $form->quantity->setValue($issue->getQuantity());
            $av_qty = $issue->getStockBatch()->getQuantity() + ABS($issue->getQuantity());
            $form->available_quantity->setValue($av_qty);
            $form->expiry_date->setValue($issue->getStockBatch()->getExpiryDate()->format("d/m/Y"));
            $this->view->issueedit = true;
            $this->view->detail_id = $this->_request->id;
        }
        // Edit Issue End
        $this->view->arr_data = $arr_data;
        $this->view->type = $this->_request->getParam("t", "issue");
        $this->view->wh_id = $this->_identity->getWarehouseId();

        $this->view->params = array("province" => $this->_identity->getProvinceId(), "district" => $this->_identity->getDistrictId());
        $this->view->role = $this->_identity->getRoleId();
    }

    /**
     * printMonthlyConsumption2
     */
    public function consumptionCommentsAction() {
        $this->_helper->layout->setLayout("popup");


        if (isset($this->_request->do) && !empty($this->_request->do)) {

            $temp = $this->_request->do;
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->temp = $temp;
            $arr_temp = $warehouse_data->monthlyConsumtionTemp();

            $this->view->rpt_date = $arr_temp['rpt_date'];
            $this->view->wh_id = $arr_temp['wh_id'];
        }
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            $hf_comments = new Model_HfDataMaster();
            $hf_comments->form_values = $data;
            $hf_comments->addConsumptionComments();
        }
    }

    /**
     * Priority Vaccines Distribution Detail Print
     */
    public function priorityVaccinesDistributionDetailAlertAction() {
        $this->_helper->layout->setLayout('print');
        $ips = new Model_ItemPackSizes();
        $this->view->products = $ips->getAllItemsNonDil();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->headTitle("Priority Vaccines Distribution");
    }

    /**
     * Update Placement
     */
    public function updateEtaAction() {
        $this->_helper->layout->disableLayout();
        $shipments = new Model_Shipments();

        $shipments->form_values = $this->_request->getPost();
        $shipments->updateEta();
        $this->_redirect("/stock/search-procurements");
    }

    public function ajaxSpaceStatusAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $item_pack_sizes = new Model_ItemPackSizes();

        $shipments = new Model_Shipments();
        $shipments->form_values = $form_values;
        $item_pack_sizes->form_values = $form_values;
        $pipeline_shipments_FR = $shipments->getPipelineShipments(15);
        $pipeline_shipments_CR = $shipments->getPipelineShipments(16);

        $stock_on_hand_FR = $shipments->getShipmentsStockOnHand(15);
        $stock_on_hand_CR = $shipments->getShipmentsStockOnHand(16);

        $issuance_demnds_FR = $shipments->getGetIssuanceDemands(15);
        $issuance_demnds_CR = $shipments->getGetIssuanceDemands(16);

        $item_cat = $item_pack_sizes->getItemCat();
        $this->view->item_cat = $item_cat;
        $this->view->stock_on_hand_FR = $stock_on_hand_FR;
        $this->view->stock_on_hand_CR = $stock_on_hand_CR;
        $this->view->pipeline_shipments_FR = $pipeline_shipments_FR;
        $this->view->pipeline_shipments_CR = $pipeline_shipments_CR;
        $this->view->issuance_demnds_FR = $issuance_demnds_FR;
        $this->view->issuance_demnds_CR = $issuance_demnds_CR;
    }

    /**
     * Delete Issue
     */
    public function deleteShipmentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;
            $id2 = $this->_request->id2;

            $em = Zend_Registry::get("doctrine");
            $em->getConnection()->beginTransaction();
            try {
                $shipments = new Model_Shipments();
                $shipments->deleteShipment($id, $id2);
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }

            $this->redirect("/stock/procurements");
        }
    }

    /**
     * Delete Issue
     */
    public function shipmentBatchDeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = $this->_request->id;

            $em = Zend_Registry::get("doctrine");
            $em->getConnection()->beginTransaction();
            try {
                $shipments = new Model_Shipments();
                $shipments->deleteShipmentBactch($id);
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollback();
                $em->close();
                App_FileLogger::info($e);
            }
            $shipment_id = $this->_request->shipment_id;

            $this->redirect("/stock/procurements-detail?id=$shipment_id");
        }
    }

    /**
     * Procurements
     */
    public function procurementsDetailAction() {

        $arr_data = array('transaction_number' => "",
            'stock_id' => "",
            'shipment_date' => date("d/m/Y"),
            'success' => $this->_request->success
        );

        $form = new Form_ReceiveSupplier();

        $form = new Form_Procurements();
        $form_manufacturer = new Form_AddManufacturer();
        $item_array = array();
        if ($this->_request->getParam('id')) {
            $form->hdn_shipment_id->setValue($this->_request->getParam('id'));
            $shipmnets = new Model_Shipments();
            $shipmnets->form_values['master_id'] = $this->_request->getParam('id');

            $stock_list = $shipmnets->getShipmentDetail();

            $form->transaction_number->setValue($stock_list[0]['transaction_number']);
            $shipmnets->form_values['shipment_id'] = $this->_request->getParam('id');
            $temp_stock_list1 = $shipmnets->getTempShipmnetProcductBatchListQuantity();
            if ($temp_stock_list1) {
                //     $available_qty = ABS($stock_list[0]['received_quantity'] - $temp_stock_list1[0]['quantity']);
            } else {
                //     $available_qty = $stock_list[0]['received_quantity'];
            }



            //  $form->available_quantity->setValue($available_qty);

            if ($stock_list) {

                foreach ($stock_list as $row_item) {
                    $item_array[$row_item['item_pack_size_id']] = $row_item['item_name'];
                }
            }
            if (!empty($item_array)) {
                $form->item_id->setMultiOptions($item_array);
            }
        }

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            if ($data['temp'] == 0) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
                    if ($this->_request->isPost()) {
                        $shipment = new Model_Shipments();
                        $data = $this->_request->getPost();
                        $shipment->form_values = $data;
                        $shipment->form_values['shipment_id'] = $this->_request->getParam('id');
                        $shipment->addProcurementsDetail();
                    }

                    $em->getConnection()->commit();
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                    App_FileLogger::info($e);
                }
            } else if ($data['temp'] == 1) {
                $shipment = new Model_Shipments();
                $data = $this->_request->getPost();
                $shipment->form_values = $data;
                $shipment->updateProcurementsDetail();
                $this->view->msg = 'Procurement Detail has been added successfully';
            }
        }

        sleep(3);
        $form_values = $this->_request->getPost();
        $shipmnets = new Model_Shipments();
        $shipmnets->form_values = $form_values;
        $shipmnets->form_values['shipment_id'] = $this->_request->getParam('id');
        $temp_stock_list = $shipmnets->getTempShipmnetProcductBatchList();

        if ($temp_stock_list) {
            $arr_data['tempstocks'] = $temp_stock_list;

            $form->hdn_master_id->setValue($temp_stock_list[0]['shipment_id']);
        }

        $form->shipment_date->setValue(date("d/m/Y h:i A"));

        $this->view->form = $form;
        $this->view->form_manufacturer = $form_manufacturer;
        $this->view->arr_data = $arr_data;


        $this->view->success = $this->_request->getParam('success', 0);
    }

    /**
     * pipelineConsignments
     */
    public function procurementsReceiveAction() {
        $start = 0;
        $end = $this->_request->getParam('counter', 10);


        $form = new Form_PipelineConsignments();
        $form->addRows($start, $end);


        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            if ($form_values['create_voucher'] == 1) {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {

                    $shipments = new Model_Shipments();
                    $shipments->form_values = $this->_request->getPost();
                    $result = $shipments->addShipmentsCreateVoucher();
                    $id = $this->_request->getParam('id');

                    $em->getConnection()->commit();
                    $this->redirect("/stock/procurements-receive?id=$id");
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                }
            } else {
                $em = Zend_Registry::get('doctrine');
                $em->getConnection()->beginTransaction();
                try {
// Create Voucher

                    $shipments = new Model_Shipments();

                    $shipments->form_values = $this->_request->getPost();
                    $shipments->form_values['counter'] = 3;
                    $result = $shipments->addShipmentsReceive();
                    if ($result) {
                        $this->view->success = "Placements details has been saved successfully.";
                    } else {
                        $this->view->error = "Please enter atleast one row data.";
                    }

                    $em->getConnection()->commit();
                } catch (Exception $e) {
                    $em->getConnection()->rollback();
                    $em->close();
                }
            }
        }




        if ($this->_request->getParam('id')) {
            $shipmnets = new Model_Shipments();
            $shipmnets->form_values['master_id'] = $this->_request->getParam('id');
            $this->view->shipment_id = $this->_request->getParam('id');

            $batch_list = $shipmnets->getShipmentBatchDetail();

            $this->view->batch_list = $batch_list;
            if ($batch_list[0]['item_category_id'] == 1) {
                $item = $this->_em->getRepository("ItemPackSizes")->find($batch_list[0]['item_pack_size_id']);
                $group = $item->getVvmGroup()->getPkId();

                $this->view->group = $group;
                $vvm_stages = $this->_em->getRepository("VvmGroups")->findBy(array("vvmGroupId" => $group));

                $array = array();
                $i = 0;
                foreach ($vvm_stages as $row) {
                    if ($group == 1) {
                        $value = $row->getVvmStage()->getPkId();
                    } else {
                        $value = $row->getVvmStage()->getVvmStageValue();
                    }

                    $array[$i]['pk_id'] = $row->getVvmStage()->getPkId();
                    $array[$i]['stage'] = $value;
                    $i++;
                }


                $this->view->item_vvm = json_encode($array);
            }
        }
        $cold_chain = new Model_ColdChain();
        $cold_chain->form_values = array('type_id' => '1,3');
        $result = $cold_chain->getColdchainByAssetShipments();
        $this->view->placments_location = $result;
        $non_ccm_loc = new Model_NonCcmLocations();
        $result_ccm = $non_ccm_loc->getDryStoreLocationsNameByShipments();
        $this->view->non_placments_location = $result_ccm;
        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;

        $shipmnets = new Model_Shipments();
        $shipmnets->form_values['shipment_id'] = $this->_request->getParam('id');

        $shipments_receive_voucher = $shipmnets->getShipmnetsReceiveVoucher();
        if ($shipments_receive_voucher) {
            $this->view->shipments_receive_voucher = $shipments_receive_voucher;
        }


        $shipments_receive = $shipmnets->getShipmnetsReceiveList();



        if ($shipments_receive) {
            $this->view->shipment_receive = $shipments_receive;
        }
    }

    /**
     * pipelineConsignments
     */
    public function ajaxReceivedRowsAction() {
        $this->_helper->layout->disableLayout();
        $start = 0;
        $end = $this->_request->getParam('counter', 10);


        $form = new Form_PipelineConsignments();
        $form->addRows($start, $end);


        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {
            if ($this->_request->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $pipeline_consignments = new Model_PipelineConsignments();
                    $pipeline_consignments->form_values = $form->getValues();
                    $pipeline_consignments->form_values['counter'] = $end;
                    $result = $pipeline_consignments->addPipelineConsignments();
                    if ($result) {
                        $this->view->success = "Pipeline Consignments has been received successfully.";
                        $form->reset();
                    } else {
                        $this->view->error = "Please enter atleast one row data.";
                    }
                }
            } else {
                $draft = new Model_PipelineConsignments();
                $saved_draft = $draft->getPipelineConsignmentsDraft();
                if ($saved_draft) {
                    $sum = count($saved_draft);
                    $end = $sum + 10;
                    $form->addRows($start, $end);

                    $form->from_warehouse_id->setValue($saved_draft[0]->getFromWarehouse()->getPkId());
                    $form->expected_arrival_date->setValue($saved_draft[0]->getExpectedArrivalDate()->format("d/m/Y"));
                    $form->reference_number->setValue($saved_draft[0]->getReferenceNumber());
                    $form->stakeholder_activity_id->setValue($saved_draft[0]->getStakeholderActivity()->getPkId());
                    $form->description->setValue($saved_draft[0]->getDescription());

                    for ($i = 0; $i < $sum; $i++) {
                        $rows = "rows" . $i;
                        $form->$rows->item_pack_size_id->setValue($saved_draft[$i]->getItemPackSize()->getPkId());
                        $form->populateManufacturer($saved_draft[$i]->getItemPackSize()->getPkId(), $rows);
                        $form->$rows->manufacturer_id->setValue($saved_draft[$i]->getManufacturer()->getPkId());
                        $form->$rows->vvm_type_id->setValue($saved_draft[$i]->getVvmType()->getPkId());
                        $form->$rows->batch_number->setValue($saved_draft[$i]->getBatchNumber());
                        $form->$rows->production_date->setValue($saved_draft[$i]->getProductionDate()->format("d/m/Y"));
                        $form->$rows->expiry_date->setValue($saved_draft[$i]->getExpiryDate()->format("d/m/Y"));
                        $form->$rows->unit_price->setValue($saved_draft[$i]->getUnitPrice());
                        $form->$rows->quantity->setValue($saved_draft[$i]->getQuantity());
                    }
                }
            }
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }



        $this->view->form = $form;
        $this->view->start = $start;
        $this->view->end = $end;
    }

    /**
     *
     */
    public function printShipmentAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Shipments Voucher");
        $shipments = new Model_Shipments();
        $shipments->form_values['pk_id'] = $this->_request->id;
        $result = $shipments->getTempShipmentsList();


        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Shipments Voucher";
        $this->view->department = $this->_identity->getUserDepartment();
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     *
     */
    public function printShipmentDetailAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Shipments Detail Voucher");
        $shipments = new Model_Shipments();
        $shipments->form_values['pk_id'] = $this->_request->id;
        $result = $shipments->getTempShipmentsDetailList();


        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Shipments Detail Voucher";
        $this->view->department = $this->_identity->getUserDepartment();
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     *
     */
    public function printShipmentReceiveAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Shipments Receive Voucher");
        $shipments = new Model_Shipments();
        $shipments->form_values['pk_id'] = $this->_request->id;
        $result = $shipments->getTempShipmentsReceiveList();


        $this->view->result = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Shipments Receive Voucher";
        $this->view->department = $this->_identity->getUserDepartment();
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     * print Receive
     */
    public function printGatepassListAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("GatePass List Voucher");
        $stock_id = $this->_request->id;


        $stock_master = new Model_StockMaster;
        $stock_master->form_values['pk_id'] = $stock_id;

        $result = $stock_master->getGatePassList();
        $this->view->result = $result;

        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->wh_id = $this->_identity->getWarehouseId();
        $this->view->print_title = "GatePass List Voucher";
        $this->view->print_serial = strtotime(date("Y-m-d H:i:s"));
    }

    /**
     * ajaxAddMoreRows
     */
    public function ajaxAddMoreRowsShipmentReceiveAction() {
        $this->_helper->layout->disableLayout();

        $start = $this->_request->getParam('start');
        $end = $this->_request->getParam('end');
        $end1 = $this->_request->getParam('end1');
        $shipment_id = $this->_request->getParam('item_id');

        $this->view->end1 = $end1;
        $this->view->item_id = $shipment_id;
        $this->view->start = $start;
        $this->view->end = $end;

        $shipment_product_batch = $this->_em->getRepository("ShipmentProductBatch")->find($shipment_id);

        $iem_pack_size_id = $this->_em->getRepository("ItemPackSizes")->find($shipment_product_batch->getItemPackSize()->getPkId());
        $this->view->item_category_id = $iem_pack_size_id->getItemCategory()->getPkId();


        if ($iem_pack_size_id->getItemCategory()->getPkId() == 1) {
            $item = $this->_em->getRepository("ItemPackSizes")->find($shipment_product_batch->getItemPackSize()->getPkId());
            $group = $item->getVvmGroup()->getPkId();

            $this->view->group = $group;
            $vvm_stages = $this->_em->getRepository("VvmGroups")->findBy(array("vvmGroupId" => $group));

            $array = array();
            $i = 0;
            foreach ($vvm_stages as $row) {
                if ($group == 1) {
                    $value = $row->getVvmStage()->getPkId();
                } else {
                    $array[$i]['pk_id'] = $row->getVvmStage()->getPkId();
                    $array[$i]['stage'] = $value;
                    $value = $row->getVvmStage()->getVvmStageValue();
                }

                $array[$i]['pk_id'] = $row->getVvmStage()->getPkId();
                $array[$i]['stage'] = $value;
                $i++;
            }

            $cold_chain = new Model_ColdChain();
            $cold_chain->form_values = array('type_id' => '1,3');
            $result = $cold_chain->getColdchainByAssetShipments();
            $this->view->item_vvm = $array;
            $this->view->placments_location = json_decode($result, true);
        }
        $non_ccm_loc = new Model_NonCcmLocations();
        $result_ccm = $non_ccm_loc->getDryStoreLocationsNameByShipments();

        $this->view->non_placments_location = json_decode($result_ccm, true);
    }

}
