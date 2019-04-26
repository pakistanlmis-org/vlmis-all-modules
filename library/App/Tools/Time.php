<?php

class App_Tools_Time {

    /**
     * Return current datetime
     *
     * @return string
     */
    public static function now($type = 'datetime') {
        switch ($type) {
            case 'time':
                return new \DateTime(date('H:i:s'));
                break;
            case 'datetime':
                return new \DateTime(date('Y-m-d H:i:s'));
                break;
            case 'date':
                return new \DateTime(date('Y-m-d'));
                break;
            default:
                return new \DateTime(date('Y-m-d H:i:s'));
        }
    }

    public static function date($date, $type = 'datetime') {
        $d = new DateTime($date);
        switch ($type) {
            case 'time':
                return $d->format('H:i:s');
                break;
            case 'datetime':
                return $d->format('Y-m-d H:i:s');
                break;
            case 'date':
                return $d->format('Y-m-d');
                break;
            default:
                return $d->format('Y-m-d H:i:s');
        }
    }

    /**
     * Compare $date with current time.
     * Returns TRUE if $date is earlier then now. Otherwhise returns FALSE.
     * @param Zend_Date $date
     */
    public static function isEarlier($date) {
        $now = new Zend_Date(self::now(), Zend_Date::ISO_8601);

        return ($now->compare($date) == 1) ? TRUE : FALSE;
    }

    /**
     * Compare $date with current time.
     * Returns TRUE if $date is later then now. Otherwhise returns FALSE.
     * @param Zend_Date $date
     */
    public static function isLater($date) {
        $now = new Zend_Date(self::now(), Zend_Date::ISO_8601);

        return ($now->compare($date) == 1) ? FALSE : TRUE;
    }

    /**
     * Compare $date with current $otherdate time.
     * Returns TRUE if $date is later then now. Otherwhise returns FALSE.
     * @param Zend_Date $date
     */
    public static function dateDifference($from_date, $to_date) {
        $date1 = date_create($from_date);
        $date2 = date_create($to_date);
        $diff = date_diff($date1, $date2);
        return $diff->format("%R%a");
    }

    public static function addMonths($date, $months){
        $d = new DateTime($date);
        $d->modify("+$months Months");
        return $d->format("Y-m-d");
    }

}
