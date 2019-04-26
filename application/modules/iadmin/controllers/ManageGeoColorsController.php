<?php

/**
 * Iadmin_ManageGeoColorsController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Rafeh Jamil <rafeh.deliver@gmail.com>
 * @version    2.5.1
 */

/**
 * This Controller Manages Geo Colors
 */
class Iadmin_ManageGeoColorsController extends App_Controller_Base {

    /**
     * This method Seraches Geo Colors
     */
    public function geoColorsAction() {

        $form = new Form_Iadmin_GeoColors();

        $color_codes = new Model_GeoColors();
        $params = array();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $color_code_name = $form->getValue('color_code_name');

                if (!empty($color_code_name)) {
                    $color_codes->form_values['color_code_name'] = $color_code_name;
                }
            }

            $form->color_code_name->setValue($color_code_name);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "stakeholder");

        $result = $color_codes->getGeoColors($order, $sort);

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
    }

    /**
     * This method adds Geo Colors
     */
    public function addGeoColorsAction() {
        $form = new Form_Iadmin_GeoColors();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $geo_colors = new GeoColor();
            $geo_colors->setColorCode($form->color_code_name->getValue());
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $geo_colors->setCreatedBy($createdBy);
            $geo_colors->setModifiedBy($createdBy);
            $geo_colors->setCreatedDate(App_Tools_Time::now());
            $geo_colors->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($geo_colors);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-geo-colors/geo-colors");
    }

    /**
     * This method checks whetehr Geo Colors is allready exists
     */
    public function checkGeoColorsAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->color_code_name;

        $GeoColors = new Model_GeoColors();
        $GeoColors->form_values = $form_values;
        $result = $GeoColors->checkGeoColors();
        $this->view->result = $result;
    }

    /**
     * This method retrieves Geo Color for edit
     */
    public function ajaxGeoColorEditAction() {
        $this->_helper->layout->disableLayout();
        $geoColor = $this->_em->find('GeoColor', $this->_request->getParam('color_code_id'));

        $form = new Form_Iadmin_GeoColors();
        $form->color_code_name->setValue($geoColor->getColorCode());

        $form->pk_id->setValue($geoColor->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method updates Geo Color
     */
    public function updateGeoColorsAction() {
        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $geoColor = $this->_em->getRepository("GeoColor")->find($form_values['pk_id']);

            $geoColor->setColorCode($form_values['color_code_name']);
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $geoColor->setModifiedBy($createdBy);
            $geoColor->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($geoColor);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-geo-colors/geo-colors");
    }

}
