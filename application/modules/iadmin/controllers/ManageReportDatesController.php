<?php

/**
 * Iadmin_ManageReportDatesController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  This Controller Manages Report Dates
*/

class Iadmin_ManageReportDatesController extends App_Controller_Base {

    /**
     * Add Report Dates
     * Used to add reports dates.
     */
    public function addReportDatesAction() {

        $form = new Form_Iadmin_UpdateCluster;

        // Check is form posted.
        if ($this->_request->isPost()) {

            $form_values = $this->_request->getPost();

            // Check is search.
            if ($form_values['search'] == 'search') {
                // Init warehouse instance.
                $warehouses = new Model_Warehouses();
                
                // Set forms values.
                $warehouses->form_values = $form_values;
                $form->province->setValue($form_values['province']);
                $form->province_hidden->setValue($form_values['province']);
                $form->district_hidden->setValue($form_values['district']);
                $form->user_hidden->setValue($form_values['user']);
                $warehouse_data = $warehouses->getAllWarehousesReportDate();

                // Set view data.
                $this->view->data = $warehouse_data;
            } else {
                $form_values_submit = $this->_request->getPost();
                if (($form_values_submit['check']) != "") {
                    // Loop through all elements.
                    foreach ($form_values_submit['check'] as $warehouse_id) {

                        $warehouses_db = $this->_em->getRepository('Warehouses')->find($warehouse_id);
                        if (($form_values_submit['from_edit']) != "") {
                            $warehouses_db->setFromEdit(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values_submit['from_edit'])));
                        }
                        if (($form_values_submit['starting_on']) != "") {
                            $warehouses_db->setStartingOn(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values_submit['starting_on'])));
                        }
                        if (($form_values_submit['working_uptil']) != "") {
                            $warehouses_db->setWorkingUptill(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values_submit['working_uptil'])));
                        }
                    }
                    // get loged in user id.
                    $created_by = $this->_em->find('Users', $this->_user_id);
                    $warehouses_db->setCreatedBy($created_by);
                    $warehouses_db->setCreatedDate(App_Tools_Time::now());
                    $warehouses_db->setModifiedBy($created_by);
                    $warehouses_db->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($warehouses_db);
                    $this->_em->flush();
                }
            }
        }
        // Set form.
        $this->view->form = $form;
    }

}
