<?php

/**
 * SurveillanceController
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Surveillance
 */
class SurveillanceController extends App_Controller_Base {

    public function indexAction() {
         $this->_helper->layout->setLayout('surveillance');
    }

    /**
     * VPD
     */
    public function vpdAction() {
        $this->_helper->layout->setLayout('surveillance');

        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();

        try {
            if ($this->_request->isPost()) {

                $data = $this->_request->getPost();
                //    App_Controller_Functions::pr($data);
                $survillance = new Model_Surveillance();
                $survillance->form_values = $data;

                $vpd_pk_id = $survillance->addVpd();
                $em->getConnection()->commit();
                $arr_data['success'] = 1;
                $this->view->type_o_case = $data['t_o_case'];
                $survillance->form_values['t_o_case1'] = $data['t_o_case'];
                $this->view->master_id = $vpd_pk_id;
                $this->view->arr_data = $arr_data;
            }
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            App_FileLogger::info($e);
        }

        if (!empty($this->_request->getParam('id'))) {
            $survillance = new Model_Surveillance();
            $survillance->form_values['id'] = $this->_request->getParam('id');
            $result = $survillance->getVpdList();
            $this->view->result = $result;
            $result_clic = $survillance->getVpdCliniical();
            $this->view->clinical_presentation = $result_clic;
        } else {
            $survillance = new Model_Surveillance();
            $result = $survillance->getVpdDraft();
            $this->view->result = $result;
            $this->view->is_draft = $result;
        }
        $locations = new Model_Locations();

        // user ucs
        $locations->form_values['district_id'] = $this->_identity->getDistrictId();
        $this->view->distrcit_ucs = $locations->getUcsByDistrict();
        $this->view->distrcit_ssites = $locations->getSurSitesByDistrict();
        $this->view->distrcit_name = $locations->getDistrictName();
        // province 
        $this->view->province = $locations->getProvinces();


        // districts

        $locations->form_values['province_id'] = $this->_identity->getProvinceId();
        $this->view->district = $locations->getDistrictsByProvince();

        if ($this->_identity->getRoleId() == 61) {
//            print_r($locations->form_values['district_id']);exit;
            $locations->form_values['province_id'] = 2;
            $locations->form_values['district_id'] = $this->_identity->getDistrictId();
            $this->view->district = $locations->getDistrictsByProvince();
            $this->view->district_admin = $locations->getDistrictsByProvince();
            $this->view->distrcit_ssites = $locations->getSurSitesByDistrictSurvAdmin();
            $this->view->distrcit_ucs = $locations->getUcsByDistrictSurvAdmin();
        }
        $this->view->role_id = $this->_identity->getRoleId();
        // type case
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::TYPE_CASE);
        $result_case = $list_master->getListDetailByType();
        $this->view->type_case = $result_case;
        // outcome
        $list_master->form_values = array('pk_id' => Model_ListMaster::OUTCOME);
        $result_outcome = $list_master->getListDetailByType();
        $this->view->outcome = $result_outcome;

        $list_master->form_values = array('pk_id' => Model_ListMaster::PLACE_OF_BIRTH);
        $result_birth = $list_master->getListDetailByType();
        $this->view->place_of_birth = $result_birth;

        $list_master->form_values = array('pk_id' => Model_ListMaster::BIRTH_ATTENDEE);
        $result_birth_attendee = $list_master->getListDetailByType();
        $this->view->birth_attendee = $result_birth_attendee;

        // lab result
        $list_master->form_values = array('pk_id' => Model_ListMaster::LABRESULT);
        $result_lab_result = $list_master->getListDetailByType();
        $this->view->lab_result = $result_lab_result;
        // final classification
        $list_master->form_values = array('pk_id' => Model_ListMaster::FINALCLASS);
        $result_final_class = $list_master->getListDetailByType();
        $this->view->final_class = $result_final_class;
        $list_detail1 = new Model_ListDetail();
        $list_detail1->form_values['master_id'] = 46;
        $result = $list_detail1->getListDetailByMasterId();
        $this->view->result_sign = $result;

        $base_url = Zend_Registry::get("baseurl");

