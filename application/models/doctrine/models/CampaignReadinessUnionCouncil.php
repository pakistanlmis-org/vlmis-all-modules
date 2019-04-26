<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignReadinessUnionCouncil
 */
class CampaignReadinessUnionCouncil
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $inaccessibleChildren
     */
    private $inaccessibleChildren;

    /**
     * @var string $inaccessibleArea
     */
    private $inaccessibleArea;

    /**
     * @var integer $numberMobileTeams
     */
    private $numberMobileTeams;

    /**
     * @var integer $numberFixedTeams
     */
    private $numberFixedTeams;

    /**
     * @var integer $numberTransitPoints
     */
    private $numberTransitPoints;

    /**
     * @var integer $aicTrained
     */
    private $aicTrained;

    /**
     * @var integer $numberTeamsTrained
     */
    private $numberTeamsTrained;

    /**
     * @var integer $mobilePopulationAreas
     */
    private $mobilePopulationAreas;

    /**
     * @var datetime $upecMeetingDate
     */
    private $upecMeetingDate;

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
    private $unionCouncil;

    /**
     * @var Campaigns
     */
    private $campaign;

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
     * Set inaccessibleChildren
     *
     * @param string $inaccessibleChildren
     */
    public function setInaccessibleChildren($inaccessibleChildren)
    {
        $this->inaccessibleChildren = $inaccessibleChildren;
    }

    /**
     * Get inaccessibleChildren
     *
     * @return string 
     */
    public function getInaccessibleChildren()
    {
        return $this->inaccessibleChildren;
    }

    /**
     * Set inaccessibleArea
     *
     * @param string $inaccessibleArea
     */
    public function setInaccessibleArea($inaccessibleArea)
    {
        $this->inaccessibleArea = $inaccessibleArea;
    }

    /**
     * Get inaccessibleArea
     *
     * @return string 
     */
    public function getInaccessibleArea()
    {
        return $this->inaccessibleArea;
    }

    /**
     * Set numberMobileTeams
     *
     * @param integer $numberMobileTeams
     */
    public function setNumberMobileTeams($numberMobileTeams)
    {
        $this->numberMobileTeams = $numberMobileTeams;
    }

    /**
     * Get numberMobileTeams
     *
     * @return integer 
     */
    public function getNumberMobileTeams()
    {
        return $this->numberMobileTeams;
    }

    /**
     * Set numberFixedTeams
     *
     * @param integer $numberFixedTeams
     */
    public function setNumberFixedTeams($numberFixedTeams)
    {
        $this->numberFixedTeams = $numberFixedTeams;
    }

    /**
     * Get numberFixedTeams
     *
     * @return integer 
     */
    public function getNumberFixedTeams()
    {
        return $this->numberFixedTeams;
    }

    /**
     * Set numberTransitPoints
     *
     * @param integer $numberTransitPoints
     */
    public function setNumberTransitPoints($numberTransitPoints)
    {
        $this->numberTransitPoints = $numberTransitPoints;
    }

    /**
     * Get numberTransitPoints
     *
     * @return integer 
     */
    public function getNumberTransitPoints()
    {
        return $this->numberTransitPoints;
    }

    /**
     * Set aicTrained
     *
     * @param integer $aicTrained
     */
    public function setAicTrained($aicTrained)
    {
        $this->aicTrained = $aicTrained;
    }

    /**
     * Get aicTrained
     *
     * @return integer 
     */
    public function getAicTrained()
    {
        return $this->aicTrained;
    }

    /**
     * Set numberTeamsTrained
     *
     * @param integer $numberTeamsTrained
     */
    public function setNumberTeamsTrained($numberTeamsTrained)
    {
        $this->numberTeamsTrained = $numberTeamsTrained;
    }

    /**
     * Get numberTeamsTrained
     *
     * @return integer 
     */
    public function getNumberTeamsTrained()
    {
        return $this->numberTeamsTrained;
    }

    /**
     * Set mobilePopulationAreas
     *
     * @param integer $mobilePopulationAreas
     */
    public function setMobilePopulationAreas($mobilePopulationAreas)
    {
        $this->mobilePopulationAreas = $mobilePopulationAreas;
    }

    /**
     * Get mobilePopulationAreas
     *
     * @return integer 
     */
    public function getMobilePopulationAreas()
    {
        return $this->mobilePopulationAreas;
    }

    /**
     * Set upecMeetingDate
     *
     * @param datetime $upecMeetingDate
     */
    public function setUpecMeetingDate($upecMeetingDate)
    {
        $this->upecMeetingDate = $upecMeetingDate;
    }

    /**
     * Get upecMeetingDate
     *
     * @return datetime 
     */
    public function getUpecMeetingDate()
    {
        return $this->upecMeetingDate;
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
     * Set unionCouncil
     *
     * @param Warehouses $unionCouncil
     */
    public function setUnionCouncil(\Warehouses $unionCouncil)
    {
        $this->unionCouncil = $unionCouncil;
    }

    /**
     * Get unionCouncil
     *
     * @return Warehouses 
     */
    public function getUnionCouncil()
    {
        return $this->unionCouncil;
    }

    /**
     * Set campaign
     *
     * @param Campaigns $campaign
     */
    public function setCampaign(\Campaigns $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get campaign
     *
     * @return Campaigns 
     */
    public function getCampaign()
    {
        return $this->campaign;
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