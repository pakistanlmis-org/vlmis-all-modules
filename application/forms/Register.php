<?php

/**
 * Form_RegisterUser
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Register User
 */
class Form_Register extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @e_mail: e_mail
     * @organization: organization
     * @country: country
     * @address: address
     * 
     * @var type 
     */
    private $_fields = array(
        "name" => "name",
        "other" => "other",
        "e_mail" => "e_mail",
        "organization" => "organization",
        "level" => "level",
        "address" => "address",
        "designation" => "designation",
        "contact" => "contact",
        "province" => "province",
        "district" => "district",
        "comments" => "comments"
    );

    /**
     * $_list
     * 
     * List
     * @country
     * 
     * @var type 
     */
    private $_list = array(
        "level" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $this->addElement('captcha', 'captcha', array(
            "attribs" => array("class" => "form-control"),
            'required' => true,
            'captcha' => array(
                'captcha' => 'Image',
                'font' => PUBLIC_DIR . '/fonts/arial.ttf',
                'fontSize' => '24',
                'wordLen' => 5,
                'height' => '50',
                'width' => '150',
                'imgDir' => PUBLIC_DIR . '/captcha',
                'imgUrl' => Zend_Controller_Front::getInstance()->getBaseUrl() . '/captcha',
                'imgAlt' => "Captcha Image",
                //error message
                'messages' => array(
                    'badCaptcha' => 'Please enter the correct code'
                ),
                'dotNoiseLevel' => 50,
                'lineNoiseLevel' => 5)
        ));

        $this->getElement('captcha')->removeDecorator("Label")->removeDecorator("HtmlTag");

        // Generate Country List Combo 
        $locations = new Model_Locations();

        $result = $locations->getAllProvinces();

        if ($result) {
            $this->_list["province"][''] = "Select";
            foreach ($result as $row) {
                $this->_list["province"][$row['pkId']] = $row['locationName'];
            }
        }



        $stakeholders = new Model_Stakeholders();
        $result = $stakeholders->getAllStakeholdersUsers();
        if ($result) {
            $this->_list["organization"][''] = "Select";
            foreach ($result as $row) {
                $this->_list["organization"][$row['pkId']] = $row['stakeholderName'];
            }
        }
        $this->_list["organization"]['7'] = "Other";

        $this->_list["district"][''] = "Select";
        $this->_list["level"]['1'] = 'Federal';
        $this->_list["level"]['2'] = 'Province';
        $this->_list["level"]['3'] = 'Division';
        $this->_list["level"]['4'] = 'District';
        $this->_list["level"]['5'] = 'Tehsil';
        $this->_list["level"]['6'] = 'Cluster Lead';
        //    }
        // }


        foreach ($this->_fields as $col => $name) {
            switch ($col) {

                case "e_mail":
                case "address":
                case "name":
                case "other":
                case "designation":
                case "contact":
                case "comments":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

}
