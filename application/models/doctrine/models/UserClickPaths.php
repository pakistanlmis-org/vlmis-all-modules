<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * UserClickPaths
 */
class UserClickPaths
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $sessionId
     */
    private $sessionId;

    /**
     * @var datetime $createdDate
     */
    private $createdDate;

    /**
     * @var Users
     */
    private $user;

    /**
     * @var Resources
     */
    private $resource;

    /**
     * @var Locations
     */
    private $province;

    /**
     * @var Roles
     */
    private $role;


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
     * Set sessionId
     *
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
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
     * Set resource
     *
     * @param Resources $resource
     */
    public function setResource(\Resources $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource
     *
     * @return Resources 
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set province
     *
     * @param Locations $province
     */
    public function setProvince(\Locations $province)
    {
        $this->province = $province;
    }

    /**
     * Get province
     *
     * @return Locations 
     */
    public function getProvince()
    {
        return $this->province;
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
}