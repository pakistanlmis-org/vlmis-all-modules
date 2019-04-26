<?php

/**
 * Form_Iadmin_ResourceSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Resource Search
 */
class Form_Iadmin_ResourceSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @resource_name: Resource Name
     * @resource_type: Resource Type
     * 
     * @var type 
     */
    private $_fields = array(
        "resource_name" => "Resource name",
        "resource_type" => "Resource type"
    );

    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'resource_type' => array()
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

        foreach ($this->_fields as $col => $name) {
            if ($col == "resource_name") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
