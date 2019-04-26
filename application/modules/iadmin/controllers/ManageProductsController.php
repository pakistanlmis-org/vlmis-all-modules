<?php

/**
 * Iadmin_ManageProductsController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Products
 */
class Iadmin_ManageProductsController extends App_Controller_Base {

    /**
     * This method searches products
     */
    public function productsAction() {
        $form = new Form_Iadmin_Products();

        $item_pack_size = new Model_ItemPackSizes();
        $params = array();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $item_name = $form->getValue('item_name');
                $item_category = $form->getValue('item_category');
                $item_unit = $form->getValue('item_unit');
                $item = $form->getValue('item');
                if (!empty($item_name)) {
                    $item_pack_size->form_values['item_name'] = $item_name;
                    $params['item_name'] = $item_name;
                }
                if (!empty($item_category)) {
                    $item_pack_size->form_values['item_category'] = $item_category;
                    $params['item_category'] = $item_category;
                }
                if (!empty($item_unit)) {
                    $item_pack_size->form_values['item_unit'] = $item_unit;
                    $params['item_unit'] = $item_unit;
                }
                if (!empty($item)) {
                    $item_pack_size->form_values['item'] = $item;
                    $params['item'] = $item_unit;
                }
            }
            $form->item_name->setValue($item_name);
            $form->item_category->setValue($item_category);
            $form->item_unit->setValue($item_unit);
            $form->item->setValue($item);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "stakeholder");
        $item_pack_size->form_values = $this->_request->getParams();
        $form->item_name->setValue($this->_getParam('item_name'));
        $form->item_category->setValue($this->_getParam('item_category'));
        $form->item_unit->setValue($this->_getParam('item_unit'));
        $form->item->setValue($this->_getParam('item'));

        $result = $item_pack_size->getAllProducts();

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->pagination_params = $params;
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method searches Item Categories
     */
    public function itemCategoriesAction() {
        $form = new Form_Iadmin_Products();

        $item_category = new Model_ItemCategories();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $item_category_name = $form->getValue('item_category_name');

                if (!empty($item_category_name)) {
                    $item_category->form_values['item_category_name'] = $item_category_name;
                }
            }
            $form->item_category_name->setValue($item_category_name);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "stakeholder");

        $result = $item_category->getItemCategories();

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method searches Item Units
     */
    public function itemUnitsAction() {
        $form = new Form_Iadmin_Products();

        $item_units = new Model_ItemUnits();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $item_unit_name = $form->getValue('item_unit_name');

                if (!empty($item_unit_name)) {
                    $item_units->form_values['item_unit_name'] = $item_unit_name;
                }
            }
            $form->item_unit_name->setValue($item_unit_name);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "stakeholder");

        $result = $item_units->getItemUnits();

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method searches Item
     */
    public function itemAction() {
        $form = new Form_Iadmin_Products();

        $items = new Model_Items();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $item_description = $form->getValue('item_description');

                if (!empty($item_description)) {
                    $items->form_values['item_description'] = $item_description;
                }
            }
            $form->item_description->setValue($item_description);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "stakeholder");

        $result = $items->getAllItems();

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method Adds Product
     */
    public function addProductAction() {
        $form = new Form_Iadmin_Products();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $item_pack_size = new ItemPackSizes();
            $item_pack_size->setItemName($form->item_name->getValue());
            $item_pack_size->setDescription($form->description->getValue());
            $item_pack_size->setListRank($form->list_rank->getValue());
            $item_unit = $this->_em->getRepository('ItemUnits')->find($form->item_unit->getValue());
            $item_pack_size->setItemUnit($item_unit);
            $item_pack_size->setStatus(1);
            $items = $this->_em->getRepository('Items')->find($form->item->getValue());
            $item_pack_size->setItem($items);

            $item_category = $this->_em->getRepository('ItemCategories')->find($form->item_category->getValue());
            $item_pack_size->setItemCategory($item_category);
            if ($form->item_category->getValue() == '1') {

                $item_pack_size->setNumberOfDoses($form->number_of_doses->getValue());
            }

            $created_by = $this->_em->find('Users', $this->_userid);
            $item_pack_size->setCreatedBy($created_by);
            $item_pack_size->setCreatedDate(App_Tools_Time::now());
            $item_pack_size->setModifiedBy($created_by);
            $item_pack_size->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_pack_size);
            $this->_em->flush();

            $itemPackSize = $item_pack_size->getPkId();
            $item_scedule = new ItemSchedule();
            $item_pack_size_id = $this->_em->getRepository('ItemPackSizes')->find($itemPackSize);
            $item_scedule->setItemPackSize($item_pack_size_id);
            $item_scedule->setNumberOfDoses($form->total_doses->getValue());
            $stakeholder_activity = $this->_em->getRepository('StakeholderActivities')->find($form->purpose->getValue());
            $item_scedule->setStakeholderActivity($stakeholder_activity);
            $item_scedule->setStartingNo($form->starting_dose->getValue());
            $locations = $this->_em->getRepository('Locations')->find($form->province->getValue());
            $item_scedule->setProvince($locations);

            $created_by = $this->_em->find('Users', $this->_userid);
            $item_scedule->setCreatedBy($created_by);
            $item_scedule->getCreatedDate(App_Tools_Time::now());
            $item_scedule->setModifiedBy($created_by);
            $item_scedule->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_scedule);
            $this->_em->flush();

            $item_activities = new ItemActivities();
            $item_pack_size_id = $this->_em->getRepository('ItemPackSizes')->find($itemPackSize);
            $item_activities->setItemPackSize($item_pack_size_id);

            $stakeholder_activity = $this->_em->getRepository('StakeholderActivities')->find($form->purpose->getValue());
            $item_activities->setStakeholderActivity($stakeholder_activity);
            $created_by = $this->_em->find('Users', $this->_userid);
            $item_activities->setCreatedBy($created_by);
            $item_activities->getCreatedDate(App_Tools_Time::now());
            $item_activities->setModifiedBy($created_by);
            $item_activities->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_activities);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/products");
    }

    /**
     * This method Adds Item Category
     */
    public function addItemCategoryAction() {
        $form = new Form_Iadmin_Products();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            // Default status is 1.
            $status = '1';
            $item_category = new ItemCategories();
            $item_category->setItemCategoryName($form->item_category_name->getValue());
            $user_id = $this->_em->getRepository('Users')->find($this->_userid);
            $item_category->setCreatedBy($user_id);
            $item_category->setModifiedBy($user_id);
            $item_category->setStatus($status);
            $item_category->setCreatedDate(App_Tools_Time::now());
            $item_category->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_category);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/item-categories");
    }

    /**
     * This method Updates Item Category
     */
    public function updateItemCategoryAction() {
        $form = new Form_Iadmin_Products();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $form_values = $this->_request->getPost();
            $item_category = $this->_em->getRepository("ItemCategories")->find($form_values['item_category_id']);

            $item_category->setItemCategoryName($form_values['item_category_name']);

            $modifiedBy = $this->_em->getRepository('Users')->find($this->_userid);
            $item_category->setModifiedBy($modifiedBy);
            $item_category->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_category);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/item-categories");
    }

    /**
     * This method Adds Item Unit
     */
    public function addItemUnitAction() {
        $form = new Form_Iadmin_Products();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $items = new ItemUnits();
            $items->setItemUnitName($form->item_unit_name->getValue());
            $items->setStatus('1');
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $items->setCreatedBy($createdBy);
            $items->setModifiedBy($createdBy);
            $items->setCreatedDate(App_Tools_Time::now());
            $items->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($items);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/item-units");
    }

    /**
     * This method Updates Item Unit
     */
    public function updateItemUnitAction() {
        $form = new Form_Iadmin_Products();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $form_values = $this->_request->getPost();
            $items = $this->_em->getRepository("ItemUnits")->find($form_values['item_unit_id']);

            $items->setItemUnitName($form->item_unit_name->getValue());
            $modifiedBy = $this->_em->getRepository('Users')->find($this->_userid);
            $items->setModifiedBy($modifiedBy);
            $items->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($items);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/item-units");
    }

    /**
     * This method Adds Item
     */
    public function addItemAction() {
        $form = new Form_Iadmin_Products();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $items = new Items();
            $items->setDescription($form->item_description->getValue());
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $items->setCreatedBy($createdBy);
            $items->setModifiedBy($createdBy);
            $items->setCreatedDate(App_Tools_Time::now());
            $items->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($items);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/item");
    }

    /**
     * This method Updates Item
     */
    public function updateItemAction() {
        $form = new Form_Iadmin_Products();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $form_values = $this->_request->getPost();
            $items = $this->_em->getRepository("Items")->find($form_values['item_group_id']);

            $items->setDescription($form_values['item_description']);

            $modifiedBy = $this->_em->getRepository('Users')->find($this->_userid);
            $items->setModifiedBy($modifiedBy);
            $items->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($items);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/item");
    }

    /**
     * This method retrieves Product information for Edit
     */
    public function ajaxProductEditAction() {
        $this->_helper->layout->setLayout("ajax");

        $item = $this->_em->find('ItemPackSizes', $this->_request->getParam('item_id'));
        $form = new Form_Iadmin_Products();
        $form->item_name_hidden->setValue($item->getItemName());
        $form->item_name->setValue($item->getItemName());
        $form->list_rank->setValue($item->getListRank());
        $form->item_unit->setValue($item->getItemUnit()->getPkId());
        $form->item_category->setValue($item->getItemCategory()->getPkId());
        $form->description->setValue($item->getDescription());
        $form->item->setValue($item->getItem()->getPkId());
        $form->item_id->setValue($item->getPkId());
        $form->status->setValue($item->getStatus());
        if ($item->getItemCategory()->getPkId() == 1 || $item->getItemCategory()->getPkId() == 4) {
            $form->number_of_doses->setValue($item->getNumberOfDoses());
        }

        $item_schedule = $this->_em->getRepository('ItemSchedule')->findBy(array('itemPackSize' => $this->_request->getParam('item_id')));

        $form->total_doses->setValue($item_schedule[0]->getNumberOfDoses());
        $form->starting_dose->setValue($item_schedule[0]->getStartingNo());
        $form->purpose->setValue($item_schedule[0]->getStakeholderActivity()->getPkId());
        $form->province->setValue($item_schedule[0]->getProvince()->getPkId());
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/iadmin/manage-products/ajax-products.js');
        $this->view->form = $form;
    }

    /**
     * This method retrieves Item Categories for Edit
     */
    public function ajaxItemCategoriesEditAction() {
        $this->_helper->layout->disableLayout();
        $item_category = $this->_em->find('ItemCategories', $this->_request->getParam('item_category_id'));
        $form = new Form_Iadmin_Products();
        $form->item_category_name->setValue($item_category->getItemCategoryName());
        $form->item_category_name_hidden->setValue($item_category->getItemCategoryName());

        $form->item_category_id->setValue($item_category->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method retrieves Item unit for Edit
     */
    public function ajaxItemUnitsEditAction() {
        $this->_helper->layout->disableLayout();
        $item_unit = $this->_em->find('ItemUnits', $this->_request->getParam('item_unit_id'));
        $form = new Form_Iadmin_Products();
        $form->item_unit_name->setValue($item_unit->getItemUnitName());
        $form->item_unit_name_hidden->setValue($item_unit->getItemUnitName());

        $form->item_unit_id->setValue($item_unit->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method retrieves Item for Edit
     */
    public function ajaxItemEditAction() {
        $this->_helper->layout->disableLayout();
        $item_group = $this->_em->find('Items', $this->_request->getParam('item_id'));
        $form = new Form_Iadmin_Products();
        $form->item_description->setValue($item_group->getDescription());
        $form->item_description_hidden->setValue($item_group->getDescription());

        $form->item_group_id->setValue($item_group->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method Updates Product
     */
    public function updateProductAction() {
        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $item_pack_size = $this->_em->getRepository("ItemPackSizes")->find($form_values['item_id']);
            $item_pack_size->setItemName($form_values['item_name']);
            $item_pack_size->setDescription($form_values['description']);
            $item_pack_size->setListRank($form_values['list_rank']);
            $item_unit = $this->_em->getRepository('ItemUnits')->find($form_values['item_unit']);
            $item_pack_size->setItemUnit($item_unit);
            $item_pack_size->setStatus($form_values['status']);
            $items = $this->_em->getRepository('Items')->find($form_values['item']);
            $item_pack_size->setItem($items);

            $item_category = $this->_em->getRepository('ItemCategories')->find($form_values['item_category']);
            $item_pack_size->setItemCategory($item_category);
            if ($form_values['item_category'] == '1') {
                $item_pack_size->setNumberOfDoses($form_values['number_of_doses']);
            }

            $created_by = $this->_em->find('Users', $this->_userid);
            $item_pack_size->setModifiedBy($created_by);
            $item_pack_size->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_pack_size);
            $this->_em->flush();



            $item_scedule1 = $this->_em->getRepository('ItemSchedule')->findBy(array('itemPackSize' => $form_values['item_id']));

            $item_scedule = $this->_em->getRepository('ItemSchedule')->find($item_scedule1[0]->getPkId());
            $item_pack_size_id = $this->_em->getRepository('ItemPackSizes')->find($form_values['item_id']);
            $item_scedule->setItemPackSize($item_pack_size_id);
            $item_scedule->setNumberOfDoses($form_values['total_doses']);
            $stakeholder_activity = $this->_em->getRepository('StakeholderActivities')->find($form_values['purpose']);
            $item_scedule->setStakeholderActivity($stakeholder_activity);
            $item_scedule->setStartingNo($form_values['starting_dose']);
            $locations = $this->_em->getRepository('Locations')->find($form_values['province']);
            $item_scedule->setProvince($locations);




            $item_scedule->setModifiedBy($created_by);
            $item_scedule->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_scedule);
            $this->_em->flush();

            $item_activities1 = $this->_em->getRepository('ItemActivities')->findBy(array('itemPackSize' => $form_values['item_id']));

            $item_activities = $this->_em->getRepository('ItemActivities')->find($item_activities1[0]->getPkId());


            $item_pack_size_id = $this->_em->getRepository('ItemPackSizes')->find($form_values['item_id']);
            $item_activities->setItemPackSize($item_pack_size_id);

            $stakeholder_activity = $this->_em->getRepository('StakeholderActivities')->find($form_values['purpose']);
            $item_activities->setStakeholderActivity($stakeholder_activity);

            $item_activities->setModifiedBy($created_by);
            $item_activities->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($item_activities);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/products");
    }

    /**
     * This method Checks Product whether it is allready exists
     */
    public function checkProductAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $item_pack_sizes = new Model_ItemPackSizes();
        if ($form_values['item_name'] !== $form_values['item_name_hidden']) {
            $item_pack_sizes->form_values = $form_values;
            $result = $item_pack_sizes->checkProducts();
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
     * This method Checks Item whether it is allready exists
     */
    public function checkItemAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $items = new Model_Items();
        if ($form_values['item_description'] !== $form_values['item_description_hidden']) {
            $items->form_values = $form_values['item_description'];
            $result = $items->checkItem();
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
     * This method Checks Item Unit whether it is allready exists
     */
    public function checkItemUnitAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $items = new Model_ItemUnits();
        if ($form_values['item_unit_name'] !== $form_values['item_unit_name_hidden']) {
            $items->form_values = $form_values['item_unit_name'];
            $result = $items->checkItemUnit();
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
     * This method Checks Item Category whether it is allready exists
     */
    public function checkItemCategoryAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $items = new Model_ItemCategories();
        if ($form_values['item_category_name'] !== $form_values['item_category_name_hidden']) {
            $items->form_values = $form_values['item_category_name'];
            $result = $items->checkItemCategory();
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
     * This method searches vvm Type
     */
    public function vvmTypeAction() {
        $form = new Form_Iadmin_VvmTypeSearch();
        $form_add = new Form_Iadmin_VvmTypeAdd();

        $vvm_type = new Model_VvmTypes();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $vvm_type_name = $form->getValue('vvm_type_name');
                $status = $form->getValue('status');

                if (!empty($vvm_type_name)) {
                    $vvm_type->form_values['vvmTypeName'] = $vvm_type_name;
                }
                if (!empty($status)) {
                    $vvm_type->form_values['status'] = $status;
                }
            }
            $form->vvm_type_name->setValue($vvm_type_name);
            $form->status->setValue($status);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "vvm_type_name");

        $result = $vvm_type->getAllVvmTypes($order, $sort);

        $paginator = false;

        if ($result) {
            //Paginate the contest results
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
        }

        $this->view->form = $form;
        $this->view->form_add = $form_add;
        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method adds Vvm Type
     */
    public function addVvmTypeAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {

            $form_values = $this->_request->getPost();

            $vvm_type = new VvmTypes();
            $vvm_type->setVvmTypeName($form_values['vvm_type_name']);
            $vvm_type->setStatus($form_values['status']);
            $user_id = $this->_em->find('Users', $this->_userid);
            $vvm_type->setCreatedBy($user_id);
            $vvm_type->setModifiedBy($user_id);
            $vvm_type->setCreatedDate(App_Tools_Time::now());
            $vvm_type->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($vvm_type);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/vvm-type?success=1");
    }

    /**
     * This method adds Vvm Group
     */
    public function addVvmGroupAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {

            $vvm_group = new VvmGroups();

            $form_values = $this->_request->getPost();

            foreach ($form_values['stages'] as $stages) {
                $vvm_group = new Model_VvmGroups();
                $vvm_group->form_values = array(
                    "vvm_stage_id" => $stages,
                    "vvm_group_id" => $form_values['vvm_group_id']
                );
                $vvm_group->addVvmGroup();
            }
        }
        $this->_redirect("/iadmin/manage-products/vvm-groups?success=1");
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->disableLayout();
        $vvm_type_id = $this->_request->getParam('vvm_type_id', '');

        $vvmType = $this->_em->find('VvmTypes', $vvm_type_id);
        $form = new Form_Iadmin_VvmTypeAdd();

        $form->vvm_type_name->setValue($vvmType->getVvmTypeName());
        $form->vvm_type_name_hidden->setValue($vvmType->getVvmTypeName());
        $form->vvm_type_id->setValue($vvmType->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method retrieves VVM Group for edit
     */
    public function ajaxEditVvmGroupAction() {
        $this->_helper->layout->disableLayout();
        $vvm_group_id = $this->_request->getParam('vvm_group_id', '');
        $form_values = $this->_request->getPost();
        $form = new Form_Iadmin_VvmGroups();

        $form->vvm_group_id_update->setValue($vvm_group_id);
        $form->vvm_group_id_hidden->setValue($vvm_group_id);

        $this->view->form = $form;
    }

    /**
     * This method updates Vvm Type
     */
    public function updateVvmTypeAction() {

        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $vvmType = $this->_em->getRepository("VvmTypes")->find($form_values['vvm_type_id']);
            $vvmType->setVvmTypeName($form_values['vvm_type_name']);


            $item_pack = $this->_em->getRepository('ItemPackSizes')->find($form_values['item_pack_size_id']);
            $vvmType->setItemPackSize($item_pack);


            $created_by = $this->_em->find('Users', $this->_userid);
            $vvmType->setModifiedBy($created_by);
            $vvmType->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($vvmType);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/vvm-type?success=2");
    }

    /**
     * This method updates Vvm Group
     */
    public function updateVvmGroupAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();

            try {
                $vvm_group_id = $form_values['vvm_group_id_update'];

                $vvm_group1 = new Model_VvmGroups();
                $vvm_group1->form_values = array(
                    "vvm_group_id" => $vvm_group_id
                );

                $vvm_group1->deleteVvmGroup();
            } catch (Exception $e) {
                //$this->_redirect("/iadmin/manage-products/vvm-groups?success=4");
            }

            $vvm_group = new VvmGroups();

            if (isset($form_values['chkbox0'])) {
                $stages_array[] = 0;
            }

            if (isset($form_values['chkbox1'])) {
                $stages_array[] = 1;
            }

            if (isset($form_values['chkbox2'])) {
                $stages_array[] = 2;
            }

            if (isset($form_values['chkbox3'])) {
                $stages_array[] = 3;
            }

            if (isset($form_values['chkbox4'])) {
                $stages_array[] = 4;
            }

            foreach ($stages_array as $stages) {
                $vvm_group = new Model_VvmGroups();
                $vvm_group->form_values = array(
                    "vvm_stage_id" => $stages,
                    "vvm_group_id" => $form_values['vvm_group_id_update']
                );
                $vvm_group->addVvmGroup();
            }
        }
        $this->_redirect("/iadmin/manage-products/vvm-groups?success=2");
    }

    /**
     * This method Changes Status of VVM type
     */
    public function ajaxChangeStatusAction() {
        $this->_helper->layout->disableLayout();
        $row = $this->_em->getRepository("VvmTypes")->find($this->_request->getParam('id'));
        if ($this->_request->getParam('ajaxaction') == 'active') {
            $row->setStatus('1');
        } else if ($this->_request->getParam('ajaxaction') == 'deactive') {
            $row->setStatus('0');
        }

        $created_by = $this->_em->find('Users', $this->_userid);
        $row->setModifiedBy($created_by);
        $row->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($row);
        $this->_em->flush();
        $this->view->ajaxaction = $this->_request->getParam('ajaxaction');
    }

    /**
     * This method searches Transaction Type
     */
    public function transactionTypeAction() {
        $form = new Form_Iadmin_TransactionTypeSearch();
        $form_add = new Form_Iadmin_TransactionTypeAdd();

        $transaction_type = new Model_TransactionTypes();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $transaction_type_name = $form->getValue('transaction_type_name');
                $nature = $form->getValue('nature');

                if (!empty($transaction_type_name)) {
                    $transaction_type->form_values['transactionTypeName'] = $transaction_type_name;
                }
                if (!empty($nature)) {
                    $transaction_type->form_values['nature'] = $nature;
                }
            }
            $form->transaction_type_name->setValue($transaction_type_name);
            $form->nature->setValue($nature);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "transaction_type_name");

        $result = $transaction_type->getAllTransactionTypes($order, $sort);

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;
        $this->view->form_add = $form_add;
        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method Changes Status Nature 
     */
    public function ajaxChangeStatusNatureAction() {
        $this->_helper->layout->disableLayout();
        $row = $this->_em->getRepository("TransactionTypes")->find($this->_request->getParam('idData'));
        if ($this->_request->getParam('ajaxactionnature') == 'positive') {
            $row->setNature('+');
        } else if ($this->_request->getParam('ajaxactionnature') == 'negative') {
            $row->setNature('-');
        }
        $created_by = $this->_em->find('Users', $this->_user_id);
        $row->setModifiedBy($created_by);
        $row->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($row);
        $this->_em->flush();
        $this->view->ajaxactionnature = $this->_request->getParam('ajaxactionnature');
    }

    /**
     * This method Adds Transaction Type
     */
    public function addTransactionTypeAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {

            $form_values = $this->_request->getPost();

            $transaction_type = new TransactionTypes();
            $transaction_type->setTransactionTypeName($form_values['transaction_type_name']);

            $transaction_type->setNature($form_values['nature']);
            $transaction_type->setIsAdjustment($form_values['is_adjustment']);
            $transaction_type->setStatus($form_values['status']);
            $created_by = $this->_em->find('Users', $this->_userid);
            $transaction_type->setCreatedBy($created_by);
            $transaction_type->setCreatedDate(App_Tools_Time::now());
            $transaction_type->setModifiedBy($created_by);
            $transaction_type->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($transaction_type);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/transaction-type?success=1");
    }

    /**
     * This method retrieves Transaction information for edit
     */
    public function ajaxEditTranAction() {
        $this->_helper->layout->disableLayout();
        $transaction_type_id = $this->_request->getParam('transaction_type_id', '');

        $transactionType = $this->_em->find('TransactionTypes', $transaction_type_id);
        $form = new Form_Iadmin_TransactionTypeAdd();

        $form->transaction_type_name->setValue($transactionType->getTransactionTypeName());
        $form->is_adjustment->setValue($transactionType->getIsAdjustment());

        $form->transaction_type_id->setValue($transactionType->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method Updates Transaction Type
     */
    public function updateTransactionTypeAction() {
        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $transactionType = $this->_em->getRepository("TransactionTypes")->find($form_values['transaction_type_id']);
            $transactionType->setTransactionTypeName($form_values['transaction_type_name']);
            if ($form_values['is_adjustment'] == 1) {
                $transactionType->setIsAdjustment('1');
            } else {
                $transactionType->setIsAdjustment('2');
            }

            $created_by = $this->_em->find('Users', $this->_userid);
            $transactionType->setModifiedBy($created_by);
            $transactionType->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($transactionType);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-products/transaction-type?success=2");
    }

    /**
     * This method Change Status of Transaction
     */
    public function ajaxChangeStatusTranAction() {
        $this->_helper->layout->disableLayout();
        $row = $this->_em->getRepository("TransactionTypes")->find($this->_request->getParam('id'));
        if ($this->_request->getParam('ajaxaction') == 'active') {
            $row->setStatus('1');
        } else if ($this->_request->getParam('ajaxaction') == 'deactive') {
            $row->setStatus('0');
        }

        $created_by = $this->_em->find('Users', $this->_user_id);
        $row->setModifiedBy($created_by);
        $row->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($row);
        $this->_em->flush();
        $this->view->ajaxaction = $this->_request->getParam('ajaxaction');
    }

    /**
     * This method Checks Vvm Types
     */
    public function checkVvmTypesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $vvm_types = new Model_VvmTypes();
        if ($form_values['vvm_type_name'] !== $form_values['vvm_type_name_hidden']) {
            $vvm_types->form_values = $form_values;
            $result = $vvm_types->checkVvmTypes();
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
     * This method Checks Vvm Groups
     */
    public function checkVvmGroupsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $vvm_types = new Model_VvmGroups();
        if ($form_values['vvm_group_id'] !== $form_values['vvm_group_id_hidden']) {
            $vvm_types->form_values = $form_values;
            $result = $vvm_types->checkVvmGroups();
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
     * This method deletes VVM Group
     */
    public function deleteVvmGroupAction() {
        try {
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(TRUE);
            $vvm_group_id = $this->_getParam('id');

            $vvm_group = new Model_VvmGroups();
            $vvm_group->form_values = array(
                "vvm_group_id" => $vvm_group_id
            );

            $vvm_group->deleteVvmGroup();

            $this->_redirect("/iadmin/manage-products/vvm-groups?success=3");
        } catch (Exception $e) {

//            $em->getConnection()->rollback();
//            $em->close();
//            App_FileLogger::info($e);
            $this->_redirect("/iadmin/manage-products/vvm-groups?success=4");
        }
    }

    /**
     * This method searches VVM Groups
     */
    public function vvmGroupsAction() {
        $form_add = new Form_Iadmin_VvmTypeAdd();
        $form = new Form_Iadmin_VvmGroups;

        $vvm_group = new Model_VvmGroups();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $vvm_group_name = $form->getValue('vvm_type_name');
                $status = $form->getValue('status');

                if (!empty($vvm_group_name)) {
                    $vvm_group->form_values['vvmTypeName'] = $vvm_group_name;
                }
                if (!empty($status)) {
                    $vvm_group->form_values['status'] = $status;
                }
            }
            $form->vvm_type_name->setValue($vvm_group_name);
            $form->status->setValue($status);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "vvm_type_name");

        $result = $vvm_group->getAllVvmGroups($order, $sort);

        $paginator = false;

        if ($result) {
            //Paginate the contest results
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
        }

        $this->view->form = $form;
        $this->view->form_add = $form_add;
        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

}
