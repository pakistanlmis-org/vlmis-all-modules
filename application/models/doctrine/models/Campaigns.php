<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Campaigns
 */
class Campaigns
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $campaignName
     */
    private $campaignName;

    /**
     * @var datetime $dateFrom
     */
    private $dateFrom;

    /**
     * @var datetime $dateTo
     */
    private $dateTo;

    /**
     * @var integer $catchUpDays
     */
    private $catchUpDays;

    /**
     * @var boolean $isClosed
     */
    private $isClosed;

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
     * @var CampaignTypes
     */
    private $campaignType;

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
     * Set campaignName
     *
     * @param string $campaignName
     */
    public function setCampaignName($campaignName)
    {
        $this->campaignName = $campaignName;
    }

    /**
     * Get campaignName
     *
     * @return string 
     */
    public function getCampaignName()
    {
        return $this->campaignName;
    }

    /**
     * Set dateFrom
     *
     * @param datetime $dateFrom
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * Get dateFrom
     *
     * @return datetime 
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param datetime $dateTo
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }

    /**
     * Get dateTo
     *
     * @return datetime 
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Set catchUpDays
     *
     * @param integer $catchUpDays
     */
    public function setCatchUpDays($catchUpDays)
    {
        $this->catchUpDays = $catchUpDays;
    }

    /**
     * Get catchUpDays
     *
     * @return integer 
     */
    public function getCatchUpDays()
    {
        return $this->catchUpDays;
    }

    /**
     * Set isClosed
     *
     * @param boolean $isClosed
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    }

    /**
     * Get isClosed
     *
     * @return boolean 
     */
    public function getIsClosed()
    {
        return $this->isClosed;
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
     * Set campaignType
     *
     * @param CampaignTypes $campaignType
     */
    public function setCampaignType(\CampaignTypes $campaignType)
    {
        $this->campaignType = $campaignType;
    }

    /**
     * Get campaignType
     *
     * @return CampaignTypes 
     */
    public function getCampaignType()
    {
        return $this->campaignType;
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