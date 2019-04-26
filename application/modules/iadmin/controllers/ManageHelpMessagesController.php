<?php

/**
 * Iadmin_ManageHelpMessagesController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Help Messages
 */
class Iadmin_ManageHelpMessagesController extends App_Controller_Base {

    /**
     * Iadmin_ManageHelpMessagesController index
     */
    public function indexAction() {
        $form = new Form_Iadmin_MessagesSearch();
        $form_add = new Form_Iadmin_MessagesAdd();
        $params = array();

        $hp = new Model_HelpMessages();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $search_text = $form->getValue('search_text');
                $page_name = $form->getValue('page_name');
                $deleted = $form->getValue('deleted');

                if (!empty($search_text)) {
                    $params['search_text'] = $search_text;
                }
                if (!empty($page_name)) {
                    $params['page_name'] = $page_name;
                }
                if (!empty($deleted)) {
                    $params['deleted'] = $deleted;
                }
            }
        } else {
            $search_text = $this->_getParam('search_text');
            $page_name = $this->_getParam('page_name');
            $deleted = $this->_getParam('deleted');

            if (!empty($search_text)) {
                $params['search_text'] = $search_text;
                $form->search_text->setValue($search_text);
            }
            if (!empty($page_name)) {
                $params['page_name'] = $page_name;
                $form->page_name->setValue($page_name);
            }
            if (!empty($deleted)) {
                $params['deleted'] = $deleted;
                $form->deleted->setValue($deleted);
            }
        }

        $sort = $this->_getParam("sort", "DESC");
        $order = $this->_getParam("order", "sr_no");
        $hp->form_values = $params;
        $result = $hp->getBySearch($order, $sort);

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
    }

    /**
     * This method adds Help Messages
     */
    public function addAction() {
        $form = new Form_Iadmin_MessagesAdd();
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $hp = new HelpMessages();
            $resource_id = $this->_em->find('Resources', $form->page_name->getValue());
            $hp->setResource($resource_id);
            $hp->setDescription($form->description->getValue());
            $hp->setStatus($form->status->getValue());
            $created_by = $this->_em->find('Users', $this->_user_id);
            $hp->setCreatedBy($created_by);
            $hp->setCreatedDate(App_Tools_Time::now());
            $hp->setModifiedBy($created_by);
            $hp->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($hp);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-help-messages");
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->setLayout("ajax");
        $id = $this->_request->getParam('id', '');

        $hp = $this->_em->find('HelpMessages', $id);
        $form = new Form_Iadmin_MessagesAdd();
        $form->addHidden();

        $form->page_name->setValue($hp->getResource()->getPkId());
        $form->description->setValue($hp->getDescription());
        $form->status->setValue($hp->getStatus());
        $form->id->setValue($hp->getPkId());

        $this->view->form_add = $form;
    }

    /**
     * This method updates Help Messages
     */
    public function updateAction() {
        $form = new Form_Iadmin_MessagesAdd();
        $form->addHidden();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $id = $form->id->getValue();
            $hp = $this->_em->getRepository("HelpMessages")->find($id);
            $resource_id = $this->_em->find('Resources', $form->page_name->getValue());
            $hp->setResource($resource_id);
            $hp->setDescription($form->description->getValue());
            $hp->setStatus($form->status->getValue());
            $created_by = $this->_em->find('Users', $this->_user_id);
            $hp->setModifiedBy($created_by);
            $hp->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($hp);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-help-messages");
    }

    /**
     * This method deletes Help Messages
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $id = $this->_request->getParam("id");
        $hp1 = $this->_em->getRepository("HelpMessages")->find($id);
        $hp1->setStatus(Model_HelpMessages::DELETED);
        $this->_em->persist($hp1);
        return $this->_em->flush();
    }

}
