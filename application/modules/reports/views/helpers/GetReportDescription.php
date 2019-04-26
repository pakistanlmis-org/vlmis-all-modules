<?php
/**
 * Zend_View_Helper_GetReportDescription
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Get Report Description
 */
class Zend_View_Helper_GetReportDescription extends Zend_View_Helper_Abstract {

    protected $_em_read;

    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Report Description
     * Used to get report description
     * @param type $geo_level_id
     * @param int $item_id
     * @param type $id
     * @param type $check
     * @return string
     */
    function getReportDescription($geo_level_id, $item_id, $id, $check = false) {
        // Check id.
        if ($id == '') {
            $r_val = '0';
        } else {
            $r_val = '1';
        }
        // Check result.
        if ($check) {
            return $r_val;
        }
        if ($id == 'SNASUM') {

            $item_id = 26;
        }

        // Init array.
        $reportTypes = array('SNASUM', 'SPROVINCEREPORT', 'SDISTRICTREPORT', 'TEHSILREPORT', 'UCREPORT');
        if (in_array($id, $reportTypes)) {

            $querypro = "SELECT Distinct
                        m.long_term,m.color_code,m.scale_start,m.scale_end
                        FROM
                        mos_scale m

                        WHERE
                        m.item_id = '" . $item_id . "'
                        AND m.geo_level_id= '" . $geo_level_id . "'
                        AND m.stakeholder_id=1
                        Order BY m.pk_id ASC";


            // Get doctrine instance.
            $row = $this->_em_read->getConnection()->prepare($querypro);
            // Execute and get result.
            $row->execute();
            $rows = $row->fetchAll();
            ?>
            <ul class="nav navbar-nav report-tab">
                <li>
                    <span>MOS Legends:</span>
                </li>
                <li>
                    Unknown
                    <div style='display:inline-block;width:10px; height:11px; background-color:#000000;margin-left:5px;'></div>
                </li>
                <?php
                $i = 1;
                foreach ($rows as $row) {
                    $scale_end = $row['scale_end'] == 99999 ? "infinity" : $row['scale_end'];
                    echo "<li>" . $row['long_term'] . " (" . $row['scale_start'] . "-" . $scale_end . " months)";
                    echo "<div style='display:inline-block;width:10px; height:11px; background-color:" . $row['color_code'] . ";margin-left:5px;'></div></li>
                    ";
                    $i++;
                }
                echo "</ul>";
            }
        }

    }
    