<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ItemPackSizes
 */
class ItemPackSizes
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $itemName
     */
    private $itemName;

    /**
     * @var text $description
     */
    private $description;

    /**
     * @var integer $numberOfDoses
     */
    private $numberOfDoses;

    /**
     * @var boolean $status
     */
    private $status;

    /**
     * @var integer $listRank
     */
    private $listRank;

    /**
     * @var integer $multiplier
     */
    private $multiplier;

    /**
     * @var float $wastageRateAllowed
     */
    private $wastageRateAllowed;

    /**
     * @var string $color
     */
    private $color;

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
     * @var ItemCategories
     */
    private $itemCategory;

    /**
     * @var ItemUnits
     */
    private $itemUnit;

    /**
     * @var Items
     */
    private $item;

    /**
     * @var VvmGroups
     */
    private $vvmGroup;

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
     * Set itemName
     *
     * @param string $itemName
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;
    }

    /**
     * Get itemName
     *
     * @return string 
     */
    public function getItemName()
    {
        return $this->itemName;
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
     * Set numberOfDoses
     *
     * @param integer $numberOfDoses
     */
    public function setNumberOfDoses($numberOfDoses)
    {
        $this->numberOfDoses = $numberOfDoses;
    }

    /**
     * Get numberOfDoses
     *
     * @return integer 
     */
    public function getNumberOfDoses()
    {
        return $this->numberOfDoses;
    }

    /**
     * Set status
     *
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set listRank
     *
     * @param integer $listRank
     */
    public function setListRank($listRank)
    {
        $this->listRank = $listRank;
    }

    /**
     * Get listRank
     *
     * @return integer 
     */
    public function getListRank()
    {
        return $this->listRank;
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
     * Set color
     *
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
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
     * Set itemCategory
     *
     * @param ItemCategories $itemCategory
     */
    public function setItemCategory(\ItemCategories $itemCategory)
    {
        $this->itemCategory = $itemCategory;
    }

    /**
     * Get itemCategory
     *
     * @return ItemCategories 
     */
    public function getItemCategory()
    {
        return $this->itemCategory;
    }

    /**
     * Set itemUnit
     *
     * @param ItemUnits $itemUnit
     */
    public function setItemUnit(\ItemUnits $itemUnit)
    {
        $this->itemUnit = $itemUnit;
    }

    /**
     * Get itemUnit
     *
     * @return ItemUnits 
     */
    public function getItemUnit()
    {
        return $this->itemUnit;
    }

    /**
     * Set item
     *
     * @param Items $item
     */
    public function setItem(\Items $item)
    {
        $this->item = $item;
    }

    /**
     * Get item
     *
     * @return Items 
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set vvmGroup
     *
     * @param VvmGroups $vvmGroup
     */
    public function setVvmGroup(\VvmGroups $vvmGroup)
    {
        $this->vvmGroup = $vvmGroup;
    }

    /**
     * Get vvmGroup
     *
     * @return VvmGroups 
     */
    public function getVvmGroup()
    {
        return $this->vvmGroup;
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