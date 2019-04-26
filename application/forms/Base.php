<?php

/**
 * Form_Base
 *
 * Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * 
 *  Base Class for all zend form elements
 *  This class has methods for generating the form elements
 *  using Zend Forms. 
 *  It uses Zend Form elements 
 *  to create form elements
 */
class Form_Base extends Zend_Form {

    /**
     * Create text field.
     * @param type $col
     * @return \Form_Base
     */
    public function createText($col) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control"),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates password fields.
     * @param type $col
     * @return \Form_Base
     */
    public function createPassword($col) {
        $this->addElement("password", $col, array(
            "attribs" => array("class" => "form-control"),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates text field with auto complete off.
     * @param type $col
     * @return \Form_Base
     */
    public function createTextWithAutocompleteOff($col) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control", "autocomplete" => "off"),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates text with placeholder.
     * @param type $col
     * @param type $placeholder
     * @return \Form_Base
     */
    public function createTextWithPlaceholder($col, $placeholder) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control", "placeholder" => $placeholder),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Create Text With Value
     * @param type $col
     * @param type $value
     * @return \Form_Base
     */
    public function createTextWithValue($col, $value) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control", 'value' => $value),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        $this->getElement($col)->setValue($value);
        return $this;
    }

    /**
     * Create Text With Additional Class
     * @param type $col
     * @param type $class
     * @return \Form_Base
     */
    public function createTextWithAdditionalClass($col, $class) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control  $class"),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Create Text Wit Multiple
     * @param type $col
     * @return \Form_Base
     */
    public function createTextWitMultiple($col) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control"),
            "allowEmpty" => true,
            "multiple" => "multiple",
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates read only text.
     * @param type $col
     * @return \Form_Base
     */
    public function createReadOnlyText($col) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control", 'readonly' => 'true'),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates read only text field with value.
     * @param type $col
     * @param type $value
     * @return \Form_Base
     */
    public function createReadOnlyTextWithValue($col, $value) {
        $this->addElement("text", $col, array(
            "attribs" => array("class" => "form-control", 'readonly' => 'true'),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array(),
            "value" => $value
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates multiline text field.
     * @param type $col
     * @param type $rows
     * @return \Form_Base
     */
    public function createMultiLineText($col, $rows) {
        $this->addElement("textarea", $col, array(
            "attribs" => array("class" => "form-control", 'rows' => "$rows", 'maxlength' => '300'),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creats select field.
     * @param type $col
     * @param type $multioptions
     * @return \Form_Base
     */
    public function createSelect($col, $multioptions) {
        $this->addElement("select", $col, array(
            "attribs" => array("class" => "form-control"),
            "filters" => array("StringTrim", "StripTags"),
            "allowEmpty" => true,
            "required" => false,
            "registerInArrayValidator" => false,
            "multiOptions" => $multioptions,
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Create Select Without Multioptions
     * @param type $col
     * @return \Form_Base
     */
    public function createSelectWithoutMultioptions($col) {
        $this->addElement("select", $col, array(
            "attribs" => array("class" => "form-control"),
            "allowEmpty" => false,
            "filters" => array("StringTrim", "StripTags")
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates multi select field.
     * @param type $col
     * @param type $multioptions
     * @return \Form_Base
     */
    public function createMultiSelect($col, $multioptions) {
        $this->addElement("multiselect", $col, array(
            "attribs" => array("class" => "form-control"),
            "filters" => array("StringTrim", "StripTags"),
            "allowEmpty" => true,
            "required" => false,
            "registerInArrayValidator" => false,
            "multiOptions" => $multioptions,
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates select field with validator.
     * @param type $col
     * @param type $name
     * @param type $multioptions
     * @return \Form_Base
     */
    public function createSelectWithValidator($col, $name, $multioptions) {
        $this->addElement("select", $col, array(
            "attribs" => array("class" => "form-control"),
            "filters" => array("StringTrim", "StripTags"),
            "allowEmpty" => true,
            "required" => false,
            "registerInArrayValidator" => false,
            "multiOptions" => $multioptions,
            "validators" => array(
                array(
                    "validator" => "Float",
                    "breakChainOnFailure" => false,
                    "options" => array(
                        "messages" => array("notFloat" => $name . " must be a valid option")
                    )
                )
            )
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates hidden field.
     * @param type $col
     * @return \Form_Base
     */
    public function createHidden($col) {
        $this->addElement("hidden", $col, array(
            "attribs" => array("class" => "hidden"),
            "allowEmpty" => false,
            "filters" => array("StringTrim"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates hidden field with validator.
     * @param type $col
     * @return \Form_Base
     */
    public function createHiddenWithValidator($col) {
        $this->addElement("hidden", $col, array(
            "attribs" => array("class" => "hidden"),
            "allowEmpty" => false,
            "filters" => array("StringTrim"),
            "validators" => array(
                array(
                    "validator" => "NotEmpty",
                    "breakChainOnFailure" => true,
                    "options" => array("messages" => array("isEmpty" => "ID cannot be blank"))
                ),
                array(
                    "validator" => "Digits",
                    "breakChainOnFailure" => false,
                    "options" => array("messages" => array("notDigits" => "ID must be numeric")
                    )
                )
            )
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates radio field.
     * @param type $col
     * @param type $multioptions
     * @return \Form_Base
     */
    public function createRadio($col, $multioptions) {
        $this->addElement("radio", $col, array(
            "attribs" => array(),
            "allowEmpty" => true,
            'separator' => '',
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array(),
            "multiOptions" => $multioptions
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag")->removeDecorator("<br>");
        return $this;
    }

    /**
     * Create Radio Without Multioptions
     * @param type $col
     * @return \Form_Base
     */
    public function createRadioWithoutMultioptions($col) {
        $this->addElement("radio", $col, array(
            "attribs" => array(),
            "allowEmpty" => true,
            'separator' => '',
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array(),
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates radio with options.
     * @param type $col
     * @param type $multioptions
     * @param type $options
     * @return \Form_Base
     */
    public function createRadioWithOptions($col, $multioptions, $options) {
        $this->addElement("radio", $col, array(
            "attribs" => array(),
            "allowEmpty" => true,
            'separator' => '',
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array(),
            "multiOptions" => $multioptions,
            "options" => $options
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag")->removeDecorator("<br>");
        return $this;
    }

    /**
     * Creates checkbox field with validator.
     * @param type $col
     * @param type $multioptions
     * @return \Form_Base
     */
    public function createCheckbox($col, $multioptions) {
        $this->addElement("checkbox", $col, array(
            "attribs" => array(),
            "allowEmpty" => true,
            'separator' => '',
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array(),
            "multiOptions" => $multioptions
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates checkbox without validator.
     * @param type $col
     * @return \Form_Base
     */
    public function createCheckbox1($col) {
        $this->addElement("checkbox", $col, array(
            "attribs" => array(),
            "allowEmpty" => true,
            'separator' => '',
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

    /**
     * Creates multi check box field.
     * @param type $col
     * @param type $multioptions
     * @return \Form_Base
     */
    public function createMultiCheckbox($col, $multioptions) {
        $this->addElement("multiCheckbox", $col, array(
            "attribs" => array(),
            "allowEmpty" => true,
            'separator' => '',
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array(),
            "multiOptions" => $multioptions
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag")->removeDecorator("<br>");
        return $this;
    }

    /**
     * Creates file field.
     * @param type $col
     * @return \Form_Base
     */
    public function createFile($col) {
        $this->addElement("file", $col, array(
            "attribs" => array("class" => "form-control"),
            "destination" => UPLOAD_PATH,
            "allowEmpty" => true,
            "required" => false,
            "filters" => array("StringTrim", "StripTags"),
            "validators" => array()
        ));
        $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
        return $this;
    }

}
