<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * WarehouseTypes
 */
class WarehouseTypes
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $warehouseTypeName
     */
    private $warehouseTypeName;

    /**
     * @var integer $resupplyInterval
     */
    private $resupplyInterval;

    /**
     * @var integer $reservedStock
     */
    private $reservedStock;

    /**
     * @var decimal $usagePercentage
     */
    private $usagePercentage;

    /**
     * @var integer $geoLevelId
     */
    private $geoLevelId;

    /**
     * @var integer $listRank
     */
    private $listRank;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var WarehouseTypeCategories
     */
    private $warehouseTypeCategory;

    /**
     * @var Users
     */
    private $createdBy;


    /**
     * Get pkId
     *
     * @return integer 
     */
    public function getPkId()
    {
        return $this->pkId;
    }

    /**
     * Set warehouseTypeName
     *
     * @param string $warehouseTypeName
     */
    public function setWarehouseTypeName($warehouseTypeName)
    {
        $this->warehouseTypeName = $warehouseTypeName;
    }

    /**
     * Get warehouseTypeName
     *
     * @return string 
     */
    public function getWarehouseTypeName()
    {
        return $this->warehouseTypeName;
    }

    /**
     * Set resupplyInterval
     *
     * @param integer $resupplyInterval
     */
    public function setResupplyInterval($resupplyInterval)
    {
        $this->resupplyInterval = $resupplyInterval;
    }

    /**
     * Get resupplyInterval
     *
     * @return integer 
     */
    public function getResupplyInterval()
    {
        return $this->resupplyInterval;
    }

    /**
     * Set reservedStock
     *
     * @param integer $reservedStock
     */
    public function setReservedStock($reservedStock)
    {
        $this->reservedStock = $reservedStock;
    }

    /**
     * Get reservedStock
     *
     * @return integer 
     */
    public function getReservedStock()
    {
        return $this->reservedStock;
    }

    /**
     * Set usagePercentage
     *
     * @param decimal $usagePercentage
     */
    public function setUsagePercentage($usagePercentage)
    {
        $this->usagePercentage = $usagePercentage;
    }

    /**
     * Get usagePercentage
     *
     * @return decimal 
     */
    public function getUsagePercentage()
    {
        return $this->usagePercentage;
    }

    /**
     * Set geoLevelId
     *
     * @param integer $geoLevelId
     */
    public function setGeoLevelId($geoLevelId)
    {
        $this->geoLevelId = $geoLevelId;
    }

    /**
     * Get geoLevelId
     *
     * @return integer 
     */
    public function getGeoLevelId()
    {
        return $this->geoLevelId;
    }

    /**
     * Set listRank
     *
     * @param integer $listRank
     */
    public function setListRank($listRank)
    {
        $this->listRank = $listRank;
    }

    /**
     * Get listRank
     *
     * @return integer 
     */
    public function getListRank()
    {
        return $this->listRank;
    }

    /**
     * Set createdDate
     *
     * @param datetime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return datetime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set modifiedDate
     *
     * @param datetime $modifiedDate
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
    }

    /**
     * Get modifiedDate
     *
     * @return datetime 
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * Set modifiedBy
     *
     * @param Users $modifiedBy
     */
    public function setModifiedBy(\Users $modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Get modifiedBy
     *
     * @return Users 
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Set warehouseTypeCategory
     *
     * @param WarehouseTypeCategories $warehouseTypeCategory
     */
    public function setWarehouseTypeCategory(\WarehouseTypeCategories $warehouseTypeCategory)
    {
        $this->warehouseTypeCategory = $warehouseTypeCategory;
    }

    /**
     * Get warehouseTypeCategory
     *
     * @return WarehouseTypeCategories 
     */
    public function getWarehouseTypeCategory()
    {
        return $this->warehouseTypeCategory;
    }

    /**
     * Set createdBy
     *
     * @param Users $createdBy
     */
    public function setCreatedBy(\Users $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get createdBy
     *
     * @return Users 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}