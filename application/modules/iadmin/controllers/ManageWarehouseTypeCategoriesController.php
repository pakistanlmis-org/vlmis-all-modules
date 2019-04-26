<?php

/**
 * Iadmin_ManageWarehouseTypeCategoriesController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Muhammad Imran <muhammad_imran@pk.jsi.com>
 * @version    2.5.1
 */
/**
 * This Controller Manages Warehouse Type Categories
 */
class Iadmin_ManageWarehouseTypeCategoriesController extends App_Controller_Base {

    /**
     * This method Searches Warehouse Type Category 
     */
    public function indexAction() {
        $form = new Form_Iadmin_WarehouseTypeCategory();
        $geo_indicators = new Model_GeoIndicators();
        $warehouse_type_categories = new Model_WarehouseTypeCategories();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $wh_category_name = $form->getValue('wh_category_name');

                if (!empty($wh_category_name)) {
                    $warehouse_type_categories->form_values['wh_category_name'] = $wh_category_name;
                }
            }
            $form->wh_category_name->setValue($wh_category_name);
        }

        $result = $warehouse_type_categories->getAllWarehouseTypeCategories();

        $this->view->form = $form;
        $this->view->result = $result;
    }

    /**
     * This method Adds Warehouse Type Category 
     */
    public function addWarehouseTypeCategoryAction() {
        $form = new Form_Iadmin_WarehouseTypeCategory();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $warehouse_type_categories = new Model_WarehouseTypeCategories();
            $wh_category_name = $form->getValue('wh_category_name');

            if (!empty($wh_category_name)) {
                $warehouse_type_categories->form_values['wh_category_name'] = $wh_category_name;
                $warehouse_type_categories->addWarehouseTypeCategory();
            }
        }
        $this->_redirect("/iadmin/manage-warehouse-type-categories/index");
    }

    /**
     * This method Updates Warehouse Type Category 
     */
    public function updateWarehouseTypeCategoryAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form = new Form_Iadmin_WarehouseTypeCategory();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $warehouse_type_categories = new Model_WarehouseTypeCategories();
            $wh_category_name = $form->getValue('wh_category_name');
            $wh_category_id = $form->getValue('wh_category_id');
            $warehouse_type_categories->form_values['wh_category_name'] = $wh_category_name;
            $warehouse_type_categories->form_values['wh_category_id'] = $wh_category_id;
            $warehouse_type_categories->updateWarehouseTypeCategory();
        }
        $this->_redirect("/iadmin/manage-warehouse-type-categories/index");
    }

    /**
     * This method Checks Warehouse Type Category 
     */
    public function checkWarehouseTypeCategoryAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();


        $warehouse_type_categories = new Model_WarehouseTypeCategories();

        if ($form_values['wh_category_name'] !== $form_values['wh_category_name_hidden']) {
            $warehouse_type_categories->form_values['wh_category_name'] = $form_values['wh_category_name'];
            $result = $warehouse_type_categories->checkWarehouseTypeCategory();
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
     * This method retrieves Warehouse Type Category for Edit
     */
    public function ajaxWarehouseTypeCategoryEditAction() {
      
        $this->_helper->layout->disableLayout();
        $form = new Form_Iadmin_WarehouseTypeCategory();
        $warehouse_type_category_id = $this->_request->getParam('warehouse_type_category_id');
        $warehouse_type_categories = new Model_WarehouseTypeCategories();
        $warehouse_type_categories->form_values['warehouse_type_category_id'] = $warehouse_type_category_id;
        $result = $warehouse_type_categories->getWarehouseTypeCategoryById();
        $wh_category_name = $result[0]['category_name'];
        $form->wh_category_name->setValue($wh_category_name);
        $form->wh_category_name_hidden->setValue($wh_category_name);
        $form->wh_category_id->setValue($warehouse_type_category_id);
        $this->view->form = $form;
    }

}
