<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * HfDataDetailDraft
 */
class HfDataDetailDraft
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var decimal $fixedInsideUcMale
     */
    private $fixedInsideUcMale;

    /**
     * @var decimal $fixedInsideUcFemale
     */
    private $fixedInsideUcFemale;

    /**
     * @var decimal $fixedOutsideUcMale
     */
    private $fixedOutsideUcMale;

    /**
     * @var decimal $fixedOutsideUcFemale
     */
    private $fixedOutsideUcFemale;

    /**
     * @var decimal $referalMale
     */
    private $referalMale;

    /**
     * @var decimal $referalFemale
     */
    private $referalFemale;

    /**
     * @var decimal $outreachMale
     */
    private $outreachMale;

    /**
     * @var decimal $outreachFemale
     */
    private $outreachFemale;

    /**
     * @var decimal $pregnantWomen
     */
    private $pregnantWomen;

    /**
     * @var decimal $nonPregnantWomen
     */
    private $nonPregnantWomen;

    /**
     * @var integer $vaccineScheduleId
     */
    private $vaccineScheduleId;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var decimal $outreachOutsideMale
     */
    private $outreachOutsideMale;

    /**
     * @var decimal $outreachOutsideFemale
     */
    private $outreachOutsideFemale;

    /**
     * @var decimal $outsidePregnantWomen
     */
    private $outsidePregnantWomen;

    /**
     * @var decimal $outsideNonPregnantWomen
     */
    private $outsideNonPregnantWomen;

    /**
     * @var decimal $mobileInsideMale
     */
    private $mobileInsideMale;

    /**
     * @var decimal $mobileInsideFemale
     */
    private $mobileInsideFemale;

    /**
     * @var decimal $lhwInsideMale
     */
    private $lhwInsideMale;

    /**
     * @var decimal $lhwInsideFemale
     */
    private $lhwInsideFemale;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var HfDataMasterDraft
     */
    private $hfDataMaster;

    /**
     * @var ListDetail
     */
    private $ageGroup;


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
     * Set fixedInsideUcMale
     *
     * @param decimal $fixedInsideUcMale
     */
    public function setFixedInsideUcMale($fixedInsideUcMale)
    {
        $this->fixedInsideUcMale = $fixedInsideUcMale;
    }

    /**
     * Get fixedInsideUcMale
     *
     * @return decimal 
     */
    public function getFixedInsideUcMale()
    {
        return $this->fixedInsideUcMale;
    }

    /**
     * Set fixedInsideUcFemale
     *
     * @param decimal $fixedInsideUcFemale
     */
    public function setFixedInsideUcFemale($fixedInsideUcFemale)
    {
        $this->fixedInsideUcFemale = $fixedInsideUcFemale;
    }

    /**
     * Get fixedInsideUcFemale
     *
     * @return decimal 
     */
    public function getFixedInsideUcFemale()
    {
        return $this->fixedInsideUcFemale;
    }

    /**
     * Set fixedOutsideUcMale
     *
     * @param decimal $fixedOutsideUcMale
     */
    public function setFixedOutsideUcMale($fixedOutsideUcMale)
    {
        $this->fixedOutsideUcMale = $fixedOutsideUcMale;
    }

    /**
     * Get fixedOutsideUcMale
     *
     * @return decimal 
     */
    public function getFixedOutsideUcMale()
    {
        return $this->fixedOutsideUcMale;
    }

    /**
     * Set fixedOutsideUcFemale
     *
     * @param decimal $fixedOutsideUcFemale
     */
    public function setFixedOutsideUcFemale($fixedOutsideUcFemale)
    {
        $this->fixedOutsideUcFemale = $fixedOutsideUcFemale;
    }

    /**
     * Get fixedOutsideUcFemale
     *
     * @return decimal 
     */
    public function getFixedOutsideUcFemale()
    {
        return $this->fixedOutsideUcFemale;
    }

    /**
     * Set referalMale
     *
     * @param decimal $referalMale
     */
    public function setReferalMale($referalMale)
    {
        $this->referalMale = $referalMale;
    }

    /**
     * Get referalMale
     *
     * @return decimal 
     */
    public function getReferalMale()
    {
        return $this->referalMale;
    }

    /**
     * Set referalFemale
     *
     * @param decimal $referalFemale
     */
    public function setReferalFemale($referalFemale)
    {
        $this->referalFemale = $referalFemale;
    }

    /**
     * Get referalFemale
     *
     * @return decimal 
     */
    public function getReferalFemale()
    {
        return $this->referalFemale;
    }

    /**
     * Set outreachMale
     *
     * @param decimal $outreachMale
     */
    public function setOutreachMale($outreachMale)
    {
        $this->outreachMale = $outreachMale;
    }

    /**
     * Get outreachMale
     *
     * @return decimal 
     */
    public function getOutreachMale()
    {
        return $this->outreachMale;
    }

    /**
     * Set outreachFemale
     *
     * @param decimal $outreachFemale
     */
    public function setOutreachFemale($outreachFemale)
    {
        $this->outreachFemale = $outreachFemale;
    }

    /**
     * Get outreachFemale
     *
     * @return decimal 
     */
    public function getOutreachFemale()
    {
        return $this->outreachFemale;
    }

    /**
     * Set pregnantWomen
     *
     * @param decimal $pregnantWomen
     */
    public function setPregnantWomen($pregnantWomen)
    {
        $this->pregnantWomen = $pregnantWomen;
    }

    /**
     * Get pregnantWomen
     *
     * @return decimal 
     */
    public function getPregnantWomen()
    {
        return $this->pregnantWomen;
    }

    /**
     * Set nonPregnantWomen
     *
     * @param decimal $nonPregnantWomen
     */
    public function setNonPregnantWomen($nonPregnantWomen)
    {
        $this->nonPregnantWomen = $nonPregnantWomen;
    }

    /**
     * Get nonPregnantWomen
     *
     * @return decimal 
     */
    public function getNonPregnantWomen()
    {
        return $this->nonPregnantWomen;
    }

    /**
     * Set vaccineScheduleId
     *
     * @param integer $vaccineScheduleId
     */
    public function setVaccineScheduleId($vaccineScheduleId)
    {
        $this->vaccineScheduleId = $vaccineScheduleId;
    }

    /**
     * Get vaccineScheduleId
     *
     * @return integer 
     */
    public function getVaccineScheduleId()
    {
        return $this->vaccineScheduleId;
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
     * Set outreachOutsideMale
     *
     * @param decimal $outreachOutsideMale
     */
    public function setOutreachOutsideMale($outreachOutsideMale)
    {
        $this->outreachOutsideMale = $outreachOutsideMale;
    }

    /**
     * Get outreachOutsideMale
     *
     * @return decimal 
     */
    public function getOutreachOutsideMale()
    {
        return $this->outreachOutsideMale;
    }

    /**
     * Set outreachOutsideFemale
     *
     * @param decimal $outreachOutsideFemale
     */
    public function setOutreachOutsideFemale($outreachOutsideFemale)
    {
        $this->outreachOutsideFemale = $outreachOutsideFemale;
    }

    /**
     * Get outreachOutsideFemale
     *
     * @return decimal 
     */
    public function getOutreachOutsideFemale()
    {
        return $this->outreachOutsideFemale;
    }

    /**
     * Set outsidePregnantWomen
     *
     * @param decimal $outsidePregnantWomen
     */
    public function setOutsidePregnantWomen($outsidePregnantWomen)
    {
        $this->outsidePregnantWomen = $outsidePregnantWomen;
    }

    /**
     * Get outsidePregnantWomen
     *
     * @return decimal 
     */
    public function getOutsidePregnantWomen()
    {
        return $this->outsidePregnantWomen;
    }

    /**
     * Set outsideNonPregnantWomen
     *
     * @param decimal $outsideNonPregnantWomen
     */
    public function setOutsideNonPregnantWomen($outsideNonPregnantWomen)
    {
        $this->outsideNonPregnantWomen = $outsideNonPregnantWomen;
    }

    /**
     * Get outsideNonPregnantWomen
     *
     * @return decimal 
     */
    public function getOutsideNonPregnantWomen()
    {
        return $this->outsideNonPregnantWomen;
    }

    /**
     * Set mobileInsideMale
     *
     * @param decimal $mobileInsideMale
     */
    public function setMobileInsideMale($mobileInsideMale)
    {
        $this->mobileInsideMale = $mobileInsideMale;
    }

    /**
     * Get mobileInsideMale
     *
     * @return decimal 
     */
    public function getMobileInsideMale()
    {
        return $this->mobileInsideMale;
    }

    /**
     * Set mobileInsideFemale
     *
     * @param decimal $mobileInsideFemale
     */
    public function setMobileInsideFemale($mobileInsideFemale)
    {
        $this->mobileInsideFemale = $mobileInsideFemale;
    }

    /**
     * Get mobileInsideFemale
     *
     * @return decimal 
     */
    public function getMobileInsideFemale()
    {
        return $this->mobileInsideFemale;
    }

    /**
     * Set lhwInsideMale
     *
     * @param decimal $lhwInsideMale
     */
    public function setLhwInsideMale($lhwInsideMale)
    {
        $this->lhwInsideMale = $lhwInsideMale;
    }

    /**
     * Get lhwInsideMale
     *
     * @return decimal 
     */
    public function getLhwInsideMale()
    {
        return $this->lhwInsideMale;
    }

    /**
     * Set lhwInsideFemale
     *
     * @param decimal $lhwInsideFemale
     */
    public function setLhwInsideFemale($lhwInsideFemale)
    {
        $this->lhwInsideFemale = $lhwInsideFemale;
    }

    /**
     * Get lhwInsideFemale
     *
     * @return decimal 
     */
    public function getLhwInsideFemale()
    {
        return $this->lhwInsideFemale;
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

    /**
     * Set hfDataMaster
     *
     * @param HfDataMasterDraft $hfDataMaster
     */
    public function setHfDataMaster(\HfDataMasterDraft $hfDataMaster)
    {
        $this->hfDataMaster = $hfDataMaster;
    }

    /**
     * Get hfDataMaster
     *
     * @return HfDataMasterDraft 
     */
    public function getHfDataMaster()
    {
        return $this->hfDataMaster;
    }

    /**
     * Set ageGroup
     *
     * @param ListDetail $ageGroup
     */
    public function setAgeGroup(\ListDetail $ageGroup)
    {
        $this->ageGroup = $ageGroup;
    }

    /**
     * Get ageGroup
     *
     * @return ListDetail 
     */
    public function getAgeGroup()
    {
        return $this->ageGroup;
    }
}