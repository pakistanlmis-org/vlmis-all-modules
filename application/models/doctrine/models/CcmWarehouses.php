<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmWarehouses
 */
class CcmWarehouses
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $routineImmunizationIcepackRequirments
     */
    private $routineImmunizationIcepackRequirments;

    /**
     * @var string $campaignIcepackRequirments
     */
    private $campaignIcepackRequirments;

    /**
     * @var integer $vaccineSupplyMode
     */
    private $vaccineSupplyMode;

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
    private $electricityAvailability;

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
     * Set routineImmunizationIcepackRequirments
     *
     * @param string $routineImmunizationIcepackRequirments
     */
    public function setRoutineImmunizationIcepackRequirments($routineImmunizationIcepackRequirments)
    {
        $this->routineImmunizationIcepackRequirments = $routineImmunizationIcepackRequirments;
    }

    /**
     * Get routineImmunizationIcepackRequirments
     *
     * @return string 
     */
    public function getRoutineImmunizationIcepackRequirments()
    {
        return $this->routineImmunizationIcepackRequirments;
    }

    /**
     * Set campaignIcepackRequirments
     *
     * @param string $campaignIcepackRequirments
     */
    public function setCampaignIcepackRequirments($campaignIcepackRequirments)
    {
        $this->campaignIcepackRequirments = $campaignIcepackRequirments;
    }

    /**
     * Get campaignIcepackRequirments
     *
     * @return string 
     */
    public function getCampaignIcepackRequirments()
    {
        return $this->campaignIcepackRequirments;
    }

    /**
     * Set vaccineSupplyMode
     *
     * @param integer $vaccineSupplyMode
     */
    public function setVaccineSupplyMode($vaccineSupplyMode)
    {
        $this->vaccineSupplyMode = $vaccineSupplyMode;
    }

    /**
     * Get vaccineSupplyMode
     *
     * @return integer 
     */
    public function getVaccineSupplyMode()
    {
        return $this->vaccineSupplyMode;
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
     * Set electricityAvailability
     *
     * @param ListDetail $electricityAvailability
     */
    public function setElectricityAvailability(\ListDetail $electricityAvailability)
    {
        $this->electricityAvailability = $electricityAvailability;
    }

    /**
     * Get electricityAvailability
     *
     * @return ListDetail 
     */
    public function getElectricityAvailability()
    {
        return $this->electricityAvailability;
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