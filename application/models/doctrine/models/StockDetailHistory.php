<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StockDetailHistory
 */
class StockDetailHistory
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $detailId
     */
    private $detailId;

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
     * @var boolean $adjustmentType
     */
    private $adjustmentType;

    /**
     * @var integer $itemUnitId
     */
    private $itemUnitId;

    /**
     * @var boolean $actionType
     */
    private $actionType;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var StockMasterHistory
     */
    private $stockMaster;

    /**
     * @var StockBatchWarehouses
     */
    private $stockBatchWarehouse;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var VvmStages
     */
    private $vvmStage;


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
     * Set detailId
     *
     * @param integer $detailId
     */
    public function setDetailId($detailId)
    {
        $this->detailId = $detailId;
    }

    /**
     * Get detailId
     *
     * @return integer 
     */
    public function getDetailId()
    {
        return $this->detailId;
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
     * @param boolean $adjustmentType
     */
    public function setAdjustmentType($adjustmentType)
    {
        $this->adjustmentType = $adjustmentType;
    }

    /**
     * Get adjustmentType
     *
     * @return boolean 
     */
    public function getAdjustmentType()
    {
        return $this->adjustmentType;
    }

    /**
     * Set itemUnitId
     *
     * @param integer $itemUnitId
     */
    public function setItemUnitId($itemUnitId)
    {
        $this->itemUnitId = $itemUnitId;
    }

    /**
     * Get itemUnitId
     *
     * @return integer 
     */
    public function getItemUnitId()
    {
        return $this->itemUnitId;
    }

    /**
     * Set actionType
     *
     * @param boolean $actionType
     */
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;
    }

    /**
     * Get actionType
     *
     * @return boolean 
     */
    public function getActionType()
    {
        return $this->actionType;
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
     * Set stockMaster
     *
     * @param StockMasterHistory $stockMaster
     */
    public function setStockMaster(\StockMasterHistory $stockMaster)
    {
        $this->stockMaster = $stockMaster;
    }

    /**
     * Get stockMaster
     *
     * @return StockMasterHistory 
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
}