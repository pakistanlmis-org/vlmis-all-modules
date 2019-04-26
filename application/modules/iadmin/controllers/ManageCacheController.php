<?php

/**
 * Iadmin_ManageCacheController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller Manages Cache
 */
class Iadmin_ManageCacheController extends App_Controller_Base {

    /**
     * Iadmin_ManageCacheController index
     */
    public function indexAction() {
        if ($this->_request->isPost() && $this->_request->getPost()) {
            $deleteList = array('zend_cache---acl', 'zend_cache---internal-metadatas---acl');
            // path on Test Server
            // path for live server
            chdir('../vlmis/');
            $arrFiles = scandir('cache', 0);
            $files = '';
            foreach ($arrFiles as $file) {
                if (in_array($file, $deleteList)) {
                    $files .= $file . '<br />';
                    unlink("cache/$file");
                }
            }
        }
    }

}
