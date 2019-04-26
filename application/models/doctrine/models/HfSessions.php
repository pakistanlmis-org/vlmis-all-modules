<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * HfSessions
 */
class HfSessions
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $fixedPlannedSessions
     */
    private $fixedPlannedSessions;

    /**
     * @var integer $fixedActuallyHeldSessions
     */
    private $fixedActuallyHeldSessions;

    /**
     * @var integer $outreachPlannedSessions
     */
    private $outreachPlannedSessions;

    /**
     * @var integer $outreachActuallyHeldSessions
     */
    private $outreachActuallyHeldSessions;

    /**
     * @var integer $fullyImmunizedChildren
     */
    private $fullyImmunizedChildren;
    
    /**
     * @var datetime $reportingStartDate
     */
    private $reportingStartDate;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Warehouses
     */
    private $warehouse;

    /**
     * @var ListDetail
     */
    private $warehouseStatus;

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
     * Set fixedPlannedSessions
     *
     * @param integer $fixedPlannedSessions
     */
    public function setFixedPlannedSessions($fixedPlannedSessions)
    {
        $this->fixedPlannedSessions = $fixedPlannedSessions;
    }

    /**
     * Get fixedPlannedSessions
     *
     * @return integer 
     */
    public function getFixedPlannedSessions()
    {
        return $this->fixedPlannedSessions;
    }

    /**
     * Set fixedActuallyHeldSessions
     *
     * @param integer $fixedActuallyHeldSessions
     */
    public function setFixedActuallyHeldSessions($fixedActuallyHeldSessions)
    {
        $this->fixedActuallyHeldSessions = $fixedActuallyHeldSessions;
    }

    /**
     * Get fixedActuallyHeldSessions
     *
     * @return integer 
     */
    public function getFixedActuallyHeldSessions()
    {
        return $this->fixedActuallyHeldSessions;
    }

    /**
     * Set outreachPlannedSessions
     *
     * @param integer $outreachPlannedSessions
     */
    public function setOutreachPlannedSessions($outreachPlannedSessions)
    {
        $this->outreachPlannedSessions = $outreachPlannedSessions;
    }

    /**
     * Get outreachPlannedSessions
     *
     * @return integer 
     */
    public function getOutreachPlannedSessions()
    {
        return $this->outreachPlannedSessions;
    }

    /**
     * Set outreachActuallyHeldSessions
     *
     * @param integer $outreachActuallyHeldSessions
     */
    public function setOutreachActuallyHeldSessions($outreachActuallyHeldSessions)
    {
        $this->outreachActuallyHeldSessions = $outreachActuallyHeldSessions;
    }

    /**
     * Get outreachActuallyHeldSessions
     *
     * @return integer 
     */
    public function getOutreachActuallyHeldSessions()
    {
        return $this->outreachActuallyHeldSessions;
    }

    /**
     * Set fullyImmunizedChildren
     *
     * @param integer $fullyImmunizedChildren
     */
    public function setFullyImmunizedChildren($fullyImmunizedChildren)
    {
        $this->fullyImmunizedChildren = $fullyImmunizedChildren;
    }

    /**
     * Get fullyImmunizedChildren
     *
     * @return integer 
     */
    public function getFullyImmunizedChildren()
    {
        return $this->fullyImmunizedChildren;
    }
    
    /**
     * Set reportingStartDate
     *
     * @param datetime $reportingStartDate
     */
    public function setReportingStartDate($reportingStartDate)
    {
        $this->reportingStartDate = $reportingStartDate;
    }

    /**
     * Get reportingStartDate
     *
     * @return datetime 
     */
    public function getReportingStartDate()
    {
        return $this->reportingStartDate;
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
     * Set warehouseStatus
     *
     * @param ListDetail $warehouseStatus
     */
    public function setWarehouseStatus(\ListDetail $warehouseStatus)
    {
        $this->warehouseStatus = $warehouseStatus;
    }

    /**
     * Get warehouseStatus
     *
     * @return ListDetail 
     */
    public function getWarehouseStatus()
    {
        return $this->warehouseStatus;
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