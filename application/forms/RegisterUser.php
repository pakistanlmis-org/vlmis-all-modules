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

class Form_RegisterUser extends Form_Base {

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
        "e_mail" => "e_mail",
        "organization" => "organization",
        "country" => "country",
        "address" => "address"
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
        "country" => array()
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
        $result = $locations->getCountryList();

        if ($result) {
            $this->_list["country"][''] = "Select";
            foreach ($result as $row) {
                $this->_list["country"][$row['country_id']] = $row['country_name'];
            }
            $this->_list["country"]['253'] = "other";
        }


        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "organization":
                case "e_mail":
                case "address":
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
