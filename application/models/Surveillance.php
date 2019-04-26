<?php

/**
 * Model_Surveillance
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Surveillance
 */
class Model_Surveillance extends Model_Base {

    /**
     * $_table
     * @var type
     */
    protected $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('VpdForm');
    }

    /**
     * Add Vpd
     *
     * @return boolean
     */
    public function addVpd() {
        $form_values = $this->form_values;

        $user_id = $this->_user_id;
        $str_sql_del = "DELETE
            vpd_form_draft.*

            FROM
            vpd_form_draft
            where
            vpd_form_draft.created_by = $user_id";

        $rec_del = $this->_em->getConnection()->prepare($str_sql_del);
        $rec_del->execute();



        if (!empty($form_values['vpd_id'])) {
            $vpd_form = $this->_em->getRepository("VpdForm")->find($form_values['vpd_id']);
            // code to write
        } else {
            $vpd_form = new VpdForm();
        }
//        echo $form_values['date'];exit;
        //$r_date = Date("Y-m-d");
        $vpd_form->setDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date'])));

        $reporting_date = App_Controller_Functions::dateToDbFormat($form_values['date']);
        $r_date = Date("Y-m-d");
        $week_i = $form_values['week'];
        $str_sql1 = "SELECT

	Date_format(
		vpd_weeks.report_date,
		'%Y-%m-%d'
	) report_date,
        YEAR(vpd_weeks.start_date)
        FROM
                vpd_weeks
        WHERE
                YEAR(vpd_weeks.start_date) = YEAR('$reporting_date')
        AND vpd_weeks.`week` = $week_i ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql1);
        $rec->execute();
        $res = $rec->fetchAll();

        //$week = $res[0]['week'];
        $report_date = $res[0]['report_date'];


        if ($reporting_date > $report_date) {
            $vpd_form->setReportingStatus(0);
        } else {
            $vpd_form->setReportingStatus(1);
        }

        $vpd_form->setWeek($form_values['week']);
        if (!empty($form_values['district'])) {
            $vpd_form->setDistrictId($form_values['district']);
        }
        if (!empty($form_values['tehsil'])) {
            $vpd_form->setTehsilId($form_values['tehsil']);
        }
        if (!empty($form_values['ucs'])) {
            $vpd_form->setUcId($form_values['ucs']);
        }
        if (!empty($form_values['case_health_facility'])) {
            $vpd_form->setHfId($form_values['case_health_facility']);
        }
        if (!empty($form_values['other_case_health_facility'])) {
            $vpd_form->setHfName($form_values['other_case_health_facility']);
        }
        // from uc id
        if (!empty($form_values['district_ucs'])) {
            $vpd_form->setFromUcId($form_values['district_ucs']);
        }
        // end

        $vpd_form->setTypeCase($form_values['t_o_case']);
        $vpd_form->setEpidNumber($form_values['epid_number']);
        $vpd_form->setChildName($form_values['child_name']);
        $vpd_form->setFatherName($form_values['father_name']);

        // address
        $vpd_form->setAddress1($form_values['address1']);

        $vpd_form->setAddressVillage($form_values['address_town']);
        // end
        $vpd_form->setContactNumber($form_values['contact_number']);
        $vpd_form->setCrossNotification($form_values['cross_notification']);
        if (!empty($form_values['birth_date'])) {
            $vpd_form->setDob(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['birth_date'])));
        }
        if (!empty($form_values['datepicker_patient_visited'])) {
            $vpd_form->setPatientVisitedDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_patient_visited'])));
        }
        // age , age unit
        if (!empty($form_values['age_month'])) {
            $vpd_form->setAge($form_values['age_month']);
        }

        if (!empty($form_values['StoolSampleDate1'])) {
            $vpd_form->setStoolSampleDate1(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['StoolSampleDate1'])));
        }
        if (!empty($form_values['StoolSampleDate2'])) {
            $vpd_form->setStoolSampleDate2(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['StoolSampleDate2'])));
        }

        $vpd_form->setAgeUnit($form_values['age_unit']);
        // end
        $vpd_form->setSpecificDoseReceived($form_values['Vaccine_Doses_Recived']);
        $vpd_form->setGender($form_values['gender']);
        $vpd_form->setIsTheChildProtected($form_values['is_child_protected']);
        $vpd_form->setHasChildReceivedOpv($form_values['has_child_received']);
        if (!empty($form_values['l_v_d_received_other'])) {
            $vpd_form->setNumberOfMonths($form_values['l_v_d_received_other']);
        }
        if (!empty($form_values['date_of_specimen_collection'])) {
            $vpd_form->setSpecimenCollectionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_specimen_collection'])));
        }
        if (!empty($form_values['date_of_lab_resulted'])) {
            $vpd_form->setDateOfLabResulted(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_lab_resulted'])));
        }
        if (!empty($form_values['datepicker_Notification'])) {
            $vpd_form->setDateNotification(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_Notification'])));
        }
        if (!empty($form_values['datepicker_Investigation'])) {
            $vpd_form->setDateInvestigation(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_Investigation'])));
        }if (!empty($form_values['l_v_d_received'])) {
            $vpd_form->setDateLastDoseReceived(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['l_v_d_received'])));
        }$vpd_form->setSpecimenCollection($form_values['Specimen_Collection']);
        if (!empty($form_values['date_of_specimen_sent'])) {
            $vpd_form->setDateSpecimenSent(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_specimen_sent'])));
        }if (!empty($form_values['date_of_specimen_sent1'])) {
            $vpd_form->setDateSpecimenSent1(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_of_specimen_sent1'])));
        }
        if (!empty($form_values['outcome'])) {
            $vpd_form->setOutcome($form_values['outcome']);
        }
        if (!empty($form_values['birth_attendee'])) {
            $vpd_form->setAttendee($form_values['birth_attendee']);
        }
        if (!empty($form_values['place_of_birth'])) {
            $vpd_form->setPlaceOfBirth($form_values['place_of_birth']);
        }
        if (!empty($form_values['lab_result_test'])) {
            $vpd_form->setLabResult($form_values['lab_result_test']);
        }
        if (!empty($form_values['final_classification'])) {
            $vpd_form->setFinalClassification($form_values['final_classification']);
        }
        if (!empty($form_values['Blood_Sample_Date'])) {
            $vpd_form->setBloodSampleDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Blood_Sample_Date'])));
        }
        if (!empty($form_values['Throad_Swab_Date'])) {
            $vpd_form->setBloodSampleMonth(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Throad_Swab_Date'])));
        }
        if (!empty($form_values['Naso_Pharyngeal_Swab_Date'])) {
            $vpd_form->setBloodSampleDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Naso_Pharyngeal_Swab_Date'])));
        }
        if (!empty($form_values['Sputum_Collection_Date_1'])) {
            $vpd_form->setBloodSampleDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Sputum_Collection_Date_1'])));
        }

        if (!empty($form_values['Sputum_Collection_Date_2'])) {
            $vpd_form->setBloodSampleMonth(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['Sputum_Collection_Date_2'])));
        }
        if (!empty($form_values['datepicker_Onset'])) {
            $vpd_form->setDateOnset(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['datepicker_Onset'])));
        }
        if ($this->_identity->getRoleId() == 61) {
            $admin_dis = $form_values['district_admin'];
            $str_sql1 = "SELECT
warehouse_users.user_id as user_id
FROM
warehouses
INNER JOIN warehouse_users ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN users ON warehouse_users.user_id = users.pk_id
WHERE
warehouses.district_id = $admin_dis AND
users.role_id = 48";
// print_r($str_sql1);  exit; 
            $rec = $this->_em_read->getConnection()->prepare($str_sql1);
            $rec->execute();
            $res = $rec->fetchAll();
//            print_r($res);exit;
            $vpd_form->setCreatedBy($res[0]['user_id']);
            $vpd_form->setCreatedDate(App_Tools_Time::now());
            $vpd_form->setModifiedBy($this->_user_id);
            $vpd_form->setModifiedDate(App_Tools_Time::now());
        } else {
            $vpd_form->setCreatedBy($this->_user_id);
            $vpd_form->setCreatedDate(App_Tools_Time::now());
            $vpd_form->setModifiedBy($this->_user_id);
            $vpd_form->setModifiedDate(App_Tools_Time::now());
        }
        $this->_em->persist($vpd_form);
        $this->_em->flush();

        // vpd form clinical presentation


        foreach ($form_values['clinical_presentation'] as $index => $row) {


            $vpd_clinical_presentation = new VpdClinicalPresentation();
            $vpd_clinical_presentation->setClinicalPresentationId($row);
            $vpd_form_id = $this->_em->getRepository("VpdForm")->find($vpd_form->getPkId());

            $vpd_clinical_presentation->setVpdForm($vpd_form_id);

            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $vpd_clinical_presentation->setCreatedBy($created_by);
            $vpd_clinical_presentation->setCreatedDate(App_Tools_Time::now());
            $vpd_clinical_presentation->setModifiedBy($created_by);
            $vpd_clinical_presentation->setModifiedDate(App_Tools_Time::now());



            $this->_em->persist($vpd_clinical_presentation);
        }

        $this->_em->flush();

        // end
        return $vpd_form->getPkId();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getVpd() {
        $form_values = $this->form_values;

        $user_id = $this->_user_id;
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(vpd_form.date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(vpd_form.date,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }
        if (!empty($form_values['district'])) {
            $where[] = "dis.pk_id = '" . $form_values['district'] . "'";
        } else {
            $where[] = "dis.province_id IN (1,2) ";
        }
        if (!empty($form_values['tehsil'])) {
            $where[] = "teh.pk_id  = '" . $form_values['tehsil'] . "'";
        }
        if (!empty($form_values['ucs'])) {
            $where[] = "uc.pk_id  = '" . $form_values['ucs'] . "'";
        }
        if (!empty($form_values['t_o_case1'])) {
            $where[] = "vpd_form.type_case  = '" . $form_values['t_o_case1'] . "'";
        } else {
            $where[] = "vpd_form.type_case  = '246'";
        }
        if ($this->_identity->getRoleId() != 61) {
            $where[] = "vpd_form.created_by  = '" . $user_id . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }



        if (!empty($form_values['location_name_hdn'])) {
            $location_name = $form_values['location_name_hdn'];
            $str_sql_order = "Order By l.location_name='$location_name' DESC,l.location_name";
        } else {
            $str_sql_order = "Order By vpd_form.pk_id DESC";
        }
        if ($form_values['t_o_case1'] == 252) {
            if (!empty($form_values['t_o_case1'])) {
                $where1[] = "vpd_form.type_case  = '" . $form_values['t_o_case1'] . "'";
            }

            if (is_array($where)) {
                $where_s1 = implode(" AND ", $where1);
            }
            $str_sql = "SELECT
        vpd_form.pk_id,
        vpd_form.date,
        vpd_form.district_id,
        vpd_form.tehsil_id,
        vpd_form.uc_id,
        vpd_form.hf_id,
        vpd_form.week,
        vpd_form.hf_name,
        vpd_form.type_case,
        vpd_form.epid_number,
        vpd_form.has_child_received_opv,
        vpd_form.child_name,
        vpd_form.father_name,
        vpd_form.address1 complete_address,
        vpd_form.contact_number,
        vpd_form.cross_notification,
        vpd_form.dob,
        vpd_form.age age_in_month,
        vpd_form.gender,
        vpd_form.date_onset,
        vpd_form.date_notification,
        vpd_form.date_investigation,
        vpd_form.specific_dose_received,
        vpd_form.date_last_dose_received,
        vpd_form.specimen_collection,
        vpd_form.date_specimen_sent,
        vpd_form.date_specimen_sent1,
        vpd_form.sign_symptoms,
        vpd_form.outcome,
        vpd_form.attendee,
        vpd_form.place_of_birth,
        vpd_form.lab_result,
        vpd_form.final_classification,
        DATE_FORMAT(vpd_form.stool_sample_date1,'%d/%m/%Y') stool_sample_date1,
        DATE_FORMAT(vpd_form.stool_sample_date2,'%d/%m/%Y') stool_sample_date2,
        DATE_FORMAT(vpd_form.blood_sample_date,'%d/%m/%Y') blood_sample_date,
        DATE_FORMAT(vpd_form.blood_sample_month,'%d/%m/%Y') blood_sample_month,

        vpd_form.created_date,
        vpd_form.created_by,
        vpd_form.modified_date,
        vpd_form.modified_by,
        list_detail.list_value as type_case
        FROM
        vpd_form

        INNER JOIN list_detail ON vpd_form.type_case = list_detail.pk_id
        WHERE  $where_s1   $str_sql_order";
        } else {
            $str_sql = "SELECT
	vpd_form.pk_id,
	DATE_FORMAT(vpd_form.date, '%d/%m/%Y') AS date,
	vpd_form.district_id,
	vpd_form.tehsil_id,
	vpd_form.uc_id,
        vpd_form.date,
	vpd_form.hf_id,
	vpd_form.hf_name,
	vpd_form.type_case,
	vpd_form.epid_number,
	vpd_form.child_name,
        vpd_form.has_child_received_opv,
        vpd_form.specific_dose_received,
        vpd_form.number_of_months,
	vpd_form.father_name,
	vpd_form.address1,
	vpd_form.contact_number,
	vpd_form.cross_notification,
	DATE_FORMAT(vpd_form.dob, '%d/%m/%Y') AS dob,
	vpd_form.age,
	vpd_form.gender AS gender,
	vpd_form.clinical_presentation,

        DATE_FORMAT(
		vpd_form.date_of_lab_resulted,
		'%d/%m/%Y'
	) AS date_of_lab_resulted,
	DATE_FORMAT(
		vpd_form.date_onset,
		'%d/%m/%Y'
	) AS date_onset,
	DATE_FORMAT(
		vpd_form.date_notification,
		'%d/%m/%Y'
	) AS date_notification,
        DATE_FORMAT(
		vpd_form.patient_visited_date,
		'%d/%m/%Y'
	) AS patient_visited_date,
	DATE_FORMAT(
		vpd_form.date_investigation,
		'%d/%m/%Y'
	) AS date_investigation,
	DATE_FORMAT(
		vpd_form.date_last_dose_received,
		'%d/%m/%Y'
	) AS date_last_dose_received,
	vpd_form.specimen_collection,
	DATE_FORMAT(
		vpd_form.date_specimen_sent,
		'%d/%m/%Y'
	) AS date_specimen_sent,
        DATE_FORMAT(
		vpd_form.date_specimen_sent1,
		'%d/%m/%Y'
	) AS date_specimen_sent1,
	vpd_form.sign_symptoms,
	vpd_form.outcome,
        vpd_form.attendee,
        vpd_form.place_of_birth,
	vpd_form.lab_result,
	vpd_form.final_classification,
	DATE_FORMAT(vpd_form.stool_sample_date1,'%d/%m/%Y') stool_sample_date1,
        DATE_FORMAT(vpd_form.stool_sample_date2,'%d/%m/%Y') stool_sample_date2,
        DATE_FORMAT(vpd_form.blood_sample_date,'%d/%m/%Y') blood_sample_date,
        DATE_FORMAT(vpd_form.blood_sample_month,'%d/%m/%Y') blood_sample_month,
	vpd_form.week,
	vpd_form.created_date,
	vpd_form.created_by,
	vpd_form.modified_date,
	vpd_form.modified_by,
	list_detail.list_value AS typeCase,
	vpd_form.from_uc_id,
	vpd_form.address2,
	vpd_form.address_village,
	vpd_form.age_unit,
	from_uc.location_name AS from_uc,
	dis.location_name AS district,
        teh.location_name AS tehsil,
        uc.location_name AS uc,
        GROUP_CONCAT(cli.list_value) as sign,
        warehouses.warehouse_name as s_site,
        lab.list_value as lab,
        final.list_value as final,
        outcome.list_value as outcome,
        attende.list_value as attendee,
        birth_place.list_value as place_of_birth,
        prov.location_name as province,
        prov.pk_id as province_id
        FROM
        vpd_form
        INNER JOIN list_detail ON vpd_form.type_case = list_detail.pk_id
        INNER JOIN locations AS from_uc ON vpd_form.from_uc_id = from_uc.pk_id
        INNER JOIN locations AS dis ON vpd_form.district_id = dis.pk_id
        INNER JOIN locations AS prov ON dis.province_id = prov.pk_id
        LEFT JOIN locations AS teh ON vpd_form.tehsil_id = teh.pk_id
        LEFT JOIN locations AS uc ON vpd_form.uc_id = uc.pk_id
        INNER JOIN vpd_clinical_presentation ON vpd_form.pk_id = vpd_clinical_presentation.vpd_form_id
        INNER JOIN list_detail as cli ON vpd_clinical_presentation.clinical_presentation_id = cli.pk_id
        LEFT JOIN warehouses  ON warehouses.pk_id = vpd_form.hf_id
        LEFT JOIN list_detail as lab ON vpd_form.lab_result = lab.pk_id
        INNER JOIN list_detail as final ON final.pk_id = vpd_form.final_classification
        INNER JOIN list_detail as outcome ON vpd_form.outcome = outcome.pk_id
        LEFT JOIN list_detail as birth_place ON vpd_form.place_of_birth = birth_place.pk_id
        LEFT JOIN list_detail as attende ON vpd_form.attendee = attende.pk_id
        WHERE  $where_s    GROUP BY pk_id $str_sql_order";
        }




        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Add AEFI
     *
     * @return boolean
     */
    public function addAefi() {

        $form_values = $this->form_values;
        $user_id = $this->_user_id;
        $str_sql_del = "DELETE
            aefi_form_draft.*

            FROM
            aefi_form_draft
            where
            aefi_form_draft.created_by = $user_id";

        $rec_del = $this->_em->getConnection()->prepare($str_sql_del);
        $rec_del->execute();


        // App_Controller_Functions::pr($form_values);
        if (!empty($form_values['aefi_id'])) {

            $aefi_form = $this->_em->getRepository("AefiForm")->find($form_values['aefi_id']);
            // code to write
        }
        $aefi_form = new AefiForm();



        $reporting_date = App_Controller_Functions::dateToDbFormat($form_values['date']);
        $r_date = Date("Y-m-d");
        $str_sql1 = "SELECT
        MIN(vpd_weeks.`week`) as week,
        vpd_weeks.start_date,
        Date_format('vpd_weeks.report_date','%Y-%m-%d') report_date
        FROM
        vpd_weeks
        where Date_format('$reporting_date','%Y-%m-%d')  BETWEEN  Date_format(vpd_weeks.start_date,'%Y-%m-%d')  AND Date_format(vpd_weeks.end_date,'%Y-%m-%d')   ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql1);
        $rec->execute();
        $res = $rec->fetchAll();
        $week = $res[0]['week'];
        $report_date = $res[0]['report_date'];


        if ($report_date > $reporting_date) {
            $aefi_form->setReportingStatus(0);
        } else {
            $aefi_form->setReportingStatus(1);
        }

        if (!empty($form_values['date'])) {
            $aefi_form->setDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date'])));
        }
        if (!empty($form_values['other_case_health_facility'])) {
            $aefi_form->setHfName($form_values['other_case_health_facility']);
        }

        $aefi_form->setWeek($form_values['week']);
        if (!empty($form_values['type_of_case'])) {
            $aefi_form->setTypeCase($form_values['type_of_case']);
        }
        if (!empty($form_values['date_notification'])) {
            $aefi_form->setDateNotification(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_notification'])));
        }

        if (!empty($form_values['district'])) {
            $aefi_form->setDistrictId($form_values['district']);
        }
        if (!empty($form_values['district_case'])) {
            $aefi_form->setDistrictId1($form_values['district_case']);
        }
        if (!empty($form_values['tehsil'])) {
            $aefi_form->setTehsilId($form_values['tehsil']);
        }
        if (!empty($form_values['tehsil_case'])) {
            $aefi_form->setTehsilId1($form_values['tehsil_case']);
        }
        if (!empty($form_values['uc'])) {
            $aefi_form->setUcId($form_values['uc']);
        }
        if (!empty($form_values['uc_case'])) {
            $aefi_form->setUcId1($form_values['uc_case']);
        }
        $aefi_form->setDob(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['birth_date'])));
        if (!empty($form_values['age_month'])) {
            $aefi_form->setAgeInMonths($form_values['age_month']);
        }
        $aefi_form->setGender($form_values['gender']);

        if (!empty($form_values['cash_reported_from_h_f'])) {
            $aefi_form->setHfId($form_values['cash_reported_from_h_f']);
        }
        if (!empty($form_values['aefi'])) {
            $aefi_form->setAefi($form_values['aefi']);
        }
        $aefi_form->setVaccineAntigen($form_values['vaccine_antigen']);
        if (!empty($form_values['batch_no'])) {
            $aefi_form->setBatchId($form_values['batch_no']);
        }
        if (!empty($form_values['other_batch_no'])) {
            $aefi_form->setBatchNumber($form_values['other_batch_no']);
        }

        if (!empty($form_values['case_vaccinated_epi'])) {
            $aefi_form->setCaseEpi($form_values['case_vaccinated_epi']);
        }
        if (!empty($form_values['other_case_vaccinated_epi'])) {
            $aefi_form->setOtherCaseEpi($form_values['other_case_vaccinated_epi']);
        }
        if (!empty($form_values['expiry_date'])) {
            $aefi_form->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['expiry_date'])));
        }
        $aefi_form->setDateVaccineGiven(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_vaccine_given'])));

        $aefi_form->setDateAefiOnset(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_aefi_onset'])));
        $aefi_form->setDateOfInvestigation(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_Investigation'])));
        $aefi_form->setAefiOther($form_values['other_aefi']);

        $aefi_form->setChildName($form_values['child_name']);
        $aefi_form->setFatherName($form_values['father_name']);

        // address
        $aefi_form->setAddress1($form_values['address1']);

        $aefi_form->setAddressVillage($form_values['address_town']);
        // end
        $aefi_form->setContactNumber($form_values['contact_number']);
        $aefi_form->setHospitalization($form_values['hospitalization']);
        $aefi_form->setDeath($form_values['death']);
        $aefi_form->setAgeUnit($form_values['age_unit']);
        $aefi_form->setDesignation($form_values['desig']);
        $aefi_form->setVaccinatorName($form_values['vaccinator']);

        if ($this->_identity->getRoleId() == 61) {
            $admin_dis = $form_values['district_admin'];
            $str_sql1 = "SELECT
            warehouse_users.user_id as user_id
            FROM
            warehouses
            INNER JOIN warehouse_users ON warehouse_users.warehouse_id = warehouses.pk_id
            INNER JOIN users ON warehouse_users.user_id = users.pk_id
            WHERE
            warehouses.district_id = $admin_dis AND
            users.role_id = 48";

            $rec = $this->_em_read->getConnection()->prepare($str_sql1);
            $rec->execute();
            $res = $rec->fetchAll();
//            print_r($res);exit;
            $aefi_form->setCreatedBy($res[0]['user_id']);
            $aefi_form->setCreatedDate(App_Tools_Time::now());
            $aefi_form->setModifiedBy($this->_user_id);
            $aefi_form->setModifiedDate(App_Tools_Time::now());
        } else {
            $aefi_form->setCreatedBy($this->_userid);
            $aefi_form->setCreatedDate(App_Tools_Time::now());
            $aefi_form->setModifiedBy($this->_userid);
            $aefi_form->setModifiedDate(App_Tools_Time::now());
        }
        $this->_em->persist($aefi_form);
        $this->_em->flush();
        return $aefi_form->getPkId();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getAefi() {
        $form_values = $this->form_values;

        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(aefi_form.date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(aefi_form.date,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }
        if (!empty($form_values['district'])) {
            $where[] = "dis.pk_id = '" . $form_values['district'] . "'";
        } else {
            $where[] = "dis.province_id = '2' ";
        }
        if (!empty($form_values['tehsil'])) {
            $where[] = "teh.pk_id  = '" . $form_values['tehsil'] . "'";
        }
        if (!empty($form_values['ucs'])) {
            $where[] = "uc.pk_id  = '" . $form_values['ucs'] . "'";
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }



        if (!empty($form_values['location_name_hdn'])) {
            $location_name = $form_values['location_name_hdn'];
            $str_sql_order = "Order By l.location_name='$location_name' DESC,l.location_name";
        } else {
            $str_sql_order = "";
        }
        if ($form_values['type_of_case1'] == 0) {

            $where1[] = "aefi_form.type_case  = '" . $form_values['type_of_case1'] . "'";

            if (is_array($where)) {
                $where_s1 = implode(" AND ", $where1);
            }

            $str_sql = "SELECT aefi_form.pk_id,
        aefi_form.type_case,
        DATE_FORMAT(aefi_form.date,'%d/%m/%Y') date,
        aefi_form.district_id,
        aefi_form.week,
        aefi_form.vaccinator_name,
        aefi_form.designation,
        aefi_form.tehsil_id,
        aefi_form.uc_id,
        aefi_form.hf_id,
        aefi_form.contact_number,
        aefi_form.district_id1,
        aefi_form.tehsil_id1,
        aefi_form.uc_id1,
        aefi_form.hf_id1,
        aefi_form.case_epi,
        aefi_form.other_case_epi,
        aefi_form.address_village,
        aefi_form.address1,
        aefi_form.hf_name,
        aefi_form.gender,
        DATE_FORMAT(aefi_form.dob,'%d/%m/%Y') dob,
        aefi_form.age_in_months,
        DATE_FORMAT(aefi_form.date_vaccine_given,'%d/%m/%Y') date_vaccine_given,
        DATE_FORMAT(aefi_form.date_aefi_onset,'%d/%m/%Y') date_aefi_onset,
        aefi_form.child_name,
        aefi_form.father_name,
        DATE_FORMAT(  aefi_form.date_of_investigation,'%d/%m/%Y') date_of_investigation,
        DATE_FORMAT(  aefi_form.date_notification,'%d/%m/%Y') date_notification,
        DATE_FORMAT(  aefi_form.expiry_date,'%d/%m/%Y') expiry_date,
        'Not Reported' as aefi,
        aefi_form.aefi_other,
        aefi_form.hospitalization,
        aefi_form.death,
        aefi_form.vaccine_antigen,
        aefi_form.batch_id,
        aefi_form.age_unit,
        aefi_form.batch_number,
        aefi_form.created_date,
        aefi_form.created_by,
        aefi_form.modified_date,
        aefi_form.modified_by

        FROM
        aefi_form


        WHERE  $where_s1   $str_sql_order";
        } else {

            $str_sql = "SELECT aefi_form.pk_id,
        aefi_form.type_case,
        DATE_FORMAT(aefi_form.date,'%d/%m/%Y') date,
        aefi_form.district_id,
        aefi_form.week,
        aefi_form.vaccinator_name,
        aefi_form.designation,
        aefi_form.tehsil_id,
        aefi_form.uc_id,
        aefi_form.hf_id,
        aefi_form.contact_number,
        aefi_form.district_id1,
        aefi_form.tehsil_id1,
        aefi_form.uc_id1,
        aefi_form.hf_id1,
        aefi_form.case_epi,
        aefi_form.other_case_epi,
        aefi_form.address_village,
        aefi_form.address1,
        aefi_form.hf_name,
        aefi_form.gender,
        DATE_FORMAT(aefi_form.dob,'%d/%m/%Y') dob,
        aefi_form.age_in_months,
        DATE_FORMAT(aefi_form.date_vaccine_given,'%d/%m/%Y') date_vaccine_given,
        DATE_FORMAT(aefi_form.date_aefi_onset,'%d/%m/%Y') date_aefi_onset,
        aefi_form.child_name,
        aefi_form.father_name,
        DATE_FORMAT(  aefi_form.date_of_investigation,'%d/%m/%Y') date_of_investigation,
        DATE_FORMAT(  aefi_form.date_notification,'%d/%m/%Y') date_notification,
        DATE_FORMAT(  aefi_form.expiry_date,'%d/%m/%Y') expiry_date,
        'Not Reported' as aefi,
        aefi_form.aefi_other,
        aefi_form.hospitalization,
        aefi_form.death,
        aefi_form.vaccine_antigen,
        aefi_form.batch_id,
        aefi_form.age_unit,
        aefi_form.batch_number,
        aefi_form.created_date,
        aefi_form.created_by,
        aefi_form.modified_date,
        aefi_form.modified_by,
        dis1.location_name AS district_case,
        teh1.location_name AS tehsil_case,
        uc1.location_name AS uc_case,
        dis.location_name AS district,
        teh.location_name AS tehsil,
        uc.location_name AS uc,
        warehouses.warehouse_name as hf_name,
        aefi_form.child_name,
        aefi_form.father_name,
        aefi_form.address1,
        aefi_form.contact_number,
        aefi_form.address2,
	aefi_form.address_village,
	aefi_form.age_unit,
        stock_batch.number as batch,
        item_pack_sizes.item_name as vaccine,
        sentinel_sites.sentinel_site_name
        FROM
        aefi_form
        INNER JOIN locations AS dis ON aefi_form.district_id = dis.pk_id
        INNER JOIN locations AS dis1 ON aefi_form.district_id1 = dis1.pk_id
        LEFT JOIN locations AS teh ON aefi_form.tehsil_id = teh.pk_id
        LEFT JOIN locations AS uc ON aefi_form.uc_id = uc.pk_id
        LEFT JOIN locations AS teh1 ON aefi_form.tehsil_id1 = teh1.pk_id
        LEFT JOIN locations AS uc1 ON aefi_form.uc_id1 = uc1.pk_id
        LEFT JOIN warehouses  ON warehouses.pk_id = aefi_form.hf_id
        INNER JOIN list_detail  ON list_detail.pk_id = aefi_form.aefi
        INNER JOIN item_pack_sizes ON aefi_form.vaccine_antigen = item_pack_sizes.pk_id
        LEFT JOIN stock_batch_warehouses ON aefi_form.batch_id = stock_batch_warehouses.pk_id
        LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        LEFT JOIN sentinel_sites ON aefi_form.address_village = sentinel_sites.pk_id
        WHERE  $where_s   $str_sql_order";
        }



        $rec = $this->_em->getConnection()->prepare($str_sql);
        $rec->execute();
//        print_r($str_sql);exit;
        return $rec->fetchAll();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getVpdList() {
        $form_values = $this->form_values;

        $id = $form_values['id'];

        $str_sql = " SELECT
        vpd_form.pk_id,
         vpd_form.week,
        DATE_FORMAT(vpd_form.date, '%d/%m/%Y') date,
        vpd_form.district_id,
        vpd_form.tehsil_id,
        vpd_form.uc_id,
        vpd_form.hf_id,
        vpd_form.hf_name,
        vpd_form.type_case,
        vpd_form.epid_number,
        vpd_form.child_name,
        vpd_form.father_name,
        vpd_form.address1 complete_address,
        vpd_form.address2,
        vpd_form.address_village,
        vpd_form.contact_number,
        vpd_form.cross_notification,
        DATE_FORMAT(vpd_form.dob, '%d/%m/%Y') dob,
        DATE_FORMAT(vpd_form.date_of_lab_resulted,'%d/%m/%Y') date_of_lab_resulted,
        DATE_FORMAT(vpd_form.patient_visited_date,'%d/%m/%Y') patient_visited_date,
        vpd_form.age age_in_month,
        vpd_form.gender,
        vpd_form.clinical_presentation,
        DATE_FORMAT(vpd_form.date_onset, '%d/%m/%Y') date_onset,
        DATE_FORMAT(vpd_form.date_notification, '%d/%m/%Y') date_notification,
        DATE_FORMAT(vpd_form.date_investigation, '%d/%m/%Y') date_investigation,
        vpd_form.specific_dose_received,
        DATE_FORMAT(vpd_form.date_last_dose_received, '%d/%m/%Y') date_last_dose_received,
        vpd_form.specimen_collection,
        DATE_FORMAT(vpd_form.date_specimen_sent, '%d/%m/%Y') date_specimen_sent,
        DATE_FORMAT(vpd_form.date_specimen_sent1, '%d/%m/%Y') date_specimen_sent1,
        vpd_form.from_uc_id,
        vpd_form.sign_symptoms,
        vpd_form.outcome,
        vpd_form.attendee,
        vpd_form.place_of_birth,
        vpd_form.lab_result,
        vpd_form.final_classification,
        DATE_FORMAT(vpd_form.stool_sample_date1,'%d/%m/%Y') stool_sample_date1,
        DATE_FORMAT(vpd_form.stool_sample_date2,'%d/%m/%Y') stool_sample_date2,
        DATE_FORMAT(vpd_form.blood_sample_date,'%d/%m/%Y') blood_sample_date,
        DATE_FORMAT(vpd_form.blood_sample_month,'%d/%m/%Y') blood_sample_month,

        vpd_form.created_date,
        vpd_form.created_by,
        vpd_form.modified_date,
        vpd_form.modified_by,
        prov.pk_id province_id
        FROM
        vpd_form
        INNER JOIN locations district ON vpd_form.district_id = district.pk_id
        INNER JOIN locations prov ON district.province_id = prov.pk_id
        WHERE vpd_form.pk_id = '$id' ";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    public function getVpdDraft() {
        $form_values = $this->form_values;

        $id = $this->_user_id;

        $str_sql = " SELECT
        vpd_form_draft.pk_id,
         vpd_form_draft.week,
        DATE_FORMAT(vpd_form_draft.date, '%d/%m/%Y') date,
        vpd_form_draft.district_id,
        vpd_form_draft.tehsil_id,
        vpd_form_draft.uc_id,
        vpd_form_draft.hf_id,
        vpd_form_draft.hf_name,
        vpd_form_draft.type_case,
        vpd_form_draft.epid_number,
        vpd_form_draft.child_name,
        vpd_form_draft.father_name,
        vpd_form_draft.address1 complete_address,
        vpd_form_draft.address2,
        vpd_form_draft.address_village,
        vpd_form_draft.contact_number,
        vpd_form_draft.cross_notification,
        DATE_FORMAT(vpd_form_draft.dob, '%d/%m/%Y') dob,
        DATE_FORMAT(vpd_form_draft.date_of_lab_resulted,'%d/%m/%Y') date_of_lab_resulted,
        DATE_FORMAT(vpd_form_draft.patient_visited_date,'%d/%m/%Y') patient_visited_date,
        vpd_form_draft.age age_in_month,
        vpd_form_draft.gender,
        vpd_form_draft.clinical_presentation,
        DATE_FORMAT(vpd_form_draft.date_onset, '%d/%m/%Y') date_onset,
        DATE_FORMAT(vpd_form_draft.date_notification, '%d/%m/%Y') date_notification,
        DATE_FORMAT(vpd_form_draft.date_investigation, '%d/%m/%Y') date_investigation,
        vpd_form_draft.specific_dose_received,
        DATE_FORMAT(vpd_form_draft.date_last_dose_received, '%d/%m/%Y') date_last_dose_received,
        vpd_form_draft.specimen_collection,
        DATE_FORMAT(vpd_form_draft.date_specimen_sent, '%d/%m/%Y') date_specimen_sent,
        DATE_FORMAT(vpd_form_draft.date_specimen_sent1, '%d/%m/%Y') date_specimen_sent1,
        vpd_form_draft.from_uc_id,
        vpd_form_draft.sign_symptoms,
        vpd_form_draft.outcome,
        vpd_form_draft.attendee,
        vpd_form_draft.place_of_birth,
        vpd_form_draft.lab_result,
        vpd_form_draft.final_classification,
        DATE_FORMAT(vpd_form_draft.stool_sample_date1,'%d/%m/%Y') stool_sample_date1,
        DATE_FORMAT(vpd_form_draft.stool_sample_date2,'%d/%m/%Y') stool_sample_date2,
        DATE_FORMAT(vpd_form_draft.blood_sample_date,'%d/%m/%Y') blood_sample_date,
        DATE_FORMAT(vpd_form_draft.blood_sample_month,'%d/%m/%Y') blood_sample_month,

        vpd_form_draft.created_date,
        vpd_form_draft.created_by,
        vpd_form_draft.modified_date,
        vpd_form_draft.modified_by,
        prov.pk_id province_id
        FROM
        vpd_form_draft
        LEFT JOIN locations district ON vpd_form_draft.district_id = district.pk_id
        LEFT JOIN locations prov ON district.province_id = prov.pk_id
        WHERE vpd_form_draft.created_by = $id ";
        //  print_r($str_sql);exit;

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        //  print_r($rec->fetchAll());exit;
        return $rec->fetchAll();
    }

    public function getAefiDraft() {
        $form_values = $this->form_values;

        $id = $this->_user_id;

        $str_sql = " SELECT aefi_form_draft.pk_id,
        aefi_form_draft.type_case,
        DATE_FORMAT(aefi_form_draft.date,'%d/%m/%Y') date,
        aefi_form_draft.district_id,
        aefi_form_draft.week,
        aefi_form_draft.vaccinator_name,
        aefi_form_draft.designation,
        aefi_form_draft.tehsil_id,
        aefi_form_draft.uc_id,
        aefi_form_draft.hf_id,
        aefi_form_draft.contact_number,
        aefi_form_draft.district_id1,
        aefi_form_draft.tehsil_id1,
        aefi_form_draft.uc_id1,
        aefi_form_draft.hf_id1,
        aefi_form_draft.case_epi,
        aefi_form_draft.other_case_epi,
        aefi_form_draft.address_village,
        aefi_form_draft.address1,
        aefi_form_draft.hf_name,
        aefi_form_draft.gender,
        DATE_FORMAT(aefi_form_draft.dob,'%d/%m/%Y') dob,
        aefi_form_draft.age_in_months,
        DATE_FORMAT(aefi_form_draft.date_vaccine_given,'%d/%m/%Y') date_vaccine_given,
        DATE_FORMAT(aefi_form_draft.date_aefi_onset,'%d/%m/%Y') date_aefi_onset,
        aefi_form_draft.child_name,
        aefi_form_draft.father_name,
        DATE_FORMAT(  aefi_form_draft.date_of_investigation,'%d/%m/%Y') date_of_investigation,
        DATE_FORMAT(  aefi_form_draft.date_notification,'%d/%m/%Y') date_notification,
        DATE_FORMAT(  aefi_form_draft.expiry_date,'%d/%m/%Y') expiry_date,
        'Not Reported' as aefi,
        aefi_form_draft.aefi_other,
        aefi_form_draft.hospitalization,
        aefi_form_draft.death,
        aefi_form_draft.vaccine_antigen,
        aefi_form_draft.batch_id,
        aefi_form_draft.age_unit,
        aefi_form_draft.batch_number,
        aefi_form_draft.created_date,
        aefi_form_draft.created_by,
        aefi_form_draft.modified_date,
        aefi_form_draft.modified_by

        FROM
        aefi_form_draft

        WHERE aefi_form_draft.created_by = $id ";
//          print_r($str_sql);exit;

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        //  print_r($rec->fetchAll());exit;
        return $rec->fetchAll();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getAefiList() {
        $form_values = $this->form_values;

        $id = $form_values['id'];

        $str_sql = " SELECT
        aefi_form.pk_id,
        DATE_FORMAT(aefi_form.date, '%d/%m/%Y') date,
        aefi_form.district_id,
        aefi_form.tehsil_id,
        aefi_form.week,
        aefi_form.child_name,
        aefi_form.father_name,
        aefi_form.contact_number,
        aefi_form.address1 complete_address,
        aefi_form.age_in_months age_in_month,
        aefi_form.age_unit,
        aefi_form.uc_id,
        aefi_form.hf_id,
        aefi_form.hf_name,
        aefi_form.type_case,
        aefi_form.gender,
        DATE_FORMAT( aefi_form.dob, '%d/%m/%Y') dob,
        aefi_form.age_in_months,
        DATE_FORMAT(aefi_form.date_vaccine_given, '%d/%m/%Y') date_vaccine_given,
        DATE_FORMAT(aefi_form.date_aefi_onset, '%d/%m/%Y') date_aefi_onset ,
        DATE_FORMAT(aefi_form.date_of_investigation, '%d/%m/%Y') date_of_investigation ,
        aefi_form.aefi,
        aefi_form.aefi_other,
        aefi_form.hospitalization,
        aefi_form.death,
        aefi_form.vaccine_antigen,
        aefi_form.batch_id,
        aefi_form.batch_number,
        aefi_form.created_date,
        aefi_form.created_by,
        aefi_form.modified_date,
        aefi_form.modified_by,
        prov.pk_id as province_id
        FROM
        aefi_form
        INNER JOIN locations dis ON aefi_form.district_id = dis.pk_id
        INNER JOIN locations prov ON dis.province_id = prov.pk_id
        WHERE aefi_form.pk_id = '$id'  ";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getDistrictCode() {
        $form_values = $this->form_values;

        $id = $form_values['district_id'];

        $str_sql = "SELECT
                locations.surveillance_dist_code pk_id
                FROM
                locations
                WHERE
                locations.pk_id = '$id'";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getVpdDetail() {
        $form_values = $this->form_values;

        if (!empty($form_values['pk_id'])) {
            $where = "where vpd_form.pk_id  = '" . $form_values['pk_id'] . "'";
        }

//        if (is_array($where)) {
//            $where_s = implode(" AND ", $where);
//        }

        $str_sql = "SELECT
	vpd_form.pk_id,
	DATE_FORMAT(vpd_form.date, '%d/%m/%Y') AS date,
	vpd_form.district_id,
	vpd_form.tehsil_id,
	vpd_form.uc_id,
	vpd_form.hf_id,
	vpd_form.hf_name,
	vpd_form.type_case,
	vpd_form.epid_number,
	vpd_form.child_name,
	vpd_form.father_name,
	vpd_form.address1,
	vpd_form.contact_number,
	vpd_form.cross_notification,
	DATE_FORMAT(vpd_form.dob, '%d/%m/%Y') AS dob,
	vpd_form.age,
	vpd_form.gender AS gender,
	vpd_form.clinical_presentation,
        vpd_form.number_of_months,
        DATE_FORMAT(
		vpd_form.date_of_lab_resulted,
		'%d/%m/%Y'
	) AS date_of_lab_resulted,
	DATE_FORMAT(
		vpd_form.date_onset,
		'%d/%m/%Y'
	) AS date_onset,
	DATE_FORMAT(
		vpd_form.date_notification,
		'%d/%m/%Y'
	) AS date_notification,
        DATE_FORMAT(
		vpd_form.patient_visited_date,
		'%d/%m/%Y'
	) AS patient_visited_date,
	DATE_FORMAT(
		vpd_form.date_investigation,
		'%d/%m/%Y'
	) AS date_investigation,
	DATE_FORMAT(
		vpd_form.date_last_dose_received,
		'%d/%m/%Y'
	) AS date_last_dose_received,
	vpd_form.specimen_collection,
	DATE_FORMAT(
		vpd_form.date_specimen_sent,
		'%d/%m/%Y'
	) AS date_specimen_sent,
        DATE_FORMAT(
		vpd_form.date_specimen_sent1,
		'%d/%m/%Y'
	) AS date_specimen_sent1,
	vpd_form.sign_symptoms,
	vpd_form.outcome,
        vpd_form.attendee,
        vpd_form.place_of_birth,
	vpd_form.lab_result,
	vpd_form.final_classification,
        DATE_FORMAT(
	vpd_form.stool_sample_date1,'%d/%m/%Y'
	) AS stool_sample_date1,
        DATE_FORMAT(
	vpd_form.stool_sample_date2,'%d/%m/%Y'
	) AS stool_sample_date2,
        DATE_FORMAT(
	vpd_form.blood_sample_date,'%d/%m/%Y'
	) AS blood_sample_date,
         DATE_FORMAT(
	vpd_form.blood_sample_month,'%d/%m/%Y'
	) AS blood_sample_month,
	vpd_form.week,
	vpd_form.created_date,
	vpd_form.created_by,
	vpd_form.modified_date,
	vpd_form.modified_by,
	list_detail.list_value AS typeCase,
	vpd_form.from_uc_id,
	vpd_form.address2,
	vpd_form.address_village,
	vpd_form.age_unit,
	from_uc.location_name AS from_uc,
	dis.location_name AS district,
        teh.location_name AS tehsil,
        uc.location_name AS uc,
        GROUP_CONCAT(cli.list_value) as sign,
        warehouses.warehouse_name as s_site,
        lab.list_value as lab,
        final.list_value as final,
        outcome.list_value as outcome,
        attende.list_value as attendee,
        birth_place.list_value as place_of_birth,
        prov.location_name as province,
        prov.pk_id as province_id
        FROM
        vpd_form
        LEFT JOIN list_detail ON vpd_form.type_case = list_detail.pk_id
        LEFT JOIN locations AS from_uc ON vpd_form.from_uc_id = from_uc.pk_id
        LEFT JOIN locations AS dis ON vpd_form.district_id = dis.pk_id
        LEFT JOIN locations AS prov ON dis.province_id = prov.pk_id
        LEFT JOIN locations AS teh ON vpd_form.tehsil_id = teh.pk_id
        LEFT JOIN locations AS uc ON vpd_form.uc_id = uc.pk_id
        LEFT JOIN vpd_clinical_presentation ON vpd_form.pk_id = vpd_clinical_presentation.vpd_form_id
        LEFT JOIN list_detail AS cli ON vpd_clinical_presentation.clinical_presentation_id = cli.pk_id
        LEFT JOIN warehouses ON warehouses.pk_id = vpd_form.hf_id
        LEFT JOIN list_detail AS lab ON vpd_form.lab_result = lab.pk_id
        LEFT JOIN list_detail AS final ON final.pk_id = vpd_form.final_classification
        LEFT JOIN list_detail AS outcome ON vpd_form.outcome = outcome.pk_id
        LEFT JOIN list_detail as birth_place ON vpd_form.place_of_birth = birth_place.pk_id
        LEFT JOIN list_detail as attende ON vpd_form.attendee = attende.pk_id
         $where ";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getAefiDetail() {
        $form_values = $this->form_values;

        if (!empty($form_values['pk_id'])) {
            $where[] = "aefi_form.pk_id  = '" . $form_values['pk_id'] . "'";
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = " SELECT
        aefi_form.pk_id,
        aefi_form.week,
        DATE_FORMAT(aefi_form.date, '%d/%m/%Y') date,
        aefi_form.district_id,
        aefi_form.tehsil_id,
        aefi_form.uc_id,
        aefi_form.hf_id,
        aefi_form.hf_name,
        aefi_form.gender,
        DATE_FORMAT( aefi_form.dob, '%d/%m/%Y') dob,
        aefi_form.age_in_months age,
        DATE_FORMAT(aefi_form.date_vaccine_given, '%d/%m/%Y') date_vaccine_given,
        DATE_FORMAT(aefi_form.date_aefi_onset, '%d/%m/%Y') date_aefi_onset ,
        DATE_FORMAT(aefi_form.date_of_investigation, '%d/%m/%Y') date_of_investigation ,
        list_detail.list_value aefi,
        aefi_form.type_case,
        aefi_form.aefi_other,
        aefi_form.hospitalization,
        aefi_form.death,
        aefi_form.vaccine_antigen,
        aefi_form.batch_id,
        aefi_form.batch_number,
        aefi_form.created_date,
        aefi_form.created_by,
        aefi_form.modified_date,
        aefi_form.modified_by,
        dis.location_name AS district,
        teh.location_name AS tehsil,
        uc.location_name AS uc,
        warehouses.warehouse_name as hf_name,
        aefi_form.child_name,
        aefi_form.father_name,
        aefi_form.contact_number,
        aefi_form.address1,
        aefi_form.address2,
	aefi_form.address_village,
	aefi_form.age_unit,
        stock_batch.number as batch,
        item_pack_sizes.item_name as vaccine,
        sentinel_sites.sentinel_site_name
        FROM
        aefi_form
        INNER JOIN locations AS dis ON aefi_form.district_id = dis.pk_id
        LEFT JOIN locations AS teh ON aefi_form.tehsil_id = teh.pk_id
        LEFT JOIN locations AS uc ON aefi_form.uc_id = uc.pk_id
        LEFT JOIN warehouses  ON warehouses.pk_id = aefi_form.hf_id
        INNER JOIN list_detail  ON list_detail.pk_id = aefi_form.aefi
        INNER JOIN item_pack_sizes ON aefi_form.vaccine_antigen = item_pack_sizes.pk_id
        LEFT JOIN stock_batch_warehouses ON aefi_form.batch_id = stock_batch_warehouses.pk_id

        LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        LEFT JOIN sentinel_sites ON aefi_form.address_village = sentinel_sites.pk_id
        WHERE $where_s ";


        $rec = $this->_em->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    public function getVpdWeeks($year) {
        $str_qry = "SELECT
            vpd_weeks.`week`,
            DATE_FORMAT(vpd_weeks.start_date,'%d %b %y') s,
            DATE_FORMAT(vpd_weeks.end_date,'%d %b %y') e,
            DATE_FORMAT(vpd_weeks.report_date,'%d %b %y') r

            FROM
            vpd_weeks
            where year = '$year'";
        $rec = $this->_em_read->getConnection()->prepare($str_qry);
        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getVpdCliniical() {
        $form_values = $this->form_values;

        $id = $form_values['id'];

        $str_sql = " SELECT
            vpd_clinical_presentation.clinical_presentation_id,
            vpd_clinical_presentation.vpd_form_id
            FROM
            vpd_clinical_presentation
           WHERE vpd_clinical_presentation.vpd_form_id = '$id' ";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get All Locations
     * @return type
     */
    public function getLastId() {
        $user_id = $this->_user_id;

        $str_sql = "SELECT
                    count(*) as id
            FROM
                    vpd_form
            WHERE
                    created_by = '$user_id'";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get Locations By Level By Province Consumption
     * @return boolean
     */
    public function getLocationsByLevelByProvinceConsumption1() {
        $geo_level_id = $this->form_values['geo_level_id'];
        $province_id = $this->form_values['province_id'];
        $querypro = "SELECT
            locations.pk_id,
            locations.location_name
            FROM
            locations
            WHERE
            locations.province_id = 2 AND
            locations.geo_level_id = 4 AND
           locations.district_id != 87
            UNION
            SELECT
            locations.pk_id,
            locations.location_name
            FROM
            locations
            WHERE
            locations.district_id = 87 AND
            locations.geo_level_id = 5";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pk_id'], 'value' => $row['location_name']);
            }
            return $data;
        } else {
            return false;
        }
    }

    public function current_week() {
        $current_week = date('Y-m-d');

        $str_sql = "SELECT
        vpd_weeks.`week`
        FROM
        vpd_weeks
        where  '$current_week' BETWEEN Date_Format(vpd_weeks.start_date,'%Y-%m-%d') AND
        Date_Format(vpd_weeks.end_date,'%Y-%m-%d') ";


        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();

        return $rec->fetchAll();
    }

}
