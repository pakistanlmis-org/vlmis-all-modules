<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Shipments
 */
class Shipments
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $referenceNumber
     */
    private $referenceNumber;

    /**
     * @var string $transactionNumber
     */
    private $transactionNumber;

    /**
     * @var integer $transactionCounter
     */
    private $transactionCounter;

    /**
     * @var date $shipmentDate
     */
    private $shipmentDate;

    /**
     * @var date $eta
     */
    private $eta;

    /**
     * @var boolean $draft
     */
    private $draft;

    /**
     * @var decimal $shipmentQuantity
     */
    private $shipmentQuantity;

    /**
     * @var date $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Warehouses
     */
    private $fundingSource;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var ItemPackSizes
     */
    private $itemPackSize;

    /**
     * @var StakeholderActivities
     */
    private $stakeholderActivity;

    /**
     * @var Warehouses
     */
    private $warehouse;

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
     * Set referenceNumber
     *
     * @param string $referenceNumber
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
    }

    /**
     * Get referenceNumber
     *
     * @return string 
     */
    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    /**
     * Set transactionNumber
     *
     * @param string $transactionNumber
     */
    public function setTransactionNumber($transactionNumber)
    {
        $this->transactionNumber = $transactionNumber;
    }

    /**
     * Get transactionNumber
     *
     * @return string 
     */
    public function getTransactionNumber()
    {
        return $this->transactionNumber;
    }

    /**
     * Set transactionCounter
     *
     * @param integer $transactionCounter
     */
    public function setTransactionCounter($transactionCounter)
    {
        $this->transactionCounter = $transactionCounter;
    }

    /**
     * Get transactionCounter
     *
     * @return integer 
     */
    public function getTransactionCounter()
    {
        return $this->transactionCounter;
    }

    /**
     * Set shipmentDate
     *
     * @param date $shipmentDate
     */
    public function setShipmentDate($shipmentDate)
    {
        $this->shipmentDate = $shipmentDate;
    }

    /**
     * Get shipmentDate
     *
     * @return date 
     */
    public function getShipmentDate()
    {
        return $this->shipmentDate;
    }

    /**
     * Set eta
     *
     * @param date $eta
     */
    public function setEta($eta)
    {
        $this->eta = $eta;
    }

    /**
     * Get eta
     *
     * @return date 
     */
    public function getEta()
    {
        return $this->eta;
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
     * Set shipmentQuantity
     *
     * @param decimal $shipmentQuantity
     */
    public function setShipmentQuantity($shipmentQuantity)
    {
        $this->shipmentQuantity = $shipmentQuantity;
    }

    /**
     * Get shipmentQuantity
     *
     * @return decimal 
     */
    public function getShipmentQuantity()
    {
        return $this->shipmentQuantity;
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
     * Set fundingSource
     *
     * @param Warehouses $fundingSource
     */
    public function setFundingSource(\Warehouses $fundingSource)
    {
        $this->fundingSource = $fundingSource;
    }

    /**
     * Get fundingSource
     *
     * @return Warehouses 
     */
    public function getFundingSource()
    {
        return $this->fundingSource;
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
     * Set stakeholderActivity
     *
     * @param StakeholderActivities $stakeholderActivity
     */
    public function setStakeholderActivity(\StakeholderActivities $stakeholderActivity)
    {
        $this->stakeholderActivity = $stakeholderActivity;
    }

    /**
     * Get stakeholderActivity
     *
     * @return StakeholderActivities 
     */
    public function getStakeholderActivity()
    {
        return $this->stakeholderActivity;
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