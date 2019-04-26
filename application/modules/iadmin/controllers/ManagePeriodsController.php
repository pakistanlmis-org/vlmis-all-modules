<?php

/**
 * Iadmin_ManagePeriodsController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Nabila Shahid <nabila.deliver@gmail.com>
 * @version    2.5.1
 */

/**
 * This controller manages periods
 */
class Iadmin_ManagePeriodsController extends App_Controller_Base {

    /**
     * This method searches Periods
     */
    public function periodsAction() {

        $form = new Form_Iadmin_Periods();

        $period = new Model_Period();
        $params = array();

        //echo "hello1";

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $period_name = $form->getValue('period_name');

                if (!empty($period_name)) {
                    $period->form_values['period_name'] = $period_name;
                }
            }

            $form->period_name->setValue($period_name);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "stakeholder");

        $result = $period->getPeriods($order, $sort);

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->pagination_params = $params;
    }

    /**
     * This method retrieves Period information for edit
     */
    public function ajaxPeriodEditAction() {
        $this->_helper->layout->disableLayout();
        $period = $this->_em->find('Period', $this->_request->getParam('period_id'));

        $form = new Form_Iadmin_Periods();
        $form->period_name_update->setValue($period->getPeriodName());
        $form->pk_id->setValue($period->getPkId());
        $form->period_code_update->setValue($period->getPeriodCode());
        $form->begin_month->setValue($period->getBeginMonth());
        $form->end_month->setValue($period->getEndMonth());
        $this->view->form = $form;
    }

    /**
     * This method checks Period code
     */
    public function checkPeriodCodeAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $period = new Model_Period();
//       if ($form_values['period_code'] !== $form_values['period_code_hidden']) {
        $period->form_values = $form_values;
        $result = $period->checkPeriodCode();
        if (count($result) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * This method adds Periods
     */
    public function addPeriodAction() {
        $form = new Form_Iadmin_Periods();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $period = new Period();
            $period->setPeriodName($form->period_name->getValue());
            $period->setPeriodCode($form->period_code->getValue());
            $period->setIsMonth('No');
            $period->setBeginMonth($form->begin_month->getValue());
            $period->setEndMonth($form->end_month->getValue());
            $period->setMonthCount($form->end_month->getValue() - $form->begin_month->getValue() + 1);
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $period->setCreatedBy($createdBy);
            $period->setModifiedBy($createdBy);
            $period->setCreatedDate(App_Tools_Time::now());
            $period->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($period);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-periods/periods");
    }

    /**
     * This method updates Periods
     */
    public function updatePeriodAction() {
        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $period = $this->_em->getRepository("Period")->find($form_values['pk_id']);

            $period->setBeginMonth($form_values['begin_month']);
            $period->setEndMonth($form_values['end_month']);
            $period->setMonthCount($form_values['end_month'] - $form_values['begin_month'] + 1);
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $period->setModifiedBy($createdBy);
            $period->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($period);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-periods/periods");
    }

}
