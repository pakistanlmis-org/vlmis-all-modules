<?php

/**
 * Form_Iadmin_Users
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Users
 */
class Form_Iadmin_Users extends Form_Base {

    /**
     * Form Fields
     *
     * Private Variable
     *
     * @user_name_add: UserName
     * @user_name_update: UserName
     * @email: Email
     * @phone: Phone No
     * @email_update: Email
     * @phone_update: Phone No
     * @old_password: Old Password
     * @new_password: New Password
     * @user_type: User Type
     * @password: Password
     * @confirm_password: Confirm_Password
     * @search_policy_users; loginId
     * @policy_users_add: loginId
     * @role: role
     * @default_warehouse: default_warehouse
     * @default_warehouse_update: default_warehouse_update
     *
     * @var type
     *
     */
    private $_fields = array(
        "login_id_add" => "LoginID",
        "user_name_add" => "UserName",
        "user_name_update" => "UserName",
        "login_id_update" => "LoginID",
        "email" => "Email",
        "phone" => "Phone No",
        "email_update" => "Email",
        "phone_update" => "Phone No",
        "old_password" => "Old Password",
        "new_password" => "New Password",
        "user_type" => "User Type",
        "password" => "Password",
        "confirm_password" => "Confirm_Password",
        "search_policy_users" => "loginId",
        "policy_users_add" => "loginId",
        "role" => "role",
        "role_policy" => "role",
        "role_policy_update" => "role",
        "default_warehouse" => "default_warehouse",
        "default_warehouse_update" => "default_warehouse_update"
    );

    /**
     * $_hidden
     * @var type 
     * user_name_update_hidden
     * user_id
     * office_type
     * province_id
     * district_id
     * tehsil_id
     * parent_id
     * office_id_edit
     * province_id_edit
     * district_id_edit
     * district_id_edit
     * tehsil_id_edit
     * parent_id_edit
     * warehouse_users_id_edit
     * default_warehouse_update_hidden
     */
    private $_hidden = array(
        "user_name_update_hidden" => "user_name_update_hidden",
        "user_id" => "pkId",
        "office_type" => "pkId",
        "province_id" => "pkId",
        "district_id" => "pkId",
        "tehsil_id" => "pkId",
        "parent_id" => "pkId",
        "office_id_edit" => "pkId",
        "province_id_edit" => "pkId",
        "district_id_edit" => "pkId",
        "tehsil_id_edit" => "pkId",
        "parent_id_edit" => "pkId",
        "warehouse_users_id_edit" => "pkId",
        "default_warehouse_update_hidden" => "default_warehouse_update_hidden"
    );

    /**
     * $_list
     * @var type
     * user_type
     * default_warehouse
     * default_warehouse_update
     * role
     */
    private $_list = array(
        'user_type' => array(),
        'default_warehouse' => array(),
        'default_warehouse_update' => array(),
        'role' => array(),
        'role_policy' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $this->_list["role_policy"][''] = 'Select';
        $this->_list["role_policy_update"][''] = 'Select';
        $this->_list["role"]['6'] = 'Supplier';
        $this->_list["role"]['21'] = 'Non-Supplier';
        /**
         * Generate Text Fields
         */
        foreach ($this->_fields as $col => $name) {
            /**
             * email
             * phone
             * email_update
             * phone_update
             */
            switch ($col) {
                case "email":
                case "phone":
                case "email_update":
                case "phone_update":
                /**
                 * user_name_add
                 * user_name_update
                 * search_policy_users
                 * policy_users_add
                 */
                case "user_name_add":
                case "user_name_update":
                case "login_id_add":
                case "login_id_update":
                case "search_policy_users":
                case "policy_users_add":
                    parent::createText($col);
                    break;
                /**
                 * password
                 * confirm_password
                 * old_password
                 * new_password
                 */
                case "password":
                case "confirm_password":
                case "old_password":
                case "new_password":
                    parent::createPassword($col);
                    break;
                default:
                    break;
            }
            /**
             * Generate Select Box
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        /**
         * Generate Hidden Fields
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                /**
                 * user_name_update_hidden
                 * user_id
                 * office_type
                 * office_id_edit
                 * province_id
                 * district_id
                 * tehsil_id
                 * parent_id
                 * province_id_edit
                 * district_id_edit
                 * tehsil_id_edit
                 * parent_id_edit
                 * warehouse_users_id_edit
                 * default_warehouse_update_hidden
                 */
                case "user_name_update_hidden":
                case "user_id":
                case "office_type":
                case "office_id_edit":
                case "province_id":
                case "district_id":
                case "tehsil_id":
                case "parent_id":
                case "province_id_edit":
                case "district_id_edit":
                case "tehsil_id_edit":
                case "parent_id_edit":
                case "warehouse_users_id_edit":
                case "default_warehouse_update_hidden":
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
