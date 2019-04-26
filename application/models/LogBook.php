<?php

/**
 * Model_Locations
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Log Book
 */

class Model_LogBook extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('LogBook');
    }

    /**
     * Add Log
     * 
     */
    public function addLog() {


        $data = $this->form_values;

        $name = $data['name'];
        $father_name = $data['father_name'];
        $age = $data['age'];
        $contact = $data['contact'];
        $address = $data['address'];
        $district = $data['district'];
        $uc = $data['uc'];
        $item_id = $data['item_id'];
        $dose_no = $data['dose_no'];
        $vaccination_date = $data['vaccination_date'];
        $reffers_to = $data['reffers_to'];
        $remarks = $data['remarks'];
        $user_id = $this->_user_id;
        $warehouse_id = $this->_identity->getWarehouseId();
        foreach ($district as $key => $val) {
            if ($val != '' && $val >= 0) {

                $log_book = new LogBook();
                $log_book->setName($name[$key]);
                $log_book->setFatherName($father_name[$key]);
                $log_book->setAge($age[$key]);
                $log_book->setContact($contact[$key]);
                $log_book->setAddress($address[$key]);
                $districtId = $this->_em->getRepository('Locations')->find($district[$key]);
                $log_book->setDistrictId($districtId);

                $ucId = $this->_em->getRepository('Locations')->find($uc[$key]);
                $log_book->setUnionCouncilId($ucId);
                $log_book->setVaccinationDate(new \DateTime(App_Controller_Functions::dateToDbFormat($vaccination_date[$key])));
                $log_book->setRefferTo($reffers_to[$key]);
                $log_book->setRemarks($remarks[$key]);
                // 
                $warehouse = $this->_em->getRepository('Warehouses')->find($warehouse_id);
                $log_book->setWarehouse($warehouse);

                $log_book->setCreatedDate(App_Tools_Time::now());
                $log_book->setCreatedBy($user_id);
                $log_book->setModifiedBy($user_id);
                $log_book->setModifiedDate(App_Tools_Time::now());

                $this->_em->persist($log_book);
                $this->_em->flush();

                $log_book_id = $log_book->getPkId();

                foreach ($item_id as $key => $val) {
                    $log_book_item_doses = new LogBookItemDoses();
                    $logBook = $this->_em->getRepository('LogBook')->find($log_book_id);
                    $log_book_item_doses->setLogBookId($logBook);
                    $itemPack = $this->_em->getRepository('ItemPackSizes')->find($val);
                    $log_book_item_doses->setItemPackSize($itemPack);
                    $log_book_item_doses->setDoses($dose_no[$key]);

                    $log_book_item_doses->setCreatedDate(App_Tools_Time::now());
                    $log_book_item_doses->setCreatedBy($user_id);
                    $log_book_item_doses->setModifiedBy($user_id);
                    $log_book_item_doses->setModifiedDate(App_Tools_Time::now());

                    $this->_em->persist($log_book_item_doses);
                    $this->_em->flush();
                }
            }
        }
    }

    /**
     * Delete Log Book
     * 
     * @param type $id
     * @return boolean
     */
    public function deleteLogBook($id) {

        $log_book_doses = $this->_em->getRepository("LogBookItemDoses")->findBy(array("logBook" => $id));

        if (count($log_book_doses) > 0) {
            $this->_em->remove($log_book_doses[0]->getLogBook());
            foreach ($log_book_doses as $log_doses) {
                $this->_em->remove($log_doses);
            }
        }

        $this->_em->flush();

        return true;
    }

    /**
     * Check Logbook Name
     * 
     */
    public function checkLogbookName() {
        $form_values = $this->form_values;

        $temp = $form_values['do'];

        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));

        $temp = explode("|", $temp);

        $warehouse_id = $temp[0];
        $rpt_date = $temp[1];
        $tt = explode("-", $rpt_date);
        $yy = $tt[0];
        $mm = $tt[1];

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("lb")
                ->from("LogBook", "lb")
                ->where("lb.name = '" . $form_values['name'] . "'")
                ->andWhere("lb.fatherName = '" . $form_values['fname'] . "'")
                ->andWhere("lb.warehouse = '" . $warehouse_id . "'")
                ->andWhere("DATE_FORMAT(lb.vaccinationDate,'%Y-%m') = '" . $yy . "-" . $mm . "'");

        $result = $str_sql->getQuery()->getResult();

        if (count($result) > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * Get Log Book
     * 
     * @param type $wh_id
     * @param type $date_in
     * @return type
     */
    public function getLogBook($wh_id, $date_in) {
        $querypro = "SELECT
                    log_book.pk_id
                    FROM
                    log_book
                    WHERE
                    log_book.warehouse_id = '$wh_id' AND
                    DATE_FORMAT(log_book.vaccination_date,'%Y-%m') = '$date_in' ";
       
        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Log Book By Month
     * 
     * @return type
     */
    public function getLogBookByMonth() {
        $month = $this->form_values['month'];
        $year = $this->form_values['year'];
        $warehouse_id = $this->form_values['warehouse_id'];

        $str_qry = "SELECT
                            log_book.pk_id,
                            log_book.`name`,
                            log_book.father_name,
                            log_book.age,
                            log_book.gender,
                            log_book.contact,
                            log_book.address,
                            log_book.district_id,
                            log_book.union_council_id,
                            log_book.vaccination_date,
                            log_book.refer_to_warehouse_id,
                            log_book.remarks,
                            log_book.warehouse_id,
                            log_book.created_by,
                            log_book.created_date,
                            log_book.modified_date,
                            UC.location_name AS uc,
                            District.location_name AS district,
                            Tehsil.location_name AS tehsil
                        FROM
                            log_book
                            LEFT JOIN locations AS UC ON log_book.union_council_id = UC.pk_id
                            INNER JOIN locations AS District ON log_book.district_id = District.pk_id
                            LEFT JOIN locations AS Tehsil ON Tehsil.pk_id = UC.parent_id
                        WHERE log_book.district_id <> 0 
                        and log_book.warehouse_id = $warehouse_id 
                        and DATE_FORMAT(log_book.vaccination_date, '%Y-%m') =" . "'" . $year . "-" . "$month" . "'";

        $row = $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Add Log Book
     * 
     */
    public function addLogBook() {

        $dose_no = $this->form_values['dose_no_of'];

        $temp = $this->form_values['temp'];
        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));

        $temp = explode("|", $temp);


        $warehouse_id = $temp[0];
        $rpt_date = $temp[1];

        $tt = explode("-", $rpt_date);
        $yy = $tt[0];
        $mm = $tt[1];
        $vaccination_date = $yy . "-" . $mm . "-" . $this->form_values['day'];
        $user_id = $this->_user_id;

        $log_book = new LogBook();
        $log_book->setName($this->form_values['name']);
        $log_book->setFatherName($this->form_values['father_name']);
        $log_book->setAge($this->form_values['age']);
        $log_book->setGender($this->form_values['gender']);
        $log_book->setContact($this->form_values['contact']);
        $log_book->setAddress($this->form_values['address']);

        $districtId = $this->_em->getRepository('Locations')->find($this->form_values['district']);
        $log_book->setDistrict($districtId);
        if (!empty($this->form_values['uc'])) {

            $log_book->setUnionCouncilId($this->form_values['uc']);
        }
        $log_book->setVaccinationDate(new \DateTime($vaccination_date));
        $log_book->setRemarks($this->form_values['remarks']);

        $warehouse = $this->_em->getRepository('Warehouses')->find($warehouse_id);
        $log_book->setWarehouse($warehouse);
        $log_book->setCreatedDate(App_Tools_Time::now());
        $log_book->setModifiedDate(App_Tools_Time::now());
        $userId = $this->_em->getRepository('Users')->find($user_id);
        $log_book->setCreatedBy($userId);
        $log_book->setModifiedBy($userId);

        $this->_em->persist($log_book);
        $this->_em->flush();

        $log_book_id = $log_book->getPkId();

        foreach ($dose_no as $prodId => $value) {

            if ($value == "") {
                $value = NULL;
            }

            $log_book_item_doses = new LogBookItemDoses();
            $logBook = $this->_em->getRepository('LogBook')->find($log_book_id);
            $log_book_item_doses->setLogBook($logBook);
            $itemPack = $this->_em->getRepository('ItemPackSizes')->find($prodId);
            $log_book_item_doses->setItemPackSize($itemPack);
            $log_book_item_doses->setDoses($value);

            $user = $this->_em->getRepository('Users')->find($this->_user_id);
            $log_book_item_doses->setModifiedBy($user);
            $log_book_item_doses->setCreatedBy($user);
            $log_book_item_doses->setCreatedDate(App_Tools_Time::now());
            $log_book_item_doses->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($log_book_item_doses);
            $this->_em->flush();
        }
    }

    /**
     * Get Search Log Book
     * 
     * @return type
     */
    public function getSearchLogBook() {

        if (!empty($this->form_values['entry_type'])) {
            // My Entries
            if ($this->form_values['entry_type'] == "1") {
                $where[] = "log_book.created_by = '" . $this->_user_id . "'";
            }
            // Referrals
            if ($this->form_values['entry_type'] == "2") {
                $where[] = "log_book.created_by <> '" . $this->_user_id . "'";
            }
        } else {
            // Default is My Entries
            $where[] = "log_book.created_by = '" . $this->_user_id . "'";
        }

        if (!empty($this->form_values['district'])) {
            $where[] = "log_book.district_id = '" . $this->form_values['district'] . "'";
        } else {
            $where[] = "log_book.district_id = 0";
        }

        if (!empty($this->form_values['tehsil'])) {
            $where[] = "Uc.parent_id = '" . $this->form_values['tehsil'] . "'";
        }

        if (!empty($this->form_values['uc'])) {
            $where[] = "log_book.union_council_id = '" . $this->form_values['uc'] . "'";
        }


        if (!empty($this->form_values['vaccination_date_from']) && !empty($this->form_values['vaccination_date_to'])) {
            $where[] = "DATE_FORMAT(log_book.vaccination_date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['vaccination_date_from']) . "' AND  '" . App_Controller_Functions::dateToDbFormat($this->form_values['vaccination_date_to']) . "' ";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(log_book.vaccination_date,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_qry = "SELECT
                        log_book.union_council_id,
                        Uc.location_name AS Uc,
                        log_book.pk_id,
                        log_book.`name`,
                        log_book.father_name,
                        log_book.gender,
                        log_book.age,
                        log_book.contact,
                        log_book.address,
                        log_book.district_id,
                        log_book.vaccination_date,
                        log_book.refer_to_warehouse_id,
                        log_book.remarks,
                        log_book.warehouse_id,
                        log_book.created_by,
                        log_book.created_date,
                        log_book.modified_date,
                        log_book.reporting_start_date,
                        District.location_name AS District,
                        Tehsil.location_name AS Tehsil,
                        warehouses.warehouse_name AS RefFromEPI,
                        ref_from_uc.location_name AS RefFromUc,
                        ref_from_dist.location_name AS RefFromDist
                    FROM
                        log_book
                        LEFT JOIN locations AS Uc ON Uc.pk_id = log_book.union_council_id
                        INNER JOIN locations AS District ON log_book.district_id = District.pk_id
                        INNER JOIN locations AS Tehsil ON Uc.parent_id = Tehsil.pk_id
                        INNER JOIN warehouses ON log_book.warehouse_id = warehouses.pk_id
                        INNER JOIN locations AS ref_from_uc ON ref_from_uc.pk_id = warehouses.location_id
                        INNER JOIN locations AS ref_from_dist ON ref_from_dist.pk_id = warehouses.district_id WHERE 
                        "
                . "$where_s";


        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

}
