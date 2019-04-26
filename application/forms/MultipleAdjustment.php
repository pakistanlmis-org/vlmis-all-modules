<?php

/**
 * Form_MultipleAdjustment
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Multiple Adjustment
 */
class Form_MultipleAdjustment extends Form_Base {

    /**
     * Fields 
     * for Form Form_MultipleAdjustment
     * 
     * 
     *  searchby
     *  transaction_date
     *  transaction_number
     *  transaction_reference
     *  vvm_stage
     *  adjustment_type
     *  quantity
     *  batch_no
     *  expiry_date
     *  product
     *  warehouse_name
     *  available_quantity
     *  activity_id"
     *  campaign_id
     *  dispatch_by
     *  issue_period
     *  issue_from
     *  issue_to
     *  comments
     *  location_id
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "searchby" => "Search By",
        "transaction_date" => "Transaction Date",
        "transaction_number" => "Transaction Number",
        "transaction_reference" => "Transaction Reference",
        "vvm_stage" => "VVM Stage",
        "adjustment_type" => "Adjustment type",
        "quantity" => "Quantity",
        "batch_no" => "batch_no",
        "expiry_date" => "Expiry Date",
        "product" => "Product",
        "warehouse_name" => "Warehouse Name",
        "available_quantity" => "Available Quantity",
        "activity_id" => "Purpose",
        'campaign_id' => 'Campaign ID',
        'dispatch_by' => 'Dispatch By',
        'issue_period' => 'Period',
        'issue_from' => 'Issue From',
        'issue_to' => 'Issued To',
        "comments" => "Comments",
        "location_id" => "Location"
    );

    /**
     * Hidden Fields 
     * for Form Form_MultipleAdjustment
     * 
     * 
     *  hdn_transaction_number
     *  hdn_stock_id
     *  hdn_province_id
     *  hdn_district_id
     *  hdn_warehouse_id
     *  hdn_available_quantity
     *  hdn_to_warehouse_id
     *  hdn_master_id
     *  hdn_activity_id
     *  hdn_campaign_id
     *  hdn_transaction_date
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "hdn_transaction_number" => "",
        "hdn_stock_id" => "",
        "hdn_province_id" => "",
        "hdn_district_id" => "",
        "hdn_warehouse_id" => "",
        "hdn_available_quantity" => "",
        "hdn_to_warehouse_id" => "",
        "hdn_master_id" => "",
        "hdn_activity_id" => "",
        "hdn_campaign_id" => "",
        "hdn_transaction_date" => "hdn_transaction_date"
    );

    /**
     * Combo boxes
     * for Form Form_MultipleAdjustment
     * 
     * 
     * searchby
     * product
     * product
     * vvm_stage
     * activity_id
     * campaign_id
     * issue_period
     * adjustment_type
     * location_id
     * 
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'searchby' => array(
            "0" => "Select",
            "1" => "Issue No",
            "2" => "Issue Ref",
            "3" => "Batch No"
        ),
        'product' => array(),
        'product' => array(),
        'vvm_stage' => array(
            '' => 'NA'
        ),
        'activity_id' => array(),
        'campaign_id' => array('' => 'Select'),
        'issue_period' => array(),
        'adjustment_type' => array(),
        'location_id' => array()
    );

    /**
     * Initializes Form Fields
     * for Form Form_MultipleAdjustment
     */
    public function init() {

        $transaction_types = new Model_TransactionTypes();
        $result = $transaction_types->getAdjusmentTypes();

        foreach ($result as $trans) {
            $this->_list["adjustment_type"][''] = 'Select';
            $this->_list["adjustment_type"][$trans['pkId']] = $trans['transactionTypeName'];
        }

        $item_pack_sizes = new Model_ItemPackSizes();
        $result1 = $item_pack_sizes->getAllManageItems();
        $this->_list["product"][''] = 'Select';
        if ($result1 && count($result1) > 0) {
            foreach ($result1 as $whs) {
                $this->_list["product"][$whs['pkId']] = $whs['itemName'];
            }
        }

        $quarter = array(
            1 => '1st Quarter',
            2 => '2nd Quarter',
            3 => '3rd Quarter',
            4 => '4th Quarter'
        );
        $this->_list["issue_period"][""] = "Select Period";

        for ($y = 2015; $y <= date("Y"); $y++) {
            for ($q = 1; $q <= 4; $q++) {
                switch ($q) {
                    case 1:
                        $key = "01/01/$y-01/03/$y";
                        break;
                    case 2:
                        $key = "01/04/$y-01/06/$y";
                        break;
                    case 3:
                        $key = "01/07/$y-01/09/$y";
                        break;
                    case 4:
                        $key = "01/10/$y-01/12/$y";
                        break;
                    default :
                        break;
                }
                $this->_list["issue_period"][$key] = $quarter[$q] . " - " . $y;
            }
        }

        $this->_list["issue_period"]["custom"] = "Custom";
        $this->_list["item_id"][''] = "Select";

        //Generate Purpose(activity_id) combo 
        $stk_activities = new Model_StakeholderActivities();
        $result1 = $stk_activities->getAllStakeholderActivities();
        foreach ($result1 as $stk_activity) {
            $this->_list["activity_id"][''] = "Select";
            $this->_list["activity_id"][$stk_activity['pkId']] = $stk_activity['activity'];
        }

        //Generating Hidden fields
        // for Form_MultipleAdjustment
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "hdn_transaction_number":
                case "hdn_stock_id":
                case "hdn_province_id":
                case "hdn_district_id":
                case "hdn_warehouse_id":
                case "hdn_available_quantity":
                case "hdn_to_warehouse_id":
                case "hdn_master_id":
                case "hdn_activity_id":
                case "hdn_campaign_id":
                case "hdn_transaction_date":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }

        // Generating Form Fields
        // for Form_MultipleAdjustment
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "transaction_reference":
                case "quantity":
                case "dispatch_by":
                case "issue_from":
                case "issue_to":
                    parent::createText($col);
                    break;
                case "expiry_date":
                case "transaction_date":
                case "available_quantity":
                case "transaction_number":
                case "warehouse_name":
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
