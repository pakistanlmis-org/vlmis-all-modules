<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * VpdForm
 */
class VpdFormDraft {

    /**
     * @var integer $pkId
     */
    private $pkId;
private $placeOfBirth;
  private $attendee;
    /**
     * @var datetime $date
     */
    private $date;

    /**
     * @var string $week
     */
    private $week;

    
    /**
     * @var integer $districtId
     */
    private $districtId;

    /**
     * @var integer $tehsilId
     */
    private $tehsilId;

    /**
     * @var integer $ucId
     */
    private $ucId;

    /**
     * @var integer $hfId
     */
    private $hfId;

    /**
     * @var string $hfName
     */
    private $hfName;

     /**
     * @var integer $fromUcId
     */
    private $fromUcId;
    
    /**
     * @var integer $typeCase
     */
    private $typeCase;

    /**
     * @var string $epidNumber
     */
    private $epidNumber;

    /**
     * @var string $clinicalPresentation
     */
    private $clinicalPresentation;

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
     * @var integer $crossNotification
     */
    private $crossNotification;

    /**
     * @var datetime $dob
     */
    private $dob;

    /**
     * @var integer $age
     */
    private $age;
    
     /**
     * @var integer $ageUnit
     */
    private $ageUnit;

    /**
     * @var integer $gender
     */
    private $gender;

    /**
     * @var date $dateOnset
     */
    private $dateOnset;

    /**
     * @var date $dateNotification
     */
    private $dateNotification;

    /**
     * @var date $dateInvestigation
     */
    private $dateInvestigation;

    /**
     * @var integer $specificDoseReceived
     */
    private $specificDoseReceived;

    /**
     * @var datetime $dateLastDoseReceived
     */
    private $dateLastDoseReceived;

    /**
     * @var integer $specimenCollection
     */
    private $specimenCollection;

    /**
     * @var datetime $dateSpecimenSent
     */
    private $dateSpecimenSent;

     /**
     * @var datetime $dateSpecimenSent1
     */
    private $dateSpecimenSent1;

    /**
     * @var string $signSymptoms
     */
    private $signSymptoms;

    /**
     * @var integer $outcome
     */
    private $outcome;

    /**
     * @var integer $labResult
     */
    private $labResult;
    
    
     /**
     * @var integer $reportingStatus
     */
    private $reportingStatus;

    /**
     * @var integer $finalClassification
     */
    private $finalClassification;

    /**
     * @var datetime $stoolSampleDate1
     */
    private $stoolSampleDate1;

    /**
     * @var datetime $stoolSampleDate2
     */
    private $stoolSampleDate2;

    /**
     * @var datetime $bloodSampleDate
     */
    private $bloodSampleDate;

    /**
     * @var string $bloodSampleMonth
     */
    private $bloodSampleMonth;

    /**
     * @var integer $numberOfMonths
     */
    private $numberOfMonths;
    
     /**
     * @var integer $isTheChildProtected
     */
    private $isTheChildProtected;
    
     /**
     * @var integer $hasChildReceivedOpv
     */
    private $hasChildReceivedOpv;
    
    /**
     * @var datetime $patientVisitedDate
     */
    private $specimenCollectionDate;
    
     /**
     * @var datetime $dateOfLabResulted
     */
    private $dateOfLabResulted;
    
    /**
     * @var datetime $patientVisitedDate
     */
    private $patientVisitedDate;

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
     * Set fromUcId
     *
     * @param integer $fromUcId
     */
    public function setFromUcId($fromUcId) {
        $this->fromUcId = $fromUcId;
    }

