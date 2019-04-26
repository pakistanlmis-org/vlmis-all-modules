<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * WarehousesDataDraft
 */
class WarehousesDataDraft
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $openingBalance
     */
    private $openingBalance;

    /**
     * @var integer $receivedBalance
     */
    private $receivedBalance;

    /**
     * @var integer $issueBalance
     */
    private $issueBalance;

    /**
     * @var integer $closingBalance
     */
    private $closingBalance;

    /**
     * @var integer $wastages
     */
    private $wastages;

    /**
     * @var integer $vialsUsed
     */
    private $vialsUsed;

    /**
     * @var integer $adjustments
     */
    private $adjustments;

    /**
     * @var datetime $reportingStartDate
     */
    private $reportingStartDate;

    /**
     * @var datetime $nearestExpiry
     */
    private $nearestExpiry;

    /**
     * @var integer $itemPackSizeId
     */
    private $itemPackSizeId;

    /**
     * @var integer $warehouseId
     */
    private $warehouseId;

    /**
     * @var integer $createdBy
     */
    private $createdBy;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;


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
     * Set openingBalance
     *
     * @param integer $openingBalance
     */
    public function setOpeningBalance($openingBalance)
    {
        $this->openingBalance = $openingBalance;
    }

    /**
     * Get openingBalance
     *
     * @return integer 
     */
    public function getOpeningBalance()
    {
        return $this->openingBalance;
    }

    /**
     * Set receivedBalance
     *
     * @param integer $receivedBalance
     */
    public function setReceivedBalance($receivedBalance)
    {
        $this->receivedBalance = $receivedBalance;
    }

    /**
     * Get receivedBalance
     *
     * @return integer 
     */
    public function getReceivedBalance()
    {
        return $this->receivedBalance;
    }

    /**
     * Set issueBalance
     *
     * @param integer $issueBalance
     */
    public function setIssueBalance($issueBalance)
    {
        $this->issueBalance = $issueBalance;
    }

    /**
     * Get issueBalance
     *
     * @return integer 
     */
    public function getIssueBalance()
    {
        return $this->issueBalance;
    }

    /**
     * Set closingBalance
     *
     * @param integer $closingBalance
     */
    public function setClosingBalance($closingBalance)
    {
        $this->closingBalance = $closingBalance;
    }

    /**
     * Get closingBalance
     *
     * @return integer 
     */
    public function getClosingBalance()
    {
        return $this->closingBalance;
    }

    /**
     * Set wastages
     *
     * @param integer $wastages
     */
    public function setWastages($wastages)
    {
        $this->wastages = $wastages;
    }

    /**
     * Get wastages
     *
     * @return integer 
     */
    public function getWastages()
    {
        return $this->wastages;
    }

    /**
     * Set vialsUsed
     *
     * @param integer $vialsUsed
     */
    public function setVialsUsed($vialsUsed)
    {
        $this->vialsUsed = $vialsUsed;
    }

    /**
     * Get vialsUsed
     *
     * @return integer 
     */
    public function getVialsUsed()
    {
        return $this->vialsUsed;
    }

    /**
     * Set adjustments
     *
     * @param integer $adjustments
     */
    public function setAdjustments($adjustments)
    {
        $this->adjustments = $adjustments;
    }

    /**
     * Get adjustments
     *
     * @return integer 
     */
    public function getAdjustments()
    {
        return $this->adjustments;
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
     * Set nearestExpiry
     *
     * @param datetime $nearestExpiry
     */
    public function setNearestExpiry($nearestExpiry)
    {
        $this->nearestExpiry = $nearestExpiry;
    }

    /**
     * Get nearestExpiry
     *
     * @return datetime 
     */
    public function getNearestExpiry()
    {
        return $this->nearestExpiry;
    }

    /**
     * Set itemPackSizeId
     *
     * @param integer $itemPackSizeId
     */
    public function setItemPackSizeId($itemPackSizeId)
    {
        $this->itemPackSizeId = $itemPackSizeId;
    }

    /**
     * Get itemPackSizeId
     *
     * @return integer 
     */
    public function getItemPackSizeId()
    {
        return $this->itemPackSizeId;
    }

    /**
     * Set warehouseId
     *
     * @param integer $warehouseId
     */
    public function setWarehouseId($warehouseId)
    {
        $this->warehouseId = $warehouseId;
    }

    /**
     * Get warehouseId
     *
     * @return integer 
     */
    public function getWarehouseId()
    {
        return $this->warehouseId;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
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
}