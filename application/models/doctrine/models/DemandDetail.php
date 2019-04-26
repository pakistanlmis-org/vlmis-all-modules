<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * DemandDetail
 */
class DemandDetail
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var bigint $demandQuantity
     */
    private $demandQuantity;

    /**
     * @var bigint $approvedQuantity
     */
    private $approvedQuantity;

    /**
     * @var integer $pairProductId
     */
    private $pairProductId;

    /**
     * @var boolean $draft
     */
    private $draft;

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
     * @var boolean $isDeleted
     */
    private $isDeleted;

    /**
     * @var DemandMaster
     */
    private $demandMaster;

    /**
     * @var ItemPackSizes
     */
    private $product;

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
     * Set demandQuantity
     *
     * @param bigint $demandQuantity
     */
    public function setDemandQuantity($demandQuantity)
    {
        $this->demandQuantity = $demandQuantity;
    }

    /**
     * Get demandQuantity
     *
     * @return bigint 
     */
    public function getDemandQuantity()
    {
        return $this->demandQuantity;
    }

    /**
     * Set approvedQuantity
     *
     * @param bigint $approvedQuantity
     */
    public function setApprovedQuantity($approvedQuantity)
    {
        $this->approvedQuantity = $approvedQuantity;
    }

    /**
     * Get approvedQuantity
     *
     * @return bigint 
     */
    public function getApprovedQuantity()
    {
        return $this->approvedQuantity;
    }

    /**
     * Set pairProductId
     *
     * @param integer $pairProductId
     */
    public function setPairProductId($pairProductId)
    {
        $this->pairProductId = $pairProductId;
    }

    /**
     * Get pairProductId
     *
     * @return integer 
     */
    public function getPairProductId()
    {
        return $this->pairProductId;
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
     * Set demandMaster
     *
     * @param DemandMaster $demandMaster
     */
    public function setDemandMaster(\DemandMaster $demandMaster)
    {
        $this->demandMaster = $demandMaster;
    }

    /**
     * Get demandMaster
     *
     * @return DemandMaster 
     */
    public function getDemandMaster()
    {
        return $this->demandMaster;
    }

    /**
     * Set product
     *
     * @param ItemPackSizes $product
     */
    public function setProduct(\ItemPackSizes $product)
    {
        $this->product = $product;
    }

    /**
     * Get product
     *
     * @return ItemPackSizes 
     */
    public function getProduct()
    {
        return $this->product;
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