        $this->view->inlineScript()->appendFile($base_url . '/common/assets/plugins/jquery.pulsate.min.js');
        if ($this->_request->isPost()) {

            $data = $this->_request->getPost();
            //    App_Controller_Functions::pr($data);
            $survillance = new Model_Surveillance();
            $survillance->form_values = $data;

            $this->view->type_o_case = $data['t_o_case'];
            $survillance->form_values['t_o_case1'] = $data['t_o_case'];
            $result1 = $survillance->getVpd();
            $this->view->search_result = $result1;
        } else {
            $result1 = $survillance->getVpd();
            $this->view->search_result = $result1;
        }
    }

    /**
     * AEFI
     */
    public function aefiAction() {

       $this->_helper->layout->setLayout('surveillance');
        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();

        try {
            if ($this->_request->isPost()) {
                $data = $this->_request->getPost();
                // App_Controller_Functions::pr($data);

                $survillance = new Model_Surveillance();
                $survillance->form_values = $data;

                $aefi_pk_id = $survillance->addAefi();
                $em->getConnection()->commit();
                $arr_data['success'] = 1;
                $this->view->master_id = $aefi_pk_id;
                $this->view->arr_data = $arr_data;
            }
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            App_FileLogger::info($e);
        }
        if (!empty($this->_request->getParam('id'))) {
            $survillance = new Model_Surveillance();
            $survillance->form_values['id'] = $this->_request->getParam('id');
            $result = $survillance->getAefiList();
            $this->view->result = $result;
        } else {
            $survillance = new Model_Surveillance();
            $result = $survillance->getAefiDraft();
            $this->view->result = $result;
            $this->view->is_draft = $result;
        }

        $locations = new Model_Locations();
        // districts
        $locations->form_values['province_id'] = $this->_identity->getProvinceId();
        $this->view->district = $locations->getDistrictsByProvince();
        $this->view->role_id = $this->_identity->getRoleId();
        // user ucs
        $locations->form_values['district_id'] = $this->_identity->getDistrictId();
        $this->view->distrcit_ucs = $locations->getWarehousesByDistrict();
        $this->view->distrcit_ssites = $locations->getSurSitesByDistrict();
        if ($this->_identity->getRoleId() == 61) {
//            print_r($locations->form_values['district_id']);exit;
            $locations->form_values['province_id'] = 2;
            $locations->form_values['district_id'] = $this->_identity->getDistrictId();
            $this->view->district = $locations->getDistrictsByProvince();
            $this->view->district_admin = $locations->getDistrictsByProvince();
            $this->view->distrcit_ssites = $locations->getSurSitesByDistrictSurvAdmin();
            $this->view->distrcit_ucs = $locations->getWarehousesByDistrictSurvAdmin();
        }


        $list_master = new Model_ListMaster();
        // province 
        $this->view->province = $locations->getProvinces();

        // week
        $list_master->form_values = array('pk_id' => Model_ListMaster::WEEK);
        $result_week = $list_master->getListDetailByType();
        $this->view->result_week = $result_week;

        // week
        $list_master->form_values = array('pk_id' => Model_ListMaster::AEFI);
        $result_aefi = $list_master->getListDetailByType();
        $this->view->result_aefi = $result_aefi;


        //Generate Products(items) Combo
        $item_pack_sizes = new Model_ItemPackSizes();
        $result2 = $item_pack_sizes->getAllAefiItems();
        $this->view->product = $result2;

        $base_url = Zend_Registry::get("baseurl");

        $this->view->inlineScript()->appendFile($base_url . '/common/assets/plugins/jquery.pulsate.min.js');
        $survillance->form_values['type_of_case1'] = 1;
        $result = $survillance->getAefi();
        $this->view->result_search = $result;
//        foreach ($result2 as $item) {
//
//            $this->_list["product"][''] = 'Select';
//            $this->_list["product"][$item['pkId']] = $item['itemName'];
//        }
    }

    /**
     * Search VPD
     */
    public function searchVpdAction() {

        $locations = new Model_Locations();
        // districts
        $locations->form_values['province_id'] = $this->_identity->getProvinceId();
        $this->view->district = $locations->getDistrictsByProvince();
        // type case
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::TYPE_CASE);
        $result_case = $list_master->getListDetailByType();

        $this->view->type_case = $result_case;
        $this->view->paginator = "";
    }

    public function ajaxGetVpdAction() {
        $this->_helper->layout->disableLayout();

        $survillance = new Model_Surveillance();

         if ($this->_request->isPost()) {
            if ($this->_request->getPost()) {
                $form_values = $this->_request->getPost();
                $survillance->form_values = $form_values;
                $result = $survillance->getVpd();
                $this->view->result = $result;
            }
        }
    }

    /**
     * Search VPD
     */
    public function searchAefiAction() {

        $locations = new Model_Locations();
        // districts
        $locations->form_values['province_id'] = $this->_identity->getProvinceId();
        $this->view->district = $locations->getDistrictsByProvince();

        $this->view->paginator = "";
    }

    public function ajaxGetAefiAction() {
        $this->_helper->layout->disableLayout();

        $survillance = new Model_Surveillance();


        if ($this->_request->isPost()) {
            if ($this->_request->getPost()) {
                $form_values = $this->_request->getPost();
                $survillance->form_values = $form_values;
                $result = $survillance->getAefi();
                $this->view->result = $result;
            }
        }
    }

    public function ajaxGetDistrictCodeAction() {
        $this->_helper->layout->disableLayout();

        $survillance = new Model_Surveillance();


        if ($this->_request->isPost()) {
            if ($this->_request->getPost()) {
                $form_values = $this->_request->getPost();


                $survillance->form_values = $form_values;
                $result = $survillance->getDistrictCode();


                $this->view->result = $result;
            }
        }
    }

    /**
     * Add New Make Model
     */
    public function addNewSignSymptomsAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getParams();
        $list_detail = new ListDetail();
        $list_detail->setListValue($form_values['sign']);
        $list_detail->setDescription($form_values['sign']);
        $list_master_id = $this->_em->getRepository('ListMaster')->find('46');
        $list_detail->setListMaster($list_master_id);

        $user = $this->_em->find('Users', $this->_userid);
        $list_detail->setCreatedBy($user);
        $list_detail->setCreatedDate(App_Tools_Time::now());
        $list_detail->setModifiedBy($user);
        $list_detail->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($list_detail);
        $this->_em->flush();


        $list_detail1 = new Model_ListDetail();

        $list_detail1->form_values['master_id'] = 46;
        $result = $list_detail1->getListDetailByMasterId();
        $this->view->result = $result;
    }

    /**
     * ajaxGetPlacementHistory
     */
    public function ajaxGetVpdDetailAction() {
        $this->_helper->layout->disableLayout();

        $form_values['pk_id'] = $this->_request->id;

        $survillance = new Model_Surveillance();
        $survillance->form_values = $form_values;
        $result = $survillance->getVpdDetail();
        $this->view->data = $result;
    }

    /**
     * ajaxGetPlacementHistory
     */
    public function ajaxGetAefiDetailAction() {
        $this->_helper->layout->disableLayout();

        $form_values['pk_id'] = $this->_request->id;

        $survillance = new Model_Surveillance();
        $survillance->form_values = $form_values;
        $result = $survillance->getAefiDetail();
        $this->view->data = $result;
    }

    public function ajaxGetDistricts() {
        $locations = new Model_Locations();
        $locations->form_values['province_id'] = $this->_identity->getProvinceId();
        $this->view->district = $locations->getDistrictsByProvince();
    }

    /**
     *
     */
    public function printVpdAction() {
        $this->_helper->layout->setLayout('print-surveillance');
        $this->view->headTitle("VPD Detail");
        $form_values['pk_id'] = $this->_request->id;

        $survillance = new Model_Surveillance();
        $survillance->form_values = $form_values;
        $result = $survillance->getVpdDetail();
        $this->view->data = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "VPD Detail";
        $this->view->department = $this->_identity->getUserDepartment();
    }

    public function printAefiAction() {
        $this->_helper->layout->setLayout('print-surveillance');
        $this->view->headTitle("AEFI Detail");
        $form_values['pk_id'] = $this->_request->id;

        $survillance = new Model_Surveillance();
        $survillance->form_values = $form_values;
        $result = $survillance->getAefiDetail();

        $this->view->data = $result;
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "AEFI Detail";
        $this->view->department = $this->_identity->getUserDepartment();
    }

    public function pdfVpdAction() {
        $this->_helper->layout->setLayout('print-surveillance');
        $vpd_master_id = $this->_request->id;
        $this->view->vpd_master_id = $vpd_master_id;
    }

    public function ajaxGetCaseNoAction() {
        $this->_helper->layout->disableLayout();
        $surveillance = new Model_Surveillance();
        $case_no = $surveillance->getLastId();
        $this->view->case_no = $case_no;
    }

    public function printEmptyVpdAction() {
        $this->_helper->layout->setLayout('print-surveillance');
        $this->view->headTitle("VPD Detail");
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "VPD Detail";
        $this->view->department = $this->_identity->getUserDepartment();
    }

    public function printEmptyAefiAction() {
        $this->_helper->layout->setLayout('print-surveillance');
        $this->view->headTitle("Aefi Detail");
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Aefi Detail";
        $this->view->department = $this->_identity->getUserDepartment();
    }

    public function saveDraftVpdAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            // print_r('form values are'+$form_values);exit;
            $str_sql1 = "DELETE FROM vpd_form_draft where created_by=" . $this->_userid;

            $rec = $this->_em->getConnection()->prepare($str_sql1);
            $rec->execute();

            $vpd_draft = new VpdFormDraft();
            $vpd_draft->setCreatedBy($this->_userid);
            if (!empty($form_values['week'])) {
                $vpd_draft->setWeek($form_values['week']);
            }

            if (!empty($form_values['district'])) {
                $vpd_draft->setDistrictId($form_values['district']);
            }
            if (!empty($form_values['date'])) {
                $vpd_draft->setDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date'])));
            }
            if (!empty($form_values['tehsil'])) {
                $vpd_draft->setTehsilId($form_values['tehsil']);
            }
            if (!empty($form_values['ucs'])) {
                $vpd_draft->setUcId($form_values['ucs']);
            }
            if (!empty($form_values['case_health_facility'])) {
                $vpd_draft->setHfId($form_values['case_health_facility']);
            }
            if (!empty($form_values['other_case_health_facility'])) {
                $vpd_draft->setHfName($form_values['other_case_health_facility']);
            }
            // from uc id
            if (!empty($form_values['district_ucs'])) {
                $vpd_draft->setFromUcId($form_values['district_ucs']);
            }
            // end
            if (!empty($form_values['t_o_case'])) {
                $vpd_draft->setTypeCase($form_values['t_o_case']);
            } if (!empty($form_values['epid_number'])) {
                $vpd_draft->setEpidNumber($form_values['epid_number']);
            }
            if (!empty($form_values['child_name'])) {
                $vpd_draft->setChildName($form_values['child_name']);
            }
            if (!empty($form_values['father_name'])) {
                $vpd_draft->setFatherName($form_values['father_name']);
            }
            if (!empty($form_values['address1'])) {
                $vpd_draft->setAddress1($form_values['address1']);
            }
            if (!empty($form_values['address_town'])) {
                $vpd_draft->setAddressVillage($form_values['address_town']);
            }
            if (!empty($form_values['contact_number'])) {
                $vpd_draft->setContactNumber($form_values['contact_number']);
            }
            if (!empty($form_values['cross_notification'])) {
                $vpd_draft->setCrossNotification($form_values['cross_notification']);
            }
            if (!empty($form_values['birth_date'])) {
                $vpd_draft->setDob(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['birth_date'])));
            }
            if (!empty($form_values['datepicker_patient_visited'])) {
                $vpd_draft->setPatientVisitedDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_patient_visited'])));
            }

            if (!empty($form_values['age_month'])) {
                $vpd_draft->setAge($form_values['age_month']);
            }

            $vpd_draft->setCrossNotification($form_values['cross_notification']);

            if (!empty($form_values['cash_reported_from_h_f'])) {
                $vpd_draft->setHfId($form_values['cash_reported_from_h_f']);
            }
            if (!empty($form_values['StoolSampleDate1'])) {
                $vpd_draft->setStoolSampleDate1(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['StoolSampleDate1'])));
            }
            if (!empty($form_values['StoolSampleDate2'])) {
                $vpd_draft->setStoolSampleDate2(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['StoolSampleDate2'])));
            }
            if (!empty($form_values['age_unit'])) {
                $vpd_draft->setAgeUnit($form_values['age_unit']);
            }
            if (!empty($form_values['Vaccine_Doses_Recived'])) {
                $vpd_draft->setSpecificDoseReceived($form_values['Vaccine_Doses_Recived']);
            }
            if (!empty($form_values['gender'])) {
                $vpd_draft->setGender($form_values['gender']);
            }
            if (!empty($form_values['is_child_protected'])) {
                $vpd_draft->setIsTheChildProtected($form_values['is_child_protected']);
            }
            if (!empty($form_values['has_child_received'])) {
                $vpd_draft->setHasChildReceivedOpv($form_values['has_child_received']);
            }
            if (!empty($form_values['l_v_d_received_other'])) {
                $vpd_draft->setNumberOfMonths($form_values['l_v_d_received_other']);
            }
            if (!empty($form_values['date_of_specimen_collection'])) {
                $vpd_draft->setSpecimenCollectionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_specimen_collection'])));
            }
            if (!empty($form_values['date_of_lab_resulted'])) {
                $vpd_draft->setDateOfLabResulted(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_lab_resulted'])));
            }
            if (!empty($form_values['datepicker_Notification'])) {
                $vpd_draft->setDateNotification(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_Notification'])));
            }
            if (!empty($form_values['datepicker_Investigation'])) {
                $vpd_draft->setDateInvestigation(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_Investigation'])));
            }
            if (!empty($form_values['l_v_d_received'])) {
                $vpd_draft->setDateLastDoseReceived(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['l_v_d_received'])));
            }
            $vpd_draft->setSpecimenCollection($form_values['Specimen_Collection']);
            if (!empty($form_values['date_of_specimen_sent'])) {
                $vpd_draft->setDateSpecimenSent(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_specimen_sent'])));
            }
            if (!empty($form_values['date_of_specimen_sent1'])) {
                $vpd_draft->setDateSpecimenSent1(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_specimen_sent1'])));
            }
            if (!empty($form_values['outcome'])) {
                $vpd_draft->setOutcome($form_values['outcome']);
            }
            if (!empty($form_values['lab_result_test'])) {
                $vpd_draft->setLabResult($form_values['lab_result_test']);
            }
            if (!empty($form_values['final_classification'])) {
                $vpd_draft->setFinalClassification($form_values['final_classification']);
            }
            if (!empty($form_values['Blood_Sample_Date'])) {
                $vpd_draft->setBloodSampleDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Blood_Sample_Date'])));
            }
            if (!empty($form_values['Throad_Swab_Date'])) {
                $vpd_draft->setBloodSampleMonth(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Throad_Swab_Date'])));
            }
            if (!empty($form_values['Naso_Pharyngeal_Swab_Date'])) {
                $vpd_draft->setBloodSampleDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Naso_Pharyngeal_Swab_Date'])));
            }
            if (!empty($form_values['Sputum_Collection_Date_1'])) {
                $vpd_draft->setBloodSampleDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Sputum_Collection_Date_1'])));
            }
            if (!empty($form_values['Sputum_Collection_Date_2'])) {
                $vpd_draft->setBloodSampleMonth(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Sputum_Collection_Date_2'])));
            }
            if (!empty($form_values['datepicker_Onset'])) {
                $vpd_draft->setDateOnset(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_Onset'])));
            }

            $vpd_draft->setCreatedDate(App_Tools_Time::now());
            $vpd_draft->setModifiedBy($this->_userid);
            $vpd_draft->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($vpd_draft);
            $this->_em->flush();
        }
    }

    public function saveDraftAefiAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);


        if ($this->_request->isPost() && $this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            // print_r('form values are'+$form_values);exit;
            $str_sql1 = "DELETE FROM aefi_form_draft where created_by=" . $this->_userid;

            $rec = $this->_em->getConnection()->prepare($str_sql1);
            $rec->execute();

            // App_Controller_Functions::pr($form_values);
            if (!empty($form_values['aefi_id'])) {

                $aefi_draft = $this->_em->getRepository("AefiFormDraft")->find($form_values['aefi_id']);
                // code to write
            }

            $aefi_draft = new AefiFormDraft();

            if (!empty($form_values['date'])) {
                $aefi_draft->setDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date'])));
            }
            if (!empty($form_values['other_case_health_facility'])) {
                $aefi_draft->setHfName($form_values['other_case_health_facility']);
            }
            $aefi_draft->setCreatedBy($this->_userid);
            $aefi_draft->setWeek($form_values['week']);
            if (!empty($form_values['type_of_case'])) {
                $aefi_draft->setTypeCase($form_values['type_of_case']);
            }
            if (!empty($form_values['date_notification'])) {
                $aefi_draft->setDateNotification(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_notification'])));
            }

            if (!empty($form_values['district'])) {
                $aefi_draft->setDistrictId($form_values['district']);
            }
            if (!empty($form_values['district_case'])) {
                $aefi_draft->setDistrictId1($form_values['district_case']);
            }
            if (!empty($form_values['tehsil'])) {
                $aefi_draft->setTehsilId($form_values['tehsil']);
            }
            if (!empty($form_values['tehsil_case'])) {
                $aefi_draft->setTehsilId1($form_values['tehsil_case']);
            }
            if (!empty($form_values['uc'])) {
                $aefi_draft->setUcId($form_values['uc']);
            }
            if (!empty($form_values['uc_case'])) {
                $aefi_draft->setUcId1($form_values['uc_case']);
            }
            $aefi_draft->setDob(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['birth_date'])));
            if (!empty($form_values['age_month'])) {
                $aefi_draft->setAgeInMonths($form_values['age_month']);
            }
            $aefi_draft->setGender($form_values['gender']);
            $aefi_draft->setTypeCase($form_values['type_of_case']);
            if (!empty($form_values['cash_reported_from_h_f'])) {
                $aefi_draft->setHfId($form_values['cash_reported_from_h_f']);
            }
            if (!empty($form_values['aefi'])) {
                $aefi_draft->setAefi($form_values['aefi']);
            }
            $aefi_draft->setVaccineAntigen($form_values['vaccine_antigen']);
            if (!empty($form_values['batch_no'])) {
                $aefi_draft->setBatchId($form_values['batch_no']);
            }
            if (!empty($form_values['other_batch_no'])) {
                $aefi_draft->setBatchNumber($form_values['other_batch_no']);
            }

            if (!empty($form_values['case_vaccinated_epi'])) {
                $aefi_draft->setCaseEpi($form_values['case_vaccinated_epi']);
            }
            if (!empty($form_values['other_case_vaccinated_epi'])) {
                $aefi_draft->setOtherCaseEpi($form_values['other_case_vaccinated_epi']);
            }
            if (!empty($form_values['expiry_date'])) {
                $aefi_draft->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['expiry_date'])));
            }
            $aefi_draft->setDateVaccineGiven(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_vaccine_given'])));

            $aefi_draft->setDateAefiOnset(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_aefi_onset'])));
            $aefi_draft->setDateOfInvestigation(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_Investigation'])));
            $aefi_draft->setAefiOther($form_values['other_aefi']);

            $aefi_draft->setChildName($form_values['child_name']);
            $aefi_draft->setFatherName($form_values['father_name']);

            // address
            $aefi_draft->setAddress1($form_values['address1']);

            $aefi_draft->setAddressVillage($form_values['address_town']);
            // end
            $aefi_draft->setContactNumber($form_values['contact_number']);
            $aefi_draft->setHospitalization($form_values['hospitalization']);
            $aefi_draft->setDeath($form_values['death']);
            $aefi_draft->setAgeUnit($form_values['age_unit']);
            $aefi_draft->setDesignation($form_values['desig']);
            $aefi_draft->setVaccinatorName($form_values['vaccinator']);
            $aefi_draft->setCreatedDate(App_Tools_Time::now());
            $aefi_draft->setModifiedBy($this->_userid);
            $aefi_draft->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($aefi_draft);
            $this->_em->flush();
        }
    }

}
