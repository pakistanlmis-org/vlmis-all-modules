<?php

/**
 * Iadmin_ManageLocationsController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manage Locations
 */
class Iadmin_ManageLocationsController extends App_Controller_Base {

    /**
     * Iadmin_ManageLocationsController index
     */
    public function indexAction() {

        $form = new Form_Iadmin_Locations();
        $locations = new Model_Locations();
        $params = array();

        if ($this->_request->isPost()) {
            if ($this->_request->getPost()) {
                $form_values = $this->_request->getPost();
                $this->view->combos = $this->_request->getPost();
                $form->province_id->setValue($form_values['combo1']);
                $form->district_id->setValue($form_values['combo2']);
                $form->parent_id->setValue($form_values['combo3']);
                if (!empty($form_values['location_level'])) {
                    $params['location_level'] = $form_values['location_level'];
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
                if (!empty($form_values['not_used'])) {
                    $params['used'] = $form_values['not_used'];
                }

                $sort = $this->_getParam("sort", "asc");
                $order = $this->_getParam("order", "location");
                $locations->form_values = $this->_request->getPost();
                $result = $locations->getAllLocations($order, $sort);

                $this->view->delete_location = $form_values['not_used'];
                $this->view->form = $form;
                $this->view->paginator = $result;
                $this->view->sort = $sort;
                $this->view->order = $order;
                $this->view->pagination_params = $params;
            }
        } else {

            $locations->form_values = $this->_request->getParams();
            $params = $this->_request->getParams();
            $this->view->combos = $this->_request->getParams();

            $form->province_id->setValue($this->_identity->getProvinceId());
            if ($this->_identity->getRoleId() == 38 || 39) {
                $form->province_id->setValue($this->_identity->getProvinceId());
                $form_values['combo1'] = $this->_identity->getProvinceId();
            } else {
                $form->province_id->setValue($this->_getParam('combo1'));
                $form_values['combo1'] = $this->_getParam('combo1');
            }

            if ($this->_identity->getRoleId() == 39) {
                $form->district_id->setValue($this->_identity->getDistrictId());
                $form_values['combo2'] = $this->_identity->getDistrictId();
            } else {
                $form->district_id->setValue($this->_getParam('combo2'));
            }
            if ($this->_identity->getRoleId() == 38) {
                $form_values['location_level'] = 3;
            } else if ($this->_identity->getRoleId() == 39) {
                $form_values['location_level'] = 5;
            } else {
                $form_values['location_level'] = '';
            }


            $form->parent_id->setValue($this->_getParam('combo3'));
            $this->view->delete_location = $this->_getParam('used');
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "location");
            $locations->form_values = $form_values;
            $result = $locations->getAllLocations($order, $sort);


            $this->view->form = $form;

            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->pagination_params = $params;
        }
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
        $this->view->role_id = $this->_identity->getRoleId();
        $base_url = Zend_Registry::get('baseurl');


        $this->view->inlineScript()->appendFile($base_url . '/js/locations_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_add_combos.js');

        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css');
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        // $this->_helper->layout->setLayout("ajax");
        $this->_helper->layout->disableLayout();
        $location_id = $this->_request->getParam('location_id', '');

        $locations = $this->_em->find('Locations', $location_id);
        $form = new Form_Iadmin_Locations();

        $form->location_name_update->setValue($locations->getLocationName());
        $form->location_id->setValue($location_id);
        $form->location_type->setValue($locations->getGeoLevel()->getPkId());
        $form->province_id_edit->setValue($locations->getProvince()->getPkId());
        if ($locations->getGeoLevel()->getPkId() != 3 && $locations->getGeoLevel()->getPkId() != 4) {
            $form->district_id_edit->setValue($locations->getDistrict()->getPkId());
        }
        $estimation_date = date('Y') . '-01-01 00:00:00';
        $location_population = $this->_em->getRepository("LocationPopulations")->findBy(array('location' => $location_id, 'estimationDate' => $estimation_date));
        if (!empty($location_population)) {
            $location_population_obj = $this->_em->getRepository("LocationPopulations")->find($location_population[0]->getPkId());
            $form->population_update->setValue($location_population_obj->getPopulation());
        }
        $form->parent_id_edit->setValue($locations->getParent()->getPkId());

        $form->location_type_id_update_hidden->setValue($locations->getLocationType()->getPkId());
        $form->ccm_location_id_update->setValue($locations->getCcmLocationId());
        $this->view->form = $form;
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
        $this->view->role_id = $this->_identity->getRoleId();
        $base_url = Zend_Registry::get('baseurl');
        //$this->view->inlineScript()->appendFile($base_url . '/js/locations_edit_combos.js');
    }

