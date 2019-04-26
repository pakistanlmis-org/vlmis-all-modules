<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Survey
 */
class Survey
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $department
     */
    private $department;

    /**
     * @var string $office
     */
    private $office;

    /**
     * @var integer $cellNumber
     */
    private $cellNumber;

    /**
     * @var text $q1DataDifficulty
     */
    private $q1DataDifficulty;

    /**
     * @var text $q2Report
     */
    private $q2Report;

    /**
     * @var text $comment
     */
    private $comment;

    /**
     * @var text $q1YN
     */
    private $q1YN;

    /**
     * @var text $q2YN
     */
    private $q2YN;

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
    private $createdBy;

    /**
     * @var Users
     */
    private $modifiedBy;

    /**
     * @var Users
     */
    private $user;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set department
     *
     * @param string $department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }

    /**
     * Get department
     *
     * @return string 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set office
     *
     * @param string $office
     */
    public function setOffice($office)
    {
        $this->office = $office;
    }

    /**
     * Get office
     *
     * @return string 
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Set cellNumber
     *
     * @param integer $cellNumber
     */
    public function setCellNumber($cellNumber)
    {
        $this->cellNumber = $cellNumber;
    }

    /**
     * Get cellNumber
     *
     * @return integer 
     */
    public function getCellNumber()
    {
        return $this->cellNumber;
    }

    /**
     * Set q1DataDifficulty
     *
     * @param text $q1DataDifficulty
     */
    public function setQ1DataDifficulty($q1DataDifficulty)
    {
        $this->q1DataDifficulty = $q1DataDifficulty;
    }

    /**
     * Get q1DataDifficulty
     *
     * @return text 
     */
    public function getQ1DataDifficulty()
    {
        return $this->q1DataDifficulty;
    }

    /**
     * Set q2Report
     *
     * @param text $q2Report
     */
    public function setQ2Report($q2Report)
    {
        $this->q2Report = $q2Report;
    }

    /**
     * Get q2Report
     *
     * @return text 
     */
    public function getQ2Report()
    {
        return $this->q2Report;
    }

    /**
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set q1YN
     *
     * @param text $q1YN
     */
    public function setQ1YN($q1YN)
    {
        $this->q1YN = $q1YN;
    }

    /**
     * Get q1YN
     *
     * @return text 
     */
    public function getQ1YN()
    {
        return $this->q1YN;
    }

    /**
     * Set q2YN
     *
     * @param text $q2YN
     */
    public function setQ2YN($q2YN)
    {
        $this->q2YN = $q2YN;
    }

    /**
     * Get q2YN
     *
     * @return text 
     */
    public function getQ2YN()
    {
        return $this->q2YN;
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
}