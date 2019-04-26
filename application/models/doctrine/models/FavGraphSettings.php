<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FavGraphSettings
 */
class FavGraphSettings
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $user
     */
    private $user;

    /**
     * @var string $selUser
     */
    private $selUser;

    /**
     * @var string $period
     */
    private $period;

    /**
     * @var string $selStakeholder
     */
    private $selStakeholder;

    /**
     * @var string $year
     */
    private $year;

    /**
     * @var text $arrproducts
     */
    private $arrproducts;

    /**
     * @var string $compareOpt
     */
    private $compareOpt;

    /**
     * @var string $optvals
     */
    private $optvals;

    /**
     * @var text $arryearcomp
     */
    private $arryearcomp;

    /**
     * @var text $arrstakecomp
     */
    private $arrstakecomp;

    /**
     * @var text $titles
     */
    private $titles;

    /**
     * @var text $allfiles
     */
    private $allfiles;

    /**
     * @var string $col
     */
    private $col;

    /**
     * @var string $unit
     */
    private $unit;

    /**
     * @var string $xaxis
     */
    private $xaxis;

    /**
     * @var string $ctype
     */
    private $ctype;

    /**
     * @var string $repTitle1
     */
    private $repTitle1;

    /**
     * @var string $repTitle2
     */
    private $repTitle2;

    /**
     * @var string $repTitle3
     */
    private $repTitle3;

    /**
     * @var string $repLogo
     */
    private $repLogo;

    /**
     * @var string $periodLable
     */
    private $periodLable;

    /**
     * @var string $comparisonTitle
     */
    private $comparisonTitle;

    /**
     * @var text $arrgroupcomp
     */
    private $arrgroupcomp;

    /**
     * @var integer $count1
     */
    private $count1;

    /**
     * @var string $logType
     */
    private $logType;

    /**
     * @var text $arrparam
     */
    private $arrparam;

    /**
     * @var text $arrleftcol
     */
    private $arrleftcol;

    /**
     * @var string $leftcol
     */
    private $leftcol;

    /**
     * @var text $arrprovinces
     */
    private $arrprovinces;

    /**
     * @var text $arrdistricts
     */
    private $arrdistricts;

    /**
     * @var text $provinces
     */
    private $provinces;

    /**
     * @var text $districts
     */
    private $districts;

    /**
     * @var integer $selProv
     */
    private $selProv;

    /**
     * @var text $repDesc
     */
    private $repDesc;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set selUser
     *
     * @param string $selUser
     */
    public function setSelUser($selUser)
    {
        $this->selUser = $selUser;
    }

    /**
     * Get selUser
     *
     * @return string 
     */
    public function getSelUser()
    {
        return $this->selUser;
    }

    /**
     * Set period
     *
     * @param string $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * Get period
     *
     * @return string 
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set selStakeholder
     *
     * @param string $selStakeholder
     */
    public function setSelStakeholder($selStakeholder)
    {
        $this->selStakeholder = $selStakeholder;
    }

    /**
     * Get selStakeholder
     *
     * @return string 
     */
    public function getSelStakeholder()
    {
        return $this->selStakeholder;
    }

    /**
     * Set year
     *
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set arrproducts
     *
     * @param text $arrproducts
     */
    public function setArrproducts($arrproducts)
    {
        $this->arrproducts = $arrproducts;
    }

    /**
     * Get arrproducts
     *
     * @return text 
     */
    public function getArrproducts()
    {
        return $this->arrproducts;
    }

    /**
     * Set compareOpt
     *
     * @param string $compareOpt
     */
    public function setCompareOpt($compareOpt)
    {
        $this->compareOpt = $compareOpt;
    }

    /**
     * Get compareOpt
     *
     * @return string 
     */
    public function getCompareOpt()
    {
        return $this->compareOpt;
    }

    /**
     * Set optvals
     *
     * @param string $optvals
     */
    public function setOptvals($optvals)
    {
        $this->optvals = $optvals;
    }

    /**
     * Get optvals
     *
     * @return string 
     */
    public function getOptvals()
    {
        return $this->optvals;
    }

    /**
     * Set arryearcomp
     *
     * @param text $arryearcomp
     */
    public function setArryearcomp($arryearcomp)
    {
        $this->arryearcomp = $arryearcomp;
    }

    /**
     * Get arryearcomp
     *
     * @return text 
     */
    public function getArryearcomp()
    {
        return $this->arryearcomp;
    }

    /**
     * Set arrstakecomp
     *
     * @param text $arrstakecomp
     */
    public function setArrstakecomp($arrstakecomp)
    {
        $this->arrstakecomp = $arrstakecomp;
    }

    /**
     * Get arrstakecomp
     *
     * @return text 
     */
    public function getArrstakecomp()
    {
        return $this->arrstakecomp;
    }

    /**
     * Set titles
     *
     * @param text $titles
     */
    public function setTitles($titles)
    {
        $this->titles = $titles;
    }

    /**
     * Get titles
     *
     * @return text 
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * Set allfiles
     *
     * @param text $allfiles
     */
    public function setAllfiles($allfiles)
    {
        $this->allfiles = $allfiles;
    }

    /**
     * Get allfiles
     *
     * @return text 
     */
    public function getAllfiles()
    {
        return $this->allfiles;
    }

    /**
     * Set col
     *
     * @param string $col
     */
    public function setCol($col)
    {
        $this->col = $col;
    }

    /**
     * Get col
     *
     * @return string 
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * Set unit
     *
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * Get unit
     *
     * @return string 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set xaxis
     *
     * @param string $xaxis
     */
    public function setXaxis($xaxis)
    {
        $this->xaxis = $xaxis;
    }

    /**
     * Get xaxis
     *
     * @return string 
     */
    public function getXaxis()
    {
        return $this->xaxis;
    }

    /**
     * Set ctype
     *
     * @param string $ctype
     */
    public function setCtype($ctype)
    {
        $this->ctype = $ctype;
    }

    /**
     * Get ctype
     *
     * @return string 
     */
    public function getCtype()
    {
        return $this->ctype;
    }

    /**
     * Set repTitle1
     *
     * @param string $repTitle1
     */
    public function setRepTitle1($repTitle1)
    {
        $this->repTitle1 = $repTitle1;
    }

    /**
     * Get repTitle1
     *
     * @return string 
     */
    public function getRepTitle1()
    {
        return $this->repTitle1;
    }

    /**
     * Set repTitle2
     *
     * @param string $repTitle2
     */
    public function setRepTitle2($repTitle2)
    {
        $this->repTitle2 = $repTitle2;
    }

    /**
     * Get repTitle2
     *
     * @return string 
     */
    public function getRepTitle2()
    {
        return $this->repTitle2;
    }

    /**
     * Set repTitle3
     *
     * @param string $repTitle3
     */
    public function setRepTitle3($repTitle3)
    {
        $this->repTitle3 = $repTitle3;
    }

    /**
     * Get repTitle3
     *
     * @return string 
     */
    public function getRepTitle3()
    {
        return $this->repTitle3;
    }

    /**
     * Set repLogo
     *
     * @param string $repLogo
     */
    public function setRepLogo($repLogo)
    {
        $this->repLogo = $repLogo;
    }

    /**
     * Get repLogo
     *
     * @return string 
     */
    public function getRepLogo()
    {
        return $this->repLogo;
    }

    /**
     * Set periodLable
     *
     * @param string $periodLable
     */
    public function setPeriodLable($periodLable)
    {
        $this->periodLable = $periodLable;
    }

    /**
     * Get periodLable
     *
     * @return string 
     */
    public function getPeriodLable()
    {
        return $this->periodLable;
    }

    /**
     * Set comparisonTitle
     *
     * @param string $comparisonTitle
     */
    public function setComparisonTitle($comparisonTitle)
    {
        $this->comparisonTitle = $comparisonTitle;
    }

    /**
     * Get comparisonTitle
     *
     * @return string 
     */
    public function getComparisonTitle()
    {
        return $this->comparisonTitle;
    }

    /**
     * Set arrgroupcomp
     *
     * @param text $arrgroupcomp
     */
    public function setArrgroupcomp($arrgroupcomp)
    {
        $this->arrgroupcomp = $arrgroupcomp;
    }

    /**
     * Get arrgroupcomp
     *
     * @return text 
     */
    public function getArrgroupcomp()
    {
        return $this->arrgroupcomp;
    }

    /**
     * Set count1
     *
     * @param integer $count1
     */
    public function setCount1($count1)
    {
        $this->count1 = $count1;
    }

    /**
     * Get count1
     *
     * @return integer 
     */
    public function getCount1()
    {
        return $this->count1;
    }

    /**
     * Set logType
     *
     * @param string $logType
     */
    public function setLogType($logType)
    {
        $this->logType = $logType;
    }

    /**
     * Get logType
     *
     * @return string 
     */
    public function getLogType()
    {
        return $this->logType;
    }

    /**
     * Set arrparam
     *
     * @param text $arrparam
     */
    public function setArrparam($arrparam)
    {
        $this->arrparam = $arrparam;
    }

    /**
     * Get arrparam
     *
     * @return text 
     */
    public function getArrparam()
    {
        return $this->arrparam;
    }

    /**
     * Set arrleftcol
     *
     * @param text $arrleftcol
     */
    public function setArrleftcol($arrleftcol)
    {
        $this->arrleftcol = $arrleftcol;
    }

    /**
     * Get arrleftcol
     *
     * @return text 
     */
    public function getArrleftcol()
    {
        return $this->arrleftcol;
    }

    /**
     * Set leftcol
     *
     * @param string $leftcol
     */
    public function setLeftcol($leftcol)
    {
        $this->leftcol = $leftcol;
    }

    /**
     * Get leftcol
     *
     * @return string 
     */
    public function getLeftcol()
    {
        return $this->leftcol;
    }

    /**
     * Set arrprovinces
     *
     * @param text $arrprovinces
     */
    public function setArrprovinces($arrprovinces)
    {
        $this->arrprovinces = $arrprovinces;
    }

    /**
     * Get arrprovinces
     *
     * @return text 
     */
    public function getArrprovinces()
    {
        return $this->arrprovinces;
    }

    /**
     * Set arrdistricts
     *
     * @param text $arrdistricts
     */
    public function setArrdistricts($arrdistricts)
    {
        $this->arrdistricts = $arrdistricts;
    }

    /**
     * Get arrdistricts
     *
     * @return text 
     */
    public function getArrdistricts()
    {
        return $this->arrdistricts;
    }

    /**
     * Set provinces
     *
     * @param text $provinces
     */
    public function setProvinces($provinces)
    {
        $this->provinces = $provinces;
    }

    /**
     * Get provinces
     *
     * @return text 
     */
    public function getProvinces()
    {
        return $this->provinces;
    }

    /**
     * Set districts
     *
     * @param text $districts
     */
    public function setDistricts($districts)
    {
        $this->districts = $districts;
    }

    /**
     * Get districts
     *
     * @return text 
     */
    public function getDistricts()
    {
        return $this->districts;
    }

    /**
     * Set selProv
     *
     * @param integer $selProv
     */
    public function setSelProv($selProv)
    {
        $this->selProv = $selProv;
    }

    /**
     * Get selProv
     *
     * @return integer 
     */
    public function getSelProv()
    {
        return $this->selProv;
    }

    /**
     * Set repDesc
     *
     * @param text $repDesc
     */
    public function setRepDesc($repDesc)
    {
        $this->repDesc = $repDesc;
    }

    /**
     * Get repDesc
     *
     * @return text 
     */
    public function getRepDesc()
    {
        return $this->repDesc;
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