<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * GeoIndicatorValues
 */
class GeoIndicatorValues
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $startValue
     */
    private $startValue;

    /**
     * @var string $endValue
     */
    private $endValue;

    /**
     * @var string $interval
     */
    private $interval;

    /**
     * @var string $description
     */
    private $description;

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
     * @var GeoIndicators
     */
    private $geoIndicator;

    /**
     * @var GeoColor
     */
    private $geoColor;

    /**
     * @var Users
     */
    private $createdBy;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startValue
     *
     * @param string $startValue
     */
    public function setStartValue($startValue)
    {
        $this->startValue = $startValue;
    }

    /**
     * Get startValue
     *
     * @return string 
     */
    public function getStartValue()
    {
        return $this->startValue;
    }

    /**
     * Set endValue
     *
     * @param string $endValue
     */
    public function setEndValue($endValue)
    {
        $this->endValue = $endValue;
    }

    /**
     * Get endValue
     *
     * @return string 
     */
    public function getEndValue()
    {
        return $this->endValue;
    }

    /**
     * Set interval
     *
     * @param string $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * Get interval
     *
     * @return string 
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set geoIndicator
     *
     * @param GeoIndicators $geoIndicator
     */
    public function setGeoIndicator(\GeoIndicators $geoIndicator)
    {
        $this->geoIndicator = $geoIndicator;
    }

    /**
     * Get geoIndicator
     *
     * @return GeoIndicators 
     */
    public function getGeoIndicator()
    {
        return $this->geoIndicator;
    }

    /**
     * Set geoColor
     *
     * @param GeoColor $geoColor
     */
    public function setGeoColor(\GeoColor $geoColor)
    {
        $this->geoColor = $geoColor;
    }

    /**
     * Get geoColor
     *
     * @return GeoColor 
     */
    public function getGeoColor()
    {
        return $this->geoColor;
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