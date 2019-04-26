<?php

class App_View_Helper_Navigation_Menu extends Zend_View_Helper_Navigation_HelperAbstract {

    private $_nav = null;

    public function menu(Zend_Navigation_Container $container = null) {
        if (null !== $container) {
            $this->setContainer($container);
        }

        return $this;
    }

    public function render(Zend_Navigation_Container $container = null) {
        $front = Zend_Controller_Front::getInstance();
        $module = $front->getRequest()->getModuleName();
        $controller = $front->getRequest()->getControllerName();
        $action = $front->getRequest()->getActionName();
        $translate = Zend_Registry::get('Zend_Translate');
        $auth = App_Auth::getInstance();
        $province_id = $auth->getProvinceId();

        $resource = ($action == '') ? trim($controller) . '/index' : trim($controller) . '/' . trim($action);
        $resource = ($module == 'default') ? $resource : $module . "/" . $resource;

        if ($resource == 'dashboard/index') {
            $parts = parse_url($_SERVER["REQUEST_URI"]);
            $resource = $resource . "?office=" . $front->getRequest()->getParam('office', '');
        }

        $em = Zend_Registry::get("doctrine");
        $baseurl = Zend_Registry::get("baseurl");

        $auth = new App_Auth();
        $role_id = $auth->getRoleId();
        $username = $auth->getUserName();

        // permissions
        $str_sql = $em->createQueryBuilder()
                ->select("rr")
                ->from("RoleResources", "rr")
                ->join("rr.role", "r")
                ->join("rr.resource", "res");
        if ($role_id == Model_Roles::SUPPLIER) {
            $str_sql->where("r.pkId IN($role_id,6)");
        } else {
            $str_sql->where("r.pkId = $role_id");
        }
        $str_sql->andWhere("res.parentId = 0")
                ->andWhere("res.resourceType = 1")
                ->orderBy("res.rank", "ASC");
        $navigationList = $str_sql->getQuery()->getResult();

        $container = '<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
                      <li class="sidebar-toggler-wrapper charcol-clr">
                        <div class="sidebar-toggler hidden-phone"></div>
                        <div class="dashboard-header">
                           <span class=" welcm">
                           ' . $translate->translate("Welcome") . '<br>
                           <span class="title">' . ucfirst($username) . '</span>
                           <!-- <img src="' . $baseurl . '/common/assets/img/dashboard.png" alt=""/> -->
                           </span>
                        </div>
                      </li>';

        if ($navigationList !== false) {
            foreach ($navigationList as $key => $value) {

                $parent_id = $value->getResource()->getPkId();

                $str_sql2 = $em->createQueryBuilder()
                        ->select("rr")
                        ->from("RoleResources", "rr")
                        ->join("rr.role", "r")
                        ->join("rr.resource", "res");
                if ($role_id == Model_Roles::SUPPLIER) {
                    $str_sql2->where("r.pkId IN($role_id,6)");
                } else {
                    $str_sql2->where("r.pkId = $role_id");
                }
                $str_sql2->andWhere("res.parentId = $parent_id")
                        ->andWhere("res.resourceType = 1")
                        ->orderBy("res.rank", "ASC");
                $navigationList2 = $str_sql2->getQuery()->getResult();
                $child_pages = array();
                $child_act_pages = array();
                if (count($navigationList2) > 0) {
                    foreach ($navigationList2 as $key2 => $value2) {
                        $child_pages[] = $value2->getResource()->getResourceName();

                        $parent_parent_id = $value2->getResource()->getPkId();
                        $str_sql3 = $em->createQueryBuilder()
                                ->select("rr")
                                ->from("RoleResources", "rr")
                                ->join("rr.role", "r")
                                ->join("rr.resource", "res");
                        if ($role_id == Model_Roles::SUPPLIER) {
                            $str_sql3->where("r.pkId IN($role_id,6)");
                        } else {
                            $str_sql3->where("r.pkId = $role_id");
                        }
                        $str_sql3->andWhere("res.parentId = $parent_parent_id")
                                ->andWhere("res.resourceType = 1")
                                ->orderBy("res.rank", "ASC");

                        $navigationList3 = $str_sql3->getQuery()->getResult();
                        if (count($navigationList3) > 0) {
                            foreach ($navigationList3 as $key3 => $value3) {
                                $child_act_pages[] = $value3->getResource()->getResourceName();
                                $child_pages[] = $value3->getResource()->getResourceName();

                                $parent_parent_id = $value3->getResource()->getPkId();
                                $str_sql4 = $em->createQueryBuilder()
                                        ->select("rr")
                                        ->from("RoleResources", "rr")
                                        ->join("rr.role", "r")
                                        ->join("rr.resource", "res");
                                if ($role_id == Model_Roles::SUPPLIER) {
                                    $str_sql4->where("r.pkId IN($role_id,6)");
                                } else {
                                    $str_sql4->where("r.pkId = $role_id");
                                }
                                $str_sql4->andWhere("res.parentId = $parent_parent_id")
                                        ->andWhere("res.resourceType = 1")
                                        ->orderBy("res.rank", "ASC");

                                $navigationList4 = $str_sql4->getQuery()->getResult();
                                if (count($navigationList4) > 0) {
                                    foreach ($navigationList4 as $key4 => $value4) {
                                        $child_act_pages[] = $value4->getResource()->getResourceName();
                                    }
                                }
                            }
                        }
                    }
                }



                if (count($child_pages) > 0) {

                    $parent_status = (in_array($resource, $child_pages) || in_array($resource, $child_act_pages)) ? "active" : '';
                    $parent_in = (in_array($resource, $child_pages) || in_array($resource, $child_act_pages)) ? "in" : '';

                    $container .= '<li class="' . $parent_status . ' ">
                            <a data-toggle="collapse" href="#submenu-' . $parent_id . '">
                                <i class="fa-size fa ' . $value->getResource()->getIconClass() . '"></i>
                                <span class="title">
                                    ' . $translate->translate($value->getResource()->getDescription()) . '
                                </span>
                                <span class="arrow "> </span>
                            </a>';

                    if (count($navigationList2) > 0) {

                        $container .= '<ul class="sub-menu ' . $parent_in . '" id="submenu-' . $parent_id . '">';

                        if ($navigationList2 !== false) {
                            foreach ($navigationList2 as $key2 => $value2) {
                                if ($value2->getPermission() == Model_RoleResources::ALLOW) {

                                    $status = ($resource == $value2->getResource()->getResourceName()) ? "active" : '';
                                    $child_id = $value2->getResource()->getPkId();

                                    $str_sql3 = $em->createQueryBuilder()
                                            ->select("rr")
                                            ->from("RoleResources", "rr")
                                            ->join("rr.role", "r")
                                            ->join("rr.resource", "res");
                                    if ($role_id == Model_Roles::SUPPLIER) {
                                        $str_sql3->where("r.pkId IN($role_id,6)");
                                    } else {
                                        $str_sql3->where("r.pkId = $role_id");
                                    }
                                    $str_sql3->andWhere("res.parentId = $child_id")
                                            ->andWhere("res.resourceType = 1")
                                            ->orderBy("res.rank", "ASC");
                                    $navigationList3 = $str_sql3->getQuery()->getResult();
                                    $child_child_pages = array();
                                    if (count($navigationList3) > 0) {
                                        foreach ($navigationList3 as $key3 => $value3) {
                                            $child_child_pages[] = $value3->getResource()->getResourceName();
                                            $child_pages[] = $value3->getResource()->getResourceName();

                                            $parent_parent_id = $value3->getResource()->getPkId();
                                            $str_sql4 = $em->createQueryBuilder()
                                                    ->select("rr")
                                                    ->from("RoleResources", "rr")
                                                    ->join("rr.role", "r")
                                                    ->join("rr.resource", "res");
                                            if ($role_id == Model_Roles::SUPPLIER) {
                                                $str_sql4->where("r.pkId IN($role_id,6)");
                                            } else {
                                                $str_sql4->where("r.pkId = $role_id");
                                            }
                                            $str_sql4->andWhere("res.parentId = $parent_parent_id")
                                                    ->andWhere("res.resourceType = 1")
                                                    ->orderBy("res.rank", "ASC");

                                            $navigationList4 = $str_sql4->getQuery()->getResult();
                                            if (count($navigationList4) > 0) {
                                                foreach ($navigationList4 as $key4 => $value4) {
                                                    $child_child_pages[] = $value4->getResource()->getResourceName();
                                                }
                                            }
                                        }
                                    }

                                    $child_status = (in_array($resource, $child_child_pages)) ? "open" : '';
                                    $child_in = (in_array($resource, $child_child_pages)) ? "block" : 'none';

                                    /* if ($value2->getResource()->getResourceName() == 'stock/issue' && $province_id == 2 && $role_id == 7) {
                                      continue;
                                      }
                                      if ($value2->getResource()->getResourceName() == 'stock/stock-issue' && $province_id != 2 && $role_id == 7) {
                                      continue;
                                      } */

                                    if (count($child_child_pages) > 0) {
                                        $container .= '<li class=" ' . $child_status .'"><a  class="" href="#subsubmenu-' . $child_id . '">
                                            <span class="arrow ">
                                                ' . $translate->translate($value2->getResource()->getDescription()) . '
                                            </span>
                                            <span class="selected">
						                    </span>
                                        </a>
                                        <ul class="sub-menu" id="subsubmenu-' . $child_id . '" style="display:' . $child_in . ' ;">';

                                        foreach ($navigationList3 as $key3 => $value3) {
                                            if ($value3->getPermission() == Model_RoleResources::ALLOW) {

                                                $status_child = ($resource == $value3->getResource()->getResourceName()) ? "active" : '';


                                                $container .= '<li class="' . $status_child . '">
                                                <a href="' . $baseurl . '/' . $value3->getResource()->getResourceName() . '">
                                                    <span>
                                                        ' . $translate->translate($value3->getResource()->getDescription()) . '
                                                    </span>
                                                    <span class="selected">
						                        </span>
                                                </a>';
                                                // 3 sub menu
                                                $status = ($resource == $value3->getResource()->getResourceName()) ? "active" : '';
                                                $child_id = $value3->getResource()->getPkId();

                                                $str_sql4 = $em->createQueryBuilder()
                                                        ->select("rr")
                                                        ->from("RoleResources", "rr")
                                                        ->join("rr.role", "r")
                                                        ->join("rr.resource", "res");
                                                if ($role_id == Model_Roles::SUPPLIER) {
                                                    $str_sql4->where("r.pkId IN($role_id,6)");
                                                } else {
                                                    $str_sql4->where("r.pkId = $role_id");
                                                }
                                                $str_sql4->andWhere("res.parentId = $child_id")
                                                        ->andWhere("res.resourceType = 1")
                                                        ->orderBy("res.rank", "ASC");
                                                $navigationList4 = $str_sql4->getQuery()->getResult();
                                                $child_child_pages = array();
                                                if (count($navigationList4) > 0) {
                                                    foreach ($navigationList4 as $key4 => $value4) {
                                                        $child_child_pages[] = $value4->getResource()->getResourceName();
                                                    }
                                                }

                                                if (count($child_child_pages) > 0) {

                                                    $container .= '<ul class="sub-menu" id="subsubmenu1" style="display:' . $child_in . ' ;">';

                                                    foreach ($navigationList4 as $key4 => $value4) {
                                                        if ($value4->getPermission() == Model_RoleResources::ALLOW) {
                                                            $status_sub_child = ($resource == $value4->getResource()->getResourceName()) ? "active" : '';
                                                            $status = ($resource == $value4->getResource()->getResourceName()) ? "active" : '';




                                                            $container .= '<li class="' . $status_sub_child . '">
                                                    <a href="' . $baseurl . '/' . $value4->getResource()->getResourceName() . '">
                                                    <span>
                                                        ' . $translate->translate($value4->getResource()->getDescription()) . '
                                                    </span>
                                                    <span class="selected">
						       </span>
                                                </a>
                                            </li>';
                                                        }
                                                    }
                                                    $container .= '</ul>';
                                                }
                                                // end 3 sub menu
                                                $container .= '</li>';
                                            }
                                        }

                                        $container .= '</ul>';
                                    } else {
                                        $container .= '<li class="' . $status . '">
                                            <a href="' . $baseurl . '/' . $value2->getResource()->getResourceName() . '">
                                                <span>
                                                    ' . $translate->translate($value2->getResource()->getDescription()) . '
                                                </span>
                                                <span class="selected">
                                                                        </span>
                                            </a></li>';
                                    }
                                }
                            }
                        }
                        $container .= '</ul>';
                    }

                    $container .= '</li>';
                } else {
//                    if ($value->getResource()->getResourceName() == 'stock/monthly-consumption' && $province_id == 2 && $role_id == 55 && $role_id == 8) { //|| ($province_id == 1 && $role_id == 7 && $value->getResource()->getResourceName() == 'stock/monthly-consumption')) {
//                     
//                        continue;
//                    }
//                    if (($value->getResource()->getResourceName() == 'stock/monthly-consumption2' && ($province_id != 2 && $province_id != 6 && $province_id != 5 && $province_id != 4 && $province_id != 7 && $role_id != 55 && $role_id != 8))   || ($province_id == 1 && $role_id == 7 && $value->getResource()->getResourceName() == 'stock/monthly-consumption2')   ) {
// 
//                        continue;
//                    }
                    $status = ($value->getResource()->getResourceName() == $resource) ? "start green-color " : "";
                    $container .= '<li class="' . $status . '">'
                            . '<a href="' . $baseurl . '/' . $value->getResource()->getResourceName() . '">'
                            . '<i class="fa fa-home"></i>'
                            . '<span class="title">' . $translate->translate($value->getResource()->getDescription()) . '</span>'
                            . '</a>'
                            . '</li>';
                }
            }
        }

        $container .= '</ul>';

        return $container;
    }

}
