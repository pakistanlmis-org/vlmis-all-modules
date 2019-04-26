<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * RoleResources
 */
class RoleResources
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $permission
     */
    private $permission;

    /**
     * @var integer $isDefault
     */
    private $isDefault;

    /**
     * @var Resources
     */
    private $resource;

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
     * Set permission
     *
     * @param string $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * Get permission
     *
     * @return string 
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set isDefault
     *
     * @param integer $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * Get isDefault
     *
     * @return integer 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
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