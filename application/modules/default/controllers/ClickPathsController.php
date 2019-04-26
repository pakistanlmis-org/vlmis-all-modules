<?php

/**
 * ClickPathsController
 *
 * 
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Click Paths
 */
class ClickPathsController extends App_Controller_Base {

    public function saveUserPathAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        // Save path code here
    }

    public function userPathAction() {

        $user_click_paths = new Model_UserClickPaths();
        $locations = new Model_Locations();

        $daily_users_count = $user_click_paths->getDailyActiveUsers();
        $weekly_users_count = $user_click_paths->getWeeklyActiveUsers();
        $monthly_users_count = $user_click_paths->getMonthlyActiveUsers();
        if ($this->_request->isPost()) {
            $from_date = $this->_request->getPost('date_from');
            $from_to = $this->_request->getPost('date_to');
            $province_id = $this->_request->getPost('province_id');
            $this->view->province_sel = $province_id;
        } else {
            $from_date = date('d/m/Y', strtotime("-1 day", strtotime(date("Y-m-d"))));
            //  $from_date = date_format($from_date1, 'd/m/Y');
            //   echo $from_date;
            //     $from_date = '01/01/' . date("Y");
            $from_to = date("d/m/Y");
            $province_id = 1;
            $this->view->province_sel = $province_id;
        }
        $user_click_paths->form_values = array(
            'province_id' => $province_id,
            'date_from' => $from_date,
            'date_to' => $from_to
        );

        $user_page_count = $user_click_paths->getPageCountForActiveRoles();



        $user_click_paths->form_values = array(
            'province_id' => $province_id,
            'role_id' => 1,
            'date_from' => $from_date,
            'date_to' => $from_to
        );
        $pages_for_role = $user_click_paths->getPagesForRole();

        $this->view->daily_users_count = $daily_users_count;
        $this->view->weekly_users_count = $weekly_users_count;
        $this->view->monthly_users_count = $monthly_users_count;

        $this->view->user_page_count = $user_page_count;
        $this->view->pages_for_role = $pages_for_role;

        $this->view->date_from = $from_date;
        $this->view->date_to = $from_to;
        $this->view->provinces = $locations->getAllProvinces();
        $this->view->role_name = $this->_identity->getRoleName(1);
        // Generating XML for chart 
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption=' Page count per role' subcaption='' exportFileName=' " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' numberSuffix=''  formatNumberScale='0' >";
        foreach ($user_page_count as $row) {
            $param = $row['role_id'];
            $param2 = $row['description'];
            $xmlstore .= "<set label='" . $row['description'] . "'  value='" . $row['page_count'] . "'  link=\"JavaScript:showData('$param|$param2');\" />";
            //$xmlstore .= "<set label='Received' value='$received' link=\"JavaScript:showData('$param|1');\" />";
        }
        $xmlstore .= "</chart>";
        $this->view->xmlstore = $xmlstore;

        // Generating XML for chart 
        $xmlstore2 = "<chart exportEnabled='1' exportAction='Download' caption=' Pages for Admin' subcaption='' exportFileName=' " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' numberSuffix=''  formatNumberScale='0' >";
        foreach ($pages_for_role as $row2) {
            $page_description = $row2['page_description'];
            $page_description = str_replace("&nbsp;", "", $page_description);
            $page_description = str_replace("&", "&#38;", $page_description);
            $page_description = str_replace("--", "", $page_description);
            $xmlstore2 .= "<set label='$page_description'  value='" . $row2['page_count'] . "' />";
        }
        $xmlstore2 .= "</chart>";
        $this->view->xmlstore2 = $xmlstore2;
    }

    public function ajaxUserPathAction() {
        $this->_helper->layout->disableLayout();

        $param_arr = explode('|', $this->_request->getParam('param'));
        $role_id = $param_arr[0];
        $role_description = $param_arr[1];
        $from_date = $this->_request->getParam('from');
        $from_to = $this->_request->getParam('to');
        $province_id = $this->_request->getParam('province_id');

        $user_click_paths = new Model_UserClickPaths();
        $user_click_paths->form_values = array(
            'province_id' => $province_id,
            'role_id' => $role_id,
            'date_from' => $from_date,
            'date_to' => $from_to
        );
        $pages_for_role = $user_click_paths->getPagesForRole();

        // Generating XML for chart 
        $xmlstore2 = "<chart exportEnabled='1' exportAction='Download' caption=' Pages for $role_description ' subcaption='' exportFileName=' " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' numberSuffix=''  formatNumberScale='0' >";
        foreach ($pages_for_role as $row2) {
            $page_description = $row2['page_description'];
            $page_description = str_replace("&nbsp;", "", $page_description);
            $page_description = str_replace("&", "&#38;", $page_description);
            $page_description = str_replace("--", "", $page_description);
            $xmlstore2 .= "<set label='$page_description'  value='" . $row2['page_count'] . "'  link=\"JavaScript:showPopUp('$param|$param2');\" />";
        }
        $xmlstore2 .= "</chart>";
        $this->view->xmlstore2 = $xmlstore2;
        $this->view->role_name = $this->_identity->getRoleName($role_id);
    }

    public function ajaxUsersAction() {
        $this->_helper->layout->disableLayout();

        $param_arr = explode('|', $this->_request->getParam('param'));
        $role_id = $param_arr[0];
        $role_description = $param_arr[1];
        $from_date = $this->_request->getParam('from');
        $from_to = $this->_request->getParam('to');
        $province_id = $this->_request->getParam('province_id');

        $user_click_paths = new Model_UserClickPaths();
        $user_click_paths->form_values = array(
            'province_id' => $province_id,
            'role_id' => $role_id,
            'date_from' => $from_date,
            'date_to' => $from_to
        );
        $result = $user_click_paths->getPagesForRole();


        $this->view->data = $result;
    }
    
     /**
     * stock Receive List
     */
    public function usersListAction() {
        $this->_helper->layout->setLayout('print');
        $this->view->headTitle("Stock Receive List");
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['searchby'] = $this->_request->searchby;
        $stock_master->form_values['number'] = $this->_request->number;
        $stock_master->form_values['warehouses'] = $this->_request->warehouses;
        $stock_master->form_values['product'] = $this->_request->product;
        $stock_master->form_values['date_from'] = $this->_request->date_from;
        $stock_master->form_values['date_to'] = $this->_request->date_to;
        $dataset = $stock_master->getAllItemStock();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->print_title = "Stock Receive List";
        $this->view->result = $dataset;
    }

}