    /**
     * Get fromUcId
     *
     * @return integer 
     */
    public function getFromUcId() {
        return $this->fromUcId;
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
     * Set epidNumber
     *
     * @param string $epidNumber
     */
    public function setEpidNumber($epidNumber) {
        $this->epidNumber = $epidNumber;
    }

    /**
     * Get epidNumber
     *
     * @return string 
     */
    public function getEpidNumber() {
        return $this->epidNumber;
    }

    /**
     * Set clinicalPresentation
     *
     * @param string $clinicalPresentation
     */
    public function setClinicalPresentation($clinicalPresentation) {
        $this->clinicalPresentation = $clinicalPresentation;
    }

    /**
     * Get clinicalPresentation
     *
     * @return string 
     */
    public function getClinicalPresentation() {
        return $this->clinicalPresentation;
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
     * Set crossNotification
     *
     * @param integer $crossNotification
     */
    public function setCrossNotification($crossNotification) {
        $this->crossNotification = $crossNotification;
    }

    /**
     * Get crossNotification
     *
     * @return integer 
     */
    public function getCrossNotification() {
        return $this->crossNotification;
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

    /**
     * Set age
     *
     * @param integer $age
     */
    public function setAge($age) {
        $this->age = $age;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge() {
        return $this->age;
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
     * Set dateOnset
     *
     * @param date $dateOnset
     */
    public function setDateOnset($dateOnset) {
        $this->dateOnset = $dateOnset;
    }

    /**
     * Get dateOnset
     *
     * @return date 
     */
    public function getDateOnset() {
        return $this->dateOnset;
    }
public function setPlaceOfBirth($placeOfBirth) {
        $this->placeOfBirth = $placeOfBirth;
    }

    /**
     * Get outcome
     *
     * @return integer 
     */
    public function getPlaceOfBirth() {
        return $this->placeOfBirth;
    }public function setAttendee($attendee) {
        $this->attendee = $attendee;
    }

    /**
     * Get outcome
     *
     * @return integer 
     */
    public function getAttendee() {
        return $this->attendee;
    }
    /**
     * Set dateNotification
     *
     * @param date $dateNotification
     */
    public function setDateNotification($dateNotification) {
        $this->dateNotification = $dateNotification;
    }

    /**
     * Get dateNotification
     *
     * @return date 
     */
    public function getDateNotification() {
        return $this->dateNotification;
    }

    /**
     * Set dateInvestigation
     *
     * @param date $dateInvestigation
     */
    public function setDateInvestigation($dateInvestigation) {
        $this->dateInvestigation = $dateInvestigation;
    }

    /**
     * Get dateInvestigation
     *
     * @return date 
     */
    public function getDateInvestigation() {
        return $this->dateInvestigation;
    }

    /**
     * Set specificDoseReceived
     *
     * @param integer $specificDoseReceived
     */
    public function setSpecificDoseReceived($specificDoseReceived) {
        $this->specificDoseReceived = $specificDoseReceived;
    }

    /**
     * Get specificDoseReceived
     *
     * @return integer 
     */
    public function getSpecificDoseReceived() {
        return $this->specificDoseReceived;
    }

    /**
     * Set dateLastDoseReceived
     *
     * @param datetime $dateLastDoseReceived
     */
    public function setDateLastDoseReceived($dateLastDoseReceived) {
        $this->dateLastDoseReceived = $dateLastDoseReceived;
    }

    /**
     * Get dateLastDoseReceived
     *
     * @return datetime 
     */
    public function getDateLastDoseReceived() {
        return $this->dateLastDoseReceived;
    }

    /**
     * Set specimenCollection
     *
     * @param integer $specimenCollection
     */
    public function setSpecimenCollection($specimenCollection) {
        $this->specimenCollection = $specimenCollection;
    }

    /**
     * Get specimenCollection
     *
     * @return integer 
     */
    public function getSpecimenCollection() {
        return $this->specimenCollection;
    }

    /**
     * Set dateSpecimenSent
     *
     * @param datetime $dateSpecimenSent
     */
    public function setDateSpecimenSent($dateSpecimenSent) {
        $this->dateSpecimenSent = $dateSpecimenSent;
    }

    /**
     * Get dateSpecimenSent
     *
     * @return datetime 
     */
    public function getDateSpecimenSent() {
        return $this->dateSpecimenSent;
    }
    
    
    /**
     * Set dateSpecimenSent1
     *
     * @param datetime $dateSpecimenSent1
     */
    public function setDateSpecimenSent1($dateSpecimenSent1) {
        $this->dateSpecimenSent1 = $dateSpecimenSent1;
    }

    /**
     * Get dateSpecimenSent1
     *
     * @return datetime 
     */
    public function getDateSpecimenSent1() {
        return $this->dateSpecimenSent1;
    }

    /**
     * Set signSymptoms
     *
     * @param string $signSymptoms
     */
    public function setSignSymptoms($signSymptoms) {
        $this->signSymptoms = $signSymptoms;
    }

    /**
     * Get signSymptoms
     *
     * @return string 
     */
    public function getSignSymptoms() {
        return $this->signSymptoms;
    }

    /**
     * Set outcome
     *
     * @param integer $outcome
     */
    public function setOutcome($outcome) {
        $this->outcome = $outcome;
    }

    /**
     * Get outcome
     *
     * @return integer 
     */
    public function getOutcome() {
        return $this->outcome;
    }

    /**
     * Set labResult
     *
     * @param integer $labResult
     */
    public function setLabResult($labResult) {
        $this->labResult = $labResult;
    }

    /**
     * Get labResult
     *
     * @return integer 
     */
    public function getLabResult() {
        return $this->labResult;
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
     * Set finalClassification
     *
     * @param integer $finalClassification
     */
    public function setFinalClassification($finalClassification) {
        $this->finalClassification = $finalClassification;
    }

    /**
     * Get finalClassification
     *
     * @return integer 
     */
    public function getFinalClassification() {
        return $this->finalClassification;
    }

    /**
     * Set stoolSampleDate1
     *
     * @param datetime $stoolSampleDate1
     */
    public function setStoolSampleDate1($stoolSampleDate1) {
        $this->stoolSampleDate1 = $stoolSampleDate1;
    }

    /**
     * Get stoolSampleDate1
     *
     * @return datetime 
     */
    public function getStoolSampleDate1() {
        return $this->stoolSampleDate1;
    }

    /**
     * Set stoolSampleDate2
     *
     * @param datetime $stoolSampleDate2
     */
    public function setStoolSampleDate2($stoolSampleDate2) {
        $this->stoolSampleDate2 = $stoolSampleDate2;
    }

    /**
     * Get stoolSampleDate2
     *
     * @return datetime 
     */
    public function getStoolSampleDate2() {
        return $this->stoolSampleDate2;
    }

    /**
     * Set bloodSampleDate
     *
     * @param datetime $bloodSampleDate
     */
    public function setBloodSampleDate($bloodSampleDate) {
        $this->bloodSampleDate = $bloodSampleDate;
    }

    /**
     * Get bloodSampleDate
     *
     * @return datetime 
     */
    public function getBloodSampleDate() {
        return $this->bloodSampleDate;
    }

    /**
     * Set bloodSampleMonth
     *
     * @param string $bloodSampleMonth
     */
    public function setBloodSampleMonth($bloodSampleMonth) {
        $this->bloodSampleMonth = $bloodSampleMonth;
    }

    /**
     * Get bloodSampleMonth
     *
     * @return string 
     */
    public function getBloodSampleMonth() {
        return $this->bloodSampleMonth;
    }

    /**
     * Set numberOfMonths
     *
     * @param integer $numberOfMonths
     */
    public function setNumberOfMonths($numberOfMonths) {
        $this->numberOfMonths = $numberOfMonths;
    }

    /**
     * Get numberOfMonths
     *
     * @return integer 
     */
    public function getNumberOfMonths() {
        return $this->numberOfMonths;
    }
    
    
    /**
     * Set hasChildReceivedOpv
     *
     * @param integer $hasChildReceivedOpv
     */
    public function setHasChildReceivedOpv($hasChildReceivedOpv) {
        $this->hasChildReceivedOpv = $hasChildReceivedOpv;
    }

    /**
     * Get hasChildReceivedOpv
     *
     * @return integer 
     */
    public function getHasChildReceivedOpv() {
        return $this->hasChildReceivedOpv;
    }
    
    /**
     * Set isTheChildProtected
     *
     * @param integer $isTheChildProtected
     */
    public function setIsTheChildProtected($isTheChildProtected) {
        $this->isTheChildProtected = $isTheChildProtected;
    }

    /**
     * Get isTheChildProtected
     *
     * @return integer 
     */
    public function getIsTheChildProtected() {
        return $this->isTheChildProtected;
    }

      /**
     * Set specimenCollectionDate
     *
     * @param datetime $specimenCollectionDate
     */
    public function setSpecimenCollectionDate($specimenCollectionDate) {
        $this->specimenCollectionDate = $specimenCollectionDate;
    }

    /**
     * Get specimenCollectionDate
     *
     * @return datetime 
     */
    public function getSpecimenCollectionDate() {
        return $this->specimenCollectionDate;
    }
    
    
       /**
     * Set dateOfLabResulted
     *
     * @param datetime $dateOfLabResulted
     */
    public function setDateOfLabResulted($dateOfLabResulted) {
        $this->dateOfLabResulted = $dateOfLabResulted;
    }

    /**
     * Get dateOfLabResulted
     *
     * @return datetime 
     */
    public function getDateOfLabResulted() {
        return $this->dateOfLabResulted;
    }
     /**
     * Set patientVisitedDate
     *
     * @param datetime $patientVisitedDate
     */
    public function setPatientVisitedDate($patientVisitedDate) {
        $this->patientVisitedDate = $patientVisitedDate;
    }

    /**
     * Get patientVisitedDate
     *
     * @return datetime 
     */
    public function getPatientVisitedDate() {
        return $this->patientVisitedDate;
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
