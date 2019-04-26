<?php

/**
 * Form_PlannedIssueFilters
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Planned Issue Filters
 */
class Form_PlannedIssueFilters extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @to_warehouse_id: Warehouse
     * @item_pack_size_id: Product Id
     * @from_date: From Date
     * @to_date: To Date
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "to_warehouse_id" => "To Warehouse",
        "item_pack_size_id" => "Product Id",
        "from_date" => "From Date",
        "to_date" => "To Date",
        "status" => "Status"
    );

    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'from_warehouse_id' => array(),
        'item_pack_size_id' => array(),
        'status' => array('Planned' => 'Planned', 'Receiving' => 'Picking', 'Received' => 'Issued')
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate WareHouses Combo
        $pc = new Model_PipelineConsignments();
        $result1 = $pc->getPipelineToWarehouses();
        $this->_list["to_warehouse_id"][""] = 'Select';
        foreach ($result1 as $wh) {
            $this->_list["to_warehouse_id"][$wh['pkId']] = $wh['warehouseName'];
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
        $date_from = date('01/m/Y');
        $date_to = date('d/m/Y');

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "from_date":
                    parent::createReadOnlyTextWithValue($col, $date_from);
                    break;
                case "to_date":
                    parent::createReadOnlyTextWithValue($col, $date_to);
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
