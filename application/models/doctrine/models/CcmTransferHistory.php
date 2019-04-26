<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmTransferHistory
 */
class CcmTransferHistory
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $quantity
     */
    private $quantity;

    /**
     * @var datetime $transferDate
     */
    private $transferDate;

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
     * @var ColdChain
     */
    private $ccm;

    /**
     * @var Warehouses
     */
    private $fromWarehouse;

    /**
     * @var Warehouses
     */
    private $toWarehouse;

    /**
     * @var CcmStatusList
     */
    private $ccmStatusList;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $transferBy;


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
     * Set transferDate
     *
     * @param datetime $transferDate
     */
    public function setTransferDate($transferDate)
    {
        $this->transferDate = $transferDate;
    }

    /**
     * Get transferDate
     *
     * @return datetime 
     */
    public function getTransferDate()
    {
        return $this->transferDate;
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
     * Set ccmStatusList
     *
     * @param CcmStatusList $ccmStatusList
     */
    public function setCcmStatusList(\CcmStatusList $ccmStatusList)
    {
        $this->ccmStatusList = $ccmStatusList;
    }

    /**
     * Get ccmStatusList
     *
     * @return CcmStatusList 
     */
    public function getCcmStatusList()
    {
        return $this->ccmStatusList;
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
     * Set transferBy
     *
     * @param Users $transferBy
     */
    public function setTransferBy(\Users $transferBy)
    {
        $this->transferBy = $transferBy;
    }

    /**
     * Get transferBy
     *
     * @return Users 
     */
    public function getTransferBy()
    {
        return $this->transferBy;
    }
}