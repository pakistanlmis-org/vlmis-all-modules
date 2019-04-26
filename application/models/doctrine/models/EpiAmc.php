<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * EpiAmc
 */
class EpiAmc
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var decimal $amc
     */
    private $amc;

    /**
     * @var integer $amcYear
     */
    private $amcYear;

    /**
     * @var string $remarks
     */
    private $remarks;

    /**
     * @var integer $createdBy
     */
    private $createdBy;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var integer $modifiedBy
     */
    private $modifiedBy;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Warehouses
     */
    private $warehouse;

    /**
     * @var ItemPackSizes
     */
    private $item;


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
     * Set amc
     *
     * @param decimal $amc
     */
    public function setAmc($amc)
    {
        $this->amc = $amc;
    }

    /**
     * Get amc
     *
     * @return decimal 
     */
    public function getAmc()
    {
        return $this->amc;
    }

    /**
     * Set amcYear
     *
     * @param integer $amcYear
     */
    public function setAmcYear($amcYear)
    {
        $this->amcYear = $amcYear;
    }

    /**
     * Get amcYear
     *
     * @return integer 
     */
    public function getAmcYear()
    {
        return $this->amcYear;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }

    /**
     * Get remarks
     *
     * @return string 
     */
    public function getRemarks()
    {
        return $this->remarks;
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
     * Set modifiedBy
     *
     * @param integer $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Get modifiedBy
     *
     * @return integer 
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
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
     * Set item
     *
     * @param ItemPackSizes $item
     */
    public function setItem(\ItemPackSizes $item)
    {
        $this->item = $item;
    }

    /**
     * Get item
     *
     * @return ItemPackSizes 
     */
    public function getItem()
    {
        return $this->item;
    }
}