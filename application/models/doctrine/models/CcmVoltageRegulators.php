<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CcmVoltageRegulators
 */
class CcmVoltageRegulators
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $nominalVoltage
     */
    private $nominalVoltage;

    /**
     * @var integer $continousPower
     */
    private $continousPower;

    /**
     * @var string $frequency
     */
    private $frequency;

    /**
     * @var string $inputVoltageRange
     */
    private $inputVoltageRange;

    /**
     * @var string $outputVoltageRange
     */
    private $outputVoltageRange;

    /**
     * @var integer $ccmId
     */
    private $ccmId;

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
     * Set nominalVoltage
     *
     * @param integer $nominalVoltage
     */
    public function setNominalVoltage($nominalVoltage)
    {
        $this->nominalVoltage = $nominalVoltage;
    }

    /**
     * Get nominalVoltage
     *
     * @return integer 
     */
    public function getNominalVoltage()
    {
        return $this->nominalVoltage;
    }

    /**
     * Set continousPower
     *
     * @param integer $continousPower
     */
    public function setContinousPower($continousPower)
    {
        $this->continousPower = $continousPower;
    }

    /**
     * Get continousPower
     *
     * @return integer 
     */
    public function getContinousPower()
    {
        return $this->continousPower;
    }

    /**
     * Set frequency
     *
     * @param string $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * Get frequency
     *
     * @return string 
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set inputVoltageRange
     *
     * @param string $inputVoltageRange
     */
    public function setInputVoltageRange($inputVoltageRange)
    {
        $this->inputVoltageRange = $inputVoltageRange;
    }

    /**
     * Get inputVoltageRange
     *
     * @return string 
     */
    public function getInputVoltageRange()
    {
        return $this->inputVoltageRange;
    }

    /**
     * Set outputVoltageRange
     *
     * @param string $outputVoltageRange
     */
    public function setOutputVoltageRange($outputVoltageRange)
    {
        $this->outputVoltageRange = $outputVoltageRange;
    }

    /**
     * Get outputVoltageRange
     *
     * @return string 
     */
    public function getOutputVoltageRange()
    {
        return $this->outputVoltageRange;
    }

    /**
     * Set ccmId
     *
     * @param integer $ccmId
     */
    public function setCcmId($ccmId)
    {
        $this->ccmId = $ccmId;
    }

    /**
     * Get ccmId
     *
     * @return integer 
     */
    public function getCcmId()
    {
        return $this->ccmId;
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