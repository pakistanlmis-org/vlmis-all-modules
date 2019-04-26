<?php

/**
 * Cadmin_ManageCcmStatusListsController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Controller Manages CCM Status Lists
 */
class Cadmin_ManageCcmStatusListsController extends App_Controller_Base {

    /**
     * This method searches Ccm Status Lists 
     */
    public function indexAction() {
        $form = new Form_Cadmin_StatusListSearch();
        $form_add = new Form_Cadmin_StatusListAdd();

        $status_lists = new Model_CcmStatusList();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $ccm_status_list_name = $form->getValue('ccm_status_list_name');
                $status = $form->getValue('status');

                if (!empty($ccm_status_list_name)) {
                    $status_lists->form_values['ccmStatusListName'] = $ccm_status_list_name;
                }
                if (!empty($status)) {
                    $status_lists->form_values['status'] = $status;
                }
            }
            $form->ccm_status_list_name->setValue($ccm_status_list_name);
            $form->status->setValue($status);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "ccm_status_list_name");

        $result = $status_lists->getAllStatusLists($order, $sort);

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

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * This method adds Ccm Status Lists
     */
    public function addAction() {
        $form = new Form_Cadmin_StatusListAdd();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $status_lists = new CcmStatusList();
            $status_lists->setType($form->type->getValue());
            $status_lists->setCcmStatusListName($form->ccm_status_list_name->getValue());
            $status_lists->setType($form->type->getValue());
            $status_lists->setStatus($form->status->getValue());
            $created_by = $this->_em->find('Users', $this->_userid);

            $status_lists->setCreatedBy($created_by);
            $status_lists->setCreatedDate(App_Tools_Time::now());
            $status_lists->setModifiedBy($created_by);
            $status_lists->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($status_lists);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-ccm-status-lists?success=1");
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->disableLayout();
        $status_list_id = $this->_request->getParam('status_list_id', '');

        $statusList = $this->_em->find('CcmStatusList', $status_list_id);
        $form = new Form_Cadmin_StatusListAdd();

        $form->ccm_status_list_name->setValue($statusList->getCcmStatusListName());
        $form->type->setValue($statusList->getType());
        $form->status_list_id->setValue($statusList->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method updates Ccm Status Lists
     */
    public function updateAction() {

        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $statusList = $this->_em->getRepository("CcmStatusList")->find($form_values['status_list_id']);
            $statusList->setCcmStatusListName($form_values['ccm_status_list_name']);
            $statusList->setType($form_values['type']);
            $created_by = $this->_em->find('Users', $this->_userid);
            $statusList->setModifiedBy($created_by);
            $statusList->setModifiedDate(new \DateTime());

            $this->_em->persist($statusList);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-ccm-status-lists?success=2");
    }

    /**
     * This method Changes Status
     */
    public function ajaxChangeStatusAction() {
        $this->_helper->layout->disableLayout();
        $row = $this->_em->getRepository("CcmStatusList")->find($this->_request->getParam('id'));
        if ($this->_request->getParam('ajaxaction') == 'active') {
            $row->setStatus('1');
        } else if ($this->_request->getParam('ajaxaction') == 'deactive') {
            $row->setStatus('0');
        }

        $created_by = $this->_em->find('Users', $this->_user_id);
        $row->setModifiedBy($created_by);
        $row->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($row);
        $this->_em->flush();
        $this->view->ajaxaction = $this->_request->getParam('ajaxaction');
    }

    /**
     * Thid method checks User
     */
    public function checkUserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $username = $this->_request->getParam('loginid');

        $usersList = $this->_em->getRepository("Users")->findBy(array("loginId" => $username));

        if (count($usersList) > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

}
