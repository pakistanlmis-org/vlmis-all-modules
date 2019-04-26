<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * UserDocuments
 */
class UserDocuments
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var string $systemIp
     */
    private $systemIp;

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
    private $user;

    /**
     * @var Documents
     */
    private $doc;

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
     * Set systemIp
     *
     * @param string $systemIp
     */
    public function setSystemIp($systemIp)
    {
        $this->systemIp = $systemIp;
    }

    /**
     * Get systemIp
     *
     * @return string 
     */
    public function getSystemIp()
    {
        return $this->systemIp;
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
     * Set user
     *
     * @param Users $user
     */
    public function setUser(\Users $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Users 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set doc
     *
     * @param Documents $doc
     */
    public function setDoc(\Documents $doc)
    {
        $this->doc = $doc;
    }

    /**
     * Get doc
     *
     * @return Documents 
     */
    public function getDoc()
    {
        return $this->doc;
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