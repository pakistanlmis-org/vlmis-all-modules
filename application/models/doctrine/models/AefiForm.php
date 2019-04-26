<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * AefiForm
 */
class AefiForm {

    /**
     * @var integer $pkId
     */
    private $pkId;
    private $designation;

    /**
     * @var datetime $date
     */
    private $date;

    /**
     * @var datetime $dateNotification
     */
    private $dateNotification;

    /**
     * @var integer $districtId
     */
    private $districtId;
    private $districtId1;
    private $caseEpi;
    private $otherCaseEpi;

    /**
     * @var integer $tehsilId
     */
    private $tehsilId;
    private $tehsilId1;

    /**
     * @var integer $ucId
     */
    private $ucId;
    private $ucId1;

    /**
     * @var integer $hfId
     */
    private $hfId;
    private $hfId1;

    /**
     * @var string $hfName
     */
    private $hfName;

    /**
     * @var integer $typeCase
     */
    private $typeCase;
    private $vaccinatorName;

    /**
     * @var integer $gender
     */
    private $gender;

    /**
     * @var datetime $dob
     */
    private $dob;
    private $expiryDate;

    /**
     * @var integer $ageInMonths
     */
    private $ageInMonths;

    /**
     * @var datetime $dateVaccineGiven
     */
    private $dateVaccineGiven;

    /**
     * @var datetime $dateAefiOnset
     */
    private $dateAefiOnset;

    /**
     * @var datetime $dateOfInvestigation
     */
    private $dateOfInvestigation;

    /**
     * @var integer $aefi
     */
    private $aefi;

    /**
     * @var string $aefiOther
     */
    private $aefiOther;

    /**
     * @var integer $hospitalization
     */
    private $hospitalization;

    /**
     * @var integer $death
     */
    private $death;

    /**
     * @var integer $vaccineAntigen
     */
    private $vaccineAntigen;

    /**
     * @var integer $batchId
     */
    private $batchId;

    /**
     * @var string $batchNumber
     */
    private $batchNumber;

    /**
     * @var string $week
     */
    private $week;

    /**
     * @var integer $reportingStatus
     */
    private $reportingStatus;

    /**
     * @var string $childName
     */
    private $childName;

    /**
     * @var string $fatherName
     */
    private $fatherName;

    /**
     * @var string $address1
     */
    private $address1;

    /**
     * @var string $address2
     */
    private $address2;

    /**
     * @var string $addressVillage
     */
    private $addressVillage;

    /**
     * @var string $contactNumber
     */
    private $contactNumber;

    /**
     * @var integer $ageUnit
     */
    private $ageUnit;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var integer $createdBy
     */
    private $createdBy;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var integer $modifiedBy
     */
    private $modifiedBy;

    /**
     * Get pkId
     *
     * @return integer 
     */
    public function getPkId() {
        return $this->pkId;
    }

    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param datetime $dateNotification
     */
    public function setDateNotification($dateNotification) {
        $this->dateNotification = $dateNotification;
    }

    /**
     * Get dateNotification
     *
     * @return datetime 
     */
    public function getDateNotification() {
        return $this->dateNotification;
    }

    /**
     * Set districtId
     *
     * @param integer $districtId
     */
    public function setDistrictId($districtId) {
        $this->districtId = $districtId;
    }

    /**
     * Get districtId
     *
     * @return integer 
     */
    public function getDistrictId() {
        return $this->districtId;
    }

    public function setDesignation($designation) {
        $this->designation = $designation;
    }

    /**
     * Get districtId
     *
     * @return integer 
     */
    public function getDesignation() {
        return $this->designation;
    }

    public function setVaccinatorName($vaccinatorName) {
        $this->vaccinatorName = $vaccinatorName;
    }

    /**
     * Get districtId
     *
     * @return integer 
     */
    public function getVaccinatorName() {
        return $this->vaccinatorName;
    }

    public function setCaseEpi($caseEpi) {
        $this->caseEpi = $caseEpi;
    }

    /**
     * Get districtId
     *
     * @return integer 
     */
    public function getCaseEpi() {
        return $this->caseEpi;
    }

    /**
     * Set tehsilId
     *
     * @param integer $tehsilId
     */
    public function setTehsilId($tehsilId) {
        $this->tehsilId = $tehsilId;
    }

    /**
     * Get tehsilId
     *
     * @return integer 
     */
    public function getTehsilId() {
        return $this->tehsilId;
    }

    /**
     * Set ucId
     *
     * @param integer $ucId
     */
    public function setUcId($ucId) {
        $this->ucId = $ucId;
    }

    /**
     * Get ucId
     *
     * @return integer 
     */
    public function getUcId() {
        return $this->ucId;
    }

