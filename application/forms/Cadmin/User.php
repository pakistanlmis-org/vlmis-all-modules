<?php

/**
 * Form_Cadmin_User
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin User
 */
class Form_Cadmin_User extends Form_Base {

    /**
     * Form Fields
     *
     * @login_id: Username
     * @role: User Role
     * @email: Email
     * @phone: Phone No
     * @password: Password
     * @confirm_password: Confirm Password
     * @old_password: Old Password
     * @new_password: New Password
     * @designation: Designation
     * @department: Department
     * @photo: Photo
     * @address: Address
     * @old_warehouse: Warehouse
     *
     * @var type
     *
     */
    private $_fields = array(
        "login_id" => "Username",
        "role" => "User Role",
        "email" => "Email",
        "phone" => "Phone No",
        "password" => "Password",
        "confirm_password" => "Confirm Password",
        "old_password" => "Old Password",
        "new_password" => "New Password",
        "designation" => "Designation",
        "department" => "Department",
        "photo" => "Photo",
        "address" => "Address",
        "old_warehouse" => "Warehouse"
    );

    /**
     * $_list
     * @var type 
     * role
     */
    private $_list = array(
        "role" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        /**
         * Get Role By Active
         * @param Cold Chain  default:null
         * @return Roles By Category
         */
        $roles = new Model_Roles();
        $result = $roles->getRoleByCat(Model_Roles::COLDCHAIN);

        foreach ($result as $role) {
            $this->_list["role"][''] = 'Select';
            $this->_list["role"][$role->getPkId()] = $role->getRoleName();
        }
        /**
         * Generate Text Boxes
         */
        foreach ($this->_fields as $col => $name) {

            switch ($col) {
                /**
                 * login_id
                 * email
                 * phone
                 */
                case "login_id":
                case "email":
                case "phone":
                    parent::createText($col);
                    break;
                /**
                 * password
                 * confirm password
                 */
                case "password":
                case "confirm_password":
                    parent::createPassword($col);
                    break;
                default:
                    break;
            }
            /**
             * Combo boxes
             * 
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        $this->addElement("hidden", "id", array(
            "attribs" => array("class" => "hidden"),
            "allowEmpty" => true,
            "required" => false,
            "filters" => array("StringTrim"),
            "validators" => array()
        ));
        $this->getElement("id")->removeDecorator("Label")->removeDecorator("HtmlTag");
    }

    /**
     * Add Fields
     */
    public function addFields() {

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * designation
                 * department
                 * old_warehouse
                 */
                case "designation":
                case "department":
                case "old_warehouse":
                    parent::createText($col);
                    break;
                /**
                 * photo
                 */
                case "photo":
                    parent::createFile($col);
                    break;
                /**
                 * old_password
                 * new_password
                 */
                case "old_password":
                case "new_password":
                    parent::createPassword($col);
                    break;
                /**
                 * address
                 */
                case "address":
                    parent::createMultiLineText($col, 1);
                    break;
                default:
                    break;
            }
        }

        $this->getElement('login_id')->setAttrib("disabled", "true");
        $this->getElement('email')->setAttrib("disabled", "true");
        $this->getElement('old_warehouse')->setAttrib("disabled", "true");
    }

}
