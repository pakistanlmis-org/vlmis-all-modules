<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StockBatchVvm
 */
class StockBatchVvm
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $stockBatchWarehouseId
     */
    private $stockBatchWarehouseId;

    /**
     * @var integer $vvmStage
     */
    private $vvmStage;

    /**
     * @var decimal $quantity
     */
    private $quantity;

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
     * Set stockBatchWarehouseId
     *
     * @param integer $stockBatchWarehouseId
     */
    public function setStockBatchWarehouseId($stockBatchWarehouseId)
    {
        $this->stockBatchWarehouseId = $stockBatchWarehouseId;
    }

    /**
     * Get stockBatchWarehouseId
     *
     * @return integer 
     */
    public function getStockBatchWarehouseId()
    {
        return $this->stockBatchWarehouseId;
    }

    /**
     * Set vvmStage
     *
     * @param integer $vvmStage
     */
    public function setVvmStage($vvmStage)
    {
        $this->vvmStage = $vvmStage;
    }

    /**
     * Get vvmStage
     *
     * @return integer 
     */
    public function getVvmStage()
    {
        return $this->vvmStage;
    }

    /**
     * Set quantity
     *
     * @param decimal $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return decimal 
     */
    public function getQuantity()
    {
        return $this->quantity;
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