    public function setOtherCaseEpi($otherCaseEpi) {
        $this->otherCaseEpi = $otherCaseEpi;
    }

    /**
     * Get ucId
     *
     * @return integer 
     */
    public function getOtherCaseEpi() {
        return $this->otherCaseEpi;
    }

    /**
     * Set hfId
     *
     * @param integer $hfId
     */
    public function setHfId($hfId) {
        $this->hfId = $hfId;
    }

    /**
     * Get hfId
     *
     * @return integer 
     */
    public function getHfId() {
        return $this->hfId;
    }

    public function setDistrictId1($districtId1) {
        $this->districtId1 = $districtId1;
    }

    /**
     * Get districtId
     *
     * @return integer 
     */
    public function getDistrictId1() {
        return $this->districtId1;
    }

    /**
     * Set tehsilId
     *
     * @param integer $tehsilId
     */
    public function setTehsilId1($tehsilId1) {
        $this->tehsilId1 = $tehsilId1;
    }

    /**
     * Get tehsilId
     *
     * @return integer 
     */
    public function getTehsilId1() {
        return $this->tehsilId1;
    }

    /**
     * Set ucId
     *
     * @param integer $ucId
     */
    public function setUcId1($ucId1) {
        $this->ucId1 = $ucId1;
    }

    /**
     * Get ucId
     *
     * @return integer 
     */
    public function getUcId1() {
        return $this->ucId1;
    }

    /**
     * Set hfId
     *
     * @param integer $hfId
     */
    public function setHfId1($hfId1) {
        $this->hfId1 = $hfId1;
    }

    /**
     * Get hfId
     *
     * @return integer 
     */
    public function getHfId1() {
        return $this->hfId1;
    }

    /**
     * Set hfName
     *
     * @param string $hfName
     */
    public function setHfName($hfName) {
        $this->hfName = $hfName;
    }

    /**
     * Get hfName
     *
     * @return string 
     */
    public function getHfName() {
        return $this->hfName;
    }

    /**
     * Set typeCase
     *
     * @param integer $typeCase
     */
    public function setTypeCase($typeCase) {
        $this->typeCase = $typeCase;
    }

