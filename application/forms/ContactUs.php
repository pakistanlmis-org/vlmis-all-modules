<?php

/**
 * Form_ContactUs
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Contact Us
 * This form is used for 
 * userfeadback
 * for anonymous users
 */
class Form_ContactUs extends Form_Base {

    /**
     * Fields for Form_ContactUs
     * 
     * Private Variable
     * 
     * Form Fields
     * name
     * e_mail
     * phone
     * department
     * message
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "name" => "name",
        "e_mail" => "e_mail",
        "phone" => "phone",
        "department" => "department",
        "message" => "message"
    );

    /**
     * Initializes Form Fields
     * for Form_ContactUs
     * 
     * Captcha Verification
     * 
     * Form Elements
     * @captcha: Captcha
     * @text: Text
     */
    public function init() {

        //Generate captcha for verification
        $this->addElement('captcha', 'captcha', array(
            "attribs" => array("class" => "form-control", "autocomplete" => "off"),
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


        //Generate fields for 
        // Form_ContactUs
        foreach ($this->_fields as $col => $name) {
            switch ($col) {

                case "name":
                case "phone":
                case "department":
                    parent::createTextWithAutocompleteOff($col);
                    break;
                case "e_mail":
                    parent::createText($col);
                    break;
                case "message":
                    parent::createMultiLineText($col, "5");
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_ContactUs
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
