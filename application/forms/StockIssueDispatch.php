<?php

/**
 * Form_StockIssueDispatch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Stock Issue Dispatch
 */
class Form_StockIssueDispatch extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @searchby: Search By
     * @number: Number
     * @warehouses: Warehouse/Supplier
     * @product: Product
     * @transaction_date: Transaction Date
     * @date_from: Date From
     * @activity_id: Purpose
     * @date_to: Date To
     * @month: month
     * @year: year
     * 
     * @var type 
     */
    private $_fields = array(
        "searchby" => "Search By",
        "number" => "Number",
        "warehouses" => "Warehouse/Supplier",
        "product" => "Product",
        "transaction_date" => "Transaction Date",
        "date_from" => "Date From",
        "activity_id" => "Purpose",
        "date_to" => "Date To",
        "month" => "month",
        "year" => "year"
    );

    /**
     * $_list
     * 
     * List
     * @searchby:
     * Select
     * Receive No
     * Receive Ref
     * Batch No
     * 
     * @var type 
     */
    private $_list = array(
        'searchby' => array(
            "0" => "Select",
            "1" => "Receive No",
            "2" => "Receive Ref",
            "3" => "Batch No"
        ),
        'product' => array(),
        'warehouses' => array()
    );

    /**
     * Initializes Form Fields 
     */
    public function init() {
        //Generate Products(items) Combo
        $item_pack_sizes = new Model_ItemPackSizes();
        $result2 = $item_pack_sizes->getAllItems();
        $this->_list["product"][''] = "Select";
        foreach ($result2 as $item) {
            $this->_list["product"][$item['pkId']] = $item['itemName'];
        }


        $warehouses = new Model_Warehouses();

        $result3 = $warehouses->getUserReceiveFromWarehouse();
        foreach ($result3 as $whs) {

            $this->_list["warehouses"][''] = "Select";
            $this->_list["warehouses"][$whs['pkId']] = $whs['warehouseName'];
        }

        $date_from = date('01/m/Y');
        $date_to = date('d/m/Y');

        foreach ($this->_fields as $col => $name) {

            switch ($col) {
                case "number":
                    parent::createText($col);
                    break;
                case "date_from":
                    parent::createReadOnlyTextWithValue($col, $date_from);
                    break;
                case "date_to":
                    parent::createReadOnlyTextWithValue($col, $date_to);
                    break;
                case "transaction_date":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            //Generate Purpose(activity_id) combo
            $stk_activities = new Model_StakeholderActivities();
            $result4 = $stk_activities->getAllStakeholderActivitiesIssues();
            if ($result4) {
                foreach ($result4 as $stk_activity) {

                    $this->_list["activity_id"][$stk_activity['pkId']] = $stk_activity['activity'];
                }
            }

            for ($i = 1; $i <= 12; $i++) {
                $this->_list["month"][''] = "Select";
                $this->_list["month"][$i] = date('M', mktime(0, 0, 0, $i, 1));
            }

            for ($j = date('Y'); $j >= 2014; $j--) {

                $this->_list["year"][''] = "Select";
                $this->_list["year"][$j] = $j;
            }
        }
    }

}
