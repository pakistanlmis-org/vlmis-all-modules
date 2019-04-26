<?php

/**
 * Cadmin_ManageUsersController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Users
 */
class Cadmin_ManageUsersController extends App_Controller_Base {

    /**
     * Cadmin_ManageUsersController index
     */
    public function indexAction() {
        $form = new Form_Cadmin_UserSearch();
        $form_add = new Form_Cadmin_User();
        $params = array();

        $users = new Model_Users();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $loginid = $form->getValue('login_id');
                $role_id = $form->getValue('role');

                if (!empty($loginid)) {
                    $params['loginId'] = $loginid;
                }
                if (!empty($role_id)) {
                    $params['role'] = $role_id;
                }
            }
        } else {
            $loginid = $this->_getParam('login_id');
            $role_id = $this->_getParam('role');

            if (!empty($loginid)) {
                $params['loginId'] = $loginid;
                $form->login_id->setValue($loginid);
            }
            if (!empty($role_id)) {
                $params['role'] = $role_id;
                $form->role->setValue($role_id);
            }
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "login_id");
        $users->form_values = $params;
        $result = $users->getUsers($order, $sort);

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;
        $this->view->form_add = $form_add;
        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->pagination_params = $params;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');

        $this->view->inlineScript()->appendFile($base_url . '/common/bootstrap/extend/jasny-bootstrap/js/jasny-bootstrap.min.js');
        $this->view->inlineScript()->appendFile($base_url . '/common/bootstrap/extend/jasny-bootstrap/js/bootstrap-fileupload.js');

        $this->view->headLink()->appendStylesheet($base_url . '/common/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css');
        $this->view->headLink()->appendStylesheet($base_url . '/common/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css');
    }

    /**
     * This method adds user
     */
    public function addAction() {
        $form = new Form_Cadmin_User();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $user = new Users();
            $user->setLoginId($form->login_id->getValue());
            $user->setUserName($form->login_id->getValue());
            $role_id = $this->_em->find('Roles', $form->role->getValue());
            $user->setRole($role_id);
            $user->setEmail($form->email->getValue());
            $user->setCellNumber($form->phone->getValue());
            $user->setPassword(base64_encode($form->password->getValue()));
            $created_by = $this->_em->find('Users', $this->_userid);
            $user->setCreatedBy($created_by);
            $user->setCreatedDate(App_Tools_Time::now());
            $user->setModifiedBy($created_by);
            $user->setModifiedDate(App_Tools_Time::now());
            $user->setLoggedAt(new \DateTime(date("0000-00-00")));
            $stakeholder = $this->_em->find('Stakeholders', 1);
            $user->setStakeholder($stakeholder);

            $this->_em->persist($user);
            $this->_em->flush();

            $user_wh = new WarehouseUsers();
            $user_wh->setUser($user);
            $warehouse_id = $this->_em->find('Warehouses', $this->_request->getPost('warehouse'));
            $user_wh->setWarehouse($warehouse_id);
            $user_wh->setIsDefault(1);

            $user_wh->setCreatedBy($created_by);
            $user_wh->setCreatedDate(App_Tools_Time::now());
            $user_wh->setModifiedBy($created_by);
            $user_wh->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($user_wh);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-users");
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->setLayout("ajax");
        $user_id = $this->_request->getParam('user_id', '');

        $user = $this->_em->find('Users', $user_id);
        $form = new Form_Cadmin_User();
        $form->addFields();
        $form->addHidden();

        $form->login_id->setValue($user->getLoginId());
        $form->role->setValue($user->getRole()->getPkId());
        $form->email->setValue($user->getEmail());
        $form->phone->setValue($user->getCellNumber());
        $form->designation->setValue($user->getDesignation());
        $form->department->setValue($user->getDepartment());
        $form->address->setValue($user->getAddress());
        $user_wh = $this->_em->getRepository("WarehouseUsers")->findOneBy(array("user" => $user_id));
        
        if(empty($user_wh))
        {
            echo "This item is not editable";
            exit();
        }

        $form->old_warehouse->setValue($user_wh->getWarehouse()->getWarehouseName());
        $form->id->setValue($user->getPkId());

        $this->view->form = $form;
        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos2.js');
        $this->view->inlineScript()->appendFile($base_url . '/js/manage-health-facility/ajax-edit.js');
    }

    /**
     * This method updates user
     */
    public function updateAction() {
        $form = new Form_Cadmin_User();
        $form->addFields();
        $form->addHidden();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $user_id = $form->id->getValue();

            // File Upload Start
            $file_path = $form->photo->getFileName();
            $file_ext = substr($file_path, -3);
            $file_name = $user_id . "." . $file_ext;
            $form->photo->addFilter('Rename', array(
                'target' => UPLOAD_PATH . "/" . $file_name,
                'overwrite' => true
            ));
            $form->photo->receive();
            // File Upload End       

            $user = $this->_em->getRepository("Users")->find($user_id);
            $role_id = $this->_em->find('Roles', $form->role->getValue());
            $user->setRole($role_id);
            $user->setCellNumber($form->phone->getValue());
            if ($user->getPassword() == base64_encode($form->old_password->getValue())) {
                $user->setPassword(base64_encode($form->new_password->getValue()));
            }
            $user->setDesignation($form->designation->getValue());
            $user->setDepartment($form->department->getValue());
            $user->setAddress($form->address->getValue());
            $user->setPhoto($file_name);

            $created_by = $this->_em->find('Users', $this->_userid);
            $user->setModifiedBy($created_by);
            $user->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($user);
            $this->_em->flush();

            $warehouse = $this->_request->getPost('warehouse2');
            if (!empty($warehouse)) {
                $user_wh = $this->_em->getRepository("WarehouseUsers")->findOneBy(array("user" => $user->getPkId()));
                if (count($user_wh) > 0) {
                    $warehouse_id = $this->_em->find('Warehouses', $warehouse);
                    $user_wh->setWarehouse($warehouse_id);
                    $user_wh->setModifiedBy($created_by);
                    $user_wh->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($user_wh);
                    $this->_em->flush();
                }
            }
        }
        $this->_redirect("/cadmin/manage-users");
    }

    /**
     * This method deletes user
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $user_id = $this->_request->getParam("user_id");
        $userswh = $this->_em->getRepository("WarehouseUsers")->findBy(array("user" => $user_id));
        foreach ($userswh as $wh) {
            $this->_em->remove($wh);
        }

        $user = $this->_em->getRepository("Users")->find($user_id);
        $this->_em->remove($user);

        return $this->_em->flush();
    }

    /**
     * This method checks User
     */
    public function checkUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $username = $this->_request->getParam('loginId');

        $usersList = $this->_em->getRepository("Users")->findBy(array("loginId" => $username));

        if (count($usersList) > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

}
