<?php

/**
 * Model_Users
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Users
 */
class Model_Users extends Model_Base {

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
        $this->_table = $this->_em->getRepository('Users');
    }

    /**
     * Get Users
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getUsers($order = null, $sort = null) {
        if (!empty($this->form_values)) {
            return $this->_table->findBy($this->form_values);
        } else {
            $qry = $this->_em_read->createQueryBuilder()
                    ->select("u")
                    ->from("Users", "u")
                    ->join("u.role", "r")
                    ->join("u.createdBy", "cb")
                    ->where("r.category = " . Model_Roles::COLDCHAIN);

            if ($order == 'login_id') {
                $qry->orderBy("u.loginId", $sort);
            }
            if ($order == 'role') {
                $qry->orderBy("r.roleName", $sort);
            }
            if ($order == 'created_by') {
                $qry->orderBy("cb.userName", $sort);
            }
            if ($order == 'logged_at') {
                $qry->orderBy("u.loggedAt", $sort);
            }

            return $qry->getQuery()->getResult();
        }
    }

    /**
     * Get All Users
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllUsers($order = null, $sort = null) {
        $form_values = $this->form_values;


        if (!empty($form_values['page']) && $form_values['page'] == 'routine') {
            $where[] = "s.pkId =  '9' and r.pkId='8'   ";
        }
        if (!empty($form_values['page']) && $form_values['page'] == 'campaigns') {
            $where[] = "s.pkId =  '10' and r.pkId IN (14,15,16) ";
        }
        if (!empty($form_values['page']) && $form_values['page'] == 'im') {
            $where[] = "s.pkId = '1' and r.pkId='8' ";
        }
        if (!empty($form_values['page']) && $form_values['page'] == 'policy') {
            $where[] = "s.pkId = '1' and r.pkId='17'";
        }
        if (!empty($form_values['search_policy_users'])) {
            $where[] = "u.loginId =  '" . $form_values['search_policy_users'] . "' ";
        }
        if (!empty($form_values['office_type']) && $form_values['office_type'] == 1) {
            $where[] = "so.pkId =  '" . $form_values['office_type'] . "' ";
        }
        if (!empty($form_values['combo1'])) {
            $where[] = "p.pkId = '" . $form_values['combo1'] . "' and  so.pkId =  '" . $form_values['office_type'] . "' ";
        }

        if (!empty($form_values['combo2'])) {
            $where[] = "d.pkId = '" . $form_values['combo2'] . "' and  so.pkId =  '" . $form_values['office_type'] . "' ";
        }
        if (!empty($form_values['combo3'])) {
            $where[] = "l.pkId = '" . $form_values['combo3'] . "' and  so.pkId =  '" . $form_values['office_type'] . "' ";
        }
        if (!empty($form_values['combo4'])) {
            $where[] = "l.pkId = '" . $form_values['combo4'] . "' and  so.pkId =  '" . $form_values['office_type'] . "' ";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $qry = $this->_em_read->createQueryBuilder()
                ->select("u.pkId,u.loginId,p.locationName as ProvinceName,d.locationName as districtName,l.locationName as Parent,"
                        . "s.stakeholderName")
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("u.role", "r")
                ->join("wu.warehouse", "w")
                ->join("w.location", "l")
                ->join("l.province", "p")
                ->join("l.district", "d")
                ->join("w.stakeholderOffice", "so")
                ->join("u.stakeholder", "s")
                ->where($where_s);


        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Im Users
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllImUsers($order = null, $sort = null) {
        $form_values = $this->form_values;

        $where[] = "s.pkId = '1'  ";

        if (!empty($form_values['office_type'])) {
            $where[] = "so.pkId =  '" . $form_values['office_type'] . "' ";
        }
        if (!empty($form_values['combo1'])) {
            $where[] = "p.pkId = '" . $form_values['combo1'] . "' ";
        }

        if (!empty($form_values['combo2'])) {
            $where[] = "d.pkId = '" . $form_values['combo2'] . "'  ";
        }
        if (!empty($form_values['combo3'])) {
            $where[] = "l.pkId = '" . $form_values['combo3'] . "'  ";
        }
        if (!empty($form_values['combo4'])) {
            $where[] = "l.pkId = '" . $form_values['combo4'] . "'  ";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $qry = $this->_em_read->createQueryBuilder()
                ->select("u.pkId,u.loginId,r.pkId as role,p.locationName as ProvinceName,d.locationName as districtName,l.locationName as Parent,"
                        . "s.stakeholderName")
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("u.role", "r")
                ->join("wu.warehouse", "w")
                ->join("w.location", "l")
                ->join("l.province", "p")
                ->join("l.district", "d")
                ->join("w.stakeholderOffice", "so")
                ->join("u.stakeholder", "s")
                ->where($where_s);


        return $qry->getQuery()->getResult();
    }

    /**
     * Get User Id By Warehouse Id
     * 
     * @param type $wh_id
     * @return boolean
     */
    public function getUserIdByWarehouseId($wh_id) {
        $user = $this->_em_read->getRepository('WarehouseUsers')->findOneBy(array("warehouse" => $wh_id, "isDefault" => 1));
        if (count($user) > 0) {
            return $user->getUser()->getPkId();
        } else {
            return false;
        }
    }

    /**
     * Update User Token
     * 
     * @param type $hash
     * @param type $wh_id
     * @return type
     */
    public function updateUserToken($hash, $wh_id) {

        $user_id = $this->getUserIdByWarehouseId($wh_id);
        $user = $this->_em->getRepository('Users')->find($user_id);
        $user->setAuth($hash);
        $user->setModifiedBy($user);
        $user->setCreatedBy($user);
        $user->setModifiedDate(App_Tools_Time::now());
        $user->setCreatedDate(App_Tools_Time::now());
        $this->_em->persist($user);
        $this->_em->flush();

        return array(array("message" => "Hash has been updated successfully", "login_id" => $user->getLoginId(), "user_id" => $user->getPkId()));
    }

    public function updateProfile() {

        $user_id = $this->_user_id;
        $params = $this->form_values;
        $user = $this->_em->getRepository('Users')->find($user_id);
        $user->setUserName($params['name']);
        $user->setCellNumber($params['cellnumber']);
        $user->setDesignation($params['office']);
        $user->setDepartment($params['department']);
        $user->setPhoneNumber($params['cellnumber']);
        //$user->setEmail($params['email']);
        $this->_em->persist($user);

        if (!$this->checkEmailAddress($user_id,$params['email'])) {
            $ever = new EmailVerification();
            $ever->setUserId($user_id);
            $ever->setEmailAddress($params['email']);
            $ever->setIsVerified(0);

            $this->_em->persist($ever);


            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
            $headers .= "From: no-reply@lmis.gov.pk" . "\r\n" .
                    "Reply-To: no-reply@lmis.gov.pk" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

            $t = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5) . base64_encode($params['email']) . substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 3);
            mail($params['email'], "Verify your email address", "Please click on the following button to verify your email address. <p><a href='http://v.lmis.gov.pk/index/email-verification?t=$t'><button>Verify this email</button></a></p>", $headers);
        }
        
        $this->_em->flush();
        return true;
    }

    /**
     * Get RI Users
     * 
     * @return type
     */
    public function getRIUsers() {
        $form_values = $this->form_values;



        $qry = $this->_em_read->createQueryBuilder()
                ->select("wu")
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("u.role", "r")
                ->join("wu.warehouse", "w")
                ->join("w.location", "l")
                ->join("w.stakeholderOffice", "so")
                ->where("so.geoLevel = 6");

        if (!empty($form_values['loc_id'])) {
            $qry->andWhere("w.location = " . $form_values['loc_id']);
        }
        if (!empty($form_values['district_id'])) {
            $qry->andWhere("w.district = " . $form_values['district_id']);
        }
        $qry->andWhere("r.pkId = 8");


        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Campaign Users
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllCampaignUsers($order = null, $sort = null) {
        $form_values = $this->form_values;
        if (!empty($form_values['office_type']) && $form_values['office_type'] == 1 || $form_values['office_type'] == 2) {

            if (!empty($form_values['page']) && $form_values['page'] == 'campaigns') {
                $where[] = "s.pkId =  '10' AND r.pkId IN (14,15) ";
            }
            if (!empty($form_values['office_type']) && $form_values['office_type'] == 1) {
                $where[] = "l.pkId = '10'  ";
            }
            if (!empty($form_values['combo1'])) {
                $where[] = "l.pkId = '" . $form_values['combo1'] . "'  ";
            }

            if (is_array($where)) {
                $where_s = implode(" AND ", $where);
            }

            $qry = $this->_em_read->createQueryBuilder()
                    ->select("DISTINCT u.pkId,u.loginId,l.locationName as ProvinceName,l.locationName as districtName,l.locationName as Parent,"
                            . "s.stakeholderName")
                    ->from("Users", "u")
                    ->join("u.role", "r")
                    ->join("u.location", "l")
                    ->join("u.stakeholder", "s")
                    ->where($where_s);
            return $qry->getQuery()->getResult();
        }

        if (!empty($form_values['office_type']) && $form_values['office_type'] == 4) {

            if (!empty($form_values['page']) && $form_values['page'] == 'campaigns') {
                $where[] = "s.pkId =  '10' and r.pkId='16' ";
            }
            if (!empty($form_values['office_type']) && $form_values['office_type'] == 1) {

                $where[] = "l.pkId = '10'  ";
            }

            if (!empty($form_values['combo1'])) {
                $where[] = "p.pkId = '" . $form_values['combo1'] . "'  ";
            }

            if (!empty($form_values['combo2'])) {
                $where[] = "d.pkId = '" . $form_values['combo2'] . "' ";
            }

            if (is_array($where)) {
                $where_s = implode(" AND ", $where);
            }

            $qry = $this->_em_read->createQueryBuilder()
                    ->select("DISTINCT u.pkId,u.loginId,p.locationName as ProvinceName,d.locationName as districtName,l.locationName as Parent,"
                            . "s.stakeholderName")
                    ->from("WarehouseUsers", "wu")
                    ->join("wu.user", "u")
                    ->join("u.role", "r")
                    ->join("wu.warehouse", "w")
                    ->join("u.location", "l")
                    ->join("l.province", "p")
                    ->join("l.district", "d")
                    ->join("w.stakeholderOffice", "so")
                    ->join("u.stakeholder", "s")
                    ->where($where_s);
            return $qry->getQuery()->getResult();
        }
    }

    /**
     * Get Campaign Users
     * 
     * @return type
     */
    public function getCampaignUsers() {
        $form_values = $this->form_values;

        $type = '';
        if (!empty($form_values['office_type'])) {
            $type = $form_values['office_type'];
        }

        switch ($type) {
            case 1:
                $role = 14;
                $loc_id = 10;
                break;
            case 2:
                $role = 15;
                $loc_id = $form_values['combo1'];
                break;
            case 4:
                $role = 16;
                $loc_id = $form_values['combo2'];
                break;
            default:
                $role = "14,15,16";
                break;
        }

        $qry = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT u")
                ->from("Users", "u")
                ->where("u.role IN ($role)");
        if (!empty($loc_id)) {
            $qry->andWhere("u.location = $loc_id");
        }
        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Policy Users
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllPolicyUsers($order = null, $sort = null) {
        $form_values = $this->form_values;

        $type = '';
        if (!empty($form_values['office_type'])) {
            $type = $form_values['office_type'];
        }

        switch ($type) {
            case 1:
                $role = 17;
                $loc_id = 10;
                break;
            case 2:
                $role = 19;
                $loc_id = $form_values['combo1'];
                break;
            case 4:
                $role = "20,23";
                $loc_id = $form_values['combo2'];
                break;
            default:
                $role = "17,19,20,23";
                break;
        }

        $qry = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT u")
                ->from("Users", "u")
                ->where("u.role IN ($role)");
        if (!empty($loc_id)) {
            $qry->andWhere("u.location = $loc_id");
        }
        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Users For Cluster
     * 
     * @return type
     */
    public function getAllUsersForCluster() {
        $form_values = $this->form_values;

        $qry = $this->_em_read->createQueryBuilder()
                ->select("Distinct u.loginId,u.pkId")
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("wu.warehouse", "w")
                ->join("w.stakeholderOffice", "s")
                ->join("w.district", "d")
                ->where("d.pkId=" . $form_values['district_id']);
                //->AndWhere("s.pkId=6");

        return $qry->getQuery()->getResult();
    }

    /**
     * Check Users
     * 
     * @return type
     */
    public function checkUsers() {

        $form_values = $this->form_values;

        if ($form_values['office_type_add'] == 1) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("u.loginId")
                    ->from('Users', 'u')
                    ->where("u.loginId= '" . $form_values['user_name_add'] . "' ");

            return $str_sql->getQuery()->getResult();
        }
        if ($form_values['office_type_add'] != 1) {

            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("u.loginId")
                    ->from('Users', 'u')
                    ->join('u.location', 'p')
                    ->where("u.loginId= '" . $form_values['user_name_add'] . "' ");

            return $str_sql->getQuery()->getResult();
        }
    }

    /**
     * Check Users Update
     * 
     * @return type
     */
    public function checkUsersUpdate() {

        $form_values = $this->form_values;

        if ($form_values['office_type_edit'] == 1) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("u.loginId")
                    ->from('Users', 'u')
                    ->where("u.loginId= '" . $form_values['user_name_update'] . "' ");

            return $str_sql->getQuery()->getResult();
        }
        if ($form_values['office_type_edit'] != 1) {

            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("u.loginId")
                    ->from('Users', 'u')
                    ->where("u.loginId= '" . $form_values['user_name_update'] . "' ");

            return $str_sql->getQuery()->getResult();
        }
    }

    /**
     * Check Users Update Policy
     * 
     * @return type
     */
    public function checkUsersUpdatePolicy() {

        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("u.loginId")
                ->from('Users', 'u')
                ->where("u.userName= '" . $form_values['user_name_update'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Users Policy
     * 
     * @return type
     */
    public function checkUsersPolicy() {

        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("u.userName")
                ->from('Users', 'u')
                ->where("u.userName= '" . $form_values['login_id_add'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Save User Feedback
     * 
     * @return boolean
     */
    public function saveUserFeedback() {
        if (!empty($this->form_values['name'])) {
            $name = $this->form_values['name'];
        }
        if (!empty($this->form_values['e_mail'])) {
            $e_mail = $this->form_values['e_mail'];
        }
        if (!empty($this->form_values['phone'])) {
            $phone = $this->form_values['phone'];
        }
        if (!empty($this->form_values['department'])) {
            $department = $this->form_values['department'];
        }
        if (!empty($this->form_values['message'])) {
            $message = $this->form_values['message'];
        }

        $str_qry = "INSERT INTO user_feedback
                        (user_feedback.`name`,
                        user_feedback.email,
                        user_feedback.phone,
                        user_feedback.department,
                        user_feedback.message,
                        user_feedback.created_date)
                    VALUES ( '$name', '$e_mail', '$phone', '$department', '$message', NOW()
                            )";

        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Get User Feedback
     * 
     * @return type
     */
    public function getUserFeedback() {

        $str_qry = "SELECT
                        user_feedback.pk_id,
                        user_feedback.`name`,
                        user_feedback.email,
                        user_feedback.phone,
                        user_feedback.department,
                        user_feedback.message,
                        user_feedback.created_date
                    FROM
                        user_feedback";

        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Register User
     * 
     * @return boolean
     */
    public function registerUser() {

        if (!empty($this->form_values['e_mail'])) {
            $e_mail = $this->form_values['e_mail'];
        }
        if (!empty($this->form_values['enc_pswd'])) {
            $enc_pswd = $this->form_values['enc_pswd'];
        }
        if (!empty($this->form_values['role_id'])) {
            $role_id = $this->form_values['role_id'];
        }
        if (!empty($this->form_values['organization'])) {
            $organization = $this->form_values['organization'];
        }
        if (!empty($this->form_values['country'])) {
            $country = $this->form_values['country'];
        }
        if (!empty($this->form_values['address'])) {
            $address = $this->form_values['address'];
        }

        $str_qry = "INSERT INTO users
                        (users.`login_id`,
                        users.password,
                        users.email,
                        users.organization,
                        users.country,
                        users.address,
                        users.role_id,
                        users.status,
                        users.user_name, 
                        users.created_by,
                        users.modified_by
                        )
                    VALUES ( '$e_mail', '$enc_pswd', '$e_mail', '$organization', '$country', '$address', '$role_id', '0', '$e_mail', '1', '1'
                            )";


        $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Is Email Taken
     * 
     * @return type
     */
    public function isEmailTaken() {

        if (!empty($this->form_values['e_mail'])) {
            $e_mail = $this->form_values['e_mail'];
        }

        $str_qry = "SELECT
                        users.login_id
                    FROM
                        users
                    WHERE
                        users.login_id = '$e_mail'";



        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Activate User Account
     * 
     * @return boolean
     */
    public function activateUserAccount() {

        if (!empty($this->form_values['id'])) {
            $id = $this->form_values['id'];
        }

        $str_qry = "UPDATE users
                        SET STATUS = '1'
                    WHERE
                        pk_id = $id";

        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return true;
    }

    /**
     * Get User Id
     * 
     * @return type
     */
    public function getUserId() {

        if (!empty($this->form_values['e_mail'])) {
            $e_mail = $this->form_values['e_mail'];
        }
        if (!empty($this->form_values['enc_pswd'])) {
            $enc_pswd = $this->form_values['enc_pswd'];
        }

        $str_qry = "SELECT
                                users.pk_id
                            FROM
                                users
                            WHERE

                                users.login_id = '$e_mail' AND
                                users.`password` = '$enc_pswd'";


        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Get Doc User Password
     * 
     * @return type
     */
    public function getDocUserPassword() {

        if (!empty($this->form_values['e_mail'])) {
            $e_mail = $this->form_values['e_mail'];
        }

        $str_qry = "SELECT
                        
                        users.`password`
                    FROM
                        users
                    WHERE
                        users.login_id = '$e_mail' AND
                        users.role_id = 32";

        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Get Doc Id
     * 
     * @return type
     */
    public function getDocId() {

        if (!empty($this->form_values['url'])) {
            $url = $this->form_values['url'];
        }

        $str_qry = "SELECT
                        documents.pk_id
                    FROM
                        documents
                    WHERE
                        documents.doc_path = '$url'";

        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Doc User Log
     * 
     * @return boolean
     */
    public function docUserLog() {

        if (!empty($this->form_values['uid'])) {
            $u_id = $this->form_values['uid'];
        }
        if (!empty($this->form_values['docid'])) {
            $doc_id = $this->form_values['docid'];
        }
        if (!empty($this->form_values['ip'])) {
            $ip = $this->form_values['ip'];
        }


        $str_qry = "INSERT INTO user_documents 
                      (
                        user_documents.user_id,
                        user_documents.doc_id,
                        user_documents.created_date,
                        user_documents.system_ip
                       )
                    VALUES
                       (
                        '$u_id',
                        '$doc_id',
                        NOW(),
                        '$ip'
                       )";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Get Doc User Log
     * 
     * @return type
     */
    public function getDocUserLog() {

        $str_qry = "SELECT
                        user_documents.user_id,
                        user_documents.created_date AS download_date,
                        user_documents.system_ip,
                        users.login_id,
                        Count(documents.pk_id) AS total_download,
                        document_categories.category_title,
                        documents.doc_title,
                        documents.doc_path
                    FROM
                        user_documents
                        INNER JOIN users ON users.pk_id = user_documents.user_id
                        INNER JOIN documents ON user_documents.doc_id = documents.pk_id
                        AND user_documents.doc_id = documents.pk_id
                        INNER JOIN document_categories ON documents.doc_category_id = document_categories.pk_id
                    GROUP BY
                        user_documents.user_id,
                        documents.pk_id";

        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Get Doc Main Categories
     * 
     * @return type
     */
    public function getDocMainCategories() {

        $str_qry = "SELECT
                        document_categories.pk_id,
                        document_categories.category_title
                    FROM
                        document_categories
                    WHERE
                        document_categories.parent_id = 0";

        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Get User Login Log
     * 
     * @return type
     */
    public function getUserLoginLog() {

        $str_qry = "SELECT
                        users.user_name,
                        users.login_id,
                        user_login_log.ip_address,
                        DATE_FORMAT(
                                Max(user_login_log.login_time),
                                '%d/%m/%Y %h:%i:%s %p'
                        ) AS last_loggedin_at
                    FROM
                        user_login_log
                        INNER JOIN users ON user_login_log.user_id = users.pk_id
                    GROUP BY
                        user_login_log.user_id";

        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();

        return $row->fetchAll();
    }

    /**
     * Register User
     * 
     * @return boolean
     */
    public function register() {

        if (!empty($this->form_values['e_mail'])) {
            $e_mail = $this->form_values['e_mail'];
        }
        if (!empty($this->form_values['enc_pswd'])) {
            $enc_pswd = $this->form_values['enc_pswd'];
        }
        if (!empty($this->form_values['role_id'])) {
            $role_id = $this->form_values['role_id'];
        }
        if (!empty($this->form_values['organization'])) {
            $organization = $this->form_values['organization'];
        }

        if (!empty($this->form_values['address'])) {
            $address = $this->form_values['address'];
        }

        $str_qry = "INSERT INTO users
                        (users.`login_id`,
                        users.password,
                        users.email,
                        users.stakeholder_id,
                        
                        users.address,
                        users.role_id,
                        users.status,
                        users.user_name, 
                        users.created_by,
                        users.modified_by
                        )
                    VALUES ( '$e_mail', '$enc_pswd', '$e_mail', '$organization', '$address', '$role_id', '0', '$e_mail', '1', '1'
                            )";
        $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }

    /**
     * Get Daily Active Users
     * @return boolean
     */
    public function getActiveUsers($limit) {
//        $province = $this->_identity->getProvinceId();
//        AND warehouses.province_id = $province
 
        //$date = date("Y-m-d");
        $str_sql = "SELECT DISTINCT
	users.user_name,
	users.designation,
	users.department,
	prov.location_name Province,
	locations.location_name District
FROM
	user_login_log
INNER JOIN users ON user_login_log.user_id = users.pk_id
INNER JOIN warehouse_users ON warehouse_users.user_id = users.pk_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations ON warehouses.district_id = locations.pk_id
INNER JOIN locations prov ON warehouses.province_id = prov.pk_id
WHERE
	user_login_log.login_time >= NOW() - INTERVAL 2 HOUR
ORDER BY
	user_login_log.pk_id DESC";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function delinkEmailAddress() {
        $user_id = $this->form_values['id'];
        $str_qry = "DELETE FROM email_verification WHERE user_id = $user_id";
        $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        return true;
    }
    
    public function checkEmailAddress($user_id, $email) {
        $str_qry = "SELECT pk_id FROM email_verification WHERE user_id = $user_id AND email_address = '$email'";
        $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $result = $row->fetchAll();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
