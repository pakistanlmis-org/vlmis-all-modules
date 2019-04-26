<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * PlacementLocations
 */
class PlacementLocations
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $locationBarcode
     */
    private $locationBarcode;

    /**
     * @var integer $locationId
     */
    private $locationId;

    /**
     * @var decimal $capacityPercentage
     */
    private $capacityPercentage;

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
    private $locationType;

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
     * Set locationBarcode
     *
     * @param string $locationBarcode
     */
    public function setLocationBarcode($locationBarcode)
    {
        $this->locationBarcode = $locationBarcode;
    }

    /**
     * Get locationBarcode
     *
     * @return string 
     */
    public function getLocationBarcode()
    {
        return $this->locationBarcode;
    }

    /**
     * Set locationId
     *
     * @param integer $locationId
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;
    }

    /**
     * Get locationId
     *
     * @return integer 
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set capacityPercentage
     *
     * @param decimal $capacityPercentage
     */
    public function setCapacityPercentage($capacityPercentage)
    {
        $this->capacityPercentage = $capacityPercentage;
    }

    /**
     * Get capacityPercentage
     *
     * @return decimal 
     */
    public function getCapacityPercentage()
    {
        return $this->capacityPercentage;
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
     * Set locationType
     *
     * @param ListDetail $locationType
     */
    public function setLocationType(\ListDetail $locationType)
    {
        $this->locationType = $locationType;
    }

    /**
     * Get locationType
     *
     * @return ListDetail 
     */
    public function getLocationType()
    {
        return $this->locationType;
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