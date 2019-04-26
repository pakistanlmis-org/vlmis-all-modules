<?php

/**
 * Cadmin_IndexController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Controller for Cadmin Index Controller
*/

class Cadmin_IndexController extends App_Controller_Base {


    /**
     * Cadmin_IndexController index
     */
    public function indexAction() {
        $auth = App_Auth::getInstance();
        $role = $auth->getRoleId();

        if (in_array($role, array(4, 5))) {
            $this->_helper->viewRenderer('user-admin');
        }
    }

}
