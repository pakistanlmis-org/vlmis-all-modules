<?php

/**
 * Form_Iadmin_Products
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Products
 */
class Form_Iadmin_Products extends Form_Base {

    /**
     * Fields
     * for Form_Iadmin_Products
     *
     *
     * item_name
     * description
     * number_of_doses
     * list_rank
     * item_category
     * item_unit
     * item
     * item_category_name
     * item_unit_name
     * item_description
     * percent_population_covered
     *
     *
     * $_fields
     * @var type
     */
    private $_fields = array(
        "item_name" => "item_name",
        "description" => "description",
        "number_of_doses" => "number_of_doses",
        "total_doses" => "total_doses",
        "starting_dose" => "starting_dose",
        "list_rank" => "list_rank",
        "item_category" => "item_category",
        "item_unit" => "item_unit",
        "item" => "group",
        "item_category_name" => "item_category_name",
        "item_unit_name" => "item_unit_name",
        "item_description" => "item_description",
        "percent_population_covered" => "percent_population_covered",
        "purpose" => "purpose",
        "province" => "province",
        "status" => "status"
    );

    /**
     * Hidden Fields
     * for Form_Iadmin_Products
     *
     * item_category_name_hidden
     * item_name_hidden
     * item_id
     * item_category_id
     * item_unit_id
     * item_group_id
     * item_unit_name_hidden
     * item_description_hidden
     *
     * $_hidden
     * @var type
     */
    private $_hidden = array(
        "item_description_hidden" => "item_description_hidden",
        "item_unit_name_hidden" => "item_unit_name_hidden",
        "item_category_name_hidden" => "item_category_name_hidden",
        "item_name_hidden" => "item_name_hidden",
        "item_id" => "item_id",
        "item_category_id" => "item_category_id",
        "item_unit_id" => "item_unit_id",
        "item_group_id" => "item_group_id"
    );

    /**
     * Combo boxes
     * for Form_Iadmin_Products
     *
     *
     * list_rank
     * item_category
     * item_unit
     * item
     *
     *
     * $_list
     * @var type
     */
    private $_list = array(
        'list_rank' => array(
        ),
        'item_category' => array(),
        'item_unit' => array(),
        'item' => array(),
        "purpose" => array(),
        "province" => array(),
        "status" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $this->_list["list_rank"][''] = "Select";
        $this->_list["list_rank"]['1'] = "1";
        $this->_list["list_rank"]['2'] = "2";
        $this->_list["list_rank"]['3'] = "3";
        $this->_list["list_rank"]['4'] = "4";
        $this->_list["list_rank"]['5'] = "5";


        $this->_list["status"][''] = "Select";
        $this->_list["status"]['1'] = "Active";
        $this->_list["status"]['0'] = "In Active";
        //Generate Combos
        $item_category = new Model_ItemCategories();

        $result1 = $item_category->getAllCategories();
        $this->_list["item_category"][''] = "Select";
        foreach ($result1 as $rs) {
            $this->_list["item_category"][$rs['pkId']] = $rs['itemCategoryName'];
        }
        $item_units = new Model_ItemUnits();

        $result2 = $item_units->getAllItemUnits();
        $this->_list["item_unit"][''] = "Select";
        foreach ($result2 as $rs) {
            $this->_list["item_unit"][$rs['pkId']] = $rs['itemUnitName'];
        }

        $item = new Model_Item();

        $result3 = $item->getAllItems();
        $this->_list["item"][''] = "Select";
        foreach ($result3 as $rs) {
            $this->_list["item"][$rs['pkId']] = $rs['description'];
        }


        //Generate Purpose(activity_id) combo
        $stk_activities = new Model_StakeholderActivities();
        $result4 = $stk_activities->getAllStakeholderActivitiesIssues();
        if ($result4) {
            $stakeholder_id = $result4[0]['pkId'];
            foreach ($result4 as $stk_activity) {
                $this->_list["purpose"][$stk_activity['pkId']] = $stk_activity['activity'];
            }
        }

        // Generate Country List Combo
        $locations = new Model_Locations();

        $result = $locations->getAllProvinces();

        if ($result) {
            $this->_list["province"][''] = "Select";
            $this->_list["province"]['10'] = "All";
            foreach ($result as $row) {
                $this->_list["province"][$row['pkId']] = $row['locationName'];
            }
        }




        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "item_name":
                case "description":
                case "number_of_doses":
                case "item_category_name":
                case "item_unit_name":
                case "item_description":
                case "percent_population_covered";
                case "total_doses":
                case "starting_dose";
                    parent::createText($col);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }

        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "item_description_hidden";
                case "item_unit_name_hidden";
                case "item_category_name_hidden":
                case "item_name_hidden":
                case "item_id":
                case "item_category_id":
                case "item_unit_id":
                case "item_group_id":
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
        $this->addElement("hidden", "id", array(
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
        $this->getElement("id")->removeDecorator("Label")->removeDecorator("HtmlTag");
    }

}
