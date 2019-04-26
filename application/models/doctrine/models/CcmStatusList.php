<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmStatusList
 */
class CcmStatusList
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $ccmStatusListName
     */
    private $ccmStatusListName;

    /**
     * @var boolean $type
     */
    private $type;

    /**
     * @var boolean $reasonType
     */
    private $reasonType;

    /**
     * @var boolean $status
     */
    private $status;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Warehouses
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
     * Set ccmStatusListName
     *
     * @param string $ccmStatusListName
     */
    public function setCcmStatusListName($ccmStatusListName)
    {
        $this->ccmStatusListName = $ccmStatusListName;
    }

    /**
     * Get ccmStatusListName
     *
     * @return string 
     */
    public function getCcmStatusListName()
    {
        return $this->ccmStatusListName;
    }

    /**
     * Set type
     *
     * @param boolean $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return boolean 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set reasonType
     *
     * @param boolean $reasonType
     */
    public function setReasonType($reasonType)
    {
        $this->reasonType = $reasonType;
    }

    /**
     * Get reasonType
     *
     * @return boolean 
     */
    public function getReasonType()
    {
        return $this->reasonType;
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
     * @param Warehouses $modifiedBy
     */
    public function setModifiedBy(\Warehouses $modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Get modifiedBy
     *
     * @return Warehouses 
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