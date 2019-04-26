<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Documents
 */
class Documents
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $docTitle
     */
    private $docTitle;

    /**
     * @var string $docPath
     */
    private $docPath;

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
     * @var DocumentCategories
     */
    private $docCategory;

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
     * Set docTitle
     *
     * @param string $docTitle
     */
    public function setDocTitle($docTitle)
    {
        $this->docTitle = $docTitle;
    }

    /**
     * Get docTitle
     *
     * @return string 
     */
    public function getDocTitle()
    {
        return $this->docTitle;
    }

    /**
     * Set docPath
     *
     * @param string $docPath
     */
    public function setDocPath($docPath)
    {
        $this->docPath = $docPath;
    }

    /**
     * Get docPath
     *
     * @return string 
     */
    public function getDocPath()
    {
        return $this->docPath;
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
     * Set docCategory
     *
     * @param DocumentCategories $docCategory
     */
    public function setDocCategory(\DocumentCategories $docCategory)
    {
        $this->docCategory = $docCategory;
    }

    /**
     * Get docCategory
     *
     * @return DocumentCategories 
     */
    public function getDocCategory()
    {
        return $this->docCategory;
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