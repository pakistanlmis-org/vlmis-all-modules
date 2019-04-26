<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Items
 */
class Items
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var text $description
     */
    private $description;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var float $packVolume
     */
    private $packVolume;

    /**
     * @var integer $dosesPerYear
     */
    private $dosesPerYear;

    /**
     * @var float $packDiluentVolume
     */
    private $packDiluentVolume;

    /**
     * @var integer $targetPopulationFactor
     */
    private $targetPopulationFactor;

    /**
     * @var integer $itemCategoryId
     */
    private $itemCategoryId;

    /**
     * @var integer $multiplier
     */
    private $multiplier;

    /**
     * @var float $wastageRateAllowed
     */
    private $wastageRateAllowed;

    /**
     * @var float $populationPercentIncreasePerYear
     */
    private $populationPercentIncreasePerYear;

    /**
     * @var float $childSurvivingPercentPerYear
     */
    private $childSurvivingPercentPerYear;

    /**
     * @var float $childSurvivingPercentTillSecondYear
     */
    private $childSurvivingPercentTillSecondYear;

    /**
     * @var Users
     */
    private $modifiedBy;

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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set packVolume
     *
     * @param float $packVolume
     */
    public function setPackVolume($packVolume)
    {
        $this->packVolume = $packVolume;
    }

    /**
     * Get packVolume
     *
     * @return float 
     */
    public function getPackVolume()
    {
        return $this->packVolume;
    }

    /**
     * Set dosesPerYear
     *
     * @param integer $dosesPerYear
     */
    public function setDosesPerYear($dosesPerYear)
    {
        $this->dosesPerYear = $dosesPerYear;
    }

    /**
     * Get dosesPerYear
     *
     * @return integer 
     */
    public function getDosesPerYear()
    {
        return $this->dosesPerYear;
    }

    /**
     * Set packDiluentVolume
     *
     * @param float $packDiluentVolume
     */
    public function setPackDiluentVolume($packDiluentVolume)
    {
        $this->packDiluentVolume = $packDiluentVolume;
    }

    /**
     * Get packDiluentVolume
     *
     * @return float 
     */
    public function getPackDiluentVolume()
    {
        return $this->packDiluentVolume;
    }

    /**
     * Set targetPopulationFactor
     *
     * @param integer $targetPopulationFactor
     */
    public function setTargetPopulationFactor($targetPopulationFactor)
    {
        $this->targetPopulationFactor = $targetPopulationFactor;
    }

    /**
     * Get targetPopulationFactor
     *
     * @return integer 
     */
    public function getTargetPopulationFactor()
    {
        return $this->targetPopulationFactor;
    }

    /**
     * Set itemCategoryId
     *
     * @param integer $itemCategoryId
     */
    public function setItemCategoryId($itemCategoryId)
    {
        $this->itemCategoryId = $itemCategoryId;
    }

    /**
     * Get itemCategoryId
     *
     * @return integer 
     */
    public function getItemCategoryId()
    {
        return $this->itemCategoryId;
    }

    /**
     * Set multiplier
     *
     * @param integer $multiplier
     */
    public function setMultiplier($multiplier)
    {
        $this->multiplier = $multiplier;
    }

    /**
     * Get multiplier
     *
     * @return integer 
     */
    public function getMultiplier()
    {
        return $this->multiplier;
    }

    /**
     * Set wastageRateAllowed
     *
     * @param float $wastageRateAllowed
     */
    public function setWastageRateAllowed($wastageRateAllowed)
    {
        $this->wastageRateAllowed = $wastageRateAllowed;
    }

    /**
     * Get wastageRateAllowed
     *
     * @return float 
     */
    public function getWastageRateAllowed()
    {
        return $this->wastageRateAllowed;
    }

    /**
     * Set populationPercentIncreasePerYear
     *
     * @param float $populationPercentIncreasePerYear
     */
    public function setPopulationPercentIncreasePerYear($populationPercentIncreasePerYear)
    {
        $this->populationPercentIncreasePerYear = $populationPercentIncreasePerYear;
    }

    /**
     * Get populationPercentIncreasePerYear
     *
     * @return float 
     */
    public function getPopulationPercentIncreasePerYear()
    {
        return $this->populationPercentIncreasePerYear;
    }

    /**
     * Set childSurvivingPercentPerYear
     *
     * @param float $childSurvivingPercentPerYear
     */
    public function setChildSurvivingPercentPerYear($childSurvivingPercentPerYear)
    {
        $this->childSurvivingPercentPerYear = $childSurvivingPercentPerYear;
    }

    /**
     * Get childSurvivingPercentPerYear
     *
     * @return float 
     */
    public function getChildSurvivingPercentPerYear()
    {
        return $this->childSurvivingPercentPerYear;
    }

    /**
     * Set childSurvivingPercentTillSecondYear
     *
     * @param float $childSurvivingPercentTillSecondYear
     */
    public function setChildSurvivingPercentTillSecondYear($childSurvivingPercentTillSecondYear)
    {
        $this->childSurvivingPercentTillSecondYear = $childSurvivingPercentTillSecondYear;
    }

    /**
     * Get childSurvivingPercentTillSecondYear
     *
     * @return float 
     */
    public function getChildSurvivingPercentTillSecondYear()
    {
        return $this->childSurvivingPercentTillSecondYear;
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