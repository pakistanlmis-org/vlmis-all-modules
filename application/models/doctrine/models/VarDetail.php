<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * VarDetail
 */
class VarDetail
{
    /**
     * @var integer $pkId
     */
    private $pkId;

    /**
     * @var string $country
     */
    private $country;

    /**
     * @var string $reportNo
     */
    private $reportNo;

    /**
     * @var date $reportDate
     */
    private $reportDate;

    /**
     * @var string $placeOfInspection
     */
    private $placeOfInspection;

    /**
     * @var date $dateOfInspection
     */
    private $dateOfInspection;

    /**
     * @var string $coldStore
     */
    private $coldStore;

    /**
     * @var date $dateOfVaccinesEnteredColdStore
     */
    private $dateOfVaccinesEnteredColdStore;

    /**
     * @var date $preAdviceDate
     */
    private $preAdviceDate;

    /**
     * @var date $shippingNotificationDate
     */
    private $shippingNotificationDate;

    /**
     * @var integer $awb
     */
    private $awb;

    /**
     * @var integer $packingList
     */
    private $packingList;

    /**
     * @var integer $invoice
     */
    private $invoice;

    /**
     * @var integer $releaseCertificate
     */
    private $releaseCertificate;

    /**
     * @var string $advanceNoteOtherDocument
     */
    private $advanceNoteOtherDocument;

    /**
     * @var string $awbNumber
     */
    private $awbNumber;

    /**
     * @var string $airportOfDestination
     */
    private $airportOfDestination;

    /**
     * @var string $flightNo
     */
    private $flightNo;

    /**
     * @var date $etaDate
     */
    private $etaDate;

    /**
     * @var date $actualTimeArrival
     */
    private $actualTimeArrival;

    /**
     * @var string $nameOfClearingAgent
     */
    private $nameOfClearingAgent;

    /**
     * @var string $onBehalf
     */
    private $onBehalf;

    /**
     * @var integer $quantityReceived
     */
    private $quantityReceived;

    /**
     * @var integer $detailShortShipment
     */
    private $detailShortShipment;

    /**
     * @var string $quantityReceivedComments
     */
    private $quantityReceivedComments;

    /**
     * @var string $detailShortShipmentComments
     */
    private $detailShortShipmentComments;

    /**
     * @var integer $documentsInvoice
     */
    private $documentsInvoice;

    /**
     * @var integer $documentsPackingList
     */
    private $documentsPackingList;

    /**
     * @var integer $documentsReleaseCertificate
     */
    private $documentsReleaseCertificate;

    /**
     * @var string $documentsVar
     */
    private $documentsVar;

    /**
     * @var string $documentOther
     */
    private $documentOther;

    /**
     * @var string $part4Comments
     */
    private $part4Comments;

    /**
     * @var string $totalNumberBoxesInspected
     */
    private $totalNumberBoxesInspected;

    /**
     * @var integer $dryIce
     */
    private $dryIce;

    /**
     * @var integer $icePacks
     */
    private $icePacks;

    /**
     * @var integer $noCoolant
     */
    private $noCoolant;

    /**
     * @var integer $coolantTypeEmpty
     */
    private $coolantTypeEmpty;

    /**
     * @var integer $vvm
     */
    private $vvm;

    /**
     * @var integer $coldChainCard
     */
    private $coldChainCard;

    /**
     * @var integer $electronicDevice
     */
    private $electronicDevice;

    /**
     * @var integer $temperatureMonitorsEmpty
     */
    private $temperatureMonitorsEmpty;

    /**
     * @var string $conditionOfBoxesArrival
     */
    private $conditionOfBoxesArrival;

    /**
     * @var string $labelsAttached
     */
    private $labelsAttached;

    /**
     * @var string $purchaseOrderNo
     */
    private $purchaseOrderNo;

    /**
     * @var string $consignee
     */
    private $consignee;

    /**
     * @var string $vaccineDescription
     */
    private $vaccineDescription;

    /**
     * @var string $manufacturer
     */
    private $manufacturer;

    /**
     * @var string $countryPart3
     */
    private $countryPart3;

    /**
     * @var text $otherCommentsElectronicDevice
     */
    private $otherCommentsElectronicDevice;

    /**
     * @var date $createdDate
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
     * @var StockMaster
     */
    private $stockMaster;

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
     * Set country
     *
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set reportNo
     *
     * @param string $reportNo
     */
    public function setReportNo($reportNo)
    {
        $this->reportNo = $reportNo;
    }

