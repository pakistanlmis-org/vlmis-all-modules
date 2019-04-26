<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StockDetail
 */
class StockDetail
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var bigint $quantity
     */
    private $quantity;

    /**
     * @var boolean $temporary
     */
    private $temporary;

    /**
     * @var integer $isReceived
     */
    private $isReceived;

    /**
     * @var integer $adjustmentType
     */
    private $adjustmentType;

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
     * @var VvmStages
     */
    private $vvmStage;

    /**
     * @var StockMaster
     */
    private $stockMaster;

    /**
     * @var StockBatchWarehouses
     */
    private $stockBatchWarehouse;

    /**
     * @var ItemUnits
     */
    private $itemUnit;

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
     * Set quantity
     *
     * @param bigint $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return bigint 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set temporary
     *
     * @param boolean $temporary
     */
    public function setTemporary($temporary)
    {
        $this->temporary = $temporary;
    }

    /**
     * Get temporary
     *
     * @return boolean 
     */
    public function getTemporary()
    {
        return $this->temporary;
    }

    /**
     * Set isReceived
     *
     * @param integer $isReceived
     */
    public function setIsReceived($isReceived)
    {
        $this->isReceived = $isReceived;
    }

    /**
     * Get isReceived
     *
     * @return integer 
     */
    public function getIsReceived()
    {
        return $this->isReceived;
    }

    /**
     * Set adjustmentType
     *
     * @param integer $adjustmentType
     */
    public function setAdjustmentType($adjustmentType)
    {
        $this->adjustmentType = $adjustmentType;
    }

    /**
     * Get adjustmentType
     *
     * @return integer 
     */
    public function getAdjustmentType()
    {
        return $this->adjustmentType;
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
     * Set vvmStage
     *
     * @param VvmStages $vvmStage
     */
    public function setVvmStage(\VvmStages $vvmStage)
    {
        $this->vvmStage = $vvmStage;
    }

    /**
     * Get vvmStage
     *
     * @return VvmStages 
     */
    public function getVvmStage()
    {
        return $this->vvmStage;
    }

    /**
     * Set stockMaster
     *
     * @param StockMaster $stockMaster
     */
    public function setStockMaster(\StockMaster $stockMaster)
    {
        $this->stockMaster = $stockMaster;
    }

    /**
     * Get stockMaster
     *
     * @return StockMaster 
     */
    public function getStockMaster()
    {
        return $this->stockMaster;
    }

    /**
     * Set stockBatchWarehouse
     *
     * @param StockBatchWarehouses $stockBatchWarehouse
     */
    public function setStockBatchWarehouse(\StockBatchWarehouses $stockBatchWarehouse)
    {
        $this->stockBatchWarehouse = $stockBatchWarehouse;
    }

    /**
     * Get stockBatchWarehouse
     *
     * @return StockBatchWarehouses 
     */
    public function getStockBatchWarehouse()
    {
        return $this->stockBatchWarehouse;
    }

    /**
     * Set itemUnit
     *
     * @param ItemUnits $itemUnit
     */
    public function setItemUnit(\ItemUnits $itemUnit)
    {
        $this->itemUnit = $itemUnit;
    }

    /**
     * Get itemUnit
     *
     * @return ItemUnits 
     */
    public function getItemUnit()
    {
        return $this->itemUnit;
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