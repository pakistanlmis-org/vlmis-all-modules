<?php

/**
 * Model_CcmAssetTypes
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Item Categories
 */

class Model_ItemCategories extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    const VACCINES = 1;
    const NONVACCINES = 2;
    const DILUENT = 3;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('ItemCategories');
    }

    /**
     * Get All Categories
     * 
     * @return type
     */
    public function getAllCategories() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ic.pkId,ic.itemCategoryName")
                ->from("ItemCategories", "ic");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Item Categories
     * 
     * @return type
     */
    public function getItemCategories() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ic.pkId,ic.itemCategoryName")
                ->from("ItemCategories", "ic");
        if (!empty($this->form_values['item_category_name'])) {
            $str_sql->where("ic.itemCategoryName = '" . $this->form_values['item_category_name'] . "' ");
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Item Category
     * 
     * @return type
     */
    public function checkItemCategory() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("i.pkId")
                ->from('ItemCategories', 'i')
                ->where("i.itemCategoryName = '" . $form_values . "' ");

        return $str_sql->getQuery()->getResult();
    }

}
