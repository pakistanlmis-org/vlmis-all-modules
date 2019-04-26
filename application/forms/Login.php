<?php

/**
 * Form_Login
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Login
* 
* Inherits:
* Zend Form
*
* Function:
* To Login to vLMIS
*/

class Form_Login extends Zend_Form {

    /**
     * Initializes Form Fields
     * 
     * Form Fields
     * @login_id: Login Id
     * placeholder: Username...
     * 
     * @password: Password
     * Placeholder: Password...
     */
    public function init() {
        $this->addElement("text", "login_id", array(
            "attribs" => array("class" => "text form-control", "placeholder" => "Username...", "autocomplete" => "off"),
            "allowEmpty" => false,
            'filters' => array(
                array('filter' => 'StringTrim'),
                array('filter' => 'StripTags'),
                array(
                    'filter' => 'PregReplace',
                    'options' => array('match' => '#[^0-9\w,.!@$&()\[\]\-_;:\\\/\s]#', 'replace' => '')
                )
            ),
            "validators" => array(
                array(
                    "validator" => "NotEmpty",
                    "breakChainOnFailure" => true,
                    "options" => array("messages" => array("isEmpty" => "Username cannot be blank"))
                )
            )
        ));
        $this->getElement("login_id")->removeDecorator("Label")->removeDecorator("HtmlTag");

        $this->addElement("password", "password", array(
            "attribs" => array("class" => "password form-control", "placeholder" => "Password...", "autocomplete" => "off"),
            "allowEmpty" => false,
            'filters' => array(
                array('filter' => 'StringTrim'),
                array('filter' => 'StripTags')
                /*array(
                    'filter' => 'PregReplace',
                    'options' => array('match' => '#[^0-9\w,.!@$&()\[\]\-_;:\\\/\s]#', 'replace' => '')
                )*/
            ),
            "validators" => array(
                array(
                    "validator" => "NotEmpty",
                    "breakChainOnFailure" => true,
                    "options" => array("messages" => array("isEmpty" => "Password cannot be blank"))
                )
            )
        ));
        $this->getElement("password")->removeDecorator("Label")->removeDecorator("HtmlTag");
    }

}

?>