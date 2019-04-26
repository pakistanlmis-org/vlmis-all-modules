<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ShipmentsReceive
 */
class ShipmentsReceive
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var decimal $quantity
     */
    private $quantity;

    /**
     * @var integer $counter
     */
    private $counter;

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
     * @var ShipmentProductBatch
     */
    private $shipmentProductBatch;

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
     * Set counter
     *
     * @param integer $counter
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
    }

    /**
     * Get counter
     *
     * @return integer 
     */
    public function getCounter()
    {
        return $this->counter;
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
     * Set shipmentProductBatch
     *
     * @param ShipmentProductBatch $shipmentProductBatch
     */
    public function setShipmentProductBatch(\ShipmentProductBatch $shipmentProductBatch)
    {
        $this->shipmentProductBatch = $shipmentProductBatch;
    }

    /**
     * Get shipmentProductBatch
     *
     * @return ShipmentProductBatch 
     */
    public function getShipmentProductBatch()
    {
        return $this->shipmentProductBatch;
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