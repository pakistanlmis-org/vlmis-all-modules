<?php

/**
 * Cadmin_ManageAssetSubTypesController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Asset Sub Types
 */
class Cadmin_ManageAssetSubTypesController extends App_Controller_Base {

    /**
     * This method Searches Asset Sub Types 
     */
    public function indexAction() {
        $form = new Form_Cadmin_MakeSearch();
        $form_add = new Form_Cadmin_AssetSubTypeAdd();
        $asset_type = new Model_CcmAssetTypes();

        if ($this->_request->isPost()) {
            if ($form_add->isValid($this->_request->getPost())) {
                $name = $form_add->getValue('assetSubType');

                if (!empty($name)) {
                    $asset_type->form_values['assetSubType'] = $name;
                }
            }
            $form_add->assetSubType->setValue("$name");
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "asset_sub_type");

        $result = $asset_type->getAllAssetSubTypes($order, $sort);

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
     * This method adds Asset Sub Types
     */
    public function addAction() {
        $form = new Form_Cadmin_AssetSubTypeAdd();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $asset_type = new CcmAssetTypes();
            $asset_type->setAssetTypeName($form->asset_sub_type->getValue());
            $asset_type->setStatus('1');
            $parent_id = $this->_em->getRepository('CcmAssetTypes')->find($form->asset_type->getValue());
            $asset_type->setParent($parent_id);
            $created_by = $this->_em->find('Users', $this->_userid);
            $asset_type->setCreatedBy($created_by);
            $asset_type->setCreatedDate(App_Tools_Time::now());
            $asset_type->setModifiedBy($created_by);
            $asset_type->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($asset_type);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-asset-sub-types");
    }

    /**
     * This method  retrieves Asset Sub Types for Edit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->disableLayout();
        $asset = $this->_em->find('CcmAssetTypes', $this->_request->getParam('asset_id'));
        $form = new Form_Cadmin_AssetSubTypeAdd();
        $form->asset_type->setValue($asset->getParent()->getPkId());
        $form->asset_sub_type->setValue($asset->getAssetTypeName());
        $form->asset_id->setValue($asset->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method Changes Status of Asset Sub Types
     */
    public function ajaxChangeStatusAction() {
        $this->_helper->layout->disableLayout();
        $row = $this->_em->getRepository("CcmAssetTypes")->find($this->_request->getParam('id'));
        if ($this->_request->getParam('ajaxaction') == 'active') {
            $row->setStatus('1');
        } else if ($this->_request->getParam('ajaxaction') == 'deactive') {
            $row->setStatus('0');
        }

        $created_by = $this->_em->find('Users', $this->_userid);
        $row->setModifiedBy($created_by);
        $row->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($row);
        $this->_em->flush();
        $this->view->ajaxaction = $this->_request->getParam('ajaxaction');
    }

    /**
     * This method updates Asset Sub Types
     */
    public function updateAction() {
        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $asset = $this->_em->getRepository("CcmAssetTypes")->find($form_values['asset_id']);
            $parent_id = $this->_em->getRepository('CcmAssetTypes')->find($form_values['asset_type']);
            $asset->setParent($parent_id);

            $asset->setAssetTypeName($form_values['asset_sub_type']);
            $created_by = $this->_em->find('Users', $this->_userid);
            $asset->setModifiedBy($created_by);
            $asset->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($asset);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-asset-sub-types");
    }

}
