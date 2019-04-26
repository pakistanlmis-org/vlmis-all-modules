<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmModels
 */
class CcmModels
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $ccmModelName
     */
    private $ccmModelName;

    /**
     * @var integer $assetDimensionLength
     */
    private $assetDimensionLength;

    /**
     * @var integer $assetDimensionWidth
     */
    private $assetDimensionWidth;

    /**
     * @var integer $assetDimensionHeight
     */
    private $assetDimensionHeight;

    /**
     * @var float $grossCapacity20
     */
    private $grossCapacity20;

    /**
     * @var float $grossCapacity4
     */
    private $grossCapacity4;

    /**
     * @var float $netCapacity20
     */
    private $netCapacity20;

    /**
     * @var float $netCapacity4
     */
    private $netCapacity4;

    /**
     * @var boolean $cfcFree
     */
    private $cfcFree;

    /**
     * @var boolean $noOfPhases
     */
    private $noOfPhases;

    /**
     * @var boolean $status
     */
    private $status;

    /**
     * @var string $reasons
     */
    private $reasons;

    /**
     * @var string $utilizations
     */
    private $utilizations;

    /**
     * @var boolean $temperatureType
     */
    private $temperatureType;

    /**
     * @var string $catalogueId
     */
    private $catalogueId;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var string $coldLife
     */
    private $coldLife;

    /**
     * @var integer $productPrice
     */
    private $productPrice;

    /**
     * @var integer $internalDimensionLength
     */
    private $internalDimensionLength;

    /**
     * @var integer $internalDimensionWidth
     */
    private $internalDimensionWidth;

    /**
     * @var integer $internalDimensionHeight
     */
    private $internalDimensionHeight;

    /**
     * @var integer $storageDimensionLength
     */
    private $storageDimensionLength;

    /**
     * @var integer $storageDimensionWidth
     */
    private $storageDimensionWidth;

    /**
     * @var integer $storageDimensionHeight
     */
    private $storageDimensionHeight;

    /**
     * @var boolean $isPqs
     */
    private $isPqs;

    /**
     * @var ListDetail
     */
    private $gasType;

    /**
     * @var ListDetail
     */
    private $powerSource;

    /**
     * @var CcmAssetTypes
     */
    private $ccmAssetType;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var CcmMakes
     */
    private $ccmMake;


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
     * Set ccmModelName
     *
     * @param string $ccmModelName
     */
    public function setCcmModelName($ccmModelName)
    {
        $this->ccmModelName = $ccmModelName;
    }

    /**
     * Get ccmModelName
     *
     * @return string 
     */
    public function getCcmModelName()
    {
        return $this->ccmModelName;
    }

    /**
     * Set assetDimensionLength
     *
     * @param integer $assetDimensionLength
     */
    public function setAssetDimensionLength($assetDimensionLength)
    {
        $this->assetDimensionLength = $assetDimensionLength;
    }

    /**
     * Get assetDimensionLength
     *
     * @return integer 
     */
    public function getAssetDimensionLength()
    {
        return $this->assetDimensionLength;
    }

    /**
     * Set assetDimensionWidth
     *
     * @param integer $assetDimensionWidth
     */
    public function setAssetDimensionWidth($assetDimensionWidth)
    {
        $this->assetDimensionWidth = $assetDimensionWidth;
    }

    /**
     * Get assetDimensionWidth
     *
     * @return integer 
     */
    public function getAssetDimensionWidth()
    {
        return $this->assetDimensionWidth;
    }

    /**
     * Set assetDimensionHeight
     *
     * @param integer $assetDimensionHeight
     */
    public function setAssetDimensionHeight($assetDimensionHeight)
    {
        $this->assetDimensionHeight = $assetDimensionHeight;
    }

    /**
     * Get assetDimensionHeight
     *
     * @return integer 
     */
    public function getAssetDimensionHeight()
    {
        return $this->assetDimensionHeight;
    }

    /**
     * Set grossCapacity20
     *
     * @param float $grossCapacity20
     */
    public function setGrossCapacity20($grossCapacity20)
    {
        $this->grossCapacity20 = $grossCapacity20;
    }

    /**
     * Get grossCapacity20
     *
     * @return float 
     */
    public function getGrossCapacity20()
    {
        return $this->grossCapacity20;
    }

    /**
     * Set grossCapacity4
     *
     * @param float $grossCapacity4
     */
    public function setGrossCapacity4($grossCapacity4)
    {
        $this->grossCapacity4 = $grossCapacity4;
    }

    /**
     * Get grossCapacity4
     *
     * @return float 
     */
    public function getGrossCapacity4()
    {
        return $this->grossCapacity4;
    }

    /**
     * Set netCapacity20
     *
     * @param float $netCapacity20
     */
    public function setNetCapacity20($netCapacity20)
    {
        $this->netCapacity20 = $netCapacity20;
    }

    /**
     * Get netCapacity20
     *
     * @return float 
     */
    public function getNetCapacity20()
    {
        return $this->netCapacity20;
    }

    /**
     * Set netCapacity4
     *
     * @param float $netCapacity4
     */
    public function setNetCapacity4($netCapacity4)
    {
        $this->netCapacity4 = $netCapacity4;
    }

    /**
     * Get netCapacity4
     *
     * @return float 
     */
    public function getNetCapacity4()
    {
        return $this->netCapacity4;
    }

    /**
     * Set cfcFree
     *
     * @param boolean $cfcFree
     */
    public function setCfcFree($cfcFree)
    {
        $this->cfcFree = $cfcFree;
    }

    /**
     * Get cfcFree
     *
     * @return boolean 
     */
    public function getCfcFree()
    {
        return $this->cfcFree;
    }

    /**
     * Set noOfPhases
     *
     * @param boolean $noOfPhases
     */
    public function setNoOfPhases($noOfPhases)
    {
        $this->noOfPhases = $noOfPhases;
    }

    /**
     * Get noOfPhases
     *
     * @return boolean 
     */
    public function getNoOfPhases()
    {
        return $this->noOfPhases;
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
     * Set reasons
     *
     * @param string $reasons
     */
    public function setReasons($reasons)
    {
        $this->reasons = $reasons;
    }

    /**
     * Get reasons
     *
     * @return string 
     */
    public function getReasons()
    {
        return $this->reasons;
    }

    /**
     * Set utilizations
     *
     * @param string $utilizations
     */
    public function setUtilizations($utilizations)
    {
        $this->utilizations = $utilizations;
    }

    /**
     * Get utilizations
     *
     * @return string 
     */
    public function getUtilizations()
    {
        return $this->utilizations;
    }

    /**
     * Set temperatureType
     *
     * @param boolean $temperatureType
     */
    public function setTemperatureType($temperatureType)
    {
        $this->temperatureType = $temperatureType;
    }

    /**
     * Get temperatureType
     *
     * @return boolean 
     */
    public function getTemperatureType()
    {
        return $this->temperatureType;
    }

    /**
     * Set catalogueId
     *
     * @param string $catalogueId
     */
    public function setCatalogueId($catalogueId)
    {
        $this->catalogueId = $catalogueId;
    }

    /**
     * Get catalogueId
     *
     * @return string 
     */
    public function getCatalogueId()
    {
        return $this->catalogueId;
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
     * Set coldLife
     *
     * @param string $coldLife
     */
    public function setColdLife($coldLife)
    {
        $this->coldLife = $coldLife;
    }

    /**
     * Get coldLife
     *
     * @return string 
     */
    public function getColdLife()
    {
        return $this->coldLife;
    }

    /**
     * Set productPrice
     *
     * @param integer $productPrice
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;
    }

    /**
     * Get productPrice
     *
     * @return integer 
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * Set internalDimensionLength
     *
     * @param integer $internalDimensionLength
     */
    public function setInternalDimensionLength($internalDimensionLength)
    {
        $this->internalDimensionLength = $internalDimensionLength;
    }

    /**
     * Get internalDimensionLength
     *
     * @return integer 
     */
    public function getInternalDimensionLength()
    {
        return $this->internalDimensionLength;
    }

    /**
     * Set internalDimensionWidth
     *
     * @param integer $internalDimensionWidth
     */
    public function setInternalDimensionWidth($internalDimensionWidth)
    {
        $this->internalDimensionWidth = $internalDimensionWidth;
    }

    /**
     * Get internalDimensionWidth
     *
     * @return integer 
     */
    public function getInternalDimensionWidth()
    {
        return $this->internalDimensionWidth;
    }

    /**
     * Set internalDimensionHeight
     *
     * @param integer $internalDimensionHeight
     */
    public function setInternalDimensionHeight($internalDimensionHeight)
    {
        $this->internalDimensionHeight = $internalDimensionHeight;
    }

    /**
     * Get internalDimensionHeight
     *
     * @return integer 
     */
    public function getInternalDimensionHeight()
    {
        return $this->internalDimensionHeight;
    }

    /**
     * Set storageDimensionLength
     *
     * @param integer $storageDimensionLength
     */
    public function setStorageDimensionLength($storageDimensionLength)
    {
        $this->storageDimensionLength = $storageDimensionLength;
    }

    /**
     * Get storageDimensionLength
     *
     * @return integer 
     */
    public function getStorageDimensionLength()
    {
        return $this->storageDimensionLength;
    }

    /**
     * Set storageDimensionWidth
     *
     * @param integer $storageDimensionWidth
     */
    public function setStorageDimensionWidth($storageDimensionWidth)
    {
        $this->storageDimensionWidth = $storageDimensionWidth;
    }

    /**
     * Get storageDimensionWidth
     *
     * @return integer 
     */
    public function getStorageDimensionWidth()
    {
        return $this->storageDimensionWidth;
    }

    /**
     * Set storageDimensionHeight
     *
     * @param integer $storageDimensionHeight
     */
    public function setStorageDimensionHeight($storageDimensionHeight)
    {
        $this->storageDimensionHeight = $storageDimensionHeight;
    }

    /**
     * Get storageDimensionHeight
     *
     * @return integer 
     */
    public function getStorageDimensionHeight()
    {
        return $this->storageDimensionHeight;
    }

    /**
     * Set isPqs
     *
     * @param boolean $isPqs
     */
    public function setIsPqs($isPqs)
    {
        $this->isPqs = $isPqs;
    }

    /**
     * Get isPqs
     *
     * @return boolean 
     */
    public function getIsPqs()
    {
        return $this->isPqs;
    }

    /**
     * Set gasType
     *
     * @param ListDetail $gasType
     */
    public function setGasType(\ListDetail $gasType)
    {
        $this->gasType = $gasType;
    }

    /**
     * Get gasType
     *
     * @return ListDetail 
     */
    public function getGasType()
    {
        return $this->gasType;
    }

    /**
     * Set powerSource
     *
     * @param ListDetail $powerSource
     */
    public function setPowerSource(\ListDetail $powerSource)
    {
        $this->powerSource = $powerSource;
    }

    /**
     * Get powerSource
     *
     * @return ListDetail 
     */
    public function getPowerSource()
    {
        return $this->powerSource;
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
     * Set ccmMake
     *
     * @param CcmMakes $ccmMake
     */
    public function setCcmMake(\CcmMakes $ccmMake)
    {
        $this->ccmMake = $ccmMake;
    }

    /**
     * Get ccmMake
     *
     * @return CcmMakes 
     */
    public function getCcmMake()
    {
        return $this->ccmMake;
    }
}