    /**
     * Get reportNo
     *
     * @return string 
     */
    public function getReportNo()
    {
        return $this->reportNo;
    }

    /**
     * Set reportDate
     *
     * @param date $reportDate
     */
    public function setReportDate($reportDate)
    {
        $this->reportDate = $reportDate;
    }

    /**
     * Get reportDate
     *
     * @return date 
     */
    public function getReportDate()
    {
        return $this->reportDate;
    }

    /**
     * Set placeOfInspection
     *
     * @param string $placeOfInspection
     */
    public function setPlaceOfInspection($placeOfInspection)
    {
        $this->placeOfInspection = $placeOfInspection;
    }

    /**
     * Get placeOfInspection
     *
     * @return string 
     */
    public function getPlaceOfInspection()
    {
        return $this->placeOfInspection;
    }

    /**
     * Set dateOfInspection
     *
     * @param date $dateOfInspection
     */
    public function setDateOfInspection($dateOfInspection)
    {
        $this->dateOfInspection = $dateOfInspection;
    }

    /**
     * Get dateOfInspection
     *
     * @return date 
     */
    public function getDateOfInspection()
    {
        return $this->dateOfInspection;
    }

    /**
     * Set coldStore
     *
     * @param string $coldStore
     */
    public function setColdStore($coldStore)
    {
        $this->coldStore = $coldStore;
    }

    /**
     * Get coldStore
     *
     * @return string 
     */
    public function getColdStore()
    {
        return $this->coldStore;
    }

    /**
     * Set dateOfVaccinesEnteredColdStore
     *
     * @param date $dateOfVaccinesEnteredColdStore
     */
    public function setDateOfVaccinesEnteredColdStore($dateOfVaccinesEnteredColdStore)
    {
        $this->dateOfVaccinesEnteredColdStore = $dateOfVaccinesEnteredColdStore;
    }

    /**
     * Get dateOfVaccinesEnteredColdStore
     *
     * @return date 
     */
    public function getDateOfVaccinesEnteredColdStore()
    {
        return $this->dateOfVaccinesEnteredColdStore;
    }

    /**
     * Set preAdviceDate
     *
     * @param date $preAdviceDate
     */
    public function setPreAdviceDate($preAdviceDate)
    {
        $this->preAdviceDate = $preAdviceDate;
    }

    /**
     * Get preAdviceDate
     *
     * @return date 
     */
    public function getPreAdviceDate()
    {
        return $this->preAdviceDate;
    }

    /**
     * Set shippingNotificationDate
     *
     * @param date $shippingNotificationDate
     */
    public function setShippingNotificationDate($shippingNotificationDate)
    {
        $this->shippingNotificationDate = $shippingNotificationDate;
    }

    /**
     * Get shippingNotificationDate
     *
     * @return date 
     */
    public function getShippingNotificationDate()
    {
        return $this->shippingNotificationDate;
    }

    /**
     * Set awb
     *
     * @param integer $awb
     */
    public function setAwb($awb)
    {
        $this->awb = $awb;
    }

    /**
     * Get awb
     *
     * @return integer 
     */
    public function getAwb()
    {
        return $this->awb;
    }

    /**
     * Set packingList
     *
     * @param integer $packingList
     */
    public function setPackingList($packingList)
    {
        $this->packingList = $packingList;
    }

    /**
     * Get packingList
     *
     * @return integer 
     */
    public function getPackingList()
    {
        return $this->packingList;
    }

    /**
     * Set invoice
     *
     * @param integer $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get invoice
     *
     * @return integer 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set releaseCertificate
     *
     * @param integer $releaseCertificate
     */
    public function setReleaseCertificate($releaseCertificate)
    {
        $this->releaseCertificate = $releaseCertificate;
    }

    /**
     * Get releaseCertificate
     *
     * @return integer 
     */
    public function getReleaseCertificate()
    {
        return $this->releaseCertificate;
    }

    /**
     * Set advanceNoteOtherDocument
     *
     * @param string $advanceNoteOtherDocument
     */
    public function setAdvanceNoteOtherDocument($advanceNoteOtherDocument)
    {
        $this->advanceNoteOtherDocument = $advanceNoteOtherDocument;
    }

