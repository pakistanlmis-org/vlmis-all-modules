<?php

/**
 * ItemsController
 *
 * 
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Items
 */
class ItemsController extends App_Controller_Base {

    /**
     * ItemsController indexAction
     */
    public function indexAction() {
        // action body
    }

    // Batch Manager
    public function ajaxGetItemsByTypeAction() {
        $this->_helper->layout->disableLayout();
        $type = $this->_request->getParam('type');

        $item_pack_sizes = new Model_ItemPackSizes();
        switch ($type) {
            case 6:
                $result = $item_pack_sizes->getAllVaccines();
                break;
            case 20:
                $str_sql = $this->_em_read->createQueryBuilder()
                        ->select("ip.pkId, ip.itemName")
                        ->from("ItemPackSizes", "ip")
                        ->where("ip.pkId IN (25,26)")
                        ->orderBy("ip.listRank", "ASC");
                $result = $str_sql->getQuery()->getResult();
                break;
            default:
                $result = $item_pack_sizes->getAllManageItems();
                break;
        }

        $this->view->products = $result;
    }

}
