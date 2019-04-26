<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Locations
 */
class Locations
{
    /**
     * @var integer $pkId
     */
    private $pkId;
    
    /**
     * @var boolean $status
     */
    private $status;

    /**
     * @var string $locationName
     */
    private $locationName;

    /**
     * @var integer $ccmLocationId
     */
    private $ccmLocationId;

    /**
     * @var string $sdmsName
     */
    private $sdmsName;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Locations
     */
    private $district;

    /**
     * @var GeoLevels
     */
    private $geoLevel;

    /**
     * @var LocationTypes
     */
    private $locationType;

    /**
     * @var Locations
     */
    private $province;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var Locations
     */
    private $parent;


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
     * Set locationName
     *
     * @param string $locationName
     */
    public function setLocationName($locationName)
    {
        $this->locationName = $locationName;
    }

    /**
     * Get locationName
     *
     * @return string 
     */
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * Set ccmLocationId
     *
     * @param integer $ccmLocationId
     */
    public function setCcmLocationId($ccmLocationId)
    {
        $this->ccmLocationId = $ccmLocationId;
    }

    /**
     * Get ccmLocationId
     *
     * @return integer 
     */
    public function getCcmLocationId()
    {
        return $this->ccmLocationId;
    }

    /**
     * Set sdmsName
     *
     * @param string $sdmsName
     */
    public function setSdmsName($sdmsName)
    {
        $this->sdmsName = $sdmsName;
    }

    /**
     * Get sdmsName
     *
     * @return string 
     */
    public function getSdmsName()
    {
        return $this->sdmsName;
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
     * Set district
     *
     * @param Locations $district
     */
    public function setDistrict(\Locations $district)
    {
        $this->district = $district;
    }

    /**
     * Get district
     *
     * @return Locations 
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set geoLevel
     *
     * @param GeoLevels $geoLevel
     */
    public function setGeoLevel(\GeoLevels $geoLevel)
    {
        $this->geoLevel = $geoLevel;
    }

    /**
     * Get geoLevel
     *
     * @return GeoLevels 
     */
    public function getGeoLevel()
    {
        return $this->geoLevel;
    }

    /**
     * Set locationType
     *
     * @param LocationTypes $locationType
     */
    public function setLocationType(\LocationTypes $locationType)
    {
        $this->locationType = $locationType;
    }

    /**
     * Get locationType
     *
     * @return LocationTypes 
     */
    public function getLocationType()
    {
        return $this->locationType;
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
     * Set province
     *
     * @param Locations $province
     */
    public function setProvince(\Locations $province)
    {
        $this->province = $province;
    }

    /**
     * Get province
     *
     * @return Locations 
     */
    public function getProvince()
    {
        return $this->province;
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
     * Set parent
     *
     * @param Locations $parent
     */
    public function setParent(\Locations $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Locations 
     */
    public function getParent()
    {
        return $this->parent;
    }
}