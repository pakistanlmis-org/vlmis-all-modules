<?php

/**
 * IndexController
 * 
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Index
 */
class IndexController extends App_Controller_Base {

    /**
     * indexAction index
     */
    public function indexAction() {

        $url = "http://lmis.gov.pk/";
        //echo "<script>window.location='$url'</script>";

        $session = new Zend_Session_Namespace('vlmis');

        $this->_helper->layout->setLayout('front-home');

        $referrer = $this->_getParam('referrer', '');
        $form = new Form_Login();
        $error = false;

        if (isset($this->_request->id) && !empty($this->_request->id)) {
            $id = base64_decode($this->_request->id);
            list($date, $username, $password) = explode('SSO-', $id);
            $formData = array(
                'login_id' => $username,
                'password' => $password
            );
        }

        if (isset($this->_request->t) && !empty($this->_request->t)) {
            $t = base64_decode($this->_request->t);
            list($date, $username, $password) = explode('SSO-', $t);
            $formData = array(
                'login_id' => $username,
                'password' => $password
            );
        }
        //START of Code for Super User - Role switching
        if (isset($this->_request->newrole) && !empty($this->_request->newrole)) {
            $newrole = $this->_request->newrole;

            $auth = App_Auth::getInstance();
            $formData = $auth->ChangeRole($newrole);
            $t = true;
        }
        //END of Code for Super User - Role switching

        if ($this->_request->isPost() || isset($formData)) {
            if (empty($formData)) {
                $formData = $this->_request->getPost();
            }

            if ($form->isValid($formData)) {
                try {
                    if (isset($t) && !empty($t)) {
                        if (!$this->_identity->login($form->login_id->getValue(), $form->password->getValue())) {
                            $error = true;
                            throw new InvalidArgumentException('INVALID_USER');
                        }
                    } else {
                        if (!$this->_identity->login($form->login_id->getValue(), md5($form->password->getValue()))) {
                            $error = true;
                            throw new InvalidArgumentException('INVALID_USER');
                        }
                    }

                    $role = $this->_identity->getRoleId();

                    $home_url = '/dashboard/index';
                    
                    if ($role == 1 || $role == 2 || $role == 22 || $role == 38 || $role == 39 || $role == 64) {
                        $home_url = '/iadmin/';
                    }
                    if ($role == 44) {
                        $home_url = '/iadmin/index/aw-stats';
                    }
                    if ($role == 48) {
                        $home_url = '/surveillance/';
                    }if ($role == 61) {
                        $home_url = '/surveillance/';
                    }
                    if ($role == 40) {
                        $home_url = '/van2/dashlet/van';
                    }
                    if (in_array($role, array(14, 15))) {
                        $home_url = '/campaign/manage-campaigns/';
                    }
                    if (in_array($role, array(25, 47))) {
                        $home_url = '/dashboard/?office=1';
                    }
                    if (in_array($role, array(30))) {
                        $home_url = '/dashboard/?office=2';
                    }
                    if (in_array($role, array(26, 27, 31))) {
                        $home_url = '/dashboard/?office=6';
                    }
                    
                    if (in_array($role, array(3, 4, 5, 6, 17, 24, 35, 36, 41, 42, 43,50,51,52,53,54,57,58,59,62))) {
                        $home_url = '/reports/dashlet/cold-chain-capacity';
                    }

                    if (in_array($role, array(32))) {
                        $home_url = '/docs/project-doc';
                    }

                    $session->home_url = $home_url;

                    if (isset($referrer) && !empty($referrer)) {
                        $home_url = base64_decode($referrer);
                    }

                    parent::_redirect($home_url);
                } catch (Exception $e) {
                    App_FileLogger::info($e);

                    $form->populate($formData);
                    $error = "Username Or Password is incorrect!";
                }
            } else {
                $form->populate($formData);
            }
        }


        $location = new Model_Locations();
        $this->view->sind = $location->getSindhTownsDistrits();

        $this->view->headTitle('Log In');
        $this->view->form = $form;
        $this->view->error = $error;
    }

