<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * PlacementSummary
 */
class PlacementSummary
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $itemName
     */
    private $itemName;

    /**
     * @var string $batchNumber
     */
    private $batchNumber;

    /**
     * @var decimal $quantity
     */
    private $quantity;

    /**
     * @var integer $cartons
     */
    private $cartons;

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
     * @var StockBatchWarehouses
     */
    private $stockBatchWarehouse;

    /**
     * @var PlacementLocations
     */
    private $placementLocation;

    /**
     * @var VvmStages
     */
    private $vvmStage;

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
     * Set itemName
     *
     * @param string $itemName
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;
    }

    /**
     * Get itemName
     *
     * @return string 
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * Set batchNumber
     *
     * @param string $batchNumber
     */
    public function setBatchNumber($batchNumber)
    {
        $this->batchNumber = $batchNumber;
    }

    /**
     * Get batchNumber
     *
     * @return string 
     */
    public function getBatchNumber()
    {
        return $this->batchNumber;
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
     * Set cartons
     *
     * @param integer $cartons
     */
    public function setCartons($cartons)
    {
        $this->cartons = $cartons;
    }

    /**
     * Get cartons
     *
     * @return integer 
     */
    public function getCartons()
    {
        return $this->cartons;
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
     * Set placementLocation
     *
     * @param PlacementLocations $placementLocation
     */
    public function setPlacementLocation(\PlacementLocations $placementLocation)
    {
        $this->placementLocation = $placementLocation;
    }

    /**
     * Get placementLocation
     *
     * @return PlacementLocations 
     */
    public function getPlacementLocation()
    {
        return $this->placementLocation;
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