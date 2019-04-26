<?php

/**
 * Form_Iadmin_Resources
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Resources
 */
class Form_Iadmin_Resources extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * for Form_Iadmin_Resources
     * 
     * 
     * @resource_name: Resource Name
     * @description: Description
     * @resource_type: Resource Type
     * @parent_id: Parent
     * @rank: Rank
     * @level: Level
     * @page_title: Page Title
     * @meta_title: Meta Title
     * @meta_desc: Meta Description
     * 
     * @var type 
     */
    private $_fields = array(
        "resource_name" => "Resource Name",
        "description" => "Description",
        "resource_type" => "Resource Type",
        "parent_id" => "Parent",
        "rank" => "Rank",
        "level" => "Level",
        "page_title" => "Page Title",
        "meta_title" => "Meta Title",
        "meta_desc" => "Meta Description"
    );

    /**
     * Combo boxes 
     * for Form_Iadmin_Resources
     * 
     * 
     * resource_type
     * rank
     * level
     * parent_id
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'resource_type' => array(),
        'rank' => array(),
        'level' => array(),
        'parent_id' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $em = Zend_Registry::get("doctrine");
        $result = $em->getRepository("ResourceTypes")->findAll();
        $this->_list["resource_type"][''] = "Select";
        foreach ($result as $rs) {
            $this->_list["resource_type"][$rs->getPkId()] = $rs->getResourceType();
        }

        $resources = new Model_Resources();
        $result2 = $resources->getResources('resource_name', 'ASC');
        $this->_list["parent_id"][''] = "Select";
        if ($result2) {
            foreach ($result2 as $row2) {
                $resource = $row2->getResourceName();
                $arr_resources = explode("/", $resource);
                $second_name = (!empty($arr_resources[1])) ? ucfirst($arr_resources[1]) . " - " : "";
                $this->_list["parent_id"][$row2->getPkId()] = ucfirst($arr_resources[0]) . " - " . $second_name . $row2->getDescription();
            }
        }

        for ($a = 1; $a <= 20; $a++) {
            $this->_list["rank"][$a] = $a;
        }

        for ($b = 1; $b <= 10; $b++) {
            $this->_list["level"][$b] = $b;
        }

        /**
         * Add Form Fields
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "resource_name":
                case "description":
                case "page_title":
                case "meta_title":
                    parent::createText($col);
                    break;
                case "meta_desc":
                    parent::createMultiLineText($col, "4");
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
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
