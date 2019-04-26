<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * EmailVerification
 */
class EmailVerification
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var integer $userId
     */
    private $userId;

    /**
     * @var string $emailAddress
     */
    private $emailAddress;

    /**
     * @var integer $isVerified
     */
    private $isVerified;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;


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
     * Set userId
     *
     * @param integer $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * Get emailAddress
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set isVerified
     *
     * @param integer $isVerified
     */
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;
    }

    /**
     * Get isVerified
     *
     * @return integer 
     */
    public function getIsVerified()
    {
        return $this->isVerified;
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
}