    /**
     * Get advanceNoteOtherDocument
     *
     * @return string 
     */
    public function getAdvanceNoteOtherDocument()
    {
        return $this->advanceNoteOtherDocument;
    }

    /**
     * Set awbNumber
     *
     * @param string $awbNumber
     */
    public function setAwbNumber($awbNumber)
    {
        $this->awbNumber = $awbNumber;
    }

    /**
     * Get awbNumber
     *
     * @return string 
     */
    public function getAwbNumber()
    {
        return $this->awbNumber;
    }

    /**
     * Set airportOfDestination
     *
     * @param string $airportOfDestination
     */
    public function setAirportOfDestination($airportOfDestination)
    {
        $this->airportOfDestination = $airportOfDestination;
    }

    /**
     * Get airportOfDestination
     *
     * @return string 
     */
    public function getAirportOfDestination()
    {
        return $this->airportOfDestination;
    }

    /**
     * Set flightNo
     *
     * @param string $flightNo
     */
    public function setFlightNo($flightNo)
    {
        $this->flightNo = $flightNo;
    }

    /**
     * Get flightNo
     *
     * @return string 
     */
    public function getFlightNo()
    {
        return $this->flightNo;
    }

    /**
     * Set etaDate
     *
     * @param date $etaDate
     */
    public function setEtaDate($etaDate)
    {
        $this->etaDate = $etaDate;
    }

    /**
     * Get etaDate
     *
     * @return date 
     */
    public function getEtaDate()
    {
        return $this->etaDate;
    }

    /**
     * Set actualTimeArrival
     *
     * @param date $actualTimeArrival
     */
    public function setActualTimeArrival($actualTimeArrival)
    {
        $this->actualTimeArrival = $actualTimeArrival;
    }

    /**
     * Get actualTimeArrival
     *
     * @return date 
     */
    public function getActualTimeArrival()
    {
        return $this->actualTimeArrival;
    }

    /**
     * Set nameOfClearingAgent
     *
     * @param string $nameOfClearingAgent
     */
    public function setNameOfClearingAgent($nameOfClearingAgent)
    {
        $this->nameOfClearingAgent = $nameOfClearingAgent;
    }

    /**
     * Get nameOfClearingAgent
     *
     * @return string 
     */
    public function getNameOfClearingAgent()
    {
        return $this->nameOfClearingAgent;
    }

    /**
     * Set onBehalf
     *
     * @param string $onBehalf
     */
    public function setOnBehalf($onBehalf)
    {
        $this->onBehalf = $onBehalf;
    }

    /**
     * Get onBehalf
     *
     * @return string 
     */
    public function getOnBehalf()
    {
        return $this->onBehalf;
    }

    /**
     * Set quantityReceived
     *
     * @param integer $quantityReceived
     */
    public function setQuantityReceived($quantityReceived)
    {
        $this->quantityReceived = $quantityReceived;
    }

    /**
     * Get quantityReceived
     *
     * @return integer 
     */
    public function getQuantityReceived()
    {
        return $this->quantityReceived;
    }

    /**
     * Set detailShortShipment
     *
     * @param integer $detailShortShipment
     */
    public function setDetailShortShipment($detailShortShipment)
    {
        $this->detailShortShipment = $detailShortShipment;
    }

    /**
     * Get detailShortShipment
     *
     * @return integer 
     */
    public function getDetailShortShipment()
    {
        return $this->detailShortShipment;
    }

    /**
     * Set quantityReceivedComments
     *
     * @param string $quantityReceivedComments
     */
    public function setQuantityReceivedComments($quantityReceivedComments)
    {
        $this->quantityReceivedComments = $quantityReceivedComments;
    }

    /**
     * Get quantityReceivedComments
     *
     * @return string 
     */
    public function getQuantityReceivedComments()
    {
        return $this->quantityReceivedComments;
    }

    /**
     * Set detailShortShipmentComments
     *
     * @param string $detailShortShipmentComments
     */
    public function setDetailShortShipmentComments($detailShortShipmentComments)
    {
        $this->detailShortShipmentComments = $detailShortShipmentComments;
    }

    /**
     * Get detailShortShipmentComments
     *
     * @return string 
     */
    public function getDetailShortShipmentComments()
    {
        return $this->detailShortShipmentComments;
    }

    /**
     * Set documentsInvoice
     *
     * @param integer $documentsInvoice
     */
    public function setDocumentsInvoice($documentsInvoice)
    {
        $this->documentsInvoice = $documentsInvoice;
    }

