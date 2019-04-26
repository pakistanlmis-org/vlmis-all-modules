<?php

/**
 * Form_PipelineConsignmentsFilters
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Pipleine Consignments Filters
 */
class Form_PipelineConsignmentsFilters extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @from_warehouse_id: Source
     * @item_pack_size_id: Product Id
     * @from_date: From Date
     * @to_date: To Date
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "from_warehouse_id" => "Source",
        "item_pack_size_id" => "Product Id",
        "from_date" => "From Date",
        "to_date" => "To Date",
        "status" => "Status"
    );

    /**
     * $_list
     * 
     * List
     * @from_warehouse_id
     * @item_pack_size_id
     * @status
     * 
     * @var type 
     */
    private $_list = array(
        'from_warehouse_id' => array(),
        'item_pack_size_id' => array(),
        'status' => array('Planned' => 'Planned', 'Receiving' => 'Receiving', 'Received' => 'Received')
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate WareHouses Combo
        $warehouse = new Model_Warehouses();
        $result1 = $warehouse->getSupplierWarehouses();
        $this->_list["from_warehouse_id"][""] = 'Select';
        foreach ($result1 as $wh) {
            $this->_list["from_warehouse_id"][$wh['pk_id']] = $wh['warehouse_name'];
        }

        //Generate Item Combo
        $item_pack_size = new Model_ItemPackSizes();
        $result = $item_pack_size->getItemsAll();
        $this->_list["item_pack_size_id"][''] = "Select";
        if ($result) {
            foreach ($result as $row) {
                $this->_list["item_pack_size_id"][$row->getPkId()] = $row->getItemName();
            }
        }

        $from_date = date('01/m/Y');
        $to_date = date('d/m/Y');

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "from_date":
                    parent::createReadOnlyTextWithValue($col, $from_date);
                    break;
                case "to_date":
                    parent::createReadOnlyTextWithValue($col, $to_date);
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
