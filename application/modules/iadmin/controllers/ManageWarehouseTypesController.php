<?php

/**
 * Iadmin_ManageWarehouseTypesController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Muhammad Imran <muhammad_imran@pk.jsi.com>
 * @version    2.5.1
 */
/**
 * This Controller Manages Warehouse Types
 */
class Iadmin_ManageWarehouseTypesController extends App_Controller_Base {

    /**
     * This method searches Warehouse Type 
     */
    public function indexAction() {
        $form = new Form_Iadmin_WarehouseType();

        $warehouse_types = new Model_WarehouseTypes();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $warehouse_type_name = $form->getValue('warehouse_type_name');
                if (!empty($warehouse_type_name)) {
                    $warehouse_types->form_values['warehouse_type_name'] = $warehouse_type_name;
                }
            }
            $form->warehouse_type_name->setValue($warehouse_type_name);
        }

        $result = $warehouse_types->getAllWarehouseTypes();

        $this->view->form = $form;
        $this->view->result = $result;
    }

    /**
     * This method Adds Warehouse Type 
     */
    public function addWarehouseTypeAction() {
        $form = new Form_Iadmin_WarehouseType();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $warehouse_types = new Model_WarehouseTypes();
            $warehouse_type_name = $form->getValue('warehouse_type_name');
            $warehouse_type_category = $form->getValue('warehouse_type_category');
            $geo_level = $form->getValue('geo_level');
            $resupply_interval = $form->getValue('resupply_interval');
            $reserved_stock = $form->getValue('reserved_stock');
            $usage_percentage = $form->getValue('usage_percentage');
            $list_rank = $form->getValue('list_rank');

            if (!empty($warehouse_type_name)) {
                $warehouse_types->form_values['warehouse_type_name'] = $warehouse_type_name;
                $warehouse_types->form_values['warehouse_type_category'] = $warehouse_type_category;
                $warehouse_types->form_values['geo_level'] = $geo_level;
                $warehouse_types->form_values['resupply_interval'] = $resupply_interval;
                $warehouse_types->form_values['reserved_stock'] = $reserved_stock;
                $warehouse_types->form_values['usage_percentage'] = $usage_percentage;
                $warehouse_types->form_values['list_rank'] = $list_rank;
                $warehouse_types->addWarehouseType();
            }
        }
        $this->_redirect("/iadmin/manage-warehouse-types/index");
    }

    /**
     * This method Updates Warehouse Type  
     */
    public function updateWarehouseTypeAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form = new Form_Iadmin_WarehouseType();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $warehouse_types = new Model_WarehouseTypes();
            $warehouse_type_id = $form->getValue('warehouse_type_id');
            $warehouse_type_name = $form->getValue('warehouse_type_name');
            $warehouse_type_category = $form->getValue('warehouse_type_category');
            $geo_level = $form->getValue('geo_level');
            $resupply_interval = $form->getValue('resupply_interval');
            $reserved_stock = $form->getValue('reserved_stock');
            $usage_percentage = $form->getValue('usage_percentage');
            $list_rank = $form->getValue('list_rank');

            $warehouse_types->form_values['warehouse_type_id'] = $warehouse_type_id;
            $warehouse_types->form_values['warehouse_type_name'] = $warehouse_type_name;
            $warehouse_types->form_values['warehouse_type_category'] = $warehouse_type_category;
            $warehouse_types->form_values['geo_level'] = $geo_level;
            $warehouse_types->form_values['resupply_interval'] = $resupply_interval;
            $warehouse_types->form_values['reserved_stock'] = $reserved_stock;
            $warehouse_types->form_values['usage_percentage'] = $usage_percentage;
            $warehouse_types->form_values['list_rank'] = $list_rank;
            
            $warehouse_types->updateWarehouseType();
        }
        $this->_redirect("/iadmin/manage-warehouse-types/index");
    }

    /**
     * This method Checks Warehouse Type  
     */
    public function checkWarehouseTypeAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $warehouse_types = new Model_WarehouseTypes();

        if ($form_values['warehouse_type_name'] !== $form_values['warehouse_type_name_hidden']) {
            $warehouse_types->form_values['warehouse_type_name'] = $form_values['warehouse_type_name'];
            $result = $warehouse_types->checkWarehouseType();
            if (count($result) > 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        } else {
            echo 'true';
        }
    }

    /**
     * This method retrieves Warehouse Type for Edit
     */
    public function ajaxWarehouseTypeEditAction() {

        $this->_helper->layout->disableLayout();
        $form = new Form_Iadmin_WarehouseType();
        $warehouse_type_id = $this->_request->getParam('warehouse_type_id');
        $warehouse_types = new Model_WarehouseTypes();
        $warehouse_types->form_values['warehouse_type_id'] = $warehouse_type_id;
        $result = $warehouse_types->getWarehouseTypeById();

        $warehouse_type_name = $result[0]['warehouse_type_name'];
        $resupply_interval = $result[0]['resupply_interval'];
        $reserved_stock = $result[0]['reserved_stock'];
        $usage_percentage = $result[0]['usage_percentage'];
        $list_rank = $result[0]['list_rank'];
        $geo_level_id = $result[0]['geo_level_id'];
        $category_id = $result[0]['category_id'];

        $form->warehouse_type_name->setValue($warehouse_type_name);
        $form->warehouse_type_name_hidden->setValue($warehouse_type_name);
        $form->warehouse_type_id->setValue($warehouse_type_id);

        $form->resupply_interval->setValue($resupply_interval);
        $form->reserved_stock->setValue($reserved_stock);
        $form->usage_percentage->setValue($usage_percentage);
        $form->list_rank->setValue($list_rank);
        $form->geo_level->setValue($geo_level_id);
        $form->warehouse_type_category->setValue($category_id);

        $this->view->form = $form;
    }

}
