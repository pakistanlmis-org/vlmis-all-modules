<?php

/**
 * Form_LoginDoc
 *
 * 
 *
 *     Logistics Management 
 * Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Login Doc
 * 
 * Inherits: Form_Base
 */
class Form_LoginDoc extends Form_Base {

    /**
     * For fields 
     * for Form_LoginDoc
     * 
     * login_id
     * password
     * @var type 
     */
    private $_fields = array(
        "login_id" => "Login Id",
        "password" => "Password"
    );

    /**
     * Initializes Form Fields
     * for Form_LoginDoc
     * 
     */
    public function init() {

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "login_id":
                    parent::createText($col);
                    break;
                case "password":
                    parent::createPassword($col);
                    break;
                default:
                    break;
            }
        }
    }
}