    /**
     * This method adds Location
     */
    public function addAction() {

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();

            $locations = new Locations();
            if ($form_values['location_level_add'] == 3) {

                $parentId = $form_values['combo1_add'];
            }
            if ($form_values['location_level_add'] == 4) {

                $parentId = $form_values['combo1_add'];
            }
            if ($form_values['location_level_add'] == 5) {

                $parentId = $form_values['combo2_add'];
            }
            if ($form_values['location_level_add'] == 6) {

                $parentId = $form_values['combo3_add'];
            }
            $province_id = $this->_em->find('Locations', $form_values['combo1_add']);
            $locations->setProvince($province_id);
            $locations->setLocationName($form_values['location_name_add']);
            $geo_level_id = $this->_em->find('GeoLevels', $form_values['location_level_add']);
            $locations->setGeoLevel($geo_level_id);
            $location_types = $this->_em->find('LocationTypes', $form_values['location_type_id']);
            $locations->setLocationType($location_types);
            $parent_id = $this->_em->find('Locations', $parentId);
            $locations->setParent($parent_id);
            if ($form_values['location_level_add'] == 5 || $form_values['location_level_add'] == 6) {
                $district_id = $this->_em->find('Locations', $form_values['combo2_add']);
                $locations->setDistrict($district_id);
            }
            if (!empty($form_values['ccm_location_id'])) {
                $locations->setCcmLocationId($form_values['ccm_location_id']);
            }
            $created_by = $this->_em->find('Users', $this->_userid);
            $locations->setStatus(1);
            $locations->setCreatedBy($created_by);
            $locations->setCreatedDate(App_Tools_Time::now());
            $locations->setModifiedBy($created_by);
            $locations->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations);
            $this->_em->flush();


            $locations_pop = new LocationPopulations();

            $loc_id = $locations->getPkId();
            $locations_pop->setPopulation($form_values['population']);
            $location_id = $this->_em->find('Locations', $loc_id);
            $locations_pop->setLocation($location_id);
            $locations_pop->setEstimationDate(new \DateTime(Date("Y") . "-01-01 00:00:00"));


