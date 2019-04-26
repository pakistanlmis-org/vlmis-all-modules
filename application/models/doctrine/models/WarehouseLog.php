<?php



use Doctrine\ORM\Mapping as ORM;
 
class WarehouseLog
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $warehouseName
     */
    private $warehouseName;
    private $warehouseId;
    /**
     * @var float $population
     */
    private $population;

    /**
     * @var string $ccemId
     */
    private $ccemId;

    /**
     * @var datetime $startingOn
     */
    private $startingOn;

    /**
     * @var datetime $fromEdit
     */
    private $fromEdit;

     

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Locations
     */
    private $district;

    /**
     * @var Locations
     */
    private $province;

    /**
     * @var Stakeholders
     */
    private $stakeholder;

    

    /**
     * @var Stakeholders
     */
    private $stakeholderOffice;

    /**
     * @var WarehouseTypes
     */
    private $warehouseType;
 
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
     * Set warehouseName
     *
     * @param string $warehouseName
     */
    public function setWarehouseName($warehouseName)
    {
        $this->warehouseName = $warehouseName;
    }

    /**
     * Get warehouseName
     *
     * @return string 
     */
    public function getWarehouseName()
    {
        return $this->warehouseName;
    }

    /**
     * Set population
     *
     * @param float $population
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    }

    /**
     * Get population
     *
     * @return float 
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set ccemId
     *
     * @param string $ccemId
     */
    public function setCcemId($ccemId)
    {
        $this->ccemId = $ccemId;
    }

    /**
     * Get ccemId
     *
     * @return string 
     */
    public function getCcemId()
    {
        return $this->ccemId;
    }

    /**
     * Set startingOn
     *
     * @param datetime $startingOn
     */
    public function setStartingOn($startingOn)
    {
        $this->startingOn = $startingOn;
    }

    /**
     * Get startingOn
     *
     * @return datetime 
     */
    public function getStartingOn()
    {
        return $this->startingOn;
    }

    /**
     * Set fromEdit
     *
     * @param datetime $fromEdit
     */
    public function setFromEdit($fromEdit)
    {
        $this->fromEdit = $fromEdit;
    }

    /**
     * Get fromEdit
     *
     * @return datetime 
     */
    public function getFromEdit()
    {
        return $this->fromEdit;
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
     * Set stakeholder
     *
     * @param Stakeholders $stakeholder
     */
    public function setStakeholder(\Stakeholders $stakeholder)
    {
        $this->stakeholder = $stakeholder;
    }

    /**
     * Get stakeholder
     *
     * @return Stakeholders 
     */
    public function getStakeholder()
    {
        return $this->stakeholder;
    }

    /**
     * Set location
     *
     * @param Locations $location
     */
     

    /**
     * Set stakeholderOffice
     *
     * @param Stakeholders $stakeholderOffice
     */
    public function setStakeholderOffice(\Stakeholders $stakeholderOffice)
    {
        $this->stakeholderOffice = $stakeholderOffice;
    }

    /**
     * Get stakeholderOffice
     *
     * @return Stakeholders 
     */
    public function getStakeholderOffice()
    {
        return $this->stakeholderOffice;
    }

    /**
     * Set warehouseType
     *
     * @param WarehouseTypes $warehouseType
     */
    public function setWarehouseType(\WarehouseTypes $warehouseType)
    {
        $this->warehouseType = $warehouseType;
    }

    /**
     * Get warehouseType
     *
     * @return WarehouseTypes 
     */
    public function getWarehouseType()
    {
        return $this->warehouseType;
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
    public function setWarehouseId($warehouseId)
    {
        $this->warehouseId = $warehouseId;
    }
 
    public function getWarehouseId()
    {
        return $this->warehouseId;
    }
}