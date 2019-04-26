<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Model_GeoColors extends Model_Base
{
    /**
     * Get Geo Colors
     * @param type $order
     * @param type $sort
     * @return boolean
     */
    public function getGeoColors($order = null, $sort = null)
    {
     
        $str_sql = "SELECT
                    geo_color.pk_id,
                    geo_color.color_code
                    FROM
                    geo_color ";
        
        if(!empty($this->form_values['color_code_name']))
        {
            $str_sql = $str_sql . "WHERE color_code = '".$this->form_values['color_code_name']."'";
        }                   
        
        $sql = $this->_em_read->getConnection()->prepare($str_sql);
        $sql->execute();
        $row = $sql->fetchAll();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }
    
    /**
     * check Geo Colors
     * @return type
     */
    public function checkGeoColors()
    {
        $form_values = $this->form_values;
        /*
        $str_sql = "SELECT
                    geo_color.pk_id,
                    geo_color.color_code
                    FROM
                    geo_color 
                    WHERE 
                    color_code = '".$form_values."'";
        */
        $str_sql = $this->_em_read->createQueryBuilder()
               ->select("s.pkId")
               ->from('GeoColor', 's')
               ->where("s.colorCode = '" . $form_values . "' ");
        return $str_sql->getQuery()->getResult();
    }
}
