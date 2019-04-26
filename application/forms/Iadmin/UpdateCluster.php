<?php

/**
 * Form_Iadmin_UpdateCluster
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */
/**
 *  Form for Iadmin Update Cluster
 */
class Form_Iadmin_UpdateCluster extends Form_Base {

/**
 * 
 * $_fields
 * 
 * Form Fields
 * for Form_Iadmin_UpdateCluster
 * 
 * 
 * @province: Product
 * @district: District
 * @user: User
 * @warehouses: Warehouses
 * @users: Users
 * @starting_on: starting_on
 * @working_uptil: working_uptil
 * @from_edit: from_edit
 * @status: Status
 * 
 * @var type 
 */
private $_fields = array(
"province" => "Product",
 "district" => "District",
 "user" => "User",
 "warehouses" => "Warehouses",
 "users" => "Users",
 "starting_on" => "starting_on",
 "working_uptil" => "working_uptil",
 "from_edit" => "from_edit",
 "status" => "Status"
);

/**
 * Combo boxes
 * for Form_Iadmin_UpdateCluster
 * 
 * 
 * province
 * district
 * user
 * warehouses
 * users
 * 
 * 
 * $_list
 * @var type 
 */
private $_list = array(
'province' => array(),
 'district' => array(),
 'user' => array(),
 'warehouses' => array(),
 'users' => array()
);

/**
 * Hidden Fields 
 * for Form_Iadmin_UpdateCluster
 * 
 * 
 * $_hidden
 * @var type 
 */
private $_hidden = array(
"province_hidden" => "pkId",
 "district_hidden" => "pkId",
 "user_hidden" => "pkId",
);

/**
 * Radio 
 * for Form_Iadmin_UpdateCluster
 * 
 * status
 * 
 * 
 * $_radio
 * @var type 
 */
private $_radio = array(
'status' => array(
"0" => "Active",
 "1" => "In Active"
)
);

/**
 * Initializes Form Fields
 */
public function init() {
    $auth = App_Auth::getInstance();
    $locations = new Model_Locations();
    $result2 = $locations->getAllProvinces();

    foreach ($result2 as $item) {

        $this->_list["province"][''] = 'Select';
        $this->_list["province"][$item['pkId']] = $item['locationName'];
    }
    if ($auth->getRoleId() == 38) {
        $locations = new Model_Locations();
        $locations->form_values['province_id'] = $auth->getProvinceId();
        $result2 = $locations->getDistrictsByProvince();

        foreach ($result2 as $item) {

            $this->_list["district"][''] = 'Select';
            $this->_list["district"][$item['pkId']] = $item['locationName'];
        }
    } else {
        $this->_list["district"][''] = 'Select';
    }
     if ($auth->getRoleId() == 39) {
           
        $users = new Model_Users();
            $users->form_values['district_id'] = $auth->getDistrictId();
            $array = $users->getAllUsersForCluster();

        foreach ($array as $item) {

            $this->_list["user"][''] = 'Select';
            $this->_list["user"][$item['pkId']] = $item['loginId'];
        }
    } else {
         $this->_list["user"][''] = 'Select';
    }
    
  


    foreach ($this->_fields as $col => $name) {
        if (in_array($col, array_keys($this->_list))) {
            parent::createSelect($col, $this->_list[$col]);
        }
        switch ($col) {

            case "starting_on":
            case "working_uptil":
            case "from_edit":
                parent::createReadOnlyText($col);
                break;
            default:
                break;
        }

        if (in_array($col, array_keys($this->_radio))) {
            parent::createRadio($col, $this->_radio[$col]);
        }
    }
    foreach ($this->_hidden as $col => $name) {
        switch ($col) {


            case "user_hidden":
            case "district_hidden":
            case "province_hidden":
                parent::createHidden($col);
                break;
            default:
                break;
        }
    }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
