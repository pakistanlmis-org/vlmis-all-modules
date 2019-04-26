
<?php

/**
 * Form_DemandIssuance
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Form_DemandIssuance
 */
class Form_DemandIssuance extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @from_warehouse_id: Source
     * @from_date: From Date
     * @to_date: To Date
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "from_warehouse_id" => "Source",
        "from_date" => "From Date",
        "to_date" => "To Date",
        "status" => "Status",
        "approved_qty" => "Approved Quantity",
        'suggested_date' => 'Suggested Date',
        'new_suggested_date' => 'New Suggested Date'
    );

    /**
     * $_list
     * 
     * List
     * @from_warehouse_id
     * @status
     * 
     * @var type 
     */
    private $_list = array(
        'from_warehouse_id' => array(),
        'status' => array('2016' => '2016', '2015' => '2015')
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate WareHouses Combo
        $warehouse = new Model_Warehouses();
        $result1 = $warehouse->getFromRequisitionWarehouses();
        //$this->_list["from_warehouse_id"][""] = 'Select';
        foreach ($result1 as $wh) {
            $this->_list["from_warehouse_id"][$wh['pk_id']] = $wh['warehouse_name'];
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
                case "approved_qty":
                    parent::createText($col);
                    break;
                case "suggested_date":
                case "new_suggested_date":
                    parent::createReadOnlyTextWithValue($col,"");
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
