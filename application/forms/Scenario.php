<?php

/**
 * Form_Placement
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Placement
 *
 * Inherits:
 * Form_Base
 */
class Form_Scenario extends Form_Base {

    /**
     * $_fields
     *
     *
     * Form Fields
     * Form_Placement
     *
     *
     * @area: Area
     * @row: Row
     * @rack: Rack
     * @pallet: Pallet
     * @level: Level
     *
     * @var type
     */
    private $_fields = array(
        "shipment_id" => "Shipment",
        "action" => "Action",
        "product" => "Product",
        "quantity" => "Quantity",
        "cold_chain" => "CR/FR"
    );

    /**
     * $_list
     *
     * Combo boxes
     * for Form_Placement
     *
     *
     * @area
     * @row
     * @rack
     * @pallet
     * @level
     *
     * @var type
     */
    private $_list = array(
        'shipment_id' => array(),
        'action' => array(),
        'product' => array(),
        'cold_chain' => array()
    );

    /**
     * $_hidden
     *
     * Hidden
     * @placement_id
     *
     * @var type
     */
    private $_hidden = array(
        "placement_id" => ""
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //Generate Action Combo

        $this->_list["action"][''] = "Select action";
        $this->_list["action"]["1"] = "Receive";
        $this->_list["action"]["2"] = "Issue";

        $shipments = new Model_Shipments();
        $result1 = $shipments->getAllShipments();
        $this->_list["shipment_id"][''] = 'Select';
        if ($result1 && count($result1) > 0) {
            foreach ($result1 as $whs) {
                $this->_list["shipment_id"][$whs['pkId']] = $whs['referenceNumber'];
            }
        }


        $item_pack_sizes = new Model_ItemPackSizes();
        $result1 = $item_pack_sizes->getAllManageItems();
        $this->_list["product"][''] = 'Select';
        if ($result1 && count($result1) > 0) {
            foreach ($result1 as $whs) {
                $this->_list["product"][$whs['pkId']] = $whs['itemName'];
            }
        }
        $this->_list["cold_chain"][''] = "Select location";

        //Generate Hidden fields
        foreach ($this->_hidden as $col => $name) {
            if ($col == "placement_id") {
                $this->addElement("hidden", $col);
                $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
            }
        }

        //Generate Form fields
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "quantity":

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

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
