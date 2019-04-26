<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CampaignReadiness
 */
class CampaignReadiness
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var datetime $vaccineArrivalDate
     */
    private $vaccineArrivalDate;

    /**
     * @var boolean $dcoAttendedMeeting
     */
    private $dcoAttendedMeeting;

    /**
     * @var boolean $edoAttendedMeeting
     */
    private $edoAttendedMeeting;

    /**
     * @var boolean $allMembersAttendedMeeting
     */
    private $allMembersAttendedMeeting;

    /**
     * @var integer $numTallySheets
     */
    private $numTallySheets;

    /**
     * @var integer $numFingerMarkers
     */
    private $numFingerMarkers;

    /**
     * @var datetime $arrivalDateMobilizationMaterial
     */
    private $arrivalDateMobilizationMaterial;

    /**
     * @var datetime $dpecMeetingDate
     */
    private $dpecMeetingDate;

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
     * @var Users
     */
    private $modifiedBy;

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
     * Get pkId
     *
     * @return integer 
     */
    public function getPkId()
    {
        return $this->pkId;
    }

    /**
     * Set vaccineArrivalDate
     *
     * @param datetime $vaccineArrivalDate
     */
    public function setVaccineArrivalDate($vaccineArrivalDate)
    {
        $this->vaccineArrivalDate = $vaccineArrivalDate;
    }

    /**
     * Get vaccineArrivalDate
     *
     * @return datetime 
     */
    public function getVaccineArrivalDate()
    {
        return $this->vaccineArrivalDate;
    }

    /**
     * Set dcoAttendedMeeting
     *
     * @param boolean $dcoAttendedMeeting
     */
    public function setDcoAttendedMeeting($dcoAttendedMeeting)
    {
        $this->dcoAttendedMeeting = $dcoAttendedMeeting;
    }

    /**
     * Get dcoAttendedMeeting
     *
     * @return boolean 
     */
    public function getDcoAttendedMeeting()
    {
        return $this->dcoAttendedMeeting;
    }

    /**
     * Set edoAttendedMeeting
     *
     * @param boolean $edoAttendedMeeting
     */
    public function setEdoAttendedMeeting($edoAttendedMeeting)
    {
        $this->edoAttendedMeeting = $edoAttendedMeeting;
    }

    /**
     * Get edoAttendedMeeting
     *
     * @return boolean 
     */
    public function getEdoAttendedMeeting()
    {
        return $this->edoAttendedMeeting;
    }

    /**
     * Set allMembersAttendedMeeting
     *
     * @param boolean $allMembersAttendedMeeting
     */
    public function setAllMembersAttendedMeeting($allMembersAttendedMeeting)
    {
        $this->allMembersAttendedMeeting = $allMembersAttendedMeeting;
    }

    /**
     * Get allMembersAttendedMeeting
     *
     * @return boolean 
     */
    public function getAllMembersAttendedMeeting()
    {
        return $this->allMembersAttendedMeeting;
    }

    /**
     * Set numTallySheets
     *
     * @param integer $numTallySheets
     */
    public function setNumTallySheets($numTallySheets)
    {
        $this->numTallySheets = $numTallySheets;
    }

    /**
     * Get numTallySheets
     *
     * @return integer 
     */
    public function getNumTallySheets()
    {
        return $this->numTallySheets;
    }

    /**
     * Set numFingerMarkers
     *
     * @param integer $numFingerMarkers
     */
    public function setNumFingerMarkers($numFingerMarkers)
    {
        $this->numFingerMarkers = $numFingerMarkers;
    }

    /**
     * Get numFingerMarkers
     *
     * @return integer 
     */
    public function getNumFingerMarkers()
    {
        return $this->numFingerMarkers;
    }

    /**
     * Set arrivalDateMobilizationMaterial
     *
     * @param datetime $arrivalDateMobilizationMaterial
     */
    public function setArrivalDateMobilizationMaterial($arrivalDateMobilizationMaterial)
    {
        $this->arrivalDateMobilizationMaterial = $arrivalDateMobilizationMaterial;
    }

    /**
     * Get arrivalDateMobilizationMaterial
     *
     * @return datetime 
     */
    public function getArrivalDateMobilizationMaterial()
    {
        return $this->arrivalDateMobilizationMaterial;
    }

    /**
     * Set dpecMeetingDate
     *
     * @param datetime $dpecMeetingDate
     */
    public function setDpecMeetingDate($dpecMeetingDate)
    {
        $this->dpecMeetingDate = $dpecMeetingDate;
    }

    /**
     * Get dpecMeetingDate
     *
     * @return datetime 
     */
    public function getDpecMeetingDate()
    {
        return $this->dpecMeetingDate;
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
}