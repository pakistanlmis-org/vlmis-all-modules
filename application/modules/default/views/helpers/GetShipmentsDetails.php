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
class Zend_View_Helper_GetShipmentsDetails extends Zend_View_Helper_Abstract {

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
    public function getShipmentsDetails($shipment_id) {



        $querypro = "SELECT
            shipment_product_batch.pk_id,
            shipment_product_batch.number,
            shipment_product_batch.expiry_date,
            shipment_product_batch.unit_price,
            shipment_product_batch.production_date,
            shipment_product_batch.vvm_type_id,
            shipment_product_batch.item_pack_size_id,
            shipment_product_batch.shipment_id,
            shipment_product_batch.stakeholder_item_pack_size_id,
            shipment_product_batch.draft,
            shipment_product_batch.created_by,
            shipment_product_batch.created_date,
            shipment_product_batch.modified_by,
            shipment_product_batch.modified_date,
            shipment_product_batch.quantity
            FROM
            shipment_product_batch
            WHERE
            shipment_product_batch.shipment_id = $shipment_id ";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();
        return $result;
    }

}