            $created_by = $this->_em->find('Users', $this->_userid);
            $locations_pop->setCreatedBy($created_by);
            $locations_pop->setCreatedDate(App_Tools_Time::now());
            $locations_pop->setModifiedBy($created_by);
            $locations_pop->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations_pop);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-locations");
    }

    /**
     * This method updates Location
     */
    public function updateAction() {

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();

            $locations = $this->_em->find('Locations', $form_values['location_id']);
            if ($form_values['location_level_edit'] == 3 || $form_values['location_level_edit'] == 4) {

                $parentId = $form_values['combo1_edit'];
            }
            if ($form_values['location_level_edit'] == 5) {

                $parentId = $form_values['combo2_edit'];
            }
            if ($form_values['location_level_edit'] == 6) {

                $parentId = $form_values['combo3_edit'];
            }
            $province_id = $this->_em->find('Locations', $form_values['combo1_edit']);
            $locations->setProvince($province_id);
            $locations->setLocationName($form_values['location_name_update']);
            $geo_level_id = $this->_em->find('GeoLevels', $form_values['location_level_edit']);
            $locations->setGeoLevel($geo_level_id);

            $parent_id = $this->_em->find('Locations', $parentId);
            $locations->setParent($parent_id);
            if ($form_values['location_level_edit'] == 5 || $form_values['location_level_edit'] == 6) {
                $district_id = $this->_em->find('Locations', $form_values['combo2_edit']);
                $locations->setDistrict($district_id);
            }
            if (!empty($form_values['ccm_location_id_update'])) {
                $locations->setCcmLocationId($form_values['ccm_location_id_update']);
            }
            $created_by = $this->_em->find('Users', $this->_userid);
            $locations->setModifiedBy($created_by);
            $locations->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations);
            $this->_em->flush();



            $created_by = $this->_em->find('Users', $this->_userid);
            $locations_pop->setCreatedBy($created_by);
            $locations_pop->setCreatedDate(App_Tools_Time::now());
            $locations_pop->setModifiedBy($created_by);
            $locations_pop->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations_pop);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-locations");
    }

    /**
     * This method checks Location whether it allready exists
     */
    public function checkLocationAction() {
        $this->_helper->layout->disableLayout();

        $form_values = $this->_request->getPost();

        $locations = new Model_Locations();
        $locations->form_values = $form_values;
        $result = $locations->checkLocation();
        $this->view->result = $result;
    }

    /**
     * Check Location Update
     */
    public function checkLocationUpdateAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();

        $locations = new Model_Locations();
        $locations->form_values = $form_values;
        $result = $locations->checkLocationUpdate();
        $this->view->result = $result;
    }

    /**
     * This method gets Location Types
     */
    public function getLocationTypesAction() {
        $this->_helper->layout->disableLayout();

        $geo_level_id = $this->_request->geo_level_id;
        $location = new Model_Locations();
        $location->form_values = $geo_level_id;
        $result = $location->getLocationType();
        $this->view->data = $result;
    }

    /**
     * This method checks Cold Chain Location
     */
    public function checkCcmLocationAction() {
        $this->_helper->layout->disableLayout();

        $form_values = $this->_request->getPost();
        $location = new Model_Locations();
        $location->form_values = $form_values;
        $result = $location->checkCcmLocationId();
        $this->view->result = $result;
    }

    /**
     * This method checks Cold Chain Location Update
     */
    public function checkCcmLocationUpdateAction() {
        $this->_helper->layout->disableLayout();

        $form_values = $this->_request->getPost();
        $location = new Model_Locations();
        $location->form_values = $form_values;
        $result = $location->checkCcmLocationIdUpdate();
        $this->view->result = $result;
    }

    /**
     * This method deletes Location
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $warehouse_id = $this->_request->getParam("warehouse_id");
        $warehouse_status = $this->_request->getParam("status");
        $warehouses = $this->_em->getRepository("Locations")->find($warehouse_id);

        $msg = "";
        if ($warehouse_status == 1) {
            $warehouses->setStatus(0);
            $msg = 'Activate';
        } else if ($warehouse_status == 0) {
            $warehouses->setStatus(1);
            $msg = 'Deactivate';
        }

        $created_by = $this->_em->find('Users', $this->_userid);
        $warehouses->setModifiedBy($created_by);
        $warehouses->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($warehouses);
        $this->view->ajaxaction = $this->_request->getParam('ajaxaction');
        echo $msg;
        return $this->_em->flush();
    }

    /**
     * This method retrieves Location Type
     */
    public function locationTypeAction() {
        $form = new Form_Iadmin_LocationTypeSearch();
        $form_add = new Form_Iadmin_LocationTypeAdd();

        $location_type = new Model_LocationTypes();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $location_type_name = $form->getValue('location_type_name');
                $status = $form->getValue('status');

                if (!empty($location_type_name)) {
                    $location_type->form_values['locationTypeName'] = $location_type_name;
                }
                if (!empty($status)) {
                    $location_type->form_values['status'] = $status;
                }
            }
            $form->location_type_name->setValue($location_type_name);
            $form->status->setValue($status);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "location_type_name");

        $result = $location_type->getAllLocationTypes($order, $sort);

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
     * This method chanes location status, making it active and inactive
     */
    public function ajaxChangeStatusAction() {
        $this->_helper->layout->disableLayout();
        $row = $this->_em->getRepository("LocationTypes")->find($this->_request->getParam('id'));
        if ($this->_request->getParam('ajaxaction') == 'deactive') {
            $row->setStatus('1');
        } else if ($this->_request->getParam('ajaxaction') == 'active') {
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
     * This method Adds Location Type
     */
    public function addLocationTypeAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {

            $form_values = $this->_request->getPost();


            $location_type = new LocationTypes();
            $location_type->setLocationTypeName($form_values['location_type_name']);


            $geo_level = $this->_em->getRepository('GeoLevels')->find($form_values['geo_level_id']);
            $location_type->setGeoLevel($geo_level);


            $location_type->setStatus($form_values['status']);
            $created_by = $this->_em->find('Users', $this->_userid);
            $location_type->setCreatedBy($created_by);
            $location_type->setCreatedDate(App_Tools_Time::now());
            $location_type->setModifiedBy($created_by);
            $location_type->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($location_type);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-locations/location-type?success=1");
    }

    /**
     * This method retrieves the location types for edit
     */
    public function ajaxEditLocTypeAction() {
        $this->_helper->layout->disableLayout();
        $location_type_id = $this->_request->getParam('location_type_id', '');

        $locationType = $this->_em->find('LocationTypes', $location_type_id);
        $form = new Form_Iadmin_LocationTypeAdd();

        $form->location_type_name->setValue($locationType->getLocationTypeName());
        $form->geo_level_id->setValue($locationType->getGeoLevel()->getPkId());

        $form->location_type_id->setValue($locationType->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method Updates Location Type
     */
    public function updateLocationTypeAction() {

        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $locationType = $this->_em->getRepository("LocationTypes")->find($form_values['location_type_id']);
            $locationType->setLocationTypeName($form_values['location_type_name']);

            $geo_level = $this->_em->getRepository('GeoLevels')->find($form_values['geo_level_id']);
            $locationType->setGeoLevel($geo_level);


            $created_by = $this->_em->find('Users', $this->_userid);
            $locationType->setModifiedBy($created_by);
            $locationType->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locationType);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-locations/location-type?success=2");
    }

    /**
     * This method searches location population
     */
    public function locationPopulationAction() {

        $form = new Form_Iadmin_Locations();
        $locations = new Model_Locations();
        $params = array();
        if ($this->_request->isPost()) {
            if ($this->_request->getPost()) {
                $form_values = $this->_request->getPost();
                $this->view->combos = $this->_request->getPost();


                if (!empty($form_values['location_level2'])) {
                    $params['location_level2'] = $form_values['location_level2'];
                }
                if (!empty($form_values['year1'])) {
                    $params['year1'] = $form_values['year1'];
                }
                $sort = $this->_getParam("sort", "asc");
                $order = $this->_getParam("order", "location");
                $locations->form_values = $this->_request->getPost();
                $result = $locations->getAllLocationsPopulation($order, $sort);
                $this->view->delete_location = $form_values['not_used'];
                $this->view->form = $form;
                $this->view->paginator = $result;
                $this->view->sort = $sort;
                $this->view->order = $order;
                $this->view->pagination_params = $params;
            }
        } else {

            $locations->form_values = $this->_request->getParams();
            $params = $this->_request->getParams();
            $this->view->combos = $this->_request->getParams();
            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "location");

            $result = $locations->getAllLocationsPopulation($order, $sort);



            $this->view->form = $form;

            $this->view->paginator = $result;
            $this->view->sort = $sort;
            $this->view->order = $order;
            $this->view->pagination_params = $params;
        }
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_add_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css');
    }

    /**
     * This method retrieves location population information for Edit
     */
    public function ajaxPopulationEditAction() {
        $this->_helper->layout->setLayout("ajax");
        $location_id = $this->_request->getParam('location_id', '');
        $prov = $this->_request->getParam('prov', '');
        $geolevelname = $this->_request->getParam('geolevelname', '');
        $population = $this->_request->getParam('population', '');
        $locPopId = $this->_request->getParam('locPopId', '');
        $estimationDate = $this->_request->getParam('locPopEstimationDate', '');

        $locations = $this->_em->find('Locations', $location_id);
        $form = new Form_Iadmin_Locations();


        $form->location_pop_name_update->setValue($locations->getLocationName());
        $form->location_id->setValue($location_id);
        $form->location_type->setValue($locations->getGeoLevel()->getPkId());
        $form->location_geo_level->setValue($geolevelname);
        $form->province_id_edit->setValue($locations->getProvince()->getPkId());
        $form->location_province->setValue($prov);
        $form->population->setValue($population);
        $form->location_population_id->setValue($locPopId);
        $form->location_pop_estimation_date->setValue($estimationDate);
        if ($locations->getGeoLevel()->getPkId() != 3 && $locations->getGeoLevel()->getPkId() != 4) {
            $form->district_id_edit->setValue($locations->getDistrict()->getPkId());
        }

        $form->parent_id_edit->setValue($locations->getParent()->getPkId());

        $form->location_type_id_update_hidden->setValue($locations->getLocationType()->getPkId());
        $form->ccm_location_id_update->setValue($locations->getCcmLocationId());
        $this->view->form = $form;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/locations_edit_combos.js');
    }

    /**
     * This method updates population
     */
    public function updatePopulationAction() {

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();

            $locpop = $this->_em->getRepository("LocationPopulations")->find($form_values['location_population_id']);

            $population = $form_values['population'];

            $locpop->setPopulation($population);
            $created_by = $this->_em->find('Users', $this->_userid);
            $locpop->setModifiedBy($created_by);
            $locpop->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locpop);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-locations/location-population");
    }

    /**
     * This method adds Population
     */
    public function addPopulationAction() {

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();

            $locations_pop = new LocationPopulations();


            $locations_pop->setPopulation($form_values['population']);
            $location_id = $this->_em->find('Locations', $form_values['location_name']);
            $locations_pop->setLocation($location_id);
            $locations_pop->setEstimationDate(new \DateTime($form_values['year'] . "-01-01 00:00:00"));
            $created_by = $this->_em->find('Users', $this->_userid);
            $locations_pop->setCreatedBy($created_by);
            $locations_pop->setCreatedDate(App_Tools_Time::now());
            $locations_pop->setModifiedBy($created_by);
            $locations_pop->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations_pop);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-locations/location-population");
    }

    public function ajaxGetTableAction() {
        $this->_helper->layout->disableLayout();
        $form = new Form_Iadmin_Locations();
        $locations = new Model_Locations();
        $params = array();

        if ($this->_request->isPost()) {
            if ($this->_request->getPost()) {
                $form_values = $this->_request->getPost();

                $sort = $this->_getParam("sort", "asc");
                $order = $this->_getParam("order", "location");

                $locations->form_values = $form_values;
                $result = $locations->getAllLocations($order, $sort);

                $this->view->delete_location = $form_values['not_used'];
                $this->view->form = $form;
                $this->view->paginator = $result;
                $this->view->sort = $sort;
                $this->view->order = $order;
                $this->view->locations_add = $form_values['location_name_hdn'];
                $this->view->locations_edit = $form_values['location_name_edit_hdn'];
                $this->view->action = $form_values['action_hdn'];
                $this->view->pagination_params = $params;
            }
        }
    }

    public function ajaxAddLocationAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();

            $locations = new Locations();
            if ($form_values['location_level_add'] == 3) {

                $parentId = $form_values['combo1_add'];
            }
            if ($form_values['location_level_add'] == 4) {

                $parentId = $form_values['combo1_add'];
            }
            if ($form_values['location_level_add'] == 5) {

                $parentId = $form_values['combo2_add'];
            }
            if ($form_values['location_level_add'] == 6) {

                $parentId = $form_values['combo3_add'];
            }
            $province_id = $this->_em->find('Locations', $form_values['combo1_add']);
            $locations->setProvince($province_id);
            $locations->setLocationName($form_values['location_name_add']);
            $geo_level_id = $this->_em->find('GeoLevels', $form_values['location_level_add']);
            $locations->setGeoLevel($geo_level_id);
            $location_types = $this->_em->find('LocationTypes', $form_values['location_type_id']);
            $locations->setLocationType($location_types);
            $parent_id = $this->_em->find('Locations', $parentId);
            $locations->setParent($parent_id);
            if ($form_values['location_level_add'] == 5 || $form_values['location_level_add'] == 6) {
                $district_id = $this->_em->find('Locations', $form_values['combo2_add']);
                $locations->setDistrict($district_id);
            }
            if (!empty($form_values['ccm_location_id'])) {
                $locations->setCcmLocationId($form_values['ccm_location_id']);
            }
            $created_by = $this->_em->find('Users', $this->_userid);
            $locations->setCreatedBy($created_by);
            $locations->setCreatedDate(App_Tools_Time::now());
            $locations->setModifiedBy($created_by);
            $locations->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations);
            $this->_em->flush();


            $locations_pop = new LocationPopulations();

            $loc_id = $locations->getPkId();
            $locations_pop->setPopulation($form_values['population']);
            $location_id = $this->_em->find('Locations', $loc_id);
            $locations_pop->setLocation($location_id);
            $locations_pop->setEstimationDate(new \DateTime(Date("Y") . "-01-01 00:00:00"));
            $created_by = $this->_em->find('Users', $this->_userid);
            $locations_pop->setCreatedBy($created_by);
            $locations_pop->setCreatedDate(App_Tools_Time::now());
            $locations_pop->setModifiedBy($created_by);
            $locations_pop->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations_pop);
            $this->_em->flush();
        }
    }

    public function ajaxEditLocationAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();

            $locations = $this->_em->find('Locations', $form_values['location_id']);
            if ($form_values['location_level_edit'] == 3 || $form_values['location_level_edit'] == 4) {

                $parentId = $form_values['combo1_edit'];
            }
            if ($form_values['location_level_edit'] == 5) {

                $parentId = $form_values['combo2_edit'];
            }
            if ($form_values['location_level_edit'] == 6) {

                $parentId = $form_values['combo3_edit'];
            }
            $province_id = $this->_em->find('Locations', $form_values['combo1_edit']);
            $locations->setProvince($province_id);
            $locations->setLocationName($form_values['location_name_update']);
            $geo_level_id = $this->_em->find('GeoLevels', $form_values['location_level_edit']);
            $locations->setGeoLevel($geo_level_id);

            $parent_id = $this->_em->find('Locations', $parentId);
            $locations->setParent($parent_id);
            if ($form_values['location_level_edit'] == 5 || $form_values['location_level_edit'] == 6) {
                $district_id = $this->_em->find('Locations', $form_values['combo2_edit']);
                $locations->setDistrict($district_id);
            }
            if (!empty($form_values['ccm_location_id_update'])) {
                $locations->setCcmLocationId($form_values['ccm_location_id_update']);
            }

            $estimation_date = date('Y') . '-01-01 00:00:00';
            $location_population = $this->_em->getRepository("LocationPopulations")->findBy(array('location' => $form_values['location_id'], 'estimationDate' => $estimation_date));
            if (!empty($location_population)) {
                $locations_pop = $this->_em->getRepository("LocationPopulations")->find($location_population[0]->getPkId());
                $loc_id = $locations->getPkId();
                $locations_pop->setPopulation($form_values['population_update']);
                $location_id = $this->_em->find('Locations', $loc_id);
                $locations_pop->setLocation($location_id);
                $locations_pop->setEstimationDate(new DateTime(Date("Y") . "-01-01 00:00:00"));
            }
            $created_by = $this->_em->find('Users', $this->_userid);
            $locations->setModifiedBy($created_by);
            $locations->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($locations);
            $this->_em->flush();
        }
    }

}
