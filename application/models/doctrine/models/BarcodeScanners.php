<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * BarcodeScanners
 */
class BarcodeScanners
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $scannerName
     */
    private $scannerName;

    /**
     * @var string $licenceNumber
     */
    private $licenceNumber;

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
     * Set scannerName
     *
     * @param string $scannerName
     */
    public function setScannerName($scannerName)
    {
        $this->scannerName = $scannerName;
    }

    /**
     * Get scannerName
     *
     * @return string 
     */
    public function getScannerName()
    {
        return $this->scannerName;
    }

    /**
     * Set licenceNumber
     *
     * @param string $licenceNumber
     */
    public function setLicenceNumber($licenceNumber)
    {
        $this->licenceNumber = $licenceNumber;
    }

    /**
     * Get licenceNumber
     *
     * @return string 
     */
    public function getLicenceNumber()
    {
        return $this->licenceNumber;
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