    /**
     * Get typeCase
     *
     * @return integer 
     */
    public function getTypeCase() {
        return $this->typeCase;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set dob
     *
     * @param datetime $dob
     */
    public function setDob($dob) {
        $this->dob = $dob;
    }

    /**
     * Get dob
     *
     * @return datetime 
     */
    public function getDob() {
        return $this->dob;
    }

    public function setExpiryDate($expiryDate) {
        $this->expiryDate = $expiryDate;
    }

    /**
     * Get dob
     *
     * @return datetime 
     */
    public function getExpiryDate() {
        return $this->expiryDate;
    }

    /**
     * Set ageInMonths
     *
     * @param integer $ageInMonths
     */
    public function setAgeInMonths($ageInMonths) {
        $this->ageInMonths = $ageInMonths;
    }

    /**
     * Get ageInMonths
     *
     * @return integer 
     */
    public function getAgeInMonths() {
        return $this->ageInMonths;
    }

    /**
     * Set dateVaccineGiven
     *
     * @param datetime $dateVaccineGiven
     */
    public function setDateVaccineGiven($dateVaccineGiven) {
        $this->dateVaccineGiven = $dateVaccineGiven;
    }

    /**
     * Get dateVaccineGiven
     *
     * @return datetime 
     */
    public function getDateVaccineGiven() {
        return $this->dateVaccineGiven;
    }

    /**
     * Set dateAefiOnset
     *
     * @param datetime $dateAefiOnset
     */
    public function setDateAefiOnset($dateAefiOnset) {
        $this->dateAefiOnset = $dateAefiOnset;
    }

    /**
     * Get dateAefiOnset
     *
     * @return datetime 
     */
    public function getDateAefiOnset() {
        return $this->dateAefiOnset;
    }

    /**
     * Set dateOfInvestigation
     *
     * @param datetime $dateOfInvestigation
     */
    public function setDateOfInvestigation($dateOfInvestigation) {
        $this->dateOfInvestigation = $dateOfInvestigation;
    }

    /**
     * Get dateOfInvestigation
     *
     * @return datetime 
     */
    public function getDateOfInvestigation() {
        return $this->dateOfInvestigation;
    }

    /**
     * Set aefi
     *
     * @param integer $aefi
     */
    public function setAefi($aefi) {
        $this->aefi = $aefi;
    }

    /**
     * Get aefi
     *
     * @return integer 
     */
    public function getAefi() {
        return $this->aefi;
    }

    /**
     * Set aefiOther
     *
     * @param string $aefiOther
     */
    public function setAefiOther($aefiOther) {
        $this->aefiOther = $aefiOther;
    }

    /**
     * Get aefiOther
     *
     * @return string 
     */
    public function getAefiOther() {
        return $this->aefiOther;
    }

    /**
     * Set hospitalization
     *
     * @param integer $hospitalization
     */
    public function setHospitalization($hospitalization) {
        $this->hospitalization = $hospitalization;
    }

    /**
     * Get hospitalization
     *
     * @return integer 
     */
    public function getHospitalization() {
        return $this->hospitalization;
    }

    /**
     * Set death
     *
     * @param integer $death
     */
    public function setDeath($death) {
        $this->death = $death;
    }

    /**
     * Get death
     *
     * @return integer 
     */
    public function getDeath() {
        return $this->death;
    }

    /**
     * Set vaccineAntigen
     *
     * @param integer $vaccineAntigen
     */
    public function setVaccineAntigen($vaccineAntigen) {
        $this->vaccineAntigen = $vaccineAntigen;
    }

    /**
     * Get vaccineAntigen
     *
     * @return integer 
     */
    public function getVaccineAntigen() {
        return $this->vaccineAntigen;
    }

    /**
     * Set batchId
     *
     * @param integer $batchId
     */
    public function setBatchId($batchId) {
        $this->batchId = $batchId;
    }

    /**
     * Get batchId
     *
     * @return integer 
     */
    public function getBatchId() {
        return $this->batchId;
    }

    /**
     * Set batchNumber
     *
     * @param string $batchNumber
     */
    public function setBatchNumber($batchNumber) {
        $this->batchNumber = $batchNumber;
    }

    /**
     * Get batchNumber
     *
     * @return string 
     */
    public function getBatchNumber() {
        return $this->batchNumber;
    }

    /**
     * Set week
     *
     * @param string $week
     */
    public function setWeek($week) {
        $this->week = $week;
    }

    /**
     * Get week
     *
     * @return string 
     */
    public function getWeek() {
        return $this->week;
    }

    /**
     * Set reportingStatus
     *
     * @param integer $reportingStatus
     */
    public function setReportingStatus($reportingStatus) {
        $this->reportingStatus = $reportingStatus;
    }

    /**
     * Get reportingStatus
     *
     * @return integer 
     */
    public function getReportingStatus() {
        return $this->reportingStatus;
    }

    /**
     * Set childName
     *
     * @param string $childName
     */
    public function setChildName($childName) {
        $this->childName = $childName;
    }

    /**
     * Get childName
     *
     * @return string 
     */
    public function getChildName() {
        return $this->childName;
    }

    /**
     * Set fatherName
     *
     * @param string $fatherName
     */
    public function setFatherName($fatherName) {
        $this->fatherName = $fatherName;
    }

    /**
     * Get fatherName
     *
     * @return string 
     */
    public function getFatherName() {
        return $this->fatherName;
    }

    /**
     * Set address1
     *
     * @param string $address1
     */
    public function setAddress1($address1) {
        $this->address1 = $address1;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1() {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     */
    public function setAddress2($address2) {
        $this->address2 = $address2;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2() {
        return $this->address2;
    }

    /**
     * Set addressVillage
     *
     * @param string $addressVillage
     */
    public function setAddressVillage($addressVillage) {
        $this->addressVillage = $addressVillage;
    }

    /**
     * Get addressVillage
     *
     * @return string 
     */
    public function getAddressVillage() {
        return $this->addressVillage;
    }

    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     */
    public function setContactNumber($contactNumber) {
        $this->contactNumber = $contactNumber;
    }

    /**
     * Get contactNumber
     *
     * @return string 
     */
    public function getContactNumber() {
        return $this->contactNumber;
    }

    /**
     * Set ageUnit
     *
     * @param integer $ageUnit
     */
    public function setAgeUnit($ageUnit) {
        $this->ageUnit = $ageUnit;
    }

    /**
     * Get ageUnit
     *
     * @return integer 
     */
    public function getAgeUnit() {
        return $this->ageUnit;
    }

    /**
     * Set createdDate
     *
     * @param datetime $createdDate
     */
    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return datetime 
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     */
    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy() {
        return $this->createdBy;
    }

    /**
     * Set modifiedDate
     *
     * @param datetime $modifiedDate
     */
    public function setModifiedDate($modifiedDate) {
        $this->modifiedDate = $modifiedDate;
    }

    /**
     * Get modifiedDate
     *
     * @return datetime 
     */
    public function getModifiedDate() {
        return $this->modifiedDate;
    }

    /**
     * Set modifiedBy
     *
     * @param integer $modifiedBy
     */
    public function setModifiedBy($modifiedBy) {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Get modifiedBy
     *
     * @return integer 
     */
    public function getModifiedBy() {
        return $this->modifiedBy;
    }

}