    /**
     * Get documentsInvoice
     *
     * @return integer 
     */
    public function getDocumentsInvoice()
    {
        return $this->documentsInvoice;
    }

    /**
     * Set documentsPackingList
     *
     * @param integer $documentsPackingList
     */
    public function setDocumentsPackingList($documentsPackingList)
    {
        $this->documentsPackingList = $documentsPackingList;
    }

    /**
     * Get documentsPackingList
     *
     * @return integer 
     */
    public function getDocumentsPackingList()
    {
        return $this->documentsPackingList;
    }

    /**
     * Set documentsReleaseCertificate
     *
     * @param integer $documentsReleaseCertificate
     */
    public function setDocumentsReleaseCertificate($documentsReleaseCertificate)
    {
        $this->documentsReleaseCertificate = $documentsReleaseCertificate;
    }

    /**
     * Get documentsReleaseCertificate
     *
     * @return integer 
     */
    public function getDocumentsReleaseCertificate()
    {
        return $this->documentsReleaseCertificate;
    }

    /**
     * Set documentsVar
     *
     * @param string $documentsVar
     */
    public function setDocumentsVar($documentsVar)
    {
        $this->documentsVar = $documentsVar;
    }

    /**
     * Get documentsVar
     *
     * @return string 
     */
    public function getDocumentsVar()
    {
        return $this->documentsVar;
    }

    /**
     * Set documentOther
     *
     * @param string $documentOther
     */
    public function setDocumentOther($documentOther)
    {
        $this->documentOther = $documentOther;
    }

    /**
     * Get documentOther
     *
     * @return string 
     */
    public function getDocumentOther()
    {
        return $this->documentOther;
    }

    /**
     * Set part4Comments
     *
     * @param string $part4Comments
     */
    public function setPart4Comments($part4Comments)
    {
        $this->part4Comments = $part4Comments;
    }

    /**
     * Get part4Comments
     *
     * @return string 
     */
    public function getPart4Comments()
    {
        return $this->part4Comments;
    }

    /**
     * Set totalNumberBoxesInspected
     *
     * @param string $totalNumberBoxesInspected
     */
    public function setTotalNumberBoxesInspected($totalNumberBoxesInspected)
    {
        $this->totalNumberBoxesInspected = $totalNumberBoxesInspected;
    }

    /**
     * Get totalNumberBoxesInspected
     *
     * @return string 
     */
    public function getTotalNumberBoxesInspected()
    {
        return $this->totalNumberBoxesInspected;
    }

    /**
     * Set dryIce
     *
     * @param integer $dryIce
     */
    public function setDryIce($dryIce)
    {
        $this->dryIce = $dryIce;
    }

    /**
     * Get dryIce
     *
     * @return integer 
     */
    public function getDryIce()
    {
        return $this->dryIce;
    }

    /**
     * Set icePacks
     *
     * @param integer $icePacks
     */
    public function setIcePacks($icePacks)
    {
        $this->icePacks = $icePacks;
    }

    /**
     * Get icePacks
     *
     * @return integer 
     */
    public function getIcePacks()
    {
        return $this->icePacks;
    }

    /**
     * Set noCoolant
     *
     * @param integer $noCoolant
     */
    public function setNoCoolant($noCoolant)
    {
        $this->noCoolant = $noCoolant;
    }

    /**
     * Get noCoolant
     *
     * @return integer 
     */
    public function getNoCoolant()
    {
        return $this->noCoolant;
    }

    /**
     * Set coolantTypeEmpty
     *
     * @param integer $coolantTypeEmpty
     */
    public function setCoolantTypeEmpty($coolantTypeEmpty)
    {
        $this->coolantTypeEmpty = $coolantTypeEmpty;
    }

    /**
     * Get coolantTypeEmpty
     *
     * @return integer 
     */
    public function getCoolantTypeEmpty()
    {
        return $this->coolantTypeEmpty;
    }

    /**
     * Set vvm
     *
     * @param integer $vvm
     */
    public function setVvm($vvm)
    {
        $this->vvm = $vvm;
    }

    /**
     * Get vvm
     *
     * @return integer 
     */
    public function getVvm()
    {
        return $this->vvm;
    }

    /**
     * Set coldChainCard
     *
     * @param integer $coldChainCard
     */
    public function setColdChainCard($coldChainCard)
    {
        $this->coldChainCard = $coldChainCard;
    }

