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
 * This Controller manages the Geo Indicators
 */
class Iadmin_ManageGeoIndicatorsController extends App_Controller_Base {

    /**
     * This method searches the Geo Indicators
     */
    public function indexAction() {
        $form = new Form_Iadmin_GeoIndicators();

        $geo_indicators = new Model_GeoIndicators();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $geo_indicator_name = $form->getValue('geo_indicator_name');

                if (!empty($geo_indicator_name)) {
                    $geo_indicators->form_values['geo_indicator_name'] = $geo_indicator_name;
                }
            }
            $form->geo_indicator_name->setValue($geo_indicator_name);
        }

        $result = $geo_indicators->getAllGeoIndicators();

        $this->view->form = $form;
        $this->view->result = $result;
    }

    /**
     * This method adds the Geo Indicators
     */
    public function addGeoIndicatorAction() {
        $form = new Form_Iadmin_GeoIndicators();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $geo_indicators = new Model_GeoIndicators();
            $geo_indicator_name = $form->getValue('geo_indicator_name');

            if (!empty($geo_indicator_name)) {
                $geo_indicators->form_values['geo_indicator_name'] = $geo_indicator_name;
                $geo_indicators->addGeoIndicator();
            }
        }
        $this->_redirect("/iadmin/manage-geo-indicators/index");
    }

    /**
     * This method updates the Geo Indicators
     */
    public function updateGeoIndicatorAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form = new Form_Iadmin_GeoIndicators();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $geo_indicators = new Model_GeoIndicators();
            $geo_indicator_name = $form->getValue('geo_indicator_name');
            $geo_indicator_id = $form->getValue('geo_indicator_id');
            $geo_indicators->form_values['geo_indicator_name'] = $geo_indicator_name;
            $geo_indicators->form_values['geo_indicator_id'] = $geo_indicator_id;
            $geo_indicators->updateGeoIndicator();
        }
        $this->_redirect("/iadmin/manage-geo-indicators/index");
    }

    /**
     * This method checks whether a Geo Indicator allready exists
     */
    public function checkGeoIndicatorAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();


        $geo_indicators = new Model_GeoIndicators();

        if ($form_values['geo_indicator_name'] !== $form_values['geo_indicator_name_hidden']) {
            $geo_indicators->form_values['geo_indicator_name'] = $form_values['geo_indicator_name'];
            $result = $geo_indicators->checkGeoIndicator();
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
     * This method retrieves the Geo Indicator information for edit
     */
    public function ajaxGeoIndicatorEditAction() {

        $this->_helper->layout->disableLayout();
        $form = new Form_Iadmin_GeoIndicators();
        $geo_indicator_id = $this->_request->getParam('geo_indicator_id');
        $geo_indicators = new Model_GeoIndicators();
        $geo_indicators->form_values['geo_indicator_id'] = $geo_indicator_id;
        $result = $geo_indicators->getGeoIndicatorById();
        $geo_indicator_name = $result[0]['geo_indicator_name'];
        $form->geo_indicator_name->setValue($geo_indicator_name);
        $form->geo_indicator_name_hidden->setValue($geo_indicator_name);
        $form->geo_indicator_id->setValue($geo_indicator_id);
        $this->view->form = $form;
    }

}
