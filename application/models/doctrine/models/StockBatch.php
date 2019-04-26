<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StockBatch
 */
class StockBatch
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $number
     */
    private $number;

    /**
     * @var date $expiryDate
     */
    private $expiryDate;

    /**
     * @var float $unitPrice
     */
    private $unitPrice;

    /**
     * @var date $productionDate
     */
    private $productionDate;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var VvmTypes
     */
    private $vvmType;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var PackInfo
     */
    private $packInfo;


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
     * Set number
     *
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set expiryDate
     *
     * @param date $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * Get expiryDate
     *
     * @return date 
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * Get unitPrice
     *
     * @return float 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set productionDate
     *
     * @param date $productionDate
     */
    public function setProductionDate($productionDate)
    {
        $this->productionDate = $productionDate;
    }

    /**
     * Get productionDate
     *
     * @return date 
     */
    public function getProductionDate()
    {
        return $this->productionDate;
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
     * Set vvmType
     *
     * @param VvmTypes $vvmType
     */
    public function setVvmType(\VvmTypes $vvmType)
    {
        $this->vvmType = $vvmType;
    }

    /**
     * Get vvmType
     *
     * @return VvmTypes 
     */
    public function getVvmType()
    {
        return $this->vvmType;
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
     * Set packInfo
     *
     * @param PackInfo $packInfo
     */
    public function setPackInfo(\PackInfo $packInfo)
    {
        $this->packInfo = $packInfo;
    }

    /**
     * Get packInfo
     *
     * @return PackInfo 
     */
    public function getPackInfo()
    {
        return $this->packInfo;
    }
}