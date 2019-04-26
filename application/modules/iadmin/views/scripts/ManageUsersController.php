<?php

/**
 * Iadmin_ManageUsersController
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Users
 */
class Iadmin_ManageUsersController extends App_Controller_Base {

    /**
     * Routine Users
     */
    public function routineUsersAction() {
        $form = new Form_Iadmin_Users();
        $user = new Model_Users();
        $params = array();
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $this->view->combos = $this->_request->getPost();
            $form->province_id->setValue($form_values['combo1']);
            $form->district_id->setValue($form_values['combo2']);
            $form->tehsil_id->setValue($form_values['combo3']);
            $form->parent_id->setValue($form_values['combo4']);
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "users");
            $user->form_values = $this->_request->getPost();
            $user->form_values['office_type'] = '6';
            $params['office_type'] = '6';
            if (!empty($form_values['combo1'])) {
                $params['combo1'] = $form_values['combo1'];
            }
            if (!empty($form_values['combo2'])) {
                $params['combo2'] = $form_values['combo2'];
            }
            if (!empty($form_values['combo3'])) {
                $params['combo3'] = $form_values['combo3'];
            }
            if (!empty($form_values['combo4'])) {
                $params['combo4'] = $form_values['combo4'];
            }
            $user->form_values['page'] = 'routine';

            $user->form_values['loc_id'] = $form_values['combo4'];

            $result = $user->getRIUsers($order, $sort);
            //Paginate the contest results


