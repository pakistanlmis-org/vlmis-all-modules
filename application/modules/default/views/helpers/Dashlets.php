<?php

/**
 * Zend_View_Helper_Dashlets
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Dashlets
 */
class Zend_View_Helper_Dashlets extends Zend_View_Helper_Abstract {

    /**
     * dashlets
     * @param type $dashboard_id
     * @param type $role_id
     * @return string
     */
    public function dashlets($dashboard_id = null, $role_id = null , $province_id) {

        $html = '';
        $base_url = Zend_Registry::get("baseurl");

        // Check role id.
        if (empty($role_id)) {
            $auth = App_Auth::getInstance();
            $role_id = $auth->getRoleId();
        }

        $role_resource = new Model_RoleResources();
        $role_resource->form_values = array('type_id' => 4, 'role_id' => $role_id, 'parent_id' => $dashboard_id , 'province_id' => $province_id);
        $dashlets = $role_resource->getRoleResourcesByTypeByParent();

        // Loop through all elements and set css classes.
        foreach ($dashlets as $row) {
            if ($row->getResource()->getLevel() == 2) {
                $class = "col-md-12";
            } else {
                $class = "col-md-6";
            }
            $html .= '<div class="' . $class . '">
                    <div data-toggle="collapse-widget" class="widget">
                        <div class="widget-head">
                            <h4 class="heading glyphicons cargo"><i></i>' . $row->getResource()->getDescription() . '</h4>
                        </div>
                        <div class="widget-body dashlets" id="' . $row->getResource()->getPkId() . '" href="' . $row->getResource()->getResourceName() . '">
                            <center><img src="' . $base_url . '/images/ajax-loader.gif"/></center>
                        </div>
                    </div>
                </div>';
        }

        return $html;
    }

}

?>