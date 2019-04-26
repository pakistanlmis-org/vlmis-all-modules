<?php

/**
 * Form_ProcurementFilters
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Procurement Filters
 */
class Form_ProcurementFilters extends Form_Base {

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
        "status" => "Status",
        "eta" => "eta"
    );

    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'from_warehouse_id' => array(),
        'item_pack_size_id' => array(),
        'status' => array('' => 'Select', 'Received' => 'Received', 'Pre Shipment Alert' => 'Pre Shipment Alert', 'PO' => 'PO', 'Tender' => 'Tender')
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
        $item_pack_sizes = new Model_ItemPackSizes();
      //  $result = $item_pack_size->getItemsAll();
        $result2 = $item_pack_sizes->getAllItems();
        $this->_list["item_pack_size_id"][''] = "Select";
        foreach ($result2 as $item) {
            $this->_list["item_pack_size_id"][$item['pkId']] = $item['itemName'];
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
                case "eta":
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
