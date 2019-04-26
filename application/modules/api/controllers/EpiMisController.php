<?php

/**
 * Api_FpController
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Api
 * @author     ahmad saib
 * @version    2.5.1
 */

/**
 *  Controller for Api Fp Controller
 */
class Api_EpiMisController extends App_Controller_Base {

    /**
     * Api_FpController init
     */
    public function init() {
        parent::init();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

//        $auth = $this->_request->getParam('token', '');
//
//        if ((empty($auth) || !$this->authenticateUser($auth))) {
//            $return = array(array("error" => 'Please provide authentication'));
//            echo Zend_Json::encode($return);
//            exit;
//        }
    }

    private function authenticateUser($auth) {
        $shared_key = 'userepimis';
        $date_current = date('Y-m-d');
        $key = md5($shared_key . $date_current);
        if ($key == $auth) {
            return 1;
        }
    }

    public function issuanceAction() {

        $year = $this->_request->getParam('year');
        $month = $this->_request->getParam('month');
        $tr_id = $this->_request->getParam('tr_id');
        if (!empty($year) && !empty($month)) {
            $epi_mis = new Model_EpiMis();
            $result = $epi_mis->getIssuanceData($year, $month, $tr_id);
        } else {
            $result = array("error" => "Please provide valid arguments");
        }
        echo Zend_Json::encode($result);
    }

    public function addConsumptionAction() {
//  Initiate curl

        $url = 'http://monitoring.punjab.gov.pk/evaccs/api/get_consumption_data?timestamp=1506288963';

        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);


        $decoded = json_decode($result, true);

        $evac = new Model_Evac();
        $res = $evac->addConsumption($decoded);
    }

}
