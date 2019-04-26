<?php

/**
 * Cadmin_ManageModelsController
 *
 * 
 *
 * @package    Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * This Controller Manages Capacity
 */
class Cadmin_ManageCapacityController extends App_Controller_Base {

    /**
     * This method searches Capacity
     */
    public function indexAction() {
        $form = new Form_Cadmin_CapacitySearch();
        $form_add = new Form_Cadmin_CapacityAdd();        
        $models = new Model_CcmModels();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $form_values = $this->_request->getPost();
                $form->office_id->setValue($form_values['office']);
                $form->combo1_id->setValue($form_values['combo1']);
                $form->combo2_id->setValue($form_values['combo2']);
                $form->warehouse_id->setValue($form_values['warehouse3']);
                $models->form_values = $this->_request->getPost();
                $result = $models->getAssetsCapacityBySearch();
            }
        } else {

            $form->office_id->setValue(1);
            $form->warehouse_id->setValue(159);
            $models->form_values['warehouse3'] = 159;
            $models->form_values['ccm_asset_type_id'] = 3;
            $result = $models->getAssetsCapacityBySearch();
        }


        //Paginate the contest results
        $paginator = $result;


        $this->view->form = $form;
        $this->view->form_add = $form_add;
        $this->view->paginator = $paginator;


        $base_url = Zend_Registry::get('baseurl');

        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos3.js');
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->setLayout('ajax');
        $ccm_asset_types = new Model_CcmAssetTypes();
        $cold_chain_id = $this->_request->getParam('model_id', '');
        $cold_chain = $this->_em->find('ColdChain', $cold_chain_id);
        $form = new Form_Cadmin_CapacityAdd();
        $form->addHidden();
        $form->id->setValue($cold_chain_id);
        $form->asset->setValue($cold_chain->getAssetId());
        $asset_type = $this->_em->find('CcmAssetTypes', $cold_chain->getCcmAssetType()->getPkId());
        $ccm_asset_types->form_values['parent_id'] = $asset_type->getParent()->getPkId();
        $associated = $ccm_asset_types->getAssetSubTypes();
        if ($associated) {
            foreach ($associated as $row) {
                $sub[$row['pkId']] = $row['assetTypeName'];
            }
        }
        $form->ccm_asset_sub_type->setMultiOptions($sub);
        $form->ccm_asset_sub_type->setValue($cold_chain->getCcmAssetType()->getPkId());
        $form->ccm_asset_type_id_add->setValue($asset_type->getParent()->getPkId());

        if ($cold_chain->getCcmAssetType()->getPkId() == 16) {
            $form->gross->setValue($cold_chain->getCcmModel()->getGrossCapacity4());
            $form->net->setValue($cold_chain->getCcmModel()->getNetCapacity4());
        } else {
            $form->gross->setValue($cold_chain->getCcmModel()->getGrossCapacity20());
            $form->net->setValue($cold_chain->getCcmModel()->getNetCapacity20());
        }

        $this->view->form = $form;
    }

    /**
     * This method updates Capacity
     */
    public function updateAction() {
        $form = new Form_Cadmin_ModelsAdd();
        $form->addHidden();
        if ($this->_request->isPost()) {

            $form_values = $this->_request->getPost();
            $cold_chain = $this->_em->getRepository("ColdChain")->find($form_values['id']);
            $created_by = $this->_em->find('Users', $this->_userid);
            $cold_chain->setAssetId($form_values['asset']);
            $asset_type_id = $this->_em->find('CcmAssetTypes', $form_values['ccm_asset_sub_type']);
            $cold_chain->setCcmAssetType($asset_type_id);
            $cold_chain->setModifiedBy($created_by);
            $cold_chain->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($cold_chain);
            $this->_em->flush();

            $ccm_models = $this->_em->find('CcmModels', $cold_chain->getCcmModel()->getPkId());

            if ($form_values['ccm_asset_sub_type'] == 16) {
                $ccm_models->setGrossCapacity4($form_values['gross']);
                $ccm_models->setNetCapacity4($form_values['net']);

                $ccm_models->setGrossCapacity20(0);
                $ccm_models->setNetCapacity20(0);
            } else {
                $ccm_models->setGrossCapacity20($form_values['gross']);
                $ccm_models->setNetCapacity20($form_values['net']);
                
                $ccm_models->setGrossCapacity4(0);
                $ccm_models->setNetCapacity4(0);
            }

            $ccm_models->setModifiedBy($created_by);
            $ccm_models->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($ccm_models);
            $this->_em->flush();
        }
        $this->_redirect("/cadmin/manage-capacity");
    }

}
