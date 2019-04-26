<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * RackInformation
 */
class RackInformation
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $rackType
     */
    private $rackType;

    /**
     * @var smallint $noOfBins
     */
    private $noOfBins;

    /**
     * @var float $binNetCapacity
     */
    private $binNetCapacity;

    /**
     * @var float $grossCapacity
     */
    private $grossCapacity;

    /**
     * @var string $capacityUnit
     */
    private $capacityUnit;

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
     * Set rackType
     *
     * @param string $rackType
     */
    public function setRackType($rackType)
    {
        $this->rackType = $rackType;
    }

    /**
     * Get rackType
     *
     * @return string 
     */
    public function getRackType()
    {
        return $this->rackType;
    }

    /**
     * Set noOfBins
     *
     * @param smallint $noOfBins
     */
    public function setNoOfBins($noOfBins)
    {
        $this->noOfBins = $noOfBins;
    }

    /**
     * Get noOfBins
     *
     * @return smallint 
     */
    public function getNoOfBins()
    {
        return $this->noOfBins;
    }

    /**
     * Set binNetCapacity
     *
     * @param float $binNetCapacity
     */
    public function setBinNetCapacity($binNetCapacity)
    {
        $this->binNetCapacity = $binNetCapacity;
    }

    /**
     * Get binNetCapacity
     *
     * @return float 
     */
    public function getBinNetCapacity()
    {
        return $this->binNetCapacity;
    }

    /**
     * Set grossCapacity
     *
     * @param float $grossCapacity
     */
    public function setGrossCapacity($grossCapacity)
    {
        $this->grossCapacity = $grossCapacity;
    }

    /**
     * Get grossCapacity
     *
     * @return float 
     */
    public function getGrossCapacity()
    {
        return $this->grossCapacity;
    }

    /**
     * Set capacityUnit
     *
     * @param string $capacityUnit
     */
    public function setCapacityUnit($capacityUnit)
    {
        $this->capacityUnit = $capacityUnit;
    }

    /**
     * Get capacityUnit
     *
     * @return string 
     */
    public function getCapacityUnit()
    {
        return $this->capacityUnit;
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