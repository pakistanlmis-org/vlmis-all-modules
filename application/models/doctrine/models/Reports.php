<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Reports
 */
class Reports
{
    /**
     * @var string $reportId
     */
    private $reportId;

    /**
     * @var string $reportGroup
     */
    private $reportGroup;

    /**
     * @var decimal $reportType
     */
    private $reportType;

    /**
     * @var string $reportTitle
     */
    private $reportTitle;

    /**
     * @var string $reportXaxis
     */
    private $reportXaxis;

    /**
     * @var string $reportYaxis
     */
    private $reportYaxis;

    /**
     * @var string $reportUnits
     */
    private $reportUnits;

    /**
     * @var decimal $reportFactor
     */
    private $reportFactor;

    /**
     * @var string $reportField
     */
    private $reportField;

    /**
     * @var text $reportDescription
     */
    private $reportDescription;

    /**
     * @var string $staticpage
     */
    private $staticpage;

    /**
     * @var string $footerStaticpage
     */
    private $footerStaticpage;

    /**
     * @var smallint $reportOrder
     */
    private $reportOrder;

    /**
     * @var smallint $reportShowSimple
     */
    private $reportShowSimple;

    /**
     * @var smallint $reportShowComp
     */
    private $reportShowComp;

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
     * Get reportId
     *
     * @return string 
     */
    public function getReportId()
    {
        return $this->reportId;
    }

    /**
     * Set reportGroup
     *
     * @param string $reportGroup
     */
    public function setReportGroup($reportGroup)
    {
        $this->reportGroup = $reportGroup;
    }

    /**
     * Get reportGroup
     *
     * @return string 
     */
    public function getReportGroup()
    {
        return $this->reportGroup;
    }

    /**
     * Set reportType
     *
     * @param decimal $reportType
     */
    public function setReportType($reportType)
    {
        $this->reportType = $reportType;
    }

    /**
     * Get reportType
     *
     * @return decimal 
     */
    public function getReportType()
    {
        return $this->reportType;
    }

    /**
     * Set reportTitle
     *
     * @param string $reportTitle
     */
    public function setReportTitle($reportTitle)
    {
        $this->reportTitle = $reportTitle;
    }

    /**
     * Get reportTitle
     *
     * @return string 
     */
    public function getReportTitle()
    {
        return $this->reportTitle;
    }

    /**
     * Set reportXaxis
     *
     * @param string $reportXaxis
     */
    public function setReportXaxis($reportXaxis)
    {
        $this->reportXaxis = $reportXaxis;
    }

    /**
     * Get reportXaxis
     *
     * @return string 
     */
    public function getReportXaxis()
    {
        return $this->reportXaxis;
    }

    /**
     * Set reportYaxis
     *
     * @param string $reportYaxis
     */
    public function setReportYaxis($reportYaxis)
    {
        $this->reportYaxis = $reportYaxis;
    }

    /**
     * Get reportYaxis
     *
     * @return string 
     */
    public function getReportYaxis()
    {
        return $this->reportYaxis;
    }

    /**
     * Set reportUnits
     *
     * @param string $reportUnits
     */
    public function setReportUnits($reportUnits)
    {
        $this->reportUnits = $reportUnits;
    }

    /**
     * Get reportUnits
     *
     * @return string 
     */
    public function getReportUnits()
    {
        return $this->reportUnits;
    }

    /**
     * Set reportFactor
     *
     * @param decimal $reportFactor
     */
    public function setReportFactor($reportFactor)
    {
        $this->reportFactor = $reportFactor;
    }

    /**
     * Get reportFactor
     *
     * @return decimal 
     */
    public function getReportFactor()
    {
        return $this->reportFactor;
    }

    /**
     * Set reportField
     *
     * @param string $reportField
     */
    public function setReportField($reportField)
    {
        $this->reportField = $reportField;
    }

    /**
     * Get reportField
     *
     * @return string 
     */
    public function getReportField()
    {
        return $this->reportField;
    }

    /**
     * Set reportDescription
     *
     * @param text $reportDescription
     */
    public function setReportDescription($reportDescription)
    {
        $this->reportDescription = $reportDescription;
    }

    /**
     * Get reportDescription
     *
     * @return text 
     */
    public function getReportDescription()
    {
        return $this->reportDescription;
    }

    /**
     * Set staticpage
     *
     * @param string $staticpage
     */
    public function setStaticpage($staticpage)
    {
        $this->staticpage = $staticpage;
    }

    /**
     * Get staticpage
     *
     * @return string 
     */
    public function getStaticpage()
    {
        return $this->staticpage;
    }

    /**
     * Set footerStaticpage
     *
     * @param string $footerStaticpage
     */
    public function setFooterStaticpage($footerStaticpage)
    {
        $this->footerStaticpage = $footerStaticpage;
    }

    /**
     * Get footerStaticpage
     *
     * @return string 
     */
    public function getFooterStaticpage()
    {
        return $this->footerStaticpage;
    }

    /**
     * Set reportOrder
     *
     * @param smallint $reportOrder
     */
    public function setReportOrder($reportOrder)
    {
        $this->reportOrder = $reportOrder;
    }

    /**
     * Get reportOrder
     *
     * @return smallint 
     */
    public function getReportOrder()
    {
        return $this->reportOrder;
    }

    /**
     * Set reportShowSimple
     *
     * @param smallint $reportShowSimple
     */
    public function setReportShowSimple($reportShowSimple)
    {
        $this->reportShowSimple = $reportShowSimple;
    }

    /**
     * Get reportShowSimple
     *
     * @return smallint 
     */
    public function getReportShowSimple()
    {
        return $this->reportShowSimple;
    }

    /**
     * Set reportShowComp
     *
     * @param smallint $reportShowComp
     */
    public function setReportShowComp($reportShowComp)
    {
        $this->reportShowComp = $reportShowComp;
    }

    /**
     * Get reportShowComp
     *
     * @return smallint 
     */
    public function getReportShowComp()
    {
        return $this->reportShowComp;
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