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
class Api_EvacController extends App_Controller_Base {

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
        $shared_key = 'userevac';
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
            $evac = new Model_Evac();
            $result = $evac->getIssuanceData($year, $month, $tr_id);
        } else {
            $result = array("error" => "Please provide valid arguments");
        }
        echo Zend_Json::encode($result);
    }

//    public function complianceConsumptionReportsAction() {
//        $indicator = $this->_request->getParam('indicator');
//        $year = $this->_request->getParam('year');
//        $month = $this->_request->getParam('month');
//        if (!empty($indicator) && !empty($year) && !empty($month)) {
//            $fp_dashboard = new Model_FpDashboard();
//            $result = $fp_dashboard->getCompianceConsumptionReports($indicator, $year, $month);
//        } else {
//            $result = array("error" => "Please provide valid arguments");
//        }
//        echo Zend_Json::encode($result);
//    }
//
//    public function childrenReceivedMeasles1Action() {
//        $indicator = $this->_request->getParam('indicator');
//        $year = $this->_request->getParam('year');
//        $month = $this->_request->getParam('month');
//        if (!empty($indicator) && !empty($year) && !empty($month)) {
//            $fp_dashboard = new Model_FpDashboard();
//            $result = $fp_dashboard->getChildrenReceivedMeasles1($indicator, $year, $month);
//        } else {
//            $result = array("error" => "Please provide valid arguments");
//        }
//        echo Zend_Json::encode($result);
//    }
}