            $this->view->combos_1 = 'routine';
            $this->view->form = $form;
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
            $this->view->pagination_params = $params;
        } else {

            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "asset_sub_type");
            $user->form_values = $this->_request->getParams();
            $params = $this->_request->getParams();
            $this->view->combos = $this->_request->getParams();
            if ($this->_identity->getRoleId() == 38) {
                $form->province_id->setValue($this->_identity->getProvinceId());
            } else {
                $form->province_id->setValue($this->_getParam('combo1'));
            }

            if ($this->_identity->getRoleId() == 39) {
                $form->province_id->setValue($this->_identity->getProvinceId());
                $form->district_id->setValue($this->_identity->getDistrictId());
                $user->form_values['district_id'] = $this->_identity->getDistrictId();
            } else {
                $form->province_id->setValue($this->_getParam('combo1'));
                $form->district_id->setValue($this->_getParam('combo2'));
                $user->form_values['district_id'] = $this->_getParam('combo2');
            }

            $form->tehsil_id->setValue($this->_getParam('combo3'));
            $form->parent_id->setValue($this->_getParam('combo4'));
            $user->form_values['page'] = 'routine';
            $user->form_values['office_type'] = '6';
            $result = $user->getRIUsers($order, $sort);
            //Paginate the contest results

            $this->view->combos_1 = 'routine';
            $this->view->form = $form;
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
            $this->view->pagination_params = $params;
        }
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
        $this->view->role_id = $this->_identity->getRoleId();
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/routine_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/routine_add_combos.js');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css');

        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method Updates Cluster
     */
    public function updateClusterAction() {
        $form = new Form_Iadmin_UpdateCluster;
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $user_id = $form_values['user'];
            $warehouse = new Model_Warehouses();
            $warehouse_users_id = $warehouse->getWharehouseUsers($user_id);
            foreach ($warehouse_users_id as $wh_users) {
                $wh_users = $this->_em->find('WarehouseUsers', $wh_users);
                $this->_em->remove($wh_users);
                $this->_em->flush();
            }
            $created_by = $this->_em->find('Users', $this->_userid);
            foreach ($form_values['wh'] as $whId) {
                $warehouse_users_id = $warehouse->getWharehouseUsersDefault($user_id);

                if ($warehouse_users_id[0]['pkId'] != $whId) {
                    $warehouse_users = new WarehouseUsers();
                    $user_id_find = $this->_em->find('Users', $user_id);
                    $warehouse_users->setUser($user_id_find);
                    $wh_id_find = $this->_em->find('Warehouses', $whId);
                    $warehouse_users->setWarehouse($wh_id_find);
                    //$warehouse_users->setIsDefault('');
                    $warehouse_users->setModifiedBy($created_by);
                    $warehouse_users->setCreatedBy($created_by);
                    $warehouse_users->setCreatedDate(App_Tools_Time::now());
                    $warehouse_users->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($warehouse_users);
                    $this->_em->flush();
                }
            }
            $this->_redirect("/iadmin/manage-users/update-cluster?e=1");
        }
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
        $this->view->role_id = $this->_identity->getRoleId();
        $this->view->district_id = $this->_identity->getDistrictId();
        $this->view->form = $form;
    }

    /**
     * This method Seraches Campaigns Users
     */
    public function campaignsUsersAction() {
        $form = new Form_Iadmin_Users();
        $user = new Model_Users();
        $params = array();

        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $this->view->combos = $this->_request->getPost();
            if (!empty($form_values['combo1'])) {
                $form->province_id->setValue($form_values['combo1']);
            }
            if (!empty($form_values['combo2'])) {
                $form->district_id->setValue($form_values['combo2']);
            }
            if (!empty($form_values['combo3'])) {
                $form->tehsil_id->setValue($form_values['combo3']);
            }
            if (!empty($form_values['combo4'])) {
                $form->parent_id->setValue($form_values['combo4']);
            }
            if (!empty($form_values['office_type'])) {
                $params['office_type'] = $form_values['office_type'];
            }
            if (!empty($form_values['combo1'])) {
                $params['combo1'] = $form_values['combo1'];
            }
            if (!empty($form_values['combo2'])) {
                $params['combo2'] = $form_values['combo2'];
            }
            if (!empty($form_values['combo3'])) {
                $params['combo3'] = $form_values['combo3'];
            }
            if (!empty($form_values['combo4'])) {
                $params['combo4'] = $form_values['combo4'];
            }
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "username");
            $user->form_values = $this->_request->getPost();
            $user->form_values['page'] = 'campaigns';
            $result = $user->getCampaignUsers($order, $sort);
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
            $this->view->combos_1 = 'campaigns';
            $this->view->form = $form;
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
            $this->view->pagination_params = $params;
        } else {
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "user");
            $user->form_values = $this->_request->getParams();
            $params = $this->_request->getParams();
            $this->view->combos = $this->_request->getParams();
            $form->province_id->setValue($this->_getParam('combo1'));
            $form->district_id->setValue($this->_getParam('combo2'));
            $form->tehsil_id->setValue($this->_getParam('combo3'));
            $form->parent_id->setValue($this->_getParam('combo4'));
            $user->form_values['page'] = 'campaigns';
            $result = $user->getCampaignUsers($order, $sort);
            //Paginate the contest results
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
            $this->view->combos_1 = 'campaigns';
            $this->view->form = $form;
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
            $this->view->pagination_params = $params;
        }

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/stores_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/stores_add_combos.js');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css');
        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method Searches Im Users
     */
    public function imUsersAction() {
        $form = new Form_Iadmin_Users();
        $user = new Model_Users();
        $params = array();
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $this->view->combos = $this->_request->getPost();
            $form->province_id->setValue($form_values['combo1']);
            $form->district_id->setValue($form_values['combo2']);
            $form->tehsil_id->setValue($form_values['combo3']);
            $form->parent_id->setValue($form_values['combo4']);
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "user");
            $user->form_values = $this->_request->getPost();
            $user->form_values['page'] = 'im';
            $result = $user->getAllImUsers($order, $sort);
            //Paginate the contest results
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
            if ($this->_identity->getRoleId() == 38 || $this->_identity->getRoleId() == 39) {
                $this->view->combos_1 = 'pro-admin';
            } else {
                $this->view->combos_1 = 'im';
            }
            $this->view->form = $form;
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
        } else {
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "user");
            $user->form_values = $this->_request->getParams();
            $params = $this->_request->getParams();
            $this->view->combos = $this->_request->getParams();
            if ($this->_identity->getRoleId() == 38) {
                $form->province_id->setValue($this->_identity->getProvinceId());
                $user->form_values['combo1'] = $this->_identity->getProvinceId();
                $user->form_values['office_type'] = 3;
            } else if ($this->_identity->getRoleId() == 39) {
                $form->province_id->setValue($this->_identity->getProvinceId());
                $form->district_id->setValue($this->_identity->getDistrictId());
                $user->form_values['combo1'] = $this->_identity->getProvinceId();
                $user->form_values['combo2'] = $this->_identity->getDistrictId();
                $user->form_values['office_type'] = '5';
            } else {
                $form->province_id->setValue($this->_getParam('combo1'));
                $form->district_id->setValue($this->_getParam('combo2'));
                $user->form_values['combo1'] = 1;
                $user->form_values['combo2'] = 33;
                $user->form_values['office_type'] = '';
            }


            $form->tehsil_id->setValue($this->_getParam('combo3'));
            $form->parent_id->setValue($this->_getParam('combo4'));
            $user->form_values['page'] = 'im';

            //   $user->form_values['combo3'] = 194;
            // $user->form_values['combo4'] = 543;
            $result = $user->getAllImUsers($order, $sort);
            //Paginate the contest results
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
            if ($this->_identity->getRoleId() == 38 || $this->_identity->getRoleId() == 39) {
                $this->view->combos_1 = 'pro-admin';
            } else {
                $this->view->combos_1 = 'im';
            }

            $this->view->role_id = $this->_identity->getRoleId();
            $this->view->form = $form;
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
        }
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
        $this->view->role_id = $this->_identity->getRoleId();
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/stores_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/stores_add_combos.js');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css');

        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method Searches Policy Users
     */
    public function policyUsersAction() {
        $form = new Form_Iadmin_Users();
        $user = new Model_Users();
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $this->view->combos = $this->_request->getPost();
            $form->province_id->setValue($form_values['combo1']);
            $form->district_id->setValue($form_values['combo2']);
            $form->tehsil_id->setValue($form_values['combo3']);
            $form->parent_id->setValue($form_values['combo4']);
            $form->search_policy_users->setValue($form_values['search_policy_users']);
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "asset_sub_type");
            $user->form_values = $this->_request->getPost();
            $user->form_values['page'] = 'policy';
            $result = $user->getAllPolicyUsers($order, $sort);
            //Paginate the contest results
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
            $this->view->combos_1 = 'policy';
            $this->view->form = $form;
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
        }
        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "asset_sub_type");
        $user->form_values['page'] = 'policy';
        $result = $user->getAllPolicyUsers($order, $sort);
        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);
        $this->view->combos_1 = 'campaigns';
        $this->view->form = $form;
        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/stores_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/stores_add_combos.js');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css');

        $this->view->count = 1;
        if ($page > 1) {
            $this->view->count = (($page - 1) * $counter) + 1;
        }
    }

    /**
     * This method  Edits Routine users
     */
    public function ajaxEditRoutineAction() {
        $this->_helper->layout->disableLayout();
        $wh_id = $this->_request->getParam('wh_id', '');
        $warehouse = new Model_Warehouses();
        $warehouse_users_id = $warehouse->getWharehouseUsersId($wh_id);
        $wh_user_id = $this->_em->find('WarehouseUsers', $warehouse_users_id['0']['pkId']);
        $warehouse_id = $wh_user_id->getWarehouse()->getPkId();
        $whare_id = $this->_em->find('Warehouses', $warehouse_id);
        $users = $this->_em->find('Users', $wh_id);
        $form = new Form_Iadmin_Users();
        $this->view->combos_1 = 'routine';
        $form->warehouse_users_id_edit->setValue($wh_user_id->getPkId());
        $form->default_warehouse_update_hidden->setValue($wh_user_id->getWarehouse()->getPkId());
        $form->user_name_update->setValue($users->getLoginId());
        $form->user_name_update_hidden->setValue($users->getLoginId());
        $form->user_id->setValue($wh_id);
        $form->office_id_edit->setValue($whare_id->getStakeholderOffice()->getPkId());
        if ($whare_id->getStakeholderOffice()->getPkId() != 1) {
            $location_id = $users->getLocation()->getPkId();
            $loc = $this->_em->find('Locations', $location_id);
            $form->province_id_edit->setValue($loc->getProvince()->getPkId());
        }
        $form->district_id_edit->setValue($whare_id->getDistrict()->getPkId());
        if ($whare_id->getStakeholderOffice()->getPkId() == 5) {
            $form->tehsil_id_edit->setValue($whare_id->getLocation()->getPkId());
        }
        if ($whare_id->getStakeholderOffice()->getPkId() == 6) {
            $form->parent_id_edit->setValue($whare_id->getLocation()->getPkId());
            $locations = $this->_em->find('Locations', $whare_id->getLocation()->getPkId());
            $form->tehsil_id_edit->setValue($locations->getParent()->getPkId());
        }
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
        $this->view->role_id = $this->_identity->getRoleId();
        $this->view->form = $form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_edit_combos.js');
    }

    /**
     * This method Edits Campaigns users
     */
    public function ajaxEditCampaignsAction() {
        $this->_helper->layout->disableLayout();

        $wh_id = $this->_request->getParam('wh_id', '');
        $form = new Form_Iadmin_Users();
        $users = $this->_em->find('Users', $wh_id);
        $locations = $this->_em->find('Locations', $users->getLocation()->getPkId());
        if ($users->getLocation()->getPkId() == 10) {
            $this->view->combos_1 = 'campaigns';
            $form->office_id_edit->setValue('1');
            $form->user_name_update->setValue($users->getLoginId());
            $form->user_name_update_hidden->setValue($users->getLoginId());
            $form->user_id->setValue($wh_id);
        } else if ($locations->getGeoLevel()->getPkId() == 2) {
            $this->view->combos_1 = 'campaigns';
            $form->office_id_edit->setValue('2');
            $form->province_id_edit->setValue($users->getLocation()->getPkId());
            $form->user_name_update->setValue($users->getLoginId());
            $form->user_name_update_hidden->setValue($users->getLoginId());
            $form->user_id->setValue($wh_id);
        } else if ($locations->getGeoLevel()->getPkId() == 4) {
            $this->view->combos_1 = 'campaigns';
            $form->office_id_edit->setValue('4');
            $form->province_id_edit->setValue($locations->getProvince()->getPkId());
            $form->district_id_edit->setValue($users->getLocation()->getPkId());
            $form->user_name_update->setValue($users->getLoginId());
            $form->user_name_update_hidden->setValue($users->getLoginId());
            $form->user_id->setValue($wh_id);
        }

        $this->view->form = $form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_edit_combos.js');
    }

    /**
     * This method Edits Im users
     */
    public function ajaxEditImAction() {
        $this->_helper->layout->disableLayout();
//        $this->_helper->layout->setLayout("ajax");
        $wh_id = $this->_request->getParam('wh_id', '');
        $warehouse = new Model_Warehouses();
        $warehouse_users_id = $warehouse->getWharehouseUsersId($wh_id);
        $wh_user_id = $this->_em->find('WarehouseUsers', $warehouse_users_id['0']['pkId']);
        $warehouse_id = $wh_user_id->getWarehouse()->getPkId();
        $whare_id = $this->_em->find('Warehouses', $warehouse_id);
        $users = $this->_em->find('Users', $wh_id);
        $form = new Form_Iadmin_Users();
        $this->view->combos_1 = 'im';
        $form->warehouse_users_id_edit->setValue($wh_user_id->getPkId());
        $form->user_name_update->setValue($users->getLoginId());
        if (!empty($users->getEmail())) {
            $form->email_update->setValue($users->getEmail());
        }
        if (!empty($users->getCellNumber())) {
            $form->phone_update->setValue($users->getCellNumber());
        }
        $form->user_name_update_hidden->setValue($users->getLoginId());
        $form->default_warehouse_update_hidden->setValue($wh_user_id->getWarehouse()->getPkId());
        $form->user_id->setValue($wh_id);
        $form->office_id_edit->setValue($whare_id->getStakeholderOffice()->getPkId());
        if ($whare_id->getStakeholderOffice()->getPkId() != 1) {
            $form->province_id_edit->setValue($whare_id->getProvince()->getPkId());
        }
        $form->district_id_edit->setValue($whare_id->getDistrict()->getPkId());
        if ($whare_id->getStakeholderOffice()->getPkId() == 5) {
            $form->tehsil_id_edit->setValue($whare_id->getLocation()->getPkId());
        }
        if ($whare_id->getStakeholderOffice()->getPkId() == 6) {
            $form->parent_id_edit->setValue($whare_id->getLocation()->getPkId());
            $locations = $this->_em->find('Locations', $whare_id->getLocation()->getPkId());
            $form->tehsil_id_edit->setValue($locations->getParent()->getPkId());
        }
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
        $this->view->role_id = $this->_identity->getRoleId();
        $this->view->form = $form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_edit_combos.js');
    }

    /**
     * This method Edits Im Role
     */
    public function ajaxEditImRoleAction() {
        $this->_helper->layout->setLayout("ajax");
        $wh_id = $this->_request->getParam('wh_id', '');
        $users = $this->_em->find('Users', $wh_id);
        $form = new Form_Iadmin_Users();
        $form->user_id->setValue($wh_id);
        $this->view->combos_1 = 'im';
        $form->role->setValue($users->getRole()->getPkId());
        $this->view->form = $form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_edit_combos.js');
    }

    /**
     * This method Edits Policy users
     */
    public function ajaxEditPolicyAction() {
        $this->_helper->layout->disableLayout();
//        $this->_helper->layout->setLayout("ajax");
        $wh_id = $this->_request->getParam('wh_id', '');
        $form = new Form_Iadmin_Users();
        $users = $this->_em->find('Users', $wh_id);
        $locations = $this->_em->find('Locations', $users->getLocation()->getPkId());
        if ($users->getLocation()->getPkId() == 10) {
            $this->view->combos_1 = 'campaigns';
            $form->office_id_edit->setValue('1');
            $form->login_id_update->setValue($users->getLoginId());
            $form->user_name_update->setValue($users->getUserName());
            $form->user_name_update_hidden->setValue($users->getLoginId());
            $form->email_update->setValue($users->getEmail());
            $form->phone_update->setValue($users->getCellNumber());
            $form->user_id->setValue($wh_id);
            $form->hdn_role_id->setValue($users->getRole()->getPkId());
        } else if ($locations->getGeoLevel()->getPkId() == 2) {
            $this->view->combos_1 = 'campaigns';
            $form->office_id_edit->setValue('2');
            $form->province_id_edit->setValue($users->getLocation()->getPkId());
            $form->login_id_update->setValue($users->getLoginId());
            $form->user_name_update->setValue($users->getUserName());
            $form->user_name_update_hidden->setValue($users->getLoginId());
            $form->email_update->setValue($users->getEmail());
            $form->phone_update->setValue($users->getCellNumber());
            $form->user_id->setValue($wh_id);
            $form->hdn_role_id->setValue($users->getRole()->getPkId());
        } else if ($locations->getGeoLevel()->getPkId() == 4) {
            $this->view->combos_1 = 'campaigns';
            $form->office_id_edit->setValue('4');
            $form->province_id_edit->setValue($locations->getProvince()->getPkId());
            $form->district_id_edit->setValue($users->getLocation()->getPkId());
            $form->login_id_update->setValue($users->getLoginId());
            $form->user_name_update->setValue($users->getUserName());
            $form->user_name_update_hidden->setValue($users->getLoginId());
            $form->email_update->setValue($users->getEmail());
            $form->phone_update->setValue($users->getCellNumber());
            $form->user_id->setValue($wh_id);
            $form->hdn_role_id->setValue($users->getRole()->getPkId());
        }

        $warehouse_users = $this->_em->getRepository("WarehouseUsers")->findBy(array('user' => $users->getPkId()));
        $form->warehouse_users_id_edit->setValue($warehouse_users[0]->getPkId());
        $form->default_warehouse_update_hidden->setValue($warehouse_users[0]->getWarehouse()->getPkId());
//      
//          $form->user_name_update->setAttribs(array('disable' => 'disable'));
        $form->old_password->setValue('');
        $this->view->form = $form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_edit_combos.js');
    }

    /**
     * This method Adds Routine users
     */
    public function addRoutineAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = new Users();
            $users->setUserName($form_values['user_name_add']);
            $users->setEmail($form_values['email']);
            $users->setCellNumber($form_values['phone']);
            $users->setLoginId($form_values['user_name_add']);
            $users->setPassword(md5($form_values['password']));
            if ($form_values['office_type_add'] != '1') {
                $location_id = $this->_em->find('Locations', $form_values['combo4_add']);
                $users->setLocation($location_id);
            }
            $role = $this->_em->find('Roles', 8);
            $users->setRole($role);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $users->setStatus('1');
            $user = $this->_em->find('Users', $this->_userid);
            $users->setCreatedBy($user);
            $users->setCreatedDate(App_Tools_Time::now());
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $user_id = $users->getPkId();
            $warehouse_users = new WarehouseUsers();
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse']);
            $warehouse_users->setWarehouse($wh_id);
            $user_id_i = $this->_em->find('Users', $user_id);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setIsDefault('1');
            $warehouse_users->setCreatedBy($user);
            $warehouse_users->setCreatedDate(App_Tools_Time::now());
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-users/routine-users");
    }

    /**
     * This method Adds Campaigns user
     */
    public function addCampaignsAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = new Users();
            $users->setUserName($form_values['user_name_add']);
            $users->setEmail($form_values['user_name_add']);
            $users->setCellNumber('03423423423');
            $users->setLoginId($form_values['user_name_add']);
            $users->setPassword(md5($form_values['password']));
            if ($form_values['office_type_add'] == '1') {
                $location_id = '10';
                $role = $this->_em->find('Roles', 14);
                $stk_id = Model_Stakeholders::CAMPAIGN;
            }
            if ($form_values['office_type_add'] == '2') {
                $location_id = $form_values['combo1_add'];
                $role = $this->_em->find('Roles', 15);
                $stk_id = Model_Stakeholders::CAMPAIGN;
            }
            if ($form_values['office_type_add'] == '4') {
                $location_id = $form_values['combo2_add'];
                $role = $this->_em->find('Roles', 16);
                $stk_id = 45;
            }
            $loc_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($loc_id);
            $users->setRole($role);
            $stakeholder = $this->_em->find('Stakeholders', $stk_id);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setCreatedBy($user);
            $users->setCreatedDate(App_Tools_Time::now());
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $user_id = $users->getPkId();
            if ($form_values['office_type_add'] == '4') {
                $warehouses = new Model_Warehouses();
                $warehouses->form_values = $form_values;
                $warehouses->form_values['page'] = "campaigns";
                $warehouse_id = $warehouses->getWarehouseIdByUcId();
                $count = 1;
                foreach ($warehouse_id as $wh_id_w) {
                    if ($count == 1) {
                        $default = 1;
                    } else {
                        $default = 0;
                    }
                    $warehouse_users = new WarehouseUsers();
                    $wh_id = $this->_em->find('Warehouses', $wh_id_w);
                    $warehouse_users->setWarehouse($wh_id);
                    $user_id_i = $this->_em->find('Users', $user_id);
                    $warehouse_users->setUser($user_id_i);
                    $warehouse_users->setIsDefault($default);
                    $warehouse_users->setCreatedBy($user);
                    $warehouse_users->setCreatedDate(App_Tools_Time::now());
                    $warehouse_users->setModifiedBy($user);
                    $warehouse_users->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($warehouse_users);
                    $this->_em->flush();
                    $count++;
                }
            }
        }
        $this->_redirect("/iadmin/manage-users/campaigns-users");
    }

    /**
     * This method Adds Inventory users
     */
    public function addInventoryAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = new Users();
            $users->setUserName($form_values['user_name_add']);
            $users->setEmail($form_values['email']);
            $users->setCellNumber($form_values['phone']);
            $users->setLoginId($form_values['user_name_add']);
            $users->setPassword(md5($form_values['password']));
            if ($form_values['office_type_add'] == '1') {
                $location_id = '10';
                $role_id = '3';
            }
            if ($form_values['office_type_add'] == '2') {
                $location_id = $form_values['combo1_add'];
                $role_id = '4';
            }
            if ($form_values['office_type_add'] == '3') {
                $location_id = $form_values['combo1_add'];
                $role_id = '5';
            }
            if ($form_values['office_type_add'] == '4') {
                $location_id = $form_values['combo2_add'];
                $role_id = '6';
            }
            if ($form_values['office_type_add'] == '5') {
                $location_id = $form_values['combo3_add'];
                $role_id = '7';
            }
            $province_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($province_id);
            $role = $this->_em->find('Roles', $role_id);
            $users->setRole($role);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $users->setStatus('1');
            $user = $this->_em->find('Users', $this->_userid);
            $users->setCreatedBy($user);
            $users->setCreatedDate(App_Tools_Time::now());
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $user_id = $users->getPkId();
            $warehouse_users = new WarehouseUsers();
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse']);
            $warehouse_users->setWarehouse($wh_id);
            $warehouse_users->setIsDefault('1');
            $user_id_i = $this->_em->find('Users', $user_id);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setCreatedBy($user);
            $warehouse_users->setCreatedDate(App_Tools_Time::now());
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-users/im-users");
    }

    /**
     * This method Adds Policy users
     */
    public function addPolicyAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = new Users();
            if ($form_values['office_type_add'] == '1') {
                $location_id = '10';
            }
            if ($form_values['office_type_add'] == '2' || $form_values['office_type_add'] == '3') {
                $location_id = $form_values['combo1_add'];
            }
            if ($form_values['office_type_add'] == '4') {
                $location_id = $form_values['combo2_add'];
            }
            $province_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($province_id);
            $users->setUserName($form_values['user_name_add']);
            $users->setEmail($form_values['email']);
            $users->setCellNumber($form_values['phone']);
            $users->setLoginId($form_values['login_id_add']);
            $users->setPassword(md5($form_values['password']));
            $role = $this->_em->find('Roles', $form_values['role_policy']);
            $users->setRole($role);
            $users->setStatus(1);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setCreatedBy($user);
            $users->setCreatedDate(App_Tools_Time::now());
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $user_id = $users->getPkId();
            $warehouse_users = new WarehouseUsers();
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse']);
            $warehouse_users->setWarehouse($wh_id);
            $warehouse_users->setIsDefault('1');
            $user_id_i = $this->_em->find('Users', $user_id);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setCreatedBy($user);
            $warehouse_users->setCreatedDate(App_Tools_Time::now());
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-users/policy-users");
    }

    /**
     * This method Updates Routine users
     */
    public function updateRoutineAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = $this->_em->find('Users', $form_values['user_id']);
            $users->setUserName($form_values['user_name_update']);
            $users->setEmail($form_values['user_name_update']);
            $users->setLoginId($form_values['user_name_update']);
            if ($form_values['office_type_edit'] != '1') {
                $province_id = $this->_em->find('Locations', $form_values['combo4_edit']);
                $users->setLocation($province_id);
            }
            $stakeholder = $this->_em->find('Stakeholders', 9);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $warehouse_users = $this->_em->find('WarehouseUsers', $form_values['warehouse_users_id_edit']);
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse_update']);
            $warehouse_users->setWarehouse($wh_id);
            $user_id_i = $this->_em->find('Users', $form_values['user_id']);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setIsDefault('1');
            $created_by = $this->_em->find('Users', $this->_userid);
            $warehouse_users->setModifiedBy($created_by);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-users/routine-users");
    }

    /**
     * This method Updates Campaigns users
     */
    public function updateCampaignsAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = $this->_em->find('Users', $form_values['user_id']);
            $users->setUserName($form_values['user_name_update']);
            $users->setEmail($form_values['user_name_update']);
            $users->setCellNumber('03423423423');
            $users->setLoginId($form_values['user_name_update']);
            if ($form_values['office_type_edit'] == '1') {
                $role = $this->_em->find('Roles', 14);
                $location_id = '10';
            }
            if ($form_values['office_type_edit'] == '2') {
                $role = $this->_em->find('Roles', 15);
                $location_id = $form_values['combo1_edit'];
            }
            if ($form_values['office_type_edit'] == '4') {
                $role = $this->_em->find('Roles', 16);
                $location_id = $form_values['combo2_edit'];
            }
            $loc_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($loc_id);
            $users->setRole($role);
            $stakeholder = $this->_em->find('Stakeholders', 10);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $user_id = $users->getPkId();
            if ($form_values['office_type_edit'] == '4') {
                $warehouse_users = $this->_em->getRepository("WarehouseUsers")->findBy(array('user' => $form_values['user_id']));
                foreach ($warehouse_users as $warehouse_users_a) {
                    $wh_id = $this->_em->find('WarehouseUsers', $warehouse_users_a->getPkId());
                    $this->_em->remove($wh_id);
                    $this->_em->flush();
                }
                $warehouses = new Model_Warehouses();
                $warehouses->form_values = $form_values;
                $warehouses->form_values['page'] = "campaigns";
                $warehouse_id = $warehouses->getWarehouseIdByUcIdUpdate();
                foreach ($warehouse_id as $wh_id_w) {
                    $warehouse_users = new WarehouseUsers();
                    $wh_id = $this->_em->find('Warehouses', $wh_id_w);
                    $warehouse_users->setWarehouse($wh_id);
                    $user_id_i = $this->_em->find('Users', $user_id);
                    $warehouse_users->setUser($user_id_i);
                    $warehouse_users->setModifiedBy($user);
                    $warehouse_users->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($warehouse_users);
                    $this->_em->flush();
                }
            }
        }
        $this->_redirect("/iadmin/manage-users/campaigns-users");
    }

    /**
     * This method Updates Inventory users
     */
    public function updateInventoryAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = $this->_em->find('Users', $form_values['user_id']);
            $users->setUserName($form_values['user_name_update']);
            $users->setEmail($form_values['email_update']);
            $users->setCellNumber($form_values['phone_update']);
            $users->setLoginId($form_values['user_name_update']);
            if ($form_values['office_type_edit'] == '1') {
                $location_id = '10';
            }
            if ($form_values['office_type_edit'] == '2' || $form_values['office_type_edit'] == '3') {
                $location_id = $form_values['combo1_edit'];
            }
            if ($form_values['office_type_edit'] == '4') {
                $location_id = $form_values['combo2_edit'];
            }
            if ($form_values['office_type_edit'] == '5') {
                $location_id = $form_values['combo3_edit'];
            }
            $province_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($province_id);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $warehouse_users = $this->_em->find('WarehouseUsers', $form_values['warehouse_users_id_edit']);
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse_update']);
            $warehouse_users->setWarehouse($wh_id);
            $user_id_i = $this->_em->find('Users', $form_values['user_id']);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setIsDefault('1');
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-users/im-users");
    }

    /**
     * This method Updates Inventory Role
     */
    public function updateInventoryRoleAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = $this->_em->find('Users', $form_values['user_id']);
            $role = $this->_em->find('Roles', $form_values['role']);
            $users->setRole($role);
            $created_by = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($created_by);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-users/im-users");
    }

    /**
     * This method Updates Policy users
     */
    public function updatePolicyAction() {
        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $users = $this->_em->find('Users', $form_values['user_id']);
            $users->setUserName($form_values['user_name_update']);
            $users->setEmail($form_values['email_update']);
            $users->setLoginId($form_values['login_id_update']);
            if ($form_values['office_type_edit'] == '1') {
                $location_id = '10';
            }
            if ($form_values['office_type_edit'] == '2' || $form_values['office_type_edit'] == '3') {
                $location_id = $form_values['combo1_edit'];
            }
            if ($form_values['office_type_edit'] == '4') {
                $location_id = $form_values['combo2_edit'];
            }
            if ($form_values['office_type_edit'] == '5') {
                $location_id = $form_values['combo3_edit'];
            }
            $province_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($province_id);
            $role = $this->_em->find('Roles', $form_values['role_policy_update']);
            $users->setRole($role);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();


            $warehouse_users = $this->_em->find('WarehouseUsers', $form_values['warehouse_users_id_edit']);
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse_update']);
            $warehouse_users->setWarehouse($wh_id);
            $user_id_i = $this->_em->find('Users', $form_values['user_id']);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setIsDefault('1');
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
           
        }
        $this->_redirect("/iadmin/manage-users/policy-users");
    }

    /**
     * This method Gets District locations
     */
    public function ajaxGetDistrictAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->province_id) && !empty($this->_request->province_id)) {
            $locations = new Model_Locations();
            $locations->form_values['province_id'] = $this->_request->province_id;
            $array = $locations->districtLocations();
            $this->view->data = $array;
        }
    }

    /**
     * This method Gets Users
     */
    public function ajaxGetUsersAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->district_id) && !empty($this->_request->district_id)) {
            $users = new Model_Users();
            $users->form_values['district_id'] = $this->_request->district_id;
            $array = $users->getAllUsersForCluster();
            $this->view->data = $array;
        }
    }

    /**
     * This method Gets Warehouses
     */
    public function ajaxGetWarehousesAction() {
        $this->_helper->layout->setLayout("ajax");
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/iadmin/manage-users/ajax-get-warehouses.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/jquery.multi-select.min.js');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/css/select.css');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/css/multiselect.css');
        $this->view->inlineScript()->appendFile($base_url . '/js/select2.min.js');
        if (isset($this->_request->userId) && !empty($this->_request->userId)) {
            $warehouse = new Model_Warehouses();
            $warehouse->form_values['user_id'] = $this->_request->userId;
            $array = $warehouse->getAllUsersForClusterByUser();
            $this->view->data = $array;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $warehouse = new Model_Warehouses();
            $warehouse->form_values['district_id'] = $this->_request->district;
            $array_1 = $warehouse->getAllUsersForClusterByDistrict();
            $this->view->data_district = $array_1;
        }
    }

    /**
     * This method Checks Users Update
     */
    public function checkUsersUpdateAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $users = new Model_Users();

        if ($form_values['user_name_update'] !== $form_values['user_name_update_hidden']) {
            $users->form_values = $form_values;
            $result = $users->checkUsersUpdate();
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
     * This method Checks Users
     */
    public function checkUsersAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $users = new Model_Users();
        $users->form_values = $form_values;
        $result = $users->checkUsers();
        if (count($result) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * This method Checks Users Policy
     */
    public function checkUsersPolicyAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $users = new Model_Users();
        $users->form_values = $form_values;
        $result = $users->checkUsersPolicy();
        $this->view->result = $result;
    }

    /**
     * This method Checks Users Update Policy
     */
    public function checkUsersUpdatePolicyAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $users = new Model_Users();
        if ($form_values['user_name_update'] !== $form_values['user_name_update_hidden']) {
            $users->form_values = $form_values;
            $result = $users->checkUsersUpdatePolicy();
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
     * This method Gets Default Warehouse
     */
    public function getDefaultWarehouseAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $warehouse = new Model_Warehouses();
        $warehouse->form_values = $form_values;
        $result = $warehouse->getDefaultWarehouse();
        $this->view->data = $result;
    }

    /**
     * This method Gets Default Warehouse By Level
     */
    public function getDefaultWarehouseByLevelAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $warehouse = new Model_Warehouses();
        $warehouse->form_values = $form_values;
        $result = $warehouse->getDefaultWarehouseByLevel();
        $this->view->data = $result;
    }

    /**
     * This method deletes user
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $user_id = $this->_request->getParam("user_id");
        $users = $this->_em->getRepository("Users")->find($user_id);
        $this->_em->remove($users);
        return $this->_em->flush();
    }

    /**
     * This method Checks Old Password
     */
    public function checkOldPasswordAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $old_password = md5($this->_request->old_password);
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("u.password")
                ->from('Users', 'u')
                ->where("u.password ='" . $old_password . "'")
                ->AndWhere("u.pkId='" . $this->_request->user_id . "'");
        $result = $str_sql->getQuery()->getResult();
        if (!empty($result) && count($result) > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /**
     * This method Gets User Feedback
     */
    public function userFeedbackAction() {
        $users = new Model_Users();
        $user_feeadback = $users->getUserFeedback();
        $this->view->result = $user_feeadback;
    }

    /**
     * This method Edits User Profile
     */
    public function editUserProfileAction() {
        $form = new Form_EditUserProfile();
        $this->view->form = $form;
    }

    /**
     * This method gets User Login Log
     */
    public function userLoginLogAction() {
        $users = new Model_Users();
        $doc_user_log = $users->getUserLoginLog();
        $this->view->result = $doc_user_log;
    }

    public function ajaxGetImTableAction() {
        $this->_helper->layout->disableLayout();
        $user = new Model_Users();

        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $this->view->combos = $this->_request->getPost();

            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "user");
            $user->form_values = $this->_request->getPost();
            $user->form_values['page'] = 'im';
            $result = $user->getAllImUsers($order, $sort);
            //Paginate the contest results
            $paginator = Zend_Paginator::factory($result);
            $page = $this->_getParam("page", 1);
            $counter = $this->_getParam("counter", 10);
            $paginator->setCurrentPageNumber((int) $page);
            $paginator->setItemCountPerPage((int) $counter);
            if ($this->_identity->getRoleId() == 38 || $this->_identity->getRoleId() == 39) {
                $this->view->combos_1 = 'pro-admin';
            } else {
                $this->view->combos_1 = 'im';
            }

            $this->view->role_id = $this->_identity->getRoleId();
            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->locations_add = $form_values['location_name_hdn'];
            $this->view->locations_edit = $form_values['location_name_edit_hdn'];
            $this->view->action = $form_values['action_hdn'];
            $this->view->counter = $counter;
        }
    }

    /**
     * This method Adds Inventory users
     */
    public function ajaxAddImUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = new Users();
            $users->setUserName($form_values['user_name_add']);
            $users->setEmail($form_values['email']);
            $users->setCellNumber($form_values['phone']);
            $users->setLoginId($form_values['user_name_add']);
            $users->setPassword(md5($form_values['password']));
            if ($form_values['office_type_add'] == '1') {
                $location_id = '10';
                $role_id = '3';
            }
            if ($form_values['office_type_add'] == '2') {
                $location_id = $form_values['combo1_add'];
                $role_id = '4';
            }
            if ($form_values['office_type_add'] == '3') {
                $location_id = $form_values['combo1_add'];
                $role_id = '5';
            }
            if ($form_values['office_type_add'] == '4') {
                $location_id = $form_values['combo2_add'];
                $role_id = '6';
            }
            if ($form_values['office_type_add'] == '5') {
                $location_id = $form_values['combo3_add'];
                $role_id = '7';
            }
            $province_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($province_id);
            $role = $this->_em->find('Roles', $role_id);
            $users->setRole($role);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $users->setStatus('1');
            $user = $this->_em->find('Users', $this->_userid);
            $users->setCreatedBy($user);
            $users->setCreatedDate(App_Tools_Time::now());
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $user_id = $users->getPkId();
            $warehouse_users = new WarehouseUsers();
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse']);
            $warehouse_users->setWarehouse($wh_id);
            $warehouse_users->setIsDefault('1');
            $user_id_i = $this->_em->find('Users', $user_id);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setCreatedBy($user);
            $warehouse_users->setCreatedDate(App_Tools_Time::now());
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
    }

    /**
     * This method Adds Inventory users
     */
    public function ajaxEditImUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = $this->_em->find('Users', $form_values['user_id']);
            $users->setUserName($form_values['user_name_update']);
            $users->setEmail($form_values['email_update']);
            $users->setCellNumber($form_values['phone_update']);
            $users->setLoginId($form_values['user_name_update']);
            if ($form_values['office_type_edit'] == '1') {
                $location_id = '10';
            }
            if ($form_values['office_type_edit'] == '2' || $form_values['office_type_edit'] == '3') {
                $location_id = $form_values['combo1_edit'];
            }
            if ($form_values['office_type_edit'] == '4') {
                $location_id = $form_values['combo2_edit'];
            }
            if ($form_values['office_type_edit'] == '5') {
                $location_id = $form_values['combo3_edit'];
            }
            $province_id = $this->_em->find('Locations', $location_id);
            $users->setLocation($province_id);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $warehouse_users = $this->_em->find('WarehouseUsers', $form_values['warehouse_users_id_edit']);
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse_update']);
            $warehouse_users->setWarehouse($wh_id);
            $user_id_i = $this->_em->find('Users', $form_values['user_id']);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setIsDefault('1');
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
    }

    public function ajaxGetRoutineTableAction() {
        $this->_helper->layout->disableLayout();
        $user = new Model_Users();

        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $this->view->combos = $this->_request->getPost();

            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "users");
            $user->form_values = $this->_request->getPost();
            $user->form_values['office_type'] = '6';
            $params['office_type'] = '6';
            if (!empty($form_values['combo1'])) {
                $params['combo1'] = $form_values['combo1'];
            }
            if (!empty($form_values['combo2'])) {
                $params['combo2'] = $form_values['combo2'];
            }
            if (!empty($form_values['combo3'])) {
                $params['combo3'] = $form_values['combo3'];
            }
            if (!empty($form_values['combo4'])) {
                $params['combo4'] = $form_values['combo4'];
            }
            $user->form_values['page'] = 'routine';

            $user->form_values['loc_id'] = $form_values['combo4'];

            $result = $user->getRIUsers($order, $sort);
            //Paginate the contest results




            $this->view->combos_1 = 'routine';

            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->counter = $counter;
            $this->view->locations_add = $form_values['location_name_hdn'];
            $this->view->locations_edit = $form_values['location_name_edit_hdn'];
            $this->view->action = $form_values['action_hdn'];
            $this->view->pagination_params = $params;
        }
    }

    /**
     * This method Adds Inventory users
     */
    public function ajaxAddRoutineUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = new Users();
            $users->setUserName($form_values['user_name_add']);
            $users->setEmail($form_values['email']);
            $users->setCellNumber($form_values['phone']);
            $users->setLoginId($form_values['user_name_add']);
            $users->setPassword(md5($form_values['password']));
            if ($form_values['office_type_add'] != '1') {
                $location_id = $this->_em->find('Locations', $form_values['combo4_add']);
                $users->setLocation($location_id);
            }
            $role = $this->_em->find('Roles', 8);
            $users->setRole($role);
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $users->setStakeholder($stakeholder);
            $users->setStatus('1');
            $user = $this->_em->find('Users', $this->_userid);
            $users->setCreatedBy($user);
            $users->setCreatedDate(App_Tools_Time::now());
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $user_id = $users->getPkId();
            $warehouse_users = new WarehouseUsers();
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse']);
            $warehouse_users->setWarehouse($wh_id);
            $user_id_i = $this->_em->find('Users', $user_id);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setIsDefault('1');
            $warehouse_users->setCreatedBy($user);
            $warehouse_users->setCreatedDate(App_Tools_Time::now());
            $warehouse_users->setModifiedBy($user);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
    }

    /**
     * This method Adds Inventory users
     */
    public function ajaxEditRoutineUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $users = $this->_em->find('Users', $form_values['user_id']);
            $users->setUserName($form_values['user_name_update']);
            $users->setEmail($form_values['user_name_update']);
            $users->setLoginId($form_values['user_name_update']);
            if ($form_values['office_type_edit'] != '1') {
                $province_id = $this->_em->find('Locations', $form_values['combo4_edit']);
                $users->setLocation($province_id);
            }
            $stakeholder = $this->_em->find('Stakeholders', 9);
            $users->setStakeholder($stakeholder);
            $user = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($user);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $warehouse_users = $this->_em->find('WarehouseUsers', $form_values['warehouse_users_id_edit']);
            $wh_id = $this->_em->find('Warehouses', $form_values['default_warehouse_update']);
            $warehouse_users->setWarehouse($wh_id);
            $user_id_i = $this->_em->find('Users', $form_values['user_id']);
            $warehouse_users->setUser($user_id_i);
            $warehouse_users->setIsDefault('1');
            $created_by = $this->_em->find('Users', $this->_userid);
            $warehouse_users->setModifiedBy($created_by);
            $warehouse_users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($warehouse_users);
            $this->_em->flush();
        }
    }

    /**
     * This method Gets Default Warehouse By Level
     */
    public function getPolicyRolesAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $roles = new Model_Roles();
        $roles->form_values = $form_values;
        $result = $roles->getPolicyRoles();
        $this->view->data = $result;
    }

}
