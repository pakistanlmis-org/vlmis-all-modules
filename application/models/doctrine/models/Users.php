<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 */
class Users
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $userName
     */
    private $userName;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $cellNumber
     */
    private $cellNumber;

    /**
     * @var string $recordId
     */
    private $recordId;

    /**
     * @var string $loginId
     */
    private $loginId;

    /**
     * @var string $designation
     */
    private $designation;

    /**
     * @var string $department
     */
    private $department;

    /**
     * @var string $photo
     */
    private $photo;

    /**
     * @var string $address
     */
    private $address;

    /**
     * @var string $phoneNumber
     */
    private $phoneNumber;

    /**
     * @var boolean $status
     */
    private $status;

    /**
     * @var datetime $loggedAt
     */
    private $loggedAt;

    /**
     * @var datetime $failedAt
     */
    private $failedAt;

    /**
     * @var integer $failedQuantity
     */
    private $failedQuantity;

    /**
     * @var string $auth
     */
    private $auth;

    /**
     * @var string $organization
     */
    private $organization;

    /**
     * @var integer $country
     */
    private $country;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var datetime $modifiedDate
     */
    private $modifiedDate;

    /**
     * @var Roles
     */
    private $role;

    /**
     * @var Stakeholders
     */
    private $stakeholder;

    /**
     * @var Locations
     */
    private $location;

    /**
     * @var Users
     */
    private $createdBy;

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
     * Set userName
     *
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
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
     * Set cellNumber
     *
     * @param string $cellNumber
     */
    public function setCellNumber($cellNumber)
    {
        $this->cellNumber = $cellNumber;
    }

    /**
     * Get cellNumber
     *
     * @return string 
     */
    public function getCellNumber()
    {
        return $this->cellNumber;
    }

    /**
     * Set recordId
     *
     * @param string $recordId
     */
    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;
    }

    /**
     * Get recordId
     *
     * @return string 
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * Set loginId
     *
     * @param string $loginId
     */
    public function setLoginId($loginId)
    {
        $this->loginId = $loginId;
    }

    /**
     * Get loginId
     *
     * @return string 
     */
    public function getLoginId()
    {
        return $this->loginId;
    }

    /**
     * Set designation
     *
     * @param string $designation
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }

    /**
     * Get designation
     *
     * @return string 
     */
    public function getDesignation()
    {
        return $this->designation;
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
     * Set photo
     *
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set status
     *
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set loggedAt
     *
     * @param datetime $loggedAt
     */
    public function setLoggedAt($loggedAt)
    {
        $this->loggedAt = $loggedAt;
    }

    /**
     * Get loggedAt
     *
     * @return datetime 
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * Set failedAt
     *
     * @param datetime $failedAt
     */
    public function setFailedAt($failedAt)
    {
        $this->failedAt = $failedAt;
    }

    /**
     * Get failedAt
     *
     * @return datetime 
     */
    public function getFailedAt()
    {
        return $this->failedAt;
    }

    /**
     * Set failedQuantity
     *
     * @param integer $failedQuantity
     */
    public function setFailedQuantity($failedQuantity)
    {
        $this->failedQuantity = $failedQuantity;
    }

    /**
     * Get failedQuantity
     *
     * @return integer 
     */
    public function getFailedQuantity()
    {
        return $this->failedQuantity;
    }

    /**
     * Set auth
     *
     * @param string $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get auth
     *
     * @return string 
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set organization
     *
     * @param string $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * Get organization
     *
     * @return string 
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set country
     *
     * @param integer $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return integer 
     */
    public function getCountry()
    {
        return $this->country;
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
     * Set role
     *
     * @param Roles $role
     */
    public function setRole(\Roles $role)
    {
        $this->role = $role;
    }

    /**
     * Get role
     *
     * @return Roles 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set stakeholder
     *
     * @param Stakeholders $stakeholder
     */
    public function setStakeholder(\Stakeholders $stakeholder)
    {
        $this->stakeholder = $stakeholder;
    }

    /**
     * Get stakeholder
     *
     * @return Stakeholders 
     */
    public function getStakeholder()
    {
        return $this->stakeholder;
    }

    /**
     * Set location
     *
     * @param Locations $location
     */
    public function setLocation(\Locations $location)
    {
        $this->location = $location;
    }

    /**
     * Get location
     *
     * @return Locations 
     */
    public function getLocation()
    {
        return $this->location;
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
}