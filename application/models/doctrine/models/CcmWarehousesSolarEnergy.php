<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmWarehousesSolarEnergy
 */
class CcmWarehousesSolarEnergy
{
    /**
     * @var integer $pkId
     */
    private $pkId;

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
     * @var CcmWarehouses
     */
    private $ccmWarehouse;

    /**
     * @var ListDetail
     */
    private $solarEnergy;

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
     * Set ccmWarehouse
     *
     * @param CcmWarehouses $ccmWarehouse
     */
    public function setCcmWarehouse(\CcmWarehouses $ccmWarehouse)
    {
        $this->ccmWarehouse = $ccmWarehouse;
    }

    /**
     * Get ccmWarehouse
     *
     * @return CcmWarehouses 
     */
    public function getCcmWarehouse()
    {
        return $this->ccmWarehouse;
    }

    /**
     * Set solarEnergy
     *
     * @param ListDetail $solarEnergy
     */
    public function setSolarEnergy(\ListDetail $solarEnergy)
    {
        $this->solarEnergy = $solarEnergy;
    }

    /**
     * Get solarEnergy
     *
     * @return ListDetail 
     */
    public function getSolarEnergy()
    {
        return $this->solarEnergy;
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