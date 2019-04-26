<?php

/**
 * Iadmin_IndexController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Controller for Iadmin Index
 */
class Iadmin_IndexController extends App_Controller_Base {

    /**
     * Iadmin_IndexController index
     */
    public function indexAction() {
      
    }

    public function awStatsAction() {
        
    }

    public function userStatsAction() {
        $user_click_paths = new Model_UserClickPaths();
        $role_partners = $user_click_paths->getRoles();
        $role_national = $user_click_paths->getNationalRoles();
        $role_provincial = $user_click_paths->getProvincialRoles();
        $role_district = $user_click_paths->getDistrictRoles();
        $result = $user_click_paths->getPagesCountForRole();
        $this->view->role_partners = $role_partners;

        $this->view->role_national = $role_national;
        $this->view->role_provincial = $role_provincial;
        $this->view->role_district = $role_district;
        $this->view->data = $result;
    }

    public function uptimeAction() {
        
    }
    
    public function provincialAction() {
      
    }
    
    public function districtAction() {
      
    }
    
    public function sdpAction() {
      
    }

}
