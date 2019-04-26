<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ShipmentProductBatch
 */
class ShipmentProductBatch
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $number
     */
    private $number;

    /**
     * @var date $expiryDate
     */
    private $expiryDate;

    /**
     * @var float $unitPrice
     */
    private $unitPrice;

    /**
     * @var bigint $quantity
     */
    private $quantity;

    /**
     * @var boolean $draft
     */
    private $draft;

    /**
     * @var date $productionDate
     */
    private $productionDate;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var VvmTypes
     */
    private $vvmType;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var Shipments
     */
    private $shipment;

    /**
     * @var StakeholderItemPackSizes
     */
    private $stakeholderItemPackSize;

    /**
     * @var ItemPackSizes
     */
    private $itemPackSize;


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
     * Set number
     *
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set expiryDate
     *
     * @param date $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * Get expiryDate
     *
     * @return date 
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * Get unitPrice
     *
     * @return float 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
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
     * Set draft
     *
     * @param boolean $draft
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;
    }

    /**
     * Get draft
     *
     * @return boolean 
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * Set productionDate
     *
     * @param date $productionDate
     */
    public function setProductionDate($productionDate)
    {
        $this->productionDate = $productionDate;
    }

    /**
     * Get productionDate
     *
     * @return date 
     */
    public function getProductionDate()
    {
        return $this->productionDate;
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
     * Set vvmType
     *
     * @param VvmTypes $vvmType
     */
    public function setVvmType(\VvmTypes $vvmType)
    {
        $this->vvmType = $vvmType;
    }

    /**
     * Get vvmType
     *
     * @return VvmTypes 
     */
    public function getVvmType()
    {
        return $this->vvmType;
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
     * Set stakeholderItemPackSize
     *
     * @param StakeholderItemPackSizes $stakeholderItemPackSize
     */
    public function setStakeholderItemPackSize(\StakeholderItemPackSizes $stakeholderItemPackSize)
    {
        $this->stakeholderItemPackSize = $stakeholderItemPackSize;
    }

    /**
     * Get stakeholderItemPackSize
     *
     * @return StakeholderItemPackSizes 
     */
    public function getStakeholderItemPackSize()
    {
        return $this->stakeholderItemPackSize;
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
}