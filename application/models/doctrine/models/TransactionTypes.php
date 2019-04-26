<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TransactionTypes
 */
class TransactionTypes
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $transactionTypeName
     */
    private $transactionTypeName;

    /**
     * @var string $nature
     */
    private $nature;

    /**
     * @var boolean $isAdjustment
     */
    private $isAdjustment;

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
     * @var Users
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
     * Set transactionTypeName
     *
     * @param string $transactionTypeName
     */
    public function setTransactionTypeName($transactionTypeName)
    {
        $this->transactionTypeName = $transactionTypeName;
    }

    /**
     * Get transactionTypeName
     *
     * @return string 
     */
    public function getTransactionTypeName()
    {
        return $this->transactionTypeName;
    }

    /**
     * Set nature
     *
     * @param string $nature
     */
    public function setNature($nature)
    {
        $this->nature = $nature;
    }

    /**
     * Get nature
     *
     * @return string 
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set isAdjustment
     *
     * @param boolean $isAdjustment
     */
    public function setIsAdjustment($isAdjustment)
    {
        $this->isAdjustment = $isAdjustment;
    }

    /**
     * Get isAdjustment
     *
     * @return boolean 
     */
    public function getIsAdjustment()
    {
        return $this->isAdjustment;
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
}