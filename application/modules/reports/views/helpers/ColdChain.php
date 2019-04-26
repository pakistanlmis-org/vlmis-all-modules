<?php

/**
 * Zend_View_Helper_ColdChain
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend ViewHelper Cold Chain
 */

class Zend_View_Helper_ColdChain extends Zend_View_Helper_Abstract {
    
    protected $_em_read;
    
    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Cold Chain
     * @return \Zend_View_Helper_ColdChain
     */
    public function coldChain() {
        return $this;
    }

    /**
     * Get Voltage Regulator Detail By Id
     * @param type $coldchain_id
     * @return boolean
     */
    public function getVoltageRegulatorDetailById($coldchain_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,"
                        . "ccmake.ccmMakeName,cc.createdDate,"
                        . "d.locationName as district, w.warehouseName as facility")
                ->from('ColdChain', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('cc.warehouse', 'w')
                ->leftJoin('w.district', 'd')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->where("cc.pkId = " . $coldchain_id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Generator Detail By Id
     * @param type $id
     * @return boolean
     */
    public function getGeneratorDetailById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,cc.serialNumber,"
                        . "ccmake.ccmMakeName,cc.createdDate,cc.workingSince,"
                        . "d.locationName as district, w.warehouseName as facility,"
                        . "stkholder.stakeholderName")
                ->from('ColdChain', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('cc.warehouse', 'w')
                ->leftJoin('w.district', 'd')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->leftJoin('cc.source', 'stkholder')
                ->where("cc.pkId = " . $id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Vaccine Carrier Detail By Id
     * @param type $id
     * @return boolean
     */
    public function getVaccineCarrierDetailById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,"
                        . "ccm.assetDimensionLength,ccm.assetDimensionWidth,"
                        . "ccm.assetDimensionHeight,ccm.catalogueId,"
                        . "ccmake.ccmMakeName,cc.createdDate,"
                        . "d.locationName as district, w.warehouseName as facility")
                ->from('ColdChain', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('cc.warehouse', 'w')
                ->leftJoin('w.district', 'd')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->where("cc.pkId = " . $id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Ice Pack Detail By Id
     * @param type $id
     * @return boolean
     */
    public function getIcePackDetailById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,"
                        . "ccmake.ccmMakeName,cc.createdDate,"
                        . "d.locationName as district, w.warehouseName as facility")
                ->from('ColdChain', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('cc.warehouse', 'w')
                ->leftJoin('w.district', 'd')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->where("cc.pkId = " . $id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Cold Room Detail By Id
     * @param type $id
     * @return boolean
     */
    public function getColdRoomDetailById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,ccr.coolingSystem,ccr.hasVoltage,trs.listValue as temperatureRecordingSystem,trsystem.listValue as typeRecordingSystem,"
                        . "rgt.listValue as refrigeratorGasType,bg.listValue as backupGenerator,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,ccm.assetDimensionLength,ccm.assetDimensionWidth,ccm.assetDimensionHeight,"
                        . "ccmake.ccmMakeName,cc.createdDate,"
                        . "d.locationName as district, w.warehouseName as facility")
                ->from('CcmColdRooms', 'ccr')
                ->leftJoin('ccr.temperatureRecordingSystem', 'trs')
                ->leftJoin('ccr.typeRecordingSystem', 'trsystem')
                ->leftJoin('ccr.refrigeratorGasType', 'rgt')
                ->leftJoin('ccr.backupGenerator', 'bg')
                ->leftJoin('ccr.ccm', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('cc.warehouse', 'w')
                ->leftJoin('w.district', 'd')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->where("cc.pkId = " . $id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Transport Detail By Id
     * @param type $id
     * @return boolean
     */
    public function getTransportDetailById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,"
                        . "ccmake.ccmMakeName,cc.createdDate,"
                        . "d.locationName as district, w.warehouseName as facility,"
                        . "cv.registrationNo, cat.assetTypeName")
                ->from('CcmVehicles', 'cv')
                ->innerJoin('cv.ccm', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('cc.warehouse', 'w')
                ->leftJoin('w.district', 'd')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->where("cc.pkId = " . $id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Refrigerator Detail By Id
     * @param type $id
     * @return boolean
     */
    public function getRefrigeratorDetailById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity, cat.assetTypeName,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,cc.serialNumber,"
                        . "ccmake.ccmMakeName,cc.createdDate,cc.workingSince,"
                        . "d.locationName as district, w.warehouseName as facility,"
                        . "stkholder.stakeholderName,ccm.assetDimensionLength,"
                        . "ccm.assetDimensionWidth, ccm.assetDimensionHeight,"
                        . "ccm.grossCapacity20, ccm.grossCapacity4,"
                        . "ccm.netCapacity20, ccm.netCapacity4,ccm.cfcFree, ccm.catalogueId")
                ->from('ColdChain', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('cc.warehouse', 'w')
                ->leftJoin('w.district', 'd')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl')
                ->leftJoin('cc.source', 'stkholder')
                ->where("cc.pkId = " . $id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}

?>