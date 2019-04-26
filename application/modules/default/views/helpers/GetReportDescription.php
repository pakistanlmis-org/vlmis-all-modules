<?php

/**
 * Zend_View_Helper_GetReportDescription
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Get Report Description
 */

class Zend_View_Helper_GetReportDescription extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Report Description
     * @param type $id
     * @param type $check
     * @return string
     */
    function getReportDescription($id, $check = false) {
        if ($id == 'TEHSILREPORT' || $id == 'UCREPORT') {
            $r_val = '0';
        } else {
            $r_val = '1';
        }
        if ($check) {
            return $r_val;
        }

        if ($id == 'SNASUM' || $id == 'SPROVINCEREPORT' || $id == 'SDISTRICTREPORT') {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("Distinct m.longTerm,m.colorCode")
                    ->from("MosScale", "m");

            $rows = $str_sql->getQuery()->getResult();


            echo "<table>";
            echo "<tr><td style='font-size:10px;' width='86%'>Unknown</td>";
            echo "<td><div style='display:inline-block;width:10px; height:10px; background-color:#000;margin-left:5px;'></div></td></tr>";
            $i = 1;
            foreach ($rows as $row) {
                echo "<tr><td style='font-size:10px;' width='86%'>" . $row['longTerm'] . " </td>";
                echo "<td><div style='display:inline-block;width:10px; height:10px; background-color:" . $row['colorCode'] . ";margin-left:5px;'></div></td></tr>
                        ";
                $i++;
            }
            echo "</table>";
        }
    }

}
