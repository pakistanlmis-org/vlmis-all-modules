<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * WarehousePopulationArchieve
 */
class WarehousePopulationArchieve
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $facilityTotalPouplation
     */
    private $facilityTotalPouplation;

    /**
     * @var integer $liveBirthsPerYear
     */
    private $liveBirthsPerYear;

    /**
     * @var integer $pregnantWomenPerYear
     */
    private $pregnantWomenPerYear;

    /**
     * @var integer $womenOfChildBearingAge
     */
    private $womenOfChildBearingAge;

    /**
     * @var datetime $estimationYear
     */
    private $estimationYear;

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
     * @var Warehouses
     */
    private $warehouse;

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
     * Set facilityTotalPouplation
     *
     * @param integer $facilityTotalPouplation
     */
    public function setFacilityTotalPouplation($facilityTotalPouplation)
    {
        $this->facilityTotalPouplation = $facilityTotalPouplation;
    }

    /**
     * Get facilityTotalPouplation
     *
     * @return integer 
     */
    public function getFacilityTotalPouplation()
    {
        return $this->facilityTotalPouplation;
    }

    /**
     * Set liveBirthsPerYear
     *
     * @param integer $liveBirthsPerYear
     */
    public function setLiveBirthsPerYear($liveBirthsPerYear)
    {
        $this->liveBirthsPerYear = $liveBirthsPerYear;
    }

    /**
     * Get liveBirthsPerYear
     *
     * @return integer 
     */
    public function getLiveBirthsPerYear()
    {
        return $this->liveBirthsPerYear;
    }

    /**
     * Set pregnantWomenPerYear
     *
     * @param integer $pregnantWomenPerYear
     */
    public function setPregnantWomenPerYear($pregnantWomenPerYear)
    {
        $this->pregnantWomenPerYear = $pregnantWomenPerYear;
    }

    /**
     * Get pregnantWomenPerYear
     *
     * @return integer 
     */
    public function getPregnantWomenPerYear()
    {
        return $this->pregnantWomenPerYear;
    }

    /**
     * Set womenOfChildBearingAge
     *
     * @param integer $womenOfChildBearingAge
     */
    public function setWomenOfChildBearingAge($womenOfChildBearingAge)
    {
        $this->womenOfChildBearingAge = $womenOfChildBearingAge;
    }

    /**
     * Get womenOfChildBearingAge
     *
     * @return integer 
     */
    public function getWomenOfChildBearingAge()
    {
        return $this->womenOfChildBearingAge;
    }

    /**
     * Set estimationYear
     *
     * @param datetime $estimationYear
     */
    public function setEstimationYear($estimationYear)
    {
        $this->estimationYear = $estimationYear;
    }

    /**
     * Get estimationYear
     *
     * @return datetime 
     */
    public function getEstimationYear()
    {
        return $this->estimationYear;
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
     * Set warehouse
     *
     * @param Warehouses $warehouse
     */
    public function setWarehouse(\Warehouses $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    /**
     * Get warehouse
     *
     * @return Warehouses 
     */
    public function getWarehouse()
    {
        return $this->warehouse;
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