    /**
     * Get coldChainCard
     *
     * @return integer 
     */
    public function getColdChainCard()
    {
        return $this->coldChainCard;
    }

    /**
     * Set electronicDevice
     *
     * @param integer $electronicDevice
     */
    public function setElectronicDevice($electronicDevice)
    {
        $this->electronicDevice = $electronicDevice;
    }

    /**
     * Get electronicDevice
     *
     * @return integer 
     */
    public function getElectronicDevice()
    {
        return $this->electronicDevice;
    }

    /**
     * Set temperatureMonitorsEmpty
     *
     * @param integer $temperatureMonitorsEmpty
     */
    public function setTemperatureMonitorsEmpty($temperatureMonitorsEmpty)
    {
        $this->temperatureMonitorsEmpty = $temperatureMonitorsEmpty;
    }

    /**
     * Get temperatureMonitorsEmpty
     *
     * @return integer 
     */
    public function getTemperatureMonitorsEmpty()
    {
        return $this->temperatureMonitorsEmpty;
    }

    /**
     * Set conditionOfBoxesArrival
     *
     * @param string $conditionOfBoxesArrival
     */
    public function setConditionOfBoxesArrival($conditionOfBoxesArrival)
    {
        $this->conditionOfBoxesArrival = $conditionOfBoxesArrival;
    }

    /**
     * Get conditionOfBoxesArrival
     *
     * @return string 
     */
    public function getConditionOfBoxesArrival()
    {
        return $this->conditionOfBoxesArrival;
    }

    /**
     * Set labelsAttached
     *
     * @param string $labelsAttached
     */
    public function setLabelsAttached($labelsAttached)
    {
        $this->labelsAttached = $labelsAttached;
    }

    /**
     * Get labelsAttached
     *
     * @return string 
     */
    public function getLabelsAttached()
    {
        return $this->labelsAttached;
    }

    /**
     * Set purchaseOrderNo
     *
     * @param string $purchaseOrderNo
     */
    public function setPurchaseOrderNo($purchaseOrderNo)
    {
        $this->purchaseOrderNo = $purchaseOrderNo;
    }

    /**
     * Get purchaseOrderNo
     *
     * @return string 
     */
    public function getPurchaseOrderNo()
    {
        return $this->purchaseOrderNo;
    }

    /**
     * Set consignee
     *
     * @param string $consignee
     */
    public function setConsignee($consignee)
    {
        $this->consignee = $consignee;
    }

    /**
     * Get consignee
     *
     * @return string 
     */
    public function getConsignee()
    {
        return $this->consignee;
    }

    /**
     * Set vaccineDescription
     *
     * @param string $vaccineDescription
     */
    public function setVaccineDescription($vaccineDescription)
    {
        $this->vaccineDescription = $vaccineDescription;
    }

    /**
     * Get vaccineDescription
     *
     * @return string 
     */
    public function getVaccineDescription()
    {
        return $this->vaccineDescription;
    }

    /**
     * Set manufacturer
     *
     * @param string $manufacturer
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * Get manufacturer
     *
     * @return string 
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Set countryPart3
     *
     * @param string $countryPart3
     */
    public function setCountryPart3($countryPart3)
    {
        $this->countryPart3 = $countryPart3;
    }

    /**
     * Get countryPart3
     *
     * @return string 
     */
    public function getCountryPart3()
    {
        return $this->countryPart3;
    }

    /**
     * Set otherCommentsElectronicDevice
     *
     * @param text $otherCommentsElectronicDevice
     */
    public function setOtherCommentsElectronicDevice($otherCommentsElectronicDevice)
    {
        $this->otherCommentsElectronicDevice = $otherCommentsElectronicDevice;
    }

    /**
     * Get otherCommentsElectronicDevice
     *
     * @return text 
     */
    public function getOtherCommentsElectronicDevice()
    {
        return $this->otherCommentsElectronicDevice;
    }

    /**
     * Set createdDate
     *
     * @param date $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return date 
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
     * Set stockMaster
     *
     * @param StockMaster $stockMaster
     */
    public function setStockMaster(\StockMaster $stockMaster)
    {
        $this->stockMaster = $stockMaster;
    }

    /**
     * Get stockMaster
     *
     * @return StockMaster 
     */
    public function getStockMaster()
    {
        return $this->stockMaster;
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