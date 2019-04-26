<?php
 
/* This plugin is made to adjust different layouts for different modules */
 
class App_Controller_Plugin_SelectLayout extends Zend_Controller_Plugin_Abstract
{
 
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    $module = $request->getModuleName();
    $layout = Zend_Layout::getMvcInstance();
 
    $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
    $options = $bootstrap->getOptions();
    $layoutPathTemp = $options['resources']['layout']['layoutPath'];
    $layoutPath = $layoutPathTemp.$module;
 
        $layout->setLayoutPath($layoutPath);
        $layout->setLayout('layout');
    }
 
}