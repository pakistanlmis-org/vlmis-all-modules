<?php

/**
 * Model_ItemPackSizes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stakeholder Item Pack Sizes
 */

class Model_StakeholderItemPackSizes extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    const GTIN = 1;
    const BATCH = 1;
    const EXPIRY = 1;
    const GTIN_START = 3;
    const GTIN_END = 16;
    const BATCH_START = 28;
    const BATCH_END = 36;
    const EXPIRY_START = 19;
    const EXPIRY_END = 25;
    const NON_GSI = 48;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('StakeholderItemPackSizes');
    }

    /**
     * Setup Barcode
     * 
     */
    public function setupBarcode() {
        $form_values = $this->form_values;
        $stakeholder_item_pack = new StakeholderItemPackSizes();


        if (!empty($form_values['item_pack_size_id'])) {
            $item_pack = $this->_em->find("ItemPackSizes", $form_values['item_pack_size_id']);
            $stakeholder_item_pack->setItemPackSize($item_pack);
        }

        if (!empty($form_values['stakeholder_id'])) {
            $stakeholder = $this->_em->find("Stakeholders", $form_values['stakeholder_id']);
            $stakeholder_item_pack->setStakeholder($stakeholder);
        }

        if (!empty($form_values['packaging_level'])) {
            $packaging_level = $this->_em->find("ListDetail", $form_values['packaging_level']);
            $stakeholder_item_pack->setPackagingLevel($packaging_level);
        }



        $stakeholder_item_pack->setItemGtin($form_values['item_gtin']);
        $stakeholder_item_pack->setPackSizeDescription($form_values['pack_size_description']);
        $stakeholder_item_pack->setLength($form_values['length']);
        $stakeholder_item_pack->setWidth($form_values['width']);
        $stakeholder_item_pack->setHeight($form_values['height']);

        $stakeholder_item_pack->setQuantityPerPack($form_values['quantity_per_pack']);
        $stakeholder_item_pack->setVolumPerVial($form_values['volume_per_unit_net']);
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $stakeholder_item_pack->setCreatedBy($user_id);
        $stakeholder_item_pack->setCreatedDate(App_Tools_Time::now());
        $stakeholder_item_pack->setModifiedBy($user_id);
        $stakeholder_item_pack->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stakeholder_item_pack);
        $this->_em->flush();
    }

    /**
     * Update Barcode Save
     * 
     */
    public function updateBarcodeSave() {
        $form_values = $this->form_values;

        $stakeholder_item_pack = $this->_em->find("StakeholderItemPackSizes", $form_values['barcode_id']);

        if (!empty($form_values['item_pack_size_id_update'])) {
            $item_pack = $this->_em->find("ItemPackSizes", $form_values['item_pack_size_id_update']);
            $stakeholder_item_pack->setItemPackSize($item_pack);
        }

        if (!empty($form_values['stakeholder_id_update'])) {
            $stakeholder = $this->_em->find("Stakeholders", $form_values['stakeholder_id_update']);
            $stakeholder_item_pack->setStakeholder($stakeholder);
        }

        if (!empty($form_values['packaging_level_update'])) {
            $packaging_level = $this->_em->find("ListDetail", $form_values['packaging_level_update']);
            $stakeholder_item_pack->setPackagingLevel($packaging_level);
        }



        $stakeholder_item_pack->setItemGtin($form_values['item_gtin']);


        $stakeholder_item_pack->setPackSizeDescription($form_values['pack_size_description']);
        $stakeholder_item_pack->setLength($form_values['length']);
        $stakeholder_item_pack->setWidth($form_values['width']);
        $stakeholder_item_pack->setHeight($form_values['height']);

        $stakeholder_item_pack->setQuantityPerPack($form_values['quantity_per_pack']);
        $stakeholder_item_pack->setVolumPerVial($form_values['volume_per_unit_net']);
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $stakeholder_item_pack->setModifiedBy($user_id);
        $stakeholder_item_pack->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stakeholder_item_pack);
        $this->_em->flush();
    }

    /**
     * Get Stakeholder Item Pack Sizes
     * 
     * @return type
     */
    public function getStakeholderItemPackSizes() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('si.pkId,pi.quantityPerPack,pi.itemGtin,ip.itemName,s.stakeholderName,ld.listValue,pi.volumPerVial')
                ->from("PackInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "si")
                ->join('si.itemPackSize', 'ip')
                ->join('si.stakeholder', 's')
                ->join('pi.packagingLevel', 'ld')
                ->where("s.stakeholderType = 3")
                ->orderBy("s.stakeholderName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Stakeholder Item Pack Sizes By Item
     * 
     * @return boolean
     */
    public function getStakeholderItemPackSizesByItem() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sips')
                ->from("StakeholderItemPackSizes", "sips")
                ->join('sips.stakeholder', 's')
                ->where("s.stakeholderType = 3")
                ->andWhere("sips.itemPackSize = " . $this->form_values['item_id']);
        $result = $str_sql->getQuery()->getResult();

        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Stakeholder Item Pack Sizes All
     * 
     * @param type $barcode_id
     * @return type
     */
    public function getStakeholderItemPackSizesAll($barcode_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('si.pkId,si.itemGtin,si.packSizeDescription,si.length,si.width,si.height,si.quantityPerPack,'
                        . 'ip.itemName,s.stakeholderName,ld.listValue as barcodeType')
                ->from("StakeholderItemPackSizes", "si")
                ->join('si.itemPackSize', 'ip')
                ->join('si.stakeholder', 's')
                ->join('si.barcodeType', 'ld')
                ->join('si.expiryDateFormat', 'ld1')
                ->where('si.pkId =' . $barcode_id);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Update Barcode
     * 
     * @return type
     */
    public function updateBarcode() {
        $form_values = $this->form_values;

        $stakeholder_item_pack = $this->_table->find($form_values['barcode_id']);
        $barcode_type = $this->_em->find("ListDetail", $form_values['barcode_type']);
        $stakeholder_item_pack->setBarcodeType($barcode_type);
        $stakeholder_id = $this->_em->find("Stakeholders", $form_values['stakeholder_id']);
        $stakeholder_item_pack->setStakeholder($stakeholder_id);
        $item_pack_id = $this->_em->find("ItemPackSizes", $form_values['item_pack_size_id']);
        $stakeholder_item_pack->setItemPackSize($item_pack_id);

        $stakeholder_item_pack->setItemGtin($form_values['item_gtin']);

        $stakeholder_item_pack->setPackSizeDescription($form_values['pack_size_description']);
        $stakeholder_item_pack->setLength($form_values['length']);
        $stakeholder_item_pack->setWidth($form_values['width']);
        $stakeholder_item_pack->setHeight($form_values['height']);
        $stakeholder_item_pack->setQuantityPerPack($form_values['quantity_per_pack']);

        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stakeholder_item_pack->setModifiedBy($created_by);
        $stakeholder_item_pack->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stakeholder_item_pack);
        return $this->_em->flush();
    }

    /**
     * Get All Products By Stakeholder Type
     * 
     * @return type
     */
    public function getAllProductsByStakeholderType() {
        $role_id = $this->_identity->getRoleId();
        $str_sql = $this->_em->createQueryBuilder()
                ->select('DISTINCT ips.itemName as item_name, ips.pkId as item_pack_size_id')
                ->from("ItemActivities", "ia")
                ->join("ia.itemPackSize", "ips")
                ->where("ia.stakeholderActivity IN (" . $this->form_values['stakeholder_id'] . "," . parent::MERGEDPRODUCTS . ")")
                ->andWhere("ips.itemCategory <> 4")
                ->andWhere("ips.pkId NOT IN (25,26,31,42)")
                ->orderBy("ips.listRank", 'ASC');
         
          if ($role_id == 53){
            $str_sql->andWhere("ips.itemCategory != 1");  
          }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Products By Stakeholder Type Vaccines
     * 
     * @return type
     */
    public function getAllProductsByStakeholderTypeVaccines() {

        if (empty($this->form_values['stakeholder_id'])) {
            $this->form_values['stakeholder_id'] = '1';
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT ips.itemName as item_name,ips.description, ips.pkId as item_pack_size_id')
                ->from("ItemSchedule", 'sips')
                ->join("sips.itemPackSize", "ips")
                ->join("ips.itemCategory", "ic")
                ->where("sips.stakeholderActivity = '" . $this->form_values['stakeholder_id'] . "' ")
                ->andWhere("ic.pkId=1")
                ->orderBy("sips.pkId", 'ASC');

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Issue Products By Stakeholder
     * 
     * @return boolean
     */
    public function getAllIssueProductsByStakeholder() {
        $tran_date = $this->form_values['trans_date'];
        $role_id = $this->_identity->getRoleId();
        if (empty($tran_date)) {
            $tran_date = date("d/m/Y h:i:s A");
        }
        list($date, $time) = explode(" ",$tran_date);
        list($dd, $mm, $yy) = explode("/",$date);
        $tran_date = $yy."-".$mm."-".$dd;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT ips.pkId')
                ->from('StockBatchWarehouses', 'sbw')
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->where("sbw.warehouse = " . $this->_identity->getWarehouseId())
                ->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') > '" . $tran_date . "'")
                ->andWhere("sbw.quantity > 0")
                ->andWhere("ips.pkId <> 26")
                ->orderBy("ips.listRank", "ASC");
        $rows = $str_sql->getQuery()->getResult();
        if (!empty($rows) && count($rows) > 0) {
            // Inactive Vaccines
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('DISTINCT ips.itemName as item_name, ips.pkId as item_pack_size_id')
                    ->from("ItemActivities", "ia")
                    ->join("ia.itemPackSize", "ips")
                    ->where("ips.itemCategory !=4")
                    ->andWhere("ia.stakeholderActivity = '" . $this->form_values['stakeholder_id'] . "'")
                    ->andWhere("ips.pkId <> 26")
                    ->orderBy("ips.listRank", 'ASC');
       
          if ($role_id == 53){
            $str_sql->andWhere("ips.itemCategory != 1");  
          }
            return  $str_sql->getQuery()->getResult();
        } else {
            return false;
        }
    }

    /**
     * Check Setup Barcode Combination
     * 
     * @return type
     */
    public function checkSetupBarcodeCombination() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sip.pkId")
                ->from('StakeholderItemPackSizes', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join('sip.stakeholder', 's')
                ->join('sip.packagingLevel', 'pl')
                ->where("ips.pkId= '" . $form_values['item_pack_size_id'] . "' ")
                ->Andwhere("s.pkId= '" . $form_values['stakeholder_id'] . "' ")
                ->Andwhere("pl.pkId= '" . $form_values['packaging_level'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Setup Barcode Combination Update
     * 
     * @return type
     */
    public function checkSetupBarcodeCombinationUpdate() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sip.pkId")
                ->from('StakeholderItemPackSizes', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join('sip.stakeholder', 's')
                ->join('sip.packagingLevel', 'pl')
                ->where("ips.pkId= '" . $form_values['item_pack_size_id'] . "' ")
                ->Andwhere("s.pkId= '" . $form_values['stakeholder_id'] . "' ")
                ->Andwhere("pl.pkId= '" . $form_values['packaging_level'] . "' ")
                ->Andwhere("sip.pkId <> '" . $form_values['barcode_type'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Product By Item Purpose
     * 
     * @return type
     */
    public function getProductByItemPurpose($item_category_id) {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT ips.itemName, ips.pkId")
                ->from('ItemActivities', 'ia')
                ->join('ia.itemPackSize', 'ips')
                ->where("ips.item = '" . $form_values['item_id'] . "'")
                ->andWhere("ips.itemCategory = $item_category_id")
                ->andWhere("ia.stakeholderActivity = '" . $form_values['purpose'] . "' ");      
       
        return $str_sql->getQuery()->getResult();
    }

}
