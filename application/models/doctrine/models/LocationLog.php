<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Locations
 */
class LocationLog {

    /**
     * @var integer $pkId
     */
    private $pkId;
    private $location;
    private $population;
            
    /**
     * @var string $locationName
     */
    private $locationName;

    /**
     * @var integer $ccmLocationId
     */
    private $ccmLocationId;

   

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

    /**
     * @var Locations
     */
    private $province;

    /**
     * @var Users
     */

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
    public function getPkId() {
        return $this->pkId;
    }

    /**
     * Set locationName
     *
     * @param string $locationName
     */
    public function setLocationName($locationName) {
        $this->locationName = $locationName;
    }

    /**
     * Get locationName
     *
     * @return string 
     */
    public function getLocationName() {
        return $this->locationName;
    }
public function setPopulation($population) {
        $this->population = $population;
    }
 
    public function getPopulation() {
        return $this->population;
    }
     public function setLocation(\Locations $location) {
        $this->location = $location;
    }

    /**
     * Get district
     *
     * @return Locations 
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set ccmLocationId
     *
     * @param integer $ccmLocationId
     */
    public function setCcmLocationId($ccmLocationId) {
        $this->ccmLocationId = $ccmLocationId;
    }

    /**
     * Get ccmLocationId
     *
     * @return integer 
     */
    public function getCcmLocationId() {
        return $this->ccmLocationId;
    }

   

    /**
     * Set modifiedDate
     *
     * @param datetime $modifiedDate
     */
    public function setModifiedDate($modifiedDate) {
        $this->modifiedDate = $modifiedDate;
    }

    /**
     * Get modifiedDate
     *
     * @return datetime 
     */
    public function getModifiedDate() {
        return $this->modifiedDate;
    }

    /**
     * Set district
     *
     * @param Locations $district
     */
    public function setDistrict(\Locations $district) {
        $this->district = $district;
    }

    /**
     * Get district
     *
     * @return Locations 
     */
    public function getDistrict() {
        return $this->district;
    }

    /**
     * Set geoLevel
     *
     * @param GeoLevels $geoLevel
     */
    public function setGeoLevel(\GeoLevels $geoLevel) {
        $this->geoLevel = $geoLevel;
    }

    /**
     * Get geoLevel
     *
     * @return GeoLevels 
     */
    public function getGeoLevel() {
        return $this->geoLevel;
    }

    /**
     * Set locationType
     *
     * @param LocationTypes $locationType
     */

    /**
     * Set province
     *
     * @param Locations $province
     */
    public function setProvince(\Locations $province) {
        $this->province = $province;
    }

    /**
     * Get province
     *
     * @return Locations 
     */
    public function getProvince() {
        return $this->province;
    }




      /**
     * Set modifiedBy
     *
     * @param Users $modifiedBy
     */
    public function setModifiedBy(\Users $modifiedBy) {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Get modifiedBy
     *
     * @return Users 
     */
    public function getModifiedBy() {
        return $this->modifiedBy;
    }

    /**
     * Set parent
     *
     * @param Locations $parent
     */
    public function setParent(\Locations $parent) {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Locations 
     */
    public function getParent() {
        return $this->parent;
    }

}
