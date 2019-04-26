<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * LocationPopulations
 */
class LocationPopulations
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $population
     */
    private $population;

    /**
     * @var datetime $estimationDate
     */
    private $estimationDate;

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
    private $location;

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
     * Set population
     *
     * @param integer $population
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    }

    /**
     * Get population
     *
     * @return integer 
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set estimationDate
     *
     * @param datetime $estimationDate
     */
    public function setEstimationDate($estimationDate)
    {
        $this->estimationDate = $estimationDate;
    }

    /**
     * Get estimationDate
     *
     * @return datetime 
     */
    public function getEstimationDate()
    {
        return $this->estimationDate;
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
     * Set location
     *
     * @param Locations $location
     */
    public function setLocation(\Locations $location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @return Locations 
     */
    public function getLocation()
    {
        return $this->location;
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