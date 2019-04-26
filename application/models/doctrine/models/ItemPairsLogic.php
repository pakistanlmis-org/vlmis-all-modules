<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ItemPairsLogic
 */
class ItemPairsLogic
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $quantity
     */
    private $quantity;

    /**
     * @var integer $groupId
     */
    private $groupId;

    /**
     * @var integer $rank
     */
    private $rank;

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
     * @var ItemUnits
     */
    private $unitOfQuantity;

    /**
     * @var ItemPackSizes
     */
    private $item;

    /**
     * @var ItemPackSizes
     */
    private $itemPair;

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
     * Set quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * Get groupId
     *
     * @return integer 
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
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
     * Set unitOfQuantity
     *
     * @param ItemUnits $unitOfQuantity
     */
    public function setUnitOfQuantity(\ItemUnits $unitOfQuantity)
    {
        $this->unitOfQuantity = $unitOfQuantity;
    }

    /**
     * Get unitOfQuantity
     *
     * @return ItemUnits 
     */
    public function getUnitOfQuantity()
    {
        return $this->unitOfQuantity;
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

    /**
     * Set itemPair
     *
     * @param ItemPackSizes $itemPair
     */
    public function setItemPair(\ItemPackSizes $itemPair)
    {
        $this->itemPair = $itemPair;
    }

    /**
     * Get itemPair
     *
     * @return ItemPackSizes 
     */
    public function getItemPair()
    {
        return $this->itemPair;
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