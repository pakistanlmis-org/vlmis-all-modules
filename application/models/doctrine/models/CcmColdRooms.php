<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmColdRooms
 */
class CcmColdRooms
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $coolingSystem
     */
    private $coolingSystem;

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
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var ListDetail
     */
    private $typeRecordingSystem;

    /**
     * @var ListDetail
     */
    private $refrigeratorGasType;

    /**
     * @var ListDetail
     */
    private $temperatureRecordingSystem;

    /**
     * @var ListDetail
     */
    private $backupGenerator;

    /**
     * @var CcmAssetTypes
     */
    private $ccmAssetSubType;

    /**
     * @var ColdChain
     */
    private $ccm;

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
     * Set coolingSystem
     *
     * @param integer $coolingSystem
     */
    public function setCoolingSystem($coolingSystem)
    {
        $this->coolingSystem = $coolingSystem;
    }

    /**
     * Get coolingSystem
     *
     * @return integer 
     */
    public function getCoolingSystem()
    {
        return $this->coolingSystem;
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
     * Set typeRecordingSystem
     *
     * @param ListDetail $typeRecordingSystem
     */
    public function setTypeRecordingSystem(\ListDetail $typeRecordingSystem)
    {
        $this->typeRecordingSystem = $typeRecordingSystem;
    }

    /**
     * Get typeRecordingSystem
     *
     * @return ListDetail 
     */
    public function getTypeRecordingSystem()
    {
        return $this->typeRecordingSystem;
    }

    /**
     * Set refrigeratorGasType
     *
     * @param ListDetail $refrigeratorGasType
     */
    public function setRefrigeratorGasType(\ListDetail $refrigeratorGasType)
    {
        $this->refrigeratorGasType = $refrigeratorGasType;
    }

    /**
     * Get refrigeratorGasType
     *
     * @return ListDetail 
     */
    public function getRefrigeratorGasType()
    {
        return $this->refrigeratorGasType;
    }

    /**
     * Set temperatureRecordingSystem
     *
     * @param ListDetail $temperatureRecordingSystem
     */
    public function setTemperatureRecordingSystem(\ListDetail $temperatureRecordingSystem)
    {
        $this->temperatureRecordingSystem = $temperatureRecordingSystem;
    }

    /**
     * Get temperatureRecordingSystem
     *
     * @return ListDetail 
     */
    public function getTemperatureRecordingSystem()
    {
        return $this->temperatureRecordingSystem;
    }

    /**
     * Set backupGenerator
     *
     * @param ListDetail $backupGenerator
     */
    public function setBackupGenerator(\ListDetail $backupGenerator)
    {
        $this->backupGenerator = $backupGenerator;
    }

    /**
     * Get backupGenerator
     *
     * @return ListDetail 
     */
    public function getBackupGenerator()
    {
        return $this->backupGenerator;
    }

    /**
     * Set ccmAssetSubType
     *
     * @param CcmAssetTypes $ccmAssetSubType
     */
    public function setCcmAssetSubType(\CcmAssetTypes $ccmAssetSubType)
    {
        $this->ccmAssetSubType = $ccmAssetSubType;
    }

    /**
     * Get ccmAssetSubType
     *
     * @return CcmAssetTypes 
     */
    public function getCcmAssetSubType()
    {
        return $this->ccmAssetSubType;
    }

    /**
     * Set ccm
     *
     * @param ColdChain $ccm
     */
    public function setCcm(\ColdChain $ccm)
    {
        $this->ccm = $ccm;
    }

    /**
     * Get ccm
     *
     * @return ColdChain 
     */
    public function getCcm()
    {
        return $this->ccm;
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