    /**
     * Logout
     *
     * @return NULL
     */
    public function logoutAction() {
        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();

        if ($role_id == 32) {
            $auth->logout();
            $this->redirect("index/login");
        } else {
            $auth->logout();
            $this->redirect("http://lmis.gov.pk/");
        }
    }

    /**
     * Update
     */
    public function Update() {
        $str_sql = $this->_em->createQueryBuilder()
                ->update('Model_Users u')
                ->set('u.password', '?', $this->m_strPass)
                ->where('u.pk_id = ?', $this->m_login);
        $row = $str_sql->execute();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Check Old Password
     */
    public function checkOldPasswordAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $old_password = md5($this->_request->old_pass);
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("u.password")
                ->from('Users', 'u')
                ->where("u.password ='" . $old_password . "'")
                ->AndWhere("u.pkId='" . $this->_userid . "'");


        $result = $str_sql->getQuery()->getResult();

        if (!empty($result) && count($result) > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    /**
     * Change Password
     */
    public function changePasswordAction() {

        if ($this->_request->isPost()) {
            $this->m_strPass = md5($this->_request->new_pass);
            $this->m_login = $this->_userid;

            $users = $this->_em->getRepository('Users')->find($this->_userid);
            $users->setPassword($this->m_strPass);
            $created_by = $this->_em->find('Users', $this->_userid);
            $users->setModifiedBy($created_by);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $this->redirect("/index/change-password?e=1");
        }
    }

    /**
     * FAQs
     */
    public function faqsAction() {
        $this->_helper->layout->setLayout('front-inner');
    }

    /**
     * FAQ
     */
    public function faqAction() {
        $this->_helper->layout->setLayout('front-inner');
    }

    /**
     * Release Notes
     */
    public function releaseNotesAction() {
        $this->_helper->layout->setLayout('front-inner');
    }

    /**
     * WhatsNew
     */
    public function whatsnewAction() {
        $this->_helper->layout->setLayout('front-inner');
    }

    /**
     * Contact Us
     */
    public function contactUsAction() {
        $this->_helper->layout->setLayout('front-inner');

        $form = new Form_ContactUs();
        $this->view->form = $form;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $data = $form->getValues();

            $user = new Model_Users();
            $user->form_values = $data;
            $saved = $user->saveUserFeedback();
            if ($saved) {
                // Reset the form
                $form->reset();
                $this->view->saved = true;
            }
        }
    }

    /**
     * Login
     */
    public function loginAction() {
        $this->_helper->layout->setLayout('doc');
        $referrer = $this->_getParam('referrer', '');
        $form = new Form_LoginDoc();
        $error = false;

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                try {
                    if (!$this->_identity->login($form->login_id->getValue(), base64_encode($form->password->getValue()))) {
                        $error = true;
                        throw new InvalidArgumentException('INVALID_USER');
                    }
                    $role = $this->_identity->getRoleId();

                    if ($role == 32) {
                        parent::_redirect('/docs/project-doc');
                    }
                    if (isset($referrer) && !empty($referrer)) {
                        parent::_redirect(base64_decode($referrer));
                    } else {
                        parent::_redirect('/dashboard/index');
                    }
                } catch (Exception $e) {
                    App_FileLogger::info($e);
                    $form->populate($formData);
                    $error = "Email Or Password is incorrect!";
                }
            } else {
                $form->populate($formData);
            }
        }

