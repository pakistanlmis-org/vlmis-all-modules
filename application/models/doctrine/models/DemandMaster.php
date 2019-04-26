<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * DemandMaster
 */
class DemandMaster
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var date $fromDate
     */
    private $fromDate;

    /**
     * @var date $toDate
     */
    private $toDate;

    /**
     * @var date $suggestedDate
     */
    private $suggestedDate;

    /**
     * @var date $approvedDate
     */
    private $approvedDate;

    /**
     * @var string $requisitionNumber
     */
    private $requisitionNumber;

    /**
     * @var integer $requisitionCounter
     */
    private $requisitionCounter;

    /**
     * @var string $requisitionReference
     */
    private $requisitionReference;

    /**
     * @var boolean $draft
     */
    private $draft;

    /**
     * @var boolean $status
     */
    private $status;

    /**
     * @var text $comments
     */
    private $comments;

    /**
     * @var boolean $isDeleted
     */
    private $isDeleted;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var TransactionTypes
     */
    private $transactionType;

    /**
     * @var Warehouses
     */
    private $fromWarehouse;

    /**
     * @var Warehouses
     */
    private $toWarehouse;

    /**
     * @var StockMaster
     */
    private $stockMaster;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var StakeholderActivities
     */
    private $stakeholderActivity;


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
     * Set fromDate
     *
     * @param date $fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * Get fromDate
     *
     * @return date 
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param date $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * Get toDate
     *
     * @return date 
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set suggestedDate
     *
     * @param date $suggestedDate
     */
    public function setSuggestedDate($suggestedDate)
    {
        $this->suggestedDate = $suggestedDate;
    }

    /**
     * Get suggestedDate
     *
     * @return date 
     */
    public function getSuggestedDate()
    {
        return $this->suggestedDate;
    }

    /**
     * Set approvedDate
     *
     * @param date $approvedDate
     */
    public function setApprovedDate($approvedDate)
    {
        $this->approvedDate = $approvedDate;
    }

    /**
     * Get approvedDate
     *
     * @return date 
     */
    public function getApprovedDate()
    {
        return $this->approvedDate;
    }

    /**
     * Set requisitionNumber
     *
     * @param string $requisitionNumber
     */
    public function setRequisitionNumber($requisitionNumber)
    {
        $this->requisitionNumber = $requisitionNumber;
    }

    /**
     * Get requisitionNumber
     *
     * @return string 
     */
    public function getRequisitionNumber()
    {
        return $this->requisitionNumber;
    }

    /**
     * Set requisitionCounter
     *
     * @param integer $requisitionCounter
     */
    public function setRequisitionCounter($requisitionCounter)
    {
        $this->requisitionCounter = $requisitionCounter;
    }

    /**
     * Get requisitionCounter
     *
     * @return integer 
     */
    public function getRequisitionCounter()
    {
        return $this->requisitionCounter;
    }

    /**
     * Set requisitionReference
     *
     * @param string $requisitionReference
     */
    public function setRequisitionReference($requisitionReference)
    {
        $this->requisitionReference = $requisitionReference;
    }

    /**
     * Get requisitionReference
     *
     * @return string 
     */
    public function getRequisitionReference()
    {
        return $this->requisitionReference;
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
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
     * Set transactionType
     *
     * @param TransactionTypes $transactionType
     */
    public function setTransactionType(\TransactionTypes $transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * Get transactionType
     *
     * @return TransactionTypes 
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set fromWarehouse
     *
     * @param Warehouses $fromWarehouse
     */
    public function setFromWarehouse(\Warehouses $fromWarehouse)
    {
        $this->fromWarehouse = $fromWarehouse;
    }

    /**
     * Get fromWarehouse
     *
     * @return Warehouses 
     */
    public function getFromWarehouse()
    {
        return $this->fromWarehouse;
    }

    /**
     * Set toWarehouse
     *
     * @param Warehouses $toWarehouse
     */
    public function setToWarehouse(\Warehouses $toWarehouse)
    {
        $this->toWarehouse = $toWarehouse;
    }

    /**
     * Get toWarehouse
     *
     * @return Warehouses 
     */
    public function getToWarehouse()
    {
        return $this->toWarehouse;
    }

    /**
     * Set stockMaster
     *
     * @param StockMaster $stockMaster
     */
    public function setStockMaster(\StockMaster $stockMaster)
    {
        $this->stockMaster = $stockMaster;
    }

    /**
     * Get stockMaster
     *
     * @return StockMaster 
     */
    public function getStockMaster()
    {
        return $this->stockMaster;
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
}