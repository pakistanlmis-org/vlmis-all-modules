<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignLqasData
 */
class CampaignLqasData
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $surveyor
     */
    private $surveyor;

    /**
     * @var string $checked
     */
    private $checked;

    /**
     * @var string $unvaccinated
     */
    private $unvaccinated;

    /**
     * @var text $remarks
     */
    private $remarks;

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
     * @var Locations
     */
    private $district;

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
     * Set surveyor
     *
     * @param string $surveyor
     */
    public function setSurveyor($surveyor)
    {
        $this->surveyor = $surveyor;
    }

    /**
     * Get surveyor
     *
     * @return string 
     */
    public function getSurveyor()
    {
        return $this->surveyor;
    }

    /**
     * Set checked
     *
     * @param string $checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    }

    /**
     * Get checked
     *
     * @return string 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set unvaccinated
     *
     * @param string $unvaccinated
     */
    public function setUnvaccinated($unvaccinated)
    {
        $this->unvaccinated = $unvaccinated;
    }

    /**
     * Get unvaccinated
     *
     * @return string 
     */
    public function getUnvaccinated()
    {
        return $this->unvaccinated;
    }

    /**
     * Set remarks
     *
     * @param text $remarks
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
    }

    /**
     * Get remarks
     *
     * @return text 
     */
    public function getRemarks()
    {
        return $this->remarks;
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