<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmVehicles
 */
class CcmVehicles
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $registrationNo
     */
    private $registrationNo;

    /**
     * @var integer $usedForEpi
     */
    private $usedForEpi;

    /**
     * @var text $comments
     */
    private $comments;

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
    private $fuelType;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var CcmAssetTypes
     */
    private $ccmAssetSubType;

    /**
     * @var ColdChain
     */
    private $ccm;


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
     * Set registrationNo
     *
     * @param string $registrationNo
     */
    public function setRegistrationNo($registrationNo)
    {
        $this->registrationNo = $registrationNo;
    }

    /**
     * Get registrationNo
     *
     * @return string 
     */
    public function getRegistrationNo()
    {
        return $this->registrationNo;
    }

    /**
     * Set usedForEpi
     *
     * @param integer $usedForEpi
     */
    public function setUsedForEpi($usedForEpi)
    {
        $this->usedForEpi = $usedForEpi;
    }

    /**
     * Get usedForEpi
     *
     * @return integer 
     */
    public function getUsedForEpi()
    {
        return $this->usedForEpi;
    }

    /**
     * Set comments
     *
     * @param text $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Get comments
     *
     * @return text 
     */
    public function getComments()
    {
        return $this->comments;
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
     * Set fuelType
     *
     * @param ListDetail $fuelType
     */
    public function setFuelType(\ListDetail $fuelType)
    {
        $this->fuelType = $fuelType;
    }

    /**
     * Get fuelType
     *
     * @return ListDetail 
     */
    public function getFuelType()
    {
        return $this->fuelType;
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
}