<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * VpdClinicalPresentation
 */
class VpdClinicalPresentation {

    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $clinicalPresentationId
     */
    private $clinicalPresentationId;

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
     * @var VpdForm
     */
    private $vpdForm;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * Get pkId
     *
     * @return integer 
     */
    public function getPkId() {
        return $this->pkId;
    }

    /**
     * Set clinicalPresentationId
     *
     * @param integer $clinicalPresentationId
     */
    public function setClinicalPresentationId($clinicalPresentationId) {
        $this->clinicalPresentationId = $clinicalPresentationId;
    }

    /**
     * Get clinicalPresentationId
     *
     * @return integer 
     */
    public function getClinicalPresentationId() {
        return $this->clinicalPresentationId;
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
     * @param Users $modifiedBy
     */
    public function setModifiedBy(\Users $modifiedBy) {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * Get modifiedBy
     *
     * @return Users 
     */
    public function getModifiedBy() {
        return $this->modifiedBy;
    }

    /**
     * Set vpdForm
     *
     * @param VpdForm $vpdForm
     */
    public function setVpdForm(\VpdForm $vpdForm) {
        $this->vpdForm = $vpdForm;
    }

    /**
     * Get vpdForm
     *
     * @return VpdForm 
     */
    public function getVpdForm() {
        return $this->vpdForm;
    }

    /**
     * Set createdBy
     *
     * @param Users $createdBy
     */
    public function setCreatedBy(\Users $createdBy) {
        $this->createdBy = $createdBy;
    }

    /**
     * Get createdBy
     *
     * @return Users 
     */
    public function getCreatedBy() {
        return $this->createdBy;
    }

}
