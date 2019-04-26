<?php

/**
 * Iadmin_ManageGeoLevelsController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Muhammad Imran <muhammad_imran@pk.jsi.com>
 * @version    2.5.1
 */

/**
 * This controller manages Geo Levels
 */
class Iadmin_ManageGeoLevelsController extends App_Controller_Base {

    /**
     * This method searches Geo Levels
     */
    public function indexAction() {
        $form = new Form_Iadmin_GeoLevels();

        $geo_levels = new Model_GeoLevels();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $geo_level_name = $form->getValue('geo_level_name');

                if (!empty($geo_level_name)) {
                    $geo_levels->form_values['geo_level_name'] = $geo_level_name;
                }
            }
            $form->geo_level_name->setValue($geo_level_name);
        }

        $result = $geo_levels->getAllGeoLevels();

        $this->view->form = $form;
        $this->view->result = $result;
    }

    /**
     * This method adds Geo Levels
     */
    public function addGeoLevelAction() {
        $form = new Form_Iadmin_GeoLevels();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $geo_levels = new Model_GeoLevels();
            $geo_level_name = $form->getValue('geo_level_name');
            $geo_level_description = $form->getValue('geo_level_description');
            $status = $form->getValue('status');
            if (!empty($status)) {
                $geo_levels->form_values['status'] = $status;
            }
            if (!empty($geo_level_name)) {
                $geo_levels->form_values['geo_level_name'] = $geo_level_name;
                $geo_levels->form_values['geo_level_description'] = $geo_level_description;
                $geo_levels->addGeoLevel();
            }
        }
        $this->_redirect("/iadmin/manage-geo-levels/index");
    }

    /**
     * This method updates Geo Levels
     */
    public function updateGeoLevelAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form = new Form_Iadmin_GeoLevels();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $geo_levels = new Model_GeoLevels();
            $geo_level_name = $form->getValue('geo_level_name');
            $geo_level_id = $form->getValue('geo_level_id');
            $geo_level_description = $form->getValue('geo_level_description');
            $status = $form->getValue('status');
            $geo_levels->form_values['geo_level_name'] = $geo_level_name;
            $geo_levels->form_values['geo_level_id'] = $geo_level_id;
            $geo_levels->form_values['geo_level_description'] = $geo_level_description;
            $geo_levels->form_values['status'] = $status;
            $geo_levels->updateGeoLevel();
        }
        $this->_redirect("/iadmin/manage-geo-levels/index");
    }

    /**
     * This method deletes Geo Levels
     */
    public function deleteGeoLevelAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $country_id = $this->_request->getParam("country_id");
        $countries = new Model_Locations();
        $countries->form_values['country_id'] = $country_id;
        $countries->deleteCountry();

        $this->_redirect("/iadmin/manage-geo-levels/index");
    }

    /**
     * This method checks whether a Geo Level is allready exists
     */
    public function checkGeoLevelAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();

        $geo_levels = new Model_GeoLevels();

        if ($form_values['geo_level_name'] !== $form_values['geo_level_name_hidden']) {
            $geo_levels->form_values['geo_level_name'] = $form_values['geo_level_name'];
            $result = $geo_levels->checkGeoLevel();
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
     * This method retrieves Geo Level information for edit
     */
    public function ajaxGeoLevelEditAction() {
        $this->_helper->layout->disableLayout();
        $form = new Form_Iadmin_GeoLevels();
        $geo_level_id = $this->_request->getParam('geo_level_id');
        $geo_levels = new Model_GeoLevels();
        $geo_levels->form_values['geo_level_id'] = $geo_level_id;
        $result = $geo_levels->getGeoLevelById();
        $geo_level_name = $result[0]['geo_level_name'];
        $geo_level_description = $result[0]['description'];
        $geo_level_status = $result[0]['status'];
        $form->geo_level_name->setValue($geo_level_name);
        $form->geo_level_name_hidden->setValue($geo_level_name);
        $form->geo_level_description->setValue($geo_level_description);
        $form->status->setValue($geo_level_status);
        $form->geo_level_description_hidden->setValue($geo_level_description);
        $form->geo_level_id->setValue($geo_level_id);
        $this->view->form = $form;
    }

    /**
     * This method changes Geo Level status from active to inactive and vice versa
     */
    public function ajaxChangeStatusAction() {
        $this->_helper->layout->disableLayout();
        $row = $this->_em->getRepository("GeoLevels")->find($this->_request->getParam('id'));
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

}
