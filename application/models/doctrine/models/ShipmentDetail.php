<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ShipmentDetail
 */
class ShipmentDetail
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var date $receivedDate
     */
    private $receivedDate;

    /**
     * @var decimal $receivedQuantity
     */
    private $receivedQuantity;

    /**
     * @var date $createdDate
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
     * @var Shipments
     */
    private $shipment;

    /**
     * @var Warehouses
     */
    private $warehouse;

    /**
     * @var ItemPackSizes
     */
    private $itemPackSize;

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
     * Set receivedDate
     *
     * @param date $receivedDate
     */
    public function setReceivedDate($receivedDate)
    {
        $this->receivedDate = $receivedDate;
    }

    /**
     * Get receivedDate
     *
     * @return date 
     */
    public function getReceivedDate()
    {
        return $this->receivedDate;
    }

    /**
     * Set receivedQuantity
     *
     * @param decimal $receivedQuantity
     */
    public function setReceivedQuantity($receivedQuantity)
    {
        $this->receivedQuantity = $receivedQuantity;
    }

    /**
     * Get receivedQuantity
     *
     * @return decimal 
     */
    public function getReceivedQuantity()
    {
        return $this->receivedQuantity;
    }

    /**
     * Set createdDate
     *
     * @param date $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return date 
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
     * Set shipment
     *
     * @param Shipments $shipment
     */
    public function setShipment(\Shipments $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Get shipment
     *
     * @return Shipments 
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * Set warehouse
     *
     * @param Warehouses $warehouse
     */
    public function setWarehouse(\Warehouses $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    /**
     * Get warehouse
     *
     * @return Warehouses 
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * Set itemPackSize
     *
     * @param ItemPackSizes $itemPackSize
     */
    public function setItemPackSize(\ItemPackSizes $itemPackSize)
    {
        $this->itemPackSize = $itemPackSize;
    }

    /**
     * Get itemPackSize
     *
     * @return ItemPackSizes 
     */
    public function getItemPackSize()
    {
        return $this->itemPackSize;
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