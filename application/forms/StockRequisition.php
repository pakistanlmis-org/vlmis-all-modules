<?php

/**
 * Form_StockRequisition
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Stock Requisition
 */
class Form_StockRequisition extends Form_Base {

    /**
     *
     * @var type 
     */
    private $_fields = array(
        "requisition_number" => "Requisition Number",
        "suggested_date" => "Suggested Date",
        "requisition_reference" => "Requisition Reference",
        "comments" => "Comments",
        "quantity" => "Quantity Required",
        "item_id" => "Product",
        "activity_id" => "Purpose",
        "warehouse_name" => "Warehouse Name",
        'period' => 'Period',
        'allocated_qty' => 'Allocated Qty',
        'remaining_balance' => 'Remaining Balance',
        'issue_from' => 'Issue From',
        'issue_to' => 'Issued To'
    );

    /**
     *
     * @var type 
     */
    private $_hidden = array(
        "hdn_transaction_number" => "",
        "hdn_stock_id" => "",
        "hdn_warehouse_id" => "",
        "hdn_to_warehouse_id" => "",
        "hdn_master_id" => "",
        "hdn_activity_id" => "",
        "hdn_suggested_date" => "hdn_suggested_date"
    );

    /**
     *
     * @var type 
     */
    private $_list = array(
        'requisition_number' => array(
            "" => "Select"
        ),
        'item_id' => array(),
        'number' => array(),
        'activity_id' => array('' => 'Select', 1 => 'Routine'),
        'warehouse_name' => array(),
        'period' => array()
    );

    /**
     * Initializes Form Fields
     * for Form_StockRequisition
     */
    public function init() {
        $quarter = array(
            1 => '1st Quarter',
            2 => '2nd Quarter',
            3 => '3rd Quarter',
            4 => '4th Quarter'
        );
        $this->_list["period"][""] = "Select Period";

        $currentQtr = new DateTime();
        $nextQtr = new DateTime();
        $prev_qtr = new DateTime();
        $nextQtr->modify("+3 months");
        $prev_qtr->modify("-3 months");

        $quarterRange = array(1 => $prev_qtr, 2 => $currentQtr, 3 => $nextQtr);


        // start months
        $month_end = date('m');
        $curr_year = date('Y');
        for ($i = 1; $i <= $month_end; $i++) {
            $key_val = "01" . "/" . str_pad($i, 2, '0', STR_PAD_LEFT) . "/$curr_year-"."01/" . str_pad($i, 2, '0', STR_PAD_LEFT) . "/$curr_year";
            $this->_list["period"][$key_val] = date('M', mktime(0, 0, 0, $i, 1)) . " - " . $curr_year;
        }
        // end months



        foreach ($quarterRange as $qtr) {
            $month = $qtr->format("m");
            $y = $qtr->format("Y");

            if ($month >= 1 && $month <= 3) {
                $key = "01/01/$y-01/03/$y";
                $this->_list["period"][$key] = $quarter[1] . " - " . $y;
            } else if ($month >= 4 && $month <= 6) {
                $key = "01/04/$y-01/06/$y";
                $this->_list["period"][$key] = $quarter[2] . " - " . $y;
            } else if ($month >= 7 && $month <= 9) {
                $key = "01/07/$y-01/09/$y";
                $this->_list["period"][$key] = $quarter[3] . " - " . $y;
            } else if ($month >= 10 && $month <= 12) {
                $key = "01/10/$y-01/12/$y";
                $this->_list["period"][$key] = $quarter[4] . " - " . $y;
            }
        }

        //Populate Requisition Numbers
        $demand_master = new Model_DemandMaster();
        $demand_master->form_values['transaction_type_id'] = 18;

        $temp_stock_list = $demand_master->getTempDemandList();
        $this->_list["requisition_number"][''] = "New";

        if (!empty($temp_stock_list)) {
            foreach ($temp_stock_list as $res) {
                $this->_list["requisition_number"][$res['pkId']] = $res['req_no'];
            }
        }

        //Generate Products(items) Combo
        $this->_list["item_id"][''] = "Select";

        //Generate Purpose(activity_id) combo 
        /* $stk_activities = new Model_StakeholderActivities();
          $result1 = $stk_activities->getAllStakeholderActivities();
          foreach ($result1 as $stk_activity) {
          $this->_list["activity_id"][''] = "Select";
          $this->_list["activity_id"][$stk_activity['pkId']] = $stk_activity['activity'];
          } */

        $warehouses = new Model_Warehouses();
        $toWarehouses = $warehouses->getToRequisitionWarehouses();
        $this->_list["warehouse_name"][''] = "Select";
        foreach ($toWarehouses as $toWarehouse) {
            $this->_list["warehouse_name"][$toWarehouse['pk_id']] = $toWarehouse['warehouse_name'];
        }

        //Generating hidden fields 
        //for Form_StockIssue
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "hdn_transaction_number":
                case "hdn_stock_id":
                case "hdn_warehouse_id":
                case "hdn_to_warehouse_id":
                case "hdn_master_id":
                case "hdn_activity_id":
                case "hdn_suggested_date":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }

        //Generating fields
        //for Form_StockIssue
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "requisition_reference":
                case "quantity":
                case "issue_from":
                case "issue_to":
                    parent::createText($col);
                    break;
                case "suggested_date":
                case "remaining_balance":
                case "allocated_qty":
                    parent::createReadOnlyText($col);
                    break;
                case "comments":
                    parent::createMultiLineText($col, "2");
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
     * Make Field Readonly
     */
    public function makeFieldReadonly() {
        $this->getElement('activity_id')->setAttrib("disabled", "true");
    }

    /**
     * Fill Batch Combo
     * @param type $item_id
     */
    public function fillBatchCombo($item_id) {
        $auth = new App_Auth();
        $wh_id = $auth->getWarehouseId();
        $em = Zend_Registry::get('doctrine_read');

        $str_sql = $em->createQueryBuilder()
                ->select("sbw.pkId,sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->where("sip.itemPackSize = '" . $item_id . "' ")
                ->andWhere("sbw.warehouse = '$wh_id' ")
                ->andWhere("sbw.status = 'Running' ");

        $items = $str_sql->getQuery()->getResult();

        $list_item[''] = "Select";
        foreach ($items as $item) {
            $list_item[$item['pkId']] = $item['number'];
        }

        $this->getElement('number')->setMultiOptions($list_item);
    }

}
