<?php

/**
 * Zend_View_Helper_GetManufacturer
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helpe Get Manufacturer
 */
class Zend_View_Helper_GetShipmentsReceive extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Manufacturer
     * @param type $batch_id
     */
    public function getShipmentsReceive($shipment_id) {


       $querypro = "SELECT
shipments_receive.pk_id,
shipments_receive.shipment_product_batch_id,
shipments_receive.quantity,
shipments_receive.placement_location_id,
shipments_receive.vvm_stage,
shipments_receive.created_by,
shipments_receive.created_date,
shipments_receive.modified_by,
shipments_receive.modified_date,
shipments_receive.counter
FROM
shipments_receive
INNER JOIN shipment_product_batch ON shipments_receive.shipment_product_batch_id = shipment_product_batch.pk_id
WHERE
shipment_product_batch.shipment_id = $shipment_id
";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();
        return $result;
    }

}
