<?php

/**
 * AjaxController
 *
 * 
 *
 * 
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Ajax
 * 
 * Inherits App Controller Base
 */
class AjaxController extends App_Controller_Base {

    /**
     * ajaxSaveSessionValues
     * 
     * Ajax to save session values
     */
    public function ajaxSaveSessionValuesAction() {
        // Disable layout.
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $session = new Zend_Session_Namespace('alllevel');

        $office = $this->_request->getParam('office', '');
        $province = $this->_request->getParam('combo1', '');
        $combo2 = $this->_request->getParam('combo2', '');
        $store = $this->_request->getParam('warehouse', '');

        if (!empty($office)) {
            $session->office = $office;
        }
        if (!empty($province)) {
            $session->province = $province;
        }
        if (!empty($combo2)) {
            $session->combo2 = $combo2;
        }
        if (!empty($store)) {
            $session->warehouse = $store;
        }
    }

    /**
     * ajaxGetOffice
     * 
     * Ajax to Get Office
     */
    public function ajaxGetOfficeAction() {
        // Set layout.
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $session = new Zend_Session_Namespace('alllevel');

        if (!empty($session->office)) {
            echo $session->office;
        } else {
            echo '';
        }
    }

    public function ajaxGetAllBatchesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values = array(
            'item_id' => $this->_request->getParam('item_id'),
            'batch' => $this->_request->getParam('batch')
        );
        $batches = $stock_batch->getSimilarBatches();

        $batch_names = array();
        if ($batches != false) {
            foreach ($batches as $row) {
                $batch_names[] = $row['number'];
            }
        }

        echo Zend_Json::encode($batch_names);
    }

    public function ajaxGetBatchExpiryAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $stock_batch = new Model_StockBatch();
        $stock_batch->form_values = array(
            'item_id' => $this->_request->getParam('item_id'),
            'number' => $this->_request->getParam('number')
        );
        $data = $stock_batch->getBatchExpiryByNumber();

        echo Zend_Json::encode($data);        
    }
}
