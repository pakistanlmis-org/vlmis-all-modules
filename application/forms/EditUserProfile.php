<?php

/**
 * Form_EditUserProfile
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Edit User Profile
 */
class Form_EditUserProfile extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @name: Name
     * @designation: Designation
     * @e_mail: E-mail
     * @mobile: mobile
     * @phone: Phone
     * @address: Address
     * 
     * @var type 
     */
    private $_fields = array(
        "name" => "name",
        "designation" => "designation",
        "department" => "department",
        "e_mail" => "e_mail",
        "mobile" => "mobile",
        "phone" => "phone",
        "address" => "address"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //foreach loop 
        //to edit user profile 
        //in database
        foreach ($this->_fields as $col => $name) {
            switch ($col) {

                case "name":
                case "designation":
                case "department":
                case "mobile":
                case "phone":
                case "address":
                case "e_mail":
                case "":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     * 
     * To add hidden fields
     * 
     */
    public function addHidden() {
        //to add hidden values
        parent::createHiddenWithValidator("id");
    }

}
