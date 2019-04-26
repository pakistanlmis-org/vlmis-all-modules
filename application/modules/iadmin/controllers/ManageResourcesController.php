<?php

/**
 * Iadmin_ManageResourcesController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Resources
 */
class Iadmin_ManageResourcesController extends App_Controller_Base {

    /**
     * This method searches Resources
     */
    public function indexAction() {
        $form = new Form_Iadmin_ResourceSearch();
        $form_add = new Form_Iadmin_Resources();
        $params = array();

        $resources = new Model_Resources();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $resource_name = $form->getValue('resource_name');
                $resource_type = $form->getValue('resource_type');

                if (!empty($resource_name)) {
                    $params['resourceName'] = $resource_name;
                }
                if (!empty($resource_type)) {
                    $params['resourceType'] = $resource_type;
                }
            }
        } else {
            $resource_name = $this->_getParam('resource_name');
            $resource_type = $this->_getParam('resource_type');

            if (!empty($resource_name)) {
                $params['resourceName'] = $resource_name;
                $form->resource_name->setValue($resource_name);
            }
            if (!empty($resource_type)) {
                $params['resourceType'] = $resource_type;
                $form->resource_type->setValue($resource_type);
            }
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "resource_name");
        $resources->form_values = $params;
        $result = $resources->getResources($order, $sort);

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
     * This method add Resources
     */
    public function addAction() {
        $form = new Form_Iadmin_Resources();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $resource = new Resources();
            $resource->setResourceName($form->resource_name->getValue());
            $resource->setDescription($form->description->getValue());
            $resource_type = $this->_em->find('ResourceTypes', $form->resource_type->getValue());
            $resource->setResourceType($resource_type);
            $parent_val = $form->parent_id->getValue();
            $parent_id = !empty($parent_val) ? $parent_val : 0;
            $resource->setParentId($parent_id);
            $resource->setRank($form->rank->getValue());
            $resource->setLevel($form->level->getValue());
            $resource->setPageTitle($form->page_title->getValue());
            $resource->setMetaTitle($form->meta_title->getValue());
            $resource->setMetaDescription($form->meta_desc->getValue());
            $user_id = $this->_em->find('Users', $this->_userid);
            $resource->setCreatedBy($user_id);
            $resource->setModifiedBy($user_id);
            $resource->setCreatedDate(App_Tools_Time::now());
            $resource->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($resource);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-resources");
    }

    /**
     * This method retrieves resources information for Edit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->setLayout("ajax");
        $resource_id = $this->_request->getParam('resource_id', '');

        $resource = $this->_em->find('Resources', $resource_id);
        $form = new Form_Iadmin_Resources();
        $form->addHidden();
        $form->getElement('resource_name')->setAttrib("readonly", "true");

        $form->resource_name->setValue($resource->getResourceName());
        $form->description->setValue($resource->getDescription());
        $form->page_title->setValue($resource->getPageTitle());
        $form->meta_title->setValue($resource->getMetaTitle());
        $form->meta_desc->setValue($resource->getMetaDescription());
        $form->resource_type->setValue($resource->getResourceType()->getPkId());
        $form->parent_id->setValue($resource->getParentId());
        $form->rank->setValue($resource->getRank());
        $form->level->setValue($resource->getLevel());
        $form->id->setValue($resource->getPkId());

        $this->view->form = $form;
    }

    /**
     * This method updates resource
     */
    public function updateAction() {
        $form = new Form_Iadmin_Resources();
        $form->addHidden();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $resource_id = $form->id->getValue();
            $resource = $this->_em->getRepository("Resources")->find($resource_id);
            $resource->setResourceName($form->resource_name->getValue());
            $resource->setDescription($form->description->getValue());
            $resource->setPageTitle($form->page_title->getValue());
            $resource->setMetaTitle($form->meta_title->getValue());
            $resource->setMetaDescription($form->meta_desc->getValue());
            $resource_type = $this->_em->find('ResourceTypes', $form->resource_type->getValue());
            $resource->setResourceType($resource_type);
            $parent_val = $form->parent_id->getValue();
            $parent_id = !empty($parent_val) ? $parent_val : 0;
            $resource->setParentId($parent_id);
            $resource->setRank($form->rank->getValue());
            $resource->setLevel($form->level->getValue());
            $created_by = $this->_em->find('Users', $this->_userid);
            $resource->setModifiedBy($created_by);
            $resource->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($resource);
            $this->_em->flush();
        }
        $this->_redirect("/iadmin/manage-resources");
    }

    /**
     * This method deletes resource
     */
    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $resource_id = $this->_request->getParam("resource_id");
        // Delete click paths.
        $click_path_resources = $this->_em->getRepository("UserClickPaths")->findBy(array("resource" => $resource_id));
        foreach ($click_path_resources as $click_path) {
            $this->_em->remove($click_path);
        }

        // Delete role resources.
        $role_resources = $this->_em->getRepository("RoleResources")->findBy(array("resource" => $resource_id));
        foreach ($role_resources as $role_resource) {
            $this->_em->remove($role_resource);
        }

        $resource = $this->_em->getRepository("Resources")->find($resource_id);
        $this->_em->remove($resource);
        return $this->_em->flush();
    }

    /**
     * This method checks Resource
     */
    public function checkResourceAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $resource_name = $this->_request->getParam('resource_name');
        $resources = $this->_em->getRepository("Resources")->findBy(array("resourceName" => $resource_name));

        if (count($resources) > 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

}
