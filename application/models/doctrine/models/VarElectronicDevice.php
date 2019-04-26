<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * VarElectronicDevice
 */
class VarElectronicDevice
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $boxNumber
     */
    private $boxNumber;

    /**
     * @var string $lotNo
     */
    private $lotNo;

    /**
     * @var string $less45
     */
    private $less45;

    /**
     * @var string $less30
     */
    private $less30;

    /**
     * @var string $less10
     */
    private $less10;

    /**
     * @var string $less5
     */
    private $less5;

    /**
     * @var string $coldChainA
     */
    private $coldChainA;

    /**
     * @var string $coldChainB
     */
    private $coldChainB;

    /**
     * @var string $coldChainC
     */
    private $coldChainC;

    /**
     * @var string $coldChainD
     */
    private $coldChainD;

    /**
     * @var string $dayOfInspection
     */
    private $dayOfInspection;

    /**
     * @var date $dateOfInspection
     */
    private $dateOfInspection;

    /**
     * @var date $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Users
     */
    private $createdBy;

    /**
     * @var VarDetail
     */
    private $varDetail;

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
     * Set boxNumber
     *
     * @param string $boxNumber
     */
    public function setBoxNumber($boxNumber)
    {
        $this->boxNumber = $boxNumber;
    }

    /**
     * Get boxNumber
     *
     * @return string 
     */
    public function getBoxNumber()
    {
        return $this->boxNumber;
    }

    /**
     * Set lotNo
     *
     * @param string $lotNo
     */
    public function setLotNo($lotNo)
    {
        $this->lotNo = $lotNo;
    }

    /**
     * Get lotNo
     *
     * @return string 
     */
    public function getLotNo()
    {
        return $this->lotNo;
    }

    /**
     * Set less45
     *
     * @param string $less45
     */
    public function setLess45($less45)
    {
        $this->less45 = $less45;
    }

    /**
     * Get less45
     *
     * @return string 
     */
    public function getLess45()
    {
        return $this->less45;
    }

    /**
     * Set less30
     *
     * @param string $less30
     */
    public function setLess30($less30)
    {
        $this->less30 = $less30;
    }

    /**
     * Get less30
     *
     * @return string 
     */
    public function getLess30()
    {
        return $this->less30;
    }

    /**
     * Set less10
     *
     * @param string $less10
     */
    public function setLess10($less10)
    {
        $this->less10 = $less10;
    }

    /**
     * Get less10
     *
     * @return string 
     */
    public function getLess10()
    {
        return $this->less10;
    }

    /**
     * Set less5
     *
     * @param string $less5
     */
    public function setLess5($less5)
    {
        $this->less5 = $less5;
    }

    /**
     * Get less5
     *
     * @return string 
     */
    public function getLess5()
    {
        return $this->less5;
    }

    /**
     * Set coldChainA
     *
     * @param string $coldChainA
     */
    public function setColdChainA($coldChainA)
    {
        $this->coldChainA = $coldChainA;
    }

    /**
     * Get coldChainA
     *
     * @return string 
     */
    public function getColdChainA()
    {
        return $this->coldChainA;
    }

    /**
     * Set coldChainB
     *
     * @param string $coldChainB
     */
    public function setColdChainB($coldChainB)
    {
        $this->coldChainB = $coldChainB;
    }

    /**
     * Get coldChainB
     *
     * @return string 
     */
    public function getColdChainB()
    {
        return $this->coldChainB;
    }

    /**
     * Set coldChainC
     *
     * @param string $coldChainC
     */
    public function setColdChainC($coldChainC)
    {
        $this->coldChainC = $coldChainC;
    }

    /**
     * Get coldChainC
     *
     * @return string 
     */
    public function getColdChainC()
    {
        return $this->coldChainC;
    }

    /**
     * Set coldChainD
     *
     * @param string $coldChainD
     */
    public function setColdChainD($coldChainD)
    {
        $this->coldChainD = $coldChainD;
    }

    /**
     * Get coldChainD
     *
     * @return string 
     */
    public function getColdChainD()
    {
        return $this->coldChainD;
    }

    /**
     * Set dayOfInspection
     *
     * @param string $dayOfInspection
     */
    public function setDayOfInspection($dayOfInspection)
    {
        $this->dayOfInspection = $dayOfInspection;
    }

    /**
     * Get dayOfInspection
     *
     * @return string 
     */
    public function getDayOfInspection()
    {
        return $this->dayOfInspection;
    }

    /**
     * Set dateOfInspection
     *
     * @param date $dateOfInspection
     */
    public function setDateOfInspection($dateOfInspection)
    {
        $this->dateOfInspection = $dateOfInspection;
    }

    /**
     * Get dateOfInspection
     *
     * @return date 
     */
    public function getDateOfInspection()
    {
        return $this->dateOfInspection;
    }

    /**
     * Set createdDate
     *
     * @param date $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return date 
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
     * Set varDetail
     *
     * @param VarDetail $varDetail
     */
    public function setVarDetail(\VarDetail $varDetail)
    {
        $this->varDetail = $varDetail;
    }

    /**
     * Get varDetail
     *
     * @return VarDetail 
     */
    public function getVarDetail()
    {
        return $this->varDetail;
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