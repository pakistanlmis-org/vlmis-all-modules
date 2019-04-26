<?php

/**
 * Cadmin_ManageHealthFacilityController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  This Controller Manages Health Facility
*/

class Cadmin_ManageHealthFacilityController extends App_Controller_Base {

    /**
     * This method searches Health Facility 
     */
    public function indexAction() {
        $form = new Form_Cadmin_HealthFacilitySearch();
        $form_add = new Form_Cadmin_HealthFacility();

        $params = array();

        $ccm_wh = new Model_CcmWarehouses();
        $form_values = $this->_request->getPost();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $form->office_id->setValue($form_values['office']);
                $form->combo1_id->setValue($form_values['combo1']);
                $form->combo2_id->setValue($form_values['combo2']);
                $form->warehouse_id->setValue($form_values['warehouse3']);
                $office = $form_values['office'];
                $combo1 = $form_values['combo1'];
                $combo2 = $form_values['combo2'];
                $warehouse = $form_values['warehouse3'];

                if (!empty($office)) {
                    $params['office'] = $office;
                }
                if (!empty($combo1)) {
                    $params['combo1'] = $combo1;
                }
                if (!empty($combo2)) {
                    $params['combo2'] = $combo2;
                }
                if (!empty($warehouse)) {
                    $params['warehouse'] = $warehouse;
                }
            }
        } else {
            $office = $this->_getParam('office');
            $combo1 = $this->_getParam('combo1');
            $combo2 = $this->_getParam('combo2');
            $warehouse = $this->_getParam('warehouse3');
            if (!empty($office)) {
                $params['office'] = $office;
                $form->office_id->setValue($office);
            }
            if (!empty($combo1)) {
                $params['combo1'] = $combo1;

                $form->combo1_id->setValue($combo1);
            }
            if (!empty($combo2)) {
                $params['combo2'] = $combo2;
                $form->combo2_id->setValue($combo2);
            }
            if (!empty($warehouse)) {
                $params['warehouse3'] = $warehouse;
                $form->warehouse_id->setValue($warehouse);
            }
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "login_id");
        $ccm_wh->form_values = $params;
        $result = $ccm_wh->getAllHealthFacility($order, $sort);

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);
        $list_detail = new Model_ListDetail();

        $list_detail->form_values['master_id'] = Model_ListMaster::VACCINATION_STAFF;
        $result1 = $list_detail->getListDetailByMasterId();
        $this->view->epi_vaccination_staff = $result1;

        $list_detail->form_values['master_id'] = Model_ListMaster::SOLAR_ENERGY;
        $result2 = $list_detail->getListDetailByMasterId();
        $this->view->solar_energy = $result2;
        $list_detail->form_values['master_id'] = Model_ListMaster::SERVICE_TYPES;
        $result23 = $list_detail->getListDetailByMasterId();
        $this->view->services_type = $result23;

        $this->view->form = $form;
        $this->view->form_add = $form_add;
        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->pagination_params = $params;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos3.js');
    }

    /**
     * This method adds Health Facility
     */
    public function addAction() {
        $form = new Form_Cadmin_HealthFacility();
        $form_values = $this->_request->getPost();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
                $ccm_warehouses = new CcmWarehouses();
                $ccm_warehouses->setRoutineImmunizationIcepackRequirments($form_values['routine_immunization_ice_pack']);
                $ccm_warehouses->setCampaignIcepackRequirments($form_values['snid_nid_ice_pack']);
                $grid_electricity_availibility = $this->_em->getRepository("ListDetail")->find($form_values['grid_electricity_availibility']);
                $ccm_warehouses->setElectricityAvailability($grid_electricity_availibility);
                $vaccine_supply_mode = $this->_em->getRepository("ListDetail")->find($form_values['vaccine_supply_mode']);
                $ccm_warehouses->setVaccineSupplyMode($vaccine_supply_mode);
                $warehouse = $this->_em->getRepository("Warehouses")->find($form_values['warehouse']);
                $ccm_warehouses->setWarehouse($warehouse);

                $created_by = $this->_em->find('Users', $this->_user_id);
                $ccm_warehouses->setCreatedBy($created_by);
                $ccm_warehouses->setCreatedDate(App_Tools_Time::now());
                $ccm_warehouses->setModifiedBy($created_by);
                $ccm_warehouses->setModifiedDate(App_Tools_Time::now());

                $this->_em->persist($ccm_warehouses);
                $this->_em->flush();
                $ccm_warehouse_id = $ccm_warehouses->getPkId();
                if (!empty($form_values['epi_vaccination_staff'])) {
                    foreach ($form_values['epi_vaccination_staff'] as $epi_vaccination) {
                        $ccm_vaccination = new CcmWarehousesVaccinationStaff();
                        $vaccinationStaff = $this->_em->getRepository("ListDetail")->find($epi_vaccination);
                        $ccm_vaccination->setVaccinationStaff($vaccinationStaff);
                        $ccm_warehouses_id = $this->_em->getRepository("CcmWarehouses")->find($ccm_warehouse_id);
                        $ccm_vaccination->setCcmWarehouse($ccm_warehouses_id);

                        $ccm_vaccination->setCreatedBy($created_by);
                        $ccm_vaccination->setCreatedDate(App_Tools_Time::now());
                        $ccm_vaccination->setModifiedBy($created_by);
                        $ccm_vaccination->setModifiedDate(App_Tools_Time::now());

                        $this->_em->persist($ccm_vaccination);
                        $this->_em->flush();
                    }
                }

                foreach ($form_values['services_type'] as $services) {
                    $services_type = new WarehousesServiceTypes();
                    $service_id = $this->_em->getRepository("ListDetail")->find($services);
                    $services_type->setServiceType($service_id);
                    $warehouses_id = $this->_em->getRepository("Warehouses")->find($form_values['warehouse']);
                    $services_type->setWarehouse($warehouses_id);

                    $services_type->setCreatedBy($created_by);
                    $services_type->setCreatedDate(App_Tools_Time::now());
                    $services_type->setModifiedBy($created_by);
                    $services_type->setModifiedDate(App_Tools_Time::now());

                    $this->_em->persist($services_type);
                    $this->_em->flush();
                }
                if (!empty($form_values['solar_energy'])) {
                    foreach ($form_values['solar_energy'] as $solar_energy) {
                        $ccm_solar = new CcmWarehousesSolarEnergy();
                        $solarEnergy = $this->_em->getRepository("ListDetail")->find($solar_energy);
                        $ccm_solar->setSolarEnergy($solarEnergy);
                        $ccm_warehouses_id = $this->_em->getRepository("CcmWarehouses")->find($ccm_warehouse_id);
                        $ccm_solar->setCcmWarehouse($ccm_warehouses_id);

                        $ccm_solar->setCreatedBy($created_by);
                        $ccm_solar->setCreatedDate(App_Tools_Time::now());
                        $ccm_solar->setModifiedBy($created_by);
                        $ccm_solar->setModifiedDate(App_Tools_Time::now());

                        $this->_em->persist($ccm_solar);
                        $this->_em->flush();
                    }
                }

                $warehouses = $this->_em->getRepository("Warehouses")->find($form_values['warehouse']);
                $warehouse_types = $this->_em->getRepository("WarehouseTypes")->find($form_values['health_facility_type']);
                $warehouses->setWarehouseType($warehouse_types);

                $warehouses->setCreatedBy($created_by);
                $warehouses->setCreatedDate(App_Tools_Time::now());
                $warehouses->setModifiedBy($created_by);
                $warehouses->setModifiedDate(App_Tools_Time::now());

                $this->_em->persist($warehouses);
                $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-health-facility");
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->setLayout("ajax");
        $ccm_warehouse_id = $this->_request->getParam('ccm_warehouse_id', '');

        $ccm_warehouses = $this->_em->find('CcmWarehouses', $ccm_warehouse_id);


        $form = new Form_Cadmin_HealthFacility();
        $form->addFields();
        $form->addHidden();

        $form->routine_immunization_ice_pack->setValue($ccm_warehouses->getRoutineImmunizationIcepackRequirments());
        $form->snid_nid_ice_pack->setValue($ccm_warehouses->getCampaignIcepackRequirments());
        $form->grid_electricity_availibility->setValue($ccm_warehouses->getElectricityAvailability()->getPkId());
        $form->vaccine_supply_mode->setValue($ccm_warehouses->getVaccineSupplyMode()->getPkId());
        $form->id->setValue($ccm_warehouses->getPkId());
        $list_detail = new Model_ListDetail();
        $list_detail->form_values['master_id'] = Model_ListMaster::SERVICE_TYPES;
        $result23 = $list_detail->getListDetailByMasterId();
        $this->view->services_type = $result23;
        $list_detail->form_values['master_id'] = Model_ListMaster::VACCINATION_STAFF;
        $result1 = $list_detail->getListDetailByMasterId();
        $this->view->epi_vaccination_staff = $result1;
        $ccm_wh_vaccination_staff = $this->_em->getRepository("CcmWarehousesVaccinationStaff")->findBy(array('ccmWarehouse' => $ccm_warehouse_id));
        $this->view->update_vaccination_staff = $ccm_wh_vaccination_staff;
        $vac_staff = array();
        foreach ($ccm_wh_vaccination_staff as $row_1) {
            array_push($vac_staff, $row_1->getVaccinationStaff()->getPkId());
        }
        $this->view->vac_staff = $vac_staff;

        $services_type = $this->_em->getRepository("WarehousesServiceTypes")->findBy(array('warehouse' => $ccm_warehouses->getWarehouse()->getPkId()));
        $this->view->update_services_type = $services_type;
        $ser_type = array();
        foreach ($services_type as $row) {
            array_push($ser_type, $row->getServiceType()->getPkId());
        }

        $this->view->ser_type = $ser_type;


        $warehouse_table = $this->_em->getRepository("Warehouses")->find($ccm_warehouses->getWarehouse()->getPkId());

        $form->health_facility_type->setValue($warehouse_table->getWarehouseType()->getPkId());


        $list_detail->form_values['master_id'] = Model_ListMaster::SOLAR_ENERGY;
        $result2 = $list_detail->getListDetailByMasterId();
        $this->view->solar_energy = $result2;

        $solar_energy = $this->_em->getRepository("CcmWarehousesSolarEnergy")->findBy(array('ccmWarehouse' => $ccm_warehouse_id));
        $so_energy = array();
        foreach ($solar_energy as $row_2) {
            array_push($so_energy, $row_2->getSolarEnergy()->getPkId());
        }
        $form->old_warehouse->setValue($ccm_warehouses->getWarehouse()->getWarehouseName());
        $form->old_warehouse_val->setValue($ccm_warehouses->getWarehouse()->getpkId());
        $warehouses = $this->_em->find('Warehouses', $ccm_warehouses->getWarehouse()->getpkId());
        $form->office_level->setValue($warehouses->getStakeholderOffice()->getPkId());
        $this->view->so_energy = $so_energy;
        $this->view->form = $form;
        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos2.js');
    }

    /**
     * This method updates Health Facility
     */
    public function updateAction() {
        $form = new Form_Cadmin_HealthFacility();
        $form->addFields();
        $form->addHidden();
        $form_values = $this->_request->getPost();
        if ($this->_request->isPost()) {
            $created_by = $this->_em->find('Users', $this->_user_id);

            if ($form->isValid($this->_request->getPost())) {

                $ccm_warehouse_id = $form->id->getValue();
                $ccm_warehouses = $this->_em->find('CcmWarehouses', $ccm_warehouse_id);

                $ccm_warehouses->setRoutineImmunizationIcepackRequirments($form_values['routine_immunization_ice_pack']);
                $ccm_warehouses->setCampaignIcepackRequirments($form_values['snid_nid_ice_pack']);
                $grid_electricity_availibility = $this->_em->getRepository("ListDetail")->find($form_values['grid_electricity_availibility']);
                $ccm_warehouses->setElectricityAvailability($grid_electricity_availibility);
                $vaccine_supply_mode = $this->_em->getRepository("ListDetail")->find($form_values['vaccine_supply_mode']);
                $ccm_warehouses->setVaccineSupplyMode($vaccine_supply_mode);

                $warehouse = $this->_request->getPost('warehouse2');
                if (!empty($warehouse)) {
                    $warehouse_id = $this->_em->getRepository("Warehouses")->find($warehouse);
                    $ccm_warehouses->setWarehouse($warehouse_id);
                }
                $ccm_wh_vac_staff = $this->_em->getRepository("CcmWarehousesVaccinationStaff")->findBy(array('ccmWarehouse' => $ccm_warehouse_id));

                foreach ($ccm_wh_vac_staff as $ccm_vac_staff) {
                    $vac_id = $this->_em->find('CcmWarehousesVaccinationStaff', $ccm_vac_staff->getPkId());
                    $this->_em->remove($vac_id);
                    $this->_em->flush();
                }

                foreach ($form_values['epi_vaccination_staff'] as $epi_vaccination) {
                    $ccm_vaccination = new CcmWarehousesVaccinationStaff();
                    $vaccinationStaff = $this->_em->getRepository("ListDetail")->find($epi_vaccination);
                    $ccm_vaccination->setVaccinationStaff($vaccinationStaff);
                    $ccm_warehouses_id = $this->_em->getRepository("CcmWarehouses")->find($ccm_warehouse_id);
                    $ccm_vaccination->setCcmWarehouse($ccm_warehouses_id);

                    $ccm_vaccination->setModifiedBy($created_by);
                    $ccm_vaccination->setModifiedDate(App_Tools_Time::now());

                    $this->_em->persist($ccm_vaccination);
                    $this->_em->flush();
                }

                $ccm_wh_solar_energy = $this->_em->getRepository("CcmWarehousesSolarEnergy")->findBy(array('ccmWarehouse' => $ccm_warehouse_id));

                foreach ($ccm_wh_solar_energy as $ccm_solar_energy) {
                    $solar_id = $this->_em->find('CcmWarehousesSolarEnergy', $ccm_solar_energy->getPkId());
                    $this->_em->remove($solar_id);
                    $this->_em->flush();
                }
                foreach ($form_values['solar_energy'] as $solar_energy) {
                    $ccm_solar = new CcmWarehousesSolarEnergy();
                    $solarEnergy = $this->_em->getRepository("ListDetail")->find($solar_energy);
                    $ccm_solar->setSolarEnergy($solarEnergy);
                    $ccm_warehouses_id = $this->_em->getRepository("CcmWarehouses")->find($ccm_warehouse_id);
                    $ccm_solar->setCcmWarehouse($ccm_warehouses_id);

                    $ccm_solar->setModifiedBy($created_by);
                    $ccm_solar->setModifiedDate(App_Tools_Time::now());

                    $this->_em->persist($ccm_solar);
                    $this->_em->flush();
                }

                $services_type = $this->_em->getRepository("WarehousesServiceTypes")->find($ccm_warehouses->getWarehouse()->getPkId());

                foreach ($services_type as $ser_type) {
                    $ser_id = $this->_em->find('WarehousesServiceTypes', $ser_type->getPkId());
                    $this->_em->remove($ser_id);
                    $this->_em->flush();
                }

                foreach ($form_values['services_type'] as $services) {
                    $services_type = new WarehousesServiceTypes();
                    $service_id = $this->_em->getRepository("ListDetail")->find($services);
                    $services_type->setServiceType($service_id);
                    $warehouses_id = $this->_em->getRepository("Warehouses")->find($ccm_warehouses->getWarehouse()->getPkId());
                    $services_type->setWarehouse($warehouses_id);

                    $services_type->setModifiedBy($created_by);
                    $services_type->setModifiedDate(App_Tools_Time::now());

                    $this->_em->persist($services_type);
                    $this->_em->flush();
                }
                $warehouses = $this->_em->getRepository("Warehouses")->find($ccm_warehouses->getWarehouse()->getPkId());
                $warehouse_types = $this->_em->getRepository("WarehouseTypes")->find($form_values['health_facility_type']);
                $warehouses->setWarehouseType($warehouse_types);
                
                $warehouses->setModifiedBy($created_by);
                $warehouses->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($warehouses);
                $this->_em->flush();
            }
        }
        $this->_redirect("/cadmin/manage-health-facility");
    }

    /**
     * This method deletes Health Facility
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $user_id = $this->_request->getParam("user_id");
        $userswh = $this->_em->getRepository("WarehouseUsers")->findBy(array("user" => $user_id));
        foreach ($userswh as $wh) {
            $this->_em->remove($wh);
        }

        $user = $this->_em->getRepository("Users")->find($user_id);
        $this->_em->remove($user);

        return $this->_em->flush();
    }

    /**
     * This method check User
     */
    public function checkUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $username = $this->_request->getParam('loginId');

        $usersList = $this->_em->getRepository("Users")->findBy(array("loginId" => $username));

        if (count($usersList) > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

    /**
     * This method Gets Health Facility Type
     */
    public function ajaxGetHealthFacilityTypeAction() {
        $this->_helper->layout->disableLayout();
        $warehouses = new Model_Warehouses();

        $result = $warehouses->getHealthFacilityTypes();
        $this->view->result = $result;
    }
    
    /**
     * This method Gets Services Type
     */
    public function ajaxGetServicesTypeAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->wh_id;

        $warehouses = new Model_Warehouses();
        $warehouses->form_values = $form_values;
        $result = $warehouses->getServicesTypes();
        $this->view->result = $result;
    }

}
