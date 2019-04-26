<?php

/**
 * Cadmin_ManageMakesController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Makes 
 */
class Cadmin_ManageMakesController extends App_Controller_Base {

    /**
     * This method searches Makes
     */
    public function indexAction() {
        $form = new Form_Cadmin_MakeSearch();
        $form_add = new Form_Cadmin_MakeAdd();

        $makes = new Model_CcmMakes();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $name = $form->getValue('name');
                $status = $form->getValue('status');

                if (!empty($name)) {
                    $makes->form_values['ccmMakeName'] = $name;
                }
                if (!empty($status)) {
                    $makes->form_values['status'] = $status;
                }
            }
            $form->name->setValue($name);
            $form->status->setValue($status);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "make_name");

        $result = $makes->getAllMakes($order, $sort);

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
     * This method adds Makes
     */
    public function addAction() {
        $form = new Form_Cadmin_MakeAdd();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $makes = new CcmMakes();
            $makes->setCcmMakeName($form->ccm_make_name->getValue());
            $makes->setStatus($form->status->getValue());
            $created_by = $this->_em->find('Users', $this->_userid);
            $makes->setCreatedBy($created_by);
            $makes->setCreatedDate(App_Tools_Time::now());
            $makes->setModifiedBy($created_by);
            $makes->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($makes);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-makes");
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->disableLayout();
        $make_id = $this->_request->getParam('make_id', '');

        $make = $this->_em->find('CcmMakes', $make_id);
        $form = new Form_Cadmin_MakeAdd();

        $form->ccm_make_name->setValue($make->getCcmMakeName());
        $form->make_id->setValue($make->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method updates Makes
     */
    public function updateAction() {

        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $make = $this->_em->getRepository("CcmMakes")->find($form_values['make_id']);
            $make->setCcmMakeName($form_values['ccm_make_name']);
            $created_by = $this->_em->find('Users', $this->_userid);
            $make->setModifiedBy($created_by);
            $make->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($make);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-makes");
    }

    /**
     * This method deletes Makes
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        // Call Ajax
        $make_id = $this->_request->getParam("make_id");

        $make = $this->_em->getRepository("CcmMakes")->find($make_id);

        $this->_em->remove($make);

        return $this->_em->flush();
    }

    /**
     * This method checks User
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