        $this->view->headTitle('Log In');
        $this->view->form = $form;
        $this->view->error = $error;
    }

    /**
     * Register User
     */
    public function registerUserAction() {
        $this->_helper->layout->setLayout('doc');


        $form = new Form_RegisterUser();
        $this->view->form = $form;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $data = $form->getValues();
            $base_url = Zend_Registry::get('baseurl');
            $user = new Model_Users();
            $user->form_values = $data;
            $email = $data['e_mail'];

            //Generate password
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $password = substr(str_shuffle($chars), 0, 8);
            $enc_pswd = base64_encode($password);

            // Save data to users table
            // Documentation User
            define("ROLE_ID", 32);
            $user->form_values['enc_pswd'] = $enc_pswd;
            $user->form_values['role_id'] = ROLE_ID;
            $saved = $user->registerUser();


            //Get user id
            $u_data = $user->getUserId();
            $id = $u_data[0]['pk_id'];

            //Generat hash
            $hash = 'zr' . base64_encode($email . '|' . $id);

            if ($saved) {
                // Reset the form
                $form->reset();
                $this->view->saved = true;

                $this->view->pswd = $password;
                $this->view->id = $id;
            }


            //send email
            $to = $email;
            $subject = "User Registration/Verification for LMIS Documentation";
            $message = "Thank you for registration!
                Your account has been created, you can login with the following credentials
                after you have activated your account by pressing the url below.

                Please click this link to activate your account:
                $base_url/index/verify?email=$email&hash=$hash
                ------------------------
                Login ID: $email
                Password: $password
                ------------------------";

            $headers = "From: support@lmis.gov.pk";

            mail($to, $subject, $message, $headers);
        }
    }

    /**
     * ajaxCheckEmail
     */
    public function ajaxCheckEmailAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost()) {
            $form_values = $this->_request->getParams();

            $user = new Model_Users();
            $user->form_values = $form_values;
            $result = $user->isEmailTaken();

            if (!empty($result) && count($result) > 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /**
     * ajaxRefreshCaptcha
     */
    public function ajaxRefreshCaptchaAction() {

        $this->_helper->layout->disableLayout();

        $form = new Form_ContactUs();
        $captcha = $form->getElement('captcha')->getCaptcha();

        $data = array();

        $data['id'] = $captcha->generate();
        $data['src'] = $captcha->getImgUrl() .
                $captcha->getId() .
                $captcha->getSuffix();

        $this->_helper->json($data);
    }

    /**
     * Verify User Account
     */
    public function verifyAction() {

        $this->_helper->layout->setLayout('doc');

        $email = $this->_request->getParam('email');
        $hash = $this->_request->getParam('hash');

        if (!empty($hash)) {
            $hash = substr($hash, 2);
            $decoded_hash = base64_decode($hash);
            list($email_2, $id) = explode("|", $decoded_hash);

            if ($email == $email_2) {
                $user = new Model_Users();
                $user->form_values['id'] = $id;
                $active = $user->activateUserAccount();
                $this->view->active = $active;
            }
        }
    }

    /**
     * Training Manuals
     */
    public function trainingManualsAction() {
        
    }

    /**
     * Technical Documents
     */
    public function technicalDocumentsAction() {
        
    }

    /**
     * Acronyms
     */
    public function acronymsAction() {
        
    }

    /**
     * All Level Combos One
     */
    public function allLevelCombosOneAction() {
        $this->_helper->layout->disableLayout();

        $session = new Zend_Session_Namespace('alllevel');
        $this->view->sel_prov = $session->warehouse;
        $this->view->role_id = $this->_identity->getRoleId();
        $this->view->page = $this->_request->page;
        if (isset($this->_request->office) && !empty($this->_request->office)) {
            $office = $this->_request->office;

            $warehouse = new Model_Warehouses();

            if ($office == '1') {
                $province_id = $this->_identity->getProvinceId();
                $stakeholder_id = $this->_identity->getStakeholderId();

                $warehouse->form_values = array('stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getFederalWarehouses();
            } else if ($office == '60' || $office == '84') {
                $province_id = $this->_identity->getProvinceId();
                $stakeholder_id = $this->_identity->getStakeholderId();
                $this->view->result = $warehouse->getIHRWarehouses($office);
            } else {
                $locations = new Model_Locations();
                $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2);
                $this->view->result = $locations->getLocationsByLevel();
                $this->view->sel_prov = $session->province;
            }
        }
    }

    /**
     * All Level Combos Two
     */
    public function allLevelCombosTwoAction() {
        $this->_helper->layout->disableLayout();

        $session = new Zend_Session_Namespace('alllevel');
        $this->view->prov_id = $session->warehouse;

        if (isset($this->_request->combo1) && !empty($this->_request->combo1)) {
            $office = $this->_request->office;
            $province_id = $this->_request->combo1;
            $location = new Model_Locations();
            $location->form_values = array('province_id' => $province_id, 'geo_level_id' => 4);

            $warehouse = new Model_Warehouses();

            switch ($office) {
                case 2:
                    $stakeholder_id = $this->_identity->getStakeholderId();
                    $warehouse->form_values = array('stakeholder_id' => $stakeholder_id, 'province_id' => $province_id);
                    $this->view->result = $warehouse->getProvincialWarehouses();
                    break;
                case 3:
                    $stakeholder_id = $this->_identity->getStakeholderId();
                    $warehouse->form_values = array('stakeholder_id' => $stakeholder_id, 'province_id' => $province_id);
                    $this->view->result = $warehouse->getDivsionalWarehousesofProvince();
                    break;
                case 4:
                    $stakeholder_id = $this->_identity->getStakeholderId();
                    $warehouse->form_values = array('stakeholder_id' => $stakeholder_id, 'province_id' => $province_id);
                    $this->view->result = $warehouse->getDistrictWarehousesofProvince();
                    break;
                case 5:
                case 6:
                case 8:
                case 9:
                    $this->view->result = $location->getLocationsByLevelByProvince();
                    $this->view->prov_id = $session->combo2;
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * All Level Combos Three
     */
    public function allLevelCombosThreeAction() {
        $this->_helper->layout->disableLayout();

        $stakeholder_id = $this->_identity->getStakeholderId();
        $session = new Zend_Session_Namespace('alllevel');

        if (isset($this->_request->combo2) && !empty($this->_request->combo2)) {
            $district_id = $this->_request->combo2;
            $office = $this->_request->office;

            $warehouse = new Model_Warehouses();
            $warehouse->form_values = array('district_id' => $district_id, 'stakeholder_id' => $stakeholder_id);

            switch ($office) {
                case 5:
                case 8:
                    $this->view->result = $warehouse->getTehsilWarehousesofDistrict();
                    $this->view->prov_id = $session->warehouse;
                    break;
                case 6:
                    $this->view->result = $warehouse->getUcsByDistrict();
                    break;
                case 9:
                    $this->view->result = $warehouse->getUCWarehousesofDistrict();
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * All Level Combos Four
     */
    public function allLevelCombosFourAction() {
        $this->_helper->layout->disableLayout();

        $stakeholder_id = $this->_identity->getStakeholderId();
        $session = new Zend_Session_Namespace('alllevel');

        if (isset($this->_request->combo3) && !empty($this->_request->combo3)) {
            $uc_id = $this->_request->combo3;
            $office = $this->_request->office;

            $warehouse = new Model_Warehouses();
            $warehouse->form_values = array('uc_id' => $uc_id, 'stakeholder_id' => $stakeholder_id);

            switch ($office) {

                case 6:
                    //getUcsByTehsil    $this->view->result = $warehouse->getUCWarehousesofUc();
                    $this->view->result = $warehouse->getUcsByTehsil();
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * All Level Combos Four
     */
    public function allLevelCombosFiveAction() {
        $this->_helper->layout->disableLayout();

        $stakeholder_id = $this->_identity->getStakeholderId();
        $session = new Zend_Session_Namespace('alllevel');

        if (isset($this->_request->combo3) && !empty($this->_request->combo3)) {
            $uc_id = $this->_request->combo3;
            $office = $this->_request->office;

            $warehouse = new Model_Warehouses();
            $warehouse->form_values = array('uc_id' => $uc_id, 'stakeholder_id' => $stakeholder_id);

            switch ($office) {

                case 6:
                    $this->view->result = $warehouse->getUCWarehousesofUc();
                    //   $this->view->result = $warehouse->getUcsByTehsil();
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * All Level Combos Four
     */
    public function allLevelCombosFiveDistrictAction() {
        $this->_helper->layout->disableLayout();

        $stakeholder_id = $this->_identity->getStakeholderId();
        $session = new Zend_Session_Namespace('alllevel');

        if (isset($this->_request->combo3) && !empty($this->_request->combo3)) {
            $uc_id = $this->_request->combo3;
            $office = $this->_request->office;

            $warehouse = new Model_Warehouses();
            $warehouse->form_values = array('district_id' => $uc_id, 'stakeholder_id' => $stakeholder_id);

            switch ($office) {

                case 6:
                    $this->view->result = $warehouse->getUCWarehousesofDistricts();
                    //   $this->view->result = $warehouse->getUcsByTehsil();
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Level Combos One
     */
    public function levelCombosOneAction() {
        $this->_helper->layout->disableLayout();
        $office = $this->_request->office;
        $province_id = $this->_identity->getProvinceId();
        $district_id = $this->_identity->getDistrictId($this->_userid);
        $stakeholder_id = $this->_identity->getStakeholderId();
        $role_id = $this->_identity->getRoleId();
        if ($role_id == 7) {
            $tehsil_id = $this->_identity->getTehsilId($this->_userid);
        } else {
            $tehsil_id = '';
        }

        $location = new Model_Locations();
        $location->form_values = array('parent_id' => $province_id, 'geo_level_id' => $office);

        $warehouse = new Model_Warehouses();

        switch ($office) {
            case 1:
                break;
            case 2:
            case 3:
            case 4:
                $this->view->result = $location->getLocationsByLevel();
                break;
            case 5:
                $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getProvincialWarehouses();
                break;
            case 6:
                $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getDivsionalWarehousesofProvince();
                break;
            case 7:
                $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getDistrictWarehousesofProvince();
                break;
            case 8:
                $warehouse->form_values = array('district_id' => $district_id, 'stakeholder_id' => $stakeholder_id);
                $this->view->result = $warehouse->getTehsilWarehousesofDistrict();
                break;
            case 9:
                $warehouse->form_values = array('district_id' => $district_id, 'stakeholder_id' => $stakeholder_id, 'role_id' => $role_id, 'tehsil_id' => $tehsil_id);
                $this->view->result = $warehouse->tehsilLocations();
                break;
            case 60:
            case 84:
                $this->view->result = $warehouse->getIHRWarehouses($office);
                break;
            default:
                break;
        }
    }

    /**
     * Level Combos Two
     */
    public function levelCombosTwoAction() {
        $this->_helper->layout->disableLayout();

        $office = $this->_request->office;
        $province_id = $this->_request->combo1;
        $stakeholder_id = $this->_identity->getStakeholderId();

        $location = new Model_Locations();
        $location->form_values = array('parent_id' => 10, 'geo_level_id' => 2);

        $warehouse = new Model_Warehouses();
        $warehouse->form_values = array('province_id' => $province_id, 'stakeholder_id' => $stakeholder_id);

        switch ($office) {
            case 1:
                break;
            case 2:
                $this->view->result = $warehouse->getProvincialWarehouses();
                break;
            case 3:
                $this->view->result = $warehouse->getDivsionalWarehousesofProvince();
                break;
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
            default:
                break;
        }
    }

    /**
     * Level Combos Three
     */
    public function levelCombosThreeAction() {
        $this->_helper->layout->disableLayout();
        $office = $this->_request->office;
        $location = new Model_Locations();

        switch ($office) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $this->view->result = $location->getLocationsByLevel(10, 2);
                break;
            default:
                break;
        }
    }

    /**
     * Locations Combos One
     */
    public function locationsCombosOneAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->office) && !empty($this->_request->office)) {

            $locations = new Model_Locations();
            $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2, 'office' => $this->_request->office);

            $this->view->result = $locations->getLocationsByLevel();
            if (!empty($this->_request->province_id)) {

                $this->view->province_id = $this->_request->province_id;
            }
        }
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * Locations Combos Two
     */
    public function locationsCombosTwoAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->combo1) && !empty($this->_request->combo1)) {
            $province_id = $this->_request->combo1;
            $office = $this->_request->office;

            $location = new Model_Locations();

            $location->form_values = array('province_id' => $province_id, 'geo_level_id' => 4, 'office' => $office);

            $this->view->result = $location->districtLocations();


            if (!empty($this->_request->district_id)) {
                $this->view->district_id = $this->_request->district_id;
            }
        }
        $this->view->role_id = $this->_identity->getRoleId();
    }

    /**
     * Locations Combos Three
     */
    public function locationsCombosThreeAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->combo2) && !empty($this->_request->combo2)) {
            $district_id = $this->_request->combo2;

            $location = new Model_Locations();
            $location->form_values = array('parent_id' => $district_id, 'geo_level_id' => 5);
            $this->view->result = $location->getLocationsByLevelByTehsil();

            if (!empty($this->_request->tehsil_id)) {

                $this->view->tehsil_id = $this->_request->tehsil_id;
            }
        }
    }

    /**
     * Locations Combos Four
     */
    public function locationsCombosFourAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->combo3) && !empty($this->_request->combo3)) {
            $tehsil_id = $this->_request->combo3;

            $location = new Model_Locations();
            $location->form_values = array('parent_id' => $tehsil_id, 'geo_level_id' => 6);
            $this->view->result = $location->getLocationsByLevelByTehsil();

            if (!empty($this->_request->uc_id)) {
                $this->view->uc_id = $this->_request->uc_id;
            }
        }
    }

    /**
     * Level Combos Four
     */
    public function levelCombosFourAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->combo3) && !empty($this->_request->combo3)) {
            $tehsil_id = $this->_request->combo3;

            $warehouse = new Model_Warehouses();
            $warehouse->form_values = array('parent_id' => $tehsil_id, 'geo_level_id' => 6);
            $this->view->result = $warehouse->getUCWarehousesofTehsil();
        }
    }

    /**
     * Locations Combos For Div
     */
    public function locationsCombosForDivAction() {
        $this->_helper->layout->disableLayout();

        $province_id = $this->_request->combo1;
        $locations = new Model_Locations();
        $locations->form_values = array('parent_id' => $province_id, 'geo_level_id' => 3);
        $this->view->result = $locations->getDivisionsByProvince();
        if (!empty($this->_request->div_id)) {
            $this->view->div_id = $this->_request->div_id;
        }
    }

    /**
     * Locations Combos For Distt
     */
    public function locationsCombosForDisttAction() {
        $this->_helper->layout->disableLayout();

        $div_id = $this->_request->combodiv;
        $locations = new Model_Locations();
        $locations->form_values = array('div_id' => $div_id, 'geo_level_id' => 4);

        $this->view->result = $locations->getDistrictsByDivision();
        if (!empty($this->_request->district_id)) {
            $this->view->district_id = $this->_request->district_id;
        }
    }

    /**
     * Locations Combos For District
     */
    public function locationsCombosForDistrictAction() {
        $this->_helper->layout->disableLayout();

        $prov_id = $this->_request->province_id;
        $locations = new Model_Locations();
        $locations->form_values = array('province_id' => $prov_id, 'geo_level_id' => 4);

        $this->view->result = $locations->getDistrictsByProvince();
    }

    /**
     * Project Documentation
     */
    public function projectDocAction() {

        $this->_helper->layout->setLayout('doc');
    }

    /**
     * Change Password Doc
     */
    public function changePasswordDocAction() {
        $this->_helper->layout->setLayout('doc');

        if ($this->_request->isPost()) {
            $this->m_strPass = base64_encode($this->_request->new_pass);
            $this->m_login = $this->_userid;


            $users = $this->_em->getRepository('Users')->find($this->_userid);
            $users->setPassword($this->m_strPass);

            $created_by = $this->_em->find('Users', $this->_user_id);
            $users->setModifiedBy($created_by);
            $users->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($users);
            $this->_em->flush();
            $this->redirect("/index/change-password-doc?e=1");
        }
    }

    /**
     * Forget Password Doc
     */
    public function forgetPasswordDocAction() {
        $this->_helper->layout->setLayout('doc');

        if ($this->_request->isPost()) {

            $e_mail = $this->_request->e_mail;
            if (!empty($e_mail)) {

                // Documentation User
                define("ROLE_ID", 32);
                $user = new Model_Users();
                $user->form_values['e_mail'] = $e_mail;
                $user->form_values['role_id'] = ROLE_ID;

                $u_data = $user->getDocUserPassword();
                $enc_password = $u_data[0]['password'];
                $password = base64_decode($enc_password);
                //send password via email
                $to = $e_mail;
                $subject = "Forget passowrd";
                $message = "Dear User,

                            Your login information is as under:

                            Login ID: $e_mail
                            Password: $password

                            Note: You may change your  password by using the 'Change Password' option, which is available after you login to the system.                            ";

                $headers = "From: support@lmis.gov.pk";
                mail($to, $subject, $message, $headers);
                $this->view->saved = true;
            }
        }
    }

    /**
     * ajaxCheckUser
     */
    public function ajaxCheckUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        if ($this->_request->isPost()) {
            $form_values = $this->_request->getParams();

            $user = new Model_Users();
            $user->form_values = $form_values;
            $result = $user->isEmailTaken();

            if (!empty($result) && count($result) > 0) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }

    public function maintenanceAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        echo "<h1>Site is down for maintanance. Please try again after 1PM today. </h1>";
    }

    /**
     * ajaxCheckUser
     */
    public function ajaxLocationsByYearLevelAction() {
        $this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();
        $dql = $this->_em_read->createQueryBuilder()
                ->select("loc.pkId")
                ->from("LocationPopulations", "lp")
                ->join("lp.location", "loc")
                ->where("YEAR(lp.estimationDate ) = " . $formData['year']);

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId", "l.locationName")
                ->from("Locations", "l")
                ->where("l.geoLevel =" . $formData['level'])
                ->AndWhere("l.pkId NOT IN($dql)");

//        print($str_sql->getQuery()->getSql());
//        exit;

        $this->view->data = $str_sql->getQuery()->getResult();
    }

    /**
     * Register
     */
    public function registerAction() {
        $this->_helper->layout->setLayout('doc');

        $form = new Form_Register();
        $this->view->form = $form;

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $data = $form->getValues();
            $base_url = Zend_Registry::get('baseurl');
            $user = new Model_Users();

            $user->form_values = $data;
            $email = $data['e_mail'];

            //Generate password
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $password = substr(str_shuffle($chars), 0, 8);
            $enc_pswd = base64_encode($password);

            // Save data to users table
            // Documentation User
            define("ROLE_ID", 32);
            $user->form_values['enc_pswd'] = $enc_pswd;
            $user->form_values['role_id'] = ROLE_ID;
            $saved = $user->register();

            if ($saved) {
                // Reset the form
                $form->reset();
                $this->view->saved = true;

                $this->view->pswd = $password;
                $this->view->id = $id;
            }
            //Get user id
//            $u_data = $user->getUserId();
//            $id = $u_data[0]['pk_id'];
//
//            //Generat hash
//            $hash = 'zr' . base64_encode($email . '|' . $id);
//
//            if ($saved) {
//                // Reset the form
//                $form->reset();
//                $this->view->saved = true;
//
//                $this->view->pswd = $password;
//                $this->view->id = $id;
//            }
//
//
//            //send email
//            $to = $email;
//            $subject = "User Registration/Verification for LMIS Documentation";
//            $message = "Thank you for registration!
//                Your account has been created, you can login with the following credentials
//                after you have activated your account by pressing the url below.
//
//                Please click this link to activate your account:
//                $base_url/index/verify?email=$email&hash=$hash
//                ------------------------
//                Login ID: $email
//                Password: $password
//                ------------------------";
//
//            $headers = "From: support@lmis.gov.pk";
//
//            mail($to, $subject, $message, $headers);
        }
    }

    public function uptimeAction() {
        $this->_helper->layout->setLayout('front-inner');
    }

    /**
     * Survey
     */
    public function surveyAction() {
        $this->_helper->layout->setLayout('without-header-footer');
        $user = $this->_em->getRepository('Users')->find($this->_userid);

        if ($this->_request->isPost()) {
            $form_values = $this->_request->getPost();
            $loading = $form_values['btn-loading'];

            if ($loading == 'contact') {
                $phone = $form_values['cellnumber'];
                $user->setUserName($form_values['name']);
                $user->setEmail($form_values['email']);
                $user->setCellNumber($phone);
                $user->setPhoneNumber($phone);
                $user->setDepartment($form_values['department']);
                $user->setDesignation($form_values['office']);
                $this->_em->persist($user);
                $this->_em->flush();

                $this->redirect("/index/survey?e=2");
            } else {
                if (!empty($form_values['feedback_id'])) {
                    $survey = $this->_em->getRepository('Survey')->find($form_values['feedback_id']);
                } else {
                    $survey = new Survey();
                }
                $survey->setName($user->getUserName());
                $survey->setCellNumber($user->getCellNumber());
                $survey->setOffice($user->getDesignation());
                $survey->setDepartment($user->getDepartment());
                $survey->setEmail($user->getEmail());
                $survey->setQ1YN($form_values['y_n_data_difficulty']);
                $survey->setQ2YN($form_values['y_n_report']);
                if ($form_values['y_n_data_difficulty'] == 1) {
                    $survey->setQ1DataDifficulty($form_values['data_difficulty']);
                }
                if ($form_values['y_n_data_difficulty'] == 0) {
                    $survey->setQ1DataDifficulty('');
                }
                if ($form_values['y_n_report'] == 1) {
                    $survey->setQ2Report($form_values['report']);
                }
                if ($form_values['y_n_report'] == 0) {
                    $survey->setQ2Report('');
                }
                $survey->setComment($form_values['comment']);
                $created_by = $this->_em->find('Users', $this->_userid);
                $survey->setUser($created_by);
                $survey->setCreatedBy($created_by);
                $survey->setModifiedBy($created_by);
                $survey->setCreatedDate(App_Tools_Time::now());
                $survey->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($survey);
                $this->_em->flush();

                $this->redirect("/index/survey?e=1");
            }
        }
        $model_survey = new Model_Survey();
        $result = $model_survey->getSurveyList();
        $this->view->result = $result;
        $this->view->user = $user;
    }

    public function activeUsersAction() {
        $this->_helper->layout->disableLayout();

        $model_users = new Model_Users();
        $result = $model_users->getActiveUsers(50);
        $this->view->result = $result;
    }

    public function updateProfileAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $model_users = new Model_Users();
        $model_users->form_values = $this->_request->getParams();
        $model_users->updateProfile();
        echo 'success';
    }
    
    public function delinkAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $model_users = new Model_Users();
        $model_users->form_values = $this->_request->getParams();
        $model_users->delinkEmailAddress();
        echo 'success';
    }

    public function emailVerificationAction() {
        $this->_helper->layout->disableLayout();

        $token = $this->_request->getParam('t');
        $email = base64_decode(substr_replace(substr_replace($token, '', 0, 5), '', -3));

        $ev = $this->_em->getRepository('EmailVerification')->findOneBy(array('emailAddress' => $email, 'isVerified' => 0));
        if (count($ev) > 0) {
            $ev->setIsVerified(1);
            $this->_em->persist($ev);
            $this->_em->flush();
        } else {
            exit("Your verifcation link has expired. Please write to support@lmis.gov.pk for further assistance");
        }
    }

}
