<?php

class App_Controller_Functions {

    public static function dateToDbFormat($tran_date) {
        if (!empty($tran_date) && $tran_date != 0) {
            $dd = str_replace("/", "-", $tran_date);
            $d = new DateTime($dd);
            return $d->format("Y-m-d");
        } else {
            return false;
        }
    }

    public static function dateTimeToDbFormat($date) {
        if (!empty($date) && $date != 0) {
            $dd = str_replace("/", "-", $date);
            $d = new DateTime($dd);
            return $d->format("Y-m-d H:i:s");
        } else {
            return false;
        }
    }

    public static function pr($data, $exit = true) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";

        if ($exit == true) {
            exit;
        }
    }

    public static function getFiscalYear() {
        $current_year = date("Y");
        $current_month = date("m");
        if ($current_month < 7) {
            $from_date = ($current_year - 1) . "-06-30";
            $to_date = $current_year . "-07-30";
        } else {
            $from_date = $current_year . "-06-30";
            $to_date = ($current_year + 1) . "-07-30";
        }

        return array(
            'from_date' => $from_date,
            'to_date' => $to_date
        );
    }

    public static function dateToUserFormat($datetime) {
        if (!empty($datetime)) {
//            list($date, $time) = explode(" ", $datetime); // Undefined offset 2
//            list($yy, $mm, $dd) = explode("-", $date);
            $date = explode(" ", $datetime);
            list($yy, $mm, $dd) = explode("-", $date[0]);
            return $dd . "/" . $mm . "/" . $yy;
        }
    }

    public static function yearFromDate($date) {
        if (!empty($date)) {
            list($dd, $mm, $yy) = explode("/", $date);
            return $yy;
        }
    }

    public static function monthFromDate($date) {
        if (!empty($date)) {
            list($dd, $mm, $yy) = explode("/", $date);
            return $mm;
        }
    }

    public static function dateFormat($date, $string, $format) {
        $d = new DateTime($date);
        $d->modify($string);
        return $d->format($format);
    }
    
    public static function dateToMonthYear($date,$format) {
        $d = new DateTime($date);
        return $d->format($format);
    }

    public static function writeXML($xml_file, $xml_data) {
        $xmlfile_path = "xml" . "/" . $xml_file;
        $handle = fopen($xmlfile_path, 'w');
        fwrite($handle, $xml_data);
        fclose($handle);
    }

    public static function generateCcemUniqueAssetId($asset_type) {
        $_em = Zend_Registry::get('doctrine');
        $generate_asset_id = "";
        $asset_id = "";
        if ($asset_type > 7) {
            $str_sql = $_em->createQueryBuilder()
                    ->select("cc.parentId")
                    ->from('CcmAssetTypes', 'cc')
                    ->where('cc.pkId = ' . $asset_type);
            $row = $str_sql->getQuery()->getResult();
            if ($row && count($row) > 0) {
                $asset_id = $row[0]['parentId'];
            }
        } else {
            $asset_id = $asset_type;
        }
        if (!empty($asset_id)):
            $str_sql = "SELECT MAX(CAST(auto_asset_id AS SIGNED)) as generate_asset_id, pk_id
            FROM cold_chain
            WHERE auto_asset_id LIKE '" . $asset_id . "%' ";
            $row = $_em->getConnection()->prepare($str_sql);
            $row->execute();
            $rs = $row->fetchAll();
            if (empty($rs[0]['generate_asset_id']) || $rs[0]['generate_asset_id'] == 0) {
                $generate_asset_id = $asset_id . str_pad(1, 8, '0', STR_PAD_LEFT);
            } else {
                $generate_asset_id = $rs[0]['generate_asset_id'] + 1;
            }
        endif;
        return $generate_asset_id;
    }

    // Encrypt the given string
    public static function encrypt($string) {
        for ($i = 0; $i < 2; $i++) {
            $string = strrev(base64_encode($string)); //apply base64 first and then reverse the string
        }
        return $string;
    }

    // Decrypt the given string
    public static function decrypt($string) {
        for ($i = 0; $i < 2; $i++) {
            $string = base64_decode(strrev($string)); //apply base64 first and then reverse the string}
        }
        return $string;
    }

    public static function getPageNarrative($name) {
        $_em = Zend_Registry::get('doctrine');
        $str_sql = $_em->createQueryBuilder()
                ->select("hp.description")
                ->from('HelpMessages', 'hp')
                ->join('hp.resource', 'r')
                ->where("r.resourceName = '" . $name . "'")
                ->andWhere('hp.status = 1');
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            $html_data = '<div class="alert alert-success text-message" id="alert-message" style="display: block;" >';
            $html_data .= $row[0]['description'];
            $html_data .= '</div>';
            return $html_data;
        } else {
            return FALSE;
        }
    }

    public static function clearString($string) {
        $string = str_replace("\n", "", $string);
        return $string;
    }

    public static function getPageTitleAndMeta($name) {
        $_em = Zend_Registry::get('doctrine');
        $str_sql = $_em->createQueryBuilder()
                ->select("r.pageTitle, r.metaTitle, r.metaDescription")
                ->from('Resources', 'r')
                ->where("r.resourceName = '" . $name . "'");
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0];
        } else {
            return FALSE;
        }
    }

}
