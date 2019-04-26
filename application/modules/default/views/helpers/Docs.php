<?php

/**
 * Zend_View_Helper_Docs
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */



/**
 *  Zend View Helper Docs
 */

class Zend_View_Helper_Docs extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * docs
     * @return \Zend_View_Helper_Docs
     */
    public function docs() {
        return $this;
    }

    /**
     * Get Doc Sub Categories
     * @param type $category_id
     * @return type
     */
    public function getDocSubCategories($category_id) {

        $querypro = "SELECT
                        document_categories.category_title,
                        document_categories.pk_id
                    FROM
                        document_categories
                        INNER JOIN document_categories AS main ON main.pk_id = document_categories.parent_id
                    WHERE
                        main.pk_id = $category_id";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }
    
    /**
     * Get Document Detail
     * @param type $category_id
     * @return type
     */
    public function getDocumentDetail($category_id) {

        $querypro = "SELECT
                        documents.pk_id,
                        documents.doc_title,
                        documents.doc_path,
                        documents.doc_category_id
                    FROM
                        documents
                    WHERE
                        documents.doc_category_id = $category_id ORDER BY documents.rank ASC";


        $this->_em_read = Zend_Registry::get('doctrine_read');
        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

}

?>