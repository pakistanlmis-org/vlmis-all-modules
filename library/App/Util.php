<?php

/**
 * It is a class to check role
 * @author ajmal.h
 *
 */

function show($action = null) {

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $action = $action === null ? $request->getActionName() : $action;
    $module = $request->getModuleName();
    $controller = $request->getControllerName();

    if (!Zend_Registry::isRegistered('acl'))
        throw new Exception('Show function can only be called inside view after preDispatch');

    $acl = Zend_Registry::get('acl');
    $resource = $module . '.' . $controller;
    return $acl->isAllowed(Zend_Auth::getInstance()->getIdentity(), $resource, $action);
}