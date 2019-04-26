<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ColdChain
 */
class ColdChain
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $assetId
     */
    private $assetId;

    /**
     * @var integer $autoAssetId
     */
    private $autoAssetId;

    /**
     * @var string $serialNumber
     */
    private $serialNumber;

    /**
     * @var smallint $estimateLife
     */
    private $estimateLife;

    /**
     * @var datetime $workingSince
     */
    private $workingSince;

    /**
     * @var integer $quantity
     */
    private $quantity;

    /**
     * @var datetime $manufactureYear
     */
    private $manufactureYear;

    /**
     * @var boolean $status
     */
    private $status;

    /**
     * @var integer $approvedBy
     */
    private $approvedBy;

    /**
     * @var datetime $approvedOn
     */
    private $approvedOn;

    /**
     * @var boolean $hasVoltage
     */
    private $hasVoltage;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var ListDetail
     */
    private $temperatureMonitor;

    /**
     * @var CcmStatusHistory
     */
    private $ccmStatusHistory;

    /**
     * @var CcmAssetTypes
     */
    private $ccmAssetType;

    /**
     * @var CcmModels
     */
    private $ccmModel;

    /**
     * @var Stakeholders
     */
    private $source;

    /**
     * @var Warehouses
     */
    private $warehouse;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;


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
     * Set assetId
     *
     * @param string $assetId
     */
    public function setAssetId($assetId)
    {
        $this->assetId = $assetId;
    }

    /**
     * Get assetId
     *
     * @return string 
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * Set autoAssetId
     *
     * @param integer $autoAssetId
     */
    public function setAutoAssetId($autoAssetId)
    {
        $this->autoAssetId = $autoAssetId;
    }

    /**
     * Get autoAssetId
     *
     * @return integer 
     */
    public function getAutoAssetId()
    {
        return $this->autoAssetId;
    }

    /**
     * Set serialNumber
     *
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * Get serialNumber
     *
     * @return string 
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * Set estimateLife
     *
     * @param smallint $estimateLife
     */
    public function setEstimateLife($estimateLife)
    {
        $this->estimateLife = $estimateLife;
    }

    /**
     * Get estimateLife
     *
     * @return smallint 
     */
    public function getEstimateLife()
    {
        return $this->estimateLife;
    }

    /**
     * Set workingSince
     *
     * @param datetime $workingSince
     */
    public function setWorkingSince($workingSince)
    {
        $this->workingSince = $workingSince;
    }

    /**
     * Get workingSince
     *
     * @return datetime 
     */
    public function getWorkingSince()
    {
        return $this->workingSince;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set manufactureYear
     *
     * @param datetime $manufactureYear
     */
    public function setManufactureYear($manufactureYear)
    {
        $this->manufactureYear = $manufactureYear;
    }

    /**
     * Get manufactureYear
     *
     * @return datetime 
     */
    public function getManufactureYear()
    {
        return $this->manufactureYear;
    }

    /**
     * Set status
     *
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set approvedBy
     *
     * @param integer $approvedBy
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
    }

    /**
     * Get approvedBy
     *
     * @return integer 
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * Set approvedOn
     *
     * @param datetime $approvedOn
     */
    public function setApprovedOn($approvedOn)
    {
        $this->approvedOn = $approvedOn;
    }

    /**
     * Get approvedOn
     *
     * @return datetime 
     */
    public function getApprovedOn()
    {
        return $this->approvedOn;
    }

    /**
     * Set hasVoltage
     *
     * @param boolean $hasVoltage
     */
    public function setHasVoltage($hasVoltage)
    {
        $this->hasVoltage = $hasVoltage;
    }

    /**
     * Get hasVoltage
     *
     * @return boolean 
     */
    public function getHasVoltage()
    {
        return $this->hasVoltage;
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
     * Set temperatureMonitor
     *
     * @param ListDetail $temperatureMonitor
     */
    public function setTemperatureMonitor(\ListDetail $temperatureMonitor)
    {
        $this->temperatureMonitor = $temperatureMonitor;
    }

    /**
     * Get temperatureMonitor
     *
     * @return ListDetail 
     */
    public function getTemperatureMonitor()
    {
        return $this->temperatureMonitor;
    }

    /**
     * Set ccmStatusHistory
     *
     * @param CcmStatusHistory $ccmStatusHistory
     */
    public function setCcmStatusHistory(\CcmStatusHistory $ccmStatusHistory)
    {
        $this->ccmStatusHistory = $ccmStatusHistory;
    }

    /**
     * Get ccmStatusHistory
     *
     * @return CcmStatusHistory 
     */
    public function getCcmStatusHistory()
    {
        return $this->ccmStatusHistory;
    }

    /**
     * Set ccmAssetType
     *
     * @param CcmAssetTypes $ccmAssetType
     */
    public function setCcmAssetType(\CcmAssetTypes $ccmAssetType)
    {
        $this->ccmAssetType = $ccmAssetType;
    }

    /**
     * Get ccmAssetType
     *
     * @return CcmAssetTypes 
     */
    public function getCcmAssetType()
    {
        return $this->ccmAssetType;
    }

    /**
     * Set ccmModel
     *
     * @param CcmModels $ccmModel
     */
    public function setCcmModel(\CcmModels $ccmModel)
    {
        $this->ccmModel = $ccmModel;
    }

    /**
     * Get ccmModel
     *
     * @return CcmModels 
     */
    public function getCcmModel()
    {
        return $this->ccmModel;
    }

    /**
     * Set source
     *
     * @param Stakeholders $source
     */
    public function setSource(\Stakeholders $source)
    {
        $this->source = $source;
    }

    /**
     * Get source
     *
     * @return Stakeholders 
     */
    public function getSource()
    {
        return $this->source;
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
}